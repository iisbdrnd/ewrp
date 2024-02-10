<?php $panelTitle = "User Profile"; ?>
<!-- End  / heading--><!-- Start .row -->
<div class=row>
   <div class="col-lg-12 col-md-12 col-sm-12">
       <!-- col-lg-4 start here -->
      <div class="panel panel-default">
         <!-- Start .panel -->
         <div class=panel-heading>
            <h4 class=panel-title>Profile details</h4>
         </div>
         <div class=panel-body>
            <div class="row">
               <div style="background-image: url('public/img/B.png'); background-image: url('public/img/B.png');height: 120px;width: 100%;margin-top: -15px;" > </div>
            </div>
            <div class="row">
               <div class="col-lg-2 profile-avatar">
                  @if(empty($userProfileDetails->image))
                  <img src="{{url('public/img/avatar.jpg')}}" alt="Profile Image", class="img-circle" alt="Cinque Terre" style="height:128px; width:135px; margin-top: -55px;">
                  @else
                  <img src="{{url('public/uploads/user_profile_images/'.$userProfileDetails->image)}}" alt="Profile Image", class="img-circle" alt="Cinque Terre" style="height:128px; width:135px; margin-left: 35px; margin-top: -55px;">
                  @endif
               </div>
               <div class="col-lg-10 mt20">
                  <span style="font-size:20px; margin-left:10px;">{{$userProfileDetails->name}}</span>
               </div>
            </div>
            <div class="row" style="padding:10px 45px 5px 45px;">
               @if(!empty($userProfileDetails->about))
               <div class="profile-info bt">
                  <h5 class=text-muted>About</h5>
                  <p style="text-align: justify;">{{$userProfileDetails->about}}</p>
               </div>
               @endif
            </div>
            <div class="row" style="padding:20px 45px 10px 45px;">
                  <table class="table table-hover">
                     <tbody>
                        <tr>
                           <td width="30%"><strong>Designation</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->designationName}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Job Area</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userJobArea->area_name}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Job Area Details</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userJobArea->area_details}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Timezone</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userTimezones->name}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Address</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->address}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Mobile</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->mobile}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Office Phone</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->office_phone}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Fax</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->fax}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Email</strong></td>
                           <td width="70%"><strong>:</strong> {{@$userProfileDetails->email}}</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Age</strong></td>
                           <td width="70%"><strong>: </strong>@if(!empty($userProfileDetails->age)) {{$userProfileDetails->age}} Years @endif</td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Gerder</strong></td>
                           <td width="70%"><strong>:</strong> <?php switch($userProfileDetails->gender) {case 1: echo "Male"; break; case 2: echo "Female";} ?></td>
                        </tr>
                        <tr>
                           <td width="30%"><strong>Report To</strong></td>
                           <td width="70%"><strong>:</strong>
                              @if(!empty($userReportTo->name))
                                 {{@$userReportTo->name}}</td>
                              @else
                                 {{'N/A'}}
                              @endif
                        </tr>
                     </tbody>
                  </table>
            </div>
         </div>
      </div>
   </div>
</div>




