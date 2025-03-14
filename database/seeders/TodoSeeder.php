<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $todos = [
            [
                'title' => 'Reading',
                'assignee' => 'Muqsith',
                'due_date' => '2025-03-30',
                'time_tracked' => 4.5,
                'status' => 'in_progress',
                'priority' => 'low'
            ],
            [
                'title' => 'Meeting',
                'assignee' => 'Arsyad',
                'due_date' => '2025-04-15',
                'time_tracked' => 2.0,
                'status' => 'pending',
                'priority' => 'high'
            ],
            [
                'title' => 'Coding',
                'assignee' => 'Muhammad',
                'due_date' => '2025-04-01',
                'time_tracked' => 6.5,
                'status' => 'completed',
                'priority' => 'high'
            ],
            [
                'title' => 'Testing',
                'assignee' => 'Muqsith',
                'due_date' => '2025-04-10',
                'time_tracked' => 1.5,
                'status' => 'open',
                'priority' => 'medium'
            ],
        ];

        foreach ($todos as $todo) {
            Todo::create($todo);
        }
    }
}
