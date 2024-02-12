<select required id="account_head" name="account_head" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
    <option value=""></option>
    @foreach($collectableAccountHeads as $collectableAccountHeads)
    <option value="{{$collectableAccountHeads->id}}">{{$collectableAccountHeads->account_head}}</option>
    @endforeach
</select>
