<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Client\Response;

class RestaurantTableController extends Controller
{
    public function index()
    {
        $restaurant = session('restaurant');
        $res_tables = RestaurantTable::where('restaurant_id',$restaurant->id)->get();
        $active_tbl = RestaurantTable::where(['restaurant_id' => $restaurant->id, 'status' =>RestaurantTable::ACTIVE])->count();
        return view('table.index',compact('res_tables','active_tbl'));
    }

    public function export_pdf(Request $request)
    {
        $qr_code[] =$request->qr_code;
        $restaurant = session('restaurant');
        $pdf = PDF::loadView('pdf.qr_code', ['qr_code' => $qr_code]);
        // Convert PDF to base64
        $base64Pdf = base64_encode($pdf->output());
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="qr_code.pdf"',
        ];

        return response()->json([
            'success' => true,
            'pdf' => $base64Pdf,
        ], 200, $headers);
    }

    public function exportQrCode(Request $request)
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

    public function show($id)
    {
        
    }

    public function destroy(Request $request)
    {
        RestaurantTable::whereIn('id',explode(",",$request->ids))->delete();
        return response()->json(['status'=>true,'message'=>"Table deleted successfully."]);
    }


}
