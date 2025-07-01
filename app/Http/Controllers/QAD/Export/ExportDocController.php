<?php

namespace App\Http\Controllers\QAD\Export;

use App\Http\Controllers\Controller;
use App\Models\QAD\Export\ExportDoc;
use App\Models\QAD\Export\ExportDocDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;

class ExportDocController extends Controller
{
    public function index()
    {
        return view('page.export.index');
    }

    public function list()
    {
        return view('page.export.list');
    }

    public function store(Request $request)
    {   
        // Mulai transaction untuk memastikan integritas data
        DB::beginTransaction();

        try{
            $validatedData = $request->validate([
                'no' => 'required|string|max:255',
                'etd' => 'required|date',
                'eta' => 'required|date',
                'commodity' => 'required|string|max:255',
                'marking' => 'required|string|max:255',
                'certificate_no' => 'required|string|max:255',
                'total_gross_weight' => 'required|string|max:255',
                'measurement' => 'required|string|max:255',
                'container_no' => 'required|string|max:255',
                'stuffing' => 'required|date',
                'gross' => 'required|array',
                'gross.*' => 'required|string|max:255',
                'batch_no' => 'required|string|max:255',
            ]);

            // Simpan data ke database
            $no = 'ESM' . $request->input('no');
            $exportDoc = ExportDoc::where('so_nbr', $no)->first();
            if (!$exportDoc) { // Jika tidak ada exportDoc dengan nomor tersebut, throw exception
                throw new \Exception('Export not found for number: ' . $request->input('no'));
            }

            // Ambil input dari request
            $gross = $request->input('total_gross_weight');
            $gross = str_replace('.', '', $gross);
            $gross = str_replace(',', '.', $gross);
            $gross = floatval($gross);

            $measurement = $request->input('measurement');
            $measurement = str_replace('.', '', $measurement);
            $measurement = str_replace(',', '.', $measurement);
            $measurement = floatval($measurement);

            $exportDoc->etd = $validatedData['etd'];
            $exportDoc->eta = $validatedData['eta'];
            $exportDoc->commodity = $validatedData['commodity'];
            $exportDoc->marking = $validatedData['marking'];
            $exportDoc->certificate_no = $validatedData['certificate_no'];
            $exportDoc->total_gross = $gross;
            $exportDoc->measurement = $measurement;
            $exportDoc->container_no = $validatedData['container_no'];
            $exportDoc->stuffing = $validatedData['stuffing'];
            $exportDoc->batch_no = $validatedData['batch_no'];

            foreach ($validatedData['gross'] as $index => $grossValue) {
                if (isset($exportDoc->details[$index])) {
                    $detail = $exportDoc->details[$index];
                    $grossWeight = str_replace('.', '', $grossValue);
                    $grossWeight = str_replace(',', '.', $grossWeight);
                    $detail->gross_weight = floatval($grossWeight);
                    $detail->save();
                }
            }

            $exportDoc->save();

            // Commit transaction jika semua berhasil
            DB::commit();
            // Jika berhasil, kembalikan response sukses
            return response()->json(['message' => 'Data berhasil disimpan.',  'redirect' => route('export.list')], 200);
        } catch (\Exception $e) {
            // Jika validasi gagal, rollback transaction dan kembalikan error
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get data from database
    public function getData()
    {
        $exportDoc = ExportDoc::all();
        return response()->json($exportDoc);
    }

    // GET DATA EXPORT
    private function httpHeader($req)
    {
        return array(
            'Content-type: text/xml;charset="utf-8"',
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'SOAPAction: ""',
            'Content-length: ' . strlen(preg_replace("/\s+/", " ", $req))
        );
    }


    public function getSoExport(Request $request)
    {   
        try {
            $soNbr = $request->input('soNbr');
            if(!$soNbr)
            {
                return response()->json("Tidak ada nomor");
            }
            $soNbr = "ESM".$soNbr;

            $qxUrl = 'http://smii.qad:24079/wsa/smiiwsa';
            $timeout = 10;
            $domain = 'SMII';

            // Prepare SOAP request
            $qdocRequest =
                '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
                <Body>
                    <getSoExport xmlns="urn:services-qad-com:smiiwsa:0001:smiiwsa">
                        <ip_domain>' . $domain . '</ip_domain>
                        <ip_so_nbr>' . $soNbr . '</ip_so_nbr>
                    </getSoExport>
                </Body>
            </Envelope>';

            $curlOptions = array(
                CURLOPT_URL => $qxUrl,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT => $timeout + 5,
                CURLOPT_HTTPHEADER => $this->httpHeader($qdocRequest),
                CURLOPT_POSTFIELDS => preg_replace("/\s+/", " ", $qdocRequest),
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            );

            $curl = curl_init();
            if ($curl) {
                curl_setopt_array($curl, $curlOptions);
                $qdocResponse = curl_exec($curl);
                curl_close($curl);
            } else {
                return redirect()->back()->with('error', 'Gagal menghubungi server.');
            }

            if (!$qdocResponse) {
                return redirect()->back()->with('error', 'Tidak ada respons dari server.');
            }

            $xmlResp = simplexml_load_string($qdocResponse);
            $xmlResp->registerXPathNamespace('ns', 'urn:services-qad-com:smiiwsa:0001:smiiwsa');

            $rows = $xmlResp->xpath('//ns:getSoExportResponse/ns:ttSoDetail/ns:ttSoDetailRow');
            $jumlahItemBaru = 0;

            if (count($rows) > 0) {
                // Ambil data header dari baris pertama
                $first = $rows[0];
                $so_nbr = (string) $first->so_nbr;

                // Cek header, update/insert
                $exportDoc = ExportDoc::where('so_nbr', $so_nbr)->first();
                if (!$exportDoc) {
                    $exportDoc = new ExportDoc();
                    $exportDoc->so_nbr = $so_nbr;
                }
                $exportDoc->so_po = (string) $first->so_po;
                $exportDoc->ad_sort = (string) $first->ad_sort;
                $exportDoc->ad_name = (string) $first->ad_name;
                $exportDoc->ad_line1 = (string) $first->ad_line1;
                $exportDoc->ad_line2 = (string) $first->ad_line2;
                $exportDoc->ad_line3 = (string) $first->ad_line3;
                $exportDoc->ad_city = (string) $first->ad_city;
                $exportDoc->ad_country = (string) $first->ad_country;
                $exportDoc->ad_phone = (string) $first->ad_phone;
                $exportDoc->ad_phone2 = (string) $first->ad_phone2;
                $exportDoc->ad_fax = (string) $first->ad_fax;
                $exportDoc->ad_fax2 = (string) $first->ad_fax2;
                $exportDoc->ship_to_name = (string) $first->ship_to_name;
                $exportDoc->total_net = $exportDoc->details->sum('net_weight');
                $exportDoc->save();

                // Hapus detail lama untuk so_nbr ini (opsional, jika ingin replace)
                ExportDocDetail::where('sod_nbr', $so_nbr)->delete();

                // Simpan detail
                foreach ($rows as $item) {
                    $detail = new ExportDocDetail();
                    $detail->sod_nbr = (string) $item->so_nbr; // relasi ke so_nbr
                    $detail->sod_part = (string) $item->sod_part;
                    $detail->pt_desc = (string) $item->pt_desc;
                    $detail->pt_net_wt = (string) $item->pt_net_wt;
                    $detail->sod_qty_ord = (string) $item->sod_qty_ord;
                    $detail->pt_um = (string) $item->pt_um;
                    $detail->so_ship = (string) $item->so_ship;
                    $detail->net_weight = (string) $item->net_weight;
                    $detail->save();
                    $jumlahItemBaru++;
                }
            }

            $exportDoc = ExportDoc::with('details')->where('so_nbr', $so_nbr)->first();
            return response()->json($exportDoc);
            session(['toastMessage' => 'Data berhasil disimpan. Jumlah detail baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
            return redirect()->back();
        } catch (\Throwable $th) {
            return response()->json('Gagal mengambil data');
        }
        
    }


    public function print($no)
    {
        // set_time_limit(120); // atur maksimal jadi 5 menit
        try {
            $exportDoc = ExportDoc::with('details')
                        ->where('so_nbr', $no)
                        ->first();
            if(!$exportDoc) {
                throw new \Exception('Export document not found for number: ' . $no);
            }
            foreach ($exportDoc->details as $detail) {
                if (is_null($detail->gross_weight) || $detail->gross_weight === '') {
                    throw new \Exception('Terdapat gross_weight yang kosong.');
                }
            }

            // Gabungkan field consignee
            $consignee = $exportDoc->ad_sort;
            $consignee .= $exportDoc->ad_line1 ? '<br>' . $exportDoc->ad_line1 : '';
            $consignee .= $exportDoc->ad_line2 ? '<br>' . $exportDoc->ad_line2 : '';
            $consignee .= $exportDoc->ad_line3 ? '<br>' . $exportDoc->ad_line3 : '';
            $consignee .= $exportDoc->ad_phone ? '. TEL: ' . $exportDoc->ad_phone : '';
            $consignee .= $exportDoc->ad_phone2 ? '. TEL2: ' . $exportDoc->ad_phone2 : '';
            $consignee .= $exportDoc->ad_fax ? '. FAX: ' . $exportDoc->ad_fax : '';
            $consignee .= $exportDoc->ad_fax2 ? '. FAX2: ' . $exportDoc->ad_fax2 : '';
            $exportDoc->consignee = $consignee;

            // Gabungkan field consignee
            $notify = $exportDoc->ad_sort;
            $notify .= $exportDoc->ad_line1 ? '<br>' . $exportDoc->ad_line1 : '';
            $notify .= $exportDoc->ad_line2 ? '<br>' . $exportDoc->ad_line2 : '';
            $notify .= $exportDoc->ad_line3 ? '<br>' . $exportDoc->ad_line3 : '';
            $notify .= $exportDoc->ad_phone ? '. TEL: ' . $exportDoc->ad_phone : '';
            $notify .= $exportDoc->ad_phone2 ? '. TEL2: ' . $exportDoc->ad_phone2 : '';
            $notify .= $exportDoc->ad_fax ? '. FAX: ' . $exportDoc->ad_fax : '';
            $notify .= $exportDoc->ad_fax2 ? '. FAX2: ' . $exportDoc->ad_fax2 : '';
            $exportDoc->notify = $notify;

            // Hitung total net_weight
            $total_net_weight = $exportDoc->details->sum('net_weight');
            $exportDoc->total_net_weight = $total_net_weight;

            // Hitung total gross_weight
            $total_gross_weight = $exportDoc->details->sum('gross_weight');
            $exportDoc->total_gross_weight = $total_gross_weight;
            

            // Export-{{so_nbr}}-name.pdf
            return view('page.export.printPage', compact('exportDoc'));
            // Export PDF
            // $pdf = Pdf::loadView('page.export.printPage3', compact('exportDoc'));
            // return $pdf->download('export-docs.pdf');

            // SnappyPDF
            // $html = view('page.export.printPage3', ['exportDoc' => $exportDoc])->render();

            // $pdf = SnappyPdf::loadHTML($html);

            // return $pdf->download('export-docs.pdf');



            // DOMPDF
            // $fileName = 'Export-' . $exportDoc->so_nbr . '-'  . $exportDoc->ad_name . '.pdf';

            // $cacheKey = 'export_pdf_' . $exportDoc->so_nbr;
            // if (cache()->has($cacheKey)) {
            //     $pdfContent = cache()->get($cacheKey);
            // } else {
            //     $pdf = Pdf::loadView('page.export.printPage3', compact('exportDoc'));
            //     $pdfContent = $pdf->output();
            //     cache()->put($cacheKey, $pdfContent, now()->addMinutes(10));
            //     // cache()->put($cacheKey, $pdfContent, now()->addHours(24));
            // }

            // return response()->streamDownload(function () use ($pdfContent) {
            //     echo $pdfContent;
            // }, $fileName, ['Content-Type' => 'application/pdf']);

            
        } catch (\Exception $e) {
            // Jika validasi gagal, kembalikan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // END OF FUNCTION GET DATA EXPORT
}
