<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $services = [
            [
                'title' => 'Fast & Free Shipping',
                'img' => 'fa-solid fa-truck-fast',
                'description' => 'Enjoy speedy and complimentary shipping on all orders, ensuring your new furniture arrives at your doorstep quickly and without any additional cost.'
            ],
            [
                'title' => 'Easy to Shop',
                'img' => 'fa-solid fa-cart-shopping',
                'description' => 'Our user-friendly online store makes it simple and convenient to browse, select, and purchase your favorite pieces from the comfort of your home.'
            ],
            [
                'title' => '24/7 Support',
                'img' => 'fa-solid fa-phone',
                'description' => 'Our dedicated customer support team is available around the clock to assist you with any questions or concerns, providing help whenever you need it.'
            ],
            [
                'title' => 'Hassle Free Returns',
                'img' => 'fa-solid fa-arrow-right-arrow-left',
                'description' => 'We offer a straightforward return policy, making it easy and stress-free to return any items that don\'t meet your expectations.'
            ],
            [
                'title' => 'Handcrafted Excellence',
                'img' => 'fa-solid fa-hand-holding-heart',
                'description' => 'Each piece is meticulously handcrafted by skilled artisans, ensuring unique and high-quality furniture.'
            ],
            [
                'title' => 'Sustainable Materials',
                'img' => 'fa-solid fa-recycle',
                'description' => 'Made from eco-friendly, natural materials, our furniture promotes a healthier planet.'
            ],
            [
                'title' => 'Timeless Design',
                'img' => 'fa-solid fa-pencil',
                'description' => 'Our designs blend modern aesthetics with timeless elegance, suitable for any interior.'
            ],
            [
                'title' => 'Durability Guaranteed',
                'img' => 'fa-solid fa-award',
                'description' => 'Built to last, our furniture offers both style and longevity, providing lasting value for your home.'
            ]
        ];

        DB::table('services')->insert($services);
    }
}
