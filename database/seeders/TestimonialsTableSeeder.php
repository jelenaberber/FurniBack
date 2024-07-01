<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $testimonials = [
            [
                'name' => 'Maria Jones',
                'img' => 'person-1.png',
                'position' => 'CEO, Co-Founder, XYZ Inc.',
                'testimony' => "I couldn't be happier with the furniture I purchased here. The craftsmanship is outstanding, and each piece exudes both comfort and style. It has truly transformed my living space into a cozy yet elegant haven. Highly recommended for anyone looking for quality furniture that stands the test of time.",
            ],
            [
                'name' => 'Ana Jones',
                'img' => 'person-1.png',
                'position' => 'CEO, Co-Founder, XYZ Inc.',
                'testimony' => "Our experience with this furniture store was exceptional. From the moment we walked in, the staff was knowledgeable and helpful, guiding us to find pieces that perfectly complemented our home decor. The quality is superb, and every detail is crafted with precision. It's made our home feel inviting and sophisticated.",
            ],
            [
                'name' => 'Chris Jones',
                'img' => 'person-1.png',
                'position' => 'CEO, Co-Founder, XYZ Inc.',
                'testimony' => "The furniture we bought here exceeded our expectations in every way. Not only is it visually stunning, but it's also incredibly durable and comfortable. The design effortlessly blends modern aesthetics with practical functionality, making our living areas both stylish and user-friendly. This store is definitely our go-to for future furniture needs.",
            ],
        ];

        // Insert data into the testimonials table
        DB::table('testimonials')->insert($testimonials);
    }
}
