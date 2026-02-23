<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Nette\Utils\Strings;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function doctorscount()
    {
        $users = User::where('major', '2')->paginate(10);
        return view('admin.staff.show', compact('users'));
    }
    /**
     * Display a listing of the resource.
     */
    public function doctorcount(String $name)
    {
        // Get visits grouped by horse and by month
        if (User::where('name', $name)->exists()) {
            $user = User::where('name', $name)->first();
            if ($user) {
                $allvisits = Visit::where('user_id', $user->id)
                    ->orderByDesc('created_at')
                    ->get();

                // Group visits by year and month for stats
                $yearlyStats = [];
                $monthlyStats = [];

                foreach ($allvisits->groupBy(function($item) { return $item->created_at->year; }) as $year => $visitsOfYear) {
                    $yearlyStats[$year] = [
                        'totalCases' => $visitsOfYear->pluck('visitdescs')->flatten()->count(),
                        'totalCasePrice' => $visitsOfYear->pluck('visitdescs')->flatten()->sum('caseprice'),
                        'totalVisitPrice' => $visitsOfYear->sum('visitprice'),
                        'totalDiscount' => $visitsOfYear->sum('discount'),
                        'totalPaid' => $visitsOfYear->sum('paid'),
                        'totalPrice' => $visitsOfYear->sum('totalprice') + $visitsOfYear->pluck('visitdescs')->flatten()->sum('caseprice'),
                        'visitsCount' => $visitsOfYear->count(),
                    ];
                    foreach ($visitsOfYear->groupBy(function($item) { return $item->created_at->month; }) as $month => $visitsOfMonth) {
                        $monthlyStats[$year][$month] = [
                            'totalCases' => $visitsOfMonth->pluck('visitdescs')->flatten()->count(),
                            'totalCasePrice' => $visitsOfMonth->pluck('visitdescs')->flatten()->sum('caseprice'),
                            'totalVisitPrice' => $visitsOfMonth->sum('visitprice'),
                            'totalDiscount' => $visitsOfMonth->sum('discount'),
                            'totalPaid' => $visitsOfMonth->sum('paid'),
                            'totalPrice' => $visitsOfMonth->sum('totalprice') + $visitsOfMonth->pluck('visitdescs')->flatten()->sum('caseprice'),
                            'visitsCount' => $visitsOfMonth->count(),
                        ];
                    }
                }

                // Group visits by year and month, then paginate the months
                $visitsByMonth = Visit::
                where('user_id', $user->id)
                ->whereHas('visitdescs')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count
                ')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->paginate(10);





        return view('admin.staff.details', compact(
            'user',
            'allvisits',
            'visitsByMonth',
            'yearlyStats',
            'monthlyStats'
        ));
            }
        } else {
            return redirect()->back()->with('status', 'This User Dosen`t exists');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('admin.staff.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();
        $input = $request->all();
        $user->create($input);
        return redirect()->back()->with('status', "Added Successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $input = $request->all();
        $user->update($input);
        return redirect()->back()->with('status', "Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('status', "Deleted Successfully");
    }
}

