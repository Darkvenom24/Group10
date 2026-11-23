<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Practical7Student;

class PracticalsController extends Controller
{
    public function practical1()
    {
        return view('practicals.p1');
    }

    public function practical2()
    {
        return view('practicals.p2');
    }

    public function practical4()
    {
        return view('practicals.p4');
    }

    public function practical7()
    {
        $currentUser = Auth::user();
        return view('practicals.p7', compact('currentUser'));
    }

    public function practical7Submit(Request $request)
    {
        $validated = $request->validate([
            'enrollment' => 'required|string',
            'full_name' => 'required|string|min:3',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'department' => 'required|string',
            'semester' => 'required|string',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'skills' => 'nullable|string',
            'project_title' => 'required|string',
            'project_desc' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Submitted';

        $record = Practical7Student::create($validated);

        return redirect()->route('practicals.p7.data')
            ->with('success', "Practical 7 Academic Record successfully stored! (ID: #{$record->id})");
    }

    public function practical7Data(Request $request)
    {
        $search = $request->query('search', '');
        $query = Practical7Student::with('user')->latest();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('enrollment', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('project_title', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(10)->withQueryString();
        return view('practicals.p7_data', compact('students', 'search'));
    }
}
