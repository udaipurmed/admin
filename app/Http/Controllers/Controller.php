<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Payment;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function dummy()
    {
        return 'dummy';
    }

    protected function Send_SMS($msgPass, $noPass)
    {
        $APIKey = "mUB6lcY3xUGUIeAeuRdmBg";
        $SenderId = "UDPRMD";

        $message = urlencode($msgPass);
        $mobile = urlencode($noPass);
        $url = "http://cloud.smsindiahub.in/api/mt/SendSMS?APIKey=".$APIKey."&senderid=".$SenderId."&channel=Trans&DCS=0&flashsms=0&number=".$mobile."&text=".$message."&route=0";
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    protected function SavePaymentRecord($payment_id, $user_id, $pay_for, $payfor_id){

        $url = "https://rzp_test_V3jVMFjGARHKgo:FAdPg0VJPAZbvmmjGbNvF8R4@api.razorpay.com/v1/payments/".$payment_id;
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);

       // print_r($response); die(); exit;
        // save record-------------------------------------------------------------
        $payments = new Payment();
        if($pay_for == 'appointment'){$payments->appointment_id = $payfor_id;}
        if($pay_for == 'medicine'){$payments->order_id = $payfor_id;}
        $response = json_decode($response);
        $payments->payment_id = $payment_id;
        $payments->user_id = $user_id;
        $payments->type = $response->method;
        $payments->amount_refunded = $response->amount_refunded;
        $payments->captured = $response->captured;
        $payments->amount = $response->amount;
        $payments->forpayment = $pay_for;
        $payments->status = 'paid';
        $payments->save();
        //-------------------------------------------------------------------------
        curl_close($curl);

    }

    protected function SendMail($appoint_id, $customer_name, $email , $paid){
        $data = [
            'no-reply' => 'info@udaipurmed.com',
            'admin'    => $email,
            'customer_name' => $customer_name,
            'email' => $email
          
        ];

        \Mail::send('email.appointment',  ['data' => $data,
         'customer_name' => $customer_name,
         'appoint_id' => $appoint_id,
         'paid' => $paid
        ],
            function ($message) use ($data)
            {
                $message
                    ->from("udaipurmed@gmail.com")
                    ->to("udaipurmed@gmail.com")->subject('Some body wrote to you online')
                    ->to("udaipurmed@gmail.com")->subject('Your submitted information')
                    ->to($data['email'], $data['customer_name'])->subject('Appointment Booked');
            });
    }

    protected function SendMail_OrderMedicine($all_medicines, $email, $payment, $is_paid, $customer_name, $order_id){
       $cust_email = $email;
        $data = [
            'no-reply' => 'info@udaipurmed.com',
            'all_medicines' => $all_medicines,
            'payment' => $payment,
            'is_paid' => $is_paid,
            'email' => $cust_email,
            'customer_name' => $customer_name,
        ];

        \Mail::send('email.medicineorder',  ['data' => $data,
         'customer_name' => $customer_name,
         'order_id' => $order_id,
         'all_medicines' => $all_medicines,
         'payment' => $payment,
         'is_paid' => $is_paid,
         'email' => $cust_email
        ],
            function ($message) use ($data)
            {
                $message
                    ->from("udaipurmed@gmail.com")
                    ->to("udaipurmed@gmail.com")->subject('Some body wrote to you online')
                    ->to("udaipurmed@gmail.com")->subject('Your submitted information')
                    ->to($data['email'], $data['customer_name'])->subject('Order Recieved');
            });
    }



    protected function Send_NurseBookingMail($customer_name, $email, 
    $booking_time , $pay_id, $payment , $nurse_name, $booking_id){

        $data = [
            'no-reply' => 'info@udaipurmed.com',
            'email'    => $email,
            'customer_name' => $customer_name,
            'email' => $email,
            'booking_time' => $booking_time,
            'pay_id' =>$pay_id,
            'payment' => $payment,
            'nurse_name' => $nurse_name,
            'booking_id' =>$booking_id
          
        ];

        \Mail::send('email.nursebooking',  ['data' => $data,
            'email'    => $email,
            'customer_name' => $customer_name,
            'email' => $email,
            'booking_time' => $booking_time,
            'pay_id' =>$pay_id,
            'payment' => $payment,
            'nurse_name' => $nurse_name,
            'booking_id' =>$booking_id
        ],
            function ($message) use ($data)
            {
                $message
                    ->from("udaipurmed@gmail.com")
                    ->to("udaipurmed@gmail.com")->subject('Some body wrote to you online')
                    ->to("udaipurmed@gmail.com")->subject('Your submitted information')
                    ->to($data['email'], $data['customer_name'])->subject('Nurse Visit Appointment Booked');
            });
    }


    protected function SendLabBookingEmail(
        $customer_name,
        $email,
        $booking_id,
        $pay_id,
        $payment){

        $data = [
            'no-reply' => 'info@udaipurmed.com',
            'email'    => $email,
            'customer_name' => $customer_name,
            'email' => $email,
            'pay_id' =>$pay_id,
            'payment' => $payment,
            'booking_id' =>$booking_id
          
        ];

        \Mail::send('email.labbooking',  ['data' => $data,
            'email'    => $email,
            'customer_name' => $customer_name,
            'email' => $email,
            'pay_id' =>$pay_id,
            'payment' => $payment,
            'booking_id' =>$booking_id
        ],
            function ($message) use ($data)
            {
                $message
                    ->from("udaipurmed@gmail.com")
                    ->to("udaipurmed@gmail.com")->subject('Some body wrote to you online')
                    ->to("udaipurmed@gmail.com")->subject('Your submitted information')
                    ->to($data['email'], $data['customer_name'])->subject('Lab Booking Confirmed');
            });
    }


}

