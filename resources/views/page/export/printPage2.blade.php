<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Instruction Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h4 {
            margin: 5px 0px;
        }
        p {
            margin-top: 2px;
            margin-bottom: 2px;
        }
        section.print-page {
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-direction: column;
        }
        main.main-background {
            flex: 1 0 auto;
            background-image: url('/assets/export/body.png');
            background-repeat: no-repeat;
            background-size: 100% auto;
            min-height: 200mm;
            padding: 0 30px;
        }
        footer {
            display: flex;
            flex-shrink: 0;
        }
        footer img {
            width: 100%;
            height: auto;
        }
        #shippingTable {
            border-spacing: 0 5px;
            border-collapse: separate;
        }
        #shippingTable td {
            vertical-align: top;
            text-align: left;
            padding: 2px 5px;
        }
        .text-bold {
            font-weight: bold;
        }
        .flex {
            display: flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .items-center {
            align-items: center;
        }
        .justify-center {
            justify-content: center;
        }
        .gap-x-4 {
            column-gap: 1rem;
        }
        .text-2xl {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .text-xl {
            font-size: 1.25rem;
        }
        .text-sm { font-size: 0.775rem; }
        .w-50 { width: 12.5rem; }
        .h-32 { height: 8rem; }
        .mr-auto { margin-right: auto; }
        .mb-5 { margin-bottom: 1.25rem; }
        .pt-14 { padding-top: 3.5rem; }
        .w-[40px] { width: 40px; }
        .w-[120px] { width: 120px; }
        .border-0 { border: 0; }
        .border-b { border-bottom: 1px solid #000; }
        .text-center { text-align: center; }
        .bg-transparent { background: transparent; }
        .p-0 { padding: 0; }
        .leading-tight { line-height: 1.25; }
        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .no-spinner {
            -moz-appearance: textfield;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #333;
        }
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none; }
            .main-background {
                background: url('/assets/export/body.png') no-repeat;
                background-size: cover;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-page {
                page-break-before: always;
                /* Atur padding/margin sesuai kebutuhan */
            }
            .print-page:first-child {
                page-break-before: auto;
            }
        }
    </style>
</head>
<body>
@php
    $chunks = $exportDoc->details->chunk(3);
@endphp

@foreach($chunks as $i => $chunk)
    <section class="print-page">
        <main class="content main-background">
            <header class="w-50 h-32 mr-auto pt-14">
                <img src="{{ asset('assets/export/logo.png') }}" style="max-width:100%; height:auto; display:block;">
            </header>
            <div class="flex flex-col items-center">
                <h4 class="">PACKING LIST</h4>
                <div class="text-sm">
                    <div>
                        <table id="packingTable" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td rowspan="2" colspan="4">
                                        <p class="text-bold">NOTIFY PARTY:</p>
                                        <p>{!! $exportDoc['notify'] ?? '' !!}</p>
                                    </td>
                                    <td colspan="2">
                                        <p>PACKING LIST NO:</p>
                                        <p>{{ $exportDoc['so_nbr'] ?? '' }}</p>
                                    </td>
                                    <td colspan="2">
                                        <p>INVOICE NO:</p>
                                        <p>{{ $exportDoc['so_nbr'] ?? '' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p>PORT OF LOADING:</p>
                                        <p class="text-bold">JAKARTA, INDONESIA</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p class="text-bold">CONSIGNED TO:</p>
                                        <p>{!! $exportDoc['consignee'] ?? '' !!}</p>
                                    </td>
                                    <td colspan="4">
                                        <p>PORT OF DESTINATION:</p>
                                        <p class="text-bold">{{ $exportDoc['ad_city'] ?? '' }}</p>
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td>ITEM</td>
                                    <td>DESCRIPTION</td>
                                    <td>QTY</td>
                                    <td>NET WEIGHT</td>
                                    <td colspan="2">
                                        <p>NET</p>
                                        <p>(K G S)</p>
                                    </td>
                                    <td colspan="2">
                                        <p>GROSS</p>
                                        <p>(K G S)</p>
                                    </td>
                                </tr>
                                @foreach($chunk as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['pt_desc'] }} ({{ $item['sod_part'] }})</td>
                                    <td>{{ $item['sod_qty_ord'] }} {{ $item['pt_um'] }}</td>
                                    <td>{{ number_format($item['net_weight'] / 1000, 2, ',', '.') }} M/TONS</td>
                                    <td colspan="2">{{ number_format($item['net_weight'], 2, ',', '.') }}</td>
                                    <td colspan="2">{{ number_format($item['gross_weight'] ?? 0, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2">{{ number_format($exportDoc->total_net, 2, ',', '.') }}</td>
                                    <td colspan="2">{{ number_format($exportDoc->total_gross, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p>BATCH NO.:</p>
                                        <p class="text-bold">{{ $exportDoc['batch_no'] ?? '' }}</p>
                                    </td>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p>CONTAINER NO. / SEAL NO.:</p>
                                        <p>{{ $exportDoc['container_no'] ?? '' }}</p>
                                    </td>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td colspan="4">{{ $exportDoc['marking'] ?? '' }}</td>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <td colspan="4">CERTIFICATE NO : <span>{{ $exportDoc['certificate_no'] ?? '' }}</span></td>
                                    <td colspan="4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-center">
                        <p class="text-bold">PT. SINAR MEADOW INTERNATIONAL INDONESIA</p>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <img src="{{ asset('/assets/export/footer.png')}}" alt="">
        </footer>
    </section>
@endforeach
</body>
</html>