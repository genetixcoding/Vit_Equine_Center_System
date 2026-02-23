<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\ExternalInvoice;
use App\Models\Financial;
use App\Models\Stud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExternalInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function countexternalinvoices()
    {
        $studs = Stud::orderBy('created_at', 'desc')->paginate(10);
        // All medical and supplies invoices for filtering by month in the view
        $allmedexternalinvoices = ExternalInvoice::whereHas('medexternalinvoices')->get();
        $allsupexternalinvoices = ExternalInvoice::whereHas('supexternalinvoices')->get();

         $medexternalinvoicesByMonth = ExternalInvoice::whereHas('medexternalinvoices')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                        COUNT(*) as medinvoicecount,
                        SUM(paid) as medpaid,
                        SUM(
                            COALESCE(
                                (SELECT SUM(totalprice)
                                 FROM medical_invoices
                                 WHERE medical_invoices.external_invoice_id = external_invoices.id), 0
                            )
                        ) as medtotalprice
                    ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

                $supexternalinvoicesByMonth = ExternalInvoice::whereHas('supexternalinvoices')
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month,
                        COUNT(*) as supinvoicecount,
                        SUM(paid) as suppaid,
                        SUM(
                            COALESCE(
                                (SELECT SUM(totalprice)
                                 FROM supplies_invoices
                                 WHERE supplies_invoices.external_invoice_id = external_invoices.id), 0
                            )
                        ) as suptotalprice
                    ')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->paginate(10);

        $externalinvoices = ExternalInvoice::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.externalinvoices.countinvoice', compact(
            'studs',
            'medexternalinvoicesByMonth',
            'supexternalinvoicesByMonth',
            'allmedexternalinvoices',
            'allsupexternalinvoices',
            'externalinvoices'
        ));
    }
    /**
     * Display a listing of the resource.
     */



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $studs = Stud::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        return view('admin.externalinvoices.add', compact('studs', 'finances'));
    }


    /**
     * Display a listing of the resource.
     */
    public function medicalindex()
    {
        $studs = Stud::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $allmedexternalinvoices = ExternalInvoice::whereHas('medexternalinvoices', function ($query) {
            $query;
        })->orderBy('created_at', 'desc')->paginate(10);
        // ByMonth grouping for medical invoices
        $medexternalinvoicesByMonth = ExternalInvoice::whereHas('medexternalinvoices')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('admin.externalinvoices.medicalindex', compact('medexternalinvoicesByMonth', 'allmedexternalinvoices', 'finances' , 'studs'));
    }

    public function suppliesindex()
    {
        $studs = Stud::orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
        $allsupexternalinvoices = ExternalInvoice::whereHas('supexternalinvoices', function ($query) {
            $query;
        })->orderBy('created_at', 'desc')->paginate(10);
        // ByMonth grouping for supplies invoices
        $supexternalinvoicesByMonth = ExternalInvoice::whereHas('supexternalinvoices')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return view('admin.externalinvoices.suppliesindex', compact('supexternalinvoicesByMonth', 'allsupexternalinvoices', 'studs', 'finances'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function medstore(Request $request)
    {
        $rules = [
            "stud_id" => "required",
            "paid" => "required",
            "finance_id" => "required|exists:financials,id",
            "medexternalinvoices.*" => "required"
        ];

        // Fix: use correct keys for validation (item, qty, price)
        foreach ($request->input('medexternalinvoices', []) as $key => $value) {
            $rules["medexternalinvoices.{$key}.item"] = 'required';
            $rules["medexternalinvoices.{$key}.qty"] = 'required|numeric';
            $rules["medexternalinvoices.{$key}.price"] = 'required|numeric';
        }

        $request->validate($rules);

        $externalinvoices = ExternalInvoice::create([
            "stud_id" => $request->input('stud_id'),
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
        foreach ($request->input('medexternalinvoices', []) as $key => $value) {
            $value['paid'] = $request->input('paid');
            $value['finance_id'] = $request->input('finance_id');
            $externalinvoices->medexternalinvoices()->create($value);
        }

        return redirect()->back()->with('status', 'Created Successfully !!');
    }


    /**
     * Store a newly created resource in storage.
     */
     public function supstore(Request $request)
    {
        $rules = [
            "stud_id" => "required",
            "paid" => "required",
            "finance_id" => "required|exists:financials,id",
            "supexternalinvoices.*" => "required"
        ];

        // Fix: use correct keys for validation (item, qty, price)
        foreach ($request->input('supexternalinvoices', []) as $key => $value) {
            $rules["supexternalinvoices.{$key}.item"] = 'required';
            $rules["supexternalinvoices.{$key}.qty"] = 'required|numeric';
            $rules["supexternalinvoices.{$key}.price"] = 'required|numeric';
        }

        $request->validate($rules);

        $externalinvoices = ExternalInvoice::create([
            "stud_id" => $request->input('stud_id'),
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
        foreach ($request->input('supexternalinvoices', []) as $key => $value) {
            $value['paid'] = $request->input('paid');
            $value['finance_id'] = $request->input('finance_id');
            $externalinvoices->supexternalinvoices()->create($value);
        }

        return redirect()->back()->with('status', 'Created Successfully !!');
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
            'stud_id' => 'nullable|exists:studs,id',
            // Add other validation rules as needed
        ]);

        $externalinvoice = ExternalInvoice::find($id);
        if (!$externalinvoice) {
            return redirect()->back()->with('status', "Record not found");
        }

        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $externalinvoice, $finance) {
                $oldpaid = $externalinvoice->paid ?? 0;
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

                $externalinvoice->fill($validated);
                $externalinvoice->update();
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
        ExternalInvoice::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }
}
