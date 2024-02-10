<?php $panelTitle = "Customer Create"; ?>

{{-- <style>
    .vew_scrl{
      width: 240px;
      margin-top: 10px;
    }
    .cropit-preview {
      background-color: #f8f8f8;
      background-size: cover;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-top: 7px;
      width: 140px;
      height: 80px;
    }
    .cropit-preview-image-container {
      cursor: move;
    }
    .image-size-label {
      margin-top: 10px;
    }
    .export {
      display: block;
      cursor: pointer;
    }
    .button_style {
        /*background-color: #f1f1f1;*/
        color: black;
        text-decoration: none;
        display: inline-block;
        padding: 0px 10px;
        font-size: 18px;
        border: 2px solid #A97C50;
        color: #A97C50;
        /*margin-left: 20px;*/

    }
    .button_style:hover {
        background-color: #ddd;
        color: black;
    }

</style> --}}

<form type="create" id="customersForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Country</label>
        <div class="col-lg-8 col-md-6">
            <select required name="country_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($countries as $country)
                <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-8 col-md-6">
            <input required name="name" placeholder="Name" class="form-control">
        </div>
    </div>
    {{-- <div class="form-group form-group-vertical">
        <label class="col-lg-2 col-md-3 control-label required">Image</label>
        <div class="col-lg-8 col-md-6">
           <div class="file-upload" input="image" prefile="" filepath="public/uploads/customers" reqWidth="500" reqHeight="588" style="height: 50%; width: 50%;"></div>
           <span>(500 x 588 pixel)</span>
        </div>
    </div> --}}
    <div class="form-group col-lg-12 col-md-12 col-xs-12">
        <label class="col-lg-2 col-md-3 control-label required">Image</label>
        <div class="col-lg-3 col-md-3">
            <div class="file-upload" input="image" filepath="public/uploads/customers" reqwidth="250" reqheight="122" ext="jpg,jpeg,png,gif"></div>
            <div class="pt5 s10">[Size: 250px * 122px]</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>

<script>
    $(".select2").select2({
        placeholder: "Select"
    }); 
</script>