<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\BeddingDesc;
use App\Models\FeedingBedding;
use App\Models\FeedingDesc;
use App\Models\Financial;
use App\Models\Horse;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedingBeddingController extends Controller
{

      public function feedingbeddingcount()
    {
        $allfeedbed = FeedingBedding::orderByDesc('created_at')->get();

        // Group feedbed by year and month, and calculate sums
        $feedbedByMonth = FeedingBedding::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as count,
            SUM(price) as price
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.feed&bed.countfeedbed', compact('allfeedbed', 'feedbedByMonth'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allfeedbed = FeedingBedding::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount' , '>', 0)->orderByDesc('created_at')->get();
        $suppliers = Supplier::all();
        $feedbedByMonth = FeedingBedding::
            selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return view('admin.feed&bed.index', compact('feedbedByMonth', 'allfeedbed', 'finances', 'suppliers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'price' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'item' => 'required|string',
            'qty' => 'required|integer',
            'paid' => 'required|integer',
        ]);

        try {
            // ✅ 2. نفذ داخل Transaction لتفادي أخطاء التزامن
            DB::transaction(function () use ($validated) {
                $finance = Financial::lockForUpdate()->find($validated['finance_id']);

                if (!$finance) {
                    throw new \Exception('Finance not found.');
                }

                if ($finance->decamount < $validated['paid']) {
                    throw new \Exception('Not enough finance.');
                }

                $finance->decrement('decamount', $validated['paid']);


                $feedbed = new FeedingBedding();
                $feedbed->fill($validated);
                $feedbed->decqty = $validated['qty'];

                $feedbed->save();
            });

            return redirect()->back()->with('status', "Feeding/Bedding Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // ✅ 1. Validate input
        $validated = $request->validate([
            'price' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'item' => 'required|string',
            'qty' => 'required|integer',
            'paid' => 'required|integer',
        ]);

        // ✅ 2. Get feedbed
        $feedbed = FeedingBedding::find($id);
        if (!$feedbed) {
            return redirect()->back()->with('status', "Record not found");
        }

        // ✅ 3. Get finance record
        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $feedbed, $finance) {
                $oldPaid = $feedbed->paid ?? 0;
                $newPaid = $validated['paid'];
                $paidDiff = $newPaid - $oldPaid;

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

                // ✅ 6. Update feedbed with only allowed fields
                $feedbed->fill($validated);
                $feedbed->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        if (FeedingBedding::where('id', $id)->exists()) {
            $feedbed = FeedingBedding::where('id', $id)->first();
            if ($feedbed) {
                return view('admin.feed&bed.details', compact('feedbed'));
            } else {
                return redirect()->back()->with('status', 'Doesn`t exist');
            }
        } else {
            return redirect()->back()->with('status', 'Doesn`t exist');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        FeedingBedding::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }

    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // Feeding Description

    public function indexfeeding()
    {
        $allfeeding = FeedingDesc::orderBy('created_at', 'desc')->get();
        $feedingbedings = FeedingBedding::where('decqty', '>', 0)->orderBy('created_at', 'desc')->get();
        $horses = Horse::all();
        $feedingByMonth = FeedingDesc::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return view('admin.feed&bed.feedingindex', compact('allfeeding', 'feedingbedings', 'horses', 'feedingByMonth'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storefeeding(Request $request)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'feedbed_id' => 'required|exists:feeding_beddings,id',
            'horse_id' => 'required|exists:horses,id',
            'qty' => 'required|numeric',
        ]);

        try {
            // ✅ 2. نفذ داخل Transaction لتفادي أخطاء التزامن
            DB::transaction(function () use ($validated) {
                $feedbed = FeedingBedding::lockForUpdate()->find($validated['feedbed_id']);

                if (!$feedbed) {
                    throw new \Exception('This Feeding & Bedding not found.');
                }

                if ($feedbed->decqty < $validated['qty']) {
                    throw new \Exception('Not Enough Feeding & Bedding.');
                }

                $feedbed->decrement('decqty', $validated['qty']);

                $feeding = new FeedingDesc();
                $feeding->fill($validated);
                $feeding->save();
            });

            return redirect()->back()->with('status', "Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatefeeding(Request $request, String $id)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'feedbed_id' => 'required|exists:feeding_beddings,id',
            'horse_id' => 'required|exists:horses,id',
            'qty' => 'required|numeric',
        ]);

        // ✅ 2. Get feeding record
        $feeding = FeedingDesc::find($id);
        if (!$feeding) {
            return redirect()->back()->with('status', "Record not found");
        }

        try {
            // ✅ 3. Transaction for concurrency safety
            DB::transaction(function () use ($validated, $feeding) {
                $feedbed = FeedingBedding::lockForUpdate()->find($validated['feedbed_id']);
                if (!$feedbed) {
                    throw new \Exception('This Feeding & Bedding not found.');
                }

                $oldqty = $feeding->qty ?? 0;
                $newqty = $validated['qty'];
                $qtyDiff = $newqty - $oldqty;

                // ✅ 4. Check balance if increasing
                if ($qtyDiff > 0 && $feedbed->decqty < $qtyDiff) {
                    throw new \Exception('Not Enough Feeding & Bedding.');
                }

                // ✅ 5. Adjust feedbed decqty
                $feedbed->decrement('decqty', $qtyDiff);

                // ✅ 6. Update feeding with only allowed fields
                $feeding->fill($validated);
                $feeding->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyfeeding(String $id)
    {
        $feeding = FeedingDesc::find($id);
        if ($feeding) {
            $feeding->delete();
            return redirect()->back()->with('status', "Deleted Successfully");
        }
        return redirect()->back()->with('status', "Record not found");
    }

    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // -------------------------------------------------------
    // Bedding Description

    public function indexbedding()
    {
        $allbedding = BeddingDesc::orderBy('created_at', 'desc')->get();
        $feedingbedings = FeedingBedding::where('decqty', '>', 0)->orderBy('created_at', 'desc')->get();
        $horses = Horse::all();
        $beddingByMonth = BeddingDesc::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return view('admin.feed&bed.beddingindex', compact('beddingByMonth', 'allbedding', 'feedingbedings', 'horses'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storebedding(Request $request)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'feedbed_id' => 'required|exists:feeding_beddings,id',
            'horse_id' => 'required|exists:horses,id',
            'qty' => 'required|numeric',
        ]);

        try {
            // ✅ 2. نفذ داخل Transaction لتفادي أخطاء التزامن
            DB::transaction(function () use ($validated) {
                $feedbed = FeedingBedding::lockForUpdate()->find($validated['feedbed_id']);

                if (!$feedbed) {
                    throw new \Exception('This Feeding & Bedding not found.');
                }

                if ($feedbed->decqty < $validated['qty']) {
                    throw new \Exception('Not Enough Feeding & Bedding.');
                }

                $feedbed->decrement('decqty', $validated['qty']);

                $bedding = new BeddingDesc();
                $bedding->fill($validated);
                $bedding->save();
            });

            return redirect()->back()->with('status', "Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatebedding(Request $request, String $id)
    {
        // ✅ 1. تحقق من صحة البيانات
        $validated = $request->validate([
            'feedbed_id' => 'required|exists:feeding_beddings,id',
            'horse_id' => 'required|exists:horses,id',
            'qty' => 'required|numeric',
        ]);

        // ✅ 2. Get bedding record
        $bedding = BeddingDesc::find($id);
        if (!$bedding) {
            return redirect()->back()->with('status', "Record not found");
        }

        try {
            // ✅ 3. Transaction for concurrency safety
            DB::transaction(function () use ($validated, $bedding) {
                $feedbed = FeedingBedding::lockForUpdate()->find($validated['feedbed_id']);
                if (!$feedbed) {
                    throw new \Exception('This Feeding & Bedding not found.');
                }

                $oldqty = $bedding->qty ?? 0;
                $newqty = $validated['qty'];
                $qtyDiff = $newqty - $oldqty;

                // ✅ 4. Check balance if increasing
                if ($qtyDiff > 0 && $feedbed->decqty < $qtyDiff) {
                    throw new \Exception('Not Enough Feeding & Bedding.');
                }

                // ✅ 5. Adjust feedbed decqty
                $feedbed->decrement('decqty', $qtyDiff);

                // ✅ 6. Update bedding with only allowed fields
                $bedding->fill($validated);
                $bedding->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroybedding(String $id)
    {
        $bedding = BeddingDesc::find($id);
        if ($bedding) {
            $bedding->delete();
            return redirect()->back()->with('status', "Deleted Successfully");
        }
        return redirect()->back()->with('status', "Record not found");
    }
}
