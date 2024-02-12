<?php $panelTitle = "Account Configuration"; ?>
@include("panelStart")
<form id="accountConfigurationForm" type="update" action="{{route('ew.account_configuration')}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal mt0" data-fv-excluded="">
    {{csrf_field()}}
    @foreach($accountConfigurationCodes as $accountConfigurationCode)
    <div class="row">
        @foreach($accountConfigurationCode as $accConfigurationCode)
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 col-xs-12 control-label required">{{$accConfigurationCode->particular_name}}</label>
                <div class="col-lg-8 col-md-8 col-xs-12">
                    <input name="{{$accConfigurationCode->particular}}" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s]+$" data-fv-regexp-message="Account configuration code can consist of number only" value="{{$accConfigurationCode->account_code}}">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="form-group mb0">
                <div class="col-lg-offset-4">
                    <button type="submit" class="btn btn-default ml10">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@include("panelEnd")