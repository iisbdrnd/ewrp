<?php $panelTitle = "Project Configuration"; ?>
<style type="text/css">
    /* Floats */

.left {float: left;}
.right {float: right;}

.clear,.clearer {clear: both;}
.clearer {
    display: block;
    font-size: 0;
    height: 0;
    line-height: 0;
}


/*
    Example specifics
------------------------------------------------------------------- */

/* Layout */

#center-wrapper {
    margin: 0 auto;
    /*width: 920px;*/
    width: 100%;
}


/* Columns */

/*.column {
    margin-left: 2%;
    width: 32%;
}
.column.first {margin-left: 0;}*/


/* Sortable items */

.sortable-list {
    background-color: #fff;
    list-style: none;
    /*margin: 0;*/
    margin-top:20px;
    min-height: 300px;
    padding: 10px 10px 0px 10px;
    /*padding-top:10px;*/
    border:5px dashed #eee;
}
.sortable-item {
    background-color: #FFF;

   border: 1px dotted #000;
    cursor: move;
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    padding: 5px 5px;
    /*text-align: center;*/
}

/* Containment area */

#containment {
    background-color: #FFA;
    height: 230px;
}


/* Item placeholder (visual helper) */

.placeholder {
    background-color: #BFB;
    border: 1px dashed #666;
    height: 58px;
    margin-bottom: 5px;
}
</style>
@include("panelStart")
<div class="row mt20">
   {{-- <h1 class="text-info text-center">Drag and drop items</h1><hr><br> --}}
    {{-- <form type="configure" id="form-data" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped"> --}}
        
        {{-- <div id="center-wrapper"> --}} 
            <div class="dhe-example-section-content">
                <div id="example-1-2">
                    {{-- <div class="col-lg-3"></div> --}}
                    <div class="column left first col-lg-3 col-lg-offset-3">
                        <h4 class="text-center">Project Default Item</h4>
                        <ul class="sortable-list" id="defaultItem">
                            <?php $i=0; ?>
                            @foreach($mobilizations as $mobilization)
                               <?php $i++ ?>
                                <li class="sortable-item text-info">
                                    <input type="hidden" name="mobilization_id[]" value="{{ $mobilization->id }}">{{ $i." ".  $mobilization->name }}

                                </li> 
                            @endforeach
                        </ul>
                    </div>
                    <div class="column left col-lg-3">
                        <h4 class="text-center">Project Configuration Item</h4>
                        <form  type="configure" id="form-data" panelTitle="{{$panelTitle}}" class="form-load">
                            {{csrf_field()}}
                            <ul class="sortable-list" id="configureItem"> 
                              <input type="hidden" name="project_id" value="{{ $project_id }}">
                              @forelse($edit_configures as $edit_configure) 
                               <li class="sortable-item text-info">
                                <input type="hidden" value="{{ $edit_configure->id }}">{{ $edit_configure->id }}
                               </li>
                              @empty
                              @endforelse 
                            </ul>
                            <div class="panel-footer col-lg-12">
                                <button type="submit" id="save-button" class="btn btn-success btn-md pull-right form-control">Save</button>
                            </div>
                        </form>   
                    </div>
                    {{-- <div class="col-lg-3"></div> --}}
                    <div class="clearer">&nbsp;</div>
                </div>
                <!-- END: XHTML for example 1.2 -->
            </div>
        {{-- </div> --}}
    {{-- </form> --}}
</div>
<br><br>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });

        // Example 1.2: Sortable and connectable lists
        $('#example-1-2 .sortable-list').sortable({
            connectWith: '#example-1-2 .sortable-list'
        });

       
    });

    $('#save-button').on('click',function(){
        var data = new FormData($('#form-data')[0]);
         $.ajax({
           type: "POST",
           url: '{{ url('storeConfiguration') }}',
           data: data,
           success: function(data)
           {
              console.log(data);
           }
         });

    e.preventDefault();
    });


</script>