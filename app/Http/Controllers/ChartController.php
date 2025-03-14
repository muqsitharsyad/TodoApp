<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function getChartData(Request $request)
    {
        $validator = validator($request->all(), [
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error validation',
                'error' => $validator->errors()->first()
            ], 422);
        }

        $type = $request->query('type');

        switch ($type) {
            case 'status':
                return $this->getStatusSummary();
            case 'priority':
                return $this->getPrioritySummary();
            case 'assignee':
                return $this->getAssigneeSummary();
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Error validation',
                    'error' => 'The type must be one of: status, priority, assignee'
                ], 400);
        }
    }

    private function getStatusSummary()
    {
        $statusCounts = Todo::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allStatuses = ['pending', 'open', 'in_progress', 'completed'];
        $statusSummary = [];

        foreach ($allStatuses as $status) {
            $statusSummary[$status] = $statusCounts[$status] ?? 0;
        }

        return response()->json([
            'status_summary' => $statusSummary
        ]);
    }

    private function getPrioritySummary()
    {
        $priorityCounts = Todo::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $allPriorities = ['low', 'medium', 'high'];
        $prioritySummary = [];

        foreach ($allPriorities as $priority) {
            $prioritySummary[$priority] = $priorityCounts[$priority] ?? 0;
        }

        return response()->json([
            'priority_summary' => $prioritySummary
        ]);
    }

    private function getAssigneeSummary()
    {
        $assignees = Todo::whereNotNull('assignee')->distinct('assignee')->pluck('assignee');
        $assigneeSummary = [];

        foreach ($assignees as $assignee) {
            
            if (!$assignee) continue;
            
            $totalTodos = Todo::where('assignee', $assignee)->count();
            
            $totalPendingTodos = Todo::where('assignee', $assignee)
                ->where('status', 'pending')
                ->count();
            
            $totalTimeTrackedCompletedTodos = Todo::where('assignee', $assignee)
                ->where('status', 'completed')
                ->sum('time_tracked');
            
            $assigneeSummary[$assignee] = [
                'total_todos' => $totalTodos,
                'total_pending_todos' => $totalPendingTodos,
                'total_timetracked_completed_todos' => $totalTimeTrackedCompletedTodos
            ];
        }

        return response()->json([
            'assignee_summary' => $assigneeSummary
        ]);
    }
}
