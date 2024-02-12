<?php $panelTitle = "Company Information"; ?>
<style type="text/css">
    .company_profile .display_none{
        display: none;
    }
    .company_profile .single-item{
        background: #ffffff;
        border: none;
        border-radius: 0px!important;
        box-shadow:none;
    }
    .company_profile .single-item .img img{
        width: 60%;
    }
    .image_div img{
        height:70px; 
        width:auto; 
        margin: 0px 5px 10px;
    }  

    .company_profile .upload-img-overly h1, .upload-img-overly h2, .upload-img-overly h3.h4 {
        float: right;
        margin-right: 15px;
        margin-top: -18px;
}
</style>
<div class="row company_profile">
   <div class="col-lg-12 col-md-12 col-sm-12">
       <!-- col-lg-4 start here -->
      <div class="panel panel-default">
        <form id="companyProfileForm" type="update" action="{{route('ew.companyProfileAc')}}" data-fv-excluded="" class="form-load form-horizontal group-border stripped" callback="companyProfile">
            {{csrf_field()}}
            <div class=panel-heading>
                <h4 class=panel-title>{{$panelTitle}}</h4>
                <i class="fa fa-edit pull-right profile_edit hand" id="edit" data="{{$companyInfo->id}}" style="margin-right:12px; margin-top: 12px;"></i>
                <button style="margin-right:5px; display: none; margin-top: 4px;" type="submit" class="go-btn btn btn-default pull-right btn-sm profile_update_btn" url="{{'content-managers-list'}}">Update</button>
            </div>
            <div class=panel-body>
                <div class="row col-lg-3 col-md-3 col-xs-4 image_div">
                    <img src="{{url('public/uploads/logo/'.$companyInfo->logo)}}" alt="Profile Image", style="" class="logo">
                    <div class="form-group display_none com_info_eidt" style="margin-left: 0px; margin-top: -5px;">
                        <div class="file-upload" input="logo" prefile="{{$companyInfo->logo}}" filepath="public/uploads/logo"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td width="10%"><strong>Company</strong><strong class="pull-right">:</strong></td>
                                    <td width="45%"><span class="com_info">{{$companyInfo->company_name}}</span> 
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus required name="company_name" class="form-control" value="{{$companyInfo->company_name}}">
                                        </div>
                                    </td>
                                    <td width="12%"><strong>Mobile</strong><strong class="pull-right">:</strong></td>
                                    <td width="33%"><span class="com_info">{{$companyInfo->mobile}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus required name="mobile" class="form-control" value="{{$companyInfo->mobile}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%"><strong>Address</strong><strong class="pull-right">:</strong></td>
                                    <td width="45%"><span class="com_info">{{$companyInfo->address}}</span> 
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="address" class="form-control" value="{{$companyInfo->address}}">
                                        </div>
                                    </td>
                                    <td><strong>Office Phone</strong><strong class="pull-right">:</strong></td>
                                    <td ><span class="com_info">{{$companyInfo->office_phone}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="office_phone" class="form-control" value="{{$companyInfo->office_phone}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Street</strong><strong class="pull-right">:</strong></td>
                                    <td><span class="com_info">{{$companyInfo->street}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="street" class="form-control" value="{{$companyInfo->street}}">
                                        </div>
                                    </td>
                                    <td width="10%"><strong>Fax</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->fax}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="fax" class="form-control" value="{{$companyInfo->fax}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%"><strong>City</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->city}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="city" class="form-control" value="{{$companyInfo->city}}">
                                        </div>
                                    </td>
                                    <td width="10%"><strong>Email</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->email}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus required name="email" class="form-control" value="{{$companyInfo->email}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%"><strong>State</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->state}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="state" class="form-control" value="{{$companyInfo->state}}">
                                        </div>
                                    </td>
                                    <td width="10%"><strong>Website</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->website}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="website" class="form-control" value="{{$companyInfo->website}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="10%"><strong>Post Code</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$companyInfo->post_code}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <input autofocus name="post_code" class="form-control" value="{{$companyInfo->post_code}}">
                                        </div>
                                    </td>
                                    <td width="10%"><strong>Country</strong><strong class="pull-right">:</strong></td>
                                    <td width="40%"><span class="com_info">{{$company_country->country}}</span>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 display_none com_info_eidt mb0">
                                            <select required id="country" name="country" data-fv-icon="false" class="select2 form-control">
                                                @foreach($countries as $country)
                                                <option value="{{$country->id}}" @if($country->id==$company_country->id){{'selected'}}@endif>{{$country->country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
      </div>
   </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".select2").select2({ placeholder: "Select" });
        $(".profile_edit").on('click', function(){
            $(".profile_update_btn").show();
            $(".com_info_eidt").show();
            $(".profile_edit").hide();
            $(".com_info").hide();
            $(".logo").hide();
        });
    });

    function companyProfile() {
        $(".profile_update_btn").hide();
        $(".com_info_eidt").hide();
        $(".profile_edit").show();
        $(".com_info").show();
        $(".logo").show();
        location.reload("{{route('ew.master')}}");

        $('.image_div').html();
    }
</script>




