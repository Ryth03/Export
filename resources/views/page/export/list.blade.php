<x-app-layout>
    @section('title')
        Export
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
                        <li class="breadcrumb-item pr-1" aria-current="page"> Export</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="box">
        <div class="box-header flex justify-between mb-4">
            <h2 class="page-title text-2xl font-medium">Export List</h2>
            <a href="{{ route('export.index') }}" class="btn btn-success">Create</a>
        </div>
        <div class="box-body">
            <table id="tableExport" class="!border-separate table table-bordered w-full">
                <thead>
                    <tr>
                        <th>No. SO</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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
            document.addEventListener('DOMContentLoaded', function() {
                $(document).ready(function() {
                    $('#tableExport').DataTable({
                        processing: true,
                        serverSide: false,
                        ajax: {
                            url: '{{ route('dashboard.export.get') }}',
                            type: 'GET',
                            dataSrc: function(response) {
                                console.log(response);
                                return response;
                            }
                        },
                        columns: [
                            { data: 'so_nbr', name: 'no' },
                            { data: 'ad_sort', name: 'name' },
                            {
                                data: null,
                                name: 'action',
                                render: function(data, type, row) {
                                    let url = "{{ route('export.print', ['no' => ':no']) }}".replace(':no', row.so_nbr);
                                    return `<a href="${url}" target="_blank" class="btn btn-sm btn-primary">Print</a>`;
                                }
                            }
                        ]
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
