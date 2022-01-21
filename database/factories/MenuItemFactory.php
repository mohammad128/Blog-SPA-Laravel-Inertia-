<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MenuItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->unique()->word;
        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'menu_id' => Menu::all()->random(1)->first()->id,
        ];
    }
}
