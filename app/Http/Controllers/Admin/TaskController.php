<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\Task;
use App\Models\TaskDesc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dailytasks()
    {
        $users = User::all();
        $horses = Horse::all();

        // Group all tasks by day
        $tasksByDay = Task::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(10);
        $alltasks = Task::orderBy('created_at', 'desc')->get();

        return view('admin.tasks.dailytasks', compact( 'users', 'horses', 'tasksByDay', 'alltasks'));
    }
    public function index()
    {
        $users = User::all();
        $horses = Horse::all();

        // Group all tasks by year and month
        $tasksByMonth = Task::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        $alltasks = Task::orderBy('created_at', 'desc')->get();

        return view('admin.tasks.index', compact( 'users', 'horses', 'tasksByMonth', 'alltasks'));
    }
    /**
     * Display a listing of the resource.
     */
    public function completetask()
    {
        $users = User::all();
        $alltasks = Task::all();

        // Group completed tasks by year and month
        $tasksByMonth = Task::whereHas('taskdesc', function($q) {
                $q->where('status', 1);
            })
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.tasks.completetask', compact('alltasks', 'users', 'tasksByMonth'));
    }
    /**
     * Display a listing of the resource.
     */
    public function mytask(string $id)
    {
        $tasks = Task::where('user_id' , Auth::id())->orderBy('created_at', 'desc')->paginate(10);;
        return view('admin.tasks.mytask', compact('tasks'));
    }
     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $horses = Horse::all();
        return view('admin.tasks.add', compact('users', 'horses'));
    }
    /**
     * Store a newly created resource in  storage.
     */
    public function store(Request $request)
    {
        $rules = [ "user_id" => "required", "taskdesc.*" => "required" ];

        foreach($request->taskdesc as $key => $value) {
            $rules["taskdesc.{$key}.task"] = 'required';
        }
        $request->validate($rules);

        $task = Task::create(["user_id" => $request->user_id]);
        foreach($request->taskdesc as $key => $value) {
            $task->taskdesc()->create($value);
        }
        return redirect()->back()->with('status' , 'Created successfully !!');
    }

    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        $input = $request->all();
        $task->update($input);
        return redirect()->back()->with('status', "Member Changed Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            Task::destroy($id);
            return redirect()->back()->with('status', "Task Deleted Successfully");
    }

    // For Edit and Update and Delete Tasks From Task Description

    public function edittaskdesc(string $id)
    {
        $taskdesc = TaskDesc::find($id);
        return view('admin.tasks.edit', compact('taskdesc'));
    }
    public function updatetaskdesc(Request $request, string $id)
    {
        $taskdesc = Taskdesc::find($id);
        $input = $request->all();
        $input['status'] = $request->has('status') ? 1 : 0; // Convert checkbox value to integer
        $taskdesc->update($input);
        return redirect()->back()->with('status', "Task Description Updated Successfully");
    }
    public function destroytaskdesc(string $id)
    {
        Taskdesc::destroy($id);
        return redirect()->back()->with('status', "Task Description Deleted Successfully");
    }

}
