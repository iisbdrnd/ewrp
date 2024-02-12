<?php $panelTitle = "Update Aviation"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 sortable-layout">
            <div class="form-group">
                <label for="company_name" class="col-lg-3 col-md-3 control-label required">Company Name</label>
                <div class="col-lg-8 col-md-8">
                    <input required id="company_name" name="company_name" placeholder="e.g. Royal Bengal Airline" class="form-control" value="{{$aviationDetails->company_name}}">
                </div>
            </div>
            <div class="form-group">
                <label for="account_id" class="col-lg-3 col-md-3 control-label required">Account Name</label>
                <div class="col-lg-8 col-md-8">
                    <select readonly required id="account_id" name="account_name" data-fv-icon="false" class="select2 form-control ml0 autoRemarks" style="width:100%;">
                      <option value=""></option>
                      @foreach($accountLevelOfFour as $levelThreeKey=>$accLevelOfFour)
                        @if (isset($accountLevelOfThree[$levelThreeKey]))
                        <!-- <optgroup label="{{$accountLevelOfThree[$levelThreeKey]->account_code.' - '.$accountLevelOfThree[$levelThreeKey]->account_head}}"> -->
                          @foreach($accLevelOfFour as $accLevelOfFour)
                            <option value="{{$accLevelOfFour->account_head}}" @if($accLevelOfFour->account_head==$aviationDetails->account_name){{'selected'}}@endif> {{$accLevelOfFour->account_code.' - '.$accLevelOfFour->account_head}}</option>
                          @endforeach
                        <!-- </optgroup> -->
                        @endif
                      @endforeach
                    </select>
                </div>                
            </div>
            <div class="form-group">
                <label for="account_code" class="col-lg-3 col-md-3 control-label required">Account Code</label>
                <div class="col-lg-8 col-md-8">
                    <input readonly required id="account_code" name="account_code" placeholder="e.g. 0000000000" class="form-control" value="{{$aviationDetails->account_code}}">
                </div>
            </div>
            <div class="form-group">
                <label for="address" class="col-lg-3 col-md-3 control-label required">Address Name</label>
                <div class="col-lg-8 col-md-8">
                    <input required id="address" name="address" placeholder="e.g. Main Road, Mohammadpur, Dhaka, Bangladesh" class="form-control" value="{{$aviationDetails->address}}">
                </div>
            </div>
            <div class="form-group">
                <label for="contact_person" class="col-lg-3 col-md-3 control-label required">Address Name</label>
                <div class="col-lg-8 col-md-8">
                    <input required id="contact_person" name="contact_person" placeholder="Contact Person Name" class="form-control" value="{{$aviationDetails->contact_person}}">
                </div>
            </div>
            <div class="form-group">
                <label for="contact_no" class="col-lg-3 col-md-3 control-label required">Address Name</label>
                <div class="col-lg-8 col-md-8">
                    <input required id="contact_no" name="contact_no" placeholder="e.g. 01729-346959" class="form-control" value="{{$aviationDetails->contact_no}}">
                </div>
            </div>
            <div class="form-group mb0" >
                <div class="col-lg-offset-3 col-md-offset-3 col-lg-9 col-md-9">
                    <button type="submit" class="btn btn-default">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
       $(".select2").select2({ placeholder: "Select" });
       $("#account_id").change(function(){
            var account_head = $(this).val();
            if (account_head) {
                $.ajax({
                    url:"{{route('ew.accountCode')}}",
                    type:"GET",
                    dataType: "json",
                    data: {accountId:account_head},
                    success: function(data){
                        $("#dis_account_code").val(data.account_code);
                        $("#account_code").val(data.account_code);
                    }
                });
            }
       }); 
    });
</script>