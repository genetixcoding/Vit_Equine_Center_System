<?php

namespace App\Http\Controllers;

use App\Models\ExternalInvoice;
use App\Models\Horse;
use App\Models\Stud;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitDesc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index(){
        $user = Auth::user();
        $stud = $user->stud;
        if ($stud) {
            $horse = $stud->horse()->paginate(10); // Use horse() to get the query builder
            return view('home', compact('user', 'stud', 'horse'));
        } else {
            return redirect()->back()->with('status', 'Not Allowed Data.');
        }
    }

    public function show($stud_name, $item_name)
    {
        if (Stud::where('name', $stud_name)->exists()) {
            if ($horse = Horse::where('name', $item_name)->exists()) {
                $horse = Horse::where('name', $item_name)->first();
                $horsevisits = VisitDesc::where('horse_id', $horse->id)->orderBy('created_at', 'desc')->get();
                return view('user.horseview', compact('horse','horsevisits'));
            } else {
                return redirect()->back()->with('status', 'This Horse Dosen`t exists');
            }
        } else {
            return redirect()->back()->with('status', 'This Horse Dosen`t exists');
        }
    }
    public function visittable(string $id)
    {
        if (Stud::where('id', $id)->exists()) {
            $stud = Stud::where('id', $id)->first();
            $visits = Visit::where('stud_id', $stud->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('user.visittable', compact('stud', 'visits'));
        } else {
            return redirect()->back()->with('status', "Stud Not Found");
        }
    }

    public function invoicetable(string $id)
    {
        if (Stud::where('id', $id)->exists()) {
            $stud = Stud::where('id', $id)->first();
            $externalmedinvoices = ExternalInvoice::where('stud_id', $stud->id)
                ->whereHas('medexternalinvoices', function ($query) {
                    $query;
                })->orderBy('created_at', 'desc')->paginate(5);

            $externalsupinvoices = ExternalInvoice::where('stud_id', $stud->id)
                ->whereHas('supexternalinvoices', function ($query) {
                    $query;
                })
                ->orderBy('created_at', 'desc')
                ->paginate(5);
            return view('user.details', compact('stud', 'externalmedinvoices', 'externalsupinvoices'));
        }
        else
        {
            return redirect()->back()->with('status', "Stud Not Found");
        }
    }
}
