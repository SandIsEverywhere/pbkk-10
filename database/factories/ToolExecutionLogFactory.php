<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ToolExecutionLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToolExecutionLog>
 */
class ToolExecutionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'loggable_type' => ChatMessage::class,
            'loggable_id' => ChatMessage::factory(),
            'tool_name' => fake()->randomElement(['search_web', 'run_python', 'read_file', 'send_email']),
            'arguments' => ['query' => fake()->words(3, true)],
            'result' => ['output' => fake()->sentence()],
            'status' => fake()->randomElement(['success', 'failed', 'pending']),
            'duration_ms' => fake()->numberBetween(50, 3000),
        ];
    }
}
