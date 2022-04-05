<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'post_title' => $this->faker->text(50),
            'post_content'=> $this->faker->text(500),
            'post_excerpt' => $this->faker->text(200),
            'post_author' => $this->faker->text(50),
            'post_readmore' => $this->faker->text(200),
        ];
    }
}
