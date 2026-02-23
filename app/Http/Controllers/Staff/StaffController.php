<?php

namespace App\Http\Controllers\staff;

use App\Models\Embryo;
use App\Models\FeedingBedding;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\Horse;
use App\Models\Note;
use App\Models\Pharmacy;
use App\Models\Stud;
use App\Models\Task;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        if (Auth::user()->major == 1 || Auth::user()->role_as !== 0 ) {
            return view('staff.accountantindex');
        } else {
            $finances = Financial::where('decamount', '>', 0)->get();
            $users = User::where('role_as', 2)->get();
            $horses = Horse::all();
            $studs = Stud::all();

            $doctors = User::where('major', 2)->orderByDesc('created_at')->get();

            $pharmacy = Pharmacy::where('unitqty', '!=', 0)->orderByDesc('created_at')->get();
            $embryos = Embryo::where('id', '!=', null)->where('status', 1)->orderByDesc('created_at')->get();

            $feedingbedings = FeedingBedding::orderBy('created_at', 'desc')->get();

            $tasks = Task::where('user_id' , Auth::id())->orderBy('created_at', 'desc')->paginate(10);
            $notes = Note::where('user_id' , Auth::id())->where('manager_id', null)->orderBy('created_at', 'desc')->paginate(10);
            return view('staff.index', compact('tasks', 'finances', 'notes','users', 'doctors', 'horses', 'pharmacy', 'embryos', 'feedingbedings' ,'studs'));
        }

    }
}
