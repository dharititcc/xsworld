<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $restaurant = session('restaurant');
        $res_tables = RestaurantTable::where('restaurant_id',$restaurant->id)->get();
        return view('table.index',compact('res_tables'));
    }

    public function create(Request $request)
    {

    }


    public function store(Request $request)
    {
        // dd(URL::current());
        // dd($request->all());
        $restaurant = session('restaurant');
        $table = RestaurantTable::create([
            'name'  => $request->name,
            'code'  => $request->code,
            'restaurant_id'     => $restaurant->id,
        ]);
        $qr_url = URL::current();
        // $qr_code_image = \QrCode::size(300)->generate($qr_url . '/'.$table->id);
        // $qr_code_image = \QrCode::size(500)
            // ->format('png')
            // ->generate($qr_url . '/'.$table->id, public_path('images/qrcode.png'));

        $qr_code_image =    \QrCode::size(212)
            ->generate(
                $qr_url . '/'.$table->id,
            );

        
        // dd($qr_code_image);

        RestaurantTable::where('id',$table->id)->update(['qr_image' => $qr_code_image, 'qr_url' => $qr_url]);
        // dd($qr_code_image);
        return $table->refresh();
    }

    public function statusUpdate(Request $request)
    {
        $restaurant = session('restaurant');
        $res_tbl = RestaurantTable::find($request->id);
        $res_tbl->status = $request->status;
        $res_tbl->save();
        return response()->json(['success'=>'Status change successfully.']);
    }


}
