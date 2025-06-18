<x-app-layout>
    @section('title')
        Inventory
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

            
            footer img {
                width: 100%;
                height: auto;
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
                        <li class="breadcrumb-item pr-1" aria-current="page"> Data Dashboard</li>
                        <li class="breadcrumb-item active" aria-current="page"> Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="content main-background">
        <header class="w-72 h-32 mr-auto mb-5">
            <img src="{{ asset('assets/export/logo.png')  }}">
        </header>
        <main class="">
            <form action="{{ route('export.store') }}" method="POST" id="exportForm" class="w-full h-full flex flex-col items-center justify-center">
                @csrf    
                <div class="flex flex-col items-center">
                    <h2 class="text-2xl">SHIPPING INSTRUCTION</h2>
                    <div class="text-xl">
                        <span>ESM/</span>
                        <input id="no" name="no"
                            type="number" 
                            value="1393" 
                            pattern="[0-9]*"
                            class="w-[40px] border-0 border-b border-black text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner text-xl">
                        <span>/04/2025</span>
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
                                        <td id="loading"></td>
                                    </tr>
                                    <tr>
                                        <td>Port of Discharge</td>
                                        <td>:</td>
                                        <td id="discharge"></td>
                                    </tr>
                                    <tr>
                                        <td>Final of Destination</td>
                                        <td>:</td>
                                        <td id="destination"></td>
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
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </main>
        
        <footer>
            <img src="{{ asset('/assets/export/footer.png')}}" alt="">
        </footer>
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
                            ETD: <input type="date" name="etd" class="w-[120px] border-0 border-b border-black text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.etd ?? ''}" required> - 
                            ETA: <input type="date" name="eta" class="w-[120px] border-0 border-b border-black text-center bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.eta ?? ''}" required>
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
                        let commodity = `<ul><li><input type="text" name="commodity" class="border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.commodity ?? ''}" required></li>`;
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
                        $('#loading').text(loading);
                        $('#discharge').text(discharge);
                        $('#destination').text(destination);
                        $('#commodity').html(commodity);

                        var po = response.so_po ?? '';
                        $('#po').html(po);

                        var marking = `<input type="text" name="marking" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.marking ?? ''}" required>`;
                        var certificate_no = `<input type="text" name="certificate_no" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.certificate_no ?? ''}" required>`;
                        $('#marking').html(marking);
                        $('#certificate_no').html(certificate_no);

                        var total_net_weight = `${(totalNetWeight*1000).toLocaleString('id-ID')} KGS`;
                        var total_gross_weight = `<input type="text" name="total_gross_weight" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" onInput="formatNumberInput(this)" value="${response.total_gross ?? ''}" required> KGS`;
                        var measurement = `<input type="text" name="measurement" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" onInput="formatNumberInput(this)" value="${response.measurement ?? ''}" required> M3`;
                        var container_no = `<input type="text" name="container_no" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.container_no ?? ''}" required>`;
                        $('#total_net_weight').text(total_net_weight);
                        $('#total_gross_weight').html(total_gross_weight);
                        $('#measurement').html(measurement);
                        $('#container_no').html(container_no);

                        var stuffing = `<input type="date" name="stuffing" class="w-[120px] border-0 border-b border-black bg-transparent focus:outline-none p-0 leading-tight no-spinner" value="${response.stuffing}" required>`;
                        $('#stuffing').html(stuffing);
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
