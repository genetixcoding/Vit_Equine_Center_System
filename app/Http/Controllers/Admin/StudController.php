<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Models\Horse;
use App\Models\Stud;
use App\Models\Visit;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class StudController extends Controller
{

    public function search(Request $request)
    {
        $searched_stud = $request->stud_name;
        if ($searched_stud != "") {
            $stud = Stud::where("name", "LIKE", "%$searched_stud%")->first();
            if ($stud) {
                return redirect('details-stud/'.$stud->id);
            } else {
                return redirect()->back()->with("status", "No Stud matched Your Search");
            }
        } else {
            return redirect()->back();
        }
    }

    public function studlistAjax()
    {
        $studs = Stud::select('name')->get();
        $data = [];

        foreach ($studs as $item) {
            $data[] = $item['name'];
        }
        return $data;
    }

    public function index()
    {
        $countstud = Stud::where('status', '0')->count();
        $rejectedstudscount = Stud::where('status', '1')->count();
        $studs = Stud::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.studs.index', compact('studs', 'countstud', 'rejectedstudscount'));
    }

    public function show(string $id)
    {
        $stud = Stud::find($id);
        if ($stud) {
            $horses = Horse::where('stud_id', $stud->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('admin.studs.details', compact('stud', 'horses'));
        } else {
            return redirect('Studs')->with('status', "Stud Not Found");
        }
    }

    public function visittable(string $name)
    {
        if (Stud::where('name', $name)->exists()) {
            $stud = Stud::where('name', $name)->first();
            $allvisits = $stud->visits;
            $visits = Visit::where('stud_id', $stud->id)
                ->with('visitdescs')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('admin.studs.visittable', compact('stud', 'visits'));
        } else {
            return redirect()->back()->with('status', "Stud Not Found");
        }
    }

    public function invoicetable(string $name)
    {
        if (Stud::where('name', $name)->exists()) {
            $stud = Stud::where('name', $name)->first();

            // جميع الفواتير الطبية والمستلزمات لهذا الـ stud
            $allmedexternalinvoices = $stud->externalinvoices()->whereHas('medexternalinvoices')->get();
            $allsupexternalinvoices = $stud->externalinvoices()->whereHas('supexternalinvoices')->get();

            // تجميع الفواتير الطبية شهريًا وسنويًا
            $medexternalinvoicesByMonth = $stud->externalinvoices()
                ->whereHas('medexternalinvoices')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->Paginate(10);

            // تجميع فواتير المستلزمات شهريًا وسنويًا
            $supexternalinvoicesByMonth = $stud->externalinvoices()
                ->whereHas('supexternalinvoices')
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->Paginate(10);

            return view('admin.studs.externalinvoicetable', compact(
                'stud',
                'allmedexternalinvoices',
                'allsupexternalinvoices',
                'medexternalinvoicesByMonth',
                'supexternalinvoicesByMonth'
            ));
        }
        else
        {
            return redirect()->back()->with('status', "Stud Not Found");
        }
    }

    public function countstuds()
    {
        $studs = Stud::with([
            'horse.femaleHorse.embryo',
            'horse.maleHorse.embryo'
        ])->orderBy('created_at', 'desc')->paginate(10);

        foreach ($studs as $stud) {
            $allBreedings = collect();

            foreach ($stud->horse as $horse) {
                $allBreedings = $allBreedings
                    ->merge($horse->femaleHorse)
                    ->merge($horse->maleHorse);
            }

            // شيل التكرار
            $stud->allBreedings = $allBreedings->unique('id');

            // هات كل الامبريوز من البريدنج
            $stud->allEmbryos = $stud->allBreedings
                ->flatMap(fn($breeding) => $breeding->embryos)
                ->unique('id');
        }

        return view('admin.studs.countstuds', compact('studs'));
    }

    public function create()
    {
        return view('admin.studs.add');
    }

    public function store(Request $request)
    {
        $stud = new Stud();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('assets/Uploads/Studs', $filename);
            $stud->image = $filename;
        }
        $stud->name = $request->input('name');
        $stud->description = $request->input('description');
        $stud->save();
        return redirect('Studs')->with('status', "Stud Added Successfully");
    }

    public function edit(string $id)
    {
        $stud = Stud::find($id);
        return view('admin.studs.edit', compact('stud'));
    }

    public function update(Request $request, string $id)
    {
        $stud = Stud::find($id);

        if ($request->hasFile('image')) {
            $path = 'assets/img/'.$stud->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;
            $file->move('assets/Uploads/Studs/', $filename);
            $stud->image = $filename;
        }
        $stud->name = $request->input('name');
        $stud->description = $request->input('description');
        $stud->status = $request->input('status') == true ? '1' : '0';
        $stud->update();
        return redirect('Studs')->with('status', "Stud Updated Successfully");
    }

    public function destroy(string $id)
    {
        Stud::destroy($id);
        return redirect()->back()->with('status', "Stud Deleted Successfully");
    }
}
