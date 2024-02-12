<select required data-fv-icon="false" name="designation" id="designation" class="form-control">
    <option value="">Select Designation</option>
    @foreach($empDesignation as $designation)
        <option value="{{$designation->id}}">{{$designation->name}}</option>
    @endforeach
</select>