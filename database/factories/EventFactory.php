<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(rand(4, 9));
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->sentence(10),
            'premium' => $this->faker->boolean(25),
            'starts_at' => $this->faker->dateTimeBetween('now', '+5 weeks'),
            'ends_at' => $this->faker->dateTimeBetween('+6 weeks', '+ 10 weeks')
        ];
    }
}
