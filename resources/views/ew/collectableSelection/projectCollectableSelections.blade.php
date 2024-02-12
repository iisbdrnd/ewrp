@foreach($projectCollectableSelections as $projectCollectableSelection)
    <div class="form-group">
        @foreach($projectCollectableSelection as $projectCollectableSelec)
        <label class="col-lg-2 col-md-2 col-xs-3 control-label required">{{$projectCollectableSelec->account_head}}</label>
        <div class="col-lg-4 col-md-4 col-xs-6">
        	<input type="hidden" name="collectable_head_id[]" value='{{$projectCollectableSelec->id}}'>
            <input required type="text" name="collectable_head_amount[]"  placeholder="0" class="form-control collectable_selectors" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only"  value='' data-fv-row=".col-lg-4" autocomplete="off">
        </div>
        @endforeach
    </div>
@endforeach
