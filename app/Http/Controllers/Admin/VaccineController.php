<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use App\Models\Vaccine;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horses = Horse::all();

        // Paginate days (keeps compatibility with view variable names)
        $VaccinesByDay = Vaccine::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(10);

        // Get Vaccines only for paginated days
        $VaccinesGroupedByDay = collect();
        foreach ($VaccinesByDay as $dayObj) {
            $day = $dayObj->day;
            $VaccinesGroupedByDay[$day] = Vaccine::whereDate('created_at', $day)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('supervisor.vaccines.index', compact('horses','VaccinesByDay', 'VaccinesGroupedByDay'));
    }

    public function store(Request $request)
{
    $request->validate([
        'vaccinedesc' => 'required|array',
        'vaccinedesc.*.horse_id' => 'required|exists:horses,id',
        'vaccinedesc.*.description' => 'required|string',
        'vaccinedesc.*.image' => 'required|image|max:5120',
    ]);

    foreach ($request->vaccinedesc as $value) {

        // اسم صورة فريد
        $imageName = time() . '_' . uniqid() . '.' . $value['image']->extension();

        // نقل الصورة إلى public
        $value['image']->move(public_path('assets/Uploads/Vaccines'), $imageName);

        Vaccine::create([
            'horse_id' => $value['horse_id'],
            'description' => $value['description'],
            'image' => $imageName,
        ]);
    }

    return redirect()->back()->with('status', 'Created successfully !!');
}


    public function update(Request $request, Vaccine $vaccine)
{
    $request->validate([
        'horse_id' => 'required|exists:horses,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:5120',
    ]);

    $data = $request->only(['horse_id', 'description']);

    if ($request->hasFile('image')) {

        // حذف الصورة القديمة لو موجودة
        $oldPath = public_path('assets/Uploads/Vaccines/' . $vaccine->image);
        if ($vaccine->image && file_exists($oldPath)) {
            unlink($oldPath);
        }

        // رفع الصورة الجديدة
        $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
        $request->image->move(public_path('assets/Uploads/Vaccines'), $imageName);

        $data['image'] = $imageName;
    }

    $vaccine->update($data);

    return redirect()->back()->with('success', 'Vaccine updated successfully');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vaccine $vaccine)
    {
        if ($vaccine->image && Storage::disk('public')->exists($vaccine->image)) {
            Storage::disk('public')->delete($vaccine->image);
        }
        $vaccine->delete();

        return redirect()->back()->with('success','Vaccine deleted');
    }
}
