<script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
<script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.data-table').dataTable({
            "scrollX": false,
            "order": [[ 0, 'desc' ]],
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
            'columnDefs': [{ 'orderable': true, 'targets': 0}
                ,{ 'orderable': true, 'targets': 1}
                ,{ 'orderable': true, 'targets': 2}
                ,{ 'orderable': true, 'targets': 3}
                ,{ 'orderable': true, 'targets': 4}
                ,{ 'orderable': false, 'targets': 5}
            ],
            "language": {
                "info": "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                "lengthMenu":     "_MENU_件を表示",
            }
        });
    });
</script>
