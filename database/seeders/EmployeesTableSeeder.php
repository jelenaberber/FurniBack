<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $employees = [
            [
                'name' => 'John Smith',
                'job' => 'CEO, Founder',
                'description' => 'John brings extensive leadership experience to our company, steering strategic decisions and fostering a culture of innovation. He is passionate about creating meaningful impact in the furniture industry, driving the team towards excellence.',
                'img' => 'person_1.jpg',
            ],
            [
                'name' => 'Michael Johnson',
                'job' => 'Operations Manager',
                'description' => 'Michael ensures seamless operations from production to delivery, optimizing efficiency and customer satisfaction. He enjoys the dynamic challenges of his role and contributing to the company\'s growth and success.',
                'img' => 'person_3.jpg',
            ],
            [
                'name' => 'Emily Brown',
                'job' => 'Chief Designer',
                'description' => 'Emily\'s creativity and attention to detail define our product aesthetics, ensuring each piece reflects elegance and functionality. She loves transforming ideas into beautiful designs that enhance customers\' living spaces.',
                'img' => 'person_4.jpg',
            ],
            [
                'name' => 'Jeremy Walker',
                'job' => 'Customer Relations Manager',
                'description' => 'Jeremy is dedicated to providing exceptional service and building strong relationships with our customers. He finds fulfillment in helping clients find the perfect furniture solutions for their homes, ensuring their satisfaction and loyalty.',
                'img' => 'person_2.jpg',
            ],
        ];

        // Insert data into the employees table
        DB::table('employees')->insert($employees);
    }
}
