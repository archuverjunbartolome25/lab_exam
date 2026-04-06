<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAffiliation;
use App\Models\StudentSkill;
use App\Models\StudentViolation;
use App\Models\StudentAchievement;
use App\Models\AcademicHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Student::query()
            ->with(['affiliations', 'skills', 'violations', 'achievements', 'academicHistory'])
            ->withCount([
                'violations as active_violations' => function ($query) {
                    $query->where('status', 'pending');
                }
            ]);

        // Apply filters
        if ($request->filled('program')) {
            $query->where('program', $request->input('program'));
        }

        if ($request->filled('standing')) {
            $query->where('standing', $request->input('standing'));
        }

        if ($request->filled('skills')) {
            $skill = $request->input('skills');
            $query->whereHas('skills', function ($q) use ($skill) {
                $q->where('name', 'like', "%{$skill}%")
                  ->orWhere('category', 'like', "%{$skill}%");
            });
        }

        if ($request->filled('affiliations')) {
            $affiliation = $request->input('affiliations');
            $query->whereHas('affiliations', function ($q) use ($affiliation) {
                $q->where('name', 'like', "%{$affiliation}%")
                  ->orWhere('type', 'like', "%{$affiliation}%");
            });
        }

        if ($request->input('violations') === 'none') {
            $query->whereDoesntHave('violations', function ($q) {
                $q->where('status', 'pending');
            });
        } elseif ($request->input('violations') === 'has') {
            $query->whereHas('violations', function ($q) {
                $q->where('status', 'pending');
            });
        }

        $students = $query->get()->map(function ($student) {
            return [
                'stud_id' => $student->stud_id,
                'stud_num' => $student->stud_num,
                'fname' => $student->fname,
                'mname' => $student->mname,
                'lname' => $student->lname,
                'ext' => $student->ext,
                'program' => $student->program,
                'standing' => $student->standing,
                'current_gpa' => $student->current_gpa,
                'total_credits' => $student->total_credits,
                'active_violations' => $student->active_violations,
                'affiliations' => $student->affiliations->map(function ($aff) {
                    return [
                        'id' => $aff->id,
                        'type' => $aff->type,
                        'name' => $aff->name,
                        'role' => $aff->role,
                    ];
                })->toArray(),
                'skills' => $student->skills->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'category' => $skill->category,
                        'name' => $skill->name,
                        'level' => $skill->level,
                    ];
                })->toArray(),
            ];
        });

        return Inertia::render('Student/Index', [
            'students' => $students,
            'filters' => $request->only(['program', 'standing', 'skills', 'affiliations', 'violations'])
        ]);
    }

    public function show(Student $student): Response
    {
        $student->load([
            'affiliations',
            'skills',
            'violations',
            'achievements',
            'academicHistory'
        ]);

        return Inertia::render('Student/Profile', [
            'student' => [
                'stud_id' => $student->stud_id,
                'stud_num' => $student->stud_num,
                'fname' => $student->fname,
                'mname' => $student->mname,
                'lname' => $student->lname,
                'ext' => $student->ext,
                'program' => $student->program,
                'standing' => $student->standing,
                'email' => $student->email,
                'contact_num' => $student->contact_num,
                'affiliations' => $student->affiliations->map(function ($aff) {
                    return [
                        'id' => $aff->id,
                        'type' => $aff->type,
                        'name' => $aff->name,
                        'role' => $aff->role,
                        'start_date' => $aff->start_date?->format('M j, Y'),
                        'end_date' => $aff->end_date?->format('M j, Y'),
                        'description' => $aff->description,
                    ];
                })->toArray(),
                'skills' => $student->skills->map(function ($skill) {
                    return [
                        'id' => $skill->id,
                        'category' => $skill->category,
                        'name' => $skill->name,
                        'level' => $skill->level,
                        'description' => $skill->description,
                        'acquired_date' => $skill->acquired_date?->format('M j, Y'),
                        'certificate' => $skill->certificate,
                    ];
                })->toArray(),
                'violations' => $student->violations->map(function ($violation) {
                    return [
                        'id' => $violation->id,
                        'type' => $violation->type,
                        'severity' => $violation->severity,
                        'description' => $violation->description,
                        'violation_date' => $violation->violation_date->format('M j, Y'),
                        'status' => $violation->status,
                        'sanction' => $violation->sanction,
                    ];
                })->toArray(),
                'achievements' => $student->achievements->map(function ($achievement) {
                    return [
                        'id' => $achievement->id,
                        'type' => $achievement->type,
                        'title' => $achievement->title,
                        'description' => $achievement->description,
                        'achievement_date' => $achievement->achievement_date->format('M j, Y'),
                        'level' => $achievement->level,
                        'awarder' => $achievement->awarder,
                    ];
                })->toArray(),
                'academic_history' => $student->academicHistory->map(function ($history) {
                    return [
                        'id' => $history->id,
                        'course_code' => $history->course_code,
                        'course_name' => $history->course_name,
                        'semester' => $history->semester,
                        'year' => $history->year,
                        'grade' => $history->grade,
                        'credits' => $history->credits,
                        'grade_letter' => $history->grade_letter,
                        'status' => $history->status,
                    ];
                })->toArray(),
            ]
        ]);
    }

    public function query(): Response
    {
        // Get saved queries for the current user
        $savedQueries = []; // TODO: Implement when user system is ready
        
        // Get recent query history
        $recentQueries = []; // TODO: Implement query history

        return Inertia::render('Student/Query', [
            'savedQueries' => $savedQueries,
            'recentQueries' => $recentQueries,
        ]);
    }

    public function runQuery(Request $request): Response
    {
        $rules = $request->input('rules', []);
        $query = Student::query()
            ->with(['affiliations', 'skills', 'violations', 'achievements', 'academicHistory'])
            ->withCount([
                'violations as active_violations' => function ($query) {
                    $query->where('status', 'pending');
                }
            ]);

        foreach ($rules as $rule) {
            $field = $rule['field'];
            $operator = $rule['operator'];
            $value = $rule['value'];
            $logic = $rule['logic'] ?? 'and';

            $query->where(function ($q) use ($field, $operator, $value, $logic) {
                switch ($field) {
                    case 'program':
                        return $this->applyOperator($q, 'program', $operator, $value, $logic);
                    case 'standing':
                        return $this->applyOperator($q, 'standing', $operator, $value, $logic);
                    case 'current_gpa':
                        return $this->applyGpaOperator($q, $operator, $value, $logic);
                    case 'total_credits':
                        return $this->applyCreditsOperator($q, $operator, $value, $logic);
                    case 'active_violations':
                        return $this->applyViolationsOperator($q, $operator, $value, $logic);
                    case 'skills.name':
                        return $this->applySkillsOperator($q, $operator, $value, $logic);
                    case 'affiliations.name':
                        return $this->applyAffiliationsOperator($q, $operator, $value, $logic);
                    default:
                        return $q;
                }
            }, null, $logic === 'or');
        }

        $students = $query->get()->map(function ($student) {
            return [
                'stud_id' => $student->stud_id,
                'stud_num' => $student->stud_num,
                'fname' => $student->fname,
                'lname' => $student->lname,
                'program' => $student->program,
                'current_gpa' => $student->current_gpa,
                'total_credits' => $student->total_credits,
                'active_violations' => $student->active_violations,
            ];
        })->toArray();

        // Save query to history (TODO: implement when user system is ready)

        return Inertia::back()->with('results', $students);
    }

    private function applyOperator($query, $field, $operator, $value, $logic)
    {
        switch ($operator) {
            case 'equals':
                return $query->where($field, $value);
            case 'not_equals':
                return $query->where($field, '!=', $value);
            case 'greater_than':
                return $query->where($field, '>', $value);
            case 'less_than':
                return $query->where($field, '<', $value);
            case 'greater_equal':
                return $query->where($field, '>=', $value);
            case 'less_equal':
                return $query->where($field, '<=', $value);
            case 'contains':
                return $query->where($field, 'like', "%{$value}%");
            case 'not_contains':
                return $query->where($field, 'not like', "%{$value}%");
            case 'is_null':
                return $query->whereNull($field);
            case 'is_not_null':
                return $query->whereNotNull($field);
            default:
                return $query;
        }
    }

    private function applyGpaOperator($query, $operator, $value, $logic)
    {
        // GPA is a computed attribute, so we need to use a subquery
        return $query->whereHas('academicHistory', function ($q) use ($operator, $value) {
            $q->selectRaw('AVG(grade) as gpa')
              ->where('status', 'completed')
              ->havingRaw('AVG(grade) ' . $this->getSqlOperator($operator) . ' ?', [$value]);
        });
    }

    private function applyCreditsOperator($query, $operator, $value, $logic)
    {
        return $query->whereHas('academicHistory', function ($q) use ($operator, $value) {
            $q->selectRaw('SUM(credits) as total_credits')
              ->where('status', 'completed')
              ->havingRaw('SUM(credits) ' . $this->getSqlOperator($operator) . ' ?', [$value]);
        });
    }

    private function applyViolationsOperator($query, $operator, $value, $logic)
    {
        return $query->whereHas('violations', function ($q) use ($operator, $value) {
            if ($operator === 'equals') {
                return $q->where('status', 'pending')->havingRaw('COUNT(*) = ?', [$value]);
            }
            return $q->where('status', 'pending')->havingRaw('COUNT(*) ' . $this->getSqlOperator($operator) . ' ?', [$value]);
        });
    }

    private function applySkillsOperator($query, $operator, $value, $logic)
    {
        return $query->whereHas('skills', function ($q) use ($operator, $value) {
            switch ($operator) {
                case 'contains':
                    return $q->where('name', 'like', "%{$value}%")
                           ->orWhere('category', 'like', "%{$value}%");
                default:
                    return $q->where('name', $this->getSqlOperator($operator), $value);
            }
        });
    }

    private function applyAffiliationsOperator($query, $operator, $value, $logic)
    {
        return $query->whereHas('affiliations', function ($q) use ($operator, $value) {
            switch ($operator) {
                case 'contains':
                    return $q->where('name', 'like', "%{$value}%")
                           ->orWhere('type', 'like', "%{$value}%");
                default:
                    return $q->where('name', $this->getSqlOperator($operator), $value);
            }
        });
    }

    private function getSqlOperator($operator)
    {
        $operators = [
            'equals' => '=',
            'not_equals' => '!=',
            'greater_than' => '>',
            'less_than' => '<',
            'greater_equal' => '>=',
            'less_equal' => '<=',
        ];
        return $operators[$operator] ?? '=';
    }
}
