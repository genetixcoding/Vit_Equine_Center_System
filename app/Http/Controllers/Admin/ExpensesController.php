<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Expenses;
use App\Models\Financial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{


     public function expensescount()
    {
        $allexpenses = Expenses::orderByDesc('created_at')->get();

        // Group expenses by year and month, and calculate sums
        $expensesByMonth = Expenses::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count,
            SUM(cost) as totalCost
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.expenses.countexpenses', compact('allexpenses', 'expensesByMonth'));
    }

    // * Note: expensesByMonth is not used here; it is only used in index/expensescount for reporting.
    public function index()
    {
        $allexpenses = Expenses::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $expensesByMonth = Expenses::
            selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.expenses.index', compact('expensesByMonth', 'allexpenses', 'finances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'cost' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'item' => 'required|string',
            // Add other validation rules as needed
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $finance = Financial::lockForUpdate()->find($validated['finance_id']);

                if (!$finance) {
                    throw new \Exception('Finance not found.');
                }

                if ($finance->decamount < $validated['cost']) {
                    throw new \Exception('NoT Enough finance.');
                }

                $finance->decrement('decamount', $validated['cost']);

                $expense = new Expenses();
                $expense->fill($validated);
                $expense->save();
            });

            return redirect()->back()->with('status', "Expense Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate input
        $validated = $request->validate([
            'cost' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'item' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            // Add other validation rules as needed
        ]);

        $expense = Expenses::find($id);
        if (!$expense) {
            return redirect()->back()->with('status', "Record not found");
        }

        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $expense, $finance) {
                $oldCost = $expense->cost ?? 0;
                $newCost = $validated['cost'];
                $costDiff = $newCost - $oldCost;

                if ($costDiff > 0 && $finance->decamount < $costDiff) {
                    throw new \Exception('Low balance in This finance.');
                }

                if ($costDiff > 0) {
                    $finance->decrement('decamount', $costDiff);
                } elseif ($costDiff < 0) {
                    $finance->increment('decamount', abs($costDiff));
                }

                $expense->fill($validated);
                $expense->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        Expenses::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }

}
