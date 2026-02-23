<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\BeddingDesc;
use App\Models\Breeding;
use App\Models\Embryo;
use App\Models\FeedingBedding;
use App\Models\FeedingDesc;
use App\Models\Horse;
use App\Models\TaskDesc;
use App\Models\Treatment;
use App\Models\Vaccine;
use App\Models\VisitDesc;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class HorseController extends Controller
{
       /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $counthorse = Horse::count();
        $horses = Horse::where('status', '1')->orderBy('created_at', 'desc')->paginate(10);
        $horsesfemale = Horse::where('gender', '0')->whereNull('shelter')->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
        $horsesmale = Horse::where('gender', '1')->whereNull('shelter')->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
        $horsesfemaleshelter = Horse::where('gender', '0')->whereNotNull('shelter')->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
        $horsesmaleshelter = Horse::whereNotNull('shelter')->where('gender', '1')->where('status', '0')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.horses.index', compact('horses', 'horsesfemale', 'horsesmale', 'horsesfemaleshelter', 'horsesmaleshelter', 'counthorse'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $horse = new Horse();
        $input = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/Uploads/Horses', $filename);
            $input['image'] = $filename; // Correctly assign the image filename to the input array
        }
        $input['gender'] = $request->input('gender') == true ? '1' : '0';
        $input['stud_id'] = $request->input('stud_id');
        $horse->create($input);

        return redirect()->back()->with('status', "Horse Added Successfully");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $name)
    {
        $horse = Horse::where('name', $name)->first();
        return view('admin.horses.edit', compact('horse'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ensure the Horse instance exists
        $horse = Horse::find($id);
        if (!$horse) {
            return redirect()->back()->with('status', "Horse not found");
        }

        $input = $request->all();
        $input['status'] = $request->input('status') == true ? '1' : '0';
        $input['gender'] = $request->input('gender') == true ? '1' : '0';

        if ($request->hasFile('image')) {
            $path = 'assets/img/' . $horse->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('assets/Uploads/Horses/', $filename);
            $input['image'] = $filename; // Update the image input explicitly
        }

        $horse->update($input); // Update the horse with the modified input

        return redirect()->back()->with('status', "Horse Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $horse = Horse::find($id);
        if (!$horse) {
            return redirect()->back()->with('status', "Horse not found");
        }

        Horse::destroy($id);
        return redirect()->back()->with('status', "Horse Deleted Successfully");
    }




    // ---------------------------------------------------------------------
    // ---------------------------------------------------------------------


    /**
     * Display the specified resource.
     */
    public function show(string $stud_name, string $item_name)
    {
        $horse = Horse::where('name', $item_name)->first();
        if ($horse) {
            return view('admin.horses.details', compact('horse'));
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }
    // ---------------------------------------------------------------------
    // ---------------------------------------------------------------------
    // Horse Management


    public function vaccinetable($id)
    {
        // Get visits grouped by horse and by month
        if (Horse::where('id', $id)->exists()) {
            $horse = Horse::where('id', $id)->first();
            if ($horse) {
                // Only get VisitDesc records attached to this horse
                $allvaccines = Vaccine::where('horse_id', $horse->id)->get();
                $vaccinesByMonth = Vaccine::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('supervisor.horses.vaccinetable', compact('allvaccines', 'vaccinesByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', 'This Horse Dosen`t exists');
        }
    }
    // Visite Table
    public function visittable(string $name)
    {
        // Get visits grouped by horse and by month
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            if ($horse) {
                // Only get VisitDesc records attached to this horse
                $allvisits = VisitDesc::where('horse_id', $horse->id)->get();
                $visitsByMonth = VisitDesc::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.visittable', compact('allvisits', 'visitsByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn`t exist");
        }
    }
    public function treatmenttable(string $name)
    {
        // Get Treatments grouped by horse and by month
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            if ($horse) {
                // Only get VisitDesc records attached to this horse
                $alltreatments = Treatment::where('horse_id', $horse->id)->get();
                $treatmentsByMonth = Treatment::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.treatmenttable', compact('alltreatments', 'treatmentsByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }
    public function tasktable(string $name)
    {
        // Get  Tasks grouped by horse and by month
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            if ($horse) {
                // Only get     taskDesc records attached to this horse
                $alltasks = TaskDesc::where('horse_id', $horse->id)->get();
                $tasksByMonth = TaskDesc::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.tasktable', compact('alltasks', 'tasksByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }


    public function  breedingtable(string $name)
    {
        // Get breedings grouped by horse and by month
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            if ($horse) {
                // Get breeding records where this horse is either female or male
                $allbreedings = Breeding::where('femalehorse', $horse->id)
                    ->orWhere('malehorse', $horse->id)
                    ->get();
                $breedingsByMonth = Breeding::where('femalehorse', $horse->id)
                    ->orWhere('malehorse', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.breedingtable', compact('allbreedings', 'breedingsByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }
    public function embryotable(string $name)
    {
        // Get Embryos grouped by horse and by month
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            $breeding = Breeding::where('femalehorse', $horse->id)
                ->orWhere('malehorse', $horse->id)
                ->first();
            if ($breeding) {
                // Only get Embryo records attached to this horse
                $allembryos = Embryo::where('breeding_id', $breeding->id)->get();
                $embryosByMonth = Embryo::where('breeding_id', $breeding->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.embryotable', compact('allembryos', 'breeding','embryosByMonth', 'horse'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }
    public function feedingbeddingtable(string $name)
    {
        // Get Feedin&Beddings grouped by horse and by month
        $feedingbedings = FeedingBedding::all() ;
        if (Horse::where('name', $name)->exists()) {
            $horse = Horse::where('name', $name)->first();
            if ($horse) {
                // Only get Feedin&BeddingDesc records attached to this horse
                $allfeeding = FeedingDesc::where('horse_id', $horse->id)->get();
                $feedingByMonth = FeedingDesc::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                $allbedding = BeddingDesc::where('horse_id', $horse->id)->get();
                $beddingByMonth = BeddingDesc::where('horse_id', $horse->id)
                    ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->orderBy('month', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('admin.horses.feedbedtable', compact('allfeeding', 'feedingByMonth', 'allbedding','beddingByMonth', 'horse', 'feedingbedings'));
            }
        } else {
            return redirect()->back()->with('status', "This Horse Doesn't exist");
        }
    }

}
