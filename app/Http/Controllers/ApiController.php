<?php

namespace App\Http\Controllers;

use \stdClass;
use Illuminate\Http\Request;
use App\Http\Requests;
use DateTime;
use App\User;
use App\Patient;
use App\Doctor;
use App\Nurse;
use App\Appointment;
use App\Drug;
use App\Prescription;
use App\Setting;
use App\Order;
use App\Coupon;
use App\Test;
use App\Package;
use App\LabBooking;
use App\NurseBooking;
use App\AmbulanceBooking;
use App\Rating;
use Response;
use Redirect;
use Hash;
use Validator;
use DB;
use App\Chat;
use App\Speciality;
use Illuminate\Support\Facades\Storage;

use App\Notification\Notification;

class ApiController extends Controller
{

    public function Test_Mail(Request $request){
        $information =  $this->SendMail('UdaipurMedOA'.'23', 'Khushal Yadav', 550);

        return Response::json(
            array(
                'status' => true,
                'msg' => '',
                "data"=>$information
            ),
            200
        );
    }

    public function checksms(Request $request){
        $information =  $this->Send_SMS('Hi, Your OTP is 987654', '91'.''.'9785558507');

        return Response::json(
            array(
                'status' => true,
                'msg' => '',
                "data"=>$information
            ),
            200
        );
    }

    public function paymentget(Request $request){
        //$payment_id, $user_id, $pay_for, $payfor_id
        $this->SavePaymentRecord( 'pay_HZXg1WeHq2XyY1', '21', 'appointment', '55' );
        return Response::json(
            array(
                'status' => true,
                'msg' => '',
                "data"=>''
            ),
            200
        );
    }

