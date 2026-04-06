<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use App\Models\Student;

class MedicalRecordsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing medical records
        MedicalRecord::truncate();
        
        // Get all students
        $students = Student::all();
        
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $allergies = ['None', 'Peanuts', 'Shellfish', 'Dairy', 'Eggs', 'Soy', 'Wheat', 'Latex', 'Penicillin', 'Aspirin'];
        $medications = ['None', 'Insulin', 'Lisinopril', 'Metformin', 'Albuterol', 'Ibuprofen', 'Acetaminophen', 'Vitamin D', 'Iron supplements'];
        $conditions = ['None', 'Asthma', 'Diabetes Type 1', 'Diabetes Type 2', 'Hypertension', 'Migraines', 'Epilepsy', 'ADHD'];
        $relationships = ['Parent', 'Guardian', 'Sibling', 'Spouse', 'Grandparent', 'Aunt', 'Uncle', 'Friend'];
        $insuranceProviders = ['PhilHealth', 'Maxicare', 'MediCard', 'Intellicare', 'Asianlife', 'Caritas Health Shield', 'Insular Health'];
        
        foreach ($students as $student) {
            MedicalRecord::create([
                'stud_id' => $student->stud_id,
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'allergies' => $allergies[array_rand($allergies)],
                'medications' => $medications[array_rand($medications)],
                'medical_conditions' => $conditions[array_rand($conditions)],
                'emergency_contact_name' => $this->generateRandomName(),
                'emergency_contact_phone' => '09' . rand(10, 99) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'emergency_contact_relationship' => $relationships[array_rand($relationships)],
                'medical_history' => $this->generateMedicalHistory(),
                'physician_name' => 'Dr. ' . $this->generateRandomName(),
                'physician_phone' => '09' . rand(10, 99) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
                'last_checkup' => now()->subDays(rand(30, 365)),
                'insurance_provider' => $insuranceProviders[array_rand($insuranceProviders)],
                'insurance_policy_number' => 'POL-' . strtoupper(substr(md5($student->stud_id), 0, 8)),
            ]);
            
            $this->command->info("Created medical record for {$student->fname} {$student->lname}");
        }
        
        $this->command->info("Successfully created medical records for all students");
    }
    
    private function generateRandomName(): string
    {
        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Carlos', 'Sofia', 'Miguel', 'Isabella', 'Roberto', 'Carmela'];
        $lastNames = ['Santos', 'Reyes', 'Cruz', 'Garcia', 'Mendoza', 'Tan', 'Lim', 'Castillo', 'Martinez', 'Ramos'];
        
        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
    
    private function generateMedicalHistory(): string
    {
        $histories = [
            'No significant medical history',
            'Childhood asthma, currently controlled',
            'Appendectomy at age 15',
            'Tonsillectomy at age 8',
            'Broken arm at age 12, fully recovered',
            'Chickenpox at age 6',
            'No chronic conditions, occasional seasonal allergies',
            'Gallbladder removal at age 25',
            'Knee surgery at age 18, fully recovered'
        ];
        
        return $histories[array_rand($histories)];
    }
}
