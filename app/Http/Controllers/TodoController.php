<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TodosExport;

class TodoController extends Controller
{
    public function store(TodoRequest $request)
    {
        try {
            $validatedData = $request->validated();
            
            $todo = Todo::create($validatedData);
            
            return response()->json([
                'success' => true,
                'message' => 'Todo successfully created',
                'data' => $todo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'an error occured',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateExcel(Request $request)
    {
        try {
            $query = Todo::query();

            if ($request->has('title')) {
                $query->where('title', 'LIKE', '%' . $request->title . '%');
            }

            if ($request->has('assignee')) {
                $assignees = explode(',', $request->assignee);
                $query->whereIn('assignee', $assignees);
            }

            if ($request->has('start') && $request->has('end')) {
                $query->whereBetween('due_date', [$request->start, $request->end]);
            }

            if ($request->has('min') && $request->has('max')) {
                $query->whereBetween('time_tracked', [$request->min, $request->max]);
            }

            if ($request->has('status')) {
                $statuses = explode(',', $request->status);
                $query->whereIn('status', $statuses);
            }

            if ($request->has('priority')) {
                $priorities = explode(',', $request->priority);
                $query->whereIn('priority', $priorities);
            }

            $todos = $query->get();

            return Excel::download(new TodosExport($todos), 'todo_report.xlsx');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'an error occured',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
