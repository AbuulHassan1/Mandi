<?php

namespace App\Http\Controllers;

use App\models\MandiPurchaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MandiSaleControllr extends Controller
{
    public function create()
    {

//        dd("here");
        $data['title'] = 'Mandi Sale';
        $items = DB::table('items')->select('*')->get();

        return view('mandi_sale.create',compact('items' ), $data);
    }

    public function store(Request $request){
        MandiPurchaseModel::create($request->all());
        Session::flash('message', 'Purchase Added Successfully');
        Session::flash('class', 'success');
        return redirect()->route('CustomerList');
//        return view('mandi_purchase.list_purchase');

    }

    public function list(){
        $purchases = DB::table('mandi_purchase_models as purchase')
            ->join('mandi_customer_models as customer', 'purchase.customer_id', '=', 'customer.id')
            ->join('items', 'purchase.item_id', '=', 'items.id')
            ->select('purchase.*', 'customer.name as customer', 'items.name as item' )
            ->get();
        $data['title'] = 'Purchase List';
        return view('mandi_purchase.list_purchase',compact('purchases' ), $data);
    }


    // ============ Purchase Invoice Start From Here ===============
    public function saleinvoice($id)
    {

        $details = DB::table('mandi_purchase_models as purchase')
            ->join('mandi_customer_models as customer', 'purchase.customer_id', '=', 'customer.id')
            ->join('items', 'purchase.item_id', '=', 'items.id')
            ->select('purchase.*', 'customer.name as name','customer.phone as phone' ,
                'customer.address as address' , 'items.name as item' )
            ->where( 'purchase.id' , '=', $id)
            ->get();
        $details = $details[0];

        if (!$details) return abort(404);

        // For Getting Company Detail
        $sett = Setting::all();
        $setting = new \stdClass();
        $setting->company_name = $sett[0]->title;
        $setting->company_address = $sett[1]->title;
        $setting->company_email = $sett[2]->title;
        $setting->company_phone = $sett[3]->title;
        $setting->trn = $sett[4]->title;
        $setting->vat = $sett[5]->title;


        //  For Getting purchase detail
        $purchasedetails = DB::table('purchase_details')
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->join('vendors', 'purchases.vendor_id', '=', 'vendors.id')
            ->join('products', 'products.id', '=', 'purchase_details.product_id')
            ->select('purchases.id',
                'vendors.id as v_id',
                'purchase_details.quantity',
                'purchase_details.unit_price',
                'purchase_details.sale_price',
                'purchase_details.discount',
                'products.id as p_id',
                'products.name as product')
            ->where('purchases.id', '=', $id)
            ->get();

        return view('mandi_purchase.mandi_purchase_invoice', compact('purchasedetails', 'details', 'setting'));
    }

}
