<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstructorApplication;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function showApplicationForm()
    {
        return view('becomeInstructor');
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'bio' => 'required|min:20|max:1000',
            'expertise' => 'required|string|max:255',
            'teaching_experience' => 'required|in:none,some,experienced,expert',
            'sample_video' => 'nullable|url',
            'social_media.linkedin' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
            'social_media.website' => 'nullable|url',
            'terms' => 'required|accepted',
        ]);

        $user = Auth::user();
        $haveAplication = InstructorApplication::where('user_id', $user->id)->first();
        
        if ($haveAplication){
            return redirect()->route('profile')->withErrors(['error' => 'Ya tienes una solicitud en proceso por favor espera respuesta.']);
        } else {
            $application = new InstructorApplication([
                'user_id' => Auth::id(),
                'bio' => $request->bio,
                'expertise' => $request->expertise,
                'teaching_experience' => $request->teaching_experience,
                'sample_video' => $request->sample_video,
                'social_media' => json_encode($request->social_media),
            ]);
    
            $application->save();
    
            return redirect()->route('profile')->with('success', 'Tu solicitud para ser instructor ha sido enviada. Te contactaremos pronto.');
        }
        
        
    }
}
