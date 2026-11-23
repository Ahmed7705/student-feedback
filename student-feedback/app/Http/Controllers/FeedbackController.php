<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();
        $query = Feedback::with('course');

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Search keywords
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                  ->orWhere('comments', 'like', "%$s%");
            });
        }

        $feedbacks = $query->latest()->get();
        return view('feedbacks.index', compact('feedbacks', 'courses'));
    }

    public function create()
    {
        // Check login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
        }
        $courses = Course::all();
        return view('feedbacks.create', compact('courses'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate input
        $data = $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'student_name' => 'required|min:3|max:100',
            'student_id'   => 'required|min:5|max:20',
            'rating'       => 'required|integer|min:1|max:5',
            'category'     => 'required',
            'title'        => 'required|min:3|max:150',
            'comments'     => 'required|min:5',
            'image'        => 'nullable|image|max:2048',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $name);
            $data['image_path'] = 'uploads/' . $name;
        }

        $data['is_anonymous'] = $request->has('is_anonymous');
        Feedback::create($data);

        return redirect()->route('home')->with('success', 'Feedback added.');
    }

    public function edit(Feedback $feedback)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
        }
        $courses = Course::all();
        return view('feedbacks.edit', compact('feedback', 'courses'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'student_name' => 'required|min:3|max:100',
            'rating'       => 'required|integer|min:1|max:5',
            'category'     => 'required',
            'title'        => 'required|min:3|max:150',
            'comments'     => 'required|min:5',
            'image'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $name);
            $data['image_path'] = 'uploads/' . $name;
        }

        $data['is_anonymous'] = $request->has('is_anonymous');
        $feedback->update($data);

        return redirect()->route('home')->with('success', 'Feedback updated.');
    }

    public function destroy(Feedback $feedback)
    {
        // Delete record
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $feedback->delete();
        return redirect()->route('home')->with('success', 'Feedback deleted.');
    }

    public function about()
    {
        return view('about');
    }
}
