{{-- FYI: Datatables do not support colspan or rowpan --}}

<script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
<script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        $('.data-table').dataTable({
            "order": [[ 2, 'desc' ]],
            "paging": false,
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
                ,{ 'orderable': false, 'targets':3}
                ,{ 'orderable': false, 'targets':4}
            ],
            "language": {
                "info": "_START_ ~ _END_ を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全ての管理者 _TOTAL_"
            }
        });
    });
</script>