    public function User_Login(Request $request)
    {
        $this->validate($request, ['email'=>'required|email', 'password' => 'required']);

        $user_email = $request->input('email');
        $user_password = $request->input('password');

        $patient = User::where(['email'=>$user_email])->first();
        

        if (!$patient) {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'No user found using this email address'
                ),
                201
            );
        }

        if($patient->role == "patient"){
            $patientdata = Patient::where([ 'user_id'=>$patient->id])->first();

        }else{
            $patientdata = Doctor::where([ 'user_id'=>$patient->id])->first();

        }

        if (Hash::check($user_password, $patient->password)) {
            $patientdata->email = $patient->email;
            $patientdata->name = $patient->name;
            $patientdata->role = $patient->role;
            return Response::json(
                array(
                    'status' => true,
                    'data' => $patientdata
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Password does not match, please try again with correct password'
                ),
                201
            );
        }
    }


    public function OTP_Login(Request $request)
    {
        //$this->validate($request, ['mobile' => 'mobile']);
        $mobile = $request->input('mobile');
        $patient = Patient::where(['phone'=>$mobile])->first();

        if (!$patient) {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'No user found using this mobile number'
                ),
                201
            );
        }

        $patientdata = User::where([ 'id'=>$patient->user_id])->first();

        if ($patient) {
            $six_digit_random_number = random_int(1000, 9999);
            $information =  $this->Send_SMS('Your OTP is '.$six_digit_random_number.', for login using UdaipurMed, Thanks!', $mobile);
          //  $information =  $this->Send_SMS('Hi, Your OTP is '.$six_digit_random_number, '91'.''.'9785558507');
            $information = '';
            $patient->email = $patientdata->email;
            $patient->name = $patientdata->name;
            $patient->role = $patientdata->role;
            return Response::json(
                array(
                    'status' => true,
                    'data' => $patient,
                    'sms' => $information,
                    'otp' => $six_digit_random_number
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error while getting user'
                ),
                201
            );
        }
    }

    public function Create_Patient(Request $request)
    {
        $this->validate($request, ['email'=>'required|email', 'password' => 'required', ]);

        $usercheckdata = User::where(['email'=>$request->email])->first();
        if ($usercheckdata) {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'User with this email already exists..'
                ),
                201
            );
        }

        $usercheckphone = Patient::where(['phone'=>$request->phone])->first();
        if ($usercheckphone) {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'User with this phone number already exists..'
                ),
                201
            );
        }

        $user = new User();
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();

        $patient = new Patient();
  

        $patient->user_id = $user->id;
        $patient->birthday = $request->birthday;
        $patient->phone = $request->phone;
        $patient->gender = $request->gender;
        $patient->blood = $request->blood;
        $patient->address = $request->address;
        $patient->weight = $request->weight;
        $patient->height = $request->height;
        $patient->save();

        //send to save only
        $patient->email = $user->email;
        $patient->name = $user->name;
        if ($user && $patient) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $patient,
                    'msg' => 'User Created Successfully, Please Wait..'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while saving your user, please try again..'
                ),
                201
            );
        }
    }

    public function Get_Patients()
    {
        $patients = User::where('role', 'patient')->get();
        if ($patients) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $patients
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'error' => ['msg' => 'Patient data not found'],
                ),
                404
            );
        }
    }

    public function Get_Specialities()
    {
        $specialities = Speciality::where('is_deleted', 0)->get();
        if ($specialities) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $specialities
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'error' => ['msg' => 'specialities data not found'],
                ),
                404
            );
        }
    }


    public function App_Landing(Request $request)
    {
        if ($request->latitude) {
            $lat = $request->latitude;
        }
        if ($request->longitude) {
            $long = $request->longitude;
        }

        $doctors = Doctor::where('is_deleted', 0)->get();
        $specialities = new stdClass();
        $doctors_featured = Doctor::where('is_deleted', 0)->get();
        $coupons_offers = Coupon::where('is_deleted', 0)->get();
        $ResData = new \stdClass();
        $ResData -> top_doctors = $doctors;
        $ResData -> doctors_featured = $doctors;
        $ResData -> specialities = $specialities;
        $ResData -> coupons = $coupons_offers;
        return Response::json(
            array(
                    'status' => true,
                    'data' => $ResData
                ),
            200
        );
    }

    public function Doctor_By_ID(Request $request)
    {
        $doctor_id = $request->user_id;

        $doctor = Doctor::where([ 'user_id'=>$doctor_id])->first();
        if ($doctor) {
            return Response::json(
                array(
                'status' => true,
                'data' => $doctor
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'error' => ['msg' => 'Doctor data not found'],
            ),
                404
            );
        }
    }

    public function Get_Nurses(Request $request)
    {
        $Nurses = Nurse::where('is_deleted', 0)->get();
        if ($Nurses) {
            return Response::json(
                array(
                'status' => true,
                'data' => $Nurses
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => true,
                'msg' => 'Nurses not found'
            ),
                201
            );
        }
    }

    public function Doctors_By_Speciality(Request $request)
    {
        $speciality = $request->speciality;

        $doctor = Doctor::where([ 'speciality'=>$speciality, 'is_deleted'=>0])->get();
        if ($doctor) {
            return Response::json(
                array(
                'status' => true,
                'data' => $doctor
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'data' => 'Doctors data not found by this speciality'
            ),
                201
            );
        }
    }

    public function Check_Appointment(Request $request)
    {
        $user_id = $request->user_id;
        $doctor_id = $request->doctor_id;
        $appointment_time = $request->appointment_time;
        $date = $request->date;
        $time_start = $request->time_start;
        $in_clinic_var = $request->in_clinic;

        $appointmentcheck = Appointment::where([ 'user_id'=>$user_id, 'doctor_id'=>$doctor_id, 'date'=>$date])->first();
   
        if ($appointmentcheck) {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Appointment already exists..',
                'data' => $appointmentcheck
            ),
                201
            );
        }else{

            //-------------check if doctor have schedule for this timing------------

            $appointmentcheckdoctor = Appointment::where(['doctor_id'=>$doctor_id, 'date'=>$date, 'time_start'=>$time_start])->first();

            if ($appointmentcheckdoctor) {
                return Response::json(
                    array(
                    'status' => false,
                    'msg' => 'Doctor schedule is busy for another patient for selected time, please select another schedule',
                    'data' => $appointmentcheck
                ),
                    201
                );
            }else{
            return Response::json(
                array(
                'status' => true,
                'msg' => 'Proceed',
                'data' => $appointmentcheck
            ),
                200
            );

        }

        }

    }

    public function Book_Appointment(Request $request)
    {
        $user_id = $request->user_id;
        $doctor_id = $request->doctor_id;
        $appointment_time = $request->appointment_time;
        $date = $request->date;
        $payment_id = $request->payment_id;
        $in_clinic_var = $request->in_clinic;
        $paid = $request->paid;

        $appointmentcheck = Appointment::where([ 'user_id'=>$user_id, 'doctor_id'=>$doctor_id, 'date'=>$date])->first();
   
        if ($appointmentcheck) {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Appointment already exists..',
                'data' => $appointmentcheck
            ),
                201
            );
        }

        $Appointment = new Appointment();
        $Appointment->user_id = $request->user_id;
        $Appointment->doctor_id = $request->doctor_id;
        $Appointment->date = $request->date;
        $Appointment->time_start = $request->time_start;
        $Appointment->first_time = $request->first_time;
        $Appointment->covid_symptoms = $request->covid_symptoms;
        $Appointment->payment_id = $payment_id;
        $Appointment->in_clinic = $in_clinic_var;
        $Appointment->paid = $paid;
        if ($request->description) {
            $Appointment->description = $request->description;
        }
        $Appointment->save();
        
        // save payment record--------------------------------------------------------
       if($payment_id){ $this->SavePaymentRecord( $payment_id, $request->user_id, 'appointment', $Appointment->id ); }

        if ($Appointment) {
            //send email using controller function------------------------
            $userData = User::where([ 'id'=>$user_id])->first();
            $this->SendMail('UdaipurMedOA'.$Appointment->id, $userData->name, $userData->email , $paid);
            // Sending Appointment Message-------------------------------------------------------------------------------
            //$mobile = '9785558507';
            $UserData = Patient::where([ 'user_id'=>$request->user_id])->first();
            $information =  $this->Send_SMS("Thanks for booking an appointment on .'$request->date'. at '.$request->time_start.', your appointment has been confirmed using UdaipurMed, Thanks!", "91"."".$UserData->phone);
            //-----------------------------------------------------------------------------------------------------------
            return Response::json(
                array(
                    'status' => true,
                    'data' => $Appointment,
                    'already' =>$information,
                    'msg' =>'Your appointment has been created successfully..'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'appointment not created',
                ),
                201
            );
        }
    }

    public function Appointment_Rating(Request $request){
  
        $rating = new Rating();
        $rating->user_id = $request->user_id;
        $rating->count = $request->rating;
        $rating->feedback = $request->feedback;
        $rating->doctor_id = $request->doctor_id;
        $rating->appointment_id = $request->appointment_id;
        //update appointment-------------------------------------------------------------------------

        $appoint = Appointment::find($request->appointment_id);
        $appoint->is_rated = 1;
        $appoint->save();

        //update doctor rating-----------------------------------------------------------------------
        $AllRatings = Rating::where([ 'doctor_id'=>$request->doctor_id])->get();
        $DoctorRatings = count($AllRatings);

        if($DoctorRatings != 0){
            $RatingsTotalValue = 0;
            foreach ($AllRatings as $value) {
                // add value----------------
                if($value->count){$RatingsTotalValue = $RatingsTotalValue + $value->count;}
            }
    
            $Average = $RatingsTotalValue / $DoctorRatings;
            //update doctor rating-----------------------------------------------------------------------
    
        }else{
            $Average = $rating->count;
        }
        $DoctorRecord = Doctor::where('user_id', $request->doctor_id)->update(['rating' => $Average]);
       
       // print_r($DoctorRecord); die(); exit;

        //-------------------------------------------------------------------------------------------
        $rating->save();

        if ($rating ) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $rating,
                    'msg' => 'Rating saved successfully..'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Rating not saved..'
                ),
                201
            );
        }

    }

    public function User_Appointments(Request $request)
    {
        $user_id = $request->user_id;

        if (!$user_id) {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Please provide user ID..'
            ),
                201
            );
        }
        $Appointments = Appointment::leftJoin
        ('doctors', 'appointments.doctor_id', '=', 'doctors.user_id')->
        select('appointments.id','appointments.date','appointments.time_start','appointments.is_rated','appointments.in_clinic',
        'appointments.report_image', 'appointments.pres_image', 
         'appointments.doctor_id', 'doctors.user_id', 'doctors.name', 'doctors.speciality',
        'doctors.city', 'doctors.image'
        )->
        where('appointments.user_id', $user_id)->get();


        if ($Appointments) {
            return Response::json(
                array(
                'status' => true,
                'data' => $Appointments
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'error' => ['msg' => 'Appointment data not found'],
            ),
                404
            );
        }
    }

    public function Upload_Prescription(Request $request)
    {
        // $image = $request->base64_image;
        // $image = str_replace('data:image/png;base64,', '', $image);
        // $image = str_replace(' ', '+', $image);
        // $rand_no = rand(1000, 100000);
        // $imageName =   $request->user_id.'_'.$rand_no.'pres.png';

        $image = $request->input('base64_image'); // image base64 encoded
        preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
        Storage::disk('prescription')->put($imageName,base64_decode($image));
    
        Storage::disk('prescription')->put($imageName, base64_decode($image));
        $imagepath = 'prescription/'.$imageName;
        //save prescription data----------------------------------------------------
        $prescription = new Prescription();
        $prescription->user_id = $request->user_id;
        $prescription->advices = $request->advices;
        $prescription->image = $imagepath;
        $prescription->save();

        if ($prescription) {
            // Sending Medicine Order Message-------------------------------------------------------------------------------
            $UserData = Patient::where([ 'user_id'=>$request->user_id])->first();
            $mobile = '9785558507';
            $information =  $this->Send_SMS('Your medicine order has been received successfully, we will update you soon, you can also on check on your orders, thanks for using UdaipurMed.', '91'.''.$mobile);
            //-----------------------------------------------------------------------------------------------------------
            return Response::json(
                array(
                'status' => true,
                'data' => $prescription
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Prescription not saved, please try again..'
            ),
                201
            );
        }
    }

    public function Upload_RX_Required(Request $request)
    {

        $image = $request->input('base64_image'); // image base64 encoded
        preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
        Storage::disk('prescription')->put($imageName,base64_decode($image));
    
        Storage::disk('prescription')->put($imageName, base64_decode($image));
        $imagepath = 'prescription/'.$imageName;
        //save prescription data----------------------------------------------------

        if ($imagepath) {
            // Sending Medicine Order Message-------------------------------------------------------------------------------
           return Response::json(
                array(
                'status' => true,
                'data' => $imagepath,
                'msg' => 'Prescription image uploaded, you can proceed with same..'
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Prescription image not saved, please try again..'
            ),
                201
            );
        }
    }

    public function Attach_Report(Request $request)
    {

        $image = $request->input('base64_image'); // image base64 encoded
        preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
        Storage::disk('prescription')->put($imageName,base64_decode($image));
    
        Storage::disk('prescription')->put($imageName, base64_decode($image));
        $imagepath = 'prescription/'.$imageName;
        //save prescription data----------------------------------------------------

        if ($imagepath) {
            $appoint = $request->input('appoint_id');
            $user_id = $request->input('user_id');
            $appoint_update = Appointment::where('id', $appoint)->
            update(['report_image' => $imagepath]);
            // -------------------------------------------------------------------------------
           return Response::json(
                array(
                'status' => true,
                'data' => $imagepath,
                'msg' => 'Report image uploaded, you can proceed with same..'
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Report image not saved, please try again..'
            ),
                201
            );
        }
    }


    public function Attach_Prescription(Request $request)
    {

        $image = $request->input('base64_image'); // image base64 encoded
        preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
        $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
        $image = str_replace(' ', '+', $image);
        $imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
        Storage::disk('prescription')->put($imageName,base64_decode($image));
    
        Storage::disk('prescription')->put($imageName, base64_decode($image));
        $imagepath = 'prescription/'.$imageName;
        //save prescription data----------------------------------------------------

        if ($imagepath) {
            $appoint = $request->input('appoint_id');
            $user_id = $request->input('user_id');
            $appoint_update = Appointment::where('id', $appoint)->
            update(['pres_image' => $imagepath]);
            // -------------------------------------------------------------------------------
           return Response::json(
                array(
                'status' => true,
                'data' => $imagepath,
                'msg' => 'Prescription image uploaded, you can proceed..'
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Prescription image not saved, please try again..'
            ),
                201
            );
        }
    }

    public function Update_Profile_Image(Request $request)
        {

            $image = $request->input('base64_image'); // image base64 encoded
            preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
            $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
            $image = str_replace(' ', '+', $image);
            $imageName = 'image_' . time() . '.' . $image_extension[1]; //generating unique file name;
            Storage::disk('profile')->put($imageName,base64_decode($image));
            $imagepath = 'profile/'.$imageName;
            //save profile data----------------------------------------------------
            $patient = Patient::where('user_id', $request->user_id)->update(['image' => $imagepath]);

            if ($patient) {
                return Response::json(
                    array(
                        'status' => true,
                        'data' => $imageName,
                        'msg' => 'Profile image updated..'
                    ),
                    200
                );
            }else{
                return Response::json(
                    array(
                        'status' => false,
                        'data' => $imageName,
                        'msg' => 'Profile not updated'
                    ),
                    201
                );
            }
    }

    public function Get_Recent_Prescriptions(Request $request)
    {
        $user_id = $request->user_id;
        $pres = Prescription::where('prescriptions.user_id', $user_id)->get();
        ;
        if ($pres) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $pres,
                    'msg' => 'Prescriptions Fetched Successfully, Please Wait..'
                ),
                200
            );
        }
    }

    public function Search_Drugs(Request $request)
    {
        $medicine_name = $request->medicine_name;
        $drugs = Drug::where('generic_name', 'like', "%{$medicine_name}%")->get();

        if ($drugs) {
            return Response::json(
                array(
                'status' => true,
                'data' => $drugs
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Medicine not found'
            ),
                201
            );
        }
    }

    public function Some_Drugs(Request $request)
    {
        $medicine_name = $request->medicine_name;
        $drugs = Drug::take(50)->get();

        if ($drugs) {
            return Response::json(
                array(
                'status' => true,
                'data' => $drugs
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Medicine not found'
            ),
                201
            );
        }
    }


    public function Search_Tests(Request $request)
    {
        $test_name = $request->test_name;
        $testall = Test::where('test_name', 'like', "%{$test_name}%")->get();

        if ($testall) {
            return Response::json(
                array(
                'status' => true,
                'data' => $testall
            ),
                200
            );
        } else {
            return Response::json(
                array(
                'status' => false,
                'msg' => 'Test not found by this name'
            ),
                201
            );
        }
    }

    public function Create_Order(Request $request)
    {
        $order = new Order();

        $order->user_id = $request->user_id;
        $order->medicines = $request->medicines;
        $order->payment_id = $request->payment_id;
        $order->payment_type = $request->payment_type;
        $order->rx_img = $request->rx_img;
        $order->paid = $request->paid;
        $order->type = 'MedicineOrder';

        if($order->payment_type == 'ONLINE'){
            $order->is_paid = 1;
        }else{
            $order->is_paid = 0;
        }
        
        $order->save();

        if ($order) {
            $UserDataMain = User::where([ 'id'=>$request->user_id])->first();
            $UserData = Patient::where([ 'user_id'=>$request->user_id])->first();
            //send email-----------------------------------------------------
            $all_medicines = json_decode($request->medicines ,true);
         //   print_r($all_medicines); die(); exit;
            $this->SendMail_OrderMedicine($all_medicines, $UserDataMain->email, $request->paid, $order->is_paid, $UserDataMain->name, 'UDMED-MED-'.''.$order->id);
            // Sending Medicine Order Message-------------------------------------------------------------------------------
            
            $information =  $this->Send_SMS('Your medicine order has been received successfully, we will update you soon, you can also on check on your orders, thanks for using UdaipurMed.', '91'.''.$UserData->phone);
            //-----------------------------------------------------------------------------------------------------------
            return Response::json(
                array(
                    'status' => true,
                    'data' => $order,
                    'msg' => 'Order Created Successfully, Please Wait..'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while saving your order, please try again..'
                ),
                201
            );
        }
    }

    public function Get_Orders(Request $request)
    {
        $order = Order::where('user_id', $request->user_id)->get();
       

        foreach ($order as $key=>$value) {
            $order[$key]->medicines = json_decode($value->medicines);
        }


        if ($order) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $order,
                    'msg' => 'Order fetched Successfully'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $order,
                    'msg' => 'Order not found'
                ),
                201
            );
        }
    }

    public function Get_UserData(Request $request)
    {
        $id = $request->user_id;
        $patient = Patient::where('user_id', $id)->first();
        $user = User::where('id', $id)->first();

        $patient->name = $user->name;
        $patient->email = $user->email;
        if ($patient) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $patient,
                    'msg' => 'User Fetched'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching user data, please try again..'
                ),
                201
            );
        }
    }

    public function Get_App_Coupons(Request $request)
    {
        $coupons = Coupon::where('is_deleted', 0)->get();
        if ($coupons) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $coupons,
                    'msg' => 'coupons Fetched'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching coupons data, please try again..'
                ),
                201
            );
        }
    }

    public function Get_Lab_Test(Request $request)
    {
        $test = Test::get();
        if ($test) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $test,
                    'msg' => 'test Fetched'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching test data, please try again..'
                ),
                201
            );
        }
    }


    public function Create_NurseBooking(Request $request)
    {
        $Nursebooking = new NurseBooking();
        $Nursebooking->patient_id = $request->user_id;
        $Nursebooking->nurse_id = $request->nurse_id;
        $Nursebooking->visit_date = $request->date;
        $Nursebooking->visit_time = $request->time;
        $Nursebooking->payment_id = $request->payment_id;
        $Nursebooking->paid = $request->paid;
        $Nursebooking->save();

        if ($Nursebooking) {
            //send email to user---------------------------------------
            $userData = User::where([ 'id'=> $request->user_id])->first();
            $nurseData = Nurse::where([ 'user_id'=> $request->nurse_id])->first();
            $this->Send_NurseBookingMail
            (
                $userData->name, 
                $userData->email,
                $request->date.' '.$request->time,
                $request->payment_id,
                $request->paid,
                $nurseData->name,
                'UDMEDNUBK'.''.$Nursebooking->id
            );

            return Response::json(
                array(
                    'status' => true,
                    'data' => $Nursebooking,
                    'msg' => 'Nurse visit booking Created Successfully,'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while saving your Nurse visit booking, please try again..'
                ),
                201
            );
        }
    }

    public function Get_NurseBooking(Request $request)
    {
        if (!$request->user_id) {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Please provide user id to get nurse visit bookings'
                ),
                201
            );
        }

        $nursebookings = NurseBooking::leftJoin('nurses', 'nursebooking.nurse_id', '=', 'nurses.user_id')->where('patient_id', $request->user_id)->get();

        if ($nursebookings) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $nursebookings,
                    'msg' => 'Nurse visit booking fetched Successfully,'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching your Nurse visit booking, please try again..'
                ),
                201
            );
        }
    }


    public function Create_LabBooking(Request $request)
    {
        $LabBooking = new LabBooking();
        $LabBooking->time = $request->time;
        $LabBooking->date = $request->date;
        $LabBooking->payment_id = $request->payment_id;
        $LabBooking->paid = $request->paid;
        if (!$request->package_selected) {
            $LabBooking->user_id = $request->user_id;
            $LabBooking->test_data = $request->test_data;
        } else {
            $LabBooking->user_id = $request->user_id;
            $LabBooking->package_selected = $request->package_selected;
            $LabBooking->package_id = $request->package_id;
        }


        $LabBooking->save();

        if ($LabBooking) {
            //send email to user--------------------------------------------------------
            $UserData = Patient::where([ 'user_id'=>$request->user_id])->first();
            $UserDataMain = User::where([ 'id'=>$request->user_id])->first();
            $this->SendLabBookingEmail(
                $UserDataMain->name,
                $UserDataMain->email,
                'UDMEDLABBK'.''.$LabBooking->id,
                $request->payment_id,
                $request->paid
            );
            // Sending Medicine Order Message-------------------------------------------------------------------------------
            $information =  $this->Send_SMS('Your lab test request received successfully for date '.$LabBooking->created_at.', our team will contact you soon for sample collection, Thanks for using UdaipurMed.', '91'.''.$UserData->phone);
            //-----------------------------------------------------------------------------------------------------------
            return Response::json(
                array(
                    'status' => true,
                    'data' => $LabBooking,
                    'msg' => 'Lab Booking Created Successfully,'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while saving your LabBooking, please try again..'
                ),
                201
            );
        }
    }

    

    public function Get_Packages(Request $request)
    {
        $Package = Package::where('is_active', 1)->get();
        if ($Package) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $Package,
                    'msg' => 'Package Fetched'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching Package data, please try again..'
                ),
                201
            );
        }
    }

    public function LabTest_Orders(Request $request)
    {
        $LabBooking = LabBooking::where('user_id', $request->user_id)->get();

        if ($LabBooking) {
            foreach ($LabBooking as $key=>$value) {
                if($value->package_selected == 1){

                    $PackageData = Package::where('id', $value->package_id)->get();
                    $LabBooking[$key]->package_data;

                }else{
                    if ($value->test_data) {
                        $LabBooking[$key]->test_data = json_decode($value->test_data);
                    }
                }
                
            }
        }

        if ($LabBooking) {
            return Response::json(
                array(
                    'status' => true,
                    'data' => $LabBooking,
                    'msg' => 'Package Fetched'
                ),
                200
            );
        } else {
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Error occured while fetching Package data, please try again..'
                ),
                201
            );
        }
    }


    

    public function Update_User(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['required'],
            'gender' => ['required'],
        ]);

        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->update();

        $patient = Patient::where('user_id', $request->user_id)
                    ->update(['birthday' => $request->birthday,
                    'gender' => $request->gender,
                    'blood' => $request->blood,
                    'address' => $request->address,
                    'weight' => $request->weight,
                    'height' => $request->height]);

        return Response::json(
            array(
                'status' => true,
                'data' => $patient,
                'msg' => 'User Updated Successfully..'
            ),
            200
        );
    }



    public function Get_User_Chats(Request $request){ 
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'to_user_id' => 'required',
        ]);

        if ($validator->fails())
        {
            $errorString = implode(",",$validator->messages()->all());
            $valierror = $validator->errors();
            return response()->json([
                'ResponseCode' => '203',
                'ResponseMessage' => $errorString
            ]);

        }else{

            $user_id = $request->input('user_id');
            $to_user_id = $request->input('to_user_id');


        $matchThese = ['user_id' => $user_id, 'to_user_id' => $to_user_id];
        $matchthese_too = ['user_id' => $to_user_id, 'to_user_id' => $user_id];
        $chats =  DB::table('chat')->where($matchThese)->orWhere($matchthese_too)->get();
      
        if ($chats) {
            return Response::json(
                            array(
                        'status' => true,
                        'data' => $chats
                            ), 200
            );
        } else {
            return Response::json(
                            array(
                        'error' => [
                            'message' => "Something wrong with your Information"
                        ]
                            ), 404
            );
        }
    }
}


