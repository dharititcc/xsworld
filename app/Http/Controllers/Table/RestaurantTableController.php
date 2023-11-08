<?php

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Client\Response;
use SVG\SVG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $qr_code =$request->qr_code;
        $image = \SVG\SVG::fromString($qr_code);
        header('Content-Type: image/png');
        $image2 = imagepng($image->toRasterImage(650,650),'dharit1.png');


        imagepng($image, 'output.png');
        dd($image->getDocument(), 'image.png');
        // $image2 = new SVG(100, 100);
        // header('Content-Type: image/png');
        // // $image2->toRasterImage(650,650);
        // $docs = $image2->getDocument();

        // $restaurant = session('restaurant');
        // $base64  = base64_encode($qr_code);
        // $pdf = PDF::loadView('pdf.qr_code', ['qr_code' => $qr_code]);

        // Convert PDF to base64
        // return 'data:application/pdf;base64,'.base64_encode($pdf->output());

        // $base64Pdf = $pdf->output();
        // return $pdf->download('qr_code.pdf');
        // $headers = [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'attachment; filename="qr_code.pdf"',
        // ];

        // $header = header('Content-Type: text/html; charset=utf-8');

        return response()->json([
            'success' => true,
            'pdf' => $image2,
        ], 200);
        // ], 200, ['Content-Type' => 'image/png']);

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

        $qr_code_image =    QrCode::size(212)
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
