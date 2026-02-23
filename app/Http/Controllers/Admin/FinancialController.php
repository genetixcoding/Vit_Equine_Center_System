<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Financial;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function financialscount()
    {
        $allfinances = Financial::orderByDesc('created_at')->get();

        // Group finances by year and month, and calculate sums
        $financesByMonth = Financial::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count,
            SUM(amount) as totalAmount,
            SUM(decamount) as totalDecAmount
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.finance.countfinance', compact('allfinances', 'financesByMonth'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allfinances = Financial::all();
        $financesByMonth = Financial::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return view('admin.finance.index', compact('allfinances', 'financesByMonth'));
    }
    public function show(string $id)
    {
        $finance = Financial::where('id', $id)->first();
        if ($finance) {
            return view('admin.finance.details', compact('finance'));
        } else {
            return redirect()->back()->with('status', "This finance Doesn't exist");
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['decamount'] = $input['amount']; // Ensure decamount is set to amount
        Financial::create($input);
        return redirect()->back()->with('status', "Added Successfully");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $finance = Financial::find($id);
        $input = $request->all();
        $finance->update($input);
        return redirect()->back()->with('status', "Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        Financial::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }


}
