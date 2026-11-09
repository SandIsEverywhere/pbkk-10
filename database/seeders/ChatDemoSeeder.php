<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use App\Models\ToolExecutionLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChatDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        $titles = ['Malware scan Q4', 'Port scan attempt', 'Log triage'];

        foreach ($titles as $title) {
            $session = ChatSession::create([
                'user_id' => $user->id,
                'title' => $title,
            ]);

            $messages = ChatMessage::factory()->count(5)->create([
                'chat_session_id' => $session->id,
            ]);

            // Attach 1-3 tool execution logs to each message (satisfying "at least 2 of those messages per session")
            foreach ($messages as $message) {
                $count = fake()->numberBetween(2, 3);
                $message->toolExecutionLogs()->saveMany(
                    ToolExecutionLog::factory()->count($count)->make([
                        'loggable_type' => ChatMessage::class,
                        'loggable_id' => $message->id,
                    ])
                );
            }
        }

        $this->seedAgentScanners();
    }

    private function seedAgentScanners(): void
    {
        $scannerModels = [
            SecurityScanner::factory()->create(['name' => 'port-scan-agent', 'status' => 'completed']),
            MalwareScanner::factory()->create(['name' => 'malware-agent', 'status' => 'completed']),
            LogAnalyzer::factory()->create(['name' => 'log-analyzer-agent', 'status' => 'completed']),
        ];

        foreach ($scannerModels as $scanner) {
            $scanner->toolExecutionLogs()->saveMany(
                ToolExecutionLog::factory()->count(3)->make()
            );
        }
    }
}
