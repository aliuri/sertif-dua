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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertasImport;

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
        // Ambil data peserta
        $data = DB::table("pesertas")->where('id', $request->peserta_id)->first();
        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $peserta = $data->name;
        $partisipan = $data->partisipan;
        $get_sertif = DB::table("sertifs")->where('id', $data->sertif_id)->first();
        $cek_page_2 = $get_sertif->id + 1;
        $get_sertif_page_2 = DB::table("sertifs")->where('id', $cek_page_2)->first();

        // Konfigurasi mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [297, 210],
            'margin_top' => 0,
            'margin_left' => 0,
            'margin_right' => 0
        ]);

        // Siapkan gaya teks
        $rataHuruf = !empty($get_sertif->rata_huruf) ? $get_sertif->rata_huruf : 'center';
        $sizeNama = !empty($get_sertif->size_nama) ? $get_sertif->size_nama . 'px' : '32px';
        $sizePeserta = !empty($get_sertif->size_peserta) ? $get_sertif->size_peserta . 'px' : '22px';

        // Buat konten HTML
        $htmlContent = sprintf(
            '
        <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
            <img src="%s" style="width: 100%%; margin: 0;" />
        </div>
        <div style="color:#323330; font-size:%s; text-align:%s; position: absolute; top:%smm; left:%smm; right:%smm;">
            %s
        </div>
        <div style="color:#323330; font-size:%s; text-align:%s; position: absolute; top:%smm; left:%smm; right:%smm;">
            %s
        </div>',
            public_path('/uploads/' . $get_sertif->file),
            $sizeNama,
            $rataHuruf,
            $get_sertif->margin_top,
            $get_sertif->margin_left,
            $get_sertif->margin_right,
            $peserta,
            $sizePeserta,
            $rataHuruf,
            $get_sertif->peserta_top,
            $get_sertif->peserta_left,
            $get_sertif->peserta_right,
            $partisipan
        );

        $mpdf->WriteHTML($htmlContent);

        // Tambahkan halaman kedua jika ada
        if (!empty($get_sertif_page_2) && $get_sertif_page_2->page_two == 1) {
            $mpdf->AddPage();
            $mpdf->WriteHTML(sprintf(
                '
            <div style="position: absolute; left:0; right: 0; top: 0; bottom: 0;">
                <img src="%s" style="width: 100%%; margin: 0;" />
            </div>',
                public_path('/uploads/' . $get_sertif_page_2->file)
            ));
        }

        // Buat nama file yang aman untuk sistem file
        $filename = str_replace([' ', '/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $peserta);
        $filename = preg_replace('/[^A-Za-z0-9\_\-]/', '', $filename); // Hapus karakter khusus
        $filename = strtolower($filename) . '_sertifikat.pdf';
        
        return $mpdf->Output($peserta . '.pdf', 'I');
    }

    public function index(Request $request)
    {   
        if ($request->ajax()) {
            $data = DB::table("sertifs")->orderBy('id', 'desc')->get();
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


        $request->validate([
            'excel_file' => 'mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            if (!empty($file)) {
                $name = $file->getClientOriginalName();
                $nama_file = time() . "_" . $file->getClientOriginalName();
                $request->file->storeAs('uploads', $nama_file, ['disk' => 'root']);
            } else {
                $nama_file = $request->file_edit;
            }
            // dd($request);
            $sertifikat = sertifs::updateOrCreate(
                ['id' => $request->sertif_id],
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
                ]
            );

            if ($request->hasFile('excel_file')) {
                // Create new import instance with sertif_id
                $import = new PesertasImport($sertifikat->id);

                // Import data using Excel facade
                Excel::import($import, $request->file('excel_file'));

                return response()->json(['success' => 'Sertif ' . $nama_file . ' berhasil di upload.']);
            }

            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        return response()->json(['success' => 'Sertif ' . $nama_file . ' berhasil di upload.']);
        // return Redirect::back();
    }

    public function edit($id)
    {   
        $sertif = sertifs::find($id);
        return response()->json($sertif);
    }

    public function destroy($id)
    {
        // Get the sertif record first
        $sertif = sertifs::find($id);

        if ($sertif != null) {
            // Delete the file from uploads directory
            $file_path = public_path() . '/' . 'uploads/' . $sertif->file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete all related peserta records
            Pesertas::where('sertif_id', $id)->delete();

            // Delete the sertif record
            $sertif->delete();

            return redirect()->back()->with('success', 'Sertifikat berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Sertifikat gagal dihapus');
    }
}
