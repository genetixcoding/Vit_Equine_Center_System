<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Breeding;
use App\Models\Embryo;
use App\Models\Financial;
use App\Models\Horse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmbryoController extends Controller
{
    /**
     * Display a listing of the resource.
     */


     public function countembryo()
    {
        $allembryos = Embryo::orderByDesc('created_at')->get();

         // Group embryos by year and month, and calculate sums
        $embryosByMonth = Embryo::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count,
            SUM(cost) as cost,
            SUM(paid) as paid
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.embryos.countembryo', compact('allembryos', 'embryosByMonth'));
    }

    // * Note: embryosByMonth is not used here; it is only used in index/embryoscount for reporting.
    public function index()
    {
        $allembryos = Embryo::orderByDesc('created_at')->get();
        $users = User::where('major', 2)->get();
        $breedings = Breeding::where('status', 1)
            ->whereDoesntHave('embryo')
            ->orderByDesc('created_at')
            ->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $embryosByMonth = Embryo::
            selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->groupBy('year', 'month')
            ->paginate(10);
        return view('admin.embryos.index', compact('embryosByMonth', 'allembryos', 'finances', 'breedings', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // ✅ 1. تحقق من صحة البيانات
    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'finance_id' => 'nullable|exists:financials,id',
        'breeding_id' => 'required|exists:breedings,id',
        'description' => 'nullable|string',
        'localhorsename' => 'required|string',
        'cost' => 'required|numeric',
        'paid' => 'nullable|numeric|min:0',
    ]);

    try {
        // ✅ 2. نفذ داخل Transaction لتفادي أخطاء التزامن
        DB::transaction(function () use ($validated) {
            $paid = $validated['paid'] ?? 0;

            // جلب سجل المالية فقط إذا كان هناك مبلغ مدفوع وfinance_id موجود
            if (!empty($validated['finance_id']) && $paid > 0) {
                $finance = Financial::lockForUpdate()->find($validated['finance_id']);

                if (!$finance) {
                    throw new \Exception('Finance not found.');
                }

                if ($finance->decamount < $paid) {
                    throw new \Exception('Not enough finance.');
                }

                $finance->decrement('decamount', $paid);
            }

            // إنشاء Embryo وربطه
            $embryo = new Embryo();
            $embryo->fill($validated);
            $embryo->save();
        });

        return redirect()->back()->with('status', 'Embryo Added Successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
    }
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // ✅ 1. Validate input
    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id',
        'finance_id' => 'nullable|exists:financials,id',
        'breeding_id' => 'required|exists:breedings,id',
        'description' => 'nullable|string',
        'localhorsename' => 'required|string',
        'cost' => 'required|numeric',
        'paid' => 'nullable|numeric|min:0',
        'status' => 'nullable|numeric',
    ]);

    // ✅ 2. Get embryo
    $embryo = Embryo::find($id);
    if (!$embryo) {
        return redirect()->back()->with('status', "Record not found");
    }

    // ✅ 3. Get finance record
    $finance = Financial::lockForUpdate()->find($validated['finance_id']);
    if (!$finance) {
        return redirect()->back()->with('status', 'Finance not found.');
    }

    DB::transaction(function () use ($validated, $embryo, $finance) {
        $oldPaid = $embryo->paid ?? 0;
        $newpaid = $validated['paid'] ?? 0;
        $paidDiff = $newpaid - $oldPaid;

        // ✅ 4. Check balance if increasing
        if ($paidDiff > 0 && $finance->decamount < $paidDiff) {
            throw new \Exception('Low balance in this finance.');
        }

        // ✅ 5. Adjust finance balance
        if ($paidDiff > 0) {
            $finance->decrement('decamount', $paidDiff);
        } elseif ($paidDiff < 0) {
            $finance->increment('decamount', abs($paidDiff));
        }

        // ✅ 6. Update embryo with only allowed fields
        $embryo->fill($validated);
        $embryo->paid = $newpaid;
        $embryo->status = $validated['status'] ?? 0;
        $embryo->update();
    });

    return redirect()->back()->with('status', "Updated Successfully");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        Embryo::destroy($id);
        return redirect()->back()->with('status', "Embryo Deleted Successfully");
    }
}

