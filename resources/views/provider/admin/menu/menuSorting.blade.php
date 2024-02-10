<?php $panelTitle = 'Menu Sorting'; ?>
@include("panelStart")
    @foreach($software_modules as $software_module_list)
        <div class=row>
            @foreach($software_module_list as $software_module)
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <a href=# data="{{$software_module->id}}" class="soft-module stats-btn pattern mb20"><i class="icon {{$software_module->module_icon}}"></i> <span class=txt>{{$software_module->module_name}}</span></a>
                </div>
            @endforeach
        </div>
    @endforeach
@include("panelEnd")
@if(empty($inputData['takeContent']))<div id="menu-view"></div>@endif

<script type="text/javascript">
    $('document').ready(function(){
        $('.soft-module').click(function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var data = $(this).attr('data');
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "{{route('provider.admin.menuSortingMenuList')}}",
                    data: {module_id: data},
                    success: function (data) {
                        dataFilter(data);
                        $('#menu-view').html(data);
                    }
                });
            }
        });
    });
</script>