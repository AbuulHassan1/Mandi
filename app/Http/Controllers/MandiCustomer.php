<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\MandiCustomerModel;
use Illuminate\Support\Facades\DB;

class MandiCustomer extends Controller
{
    public function index(){

        $customers = DB::table('mandi_customer_models')
            ->select('*')->get();
        $data['title'] = 'Mandi Customer';

        return view('mandi_customer.create', compact('customers'), $data);

    }

    public function store(Request $request){

        $MandiCustomer = new MandiCustomerModel();
        $MandiCustomer->name = $request->customer_name;
        $MandiCustomer->phone = $request->customer_phone;
        $MandiCustomer->address = $request->customer_address;
        $result = $MandiCustomer->save(); // return a boolean for success/fail

        $message = $result ? "Customer Added Successfully" : "Customer Added failed!";
        // Response
        if ($request->ajax())
        {
            // Ajax response, will translate to JSON
            return  Array(
            'Success' => $result,
            'Message' => $message,
            'data' => $MandiCustomer
        );
        }

        // Regular Http response
        Session::flash('save-response', $message);

        Session::flash('message', $message);
                Session::flash('class', 'success');
        return ($MandiCustomer);
        //        return redirect()->route('mandi_customer');

    }
}
