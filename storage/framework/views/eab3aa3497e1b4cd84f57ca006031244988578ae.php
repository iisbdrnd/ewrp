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
<div class="col-md-3 col-xs-12 pull-right">
  
</div>
<div class="row mt15">
    <div class="col-sm-12">
        
        <ul class="clearfix" id="sortable">
         
        <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
              <li id="<?php echo e($gallery->id); ?>" class="todo-task-item">
                <div class="box nostatus">
                    <img class="img-thumbnail" src="<?php echo e(url('public/uploads/gallery/thumb/'.$gallery->image_thumb)); ?>">
                </div>
                <button type="button" class="close todo-close">
                    <i aria-hidden="true" class="icomoon-icon-close" title="remove" id="delete" data="<?php echo e($gallery->id); ?>"></i>
                </button>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<div class="row">
  <div class="col-md-12 col-xs-12">
      
  </div>
</div>

<script>
    $(document).ready(function() {
        $( "#sortable" ).sortable({
          beforeStop: function( event, ui ) {
                var sorted = $( "#sortable" ).sortable( "toArray" );
                $.ajax({
                    url: '<?php echo e(route("provider.eastWest.gallerySortingAction")); ?>',
                    data: {_token: "<?php echo e(csrf_token()); ?>", sorted: sorted},
                    type: 'post',
                });
            }
        });
        $( "#sortable" ).disableSelection();
    });
    
    // $('#sortable li').addClass('ui-state-default col-md-3 col-sm-4 col-xs-12');
    $('.danger h4').prepend('<i class="fa fa-exclamation-circle"></i>');
    $('.good h4').prepend('<i class="fa fa-check"></i>');
    $('.excellent h4').prepend('<i class="fa fa-star"></i>');
</script>
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/galleryimage/listData.blade.php ENDPATH**/ ?>