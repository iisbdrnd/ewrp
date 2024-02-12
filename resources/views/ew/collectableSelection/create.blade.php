<?php $panelTitle = "Collectable Selection Create"; ?>
<form type="create" id="expenseSelectionForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="form-group">
        <label for="project_id" class="col-lg-4 col-md-4 control-label required">Project</label>
        <div class="col-lg-8 col-md-8">
            <select required name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0">
                <option value=""></option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->project_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-4 col-md-4 control-label required">Collectable Account</label>
        <div class="col-lg-8 col-md-8">
            <select required name="collectable_account_id[]" data-fv-icon="false" class="select2 form-control ml0" multiple="multiple">
                <option value=""></option>
                @foreach($collectableAccountHeads as $collectableAccountHeads)
                <option value="{{$collectableAccountHeads->id}}">{{$collectableAccountHeads->account_head}}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });
</script>