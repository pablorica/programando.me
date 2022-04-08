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
            'post_title' => $this->faker->sentence(15),
            'post_content'=> $this->faker->paragraphs(15, true),
            'post_excerpt' => $this->faker->sentences(3, true),
            'post_author' => $this->faker->text(50),
            'post_readmore' => $this->faker->text(200),
            'post_category' => 1,
            'post_slug' => $this->faker->slug(4),
            'post_image' => "post.png",
            'post_author' => 1,
        ];
    }

    /**
     * Indicates the post is published.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'post_published' => true,
                'published_at' => now(),
            ];
        });
    }
}
