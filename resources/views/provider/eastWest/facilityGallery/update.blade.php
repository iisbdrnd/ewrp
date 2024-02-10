

<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form type="update" class="form-load form-horizontal" id="expiry_date_form">
        {{csrf_field()}}
            <div class=form-group>
                <label class="col-lg-2 col-md-3 control-label">Image</label>
                <div class="col-lg-8 col-md-7 pl0" id="file_attachment">
                    <div class="col-lg-10 col-md-9">
                        <button id="file_attachment_upload" input-prefix="image" file-attach-direction="last" callback="up_images" remove-callback="down_images" file-path="public/uploads/corporate_certificate_image" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Image Upload </button>
                        <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
                    </div>
                    <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                        <div id="attachment_area_file_attachment_upload" class="attachment_area">
                            @if($corporateAgreementInfo->certificate_configuration==1)
                                @if($corCertificateInfo->certificate_status==1)
                                    @foreach($certificateImages as $mdlFile)
                                    <div class="attachment-item clearfix image">
                                        @if(!empty($mdlFile->image))
                                        <div class="attachment-img" style="background-image: url({{Helper::getFileThumb($mdlFile->image, 'public/uploads/corporate_certificate_image')}})"></div>
                                        @endif
                                        <div class="attachment-content">
                                            <div class="close_x"><span class="fa fa-close remove_files" file_name="{{$mdlFile->image}}" filepath="public/uploads/corporate_certificate_image" auto-remove="false"></span></div>
                                            <div class="attachment-title">
                                                <a class="igniterImg" href="{{url('public/uploads/corporate_certificate_image/'.$mdlFile->image)}}" target="_blank">{{$mdlFile->image_real_name}} {{$mdlFile->image_parameter}}</a>
                                            </div>
                                            <?php
                                                $d=strtotime($mdlFile->created_at);
                                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                                            ?>
                                            <div class="attachment-date">{{$uploaded_at}}</div>
                                            <div class="attachment-size">{{Helper::fileSizeConvert($mdlFile->image_size)}}</div>
                                            <input name="image_attachment_id[]" value="{{$mdlFile->id}}" type="hidden">
                                            <input name="image_attachment[]" value="{{$mdlFile->image}}" type="hidden">
                                            <input name="image_attachment_real_name[]" value="{{$mdlFile->image_real_name}}" type="hidden">
                                            <input name="image_attachment_size[]" value="{{$mdlFile->image_size}}" type="hidden">
                                            <input name="image_parameter[]" value="{{$mdlFile->image_parameter}}" type="hidden">
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg-3 col-md-3 control-label">Published Status</label>
                <div class="col-lg-9 col-md-9">
                    <div class="toggle-custom toggle-inline">
                        <label class=toggle data-on=Yes data-off=No>
                        <input type=checkbox name="status" {{$event->status ? 'checked' : '' }} value="1"> <span class=button-checkbox></span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        var event_t = {{$event->event_type}};
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
        $("select.select2").select2({
           placeholder: "Select",
        });

        if (event_t == 2) {
            $('#expiry_date_form #cheque_no').hide();

        }else{ 
            $('#expiry_date_form #cheque_no').show();
            $('#expiry_date_form').formValidation('addField', $('#cheque_no'));    
        } 
    });

</script>

