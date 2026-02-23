<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Breeding;
use App\Models\Embryo;
use App\Models\Horse;
use App\Models\Stud;
use App\Models\Task;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $countstud = Stud::count();
        $counthorse = Horse::count();
        $countbreeding = Breeding::count();
        $countembryo = Embryo::count();
        $countvisit = Visit::count();
        $taskscount = Task::count();
        $clintscount = User::where('role_as' , '0')->count();

        $monthvisitscount = Visit::whereMonth('created_at', date('m'))->count();
        $lmonthvisitscount = Visit::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $monthvisits = Visit::whereMonth('created_at', date('m'))->orderBy('created_at', 'desc')->paginate(5);
        $lmonthvisits = Visit::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->orderBy('created_at', 'desc')->paginate(5);
        return view('admin.index',compact(
                'countstud',
                'counthorse',
                'countbreeding',
                'countembryo',
                'countvisit',
                'taskscount',
                'clintscount',
                'monthvisits',
                'lmonthvisits',
                'monthvisitscount',
                'lmonthvisitscount',
            )
        );
    }

}
