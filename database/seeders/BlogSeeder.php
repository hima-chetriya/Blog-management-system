<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        Blog::create([
            'title' => 'Blog 1',
            'description' => 'Description 1',
            'image' => 'uploads/blog1.jpg',
            'user_id' => 1,
        ]);

        Blog::create([
            'title' => 'Blog 2',
            'description' => 'Description 2',
            'image' => 'uploads/blog2.jpg',
            'user_id' => 1,
        ]);

        Blog::create([
            'title' => 'Blog 3',
            'description' => 'Description 3',
            'image' => 'uploads/blog3.jpg',
            'user_id' => 1,
        ]);

        Blog::create([
            'title' => 'Blog 4',
            'description' => 'Description 4',
            'image' => 'uploads/blog4.jpg',
            'user_id' => 1,
        ]);

        Blog::create([
            'title' => 'Blog 5',
            'description' => 'Description 5',
            'image' => 'uploads/blog5.jpg',
            'user_id' => 1,
        ]);
    }
}
