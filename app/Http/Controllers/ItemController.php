<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function create(){

        $items = DB::table('items')
            ->select('*')->get();
        $data['title'] = 'Mandi Items';

        return view('mandi_item.create', compact('items'), $data);
    }

    public function store(Request $request){

        $item = new Item();
        $item->name = $request->item_name;
        $result = $item->save();
        $message = $result ? "Item Added Successfully" : "Item Added failed!";
        // Response
        if ($request->ajax())
        {
            // Ajax response, will translate to JSON
            return  Array(
                'Success' => $result,
                'Message' => $message,
                'data' => $item
            );
        }

        // Regular Http response
        Session::flash('save-response', $message);

        Session::flash('message', $message);
        Session::flash('class', 'success');
        return ($item);


    }
}
