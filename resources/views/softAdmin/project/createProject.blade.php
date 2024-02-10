<div style="padding-top: 1px; margin-top: -1px;"></div>
<form id="createProjectForm" callback="projIdSet" type="create" panelTitle="Project Registration" class="form-load form-horizontal group-border stripped" action="{{route('softAdmin.softAdmin.projectRegistration.store')}}" data-fv-excluded="">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 sortable-layout">
           <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Project ID</label>
                            <div class="col-lg-10 col-md-9">
                                <input readonly id="proj_id" name="project_id" placeholder="Project ID" class="form-control" value="{{$autoProjectId}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 col-md-3 control-label required">Project Name</label>
                            <div class="col-lg-10 col-md-9">
                                <input required name="name" placeholder="Project Name" class="form-control" kl_virtual_keyboard_secure_input="on">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 sortable-layout"><!--Left Side Box-->
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label required">Company Name</label>
                            <div class="col-lg-8 col-md-9">
                                <input name="company_name" required placeholder="Company Name" class="form-control">
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label" for="">Address</label>
                            <div class="col-lg-8 col-md-9"><textarea name="address" class="form-control" rows=3></textarea></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label">Street</label>
                            <div class="col-lg-8 col-md-9">
                                <input name="street" placeholder="Street" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label">City</label>
                            <div class="col-lg-8 col-md-9">
                                <input name="city" placeholder="City" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label">State</label>
                            <div class="col-lg-8 col-md-9">
                                <input name="state" placeholder="State" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-3 control-label">Post Code</label>
                            <div class="col-lg-8 col-md-9">
                                <input type="text" name="post_code" placeholder="Post Code" class="form-control">
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label required">Country</label>
                            <div class="col-lg-8 col-md-9">
                                <select required name="country" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=""></option>
                                   @foreach($countries as $coun)
                                    <option value="{{$coun->id}}">{{$coun->country}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label required">Default Currency</label>
                            <div class="col-lg-8 col-md-9">
                                <select required name="default_currency" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=""></option>
                                    @foreach($currency as $curr)
                                    <option value="{{$curr->id}}">{{$curr->html_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 sortable-layout"><!--Right Side Box-->
                <div class="panel panel-default chart">
                    <div class="panel-body pt0 pb0">
                        <div class=simple-chart>
                            <div class="form-group" style="font-size: 16px;text-align: center;">
                                <span>New User</span>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label required">Name</label>
                                <div class="col-lg-8 col-md-9">
                                    <input required name="user_name" placeholder="Name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label required">Designation</label>
                                <div class="col-lg-8 col-md-9">
                                    <input required name="designation" placeholder="Designation" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label required">Email</label>
                                <div class="col-lg-8 col-md-9">
                                    <input required name="user_email" placeholder="Email" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label required">Timezone</label>
                                <div class="col-lg-8 col-md-9">
                                    <select required name="timezone" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                        @foreach($timezones as $timezone)
                                            <option value="{{$timezone->id}}"> {{$timezone->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default chart">
                    <div class="panel-body pt0 pb0">
                        <div class=simple-chart>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label required" for="">Mobile</label>
                                <div class="col-lg-8 col-md-9">
                                    <input type="text" required placeholder="Mobile Number" class="form-control" name="mobile" id="mobile" data-fv-regexp="true"
                                    data-fv-regexp-regexp="^[0-9+\s]+$"  data-fv-regexp-message="Phone can consist of number only"/>
                                </div>
                            </div>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label" for="">Office Phone</label>
                                <div class="col-lg-8 col-md-9">
                                    <div class="input-group input-icon"><span class=input-group-addon><i class="fa fa-phone s16"></i></span> <input name="office_phone" class=form-control  placeholder="Office Phone" data-fv-regexp="true"data-fv-regexp-regexp="^[0-9+\s]+$" data-fv-regexp-message="Phone can consist of number only"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label">Fax</label>
                                <div class="col-lg-8 col-md-9">
                                    <input name="fax" placeholder="Fax" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label">Website</label>
                                <div class="col-lg-8 col-md-9">
                                    <input name="website" placeholder="http://" class="form-control" type="url">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label required">Email</label>
                                <div class="col-lg-8 col-md-9">
                                    <input type="email" required name="email" placeholder="Email" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-3 control-label">Logo </label>
                                <div class="col-lg-8 col-md-9">
                                    <div class="file-upload" input="logo" filepath="public/uploads/logo"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Project</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {        
        $(".select2").select2({
            placeholder: "Select"
        });
    });

    function projIdSet() {
        $.ajax({
            url: appUrl.getSiteAction('/projectIdView'),
            dataType: "json",
            success: function (data) {
                $("#proj_id").val(data.projectId);
            }
        });
    }


</script>
