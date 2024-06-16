<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        // Fetch all tasks ordered by priority
        $tasks = Task::orderBy('priority')->get();
        return view('tasks', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Get the highest current priority and increment it for the new task
        $priority = Task::max('priority') + 1;

        // Create the new task
        Task::create([
            'name' => $request->name,
            'priority' => $priority
        ]);

        // Redirect to the tasks index with a success message
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Update the task with new data
        $task->update([
            'name' => $request->name
        ]);

        // Redirect back with a success message
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        // Find the task to be deleted
        $task = Task::find($id);

        // Check if the task exists
        if (!$task) {
            return response()->json(['status' => 'Task not found'], 404);
        }

        // Get the priority of the task being deleted
        $deletedTaskPriority = $task->priority;

        // Delete the task
        $task->delete();

        // Decrement the priorities of tasks with higher priority
        Task::where('priority', '>', $deletedTaskPriority)->decrement('priority');

        // Return a JSON response indicating success
        return response()->json(['status' => 'Task deleted successfully']);
    }

    /**
     * Update the priorities of tasks based on the new order from the drag-and-drop operation.
     */
    public function sortTask(Request $request)
    {
        // Validate the incoming JSON structure
        $tasks = $request->validate([
            '*.id' => 'required|integer|exists:tasks,id',
            '*.name' => 'required|string'
        ]);

        // Loop through each task and update its priority
        foreach ($tasks as $index => $task) {
            $taskId = $task['id'];

            // Update the task's priority based on the index
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        // Return a JSON response indicating success
        return response()->json(['status' => 'Tasks updated successfully']);
    }
}
