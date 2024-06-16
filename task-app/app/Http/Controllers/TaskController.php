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
        // Decode the JSON data to an array
        $tasks = $request->json()->all();

        // Loop through each task and access the id and name
        $index=0;
        foreach ($tasks as $task) {
            $taskId = $task['id'];
            
            // Update the task's priority based on the index
            try {
                Task::where('id', $taskId)->update(['priority1' => $index + 1]);
                return response()->json(['status' => 'success','message'=>'Tasks updated successfully!']);
            } catch (\Throwable $th) {
                return response()->json(['status' => 'error','message'=>'Error updating tasks. Please try again later.','error'=>$th->getMessage()]);
            }
    
            // Increment the counter
            $index++;
        }
    }
}
