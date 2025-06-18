<x-app-layout>
    @section('title')
Dashboard
    @endsection

    
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.jqueryui.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
    @endpush

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
                                    let url = "{{ route('export.print', ['no' => ':no']) }}".replace(':no', row.id);
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
