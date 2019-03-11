<link href="{{URL::to('/')}}/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/css/bootstrap-select.css" rel="stylesheet">

<script src="{{URL::to('/')}}/plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="{{URL::to('/')}}/js/bootstrap-select.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var $nonsmoke_sort  = $('#nonsmoke_sort');
        var $smoke_sort     = $('#smoke_sort');
        var $smoke_order    = $('#smoke_orders');
        var $nonsmoke_order = $('#nonsmoke_orders');
        var $shop_list      = $('#shop_list');
        var $model_list     = $('#model_list');
        var $count_all       = $('#count_all');
        var $count_smoke     = $('#count_smoke');
        var $count_nonsmoke  = $('#count_nonsmoke');

        var shop_id         = {{ $shop_id }};
        var model_id        = 0;

        var shops           = {!! $j_shops !!};
        var modelObjs       = {!! $models !!};
        var smokeObjs       = {!! $smokes !!};
        var nonsmokeObjs    = {!! $nonsmokes !!};
        var change_smoke    = false;
        var change_nonsmoke = false;

        var shop_ids = [];
        $shop_list.empty();
        for(var k = 0; k < shops.length; k++) {
            var shop = shops[k];
            shop_ids.push(shop.id);
            var selected = '';
            if(shop_id == shop.id) selected = 'selected';
            var option = '<option value="' + shop.id + '" ' + selected +'>'+ shop.name +'</option>';
            $shop_list.append(option);
        }
        $shop_list.selectpicker();

        function init_car_list(shop_id, model_id) {
            // fill smoke_list
            var cars = smokeObjs[shop_id][model_id];
            var smoke_count = cars.length;

            $smoke_sort.empty();
            for(var k = 0; k < smoke_count; k++) {
                var car = cars[k];
                var number = car.numberplate1 + car.numberplate2 + car.numberplate3 + car.numberplate4;
                var option = '<li class="ui-state-default" order="'+ car.id + '">' +
                        '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' + number +'&nbsp;/&nbsp;'+car.shortname+
                        '</li>';
                $smoke_sort.append(option);
            }
            $smoke_sort.sortable({
                create: function( event, ui ) { getOrder($smoke_sort, $smoke_order); },
                update: function( event, ui ) { getOrder($smoke_sort, $smoke_order); }
            });
            $smoke_sort.disableSelection();
            // fill nonsmoke list
            cars = nonsmokeObjs[shop_id][model_id];
            var nonsmoke_count = cars.length;

            $nonsmoke_sort.empty();
            for(var k = 0; k < nonsmoke_count; k++) {
                var car = cars[k];
                var number = car.numberplate1 + car.numberplate2 + car.numberplate3 + car.numberplate4;
                var option = '<li class="ui-state-default" order="'+ car.id + '">' +
                    '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' + number +'&nbsp;/&nbsp;'+car.shortname+
                    '</li>';
                $nonsmoke_sort.append(option);
            }
            $nonsmoke_sort.sortable({
                create: function( event, ui ) { getOrder($nonsmoke_sort, $nonsmoke_order); },
                update: function( event, ui ) { getOrder($nonsmoke_sort, $nonsmoke_order); }
            });
            $nonsmoke_sort.disableSelection();

            $count_all.text(nonsmoke_count + smoke_count);
            $count_smoke.text(smoke_count);
            $count_nonsmoke.text(nonsmoke_count);

            getOrder($nonsmoke_sort, $nonsmoke_order);
            getOrder($smoke_sort, $smoke_order);
        }

        function init_model_list(shop_id) {
            $model_list.empty();
            var models = modelObjs[shop_id];
            for(var k = 0; k < models.length; k++) {
                var model = models[k];
                if(k == 0) {
                    selected = 'selected';
                    model_id = model.model_id;
                }
                var option = '<option value="' + model.model_id + '">'+ model.name +'</option>';
                $model_list.append(option);
            }
            $model_list.selectpicker();
            $model_list.selectpicker('refresh');

            init_car_list(shop_id, model_id)
        }

        init_model_list(shop_id);

        function getOrder($sortable, $order) {
            var models = $sortable.find('li');
            var orders = [];
            for(var k = 0; k < models.length; k++) {
                orders.push($(models[k]).attr('order'));
            }
            $order.val(orders.join(','));
        }

        $('#btn-save').click(function () {
            $('#shop').val(shop_id);
            $('#model').val(model_id);
            $('#saveform').submit();
        });

        $shop_list.change( function () {
            shop_id = $(this).val();
            init_model_list(shop_id);
        });

        $model_list.change( function () {
            model_id = $(this).val();
            init_car_list(shop_id, model_id);
        });
     });
</script>
