<select required name="agency_id[]" data-fv-icon="false" class="select2 form-control ml0" id="agency_id">
    <option value=""></option>
    @foreach($agencySearch as $agency)
    <option value="{{$agency->id}}">{{$agency->agency_name}}</option>
    @endforeach
</select>