<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\FeedingBedding;
use App\Models\Financial;
use App\Models\InternalInvoice;
use App\Models\MedicalInvoice;
use App\Models\Supplier;
use App\Models\SuppliesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InternalInvoiceController extends Controller
{
   public function invoicescount()
    {
        $allinvoices = InternalInvoice::with(['medinternalinvoices', 'supinternalinvoices'])
            ->orderByDesc('created_at')
            ->get();

        // Build yearly and monthly stats arrays
        $yearlyStats = [];
        $monthlyStats = [];

        foreach ($allinvoices->groupBy(fn($item) => $item->created_at->year) as $year => $invoicesOfYear) {
            $meds = $invoicesOfYear->pluck('medinternalinvoices')->flatten();
            $sups = $invoicesOfYear->pluck('supinternalinvoices')->flatten();

            // احصل على paid من InternalInvoice وليس من MedicalInvoice/SuppliesInvoice
            $medInternalInvoiceIds = $meds->pluck('internal_invoice_id')->unique();
            $supInternalInvoiceIds = $sups->pluck('internal_invoice_id')->unique();

            $medInternalInvoiceCount = $medInternalInvoiceIds->count();
            $supInternalInvoiceCount = $supInternalInvoiceIds->count();

            $medInternalInvoicePaid = InternalInvoice::whereIn('id', $medInternalInvoiceIds)->sum('paid');
            $supInternalInvoicePaid = InternalInvoice::whereIn('id', $supInternalInvoiceIds)->sum('paid');

            $yearlyStats[$year] = [
                'medTotalPrice' => $meds->sum('totalprice'),
                'medTotalId' => $medInternalInvoiceCount,
                'medTotalPaid' => $medInternalInvoicePaid,
                'supTotalPrice' => $sups->sum('totalprice'),
                'supTotalId' => $supInternalInvoiceCount,
                'supTotalPaid' => $supInternalInvoicePaid,
                'totalPaid' => $invoicesOfYear->sum('paid'),
                'invoicesCount' => $invoicesOfYear->count(),
            ];

            foreach ($invoicesOfYear->groupBy(fn($item) => $item->created_at->month) as $month => $invoicesOfMonth) {
                $medsMonth = $invoicesOfMonth->pluck('medinternalinvoices')->flatten();
                $supsMonth = $invoicesOfMonth->pluck('supinternalinvoices')->flatten();

                $medInternalInvoiceIdsMonth = $medsMonth->pluck('internal_invoice_id')->unique();
                $supInternalInvoiceIdsMonth = $supsMonth->pluck('internal_invoice_id')->unique();

                $medInternalInvoiceCountMonth = $medInternalInvoiceIdsMonth->count();
                $supInternalInvoiceCountMonth = $supInternalInvoiceIdsMonth->count();

                $medInternalInvoicePaidMonth = InternalInvoice::whereIn('id', $medInternalInvoiceIdsMonth)->sum('paid');
                $supInternalInvoicePaidMonth = InternalInvoice::whereIn('id', $supInternalInvoiceIdsMonth)->sum('paid');

                $monthlyStats[$year][$month] = [
                    'medTotalPrice' => $medsMonth->sum('totalprice'),
                    'medTotalId' => $medInternalInvoiceCountMonth,
                    'medTotalPaid' => $medInternalInvoicePaidMonth,
                    'supTotalPrice' => $supsMonth->sum('totalprice'),
                    'supTotalId' => $supInternalInvoiceCountMonth,
                    'supTotalPaid' => $supInternalInvoicePaidMonth,
                    'totalPaid' => $invoicesOfMonth->sum('paid'),
                    'invoicesCount' => $invoicesOfMonth->count(),
                ];
            }
        }

        $invoicesByMonth = InternalInvoice::selectRaw("
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            SUM(
                COALESCE(
                    (SELECT
                    SUM(totalprice)
                    FROM medical_invoices
                    WHERE medical_invoices.internal_invoice_id = internal_invoices.id), 0
                ) +
                COALESCE(
                    (SELECT
                    SUM(totalprice)
                    FROM supplies_invoices
                    WHERE supplies_invoices.internal_invoice_id = internal_invoices.id), 0
                )
            ) as totalPrice,
            SUM(paid) as totalPaid,
            COUNT(*) as count
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);


    // ➤ إعادة كل البيانات للـ Blade
    return view('admin.internalinvoices.countinvoice', compact(
        'allinvoices', 'invoicesByMonth', 'yearlyStats', 'monthlyStats'
    ));
    }




          /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        return view('admin.internalinvoices.add', compact('suppliers', 'finances'));
    }


    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, String $id)
    {
        // Validate input
        $validated = $request->validate([
            'paid' => 'required|numeric',
            'finance_id' => 'required|exists:financials,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            // Add other validation rules as needed
        ]);

        $internalinvoice = InternalInvoice::find($id);
        if (!$internalinvoice) {
            return redirect()->back()->with('status', "Record not found");
        }

        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $internalinvoice, $finance) {
                $oldpaid = $internalinvoice->paid ?? 0;
                $newpaid = $validated['paid'];
                $paidDiff = $newpaid - $oldpaid;

                if ($paidDiff > 0 && $finance->decamount < $paidDiff) {
                    throw new \Exception('Low balance in This finance.');
                }

                if ($paidDiff > 0) {
                    $finance->decrement('decamount', $paidDiff);
                } elseif ($paidDiff < 0) {
                    $finance->increment('decamount', abs($paidDiff));
                }

                $internalinvoice->fill($validated);
                $internalinvoice->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        InternalInvoice::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }







    // Supplier Controller

    /**
     * Display a listing of the resource.
     */
    public function indexsupplier()
    {
        // موردين فواتير طبية فقط
        $medicalSuppliers = Supplier::whereHas('internalinvoices', function ($q) {
            $q->whereHas('medinternalinvoices');
        })->get();

        // موردين فواتير مستلزمات فقط
        $suppliesSuppliers = Supplier::whereHas('internalinvoices', function ($q) {
            $q->whereHas('supinternalinvoices');
        })->get();

        // موردين تغذية فقط
        $feedingSuppliers = Supplier::whereHas('feedbed', function ($q) {
            $q->whereHas('feeding');
        })->get();

        // موردين فرش فقط
        $beddingSuppliers = Supplier::whereHas('feedbed', function ($q) {
            $q->whereHas('bedding');
        })->get();

        return view('admin.internalinvoices.indexsupplier', compact(
            'medicalSuppliers',
            'suppliesSuppliers',
            'feedingSuppliers',
            'beddingSuppliers'
        ));
    }

        public function showsupplier(string $id)
        {
            if (Supplier::where('id', $id)->exists()) {
                $supplier = Supplier::where('id', $id)->first();

                // All medical and supplies invoices for filtering by month in the view
                $allmedinternalinvoices = InternalInvoice::where('supplier_id', $supplier->id)->whereHas('medinternalinvoices')->get();
                $allsupinternalinvoices = InternalInvoice::where('supplier_id', $supplier->id)->whereHas('supinternalinvoices')->get();
                $allfeeding = FeedingBedding::where('supplier_id', $supplier->id)->whereHas('feeding')->get();
                $allbedding = FeedingBedding::where('supplier_id', $supplier->id)->whereHas('bedding')->get();

                // ByMonth grouping for each type
                $medinternalinvoicesByMonth = InternalInvoice::where('supplier_id', $supplier->id)
                    ->whereHas('medinternalinvoices')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                        COUNT(*) as medinvoicecount,
                        SUM(paid) as medpaid,
                        SUM(
                            COALESCE(
                                (SELECT SUM(totalprice)
                                 FROM medical_invoices
                                 WHERE medical_invoices.internal_invoice_id = internal_invoices.id), 0
                            )
                        ) as medtotalprice
                    ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

                $supinternalinvoicesByMonth = InternalInvoice::where('supplier_id', $supplier->id)
                    ->whereHas('supinternalinvoices')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                        COUNT(*) as supinvoicecount,
                        SUM(paid) as suppaid,
                        SUM(
                            COALESCE(
                                (SELECT SUM(totalprice)
                                 FROM supplies_invoices
                                 WHERE supplies_invoices.internal_invoice_id = internal_invoices.id), 0
                            )
                        ) as suptotalprice
                    ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

                $feedingByMonth = FeedingBedding::where('supplier_id', $supplier->id)
                    ->whereHas('feeding')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                    COUNT(*) as feedingcount,
                    SUM(price) as feedingprice,
                    SUM(paid) as feedingpaid
                    ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

                $beddingByMonth = FeedingBedding::where('supplier_id', $supplier->id)
                    ->whereHas('bedding')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                     COUNT(*) as beddingcount,
                     SUM(price) as beddingprice,
                     SUM(paid) as beddingpaid
                     ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

                return view('admin.internalinvoices.details', compact(
                    'supplier',
                    'medinternalinvoicesByMonth',
                    'supinternalinvoicesByMonth',
                    'feedingByMonth',
                    'beddingByMonth',
                    'allmedinternalinvoices',
                    'allsupinternalinvoices',
                    'allfeeding',
                    'allbedding'
                ));
            }
            else
            {
                return redirect()->back()->with('status', "Supplier Not Found");
            }
        }

    /**
     * Display the specified resource.
     */
     public function accountsupplier(string $name)
    {
        $supplier = Supplier::where('name', $name)->first();
        if (!$supplier) {
            return redirect()->back()->with('status', "Supplier Not Found");
        }

        // جميع الفواتير لهذا المورد
        $internalinvoices = InternalInvoice::where('supplier_id', $supplier->id)->get();

        // تجميع سنوي
        $yearly = InternalInvoice::where('supplier_id', $supplier->id)
            ->selectRaw('YEAR(created_at) as year,
                SUM(
                    COALESCE(
                        (SELECT SUM(totalprice) FROM medical_invoices WHERE medical_invoices.internal_invoice_id = internal_invoices.id), 0
                    ) +
                    COALESCE(
                        (SELECT SUM(totalprice) FROM supplies_invoices WHERE supplies_invoices.internal_invoice_id = internal_invoices.id), 0
                    )
                ) as totalPrice,
                SUM(paid) as totalPaid,
                COUNT(*) as count')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        // تجميع شهري
        $monthly = InternalInvoice::where('supplier_id', $supplier->id)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                SUM(
                    COALESCE(
                        (SELECT SUM(totalprice) FROM medical_invoices WHERE medical_invoices.internal_invoice_id = internal_invoices.id), 0
                    ) +
                    COALESCE(
                        (SELECT SUM(totalprice) FROM supplies_invoices WHERE supplies_invoices.internal_invoice_id = internal_invoices.id), 0
                    )
                ) as totalPrice,
                SUM(paid) as totalPaid,
                COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        // تجميع سنوي لـ feeding
        $feedingYearly = FeedingBedding::where('supplier_id', $supplier->id)
            ->whereHas('feeding')
            ->selectRaw('YEAR(created_at) as year, SUM(price) as totalPrice, SUM(paid) as totalPaid, COUNT(*) as count')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        // تجميع شهري لـ feeding
        $feedingMonthly = FeedingBedding::where('supplier_id', $supplier->id)
            ->whereHas('feeding')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(price) as totalPrice, SUM(paid) as totalPaid, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        // تجميع سنوي لـ bedding
        $beddingYearly = FeedingBedding::where('supplier_id', $supplier->id)
            ->whereHas('bedding')
            ->selectRaw('YEAR(created_at) as year, SUM(price) as totalPrice, SUM(paid) as totalPaid, COUNT(*) as count')
            ->groupBy('year')
            ->orderByDesc('year')
            ->get();

        // تجميع شهري لـ bedding
        $beddingMonthly = FeedingBedding::where('supplier_id', $supplier->id)
            ->whereHas('bedding')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(price) as totalPrice, SUM(paid) as totalPaid, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('admin.internalinvoices.accountsupplier', compact(
            'supplier', 'yearly', 'monthly', 'internalinvoices',
            'feedingYearly', 'feedingMonthly',
            'beddingYearly', 'beddingMonthly'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storesupplier(Request $request)
    {
        $supplier   = new Supplier();
        $input = $request->all();
        $supplier  ->create($input);
        return redirect()->back()->with('status', "Added Successfully");
    }
    public function updatesupplier(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        $input = $request->all();
        $supplier->update($input);
        return redirect()->back()->with('status', "Updated Successfully");
    }
    public function destroysupplier(string $id)
    {
            Supplier::destroy($id);
            return redirect()->back()->with('status', "Deleted Successfully");

    }



    // -----------------------------------------------------
    // Medical Invoice Controller


    public function medicalindex()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $allmedinternalinvoices = InternalInvoice::whereHas('medinternalinvoices', function ($query) {
            $query;
        })->orderBy('created_at', 'desc')->paginate(10);
        // ByMonth grouping for medical invoices
        $medinternalinvoicesByMonth = InternalInvoice::whereHas('medinternalinvoices')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('admin.internalinvoices.medicalindex', compact('allmedinternalinvoices', 'suppliers', 'finances', 'medinternalinvoicesByMonth'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function medstore(Request $request)
    {
        $rules = [
            "supplier_id" => "required",
            "paid" => "required",
            "finance_id" => "required|exists:financials,id",
            "medinternalinvoices.*" => "required"
        ];

        // Fix: use correct keys for validation (item, qty, price)
        foreach ($request->input('medinternalinvoices', []) as $key => $value) {
            $rules["medinternalinvoices.{$key}.item"] = 'required';
            $rules["medinternalinvoices.{$key}.qty"] = 'required|numeric';
            $rules["medinternalinvoices.{$key}.price"] = 'required|numeric';
        }

        $request->validate($rules);

        $invoices = InternalInvoice::create([
            "supplier_id" => $request->input('supplier_id'),
            "paid" => $request->input('paid'),
            "finance_id" => $request->input('finance_id')
        ]);

        $finance = Financial::where('id', $request->input('finance_id'))->first();

        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        if ($finance->decamount < $request->input('paid')) {
            return redirect()->back()->with('status', value: 'NoT Enough finance.');
        }

        // Decrement finance amount
        $finance->decrement('decamount', $request->input('paid'));

        // Create medical invoices
        foreach ($request->input('medinternalinvoices', []) as $key => $value) {
            $value['paid'] = $request->input('paid');
            $value['finance_id'] = $request->input('finance_id');
            $invoices->medinternalinvoices()->create($value);
        }

        return redirect()->back()->with('status', 'Created Successfully !!');
    }



    /**
     * Update the specified resource in storage.
     *
     */

    public function updatemedicine(Request $request, string $id)
    {


        $medicine   = MedicalInvoice::find($id);
        $input = $request->all();
        $medicine->update($input);
        return redirect()->back()->with('status', "Updated Successfully");


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroymedicine(string $id)
    {
        MedicalInvoice::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }




    // ------------------------------------------------------

    // Supplies Invoice Controller


    /**
     * Display a listing of the resource.
     */
    public function suppliesindex()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $allsupinternalinvoices = InternalInvoice::whereHas('supinternalinvoices', function ($query) {
            $query;
        })->orderBy('created_at', 'desc')->paginate(10);

        // ByMonth grouping for supplies invoices
        $supinternalinvoicesByMonth = InternalInvoice::whereHas('supinternalinvoices')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('admin.internalinvoices.suppliesindex', compact('allsupinternalinvoices', 'suppliers', 'finances', 'supinternalinvoicesByMonth'));
    }




        public function supstore(Request $request)
    {
        $rules = [
            "supplier_id" => "required",
            "paid" => "required",
            "finance_id" => "required|exists:financials,id",
            "supinternalinvoices.*" => "required"
        ];

        // Fix: use correct keys for validation (item, qty, price)
        foreach ($request->input('supinternalinvoices', []) as $key => $value) {
            $rules["supinternalinvoices.{$key}.item"] = 'required';
            $rules["supinternalinvoices.{$key}.qty"] = 'required|numeric';
            $rules["supinternalinvoices.{$key}.price"] = 'required|numeric';
        }

        $request->validate($rules);

        $invoices = InternalInvoice::create([
            "supplier_id" => $request->input('supplier_id'),
            "paid" => $request->input('paid'),
            "finance_id" => $request->input('finance_id')
        ]);

        $finance = Financial::where('id', $request->input('finance_id'))->first();

        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        if ($finance->decamount < $request->input('paid')) {
            return redirect()->back()->with('status', value: 'NoT Enough finance.');
        }

        // Decrement finance amount
        $finance->decrement('decamount', $request->input('paid'));

        // Create supical invoices
        foreach ($request->input('supinternalinvoices', []) as $key => $value) {
            $value['paid'] = $request->input('paid');
            $value['finance_id'] = $request->input('finance_id');
            $invoices->supinternalinvoices()->create($value);
        }

        return redirect()->back()->with('status', 'Created Successfully !!');
    }



    /**
     * Update the specified resource in storage.
     */
    public function updatesupplies(Request $request, string $id)
    {


        $supplise   = SuppliesInvoice::find($id);
        $input = $request->all();
        $supplise->update($input);
        return redirect()->back()->with('status', "Updated Successfully");


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroysupplies(string $id)
    {
        SuppliesInvoice::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }


}
