<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'header_logo' => 'My Blog',
            'footer_logo' => 'My Blog',
            'footer_desc' => $this->faker->paragraph(),
            'email' => $this->faker->email(),
            'phone' => '5358222502',
            'address' => 'bursa',
            'facebook' => 'facebook',
            'instagram' => 'instagram',
            'youtube' => 'youtube',
            'about_title' => $this->faker->sentence(),
            'about_desc' => $this->faker->paragraph()
        ];
    }
}
