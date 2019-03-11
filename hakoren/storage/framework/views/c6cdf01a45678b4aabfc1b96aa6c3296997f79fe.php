<script src="<?php echo e(URL::to('/')); ?>/js/plugins/chosen/chosen.jquery.min.js"></script>

<script>
    /*search and select model*/
    $(".chosen-select").chosen({
        max_selected_options: 5,
        no_results_text: "Oops, nothing found!",
    });
</script>