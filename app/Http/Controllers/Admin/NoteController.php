<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allnotes = Note::orderBy('created_at', 'desc')->paginate(10);
        $users = User::all();

        // Paginate days
        $days = Note::selectRaw('DATE(created_at) as day')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(10);

        // Get notes only for paginated days
        $notesGroupedByDay = collect();
        foreach ($days as $dayObj) {
            $day = $dayObj->day;
            $notesGroupedByDay[$day] = Note::whereDate('created_at', $day)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Count notes per day for table
        $notesByDay = Note::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->paginate(10);

        return view('admin.notes.index', compact('allnotes', 'users', 'notesByDay', 'notesGroupedByDay', 'days'));
    }

    public function mynotes(string $id)
    {
        $users = User::where('role_as', 2)->get();
        $notes = Note::where('manager_id' , Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.notes.mynotes', compact('notes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $note = new Note();
        $input = $request->all();
        $note->create($input);
        return redirect()->back()->with('status', "Note Added Successfully");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = Note::find($id);
        $input = $request->all();
        $note->update($input);
        return redirect()->back()->with('status', "Note Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Note::destroy($id);
        return redirect()->back()->with('status', "Note Deleted Successfully");
    }
}
