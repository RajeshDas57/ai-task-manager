<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    // Page load
    public function index()
    {
        return view('tasks');
    }


    // সব task JSON দিবে
    public function apiIndex()
    {
        return response()->json(
            Task::latest()->get()
        );
    }


    // Add Task
    public function store(Request $request)
    {

        $task = Task::create([

            'title' => $request->title,

            'description' => $request->description,

            'priority' => $request->priority,

            'due_date' => $request->due_date,

        ]);


        return response()->json($task);
    }



    // Complete / Undo
    public function toggle(Task $task)
    {

        $task->is_completed = !$task->is_completed;

        $task->save();


        return response()->json($task);

    }



    // Delete
    public function destroy(Task $task)
    {

        $task->delete();


        return response()->json([
            'message'=>'Deleted'
        ]);

    }



    // Edit page (এখন লাগবে না, পরে দরকার হবে)
    public function edit(Task $task)
    {
        return view('edit', compact('task'));
    }



    // Update
    public function update(Request $request, Task $task)
    {

        $task->update([

            'title'=>$request->title,

            'description'=>$request->description,

            'priority'=>$request->priority,

            'due_date'=>$request->due_date,

        ]);


        return response()->json($task);

    }



    // AI Suggest
    public function suggest(Request $request)
    {

        return response()->json([

            'suggestion' =>
            "Break this task into smaller steps and complete it one by one."

        ]);

    }

}