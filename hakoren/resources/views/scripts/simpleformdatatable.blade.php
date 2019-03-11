{{-- FYI: Datatables do not support colspan or rowpan --}}

<script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
<script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('.data-table').dataTable({
            "order": [[ 1, 'desc' ]],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength" : 25,
            "serverSide": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "dom": 'T<"clear">lfrtip',
            "sPaginationType": "full_numbers",
            'columnDefs': [{ 'orderable': true, 'targets': 0 }
                ,{ 'orderable': true, 'targets': 1 }
                ,{ 'orderable': true, 'targets': 2 }
                ,{ 'orderable': true, 'targets': 3 }
                ,{ 'orderable': true, 'targets': 4 }
                ,{ 'orderable': true, 'targets': 5 ,'width': '10%' }
                ,{ 'orderable': false, 'targets': 6 }
            ],
        });
    });
</script>