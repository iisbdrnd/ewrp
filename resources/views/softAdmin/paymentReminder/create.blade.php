<?php $panelTitle = "Client Payment Reminder"; ?>
@include("panelStart")
<form type="update" id="emailForm" action={{url('softAdmin/crmPaymentReminderAc')}} data-fv-excluded="" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="row mt15">
        <!--Left Side Box-->
        <div class="col-lg-12 col-md-12 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class="simple-chart">
                        <div class="form-group" style="font-size: 16px; text-align:center;">
                            <div class="">
                                <span class="ml15">General Reminder</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Subject</label>
                            <div class="col-lg-10 col-md-9">
                                <input autofocus required name="general_subject" placeholder="Subject" class="form-control" @if(!empty($crmPaymentReminder))value="{{$crmPaymentReminder->general_subject}}"@endif>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-2 col-md-3 control-label required">Description</label>

                            <div class="col-lg-10 col-md-9">
                                <span class="info">Note [{expire_date}, {expire_date}]</span>
                                <textarea required name="general_description" id="general_description">@if(!empty($crmPaymentReminder)){{$crmPaymentReminder->general_description}}@endif</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Before Expire Date</label>
                            <div class="col-lg-3 col-md-3">
                                <div class=input-group>
                                    <input required data-fv-icon="false" name="general_before_days" placeholder="Before Expire Date" class="form-control" @if(!empty($crmPaymentReminder))value="{{$crmPaymentReminder->general_before_days}}"@endif>
                                    <span class=input-group-addon>Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 sortable-layout" style="margin-bottom: -11px;">
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class="simple-chart">
                        <div class="form-group" style="font-size: 16px; text-align:center;">
                            <div class="">
                                <span class="ml15">Extreme Reminder</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Subject</label>
                            <div class="col-lg-10 col-md-9">
                                <input autofocus required name="extreme_subject" placeholder="Subject" class="form-control" @if(!empty($crmPaymentReminder))value="{{$crmPaymentReminder->extreme_subject}}"@endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Description</label>
                            <div class="col-lg-10 col-md-9">
                                <span class="info">Note [{expire_date}, {expire_date}]</span>
                                <textarea autofocus required name="extreme_description" id="extreme_description" placeholder="Description" class="form-control" rows=3>@if(!empty($crmPaymentReminder)){{$crmPaymentReminder->extreme_description}}@endif</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Before Expire Date</label>
                            <div class="col-lg-3 col-md-3">
                                <div class=input-group>
                                    <input required data-fv-icon="false" name="extreme_before_days" placeholder="Before Expire Date" class="form-control" @if(!empty($crmPaymentReminder))value="{{$crmPaymentReminder->extreme_before_days}}"@endif>
                                    <span class=input-group-addon>Days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2" style="margin-top: -14px;">
            <button type="submit" class="btn btn-default ml25">Update Payment Reminder</button>
        </div>
    </div>
</form>
@include("panelEnd")

<script type="text/javascript">
    $("#emailForm").find("#general_description").summernote({
        height: 200,
        placeholder: "Your text..."
    });
    $("#emailForm").find("#extreme_description").summernote({
        height: 200,
        placeholder: "Your text..."
    });

</script>