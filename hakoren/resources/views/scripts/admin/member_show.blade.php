<script src="{{URL::to('/')}}/js/plugins/dataTables/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/js/plugins/dataTables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.table').dataTable({
            // "scrollX": true,
            "order": [[ 0, 'desc' ]],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength" : 25,
            "ordering": true,
            "info": true,
            // "autoWidth": true,
            "dom": 'T<"clear">lfrtip',
            "sPaginationType": "full_numbers",
            "language": {
                processing: "処理中...",
                search: "検索:",
                lengthMenu: "_MENU_個の要素を表示",
                info: "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                infoEmpty: "0件中0件から0件までを表示",
                infoFiltered: "（合計で_MAX_個のアイテムからフィルタリングされました）",
                infoPostFix: "",
                loadingRecords: "読み込んでいます...",
                zeroRecords: "表示する項目がありません",
                emptyTable: "テーブルのデータがありません",
                paginate: {
                    first: "最初",
                    previous: "以前",
                    next: "次に",
                    last: "最終"
                },
                aria: {
                    sortAscending: ": 列を昇順にソートする有効にします。",
                    sortDescending: ": 列を降順で並べ替えるためにアクティブにする"
                }
            }
        });
    });
</script>