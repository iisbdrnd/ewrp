<select required data-fv-icon="false" name="report_to" id="report_to" class="form-control">
    <option value="0">N/A</option>
    @foreach($report_to as $report_to)
        <option value="{{$report_to->id}}">{{$report_to->name}}</option>
    @endforeach
</select>