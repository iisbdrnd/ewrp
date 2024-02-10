<?php $panelTitle = "Gallery Album Sorting"; ?>
<style>
#sortable li {
      display: inline-block;
      text-align: center;
      margin-bottom: 20px;
    }
      #sortable li:hover {
      cursor: pointer;
    }

    #sortable li .box {
      height: 100%;
      /*background: #eee;*/
      width: 100%;
      padding: 5px;
      overflow: hidden;
      -moz-border-radius: 4px;
      -webkit-border-radius: 4px;
      border-radius: 4px;
    }

  
    #sortable li .box.nostatus {
  /*position: absolute;*/
  top: 0;
  left: 0;
  z-index: -1;
  height: 120px;
  width: 120px;
  -webkit-transform-style: preserve-3d;
  overflow: hidden;

          
    }


    #sortable li .box.nostatus img {
     /*position: absolute;*/
  z-index: -1;
  top:50%;
  left:50%;
  height:100%;
  width:100%;
  min-width:100%;
  min-height:100%;
  /*transform:translate(-50%, -50%);*/
  object-fit:cover;
    }


    #sortable li .box.nostatus img .img-thumbnail {
    padding: 4px;
    line-height: 1.42857143;
    /*background-color: #f2f2f2;*/
    border: 1px solid #c4c4c4;
    border-radius: 2px;
    transition: all .2s ease-in-out;
    display: inline-block;
    max-width: 100%;
    /*height: auto;*/
}

    .todo-task-item .close{
      position: absolute;
      margin-left: 43px;
        margin-top: -123px;
        /*padding: 10px 5px;*/
        background-color: black;
        border-radius: 50%;
    }
    .todo-task-item .close i{
        color: white;
        font-size: 7.5px!important;
    }

   
   
   

</style>


<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    
  <div class="form-group"> 
    <div class="row mt15">
        <div class="col-sm-12">
            {{-- <h5 style="text-align:center;">Drag Boxes Around</h5> --}}
            <ul class="clearfix" id="sortable">
             
            @foreach($albumImages as $album)

                  <li id="{{$album->id}}" >
                    <div class="box nostatus">
                        <img class="img-thumbnail" src="{{url('public/uploads/gallery/'.$album->gallery_thumb)}}">
                    </div>
                    
                </li>
            @endforeach
            </ul>
        </div>
    </div>
  </div>





</div>
    <div class="row mt15">
        <div class="col-sm-12">
        <a class="btn btn-default btn-sm ajax-link" href="gallery" type="button" style="margin-left: 12px;">Back to List</a>
      </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $( "#sortable" ).sortable({
          beforeStop: function( event, ui ) {
                var sorted = $( "#sortable" ).sortable( "toArray" );
                $.ajax({
                    url: '{{route("provider.eastWest.galleryAlbumSorting")}}',
                    data: {_token: "{{ csrf_token() }}", sorted: sorted},
                    type: 'post'
                });
            }
        });
        $( "#sortable" ).disableSelection();
    });

</script>