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
        .main-background {
            background-image: url('/assets/export/body.png');
            background-repeat: no-repeat;
            background-size: 100% auto;
            min-height: 100vh;
            padding: 0 30px;
        }
        footer img {
            width: 100%;
            height: auto;
        }
        #shippingTable {
            border-spacing: 0 10px;
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
        .justify-end {
            justify-content: flex-end;
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
        .w-72 { width: 18rem; }
        .h-32 { height: 8rem; }
        .mr-auto { margin-right: auto; }
        .mb-5 { margin-bottom: 1.25rem; }
        .pt-15 { padding-top: 3.75rem; }
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
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <section>
        <main class="content main-background">
            <header class="w-72 h-32 mr-auto mb-5 pt-15">
                <img src="{{ asset('assets/export/logo.png') }}" style="max-width:100%; height:auto; display:block;">
            </header>
            <div class="flex flex-col items-center">
                <h2 class="text-2xl">SHIPPING INSTRUCTION</h2>
                <div class="text-xl">
                    ESM/<span>{{ str_replace('ESM', '', $exportDoc['so_nbr'] ?? '') }}</span>/04/2025
                </div>
                <div>
                    <div>
                        <p>We request you to book shipment on our behalf with the following specifications.</p>
                    </div>
                    <div>
                        <table id="shippingTable">
                            <tbody>
                                <tr>
                                    <td>Shipped by</td>
                                    <td>:</td>
                                    <td>
                                        ETD: {{ $exportDoc['etd'] ?? '' }} - ETA: {{ $exportDoc['eta'] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shipper</td>
                                    <td>:</td>
                                    <td>
                                        PT. SINAR MEADOW INTERNATIONAL INDONESIA <br>
                                        JL. PULO AYANG 1/6, K.I PULO GADUNG, JATINEGARA, CAKUNG <br>
                                        KOTA ADM. JAKARTA TIMUR, DKI JAKARTA 13260 INDONESIA
                                    </td>
                                </tr>
                                <tr>
                                    <td>Consignee</td>
                                    <td>:</td>
                                    <td>
                                        {!! $exportDoc['consignee'] ?? '' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Notify Party</td>
                                    <td>:</td>
                                    <td>
                                        {!! $exportDoc['notify'] ?? '' !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Port of Loading</td>
                                    <td>:</td>
                                    <td>JAKARTA, INDONESIA</td>
                                </tr>
                                <tr>
                                    <td>Port of Discharge</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['ad_city'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Final of Destination</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['ad_city'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Commodity</td>
                                    <td>:</td>
                                    <td>
                                        <p style="margin: 0;">{{ $exportDoc['commodity'] ?? '' }}</p>
                                        <ul>
                                            @foreach($exportDoc->details as $item)
                                                <li>
                                                    {{ $item['pt_desc'] }} ({{ $item['sod_part'] }}) <br>
                                                    {{ $item['sod_qty_ord'] }} X {{ $item['pt_net_wt'] }}KG/{{ $item['pt_um'] }} = {{ number_format($item['net_weight']/1000, 3, ',', '.') }} M/TONS
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p class="text-bold">PO: {{ $exportDoc['so_po'] ?? '' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div class="flex gap-x-4">
                                            <p class="text-bold">MARKING: {{ $exportDoc['marking'] ?? '' }}</p>
                                            <p class="text-bold">CERTIFICATE NO: {{ $exportDoc['certificate_no'] ?? '' }}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Net Weight</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['total_net_weight'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Gross Weight</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['total_gross'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Measurement</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['measurement'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Container No</td>
                                    <td>:</td>
                                    <td>{{ $exportDoc['container_no'] ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <p class="text-bold">STUFFING: {{ $exportDoc['stuffing'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </main>
        <footer class="pt-15">
            <img src="{{ asset('/assets/export/footer.png')}}" alt="">
        </footer>
    </section>
</body>
</html>