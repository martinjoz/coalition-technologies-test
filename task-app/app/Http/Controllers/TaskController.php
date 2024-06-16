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
        $task->update([
            'name' => $request->name
        ]);

        return back();
    }

    public function destroy($id)
    {
        // Find the task to be deleted
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['status' => 'Task not found'], 404);
        }

        // Get the priority of the task being deleted
        $deletedTaskPriority = $task->priority;

        // Delete the task
        $task->delete();

        // Decrement the priorities of tasks with higher priority
        Task::where('priority', '>', $deletedTaskPriority)->decrement('priority');

        return response()->json(['status' => 'Task deleted successfully']);
    }

    public function sortTask(Request $request)
    {
        // Decode the JSON data to an array
        $tasks = $request->json()->all();

        // Loop through each task and access the id and name
        $index=0;
        foreach ($tasks as $task) {
            $taskId = $task['id'];
            
            // Update the task's priority based on the index
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
    
            // Increment the counter
            $index++;
        }
        return response()->json(['status' => 'task updated successfully']);
    }
}
