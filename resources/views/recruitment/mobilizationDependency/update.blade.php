<style type="text/css">
    .modal-dialog
    {
        width: 80%;
        margin-left: 160%;
        margin-top: -22% !important;
    }
</style>
<?php $panelTitle = "Mobilization Dependency Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border" id="moblize_dependency_form">
    {{csrf_field()}}
   
    <div class="form-group" style="margin-top: 1%">
        <label class="col-lg-offset-1 col-lg-3 col-md-3 control-label required" style="font-weight: 800;">Select Country</label>
        <div class="col-lg-5 col-md-5">
            <select name="project_country_id" id="country_id" class="form-control select2" placeholder="Select Country" required="">
                <option value="">Select</option>
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if($country->id==$project_country_id){{'selected'}}@endif>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="project_contry_name" id="country_name_value">
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6" style="border-right: 5px solid #d6d5d5;">
            @foreach($mobilization as $key => $s_dependency)
            <div class="col-lg-12 col-md-12" style="margin-left: 2%">
                <div class="form-group">
                  <div id="dependency_plus">
                    <div class="dependency_top">
                       <div class="col-lg-1 col-md-1" style="width: 4%">
                        {{ $key+1 }})
                        </div>
                        <div class="col-lg-5 col-md-5" id="dependency_list">
                            <select name="mobilize_name_id[]" id="mobilize_id" data-fv-icon="false" class="form-control select2 ml0"> 
                              <option value="{{$key+1}}" selected="selected">{{ @Helper::getMobilizeName($key+1) }}</option>
                            </select>
                        </div>

                        <div class="col-lg-5 col-md-5">
                            <select name="mobilize_dependency_id[]" id="dependency_id" data-fv-icon="false" class="form-control select2 ml0"> 
                              <option value="0">N/A</option>
                              @foreach($all_mobilization as $mobilize)
                                <option value="{{$mobilize->id}}" @if($mobilize->id==$s_dependency->mobilize_depended_on_id){{'selected'}}@endif>
                                    {{$mobilize->name}}
                                </option>
                              @endforeach
                            </select>
                        </div>


                    </div>
                  </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-lg-6 col-md-6">
            @foreach($last_mobilization as $key => $s_dependency)
            <div class="col-lg-12 col-md-12" style="margin-left: 2%">
                <div class="form-group">
                  <div id="dependency_plus">
                    <div class="dependency_top">
                       <div class="col-lg-1 col-md-1" style="width: 4%">
                        {{ $key+16 }})
                        </div>
                        <div class="col-lg-5 col-md-5" id="dependency_list">
                            <select name="mobilize_name_id[]" id="mobilize_id" data-fv-icon="false" class="form-control select2 ml0"> 
                               <option value="{{$key+16}}" selected>{{ @Helper::getMobilizeName($key+16) }}</option>
                            </select>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <select name="mobilize_dependency_id[]" id="dependency_id" data-fv-icon="false" class="form-control select2 ml0"> 
                              <option value="0">Select</option>
                              @foreach($all_mobilization as $mobilize)
                                <option value="{{$mobilize->id}}" @if($mobilize->id==$s_dependency->mobilize_depended_on_id){{'selected'}}@endif>
                                    {{$mobilize->name}}
                                </option>
                              @endforeach
                            </select>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    



</form>

<script type="text/javascript">
    $(document).ready(function() {
        var country_id = $("#moblize_dependency_form #country_id").val();
        if(country_id != 0)
        {
            var name =$("#moblize_dependency_form #country_id").find("option:selected").text();
            var name = name.trim();
            $('#moblize_dependency_form #country_name_value').val(name);
        }

        $('#moblize_dependency_form #country_id').change(function(){
            var name = this.options[this.selectedIndex].text;
            $('#moblize_dependency_form #country_name_value').val(name);
        });
        
        $("#moblize_dependency_form #mobilize_id").select2({
            
        });
        $("#moblize_dependency_form #dependency_id").select2({
            
        });
        $("#moblize_dependency_form #country_id").select2({
            placeholder: "Select",
        });

    });

</script>