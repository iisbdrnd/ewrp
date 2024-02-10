<?php $panelTitle = "Terms and condition Update";?>
<form type="update" action="{{route('provider.eastWest.provider.eastWest.termsAndCondition.update', [$conditions->id])}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Terms And Condition</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="terms_and_condition" name="terms_and_condition" rows="5" class="form-control">{!!    $conditions->terms_and_condition !!}</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {    
        $("#terms_and_condition").summernote({
            height: 150
        });
    });
</script>