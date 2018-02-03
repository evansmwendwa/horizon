<?php

use Illuminate\Database\Seeder;
use App\Photo;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Photo::truncate();

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            Photo::create([
                'tracking_code' => $faker->uuid,
                'color' => $faker->hexcolor,
                'creation_date' => $faker->dateTime,
                'width' => $faker->randomNumber,
                'height' => $faker->randomNumber,
                'likes' => $faker->randomNumber,
                'description' => $faker->sentence,
                'thumbnail' => $faker->imageUrl,
                'small' => $faker->imageUrl,
                'regular' => $faker->imageUrl,
                'full' => $faker->imageUrl,
                'raw' => $faker->imageUrl,
                'user_id' => $faker->uuid,
                'object' => $faker->paragraph,
                'classified' => $faker->boolean,
                'tags' => $faker->paragraph,
                'classified_date' => $faker->dateTime,
            ]);
        }
    }
}
