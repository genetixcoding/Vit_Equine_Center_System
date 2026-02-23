<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Financial;
use App\Models\Horse;
use App\Models\Stud;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitDesc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{

     /**
     * Display the specified resource.
     */
    public function countvisit()
    {
        $allvisits = Visit::orderByDesc('created_at')->get();
        // Group visits by year and month, then paginate the months
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
        $visitsByMonth = Visit::whereHas('visitdescs')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count
                ')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->paginate(10);


        return view('admin.visits.countvisit', compact(
    'allvisits',
    'visitsByMonth',
            'yearlyStats',
            'monthlyStats'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horses = Horse::all();
        $allvisits = Visit::orderByDesc('created_at')->get();

        // Group visits by year and month, then paginate the months
        $visitsByMonth = Visit::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        return view('admin.visits.index', compact('allvisits' , 'horses', 'visitsByMonth'));
    }
       /**
     * Show the form for creating a new resource.
     */
    public function showvisit($id) {
            $users = User::where('major', 2)->orderByDesc('created_at')->get();
            $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();
            $horses = Horse::orderByDesc('created_at')->get();

        if (Visit::where('id', $id)->exists()) {
            $visit = Visit::where('id', $id)->first();
            if ($visit) {
                return view('admin.visits.view', compact('visit', 'users', 'finances','horses'));
            } else {
                return redirect()->back()->with('status', 'This Visit Dosen`t exists');
            }

        } else {
            return redirect()->back()->with('status', 'This Visit Dosen`t exists');
        }
    }
    public function create()
    {
        $studs = Stud::all();
        $horses = Horse::all();
        $users = User::where('major', 2)->orderByDesc('created_at')->get();
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get();

        return view('admin.visits.add', compact( 'horses', 'users', 'finances', 'studs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // قواعد التحقق العامة
    $rules = [
        "user_id" => "required|exists:users,id",
        "stud_id" => "required|exists:studs,id",
        "paid" => "nullable|numeric",
        "visitprice" => "nullable|numeric",
        "discount" => "nullable|numeric",
        "visitdescs" => "required|array|min:1",
    ];

    // إضافة قواعد لكل visitdescs داخل اللوب
    foreach ($request->input('visitdescs', []) as $key => $value) {
        $rules["visitdescs.{$key}.horse_id"] = 'required|exists:horses,id';
        $rules["visitdescs.{$key}.case"] = 'required|string|max:255';
        $rules["visitdescs.{$key}.image"] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        $rules["visitdescs.{$key}.treatment"] = 'nullable|string';
        $rules["visitdescs.{$key}.description"] = 'nullable|string';
        $rules["visitdescs.{$key}.caseprice"] = 'nullable|numeric|min:0';
    }


    $validated = $request->validate($rules);
    // إنشاء سجل الزيارة
    $visit = Visit::create([
        "user_id" => $request->user_id,
        "stud_id" => $request->stud_id,
        "finance_id" => $request->finance_id,
        "paid" => $request->paid,
        "visitprice" => $request->visitprice,
        "discount" => $request->discount,
    ]);

    // رفع الصور وتسجيل تفاصيل الزيارة
    foreach ($request->visitdescs as $desc) {
        if (isset($desc['image']) && $desc['image'] instanceof \Illuminate\Http\UploadedFile) {
            $imageName = time() . '_' . uniqid() . '.' . $desc['image']->getClientOriginalExtension();
            $desc['image']->move(public_path('assets/Uploads/Visits/'), $imageName);
            $desc['image'] = $imageName;
        } else {
            $desc['image'] = null;
        }
        $visit->visitdescs()->create($desc); // لاحظ الجمع: visitdescs()
    }

    return redirect()->back()->with('status', 'Created successfully !!');
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
               // Validate input
        $request->validate([
            "user_id" => "required",
            "visitprice" => "nullable|numeric",
            "discount" => "nullable|numeric",
            "paid" => "required|numeric",
            // Add other validation rules as needed
        ]);

        $visit = Visit::find($id);
        if (!$visit) {
            return redirect()->back()->with('status', "Record not found");
        }
        // Only update allowed fields
        $visit->update([
            'user_id' => $request->user_id,
            'visitprice' => $request->visitprice,
            'discount' => $request->discount,
            'paid' => $request->paid,
            'finance_id' => $request->finance_id,
        ]);
        return redirect()->back()->with('status', "Visit Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Visit::destroy($id);
        return redirect()->back()->with('status', "Visit Deleted Successfully");
    }



    // ---------------------------------------------------------
    // ---------------------------------------------------------
    // ---------------------------------------------------------
    // ---------------------------------------------------------
    // ---------------------------------------------------------


    /**
     * Update the specified resource in storage.
     */
    public function updatevisitdescs(Request $request, string $id)
    {
        $visitdesc = VisitDesc::find($id); // Fix variable name from $visitdescs to $visitdesc
        $input = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->image->getClientOriginalName();
            $request->image->move(public_path('assets/Uploads/Visits/'), $imageName);
            $input['image'] = $imageName;
        }

        $visitdesc->update($input); // Fix variable name here as well
        return redirect()->back()->with('status', "Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyvisitdescs(string $id)
    {
        VisitDesc::destroy($id);
        return redirect()->back()->with('status', "Deleted Successfully");
    }
}
