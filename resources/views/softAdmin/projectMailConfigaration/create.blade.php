<?php $panelTitle = "Project Mail Configaration"; ?>
@include("panelStart")
<form type="update" id="mailConfigarationForm" action={{url('softAdmin/projectMailConfigurationAc')}} data-fv-excluded="" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="row mt15">
        <!--Left Side Box-->
        <div class="col-lg-12 col-md-12 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class="simple-chart">
                        <input type="hidden" name="project_id" class="form-control" @if(!empty($mailConfigaration))value="{{$mailConfigaration->project_id}}"@else value="{{$project_id}}"@endif>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">Mail From</label>
                            <div class="col-lg-5 col-md-5">
                                <input required type="email" name="mail_from_email" data-fv-row=".col-md-5" placeholder="Mail From Email" id='form_email' class="form_email form-control from_email" @if(!empty($mailConfigaration))value="{{$mailConfigaration->mail_from_email}}"@endif>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <input required name="mail_from_name" data-fv-row=".col-md-5" placeholder="Mail From Name" class="form-control" @if(!empty($mailConfigaration))value="{{$mailConfigaration->mail_from_name}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">Mail Protocol</label>
                            <div class="col-lg-10 col-md-10">
                                <select required id="mail_protocal" name="mail_protocal" data-fv-row=".col-md-10"  data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=1 @if(@$mailConfigaration->mail_protocal==1){{'selected'}}@endif>SMTP</option>
                                    <option value=2 @if(@$mailConfigaration->mail_protocal==2){{'selected'}}@endif>Mail</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">SMTP Hostname</label>
                            <div class="col-lg-10 col-md-10">
                                <input name="smtp_hostname" data-fv-row=".col-md-10" placeholder="Ex:smtp.gmail.com" class="form-control disabled_input" @if(!empty($mailConfigaration))value="{{$mailConfigaration->smtp_hostname}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">SMTP Username</label>
                            <div class="col-lg-10 col-md-10">
                                <input readonly type="email" name="smtp_username" data-fv-row=".col-md-10" placeholder="SMTP Username" class="form-control disabled_input" id='smtp_user_name' @if(!empty($mailConfigaration))value="{{$mailConfigaration->smtp_username}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">SMTP Password</label>
                            <div class="col-lg-10 col-md-10">
                                <input type="password" name="smtp_password" data-fv-row=".col-md-10" placeholder="SMTP Password" class="login-field  login-field-password form-control smtp_password disabled_input" @if(!empty($mailConfigaration))value="{{$mailConfigaration->smtp_password}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">SMTP Port</label>
                            <div class="col-lg-4 col-md-4">
                                <input name="smtp_port" data-fv-row=".col-md-4" placeholder="SMTP Port" class="form-control disabled_input" @if(!empty($mailConfigaration))value="{{$mailConfigaration->smtp_port}}"@else value="25"@endif kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                     data-fv-regexp-regexp="^[0-9+\s]+$"  data-fv-regexp-message="SMTP port can consist of number only">
                            </div>
                            <label class="col-lg-2 col-md-2 control-label required">SMTP Secure</label>
                            <div class="col-lg-4 col-md-4">
                                <select name="smtp_secure" data-fv-row=".col-md-4"  data-fv-icon="false" class="select2 form-control ml0 disabled_input">
                                    <option value="">None</option>
                                    <option value="tls" @if(@$mailConfigaration->smtp_secure=="tls"){{'selected'}}@endif>TLS</option>
                                    <option value="ssl" @if(@$mailConfigaration->smtp_secure=="ssl"){{'selected'}}@endif>SSL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2" style="margin-top: -14px;">
            <button type="submit" class="btn btn-default ml25">Update Mail Configaration</button>
            <button type="button" id="testConfig" class="btn btn-default ml25">Test Your Configaration</button>
            <button type="button" href="projectRegistration " menu-active="projectRegistration" class="ajax-link btn btn-default ml20 hand"><i class="s12 icomoon-icon-reply-2 mr5"></i>Back</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });

        var smtpUserName = document.getElementById('form_email');
        smtpUserName.onkeyup = function(){
            document.getElementById('smtp_user_name').value = smtpUserName.value;
        }

        $('.smtp_password').hidePassword(true);
            
        var  mailProtocalValue = $("#mail_protocal").val();
        if(mailProtocalValue==2){          
            $('.disabled_input').attr('disabled','disabled');
            $('.disabled_input').removeAttr('required', 'required');   
            $('.disabled_input').val('');
            $('.disabled_input').select2("val", "");
        }        

        $('#mail_protocal').on('change',function(){
            var  mailProtocal = $(this).val();
            if(mailProtocal == 1){           
                $('.disabled_input').removeAttr("disabled", "disabled");
                $('.disabled_input').attr("required", "required");
            }else{
                $('.disabled_input').attr('disabled', 'disabled');
                $('.disabled_input').removeAttr('required', 'required');  
                $('.disabled_input').val('');
                $('.disabled_input').select2("val", "");
            }
        });

        $("#testConfig").click(function(){
            var $that = $(this);
            preLoader($that);
            $.ajax({
                mimeType: 'text/html; charset=utf-8',
                url : appUrl.getSiteAction('/projectMailConfigurationTest'),
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    preLoaderHide($that);
                    if(data.msgType=='success') {
                        swal("Cancelled", 'Mial configuration is ready to sending email', "success");
                    } else {
                        swal("Cancelled", data.messege, "error");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    preLoaderHide($that);
                    swal("Cancelled", 'Mail configuration is not ok', "error");
                },
                async: false
            });
        });
    });
</script>