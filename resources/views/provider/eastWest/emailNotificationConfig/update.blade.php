<?php $panelTitle = "Email Notification Configuration"; ?>
@include("panelStart")
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class="simple-chart">
                        <div class="form-group" style="font-size: 16px;text-align: center;">
                            <span>Course Completion Notification</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="col-lg-4 col-md-3 control-label required">Notification send when course duration passed</label>
                            <div class="col-lg-8 col-md-9">
                                <div class="input-group">
                                    <input data-fv-icon="false" name="course_completion_notification_passed_duration" required placeholder="Ex: 25" class="form-control" type="number" value="{{@$mailNotifyConfig->course_completion_notification_passed_duration}}" data-fv-greaterthan="true" data-fv-greaterthan-value="1" data-fv-lessthan="true" data-fv-lessthan-value="100">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="col-lg-4 col-md-3 control-label required">Course effort lagging behind (less than)</label>
                            <div class="col-lg-8 col-md-9">
                                <div class="input-group">
                                    <input data-fv-icon="false" name="course_completed_less_than" required placeholder="Ex: 50" class="form-control" type="number" value="{{@$mailNotifyConfig->course_completed_less_than}}" data-fv-greaterthan="true" data-fv-greaterthan-value="1" data-fv-lessthan="true" data-fv-lessthan-value="100">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
        <div class="col-lg-12 col-md-12 sortable-layout">
            <div class="panel panel-default chart mb10">
                <div class="panel-body pt0 pb0">
                    <div class="simple-chart">
                        <div class="form-group" style="font-size: 16px;text-align: center;">
                            <span>Enrollment Pending Notification</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="col-lg-4 col-md-3 control-label required">Notification send when course duration passed</label>
                            <div class="col-lg-8 col-md-9">
                                <div class="input-group">
                                    <input data-fv-icon="false" name="course_enrollment_notification_passed_duration" required placeholder="Ex: 25" class="form-control" type="number" value="{{@$mailNotifyConfig->course_enrollment_notification_passed_duration}}" data-fv-greaterthan="true" data-fv-greaterthan-value="1" data-fv-lessthan="true" data-fv-lessthan-value="100">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-4">
            <button type="submit" class="btn btn-default ml20">Update Configuration</button>
        </div>
    </div>
</form>
@include("panelEnd")