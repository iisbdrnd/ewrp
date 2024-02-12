<?php $panelTitle = "Reference Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
  {{csrf_field()}}
  <div class="form-group">
    <label class="col-lg-3 col-md-3 control-label required">Reference Name</label>
    <div class="col-lg-8 col-md-8">
      <input autofocus required name="reference_name" placeholder="Reference Name" class="form-control" value="{{$ewReferences
            ->reference_name}}">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 control-label required">Reference Phone</label>
    <div class="col-lg-8 col-md-8">
      <input autofocus required id="reference_phone" name="reference_phone" placeholder="e.g.016XXXXXXXX" class="form-control"
        kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s]+$"
        data-fv-regexp-message="Reference phone can consist of number only" value="{{$ewReferences->reference_phone}}">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 control-label">Reference Email</label>
    <div class="col-lg-8 col-md-8">
      <input type="email" autofocus name="reference_email" placeholder="Reference Email" class="form-control" value="{{ $ewReferences->reference_email }}">
    </div>
  </div>
  <div class="form-group">
    <label class="col-lg-3 col-md-3 control-label">Reference Address</label>
    <div class="col-lg-8 col-md-8">
      <textarea autofocus name="reference_address" placeholder="Reference Address" class="form-control">{{$ewReferences->reference_address}}</textarea>
    </div>
  </div>
  <div class="form-group">
      <label class="col-lg-3 col-md-3 control-label required">Select Dealer</label>
      <div class="col-lg-8 col-md-8">
        {{-- <select name="dealer[]" id="" class="form-control select2" required multiple placeholder="Select Delear">
          <option>Select Dealer</option>
          @foreach($dealers as $index => $dealerId)
          <option {{ @$dealerArrId[$dealerId->id] == $dealerId->id?'selected':''}} value="{{ $dealerId->id }}">{{
            $dealerId->name }}</option>
          @endforeach
        </select> --}}
        <select name="dealer" id="" class="form-control select2" required placeholder="Select Delear">
            <option>Select Dealer</option>
            @foreach($dealers as $dealer)
                <option value="{{ $dealer->id }}" @if($ewReferences->dealer == $dealer->id) selected @endif>{{ $dealer->name }}</option>
            @endforeach
        </select>
      </div>
    </div>
</form>
<script type="text/javascript">
  $("select.select2").select2({
    placeholder: "Select"
  });
</script>