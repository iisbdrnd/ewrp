<?php $panelTitle = "Gallery Create"; ?>
@include("panelStart")
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form type="create" panelTitle="{{$panelTitle}}" id="expiry_date_form" class="form-load form-horizontal">
            {{csrf_field()}}
            <div class=form-group>
                <label class="col-lg-2 col-md-3 control-label">Image</label>
                <div class="col-lg-8 col-md-7 pl0" id="file_attachment">
                    {{--<div class="col-lg-10 col-md-9">
                        <button id="file_attachment_upload" input-prefix="image" file-attach-direction="last" callback="up_images" remove-callback="down_images" file-path="public/uploads/gallery" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Image Upload </button>
                        <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
                    </div>--}}
                    
                    <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                        <div id="attachment_area_file_attachment_upload" class="attachment_area">
                            <div class="attachment-item clearfix image">
                                <div class="attachment-img" style="background-image: url()}})"></div>
                                @endif
                                <div class="attachment-content">
                                    <div class="close_x"><span class="fa fa-close remove_files" file_name="" filepath="public/uploads/corporate_certificate_image" auto-remove="false"></span></div>
                                    <div class="attachment-title">
                                        <a class="igniterImg" href="{{url('public/uploads/corporate_certificate_image/')}}" target="_blank"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            {{-- <div class="form-group">
                <label for="status" class="col-lg-3 col-md-3 control-label">Published Status</label>
                <div class="col-lg-9 col-md-9">
                    <div class="toggle-custom toggle-inline">
                        <label class=toggle data-on=Yes data-off=No>
                        <input type=checkbox name="status" id="status" value="1"> <span class=button-checkbox></span>
                        </label>
                    </div>
                </div>
            </div> --}}

        </form>
    </div>
</div>
@include("panelEnd")


<script type="text/javascript">
    $(document).ready(function() {
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
        $("select.select2").select2({
           placeholder: "Select"
        });
    });


        $("#expiry_date_form #event_type").change(function(){
           var event_type = $(this).val();
           if(event_type == 2){
                $('#cheque_no').hide();
                $('#expiry_date_form').formValidation('removeField', $('#cheque_no')); 
            } else {   
                $('#cheque_no').show();
                $('#expiry_date_form').formValidation('addField', $('#cheque_no'));
            }
        });

</script>