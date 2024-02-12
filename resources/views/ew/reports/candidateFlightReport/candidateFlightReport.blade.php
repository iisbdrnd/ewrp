<?php $panelTitle = "Ledger Query"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="candidateFlightReportForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-3 col-md-3 control-label required">By Project</label>
                <div class="col-lg-6 col-md-6">
                    <select name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0" required>
                        <option value=0>All Project</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 col-md-3 control-label required">By Reference</label>
                <div class="col-lg-6 col-md-6">
                    <div id="project_reference_id">
                        <select name="reference_id" id="reference_id" data-fv-icon="false" class="select2 form-control ml0">
                            <option value=0>All Reference</option>
                            @foreach($references as $reference)
                            <option value="{{$reference->id}}">{{$reference->reference_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-3 col-md-3">
                    <div class="checkbox-custom pull-right">
                        <input id="date_range" name="date_range" type="checkbox">
                        <label for="date_range">Date Range:</label>
                    </div>
                </div>
                <div id="date_fields" style="display: none;">
                    <div class="col-lg-3 col-md-3">
                        <div class="input-group">
                            <input id="from_date" name="from_date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="input-group">
                            <input id="to_date" name="to_date" placeholder="To: dd/mm/yyyy" class="form-control dtpicker">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-3 col-md-8 btn-pb15">
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
        $("#reference_id").select2({
            placeholder: "Select"
        });

        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});

        $("#date_range").on('change', function() {
            if ($(this).is(":checked")) {
                $("#date_fields").show();
                $(".dtpicker").attr('required', 'required');
                $('#candidateFlightReportForm').formValidation('addField', $(".dtpicker"));
                date_range = 1;
            } else {
                $("#date_fields").hide();
                $(".dtpicker").removeAttr('required');
                $('#candidateFlightReportForm').formValidation('removeField', $(".dtpicker"));
                date_range = 0;
            }
        });
        $("#candidateFlightReportForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var project = $('#project_id').val();
            var reference_id = $('#reference_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.candidateFlightReportData')}}"+'?project='+project+ '&date_range='+date_range+ '&from_date='+from_date+ '&to_date='+to_date+ '&reference_id='+reference_id;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });

        //Project Wise Reference
        $("#project_id").on('change', function(e) {
            var project_id = $(this).val();

            if (project_id) {
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectReferences")}}',
                    data: {project_id:project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $("#project_reference_id").html(data);
                        $('#amountReceiveForm').formValidation('addField', $('#reference_id'));


                    }
                });
            }
        });
    });
</script>