public function Send_Msg(Request $request){

    $validator = Validator::make($request->all(), [
        'user_id' => 'required',
        'to_user_id' => 'required',
        'message' => 'required'
    ]);

    if ($validator->fails())
    {
        $errorString = implode(",",$validator->messages()->all());
        $valierror = $validator->errors();
        return response()->json([
            'ResponseCode' => '203',
            'ResponseMessage' => $errorString
        ]);

    }else{

        $user_id = $request->input('user_id');
        $to_user_id = $request->input('to_user_id');
        $message = $request->input('message');
        
        $created_date=date('Y-m-d H:i:s');
        $query = Chat::insert([
            'user_id' => $user_id,
            'to_user_id' => $to_user_id,
            'message' => $message,
            'message_time' => $created_date,
            'status' => 'Sent'
        ]);
       // $last_id = Order::getPdo()->lastInsertId();
       $last_id = DB::getPdo()->lastInsertId();
       $uInformation = DB::table('chat')->where("message_id", $last_id)->first();

      //  $uInformation = Order::where("id", $last_id)->first();

        if($query == true)
        {   
            return response()->json([
                'ResponseCode' => '200',
                'ResponseMessage' => 'Message Sent Successfully',
                'Chat' => $uInformation
                //'Info' => $uInformation,
            ]);

        }else{

            return response()->json([
                'ResponseCode' => '203',
                'ResponseMessage' => 'Order Placed Failed',
            ]);
        }

    }

}

