<?php if(empty($perPageArray)) { $perPageArray = array(10, 25, 50, 100); } ?>
<div class="dataTables_length">
    <label>
        <span>
            <select name="basic-datatables_length" class="form-control input-sm" id="perPage">
                @foreach($perPageArray as $perPageVal)
                <option @if(!empty($perPage) && ($perPage==$perPageVal)) {{'selected'}} @endif value="{{$perPageVal}}">{{$perPageVal}}</option>
                @endforeach
            </select>
        </span>
    </label>
</div>