<?php $panelTitle = "Counter Update"; ?>
@include("panelStart")
<form type="update" action="{{route('provider.eastWest.provider.eastWest.counter.update', 0)}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    @foreach($counters as $key=>$counter)
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">{{$counter->title}}</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="counter[]" placeholder="{{$counter->title}}" value="{{$counter->counter}}" class="form-control">
            <input type="hidden" name="ids[]" value="{{$counter->id}}">
        </div>
    </div>
    @endforeach

    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {    
        $("#description").summernote({
            height: 150
        });
    });
</script>

@include("panelEnd")