<?php $panelTitle = "Opportunity Update"; ?>
<form type="update" id="opportunityUpdateForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="row mt15">
        <!--Left Side Box-->
        <div class="col-lg-6 col-md-12 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label required">Opportunity Name</label>
                            <div class="col-lg-8 col-md-9">
                                <input required name="opportunity_name" placeholder="Opportunity Name" class="form-control" value="{{$oppUpdateInfo->opportunity_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label required">Account</label>
                            <div class="col-lg-8 col-md-9">
                               <div id="account_src" class="row advance-search" search-url="account-search" search-title="Account Search">
                                   <div class="col-sm-9">
                                        <select required name="account_id" data-fv-row=".col-md-5" data-fv-icon="false" id="acc_id" class="select2 form-control adv-search">
                                            @foreach($accountNames as $accountName)
                                            <option value="{{$accountName->id}}" @if(@$accountName->id==$oppUpdateInfo->account_id){{'selected'}}@endif>{{$accountName->account_name}}</option>
                                            @endforeach
                                        </select>
                                   </div>
                                   <div class="col-sm-1 pl0">
                                       <button class="btn btn-default adv-btn" type="button">
                                           <i class="fa fa-search"></i>
                                       </button>
                                   </div>
                                   <div class="col-sm-1">
                                       <button class="btn btn-default adv-reset" type="button">
                                           <i class="fa fa-times"></i>
                                       </button>
                                   </div>
                               </div>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Type</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="opportunity_type" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value="">Select</option>
                                    @foreach($opportunityTypes as $opportunityType)
                                    <option value="{{$opportunityType->id}}" @if(@$opportunityType->id==$oppUpdateInfo->opportunity_type){{'selected'}}@endif>{{$opportunityType->status_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
						<div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Opportunity Source</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="opportunity_source" data-fv-icon="false" add-url="leadSourceAdd" class="@if($sourceAddAccess){{'select2-add'}}@endif select2 form-control ml0">
                                    <option value="">Select</option>
									@foreach($crmLeadSources as $crmLeadSource)
									<option value="{{$crmLeadSource->id}}" @if(@$crmLeadSource->id==$oppUpdateInfo->opportunity_source){{'selected'}}@endif>{{$crmLeadSource->source_name}}</option>
									@endforeach
                                </select>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Campaign Source</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="campaign_source" data-fv-icon="false" add-url="campaignAdd" class="select2 @if($campaignAddAccess){{'select2-add'}}@endif form-control ml0" add-modal-size="large">
                                    <option value="">Select</option>
                                    @foreach($campaignNames as $campaignName)
                                    <option value="{{$campaignName->id}}" @if(@$campaignName->id==$oppUpdateInfo->campaign_source){{'selected'}}@endif>{{$campaignName->campaign_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-4 col-md-offset-3 col-lg-8 col-md-9">
                                <div class="checkbox-custom checkbox-inline">
                                    <input type="checkbox" name="budget_confirmed" id="budget_confirmed" @if($oppUpdateInfo->budget_confirmed==1){{"checked"}}@endif value=1>
                                    <label for="budget_confirmed">Do you know about Budget of Customer</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-4 col-md-offset-3 col-lg-8 col-md-9">
                                <div class="checkbox-custom checkbox-inline">
                                    <input type="checkbox" name="analysis_completed" id="analysis_completed" @if($oppUpdateInfo->analysis_completed==1){{"checked"}}@endif value=1>
                                    <label for="analysis_completed">Return of Investment analysis completed</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 sortable-layout"><!--Right Side Box-1-->
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Status</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="status" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value="">Select</option>
                                    @foreach($opportunityStatus as $opportunityStatus)
                                    <option value="{{$opportunityStatus->id}}" @if(@$opportunityStatus->id==$oppUpdateInfo->status){{'selected'}}@endif>{{$opportunityStatus->status_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Category</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="category" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value="">Select</option>
                                    @foreach($crmOpportunityCategories as $crmOpportunityCategory)
                                    <option value="{{$crmOpportunityCategory->id}}"@if(@$crmOpportunityCategory->id==$oppUpdateInfo->category){{'selected'}}@endif>{{$crmOpportunityCategory->status_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label required">Stage</label>
                            <div class="col-lg-8 col-md-9">
                                <select required name="stage" id="stage" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value="">Select</option>
                                    @foreach($crmOpportunityStage as $stage)
                                    <option value="{{$stage->id}}"@if(@$stage->id==$oppUpdateInfo->stage){{'selected'}}@endif>{{$stage->stage_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="lost_reason_div"></div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label">Progress</label>
                            <div class="col-lg-3 col-md-3">
                                <div class=input-group>
                                    <input name="probability" id="probability" class="form-control" readonly value="{{$oppUpdateInfo->probability}}">
                                    <span class=input-group-addon>%</span>
                                </div>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label" for="">Opportunities</label>
                            <div class="col-lg-8 col-md-9">
                                <div class=input-group>
                                    <span class="input-group-addon" name="currency_id">{{@$currency->html_code}}</span> 
                                    <input name="opportunity_amount"  placeholder="Amount" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                     data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only" value="{{$oppUpdateInfo->opportunity_amount}}">
                                </div>
                            </div>
                        </div>
                        <?php
                            $closedDate = DateTime::createFromFormat('Y-m-d', $oppUpdateInfo->closed_date);
                            $closed_date = $closedDate->format('d/m/Y');
                         ?>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label required">Assumption Closed Date</label>
                            <div class="col-lg-8 col-md-9">
                                <div class=input-group><span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="closed_date" name="closed_date" class="form-control" value="{{$closed_date}}" data-fv-trigger="blur"></div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label">Owner</label>
                            <div class="col-lg-8 col-md-9">
                                <select name="assign_to" class="select2 form-control ml0" disabled>
                                    <option value="{{Auth::user()->get()->id}}">{{Auth::user()->get()->name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        var input;
        $(".select2").select2({
            placeholder: "Select"
        });
        $('#closed_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        var stage_probability = <?php echo json_encode($crmOpportunityStage); ?>;

        $('#stage').on('change', function() {
            var stage_probability_id = $(this).val();
            var stageProDetails = stage_probability[stage_probability_id];
            
            if(stage_probability_id){
                $("#probability").val(stageProDetails.percentage);
            } else {
                $("#probability").val('');
            }
        });
        
        $("#account_src").attr('search-url', 'account-search').attr('search-title', 'Account search');
        
        var stage = $("#stage").val();
        if(stage==7) {
            if($("#lost_reason_div").find(".form-group").length <= 0) {
                $("#lost_reason_div").html('<div class="form-group"><label class="col-lg-4 col-md-3 control-label required">Lost reason</label><div class="col-lg-8 col-md-9"><select required id="lost_reason_id" name="lost_reason_id" data-fv-icon="false" class="select2 form-control ml0"><option value="">Select</option>@foreach($lostReasons as $lost_reason)<option value="{{$lost_reason->id}}" @if(@$lost_reason->id==@$oppUpdateInfo->lost_reason_id){{'selected'}}@endif>{{$lost_reason->reason_name}}</option>@endforeach</select></div></div><div class="form-group" style="border-bottom: 1px solid #dedede;"><label class="col-lg-4 col-md-3 control-label">Reason Details</label><div class="col-lg-8 col-md-9"><textarea class="form-control" id="lost_reason_details" name="lost_reason_details" row="3">{{$oppUpdateInfo->lost_reason_details}}</textarea></div></div>');
                $('#opportunityUpdateForm').formValidation('addField', $("#lost_reason_id"));
                $('#opportunityUpdateForm #lost_reason_id').select2();
            }
        }

        $("#stage").on('change', function(e) {
            e.preventDefault();
            var stage = $(this).val();
            if(stage==7) {
                if($("#lost_reason_div").find(".form-group").length <= 0) {
                    $("#lost_reason_div").html('<div class="form-group"><label class="col-lg-4 col-md-3 control-label required">Lost reason</label><div class="col-lg-8 col-md-9"><select required id="lost_reason_id" name="lost_reason_id" data-fv-icon="false" class="select2 form-control ml0"><option value="">Select</option>@foreach($lostReasons as $lost_reason)<option value="{{$lost_reason->id}}" @if(@$lost_reason->id==@$oppUpdateInfo->lost_reason_id){{'selected'}}@endif>{{$lost_reason->reason_name}}</option>@endforeach</select></div></div><div class="form-group" style="border-bottom: 1px solid #dedede;"><label class="col-lg-4 col-md-3 control-label">Reason Details</label><div class="col-lg-8 col-md-9"><textarea class="form-control" id="lost_reason_details" name="lost_reason_details" row="3">{{$oppUpdateInfo->lost_reason_details}}</textarea></div></div>');
                    $('#opportunityUpdateForm').formValidation('addField', $("#lost_reason_id"));
                    $('#opportunityUpdateForm #lost_reason_id').select2();
                }
            } else {
                if($("#lost_reason_div").find(".form-group").length > 0) {
                    var $lost_reason_id = $("#lost_reason_id");
                    $("#crm_package_id option:selected").removeAttr("selected");
                    $("#lost_reason_details").val('');
                    $("#lost_reason_div").html("");
                    $('#opportunityUpdateForm').formValidation('removeField', $lost_reason_id);
                }
            }
        });
    });
</script>