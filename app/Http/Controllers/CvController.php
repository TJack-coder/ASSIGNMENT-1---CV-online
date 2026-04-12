<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvEducation;
use App\Models\CvWorkHistory;
use App\Models\CvCertificate;
use App\Models\CvSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvController extends Controller
{
    public function create()
    {
        if (Auth::user()->role !== 'job_seeker') {
            abort(403, 'Only job seekers can manage CVs');
        }

        // Pass all reference data for dropdowns to the view
        return view('seeker.cv.form', [
            'categories' => \App\Models\CvCategory::all(),
            'countries'  => \App\Models\Country::all(),
            'cities'     => \App\Models\City::all(),
            'districts'  => \App\Models\District::all(),
            'degrees'    => \App\Models\DegreeLevel::all(),
            'majors'     => \App\Models\Major::all(),
            'institutions'=> \App\Models\Institution::all(),
            'jobTitles'  => \App\Models\JobTitle::all(),
            'employmentTypes' => \App\Models\EmploymentType::all(),
            'industries' => \App\Models\Industry::all(),
            'skillsList' => \App\Models\Skill::all(),
            'proficiencies' => \App\Models\ProficiencyLevel::all(),
            'certificates'=> \App\Models\CertificateName::all(),
            'organizations' => \App\Models\IssuingOrganization::all(),
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'job_seeker') {
            abort(403, 'Only job seekers can manage CVs');
        }
        
        $request->validate([
            'full_name' => 'required',
            'date_of_birth' => 'required|date',
            'skills' => 'array|max:5', 
        ]);

        // Only one CV per user
        $cv = Cv::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['cv_category_id','full_name','date_of_birth','gender','email','phone_number',
                            'country_id','city_id','district_id','street_address','postal_code'])
        );

        // Delete old records 
        $cv->educations()->delete();
        $cv->workHistories()->delete();
        $cv->certificates()->delete();
        $cv->skills()->delete();

        foreach ($request->educations ?? [] as $edu) {
            $cv->educations()->create($edu);
        }


        return redirect()->route('cv.create')->with('success', 'CV saved successfully!');
    }
}