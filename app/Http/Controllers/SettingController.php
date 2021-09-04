<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\Setting;

class SettingController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    
    public function doctorino_settings(Request $request){
    	$settings = Setting::where(['option_name' => 'pincode_av'])->first();
        $pincodes = $settings->option_value;
       // print_r($pincodes); die(); exit;
        $language = ['fr' => 'French', 'en' => 'English', 'es' => 'Spanish', 'de' => 'German', 'bn' => 'Bengali'];
    	return view('settings.doctorino_settings', ['pincodes' => $pincodes]);
    }

    public function doctorino_settings_store(Request $request){

        //print_r($request->pincodes);die();exit;

        Setting::where('option_name','pincode_av')->update([ 'option_value' => $request->pincodes ]);
	    //	Setting::update_option('system_name', $request->system_name);

    	return Redirect::route('doctorino_settings.edit')->with('success', __("sentence.Settings edited Successfully") );
    }

    public function prescription_settings(Request $request){

    	return view('settings.prescription_settings');
    }

    public function prescription_settings_store(Request $request){

	    	Setting::update_option('header_right', $request->header_right);
	    	Setting::update_option('header_left', $request->header_left);
	    	Setting::update_option('footer_right', $request->footer_right);
	    	Setting::update_option('footer_left', $request->footer_left);

    	return Redirect::route('prescription_settings.edit')->with('success', __("sentence.Settings edited Successfully"));

	}


}