public function Book_Emergency_Service(Request $request)
{
    $user_id = $request->user_id;
    $description = $request->description;
    $booking_time = $request->booking_time;
    $city = $request->city;
    $state = $request->state;
    $area = $request->area;
    $address = $request->address;
    $need_radiology = $request->need_radiology;

    $appointmentcheck = AmbulanceBooking::where([ 'user_id'=>$user_id, 'booking_time'=>$booking_time])->first();

    if ($appointmentcheck) {
        return Response::json(
            array(
            'status' => false,
            'msg' => 'Your ambulance request has already recieved, please wait for our support team to reach you',
            'data' => $appointmentcheck
        ),
            201
        );
    }

    $AmbulanceBooking = new AmbulanceBooking();
    $AmbulanceBooking->user_id = $request->user_id;
    $AmbulanceBooking->booking_time = $booking_time;
    $AmbulanceBooking->city = $city;
    $AmbulanceBooking->state = $state;
    $AmbulanceBooking->area = $area;
    $AmbulanceBooking->address = $address;
    $AmbulanceBooking->pincode = $request->pincode;
    if($need_radiology == true){$AmbulanceBooking->need_radiology = $need_radiology;}

    if ($request->description) {
        $AmbulanceBooking->description = $request->description;
    }
    $AmbulanceBooking->save();

    if ($AmbulanceBooking) {
        // // Sending Appointment Message-------------------------------------------------------------------------------
        // $mobile = '9785558507';
        
        $UserData = Patient::where([ 'user_id'=>$request->user_id])->first();
        $information =  $this->Send_SMS("Thanks for booking an appointment on .'$request->date'. at '.$request->time_start.', your appointment has been confirmed using UdaipurMed, Thanks!", "91"."".$UserData->phone);
        // //-----------------------------------------------------------------------------------------------------------
         return Response::json(
            array(
                'status' => true,
                'data' => $AmbulanceBooking,
                'msg' =>'Your ambulance request has been created successfully..'
            ),
            200
        );
    } else {
        return Response::json(
            array(
                'status' => false,
                'msg' => 'ambulance request not sent, please contact support',
            ),
            201
        );
    }
}

