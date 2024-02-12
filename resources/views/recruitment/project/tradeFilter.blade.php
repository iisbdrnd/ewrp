<select required name="trade_id[]" data-fv-icon="false" class="select2 form-control ml0" id="trade_id">
    <option value=""></option>
    @foreach($tradeSearch as $trade)
    <option value="{{$trade->id}}">{{$trade->trade_name}}</option>
    @endforeach
</select>