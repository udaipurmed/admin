<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Drug;
use Hash;
use Redirect;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{

	public function __construct(){
        $this->middleware('auth');
    }


    public function all(){
		// $orders = Order::get();
		// $order_id = $order->user_id;
		// echo $this->belongsTo(User::class);
		$orders = Order::join('users', 'orders.user_id', '=', 'users.id')->get(['orders.*', 'users.name', 'users.email']);
		// echo $orders;
    	return view('order.all', ['orders' => $orders]);
		
    }
	
    public function create(){
		$users = User::get();
		$drugs = Drug::get();
		return view('order.create', ['users' => $users, 'drugs' => $drugs]);
    }
	
    public function edit($id){
		//$order = Order::find($id);
    	//return view('order.edit',['order' => $order]);

		$order = Order::findOrfail($id);
		if($order){
			if($order->medicines)$order->medicines = json_decode($order->medicines);
			$UserData = User::where('id', $order->user_id)->first();

			

		}
    	return view('order.edit', ['order' => $order, 'userdata' => $UserData]);
    }
	
	public function store_edit(Request $request){

    	$validatedData = $request->validate([
        	'user_id' => ['required'],
            'medicines' => ['required'],
            'type' => ['required'],

    	]);

		$order = Order::where('id', $request->id)->update(['medicines' => $request->medicines,
								'type' => $request->type,]);

		return Redirect::back()->with('success', __('sentence.Order Updated Successfully'));

    }

    public function store(Request $request){

		//print_r($request->medicines); die(); exit;

    	$validatedData = $request->validate([
        	'user_id' => ['required'],
            'medicines' => ['required'],
            'type' => ['required'],
    	]);
		$order = new Order();
		$order->user_id = $request->user_id;
		
		// $order->payment_id = $request->payment_id;
		$order->type = $request->type;
		$order->status = 'pending';


		//----------------making same object as JSON-------------------------
		$AllMedicines = $request->medicines;
		$AllPrices = $request->price;
		$AllQTY = $request->qty;
		$arrOrder = array( );
		$index = 0;
		foreach ($AllMedicines as $value) {
			
			$tempprice = array_values($AllPrices)[$index];
			$tempqty = array_values($AllQTY)[$index];

			array_push($arrOrder, (object)[
				'trade_name' => $value,
				'generic_name' => $value,
				'rate' => $tempprice,
				'qty' => $tempqty
			]);

		  }


		  $final_order_data = json_encode($arrOrder, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
		  $order->medicines = $final_order_data;


		$order->save();

		return Redirect::route('order.all')->with('success', __('sentence.Order Created Successfully'));

    }


    public function view($id){

    	$order = Order::findOrfail($id);
		if($order){
			if($order->medicines)$order->medicines = json_decode($order->medicines);
		}
    	return view('order.view', ['order' => $order]);

    }
	public function update($id, $status){

		if($status == 0){
			$order = Order::where('id', $id)->update(['Is_paid' => 1]);
			$activeStatus = 'Order Deleted Successfully!';
		} else{
			$order = Order::where('id', $id)->update(['Is_paid' => 0]);
			$activeStatus = 'Order Added Successfully';
		}
        return Redirect::route('order.all')->with('success', $activeStatus);

    }


}
