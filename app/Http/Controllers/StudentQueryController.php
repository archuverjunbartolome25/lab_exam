<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentSkill;
use App\Models\StudentAffiliation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentQueryController extends Controller
{
    /**
     * Query students by skill
     */
    public function queryBySkill(Request $request)
    {
        $skill = $request->input('skill');
        
        $students = Student::whereHas('skills', function($query) use ($skill) {
            $query->where('name', 'LIKE', "%{$skill}%");
        })
        ->with(['skills', 'affiliations', 'academicHistory'])
        ->get()
        ->map(function ($student) {
            return [
                'id' => $student->stud_id,
                'name' => $student->fname . ' ' . $student->lname,
                'stud_num' => $student->stud_num,
                'program' => $student->program,
                'skills' => $student->skills->map(function($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'level' => $skill->level
                    ];
                }),
                'affiliations' => $student->affiliations->map(function($affiliation) {
                    return [
                        'id' => $affiliation->id,
                        'name' => $affiliation->name,
                        'type' => $affiliation->type
                    ];
                })
            ];
        });

        return Inertia::render('StudentQuery/Results', [
            'students' => $students,
            'query' => $skill,
            'queryType' => 'skill',
            'total' => $students->count()
        ]);
    }

    /**
     * Query students by affiliation/activity
     */
    public function queryByAffiliation(Request $request)
    {
        $affiliation = $request->input('affiliation');
        
        $students = Student::whereHas('affiliations', function($query) use ($affiliation) {
            $query->where('name', 'LIKE', "%{$affiliation}%");
        })
        ->with(['skills', 'affiliations', 'academicHistory'])
        ->get()
        ->map(function ($student) {
            return [
                'id' => $student->stud_id,
                'name' => $student->fname . ' ' . $student->lname,
                'stud_num' => $student->stud_num,
                'program' => $student->program,
                'skills' => $student->skills->map(function($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'level' => $skill->level
                    ];
                }),
                'affiliations' => $student->affiliations->map(function($aff) {
                    return [
                        'id' => $aff->id,
                        'name' => $aff->name,
                        'type' => $aff->type
                    ];
                })
            ];
        });

        return Inertia::render('StudentQuery/Results', [
            'students' => $students,
            'query' => $affiliation,
            'queryType' => 'affiliation',
            'total' => $students->count()
        ]);
    }

    /**
     * Advanced query with multiple filters
     */
    public function advancedQuery(Request $request)
    {
        $skill = $request->input('skill');
        $affiliation = $request->input('affiliation');
        
        $studentsQuery = Student::with(['skills', 'affiliations', 'academicHistory']);

        if ($skill) {
            $studentsQuery->whereHas('skills', function($query) use ($skill) {
                $query->where('name', 'LIKE', "%{$skill}%");
            });
        }

        if ($affiliation) {
            $studentsQuery->whereHas('affiliations', function($query) use ($affiliation) {
                $query->where('name', 'LIKE', "%{$affiliation}%");
            });
        }

        $students = $studentsQuery->get()
        ->map(function ($student) {
            return [
                'id' => $student->stud_id,
                'name' => $student->fname . ' ' . $student->lname,
                'stud_num' => $student->stud_num,
                'program' => $student->program,
                'skills' => $student->skills->map(function($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'level' => $skill->level
                    ];
                }),
                'affiliations' => $student->affiliations->map(function($aff) {
                    return [
                        'id' => $aff->id,
                        'name' => $aff->name,
                        'type' => $aff->type
                    ];
                })
            ];
        });

        return Inertia::render('StudentQuery/Results', [
            'students' => $students,
            'query' => $skill ?: $affiliation,
            'queryType' => 'advanced',
            'total' => $students->count(),
            'filters' => [
                'skill' => $skill,
                'affiliation' => $affiliation
            ]
        ]);
    }

    /**
     * General search method
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $students = Student::where(function($queryBuilder) use ($query) {
            $queryBuilder->where('fname', 'LIKE', "%{$query}%")
                      ->orWhere('lname', 'LIKE', "%{$query}%")
                      ->orWhere('stud_num', 'LIKE', "%{$query}%")
                      ->orWhere('program', 'LIKE', "%{$query}%");
        })
        ->with(['skills', 'affiliations', 'academicHistory'])
        ->limit(50)
        ->get()
        ->map(function ($student) {
            return [
                'id' => $student->stud_id,
                'name' => $student->fname . ' ' . $student->lname,
                'stud_num' => $student->stud_num,
                'program' => $student->program,
                'skills' => $student->skills->map(function($skill) {
                    return [
                        'id' => $skill->id,
                        'name' => $skill->name,
                        'level' => $skill->level
                    ];
                }),
                'affiliations' => $student->affiliations->map(function($affiliation) {
                    return [
                        'id' => $affiliation->id,
                        'name' => $affiliation->name,
                        'type' => $affiliation->type
                    ];
                })
            ];
        });

        return Inertia::render('StudentQuery/Results', [
            'students' => $students,
            'query' => $query,
            'queryType' => 'search',
            'total' => $students->count()
        ]);
    }
}
