<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'user_id' => User::factory(),
            'description' => $this->faker->text,
            'status'  => '0',
            'started_at' => now()->format('Y-m-d H:i'),
            'ended_at' => now()->addWeek()->format('Y-m-d H:i'),
        ];
    }
}
