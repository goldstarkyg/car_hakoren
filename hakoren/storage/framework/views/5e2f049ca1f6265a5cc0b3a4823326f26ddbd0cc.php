<script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#info-table').dataTable({
            "order": [[ 3, 'desc' ]],
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
            "language": {
                processing:     "処理中...",
                search:         "検索:",
                lengthMenu:     "_MENU_個の要素を表示",
                info:           "_START_ ~ _END_  を表示中&nbsp;&nbsp;|&nbsp;&nbsp;全項目 _TOTAL_",
                infoEmpty:      "0件中0件から0件までを表示",
                infoFiltered:   "（合計で_MAX_個のアイテムからフィルタリングされました）",
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

        //select charge system
        for(var i= 0 ;i<3; i++ ) {
            var sel, tog;
            if(i==0) {
                sel = $('#smoke').val();
                tog = $('#smokeBtn span').data('toggle');
            }
            if(i==1) {
                sel = $('#dropoff_availability').val();
                tog = $('#dropoffBtn span').data('toggle');
            }
            if(i==2) {
                sel = $('#status').val();
                tog = $('#statusBtn span').data('toggle');
            }
            $('#' + tog).val(sel);
            $('span[data-toggle="' + tog + '"]').not('[data-value="' + sel + '"]').removeClass('active btn-primary').addClass('notActive btn-default');
            $('span[data-toggle="' + tog + '"][data-value="' + sel + '"]').removeClass('notActive btn-default').addClass('active btn-primary');

        }

    });
</script>