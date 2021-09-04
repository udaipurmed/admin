<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Drug;
use App\Imports\BulkImport;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
class DrugController extends Controller{


	public function __construct(){
        $this->middleware('auth');
    }


    //
    public function create(){
    	return view('drug.create');

    }
    public function import() 
    {
        Excel::import(new BulkImport,request()->file('file'));
        return $this->all();
        // return back();
    }

    public function store(Request $request){

        //print_r($request->rate); die(); exit;

    	$validatedData = $request->validate([
        	'trade_name' => 'required',
        	'generic_name' => 'required',
        	'note' => 'required',
        	'rate' => 'required',
            'type_sell' => 'required',
        	'manufacturer' => 'required',
            'country_origin' => 'required',
        	'salt' => 'required',
            'uses' => 'required',
        	'alternate' => 'required',
            'side_effect' => 'required',
        	'direction_use' => 'required',
            'therapeutic' => 'required',
            'image' => 'required',
    	]);
        $imageSavePath = '';
        if ($request->hasFile('image')) {
           // echo 'im'; die(); exit;
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg, JPEG, PNG',
			]);
			$image           = $request->file('image');
			$name            = 'img'.time().'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/drugs/'.strtolower(now()->monthName);
			$image->move(public_path($destinationPath), $name);
			$imageSavePath = 'drugs/'.strtolower(now()->monthName).'/'.$name;		
		}

       // print_r($imageSavePath); die(); exit;

    	$drug = Drug::updateOrCreate(
		    [
                'trade_name' => $request->trade_name, 
                'generic_name' => $request->generic_name, 
                'note' => $request->note, 
                'rate' => $request->rate,
                'type_sell' => $request->type_sell, 
                'manufacturer' => $request->manufacturer, 
                'country_origin' => $request->country_origin, 
                'salt' => $request->salt,
                'uses' => $request->uses, 
                'alternate' => $request->alternate, 
                'side_effect' => $request->side_effect, 
                'direction_use' => $request->direction_use,
                'therapeutic' => $request->therapeutic,
                'image' => $imageSavePath
            ]
		);

    	return Redirect::back()->with('success', __('sentence.Drug added Successfully'));
    }

    public function all(){
    	$drugs = Drug::all();

    	return view('drug.all',['drugs' => $drugs]);
    }


    public function edit($id){
        $drug = Drug::find($id);
        return view('drug.edit',['drug' => $drug]);
    }

    public function store_edit(Request $request){
            
        $validatedData = $request->validate([
            'trade_name' => 'required',
            'generic_name' => 'required',
            'note' => 'required',
            'rate' => 'required',
            'type_sell' => 'required',
        	'manufacturer' => 'required',
            'country_origin' => 'required',
        	'salt' => 'required',
            'uses' => 'required',
        	'alternate' => 'required',
            'side_effect' => 'required',
        	'direction_use' => 'required',
            'therapeutic' => 'required',
        ]);

        if ($request->hasFile('image')) {
			$validatedData = $request->validate([
				'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			]);
			if(!empty($request->image) && file_exists(public_path().'/imgs/drugs/'.strtolower(now()->monthName).'/'.$request->image)) {
				unlink(public_path().'/imgs/drugs/'.strtolower(now()->monthName).'/'.$request->image);
			}
			$image           = $request->file('image');
			$name            = 'img'.$request->user_id.'.'.$image->getClientOriginalExtension();
			$destinationPath = '/imgs/drugs/'.strtolower(now()->monthName);			
			// var_dump($name);
			$image->move(public_path($destinationPath), $name);
			$doctor = Drug::where('id', $request->drug_id)->update(['image' => 'drugs/'.strtolower(now()->monthName).'/'.$name]);			
		}
        
        $drug = Drug::find($request->drug_id);

        $drug->trade_name = $request->trade_name;
        $drug->generic_name = $request->generic_name;
        $drug->note = $request->note;
        $drug->rate = $request->rate;
        $drug->type_sell = $request->type_sell;
        $drug->manufacturer = $request->manufacturer;
        $drug->country_origin = $request->country_origin;
        $drug->salt = $request->salt;
        $drug->uses = $request->uses;
        $drug->alternate = $request->alternate;
        $drug->side_effect = $request->side_effect;
        $drug->direction_use = $request->direction_use;
        $drug->therapeutic = $request->therapeutic;

        $drug->save();

        return Redirect::route('drug.all')->with('success', __('sentence.Drug Edited Successfully'));

    }

        public function destroy($id){

        Drug::destroy($id);
        return Redirect::route('drug.all')->with('success', __('sentence.Drug Deleted Successfully'));

    }
}
