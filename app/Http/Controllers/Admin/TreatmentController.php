<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Models\Embryo;
use App\Models\Horse;
use App\Models\Pharmacy;
use App\Models\Treatment;
use App\Models\TreatmentDesc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alltreatments = Treatment::orderBy('created_at', 'desc')->paginate(10);
        $horses = Horse::all();
        $users = User::all();
        $pharmacy = Pharmacy::all();
        $embryos = Embryo::where('status', 0)->get();
        $treatmentsByMonth = Treatment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(5);
        return view('admin.treatments.index', compact( 'alltreatments', 'horses','embryos', 'pharmacy', 'users', 'treatmentsByMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $horses = Horse::all();
        $doctors = User::where('major', 2)->orderByDesc('created_at')->get();
        $pharmacy = Pharmacy::where('unitqty', '!=', 0)->orderByDesc('created_at')->get();
        $embryos = Embryo::where('status', 0)->orderByDesc('created_at')->get();
        return view('admin.treatments.add', compact('horses', 'pharmacy', 'embryos', 'doctors'));
    }

    public function show(String $id)
    {
        $horses = Horse::all();
        $users = User::all();
        $pharmacy = Pharmacy::where('unitqty', '!=', 0)->orderByDesc('created_at')->get();
        $embryos = Embryo::where('status', 0)->orderByDesc('created_at')->get();
        if (Treatment::where('id', $id)->exists()) {
            $treatment = Treatment::where('id', $id)->first();
            if ($treatment) {
                return view('admin.treatments.details',compact('treatment', 'pharmacy', 'horses', 'users' ,'embryos'));
            } else {
                return redirect()->back()->with('status', 'This Breeding Doesn`t exist');
            }
        } else {
            return redirect()->back()->with('status', 'This Breeding Doesn`t exist');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            "horse_id" => "nullable|exists:horses,id",
            "embryo_id" => "nullable|exists:embryos,id",
            "user_id" => "required|exists:users,id",
            "treatmentdesc.*" => "required|array"
        ];

        $request->validate($rules);

        foreach($request->treatmentdesc as $key => $value) {
            $rules["treatmentdesc.{$key}.pharmacy_id"] = 'required';
            $rules["treatmentdesc.{$key}.description"] = 'required';
            $rules["treatmentdesc.{$key}.qty"] = 'required|numeric|min:1';
            $rules["treatmentdesc.{$key}.type"] = 'required';
        }
        $request->validate($rules);

        // Check and decrement for each medicine used
        foreach($request->treatmentdesc as $value) {
            $pharmacy = Pharmacy::find($value['pharmacy_id']);
            if (!$pharmacy) {
                return redirect()->back()->with('status', 'Medicine not found.');
            }
            if ($pharmacy->unitqty < $value['qty']) {
                return redirect()->back()->with('status', 'Not enough amount of medicine.');
            }
            $pharmacy->decrement('unitqty', $value['qty']);
        }

        $treatment = Treatment::create([
            "horse_id" => $request->horse_id,
            "embryo_id" => $request->embryo_id,
            "user_id" => $request->user_id,
        ]);

        foreach($request->treatmentdesc as $value) {
            $treatment->treatmentdesc()->create($value);
        }

        return redirect()->back()->with('status', 'Created successfully !!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $treatment = Treatment::find($id);
        $input = $request->all();
        $treatment->update($input);
        return redirect()->back()->with('status', "Treatment Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Treatment::destroy($id);
        return redirect()->back()->with('status', "Treatment Deleted Successfully");
    }

    // For Edit and Update and Delete Treatments From Treatment Description
    public function updatetreatmentdesc(Request $request, string $id)
    {
        $validated = $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'description' => 'required|string',
            'qty' => 'required|numeric|min:1',
            'type' => 'required|string',
        ]);
        $treatmentdesc = TreatmentDesc::find($id);
        if (!$treatmentdesc) {
            return redirect()->back()->with('status', "Record not found");
        }

        $pharmacy = Pharmacy::lockForUpdate()->find($validated['pharmacy_id']);
        if (!$pharmacy) {
            return redirect()->back()->with('status', 'pharmacy not found.');
        }

        try {
            DB::transaction(function () use ($validated, $treatmentdesc, $pharmacy) {
                $oldQty = $treatmentdesc->qty ?? 0;
                $newQty = $validated['qty'];
                $qtyDiff = $newQty - $oldQty;

                if ($qtyDiff > 0 && $pharmacy->unitqty < $qtyDiff) {
                    throw new \Exception('Low balance in This pharmacy.');
                }

                if ($qtyDiff > 0) {
                    $pharmacy->decrement('unitqty', $qtyDiff);
                } elseif ($qtyDiff < 0) {
                    $pharmacy->increment('unitqty', abs($qtyDiff));
                }

                $treatmentdesc->fill($validated);
                $treatmentdesc->update();
            });

            return redirect()->back()->with('status', "Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }
    public function destroytreatmentdesc(string $id)
    {
        TreatmentDesc::destroy($id);
        return redirect()->back()->with('status', "Task Description Deleted Successfully");
    }
}
