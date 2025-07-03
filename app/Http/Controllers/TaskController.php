<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('completed', false)->orderBy('priority', 'desc')->orderBy('due_date')->get();
        return view('tasks.index', compact('tasks'));
    }
    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);
        Task::create($request->all());
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.'); 

    }
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);
        
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
            'priority' => $request->input('priority')
        ]); 
                return redirect()->route('tasks.index')->with('success', 'Task updated successfully.'); 
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
    
    public function complete(Task $task){
        $task->completed = true;
        $task->completed_at = now();
        $task->save();
        return redirect()->route('tasks.index')->with('success', 'Task marked as completed.');
    }
    public function showCompleted(){
        $completedTasks = Task::where('completed', true)->orderBy('completed_at', 'desc')->get();
        return view('taskshow', compact('completedTasks'));
    }
}
