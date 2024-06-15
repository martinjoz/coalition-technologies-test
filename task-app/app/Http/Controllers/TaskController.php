<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('priority')->get();
        return view('tasks', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $priority = Task::max('priority') + 1;

        Task::create([
            'name' => $request->name,
            'priority' => $priority
        ]);

        return redirect()->route('tasks.index');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $task->update([
            'name' => $request->name
        ]);

        return redirect()->route('tasks');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks');
    }

    public function sortTask(Request $request)
    {
        $tasks = $request->tasks;

        foreach ($tasks as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}
