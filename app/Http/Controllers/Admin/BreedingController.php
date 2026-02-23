<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;



use App\Models\Breeding;
use App\Models\Financial;
use App\Models\User;
use App\Models\Horse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BreedingController extends Controller
{

    public function countbreeding()
    {
        $allbreedings = Breeding::orderByDesc('created_at')->get();

        // Group breedings by year and month, and calculate sums
        $breedingsByMonth = Breeding::selectRaw("
            YEAR(breedings.created_at) as year,
            MONTH(breedings.created_at) as month,
            COUNT(*) as count,
            SUM(breedings.cost) as totalCost,
            SUM(breedings.paid) as totalPaid
        ")
        ->groupBy('year', 'month')
        ->orderByDesc('year')
        ->orderByDesc('month')
        ->paginate(5);

        return view('admin.breedings.countbreeding', compact('allbreedings', 'breedingsByMonth'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horse = Horse::all();
        $users = User::where('major', '2')->get(); // Exclude users with major = 1
        $finances = Financial::where('decamount', '>', 0)->orderByDesc('created_at')->get(); // Get finances for these users


        // All breedings by month
        $breedingsByMonth = Breeding::
            selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->groupBy('year', 'month')
            ->paginate(10);

        // Pregnant breedings by month
        $breedingspregByMonth = Breeding::where('status', 1)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->groupBy('year', 'month')
            ->get();

        // Not pregnant breedings by month
        $breedingsnotpregByMonth = Breeding::where('status', 2)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->groupBy('year', 'month')
            ->get();

        // All breedings for filtering in the view
        $allbreedings = Breeding::orderBy('created_at', 'desc')->get();

        return view('admin.breedings.index', compact(
                'breedingsnotpregByMonth',
                'breedingspregByMonth',
                'breedingsByMonth',
                'allbreedings',
                'horse',
                'users',
                'finances'
            )
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'femalehorse' => 'nullable|exists:horses,id',
            'malehorse' => 'nullable|exists:horses,id',
            'user_id' => 'nullable|exists:users,id',
            'stud' => 'nullable',
            'horsename' => 'nullable',
            'cost' => 'nullable|numeric',
            'status' => 'nullable|numeric',
            'description' => 'nullable',
            'paid' => 'nullable|numeric|min:0',
            'finance_id' => 'nullable|exists:financials,id',
            // Add other validation rules as needed
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $paid = $validated['paid'] ?? 0;

                if (!empty($validated['finance_id'])) {
                    $finance = Financial::lockForUpdate()->find($validated['finance_id']);
                    if (!$finance) {
                        throw new \Exception('Finance not found.');
                    }
                    if ($finance->decamount < $paid) {
                        throw new \Exception('NoT Enough finance.');
                    }
                    $finance->decrement('decamount', $paid);
                }

                $breeding = new Breeding();
                $breeding->fill($validated);
                $breeding->status = $validated['status'] ?? 0;
                $breeding->save();
            });

            return redirect()->back()->with('status', "Breeding Added Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $validated = $request->validate([
            'femalehorse' => 'nullable|exists:horses,id',
            'malehorse' => 'nullable|exists:horses,id',
            'user_id' => 'nullable|exists:users,id',
            'stud' => 'nullable',
            'horsename' => 'nullable',
            'cost' => 'nullable|numeric',
            'description' => 'nullable',
            'paid' => 'nullable|numeric|min:0',
            'status' => 'nullable|numeric',
            'finance_id' => 'nullable|exists:financials,id',
            // Add other validation rules as needed
        ]);

        $breeding = Breeding::find($id);
        if (!$breeding) {
            return redirect()->back()->with('status', "Record not found");
        }

        $finance = Financial::lockForUpdate()->find($validated['finance_id']);
        if (!$finance) {
            return redirect()->back()->with('status', 'Finance not found.');
        }

        try {
            DB::transaction(function () use ($validated, $breeding, $finance) {
                $oldPaid = $breeding->paid ?? 0;
                $newPaid = $validated['paid'] ?? 0;
                $paidDiff = $newPaid - $oldPaid;

                if ($paidDiff > 0 && $finance->decamount < $paidDiff) {
                    throw new \Exception('NoT Enough finance.');
                }

                if ($paidDiff > 0) {
                    $finance->decrement('decamount', $paidDiff);
                } elseif ($paidDiff < 0) {
                    $finance->increment('decamount', abs($paidDiff));
                }

                // Update status first
                $breeding->fill($validated);
                $breeding->paid = $newPaid; // Always update paid
                $breeding->status = $validated['status'] ?? 0;
                $breeding->save();
            });

            return redirect()->back()->with('status', "Breeding Updated Successfully");
        } catch (\Exception $e) {
            return redirect()->back()->with('status', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    $horse = Horse::all();
    $users = User::all();
    $finances = Financial::where('decamount', '>', 0)
        ->orderByDesc('created_at')
        ->get();

    $breeding = Breeding::with('embryo')->find($id);

    if (!$breeding) {
        return redirect()->back()->with('status', 'This Breeding Doesn`t exist');
    }

    return view('admin.breedings.details', compact('breeding', 'horse', 'users', 'finances'));
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Breeding::destroy($id);
        return redirect()->back()->with('status', "Breeding Deleted Successfully");
    }
}
