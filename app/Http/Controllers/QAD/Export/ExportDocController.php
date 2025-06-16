<?php

namespace App\Http\Controllers\QAD\Export;

use App\Http\Controllers\Controller;
use App\Models\QAD\Export\ExportDoc;
use App\Models\QAD\Export\ExportDocDetail;
use Illuminate\Http\Request;

class ExportDocController extends Controller
{

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


    public function getSoExport($soNbr)
    {
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

        session(['toastMessage' => 'Data berhasil disimpan. Jumlah detail baru: ' . $jumlahItemBaru, 'toastType' => 'success']);
        return redirect()->back();
    }

    // END OF FUNCTION GET DATA EXPORT
}
