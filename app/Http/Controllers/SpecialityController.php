<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Speciality;
use Hash;
use Redirect;
use Illuminate\Validation\Rule;

class SpecialityController extends Controller
{

	public function __construct(){
        $this->middleware('auth');
    }


    public function all(){

    	$speciality = Speciality::get();

    	return view('speciality.all', ['speciality' => $speciality]);

    }

    public function create(){
		return view('speciality.create');
    }

    public function edit($id){
		$speciality = Speciality::find($id);
    	return view('speciality.edit',['speciality' => $speciality]);
    }
	
	public function store_edit(Request $request){

    	$validatedData = $request->validate([
        	'name' => ['required', 'string', 'max:255'],
         

    	]);


		if ($request->hasFile('image')) {
		
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
			if(!empty($request->image) && file_exists(public_path().'/imgs/speciality/'.strtolower(now()->monthName).'/'.$request->image)) {
				unlink(public_path().'/imgs/speciality/'.strtolower(now()->monthName).'/'.$request->image);
			}
			$image           = $request->file('image');
			$random = substr(md5(mt_rand()), 0, 7);
			//print_r($random);
			$name_img            = 'img'.$random.$request->user_id.'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/speciality/'.strtolower(now()->monthName);

			//print_r($name_img); die(); exit;
			$image->move(public_path($destinationPath), $name_img);
			$Speciality_image = Speciality::where('id', $request->id)->update(['logo' => 'speciality/'.strtolower(now()->monthName).'/'.$name_img]);			
		} 


		$speciality = Speciality::where('id', $request->id)
		         			->update(['name' => $request->name,
                                        'icon' => $request->icon]);

		return Redirect::back()->with('success', __('sentence.Speciality Updated Successfully'));

    }

    public function store(Request $request){

    	$validatedData = $request->validate([
        	'name' => ['required', 'string', 'max:255'],
            'image' => ['required'],

    	]);
		$imageSavePath = '';

		if ($request->hasFile('image')) {
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
			$image           = $request->file('image');
			$random = substr(md5(mt_rand()), 0, 7);
			$name_img            = 'img'.$random.time().'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/speciality/'.strtolower(now()->monthName);
			$image->move(public_path($destinationPath), $name_img);
			$imageSavePath = 'speciality/'.strtolower(now()->monthName).'/'.$name_img;		
		}

		$speciality = new Speciality();
		$speciality->name = $request->name;
		$speciality->logo = $imageSavePath;
		$speciality->save();

		return Redirect::route('speciality.all')->with('success', __('sentence.Speciality Created Successfully'));

    }


    public function view($id){

    	$speciality = Speciality::findOrfail($id);
    	return view('speciality.view', ['speciality' => $speciality]);

    }
	public function update($id, $status){

		if($status == 0){
			$speciality = Speciality::where('id', $id)->update(['is_deleted' => 1]);
			$activeStatus = 'Speciality Deleted Successfully!';
		} else{
			$speciality = Speciality::where('id', $id)->update(['is_deleted' => 0]);
			$activeStatus = 'Speciality Added Successfully';
		}
        return Redirect::route('speciality.all')->with('success', $activeStatus);

    }



}
