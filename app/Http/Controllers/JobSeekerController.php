<?php

namespace App\Http\Controllers;

use App\Models\JobSeeker;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;

class JobSeekerController extends Controller
{

    public function index()
    {
        return view('jobseeker.index');
    }

    public function mailRegistration($jobseekers)
    {
        $data = array(
            'name' => $jobseekers['name'],
            'email' => $jobseekers['email']
        );

        Mail::send('mail.template', $data, function ($message) use ($jobseekers) {
            $message->to($jobseekers['email'], $jobseekers['name'])
                ->subject($jobseekers['subject']);
        });
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'contact' => 'required|max:191',
            'email' => 'required|email|max:191',
            'skillsets' => 'required|max:255',
            'experience' => 'required|max:191',
            'org' => 'max:255',
            'remarks' => 'max:255',
            'resume' => 'required|max:10000|mimes:doc,docx,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {

            $jobseekers = new JobSeeker;
            $jobseekers->name = $request->input('name');
            $jobseekers->contact = $request->input('contact');
            $jobseekers->email = $request->input('email');
            $jobseekers->skillsets = $request->input('skillsets');
            $jobseekers->experience = $request->input('experience');
            $jobseekers->org = $request->input('org');
            $jobseekers->remarks = $request->input('remarks');

            $email_data = array(
                'name' => $jobseekers->name,
                'contact' => $jobseekers->contact,
                'email' => $jobseekers->email,
                'skillsets' => $jobseekers->skillsets,
                'experience' => $jobseekers->experience,
                'org' => $jobseekers->org,
                'remarks' => $jobseekers->remarks,
                'subject' => 'Registration : new Job Seekers',
            );

            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->storeAs('public/uploads/jobseekers', $filename);
                $jobseekers->resume = $filename;
            }

            if (JobSeeker::where('email', '=', $jobseekers->email)->exists()) {
                return response()->json([
                    'status' => 400,
                    'errors' => ['error' => 'this mail already exist in database']
                ]);
            } else {

                $jobseekers->save();
                $this->mailRegistration($email_data);

                return response()->json([
                    'status' => 200,
                    'message' => 'jobseekers data uploaded successfully'
                ]);
            }
        }
    }
}
