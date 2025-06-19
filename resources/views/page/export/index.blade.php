<x-app-layout>
    @section('title')
        Create Export
    @endsection
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
        <style>

            .main-background{
                background-image: url('/assets/export/body.png');
                background-repeat: no-repeat;
                background-size: 100% auto;
                min-height: 100vh;
            }

            /* Hilangkan panah spinner di input number untuk semua browser utama */
            input.no-spinner::-webkit-outer-spin-button,
            input.no-spinner::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input.no-spinner {
                -moz-appearance: textfield; /* Firefox */
            }
            #shippingTable{
                border-spacing: 0 10px; /* horizontal 0, vertical 10px */
                border-collapse: separate;
            }
            #shippingTable td{
                vertical-align: top;
                text-align: left;
            }
        </style>
    @endpush


    <div class="content-header">
        <div class="flex items-center justify-between">
            <h4 class="page-title text-2xl font-medium"></h4>
            <div class="inline-flex items-center">
                <nav>
                    <ol class="breadcrumb flex items-center">
                        <li class="breadcrumb-item pr-1"><a href="{{ route('dashboard') }}"><i
                                    class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item pr-1" aria-current="page"><a href="{{ route('export.list') }}"> Export </a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    
    <section class="box">
        <div class="box-header flex justify-between mb-4">
            <h2 class="page-title text-2xl font-medium">Create Export</h2>
            <a href="{{ route('export.list') }}" class="btn btn-success">Back to List</a>
        </div>
        <div class="box-body">
            <form action="{{ route('export.store') }}" method="POST" id="exportForm" class="w-full h-full mt-5">
                @csrf  
                <div class="flex justify-center mb-8">
                    <div class="text-xl">
                        <span>ESM/</span>
                        <input id="no" name="no" required
                            type="number" 
                            value="" 
                            pattern="[0-9]*"
                            class="w-[40px] border-0 border-b border-red-600 text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner text-xl">
                        <span>/04/2025</span>
                    </div>
                </div>
                <div class="flex justify-around">  
                    <main class="">  
                        <div class="flex flex-col items-center">
                            <h2 class="text-2xl">SHIPPING INSTRUCTION</h2>
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
                                                <td id="shipped"></td>
                                            </tr>
                                            <tr>
                                                <td>Shipper</td>
                                                <td>:</td>
                                                <td id="shipper"></td>
                                            </tr>
                                            <tr>
                                                <td>Consignee</td>
                                                <td>:</td>
                                                <td id="consignee"></td>
                                            </tr>
                                            <tr>
                                                <td>Notify Party</td>
                                                <td>:</td>
                                                <td id="notify"></td>
                                            </tr>
                                            <tr>
                                                <td>Port of Loading</td>
                                                <td>:</td>
                                                <td class="loading"></td>
                                            </tr>
                                            <tr>
                                                <td>Port of Discharge</td>
                                                <td>:</td>
                                                <td id="discharge"></td>
                                            </tr>
                                            <tr>
                                                <td>Final of Destination</td>
                                                <td>:</td>
                                                <td class="destination"></td>
                                            </tr>
                                            <tr>
                                                <td>Commodity</td>
                                                <td>:</td>
                                                <td id="commodity"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <p class="text-bold">PO: <span id="po"></span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="flex gap-x-4">
                                                        <p class="text-bold">MARKING: <span id="marking"></span></p>
                                                        <p class="text-bold">CERTIFICATE NO: <span id="certificate_no"></span></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Net Weight</td>
                                                <td>:</td>
                                                <td id="total_net_weight"></td>
                                            </tr>
                                            <tr>
                                                <td>Gross Weight</td>
                                                <td>:</td>
                                                <td id="total_gross_weight"></td>
                                            </tr>
                                            <tr>
                                                <td>Measurement</td>
                                                <td>:</td>
                                                <td id="measurement"></td>
                                            </tr>
                                            <tr>
                                                <td>Container No</td>
                                                <td>:</td>
                                                <td id="container_no"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex justify-end">
                                    <p class="text-bold">STUFFING: <span id="stuffing"></span></p>
                                </div>
                            </div>
                        </div>
                    </main>
                    
                    <main class="mt-15">
                        <div class="flex flex-col items-center">
                            <h2 class="text-2xl">PACKING LIST</h2>
                            <table id="packingTable" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td rowspan="2" colspan="4">
                                            <p>NOTIFY PARTY:</p>
                                            <p id="notifyPacking"></p>
                                        </td>
                                        <td colspan="2">
                                            <p>PACKING LIST NO:</p>
                                            <p id="packingNo"></p>
                                        </td>
                                        <td colspan="2">
                                            <p>INVOICE NO:</p>
                                            <p id="invoiceNo"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>PORT OF LOADING:</p>
                                            <p class="loading"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>CONSIGNED TO:</p>
                                            <p id="consigned"></p>
                                        </td>
                                        <td colspan="4">
                                            <p>PORT OF DESTINATION:</p>
                                            <p class="destination"></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ITEM</td>
                                        <td>DESCRIPTION</td>
                                        <td>QTY</td>
                                        <td>NET WEIGHT</td>
                                        <td colspan="2">NET</td>
                                        <td colspan="2">GROSS</td>
                                    </tr>
                                    <tr id="detailTR">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </main>
                </div>
                <div class="flex justify-center mt-8">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
        
    </section>


    <script type="text/javascript" src="{{ asset('assets') }}/ajax/libs/jQuery-slimScroll/1.3.8/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script> 
    <script src = "https://cdn.datatables.net/2.0.8/js/dataTables.jqueryui.js" ></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.jqueryui.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/select.jqueryui.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @push('scripts')
        <script>
            document.getElementById('exportForm').addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.type !== 'textarea') {
                    e.preventDefault();
                }
            });
            function showSuccess(message) {
                Swal.fire({
                    title: 'Success!',
                    text: message,
                    icon: 'success',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
            function showFailed(message) {
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            function resetData()
            {
                $.ajax({
                    url: "{{ route('export.get') }}",
                    type: "POST",
                    data: {
                        soNbr: $('#no').val()
                    },
                    success: function(response) {
                        showSuccess("Berhasil mengambil data");
                        console.log('Success:', response);
                        console.log('Success:', response.details);
                        var shipped = `<br>
                            ETD: <input type="date" name="etd" class="w-[120px] border-0 border-b border-red-600 text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.etd ?? ''}" required> - 
                            ETA: <input type="date" name="eta" class="w-[120px] border-0 border-b border-red-600 text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.eta ?? ''}" required>
                        `;
                        var shipper = `PT. SINAR MEADOW INTERNATIONAL INDONESIA <br>
                        JL. PULO AYANG 1/6, K.I PULO GADUNG, JATINEGARA, CAKUNG <br>
                        KOTA ADM. JAKARTA TIMUR, DKI JAKARTA 13260 INDONESIA`;
                        var consignee = `${response.ad_sort}` + 
                        (response.ad_line1 ? `<br> ${response.ad_line1}` : '') +
                        (response.ad_line2 ? `<br> ${response.ad_line2}` : '') +
                        (response.ad_line3 ? `<br> ${response.ad_line3}` : '') + 
                        (response.ad_phone ? `. TEL: ${response.ad_phone}` : '') + 
                        (response.ad_phone2 ? `. TEL2: ${response.ad_phone2}` : '') + 
                        (response.ad_fax ? `. FAX: ${response.ad_fax}` : '') + 
                        (response.ad_fax2 ? `. FAX2: ${response.ad_fax2}` : '');
                        var notify = `${response.ad_sort}` + 
                        (response.ad_line1 ? `<br> ${response.ad_line1}` : '') +
                        (response.ad_line2 ? `<br> ${response.ad_line2}` : '') +
                        (response.ad_line3 ? `<br> ${response.ad_line3}` : '') + 
                        (response.ad_phone ? `. TEL: ${response.ad_phone}` : '') + 
                        (response.ad_phone2 ? `. TEL2: ${response.ad_phone2}` : '') + 
                        (response.ad_fax ? `. FAX: ${response.ad_fax}` : '') + 
                        (response.ad_fax2 ? `. FAX2: ${response.ad_fax2}` : '');
                        var loading = "JAKARTA, INDONESIA";
                        var discharge = response.ad_city;
                        var destination = response.ad_city;

                        var totalNetWeight = 0;
                        var commodityList = response.details;
                        let commodity = `<ul><li><input type="text" name="commodity" class="border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.commodity ?? ''}" onInput="syncInputs(this)" required></li>`;
                        commodityList.forEach(item => {
                            var netWeightTon = item.net_weight / 1000;
                            commodity += `<li>
                                ${item.pt_desc} (${item.sod_part}) <br>
                                ${item.sod_qty_ord} X ${item.pt_net_wt}KG/${item.pt_um} = ${netWeightTon.toLocaleString('id-ID')} M/TONS
                            </li>`;
                            totalNetWeight += netWeightTon;
                        });
                        commodity += '</ul>';

                        $('#shipped').html(shipped);
                        $('#shipper').html(shipper);
                        $('#consignee').html(consignee);
                        $('#notify').html(notify);
                        $('.loading').text(loading);
                        $('#discharge').text(discharge);
                        $('.destination').text(destination);
                        $('#commodity').html(commodity);

                        var po = response.so_po ?? '';
                        $('#po').html(po);

                        var marking = `<input type="text" name="marking" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.marking ?? ''}" onInput="syncInputs(this)" required>`;
                        var certificate_no = `<input type="text" name="certificate_no" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.certificate_no ?? ''}" onInput="syncInputs(this)" required>`;
                        $('#marking').html(marking);
                        $('#certificate_no').html(certificate_no);

                        var total_net_weight = `${(totalNetWeight*1000).toLocaleString('id-ID')} KGS`;
                        var total_gross_weight = `<input type="text" name="total_gross_weight" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" onInput="formatNumberInput(this); syncInputs(this)" value="${response.total_gross ? response.total_gross.toLocaleString('id-ID') : ''}" required> KGS`;
                        var measurement = `<input type="text" name="measurement" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" onInput="formatNumberInput(this)" value="${response.measurement ?? ''}" required> M3`;
                        var container_no = `<input type="text" name="container_no" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.container_no ?? ''}" onInput="syncInputs(this)" required>`;
                        $('#total_net_weight').text(total_net_weight);
                        $('#total_gross_weight').html(total_gross_weight);
                        $('#measurement').html(measurement);
                        $('#container_no').html(container_no);

                        var stuffing = `<input type="date" name="stuffing" class="w-[120px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.stuffing}" required>`;
                        $('#stuffing').html(stuffing);

                        var notifyPacking = `${response.ad_sort}` + 
                        (response.ad_line1 ? `<br> ${response.ad_line1}` : '') +
                        (response.ad_line2 ? `<br> ${response.ad_line2}` : '') +
                        (response.ad_line3 ? `<br> ${response.ad_line3}` : '') + 
                        (response.ad_phone ? `<br> TEL: ${response.ad_phone}` : '') + 
                        (response.ad_phone2 ? `. TEL2: ${response.ad_phone2}` : '') + 
                        (response.ad_fax ? `. FAX: ${response.ad_fax}` : '') + 
                        (response.ad_fax2 ? `. FAX2: ${response.ad_fax2}` : '');
                        var consigned = `${response.ad_sort}` + 
                        (response.ad_line1 ? `<br> ${response.ad_line1}` : '') +
                        (response.ad_line2 ? `<br> ${response.ad_line2}` : '') +
                        (response.ad_line3 ? `<br> ${response.ad_line3}` : '') + 
                        (response.ad_phone ? `<br> TEL: ${response.ad_phone}` : '') + 
                        (response.ad_phone2 ? `. TEL2: ${response.ad_phone2}` : '') + 
                        (response.ad_fax ? `. FAX: ${response.ad_fax}` : '') + 
                        (response.ad_fax2 ? `. FAX2: ${response.ad_fax2}` : '');

                        $('#notifyPacking').html(notifyPacking);
                        $('#consigned').html(consigned);
                        $('#packingNo').text(response.so_nbr ?? '');
                        $('#invoiceNo').text(response.so_nbr ?? '');

                        var packingTable = $('#packingTable tbody');
                        var detailTR = $('#detailTR');
                        detailTR.empty();
                        $('#detailTR').nextAll('tr').remove();
                        var row = 
                        `
                        <tr>
                            <td colspan="4" id="commodityText">${response.commodity ?? ''}</td>
                            <td colspan="4"></td>
                        </tr>
                        `;
                        packingTable.append(row);
                        var details = response.details;
                        details.forEach((item, index) => {
                            console.log('Detail Item:', item);
                            var row = `
                            <tr>
                                <td colspan="4">
                                    <p>${item.pt_desc} (${item.sod_part})</p>
                                    <p>${item.sod_qty_ord} ${item.pt_um}</p>
                                </td>
                                <td colspan="2">${item.net_weight.toLocaleString('id-ID')}</td>
                                <td colspan="2"><input type="text" name="gross[]" class="w-[80px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" onInput="formatNumberInput(this)" value="${item.gross_weight ?? ''}" required></td>
                            </tr>
                            `;
                            packingTable.append(row);
                        });

                         var row = `
                            <tr>
                                <td colspan="4"></td>
                                <td colspan="2">${response.total_net ? Number(response.total_net).toLocaleString('id-ID') : ''}</td>
                                <td colspan="2" id="total_gross_weightText">${response.total_gross ? Number(response.total_gross).toLocaleString('id-ID') : ''}</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <p>BATCH NO.:</p>
                                    <p><input type="text" name="batch_no" class="w-[160px] border-0 border-b border-red-600 bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.batch_no ?? ''}" required></p>
                                </td>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <p>CONTAINER NO. / SEAL NO.:</p>
                                    <p id="container_noText">${response.container_no ?? ''}</p>
                                </td>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="4" id="markingText">${response.marking ?? ''}</td>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="4">CERTIFICATE NO : <span id="certificate_noText">${response.certificate_no ?? ''}</span></td>
                                <td colspan="2"></td>
                                <td colspan="2"></td>
                            </tr>
                        `;
                        packingTable.append(row);
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                        showFailed("Gagal mengambil data");
                    }
                });
            }

            $('#no').on('blur keydown', function(e) {
                const val = $(this).val();
                if ((e.type === 'Enter' || e.keyCode === 13) && val !== '') {
                    resetData();
                }
                // if (e.type === 'blur' || (e.type === 'keydown' && (e.key === 'Enter' || e.keyCode === 13))) {
                //     resetData();
                // }
            });

            function formatNumberInput(input) {
                // Ambil hanya angka dan koma
                let value = input.value.replace(/[^0-9,]/g, '');

                // Jika ada lebih dari satu koma, ambil hanya yang pertama
                let parts = value.split(',');
                if (parts.length > 2) {
                    value = parts[0] + ',' + parts[1];
                }

                // Ganti koma dengan titik untuk parsing float
                let number = parseFloat(value.replace(',', '.'));

                // Jika hasil parsing adalah angka, format ke locale Indonesia
                if (!isNaN(number)) {
                    // Pisahkan bagian desimal jika ada
                    let [integer, decimal] = value.split(',');
                    let formatted = parseInt(integer, 10).toLocaleString('id-ID');
                    if (decimal !== undefined) {
                        formatted += ',' + decimal;
                    }
                    input.value = formatted;
                } else {
                    input.value = '';
                }
            }


            document.getElementById('exportForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Cegah submit standar

                let form = this;
                let formData = new FormData(form);
                console.log('Form Data:', formData, formData.entries().length, formData.keys());
                if ([...formData.entries()].length <= 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: 'Form harus diisi terlebih dahulu. Tekan enter untuk mengambil data form dari nomor SO.',
                    });
                    return;
                }
                if (!formData || [...formData.entries()].length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: 'Form tidak boleh kosong.'
                    });
                    return;
                }
                let total = 0;total_gross_weight
                document.querySelectorAll('input[name="gross[]"]').forEach(function(input) {
                    // Hilangkan titik ribuan dan ganti koma dengan titik
                    let value = input.value.replace(/\./g, '').replace(',', '.');
                    let num = parseFloat(value);
                    if (!isNaN(num)) total += num;
                });
                // Ambil input dengan nama total_gross_weight
                let totalGrossWeightInput = document.querySelector('input[name="total_gross_weight"]');
                let totalGrossWeightValue = 0;
                if (totalGrossWeightInput) {
                    // Hilangkan titik ribuan dan ganti koma dengan titik
                    let value = totalGrossWeightInput.value.replace(/\./g, '').replace(',', '.');
                    let num = parseFloat(value);
                    if (!isNaN(num)) totalGrossWeightValue = num;
                }

                if (totalGrossWeightValue !== total) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: 'Jumlah gross pada detail harus sama dengan Total Gross Weight.'
                    });
                    return;
                }

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON.error;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: message
                        });
                    }
                });
            });

            function syncInputs(input)
            {
                const targetId = input.name + 'Text';
                const target = document.getElementById(targetId);
                if (target) {
                    target.textContent = input.value;
                }
            }

            // Penanganan pesan sukses
            @if (session()->has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session()->get('success') }}',
                    text: '{{ session()->get('message') }}',
                });
            @endif
        </script>
    @endpush
</x-app-layout>
