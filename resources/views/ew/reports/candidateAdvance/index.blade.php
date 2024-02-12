<?php $panelTitle = "Candidate Advance"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="referenceLedgerForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Project</label>
                <div class="col-lg-4 col-md-4">
                    <select id="ew_project_id" name="project_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value="">All Projects</option>
                        @foreach($ewProjects as $ewProjects)
                        <option value="{{$ewProjects->id}}">{{$ewProjects->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Reference</label>
                <div class="col-lg-4 col-md-4">
                  <select id="reference_id" name="reference_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value="">All Reference</option>
                        @foreach($ewReferences as $ewReferences)
                        <option value="{{$ewReferences->id}}">{{$ewReferences->reference_name}}</option>
                        @endforeach
                    </select>
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

        $("#referenceLedgerForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var projectId = $('#ew_project_id').val();
            var referenceId = $('#reference_id').val();
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.candidateAdvanceData')}}"+'?projectId='+projectId+'&referenceId='+referenceId;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
