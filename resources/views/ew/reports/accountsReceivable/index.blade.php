<?php $panelTitle = "Accounts Receivable"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="accountsReceivableForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">By Project</label>
                <div class="col-lg-4 col-md-4">
                    <select name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value=''>All Project</option>
                        <option value='0'>Head Office</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4">
                    <div class="checkbox-custom pull-right">
                        <input id="date_range" name="date_range" type="checkbox">
                        <label for="date_range">Date:</label>
                    </div>
                </div>
                <div id="date_fields" style="display: none;">
                    <div class="col-lg-4 col-md-4">
                        <div class="input-group">
                            <input id="date" name="date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker" required data-fv-trigger="blur">
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
        $('#accountsReceivableForm').formValidation('removeField', $(".dtpicker"));

        $("#date_range").on('change', function() {
            if ($(this).is(":checked")) {
                $("#date_fields").show();
                $(".dtpicker").attr('required', 'required');
                $('#accountsReceivableForm').formValidation('addField', $(".dtpicker"));
                date_range = 1;
            } else {
                $("#date_fields").hide();
                $(".dtpicker").removeAttr('required');
                $('#accountsReceivableForm').formValidation('removeField', $(".dtpicker"));
                date_range = 0;
            }
            $(".dtpicker").val();
        });
        $("#accountsReceivableForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var project = $('#project_id').val();
            var date = $('#date').val();
            //console.log(postData);
            var width = $(document).width();
            var height = $(document).height();
            var previewType = 1;
            var url = "{{route('ew.accountsReceivableReport')}}"+'?project='+project+ '&date_range='+date_range+ '&date='+date;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
