<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantTableController extends Controller
{
    /**
     * Method index
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $restaurant = session('restaurant');
        $res_tables = RestaurantTable::where('restaurant_id',$restaurant->id)->get();
        $active_tbl = RestaurantTable::where(['restaurant_id' => $restaurant->id, 'status' =>RestaurantTable::ACTIVE])->count();
        return view('table.index',compact('res_tables','active_tbl'));
    }

    /**
     * Method export_pdf
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export_pdf(Request $request)
    {
        $table  = RestaurantTable::findOrFail($request->id);
        $qrImage= asset("images/".$table->qr_image);

        return response()->json([
            'success'   => true,
            'pdf'       => $qrImage,
            'name'      => $table->code
        ], 200);
    }

    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return \App\Models\RestaurantTable
     */
    public function store(Request $request)
    {
        $restaurant = session('restaurant');
        $table = RestaurantTable::create([
            'name'  => $request->name,
            'code'  => $request->code,
            'restaurant_id'     => $restaurant->id,
        ]);
        $qr_url = URL::current();

        $qr_code_image = QrCode::size(500)
            ->format('png')
            ->backgroundColor(255,255,255)
            ->generate($qr_url . '/'.$restaurant->id . '/'.$table->code.'/'.$table->id, public_path("images/qrcode_$table->id.png"));

        $imageName = "qrcode_$table->id.png";
        RestaurantTable::where('id',$table->id)->update(['qr_image' => $imageName, 'qr_url' => $qr_url]);

        return $table->refresh();
    }

    /**
     * Method statusUpdate
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(Request $request)
    {
        $restaurant = session('restaurant');
        $res_tbl = RestaurantTable::find($request->id);
        $res_tbl->status = $request->status;
        $res_tbl->save();
        return response()->json(['success'=>'Status change successfully.']);
    }

    /**
     * Method destroy
     *
     * @param Request $request [explicite description]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        RestaurantTable::whereIn('id',explode(",",$request->ids))->delete();
        return response()->json(['status'=>true,'message'=>"Table deleted successfully."]);
    }
}
