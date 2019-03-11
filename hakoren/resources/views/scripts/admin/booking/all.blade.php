<!-- <script src="{{URL::to('/')}}/js/jquery.dataTables2.js"></script>
<script src="{{URL::to('/')}}/js/dataTables.bootstrap.js"></script> -->
<script src="{{URL::to('/')}}/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.data-table').dataTable({
            "responsive": true,
            "scrollX": true,
            "order": [[ 0, 'desc' ]],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength" : 25,
//            lengthMenu: [
//                [ 10, 24, 50, 100],
//                [ '10', '24', '50', '100' ]
//            ],
//            buttons: [
//                'pageLength'
//            ],
            "serverSide": false,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "dom": 'T<"clear">lfrtip',
            "sPaginationType": "full_numbers",
            // 'columnDefs': [{ 'orderable': true, 'targets': 0,'width':'50px' }
            //     ,{ 'orderable': true, 'targets': 1}
            //     ,{ 'orderable': true, 'targets': 2}
            //     ,{ 'orderable': true, 'targets':3}
            //     ,{ 'orderable': true, 'targets':4}
            //     ,{ 'orderable': true, 'targets':5}
            //     ,{ 'orderable': true, 'targets':6}
            //     ,{ 'orderable': true, 'targets':7}
            //     ,{ 'orderable': true, 'targets':8}
            //     ,{ 'orderable': true, 'targets':9}
            //     ,{ 'orderable': true, 'targets':10}
            //     ,{ 'orderable': true, 'targets':11}
            //     ,{ 'orderable': true, 'targets':12}
            //     ,{ 'orderable': true, 'targets':13}
            //     ,{ 'orderable': true, 'targets':14}
            //     ,{ 'orderable': false, 'targets':15}
            // ],
            "language": {
                processing:     "処理中...",
                search:         "検索:",
                lengthMenu:     "_MENU_個の予約を表示",
                info:           "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                infoEmpty:      "0件中0件から0件までを表示",
                infoFiltered:   "（合計で_MAX_個の項目からフィルタリングされました）",
                infoPostFix:    "",
                loadingRecords: "読み込んでいます...",
                zeroRecords:    "表示する項目がありません",
                emptyTable:     "テーブルのデータがありません",
                paginate: {
                    first:      "最初",
                    previous:   "以前",
                    next:       "次に",
                    last:       "最終"
                },
                aria: {
                    sortAscending:  ": 列を昇順にソートする有効にします。",
                    sortDescending: ": 列を降順で並べ替えるためにアクティブにする"
                }
            }
        });
    });
</script>
<script src="{{URL::to('/')}}/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="{{URL::to('/')}}/js/plugins/fullcalendar/moment.min.js"></script>

<script src="{{URL::to('/')}}/js/plugins/daterangepicker/daterangepicker.js"></script>
<script>
    function clicksearch(){
        $('#searchform').submit();
    }
    $('#dateinterval').daterangepicker(
            {
                format: 'YYYY/MM/DD',
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'));
            });
</script>