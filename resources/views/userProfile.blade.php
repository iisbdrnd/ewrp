<?php $panelTitle = "User Profile"; ?>
	<!-- End  / heading--><!-- Start .row -->
        <div class=row>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <!-- col-lg-4 start here -->
               <div class="panel panel-default">
	                <!-- Start .panel -->
	                <div class=panel-heading>
	                    <h4 class=panel-title>Profile details</h4>
	                </div>
   	            <div class=panel-body>
   	               <div id="test" class="row profile">
   	                        <!-- Start .row -->
   	                        <div class=col-md-4>
   	                           <div class=profile-avatar>
                                    @if(empty($userProfileDetails->image))
                                    <img src="{{url('public/img/avatar.jpg')}}" alt="Profile Image", style="height:135px; width:135px;">
   	                              @else
                                    <img src="{{url('public/uploads/user_profile_images/'.$userProfileDetails->image)}}" alt="Profile Image", style="height:135px; width:135px;">
                                    @endif
                                 </div>
   	                        </div>
   	                        <div class="col-md-8 mt20">
   	                           <div class=profile-name>
   	                              <h3>{{$userProfileDetails->name}}</h3>
                                    <p class="job-title mb0"><strong>{{$userProfileDetails->designationName}}</strong></p>
                                    <p class="job-title mb0">{{$userProfileDetails->projectName}}</p>
                                    
   	                           </div>
   	                        </div>
   	                        <div class="col-md-12 mt15">
   	                           <div class="profile-info bt">
   	                              <h5 class=text-muted>About</h5>
   	                              <p>{{$userProfileDetails->about}}</p>
   	                           </div>
                                 <div class="social bt">
                                    <dl class="dl-horizontal mt10 mb0">
                                       <dt><span class="text-muted">Address</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->address}}</dd>
                                       <dt><span class="text-muted">Mobile</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->mobile}}</dd>                                       <dt><span class="text-muted">Office Phone</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->office_phone}}</dd>
                                       <dt><span class="text-muted">Fax</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->fax}}</dd>
                                       <dt><span class="text-muted">Email</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->email}}</dd>
                                       <dt><span class="text-muted">Age</span></dt>
                                       <dd><strong>: </strong>{{$userProfileDetails->age}} Years</dd>
                                       <dt><span class="text-muted">Gerder</span></dt>
                                       <dd><strong>: </strong><?php switch($userProfileDetails->gender) {case 1: echo "Male"; break; case 2: echo "Female";} ?></dd>
                                    </dl>
                                 </div>
   	                        </div>
   	               </div>
   	               <!-- End .row -->
                  </div>
               </div>
               <!-- End .panel -->
               <div class="panel panel-default plain">
                  <!-- Start .panel -->
                  <!-- <div class=panel-heading>
                     <h4 class=panel-title>User stats</h4>
                  </div>
                  <div class=panel-body>
                     <ul class="progressbars-stats list-unstyled">
                        <li>
                           <div class=progressbar-icon><i class="glyphicon glyphicon-user"></i></div>
                           <span class=progressbar-text>Profile complete</span>
                           <div class="progress animated-bar flat mt0">
                              <div class=progress-bar role=progressbar data-transitiongoal=40></div>
                           </div>
                        </li>
                     </ul>
                  </div> -->
               </div>
               <!-- End .panel -->
            </div>
            <!-- col-lg-4 end here -->
            <div class="col-lg-6 col-md-6 col-sm-12">
            <!-- col-lg-4 start here -->
            <div class="tabs mb20">
               <ul class="nav nav-tabs" id="profileTab">
                  <li class="active"><a data-toggle="tab" href="#update_profile" aria-expanded="true">Update Profile</a></li>
                  <li><a data-toggle="tab" href="#change_password">Change Password</a></li>
				  <li><a data-toggle="tab" href="#timezone">Timezone</a></li>
               </ul>
               <div class="tab-content" id="myTabContent">
                  <div id="update_profile" class="tab-pane fade active in">
                     <form id="updateProfileForm" type="update" action="{{route('crm.userProfileUpdate', $user_id)}}" data-fv-excluded="" class="form-load form-horizontal group-border stripped">
                        {{csrf_field()}}
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label required">Name</label>
                           <div class="col-lg-9">
                              <input required name="name" id="name" placeholder="Name" class="form-control" value="{{$userProfileDetails->name}}">
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Designation</label>
                           <div class="col-lg-9">
                              <select disabled name="" data-fv-icon="false" class="select2 form-control ml0 disabled">
                                 <option>{{$userProfileDetails->designationName}}</option>
                              </select>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Adress</label>
                           <div class="col-lg-9"><textarea class="form-control" rows="3" id="address" name="address">{{$userProfileDetails->address}}</textarea></div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">About</label>
                           <div class="col-lg-9"><textarea class="form-control" rows="3" id="about" name="about">{{$userProfileDetails->about}}</textarea></div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Mobile</label>
                           <div class="col-lg-9 col-md-9">
                              <input type="text" placeholder="Mobile Number" class="form-control" name="mobile" id="mobile" data-fv-regexp="true" value="{{$userProfileDetails->mobile}}"
                              data-fv-regexp-regexp="^[0-9+\s]+$"  data-fv-regexp-message="Mobile can consist of number only"/>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Office Phone</label>
                           <div class="col-lg-9 col-md-9">
                              <input type="text" placeholder="Office Phone" class="form-control" name="office_phone" id="office_phone" data-fv-regexp="true" value="{{$userProfileDetails->office_phone}}"
                              data-fv-regexp-regexp="^[0-9+\s]+$"  data-fv-regexp-message="Phone can consist of number only"/>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Fax</label>
                           <div class="col-lg-9"><input name="fax" id="fax" placeholder="Fax" class="form-control" value="{{$userProfileDetails->fax}}"></div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Age</label>
                           <div class="col-lg-9">
                              <div class=input-group>
                                 <input data-fv-icon="false" name="age" placeholder="Age" class="form-control" value="{{$userProfileDetails->age}}">
                                 <span class="input-group-addon">Years</span>
                              </div>
                           </div>
                        </div>
                        <!-- End .form-group  -->

                        <div class="form-group">
                           <label for="" class="col-lg-3 control-label">Gender</label>
                           <div class="col-lg-9">
								<select name="gender" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value="{{$userProfileDetails->gender}}"><?php switch($userProfileDetails->gender) {case 1: echo "Male"; break; case 2: echo "Female";} ?></option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group form-group-vertical">
                           <label class="col-lg-3 col-md-3 control-label">Image</label>
                           <div class="col-lg-9 col-md-9">
                              <div class="file-upload" input="image" prefile="{{$userProfileDetails->image}}" filepath="public/uploads/user_profile_images"></div>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <div class="col-lg-offset-3 mb15">
                              <button id="updateProfileButton" type="submit" class="btn btn-default ml15">Update Profile</button>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                     </form>
                  </div>
                  <div id="change_password" class="tab-pane fade pb0 mb15">
                     <form type="create" action="{{route('crm.changePassword', $user_id)}}" data-fv-excluded="" class="form-load form-horizontal group-border stripped">
                        {{csrf_field()}}
                        <!-- End .form-group  -->
                        <div class="form-group form-group-vertical">
                           <label for="" class="col-lg-3 control-label required">Old password</label>
                           <div class="col-lg-9">
                              <input required name="old_password" type="password" placeholder="Old Password" class="form-control">
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group form-group-vertical">
                           <label for="" class="col-lg-3 control-label required">New password</label>
                           <div class="col-lg-9">
                              <input required name="password" type="password" placeholder="New Password" class="form-control" >
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group form-group-vertical">
                           <label for="" class="col-lg-3 control-label required">Confirm Password</label>
                           <div class="col-lg-9">
                              <input required data-fv-identical="true" data-fv-identical-field="password" name="password_confirmation" type="password" placeholder="Confirm Password" class="form-control" >
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <div class="col-lg-offset-3">
                              <button id="changePassButton" type="submit" class="btn btn-default ml15">Change Password</button>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                     </form>
                  </div>
				  <div id="timezone" class="tab-pane fade pb0 mb15">
                     <form type="update" action="{{route('changeTimeZone', $user_id)}}" data-fv-excluded="" class="form-load form-horizontal group-border stripped">
                        {{csrf_field()}}
						<input type="hidden" name="timezone_id" value="">
                        <!-- End .form-group  -->
                        <div class="form-group form-group-vertical">
                           <label for="" class="col-lg-3 control-label required">Timezone</label>
                           <div class="col-lg-9">
      								<select name="timezone" data-fv-icon="false" class="select2 form-control ml0">
      									@foreach($timezones as $timezone)
      										<option value="{{$timezone->id}}" @if($userProfileDetails->timezone_id==$timezone->id) {{'selected'}} @endif > {{$timezone->name}} </option>
      									@endforeach
                              </select>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                        <div class="form-group">
                           <div class="col-lg-offset-3">
                              <button id="changeTimezoneButton" type="submit" class="btn btn-default ml15">Change Timezone</button>
                           </div>
                        </div>
                        <!-- End .form-group  -->
                     </form>
                  </div>
               </div>
            </div>
            <!-- End .tabs -->
            </div>
         </div>
         <!-- End .row -->
         <script type="text/javascript">
             $(document).ready(function() {
                 $(".select2").select2({
                     placeholder: "Select"
                 });
             });
			 $("#updateProfileButton").click(function(){
					location.reload("{{route('crm.master')}}");
			 });
         </script>