public function Check_Pincode(Request $request){

    $settings = Setting::where([ 'option_name'=>'pincode_av'] )->first();
    $pincodes = explode(' ', $settings->option_value);

    if( in_array( $request->pincode ,$pincodes ) )
    {
        return Response::json(
            array(
                'status' => true,
                'msg' => 'Proceed',
            ),
            200
        );
    }else{
        return Response::json(
            array(
                'status' => false,
                'msg' => 'Pincode is not under our service area region',
            ),
            200
        );
    }
}

public function CheckCoupon(Request $request){

		$coupon = Coupon::where(['code'=>$request->couponCode])->first();

        // check date is beetween--------------------------------------------------------
        $checkexpiry = 0;
        if($coupon){
            $startdateC = $coupon->startingdate;
            $startdateC = str_replace( "/" ,"-" ,  $startdateC );
            $enddateC = $coupon->endingdate;
            $enddateC = str_replace( "/" ,"-" ,  $enddateC );
            $paymentDate = date('Y-m-d');
            $paymentDate=date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($startdateC));
            $contractDateEnd = date('Y-m-d', strtotime($enddateC));
            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
                
                $checkexpiry = 1;
            }else{
                
            }
        }

        //-------------------------------------------------------------------------------

        if( $coupon && $checkexpiry==1)
        {
            return Response::json(
                array(
                    'status' => true,
                    'msg' => 'Proceed',
                    'data' => $coupon
                ),
                200
            );
        }else{
            return Response::json(
                array(
                    'status' => false,
                    'msg' => 'Coupon is not valid or expired..',
                ),
                201
            );
        }
 
    
}




}
