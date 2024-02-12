<?php $panelTitle = "Collectable Income"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="collectableIncomeForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">By Project</label>
                <div class="col-lg-4 col-md-4">
                    <select name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value=''>All Project</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Report View:</label>
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom radio-inline">
                        <input name="group_report" value="0" id="none_group" type="radio" checked>
                        <label for="none_group">All</label>
                    </div>
                    <div class="radio-custom radio-inline">
                        <input name="group_report" value="1" id="group_project" type="radio">
                        <label for="group_project">Group wise (Project)</label>
                    </div>
               </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Date Range:</label>
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom radio-inline">
                        <input name="date_range" value="1" id="upto_date" type="radio" checked>
                        <label for="upto_date">Upto date</label>
                    </div>
                    <div class="radio-custom radio-inline">
                        <input name="date_range" value="2" id="select_range" type="radio">
                        <label for="select_range">Select range</label>
                    </div>
               </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label"></label>
                <div id="upto_date_fields">
                    <div class="col-lg-4 col-md-4">
                        <div class="input-group">
                            <input id="date" name="date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker" required data-fv-trigger="blur" data-fv-row=".col-md-4">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div id="date_fields" style="display: none;">
                    <div class="col-lg-2 col-md-2">
                        <div class="input-group">
                            <input id="from_date" name="from_date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-2">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="input-group">
                            <input id="to_date" name="to_date" placeholder="To: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-2">
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
        date_range = 0;
        $(".select2").select2({
            placeholder: "Select"
        });
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});

        $("input[name=date_range]").on('change', function() {
            var date_range = $(this).val();
            if (date_range==1) {
                if($("#date_fields").is(':visible')) {
                    $("#from_date").removeAttr('required');
                    $("#to_date").removeAttr('required');
                    $('#collectableIncomeForm').formValidation('removeField', $("#from_date"));
                    $('#collectableIncomeForm').formValidation('removeField', $("#to_date"));
                    $("#date_fields").hide();
                }
                $("#upto_date_fields").show();
                $("#date").attr('required', 'required');
                $('#collectableIncomeForm').formValidation('addField', $("#date"));
            } else if (date_range==2) {
                if($("#upto_date_fields").is(':visible')) {
                    $("#date").removeAttr('required');
                    $('#collectableIncomeForm').formValidation('removeField', $("#date"));
                    $("#upto_date_fields").hide();
                }
                $("#date_fields").show();
                $("#from_date").attr('required', 'required');
                $("#to_date").attr('required', 'required');
                $('#collectableIncomeForm').formValidation('addField', $("#from_date"));
                $('#collectableIncomeForm').formValidation('addField', $("#to_date"));
            } else {
                if($("#upto_date_fields").is(':visible')) {
                    $("#date").removeAttr('required');
                    $('#collectableIncomeForm').formValidation('removeField', $("#date"));
                    $("#upto_date_fields").hide();
                }
                if($("#date_fields").is(':visible')) {
                    $("#from_date").removeAttr('required');
                    $("#to_date").removeAttr('required');
                    $('#collectableIncomeForm').formValidation('removeField', $("#from_date"));
                    $('#collectableIncomeForm').formValidation('removeField', $("#to_date"));
                    $("#date_fields").hide();
                }
            }
        });

        $("#collectableIncomeForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var project = $('#project_id').val();
            var group_report = $("input[name=group_report]:checked").val();
            var date_range = $("input[name=date_range]:checked").val();
            var dateParameter = '';
            if(date_range==1) {
                dateParameter += '&date='+$('#date').val();
            } else if(date_range==2) {
                dateParameter += '&from_date='+$('#from_date').val()+'&to_date='+$('#to_date').val();
            }

            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.collectableIncomeReport')}}"+'?project='+project+'&group_report='+group_report+'&date_range='+date_range + dateParameter;

            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
