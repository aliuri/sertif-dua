<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use DataTables;
use App\sertifs;
use App\Pesertas;
use Redirect;
use Mpdf\Mpdf;

class TestControllers extends Controller
{
    public function pdfview(Request $request)
    {
        $items = DB::table("pesertas")->get();
        view()->share('items',$items);


        if($request->has('download')){
            $pdf = PDF::loadView('pdfview')->setPaper('A4','landscape');
            return $pdf->download('pdfview.pdf');
        }


        return view('pdfview');
    }

    public function cek(Request $request)
    {
        $data = Pesertas::select('pesertas.id as peserta_id','pesertas.name','pesertas.email','sertifs.id as sertif_id','sertifs.file')->join('sertifs','sertifs.id','=','pesertas.sertif_id')->where('pesertas.email',$request->email)->get();
        // dd($data);
        if(!empty($data)){

            return response()->json($data);
            // return response()->json([
            //     'success'=>''.$data->name.'',
            //     ]);

        }else{
            return response()->json([
                'success'=>'Email tidak terdaftar.',
                ]);
        }
        // dd($data->name);
    }

    public function downloadSertif(Request $request)
    {
        $data = DB::table("pesertas")->where('id',$request->peserta_id)->first();
        $peserta = $data->name;
        $partisipan = $data->partisipan;
        $get_sertif = $data = DB::table("sertifs")->where('id',$data->sertif_id)->first();
        $cek_page_2 = $sertif = $get_sertif->id+1;
        $get_sertif_page_2 = $data = DB::table("sertifs")->where('id',$cek_page_2)->first();
        
        // view('page',$get_sertif)
        //Nama
        $sertif = $get_sertif->file;
        $atas = $get_sertif->margin_top.'mm';
        $kanan = $get_sertif->margin_right.'mm';
        $kiri = $get_sertif->margin_left.'mm';
        $rataHuruf = !empty($get_sertif->rata_huruf) ? $get_sertif->rata_huruf : 'center';
        $sizeNama = !empty($get_sertif->size_nama) ? $get_sertif->size_nama.'px' : '32px';
        // dd($get_sertif->rata_huruf);
        //Partisipan
        $sertif_2 = $get_sertif_page_2->file;
        $atas_2 = $get_sertif->peserta_top.'mm';
        $kanan_2 = $get_sertif->peserta_right.'mm';
        $kiri_2 = $get_sertif->peserta_left.'mm';
        $sizePeserta = !empty($get_sertif->size_peserta) ? $get_sertif->size_peserta.'px' : '22px';

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => [297, 210]]);
        $data = "<div style='color:#323330;font-size:$sizeNama;text-align:$rataHuruf;padding-top: $atas;margin-left: $kiri;margin-right: $kanan;'> $peserta </div>
                <div style='color:#323330;font-size:$sizePeserta;text-align:$rataHuruf;padding-top: $atas_2;margin-left: $kiri_2;margin-right: $kanan_2;'> $partisipan </div>";
        $mpdf->WriteHtml('<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
                        <img src="'.public_path().'/'.'uploads/'.$sertif.'" 
                            style="margin: 0;" />
                    </div>');
        $mpdf->WriteHtml($data);
        if(!empty($get_sertif_page_2) && $get_sertif_page_2->page_two == 1){
            $mpdf->AddPage();
            $mpdf->WriteHtml('<div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
                        <img src="'.public_path().'/'.'uploads/'.$sertif_2.'" 
                            style="margin: 0;" />
                    </div>');
        }
        $mpdf->Output($peserta.'.pdf','I');
    }

    public function index(Request $request)
    {   
        if ($request->ajax()) {
                $data = DB::table("sertifs")->get();
                // dd($data);
                return Datatables::of($data)
                        ->addIndexColumn()
                        ->addColumn('page_2', function($sertif){
                            if ($sertif->page_two == 1) {
                                return "Page 2";
                            }else{
                                return "ONE";
                            }
                        })
                        ->addColumn('action', function($sertif){
                            return view('sertif-action', compact('sertif'));
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
        return view('sertif-index');
    }

    public function store(Request $request)
    {
        // dd($request->file);
        $file = $request->file('file');  
        if(!empty($file)){
            $name = $file->getClientOriginalName();
            $nama_file = time()."_".$file->getClientOriginalName();
            $request->file->storeAs('uploads', $nama_file, ['disk' => 'root']);
        } else {
            $nama_file = $request->file_edit;
        }
        // dd($request);
        sertifs::updateOrCreate(['id' => $request->sertif_id],
                [
                    'file' => $nama_file,
                    'page_two' => $request->page_2,
                    'rata_huruf' => $request->rata_huruf,
                    'margin_top' => $request->margin_top,
                    'margin_left' => $request->margin_left,
                    'margin_right' => $request->margin_right,
                    'peserta_top' => $request->peserta_top,
                    'peserta_left' => $request->peserta_left,
                    'peserta_right' => $request->peserta_right,
                    'size_nama' => $request->size_nama,
                    'size_peserta' => $request->size_peserta,
                ]);
        return response()->json(['success'=>'Sertif '.$nama_file.' berhasil di upload.']);
        // return Redirect::back();
    }

    public function edit($id)
    {   
        $sertif = sertifs::find($id);
        return response()->json($sertif);
    }

    public function destroy($id)
    {
        $Sertif = sertifs::find($id);
        if ($Sertif != null) {
        $file_path = public_path().'/'.'uploads/'.$Sertif->file;
        unlink($file_path);
        $Sertif->delete();

        // flash("Pengguna berhasil di hapus.")->success();
        return Redirect::back();
    }

        // flash("Pengguna gagal di hapus.")->success();
        return Redirect::back();

        
    }

}
