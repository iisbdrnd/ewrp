<div style="padding-top: 1px; margin-top: -1px;"></div>
<form id="projectRenewForm" type="update" action="{{url('softAdmin/projectRenewAc')}}" panelTitle="Project Renew" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <input id="project_id" type="hidden" name="project_id" value="{{$project->id}}">
    <div class="row mt15">
        <div class="col-lg-6 col-md-12 sortable-layout">
            <!--Start-->
            <div class="panel panel-default chart">
                <div class="panel-body pt0 pb0">
                    <div class=simple-chart>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label required">CRM Package</label>
                            <div class="col-lg-8 col-md-9">
                                <select id="crm_package_id" name="crm_package_id" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=""></option>
                                    @foreach($crmPackage as $crmPackage)
                                    <option value="{{$crmPackage->id}}" @if($project->crm_package_id==$crmPackage->id){{'selected'}}@endif>{{$crmPackage->package_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=form-group>
                            <label class="col-lg-4 col-md-3 control-label required">CRM User</label>
                            <div class="col-lg-8 col-md-9">
                                <input required id="pkgUser" name="crm_user" type="text" placeholder="CRM User" class="form-control" value="{{$project->crm_user}}">
                            </div>
                        </div>
                        <?php
                            $crmExpireDate = DateTime::createFromFormat('Y-m-d', $project->crm_expire_date);
                            $crm_expire_date = $crmExpireDate->format('d/m/Y');
                        ?>
                        <div class=form-group>
                           <label class="col-lg-4 col-md-3 control-label required" for="">Duration</label>
                           <div class="col-lg-8 col-md-9">
                              <div class=input-group><span class=input-group-addon><i class="fa fa-calendar"></i></span> <input required id="crm_expire_date" name="crm_expire_date" class="form-control" value={{$crm_expire_date}}></div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End-->
        </div>
        <div class="col-lg-6 col-md-12 sortable-layout"><!--Right Side Box-->
                <div class="panel panel-default chart">
                    <div class="panel-body pt0 pb0">
                        <div class=simple-chart>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label required">Payment By</label>
                                <div class="col-lg-8 col-md-9">
                                    <select required name="user_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label">Currency</label>
                                <div class="col-lg-8 col-md-9">
                                    <select name="currency" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                        @foreach($currency as $curr)
                                        <option value="{{$curr->id}}">{{$curr->html_code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label">Amount</label>
                                <div class="col-lg-8 col-md-9">
                                    <input name="amount"  placeholder="Amount" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                        data-fv-regexp-regexp="^[0-9+\s\,\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                                </div>
                            </div>
                            <div class=form-group>
                                <label class="col-lg-4 col-md-3 control-label">Payment Method</label>
                                <div class="col-lg-8 col-md-9">
                                    <input name="payment_method" type="text" placeholder="Payment Method" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Project Renew</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });

        $('#crm_expire_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    });
</script>
