<form type="create" id="opportunityAddForm" action="{{url('crm/opportunityAdd')}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-4 col-md-3 control-label required">Opportunity Name</label>
        <div class="col-lg-8 col-md-9">
            <input required name="opportunity_name" placeholder="Opportunity Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-4 col-md-3 control-label required">Account</label>
        <div class="col-lg-8 col-md-9">
           <div id="account_src" class="row advance-search" search-url="account-search" search-title="Account Search">
               <div class="col-sm-9">
                    <select required name="account_id" data-fv-row=".col-lg-8" data-fv-icon="false" id="acc_id" class="select2 form-control adv-search">
                       <option value="">Select</option>
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
    <div class=form-group id="stage_id">
	    <label class="col-lg-4 col-md-3 control-label required">Stage</label>
	    <div class="col-lg-8 col-md-9">
	        <select required name="stage" id="stage" data-fv-icon="false" class="select2 form-control ml0">
	            <option value="">Select</option>
	            @foreach($crmOpportunityStage as $stage)
	            <option value="{{$stage->id}}">{{$stage->stage_name}}</option>
	            @endforeach
	        </select>
	    </div>
	</div>
	<div id="lost_reason_div"></div>
	<div class="form-group">
	    <label class="col-lg-4 col-md-3 control-label">Progress</label>
	    <div class="col-lg-3 col-md-3">
	        <div class=input-group>
	            <input name="probability" id="probability" class="form-control" readonly>
	            <span class=input-group-addon>%</span>
	        </div>
	    </div>
	</div>
	<div class=form-group>
	    <label class="col-lg-4 col-md-3 control-label" for="">Opportunities</label>
	    <div class="col-lg-8 col-md-9">
	        <div class=input-group>
	            <span class="input-group-addon">{{$currency->html_code}}</span> 
	            <input name="opportunity_amount"  placeholder="Amount" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
	             data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
	        </div>
	    </div>
	</div>
	<div class="form-group">
	    <label class="col-lg-4 col-md-3 control-label required">Assumption Closed Date</label>
	    <div class="col-lg-8 col-md-9">
	        <div class=input-group><span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="closed_date" name="closed_date" class="form-control" data-fv-trigger="blur"></div>
	    </div> 
	</div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        var input;
        $("#opportunityAddForm").find(".select2").select2({
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

        $("#opportunityAddForm").formValidation({
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            }
        })

        $("#stage").on('change', function(e) {
            e.preventDefault();
            var stage = $(this).val();
            if(stage==7) {
                if($("#lost_reason_div").find(".form-group").length <= 0) {
                    $("#lost_reason_div").html('<div class="form-group"><label class="col-lg-4 col-md-3 control-label required">Lost reason</label><div class="col-lg-8 col-md-9"><select required id="lost_reason_id" name="lost_reason_id" data-fv-icon="false" class="select2 form-control ml0"><option value="">Select</option>@foreach($lostReasons as $lost_reason)<option value="{{$lost_reason->id}}">{{$lost_reason->reason_name}}</option>@endforeach</select></div></div><div class="form-group" style="border-bottom: 1px solid #dedede;"><label class="col-lg-4 col-md-3 control-label">Reason Details</label><div class="col-lg-8 col-md-9"><textarea class="form-control" id="lost_reason_details" name="lost_reason_details" row="3"></textarea></div></div>');
                    $('#opportunityAddForm').formValidation('addField', $("#lost_reason_id"));
                    $('#opportunityAddForm #lost_reason_id').select2();
                }
            } else {
                if($("#lost_reason_div").find(".form-group").length > 0) {
                    var $lost_reason_id = $("#lost_reason_id");
                    $("#lost_reason_div").html("");
                    $('#opportunityAddForm').formValidation('removeField', $lost_reason_id);
                }
            }
        });
    });
</script>