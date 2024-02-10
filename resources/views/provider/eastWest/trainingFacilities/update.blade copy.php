<?php $panelTitle = "Company Policy Update"; ?>

<form type="update" panelTitle="{{$panelTitle}}"  action="{{route('provider.eastWest.provider.eastWest.trainingFacilities.update', [1])}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}

    @if ($trainingFacilities)
    @foreach ($trainingFacilities as $trainingFacility)
        <div class="form-group">
            <label class="col-lg-2 col-md-3 control-label required">{{$trainingFacility->title}}</label>
            <div class="col-lg-8 col-md-6">
                <input type="hidden" name="ids[]" value={{$trainingFacility->id}} />
                <input autofocus required name="description[]" placeholder="Title" value="{{$trainingFacility->description}}" class="form-control">
            </div>
        </div>
        {{-- <div class="form-group">
            <label class="col-lg-2 col-md-3 control-label required">Description</label>
            <div class="col-lg-8 col-md-6">
                <input autofocus value="{{$trainingFacility->total_area}}" name="total_area" placeholder="Total Area" class="form-control">
            </div>
        </div> --}}
    @endforeach
    @endif
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
    {{-- <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Total Area </label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->total_area}}" name="total_area" placeholder="Total Area" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Floors</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_floor}}" name="num_of_floor" placeholder="Number of Floors" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Interview Rooms</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_interview_room}}" name="num_of_interview_room" placeholder="Number of Interview Rooms" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Conference Rooms</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_conferance_room}}" name="num_of_conferance_room" placeholder="Number of Conference Rooms" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Rooms for Processing</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_room_for_processing}}" name="num_of_room_for_processing" placeholder="Number of Rooms for Processing" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Class Rooms</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_classroom}}" name="num_of_classroom" placeholder="Number of Class Rooms" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Teacher’s Rooms</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_teachers_room}}" name="num_of_teachers_room" placeholder="Number of Teacher’s Rooms" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Welding Booth</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_welding_booth}}" name="num_of_welding_booth" placeholder="Number of Welding Booth" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Pipe Fabrication Booth</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_pipe_fabrication_booth}}" name="num_of_pipe_fabrication_booth" placeholder="Number of Pipe Fabrication Booth" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Booth for Mason</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_booth_for_mason}}" name="num_of_booth_for_mason" placeholder="Number of Booth for Mason" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Number of Booth for Steel Fixers</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus value="{{$trainingFacility->num_of_booth_for_steel_filxers}}" name="num_of_booth_for_steel_filxers" placeholder="Number of Booth for Steel Fixers" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div> --}}
</form>

<script>
    $(document).ready(function() {    
        $("#description").summernote({
            height: 150
        });
        $("#contact_us").summernote({
            height: 100
        });
    });
</script>