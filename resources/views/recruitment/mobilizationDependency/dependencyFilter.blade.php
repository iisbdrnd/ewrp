<select required name="dependency_id[]" data-fv-icon="false" class="select2 form-control ml0" id="dependency_id">
    <option value=""></option>
    @foreach($dependencySearch as $dependency)
    <option value="{{$dependency->id}}">{{$dependency->name}}</option>
    @endforeach
</select>