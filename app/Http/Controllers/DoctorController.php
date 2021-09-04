<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Doctor;
use App\Speciality;
use Hash;
use Redirect;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{

	public function __construct(){
        $this->middleware('auth');
    }


    public function all(){

    	$doctors = Doctor::get();
		// $doctors = User::where('role', 'doctor')->get();


    	return view('doctor.all', ['doctors' => $doctors]);

    }

    public function create(){
    	$speciality = Speciality::get();
		return view('doctor.create', ['specialities' => $speciality]);
    }
    public function edit($id){
		$doctor = User::find($id);
    	$speciality = Speciality::get();
    	return view('doctor.edit',['doctor' => $doctor, 'specialities' => $speciality]);
    }
	
	public function store_edit(Request $request){

		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => [
				'required', 'email', 'max:255',
				Rule::unique('users')->ignore($request->user_id),
			],
			'birthday' => ['required'],
			'gender' => ['required'],
			'phone' => ['required'],
			'address' => ['required'],
			'city' => ['required'],
			'state' => ['required'],
			'speciality' => ['required'],
			'experience' => ['required'],
			'registration' => ['required'],
			'qualification' => ['required'],
		]);
		
		if ($request->hasFile('image')) {
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
			if(!empty($request->image) && file_exists(public_path().'/imgs/doctors/'.strtolower(now()->monthName).'/'.$request->image)) {
				unlink(public_path().'/imgs/doctors/'.strtolower(now()->monthName).'/'.$request->image);
			}
			$image           = $request->file('image');
			$name            = 'IMG'.$request->user_id.'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/doctors/'.strtolower(now()->monthName);			
			// var_dump($name);
			$image->move(public_path($destinationPath), $name);
			$doctor = Doctor::where('user_id', $request->user_id)->update(['image' => 'doctors/'.strtolower(now()->monthName).'/'.$name]);			
		} 
		$user = User::find($request->user_id);
		$user->email = $request->email;
		$user->name = $request->name;
		$user->role = 'doctor';
		$user->update();
		// $days = [
		// 	'Monday',
		// 	'Tuesday',
		// 	'Wednesday',
		// 	'Thrusday',
		// 	'Friday',
		// 	'Saturday',
		// 	'Sunday'
		// ];
		// $available = [
		// 		"day"=> $days,
		// 		"status"=> $request->status,
		// 		"start_time"=> $request->start_time,
		// 		"end_time"=> $request->end_time,			
		// ];	
		$videocall = 0;
		$in_clinic = 0;

		if($request->video_call == 'on' || $request->video_call == '1' || $request->video_call == 1){
			$videocall = 1;
		}

		if($request->in_clinic == 'on' || $request->in_clinic == '1' || $request->in_clinic == 1){
			$in_clinic = 1;
		}


		
		$doctor = Doctor::where('user_id', $request->user_id)
			->update(['birthday' => $request->birthday,
				'phone' => $request->phone,
				'name' => $request->name,
				'email' => $request->email,
				'gender' => $request->gender,
				'address' => $request->address,
				'city' => $request->city,
				'state' => $request->state,
				'description' => $request->description,
				'lat' => $request->lat,
				'long' => $request->long,
				'speciality' => $request->speciality,
				'experience' => $request->experience,
				'registration' => $request->registration,
				'qualification' => $request->qualification,
				'fee' => $request->fee,
				'video_call' => $videocall,
				'in_clinic' => $in_clinic
				]);
		return Redirect::back()->with('success', __('sentence.Doctor Updated Successfully'));

    }

    public function store(Request $request){

	
		$validatedData = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			//'birthday' => ['required'],
			'gender' => ['required'],
			'phone' => ['required'],
			//'address' => ['required'],
			'city' => ['required'],
			'state' => ['required'],
			'speciality' => ['required'],
			'experience' => ['required'],
			'registration' => ['required'],
			'qualification' => ['required'],
			'image' => ['required'],
			'fee' => ['required']
			
		]);
		$user = new User();
		$user->password = Hash::make('udaipurmed2021');
		$user->email = $request->email;
		$user->name = $request->name;
		$user->role = 'doctor';
		$user->save();
		$imageSavePath = '';
		if ($request->hasFile('image')) {
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
			$image           = $request->file('image');
			$name            = 'IMG'.time().'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/doctors/'.strtolower(now()->monthName);
			$image->move(public_path($destinationPath), $name);
			$imageSavePath = 'doctors/'.strtolower(now()->monthName).'/'.$name;		
		}

		//-----------timing logics--------------------------------------------------------

		$days = $request -> day;
		$available = $request -> status;
		$starttime = $request -> start_time;
		$endtime = $request -> end_time;

		$arrTiming = array( );
		$index = 0;
		if(!$request -> day){
			return Redirect::route('doctor.all')->with('warning', 'Please select doctor working days');
		}
		foreach ($days as $value) {
			if(isset(array_values($available)[$index])){
				$currentday = array_values($days)[$index];
				$status_temp = array_values($available)[$index];
				$starttime_temp = array_values($starttime)[$index];
				$endtime_temp = array_values($endtime)[$index];


				array_push($arrTiming, (object)[
					'day' => $currentday,
					'status' => $status_temp,
					'start_time' => $starttime_temp,
					'end_time' => $endtime_temp
				]);
			}
			
			$index ++;
		  }


		  $final_data = json_encode($arrTiming, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
		//--------------------------------------------------------------------------------

		$doctor = new Doctor();
		$doctor->user_id = $user->id;
		$doctor->name = $request->name;
		$doctor->email = $request->email;
		$doctor->birthday = $request->birthday;
		$doctor->phone = $request->phone;
		$doctor->gender = $request->gender;
		$doctor->address = $request->address;
		$doctor->city = $request->city;
		$doctor->state = $request->state;
		$doctor->country = 'India';
		$doctor->speciality = $request->speciality;
		$doctor->experience = $request->experience;
		$doctor->description = $request->description;
		$doctor->lat = $request->lat;
		$doctor->long = $request->long;
		$doctor->registration = $request->registration;
		$doctor->qualification = $request->qualification;
		$doctor->image = $imageSavePath;

		$doctor->fee = $request->fee;
		print_r($request->video_call);
		if($request->video_call == 'on' || $request->video_call == '1' || $request->video_call == 1){
			$doctor->video_call = 1;
		}
		else{
			$doctor->video_call = 0;
		}

		if($request->in_clinic == 'on' || $request->in_clinic == '1' || $request->in_clinic == 1){
			$doctor->in_clinic = 1;
		}
		else{
			$doctor->in_clinic = 0;
		}


		
		  $doctor->availability = $final_data;


		$doctor->save();
		// print_r($doctor);

		return Redirect::route('doctor.all')->with('success', __('sentence.Doctor Created Successfully'));

    }


    public function view($id){

    	$doctor = User::findOrfail($id);
    	return view('doctor.view', ['doctor' => $doctor]);

    }
	public function update($id, $status){

		if($status == 0){
			$doctor = Doctor::where('id', $id)->update(['is_deleted' => 1]);
			$activeStatus = 'Doctor Deleted Successfully!';
		} else{
			$doctor = Doctor::where('id', $id)->update(['is_deleted' => 0]);
			$activeStatus = 'Doctor Added Successfully';
		}
        return Redirect::route('doctor.all')->with('success', $activeStatus);

    }


}
