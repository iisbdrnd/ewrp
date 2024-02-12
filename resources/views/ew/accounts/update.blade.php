<?php $panelTitle = "Update Account"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 sortable-layout">
           <div class="panel panel-default chart mb15">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
					    <div class="form-group">
					        <label for="main_code" class="col-lg-3 col-md-3 control-label required">Main</label>
					        <div class="col-lg-4 col-md-4">
					            <input readonly type="text" autofocus required id="main_code" name="main_code" min="0" max="4" class="form-control" data-fv-icon="false" value="{{$accountDetails->main_code}}">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="mainClass">Main Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="classified_code" class="col-lg-3 col-md-3 control-label required">General</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="text" readonly required id="classified_code" name="classified_code" min="0" max="9" class="form-control" data-fv-icon="false" value="{{$accountDetails->classified_code}}">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="classifiedClass">General Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="control_code" class="col-lg-3 col-md-3 control-label required">Control</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="text" readonly required id="control_code" name="control_code" min="0" max="99" class="form-control" data-fv-icon="false" value="{{$accountDetails->control_code}}">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="controlClass">Control Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="subsidiary_code" class="col-lg-3 col-md-3 control-label required">Subsidiary</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="text" readonly required id="subsidiary_code" name="subsidiary_code" class="form-control" data-fv-icon="false" value="{{$accountDetails->subsidiary_code}}">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="subsidiaryClass">Subsidiary Classification</span></label>
					    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 sortable-layout">
           <div class="panel panel-default chart mb0">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                    	<div class="form-group">
					        <label for="account_code" class="col-lg-3 col-md-3 control-label required">Account Code</label>
					        <div class="col-lg-8 col-md-8">
					            <input readonly required id="account_code" name="account_code" placeholder="0000000000" class="form-control" value="{{$accountDetails->account_code}}">
					        </div>
					    </div>
					    <div class="form-group">
					        <label for="account_head" class="col-lg-3 col-md-3 control-label required">Account Head</label>
					        <div class="col-lg-8 col-md-8">
					            <input required id="account_head" name="account_head" placeholder="Account Head" class="form-control" value="{{$accountDetails->account_head}}">
					        </div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>