<select id="by_dealer_id" name="by_dealer_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
    <option value="0">All Dealer</option>
    @foreach($dealers as $dealer)
    <option value="{{$dealer->id}}">{{$dealer->name}}</option>
    @endforeach
</select>
