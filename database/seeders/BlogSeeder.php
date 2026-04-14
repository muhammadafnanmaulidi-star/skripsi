<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User; // Assuming we need a user
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // Ensure there is at least one user to assign as author
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@utararestaurant.com',
                'password' => bcrypt('password'),
            ]);
        }

        $blogs = [
            [
                'title' => 'KOPI DIPAGI HARI',
                'slug' => 'kopi-dipagi-hari',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sed enim ut sem viverra aliquet eget sit amet. Ornare arcu dui vivamus arcu felis bibendum ut.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.

Punttitor rhoncus dolor purus non enim praesent elementum. Eget dolor marbi non arcu risus quis varius. Praesent id id consequat semper viverra nam libero. In iaculis quorn viverra orci sagittis eu. Tristique et nut eu feugiat scelerisque potenti urna mi. Tempus quam pellentesque nec nam aliquam sem et. Convallis.

Est pellentesque elit ullamcorper dignissim. Consectetur a erat nam at. Blandit libero volutpat sed cras ornare arcu. Iaculis urna id volutpat lacus laoreet. Tincidunt ornare massa eget egestas purus viverra accumsan in. Viverra ipsum nunc aliquet bibendum enim facilisis gravida neque.

Duis turpis massa sed elementum tempus egestas sed. Quam lacus suspendisse faucibus interdum posuere lorem ipsum. Vivamus ets nisi ultricies he quam eget sit. Risus commodo adipiscing at in tellus integer feugiat. Elementum eu facilisis sed odio marbi quis commodo. Aturpis cursus in hac habitasse.',
                'image' => '/blog1.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
            [
                'title' => 'Hope dies last',
                'slug' => 'hope-dies-last',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for Hope dies last...',
                'image' => '/blog2.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
            [
                'title' => 'The best art museums',
                'slug' => 'the-best-art-museums',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for The best art museums...',
                'image' => '/blog3.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
            [
                'title' => 'The devil is the details',
                'slug' => 'the-devil-is-the-details',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for The devil is the details...',
                'image' => '/blog4.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
             [
                'title' => 'An indestructible hope',
                'slug' => 'an-indestructible-hope',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for An indestructible hope...',
                'image' => '/blog5.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
            [
                'title' => 'Street art festival',
                'slug' => 'street-art-festival',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for Street art festival...',
                'image' => '/blog6.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ],
            [
                'title' => 'Through the eyes of street artists',
                'slug' => 'through-the-eyes-of-street-artists',
                'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Egestas dui id ornare arcu odio ut sem. Cras ornare arcu dui vivamus arcu felis bibendum ut. Porttitor leo a diam.',
                'content' => 'Full content for Through the eyes of street artists...',
                'image' => '/blog7.jpg',
                'user_id' => $user->id,
                'is_published' => true,
                'published_at' => Carbon::parse('2022-03-16'),
            ]
        ];

        foreach ($blogs as $blogData) {
            Blog::updateOrCreate(
                ['slug' => $blogData['slug']], // Check uniqueness by slug
                $blogData
            );
        }
    }
}
