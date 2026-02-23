<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Financial;
use App\Models\Salary;
use App\Models\SalaryDesc;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class SalaryController extends Controller
{

    public function salarycount()
    {
        $allsalary = Salary::orderByDesc('created_at')->get();

        // Group salary by year and month, and calculate sums
        $salaryByMonth = Salary::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count,
            SUM(salaryamount) as totalSalaryAmount,
            SUM(decsalaryamount) as totalDecSalaryAmount
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.salary.countsalary', compact('allsalary', 'salaryByMonth'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allsalary = Salary::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $users = User::all();

        // Group salary by year and month, then paginate the months
        $salaryByMonth = Salary::
            selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('admin.salary.index', compact('allsalary', 'finances', 'users', 'salaryByMonth'));
    }


    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        if (Salary::where('id', $id)->exists()) {
            $salary = Salary::where('id', $id)->first();
                return view('admin.salary.details', compact('salary'));
        } else {
            return redirect()->back()->with('status', 'This Salary Doesn`t exist');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'salaryamount' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            // ✅ 2. نفذ داخل Transaction لتفادي أخطاء التزامن
            DB::transaction(function () use ($validated) {
                $salaryamount = $validated['salaryamount'] ?? 0;

            if (!empty($validated['finance_id'])) {
                $finance = Financial::lockForUpdate()->find($validated['finance_id']);
                if (!$finance) {
                    throw new \Exception('Finance not found.');
                }

                if ($finance->decamount < $validated['salaryamount']) {
                    throw new \Exception('Not enough finance available.');
                }

                $finance->decrement('decamount', $salaryamount);
            }
                $salary = new Salary();
                $salary->fill($validated);
                $salary->decsalaryamount = $salaryamount;
                $salary->save();
            });

            return redirect()->back()->with('status', "Salary Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {

        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'salaryamount' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'user_id' => 'required|exists:users,id',
        ]);

        // ✅ 2. Get salary record
        $salary = Salary::find($id);
        if (!$salary) {
            return redirect()->back()->with('status', "Record not found");
        }

        // ✅ 3. Get finance record
        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $salary, $finance) {
                $oldSalaryAmount = $salary->salaryamount ?? 0;
                $newSalaryAmount = $validated['salaryamount'];
                $salaryamountDiff = $newSalaryAmount - $oldSalaryAmount;

                // ✅ 4. Check balance if increasing
                if ($salaryamountDiff > 0 && $finance->decamount < $salaryamountDiff) {
                    throw new \Exception('Low balance in This finance.');
                }

                // ✅ 5. Adjust finance decamount
                if ($salaryamountDiff > 0) {
                    $finance->decrement('decamount', $salaryamountDiff);
                } elseif ($salaryamountDiff < 0) {
                    $finance->increment('decamount', abs($salaryamountDiff));
                }

                // ✅ 6. Update salary with only allowed fields
                $salary->fill($validated);
                $salary->salaryamount = $newSalaryAmount;
                $salary->decsalaryamount = $newSalaryAmount; // Correctly update decsalaryamount based on the difference
                $salary->update();

            });

            return redirect()->back()->with('status', "Salary Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        Salary::destroy($id);
        return redirect()->back()->with('status', "Salary has been deleted successfully.");
    }

    // // -------------------
    // // Salary Description
    // /**
    //  * Store a newly created resource in storage.
    //  */
    public function storesalarydesc(Request $request)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'salary_id' => 'required|exists:salaries,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $salary = Salary::lockForUpdate()->find($validated['salary_id']);
                if (!$salary) {
                    throw new \Exception('Salary not found.');
                }

                if ($salary->decsalaryamount < $validated['amount']) {
                    throw new \Exception('Not enough salary available.');
                }

                $salary->decrement('decsalaryamount', $validated['amount']);

                $salaryDesc = new SalaryDesc();
                $salaryDesc->fill($validated);
                $salaryDesc->save();
            });

            return redirect()->back()->with('status', "Salary Expenses Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
   public function updatesalarydesc(Request $request, String $id)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'salary_id' => 'required|exists:salaries,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                $salarydesc = SalaryDesc::find($id); // ✅ استدعاء جوا الترانزاكشن
                if (!$salarydesc) {
                    throw new \Exception("SalaryDesc not found");
                }

                $salary = Salary::lockForUpdate()->find($validated['salary_id']);
                if (!$salary) {
                    throw new \Exception('Salary not found.');
                }

                $oldAmount = $salarydesc->amount ?? 0;
                $newAmount = $validated['amount'];
                $amountDiff = $newAmount - $oldAmount;

                // تحقق من الرصيد قبل الزيادة
                if ($amountDiff > 0 && $salary->decsalaryamount < $amountDiff) {
                    throw new \Exception('Not enough salary available.');
                }

                // تعديل الرصيد
                if ($amountDiff > 0) {
                    $salary->decrement('decsalaryamount', $amountDiff);
                } elseif ($amountDiff < 0) {
                    $salary->increment('decsalaryamount', abs($amountDiff));
                }

                // تحديث بيانات الخصم
                $salarydesc->fill($validated);
                $salarydesc->update();
            });

            return redirect()->back()->with('status', "Salary Expenses Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroysalarydesc(String $id)
    {
        SalaryDesc::destroy($id);
        return redirect()->back()->with('status', "Salary has been deleted successfully.");
    }
}
