<?php $panelTitle = 'Menu Sorting'; ?>
@include("panelStart")
    <div class="row mb20">
        <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label" style="text-align:right">Folder</label>
            <div class="col-lg-4 col-md-4">
                <select required id="folder_id" name="folder_id" class="form-control select2">
                    <option value="">None</option>
                    @foreach($folder as $folder)
                        <option value="{{$folder->id}}">{{$folder->folder_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div id="software_module"></div>
    
@include("panelEnd")
@if(empty($inputData['takeContent']))<div id="menu-view"></div>@endif

<script type="text/javascript">
    $('document').ready(function(){
        $("#folder_id").select2({placeholder:"Select Folder"});

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $.ajax({
                    url: "{{route('softAdmin.menuSortingModuleView')}}",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        $('#software_module').html(data);
                        $('#menu-view').html('');
                    }
                });
            }
        });

        $('#software_module').on('click', '.soft-module', function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var data = $(this).attr('data');
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "{{route('softAdmin.softwareMenuSortingMenuList')}}",
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