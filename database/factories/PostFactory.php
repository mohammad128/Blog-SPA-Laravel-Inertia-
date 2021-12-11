<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {

        return [
            'title'             => $this->faker->text(rand(25,100)),
            'content'           => $this->faker->paragraph(rand(200,300)),
            'feature_image'     => "/uploads/images/foto".rand(1,6).".png",
            'user_id'           => 1
        ];
    }
}
