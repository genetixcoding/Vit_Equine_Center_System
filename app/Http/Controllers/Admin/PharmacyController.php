<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pharmacycount()
    {
        $countpharmacy = Pharmacy::count();
        $emptypharmacy = Pharmacy::where('qty','0')->orderBy('created_at', 'desc')->paginate(10);
        $pharmacy = Pharmacy::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pharmacy.pharmacycount', compact('pharmacy','emptypharmacy', 'countpharmacy'));
    }
    public function index()
    {
        $countpharmacy = Pharmacy::count();
        $emptypharmacy = Pharmacy::where('qty','0')->orderBy('created_at', 'desc')->paginate(10);
        $pharmacy = Pharmacy::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pharmacy.index', compact('pharmacy','emptypharmacy', 'countpharmacy'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'unit' => 'required|numeric',
            'price' => 'required|numeric',

        ]);


        $pharmacy = new Pharmacy();
        $input = $validated;
        $input['unitqty'] = $validated['qty'] * $validated['unit'];
        $pharmacy->create($input);
        return redirect()->back()->with('status', "Medicine Added Successfully");

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'unit' => 'required|numeric',
            'price' => 'required|numeric',
            'type' => 'required|string|max:255',
        ]);

        $pharmacy = Pharmacy::find($id);
        $input = $validated;
        $input['unitqty'] = $validated['qty'] * $validated['unit'];
        $pharmacy->update($input);
        return redirect()->back()->with('status', "Medicine Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Pharmacy::destroy($id);
        return redirect()->back()->with('status', "Medicine Deleted Successfully");
    }
}
