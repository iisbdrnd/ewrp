<select required name="mobilize_id[]" data-fv-icon="false" class="select2 form-control ml0" id="mobilize_id">
    <option value=""></option>
    @foreach($mobilizeSearch as $mobilize)
    <option value="{{$mobilize->id}}">{{$mobilize->name}}</option>
    @endforeach
</select>