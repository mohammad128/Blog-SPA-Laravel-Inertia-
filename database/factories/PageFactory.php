<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(20, 40));
        $title = $this->faker->realText(50);
        $content = "<h1>{$title}</h1>";
        foreach ($paragraphs as $para) {
            $content .= "<p>{$para}</p>";
        }

        return [
            'title'             => $this->faker->text(rand(25,100)),
            'content'           => $content,//$this->faker->paragraph(rand(200,300)),
            'feature_image'     => "/uploads/images/foto".rand(1,6).".png",
            'user_id'           => 1,
            'draft'             => rand(0,1),
            'disable_comment'   => rand(0,1),
            'slug'              => Str::slug($title).'-'.rand(1,100),
        ];
    }
}
