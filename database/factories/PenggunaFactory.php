<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenggunaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Pengguna::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nama_lengkap' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'), // Default password
        ];
    }
}
