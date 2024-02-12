<?php $panelTitle = "Trial Balance"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="trialBalanceForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">By Project:</label>
                <div class="col-lg-4 col-md-4">
                    <select name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value="0">All Project</option>
                        {{-- <option value="0">Head Office</option> --}}
                        @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Account Level:</label>
                <div class="col-lg-4 col-md-4">
                    <select name="account_level" id="account_level" data-fv-icon="false" class="select2 form-control ml0">
                        <option value="1">1st Level</option>
                        <option value="2">2nd Level</option>
                        <option value="3">3rd Level</option>
                        <option value="4">4th Level</option>
                    </select>
                </div>
            </div> --}}
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Date Range:</label>
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom radio-inline">
                        <input name="date_range" value="0" id="upto_date" type="radio" checked>
                        <label for="upto_date">Upto date</label>
                    </div>
                    <div class="radio-custom radio-inline">
                        <input name="date_range" value="1" id="select_range" type="radio">
                        <label for="select_range">Select range</label>
                    </div>
               </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label"></label>
                <div id="date_fields" style="display: none;">
                    <div class="col-lg-2 col-md-2">
                        <div class="input-group">
                            <input id="from_date" name="from_date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" required data-fv-row=".col-md-2">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="input-group">
                            <input id="to_date" name="to_date" placeholder="To: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" required data-fv-row=".col-md-2">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-4 col-md-8 btn-pb15">
            <button id="preview_button" type="submit" class="btn btn-default">Preview</button>
        </div>
    </form>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
        $('#trialBalanceForm').formValidation('removeField', $("#from_date"));
        $('#trialBalanceForm').formValidation('removeField', $("#to_date"));

        $("input[name=date_range]").on('change', function() {
            date_range = $(this).val();
            if (date_range==1) {
                $("#date_fields").show();
                $(".dtpicker").attr('required', 'required');
                $('#trialBalanceForm').formValidation('addField', $("#from_date"));
                $('#trialBalanceForm').formValidation('addField', $("#to_date"));
            } else {
                $("#date_fields").hide();
                $(".dtpicker").removeAttr('required');
                $('#trialBalanceForm').formValidation('removeField', $("#from_date"));
                $('#trialBalanceForm').formValidation('removeField', $("#to_date"));
            }
        });
        $("#trialBalanceForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var project = $('#project_id').val();
            var account_level = $('#account_level').val();
            var date_range = $("#select_range").is(":checked")?1:0;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            //console.log(postData);
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.cash-bank-position-report')}}"+'?project='+project+ '&account_level='+account_level+ '&date_range='+date_range+ '&from_date='+from_date+ '&to_date='+to_date;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>