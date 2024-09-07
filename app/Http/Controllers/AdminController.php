<?php

namespace App\Http\Controllers;

use App\Models\InstructorApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function instructorApplications()
    {
        $applications = InstructorApplication::with('user')->paginate(10);
        return view('admin', compact('applications'));
    }

    public function showApplication(InstructorApplication $application)
    {
        return response()->json($application->load('user'));
    }

    public function updateStatus(Request $request, InstructorApplication $application)
{

    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
    ]);



    $application->update(['status' => $request->status]);

    // Update user role based on application status
    if ($request->status === 'approved') {
        $application->user->update(['role' => 'instructor']);
    } else {
        $application->user->update(['role' => 'user']);
    }

    return response()->json([
        'success' => true,
        'message' => 'Estado de la solicitud y rol del usuario actualizados correctamente.',
    ]);
}

    
}
