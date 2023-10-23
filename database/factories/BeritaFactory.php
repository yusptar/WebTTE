<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'judul' => $this->faker->sentence(5),
            'isi' => $this->faker->paragraph(5),
            'views' => $this->faker->numberBetween(0, 100),
            'slug' => $this->faker->slug(5),
        ];
    }
}