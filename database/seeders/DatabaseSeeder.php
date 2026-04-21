<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CreateTestUsersSeeder::class,
            CoursesSeeder::class,
            SectionsSeeder::class,
            AcademicTablesSeeder::class,
            StudentProfilingSeeder::class,
            AdditionalStudentsSeeder::class,
            IrregularStudentsSeeder::class,
            MedicalRecordsSeeder::class,
        ]);
    }
}
