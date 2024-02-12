<?php $panelTitle = "Ticket Bill"; ?>
@include("panelStart")
<div class="row mt15">
    <form id="aviationBillEntryForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_candidate_area">
    {{csrf_field()}}
        <div class="col-lg-4 col-md-5 col-sm-6 sortable-layout mb10">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group">
                            <label for="aviation_id" class="col-lg-3 col-md-3 control-label required">Aviation</label>
                            <div class="col-lg-9 col-md-9">
                                <select required name="aviation_id" id="aviation_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                    @foreach($ewAviations as $ewAviation)
                                    <option value="{{$ewAviation->id}}">{{$ewAviation->account_code .' - '. $ewAviation->company_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="target_amount" class="col-lg-3 col-md-3 control-label required">Target Amount</label>
                            <div class="col-lg-9 col-md-9">
                                <input required name="target_amount" id="target_amount"  placeholder="e.g. 50000" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                   data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group">
                            <label for="ew_project_id" class="col-lg-3 col-md-3 control-label required">Project</label>
                            <div class="col-lg-9 col-md-9">
                                <select required id="ew_project_id" name="ew_project_id" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=""></option>
                                    @foreach($ewProjects as $ewProject)
                                    <option value="{{$ewProject->id}}">{{$ewProject->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="candidate_id" class="col-lg-3 col-md-3 control-label required">Candidate</label>
                            <div class="col-lg-9 col-md-9">
                                <select required name="candidate_id" id="candidate_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="bill_type" class="col-lg-3 col-md-3 control-label">Types</label>
                            <div class="col-lg-9 col-md-9">
                                <select required id="bill_type" name="bill_type" data-fv-icon="false" class="select2 form-control ml0">
                                    <option value=""></option>
                                    @foreach($aviationTypes as $type)
                                    <option value="{{$type->id}}" @if($type->id==1) selected @endif>{{$type->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nos" class="col-lg-3 col-md-3 control-label required">NOS</label>
                            <div class="col-lg-9 col-md-9">
                                <input required id="nos" name="nos"  placeholder="e.g. 012" class="form-control nos" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                   data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-lg-3 col-md-3 control-label required">Amount</label>
                            <div class="col-lg-9 col-md-9">
                                <input required id="amount" name="payment_amount"  placeholder="e.g. 50000" class="form-control amount number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                   data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                            </div>
                             <p class="text-center"><strong class="words  text-primary"></strong></p>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 col-md-3 control-label">Remarks</label>
                            <div class="col-lg-9 col-md-9">
                                <textarea rows=3 autofocus name="remarks" id="remarks" placeholder="Remarks" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-lg-offset-3 col-md-offset-3">
                            <button id="add_amount" type="button" class="btn btn-block btn-info add_account">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Store Table Start-->
        <div class="col-lg-8 col-md-7 col-sm-6 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <table cellspacing="0" class="responsive table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="30%">Project</th>
                                <th width="30%">Candidate</th>
                                <th width="20%">Bill Type</th>
                                <th width="20%">Amount</th>
                                <th width="5%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="selected_candidate">
                            <tr class="nothing_here">
                                <td colspan="7" class="text-center">Nothing here</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align:right; font-weight:bold;">Total Bill Amount</td>
                                <td id="total_amount_area" colspan="2">
                                    <span>&#2547;</span>
                                    <span id="total_amount_text">00</span>
                                    <input type="hidden" name="total_amount" value="0"/>
                                </td>
                            </tr>
                         </tfoot>
                    </table>
                </div>
            </div>
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Remarks:</label>
                            <div class="col-md-11">
                                <input autofocus id="main_remarks" name="main_remarks" placeholder="Remarks" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt10">
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </form>
</div>
@include("panelEnd")
<script src="{{asset('public/js/number_to_words.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        sl=0;
        $(".select2").select2({ placeholder: "Select" });
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});

        //CONDIDATE SEARCH
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $(this).val();
            if (ew_project_id) {
                //For Project wise candidates
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectWiseCandidates")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $('#aviationBillEntryForm').formValidation('removeField', $('#candidate_id'));
                        $("#candidate_id").html(data);
                        $("#candidate_id").select2({ placeholder: "Select" });
                        $('#aviationBillEntryForm').formValidation('addField', $('#candidate_id'));
                    }
                });
            }
        });


        $("#aviationBillEntryForm").formValidation();

        //Amount onclick Remarks
        $("#aviationBillEntryForm").on('keypress', '.amount', function(){
            var projectName = $("#ew_project_id option:selected").text();
            var candidate_name = $("#candidate_id option:selected").text();
            var remarks = 'Project: '+projectName+ ', Candidate: '+candidate_name;
            $("#remarks").val(remarks);
        });

        $(".genMainRemarks").on('keyup', function() {
            genMainRemarks();
        });

        $("#target_amount").on('change', function() {
            genMainRemarks();
        });

        $("#add_amount").on('click', function(e) {
            var ew_project_id = $("#ew_project_id option:selected").val();
            var candidate_id = $("#candidate_id option:selected").val();
            var bill_type = $("#bill_type option:selected").val();
            var bill_date = $("#bill_date").val();
            var nos = $("#nos").val();
            var amount = parseFloat($("#amount").val());
            var remarks = $("#remarks").val();
            // alert(bill_date);

            var ew_project_name = $("#ew_project_id option:selected").text();
            var candidate_name = $("#candidate_id option:selected").text();
            var bill_type_name = $("#bill_type option:selected").text();
            // alert(candidate_name);
            if(candidate_id && amount>0 && nos.length>0) {
                var candidate = $("#selected_candidate").find('input[name="candidate_id[]"][value="'+candidate_id+'"]');
                var candidate_bill_type = candidate.parent().find('input[name="bill_type[]"][value="'+bill_type+'"]');

                //alert for type
                if(candidate.length>0 && candidate_bill_type.length>0) {
                    alert('You already select this candidate');
                    $("#candidate_id").val('');
                    $("#candidate_id").select2({placeholder: "Select"});
                    return false;
                }
                sl++;
                var html = '<tr id="row_'+sl+'"><td class="sl_no">'+sl+'</td>';
                    html += '<td class="project_name">'+ew_project_name+'</td>';
                    html += '<td class="candidate_name">'+candidate_name+'</td>';
                    html += '<td>'+bill_type_name+'</td>';
                    html += '<td>&#2547; '+amount+'</td>';
                    html += '<td class="text-center"><i class="icomoon-icon-close remove_amount hand" title="remove"></i></td>';
                    html += '<input type="hidden" name="ew_project_id[]" value="'+ew_project_id+'" />';
                    html += '<input type="hidden" name="ew_project_name[]" value="'+ew_project_name+'" />';
                    html += '<input type="hidden" name="candidate_id[]" value="'+candidate_id+'" />';
                    html += '<input type="hidden" name="bill_type[]" value="'+bill_type+'" />';
                    html += '<input type="hidden" name="nos[]" value="'+nos+'" />';
                    html += '<input type="hidden" name="amounts[]" value="'+amount+'" />';
                    html += '<input type="hidden" name="remarks[]" value="'+remarks+'" /></tr>';



                if($('#selected_candidate').find('.nothing_here').is(':visible')) {
                    $('#selected_candidate').find('.nothing_here').remove();
                }
                $("#selected_candidate").append(html);
                $("#candidate_id").val('');
                // $("#amount").val('');
                // $("#bill_type").val('');
                // $("#nos").val('');
                $("#remarks").val('');
                $("#candidate_id").select2({placeholder: "Select"});
                $("#bill_type").select2({placeholder: "Select"});
                update_total_amount();
                //For Auto Remarks Generate
                genMainRemarks();
            } else {
                alert('Please select all required field with amount');
            }
        });

        $("#selected_candidate").on('click', '.remove_amount', function(e) {
            $(this).parent().parent().remove();
            if($("#selected_candidate > tr").length==0) {
                $("#selected_candidate").append('<tr class="nothing_here"><td colspan="7" class="text-center">Nothing here</td></tr>');
            } else {
                $("#selected_candidate > tr").each(function(index, element) {
                    $(element).find(".sl_no").text(index+1);
                });
            }
            update_total_amount();
            genMainRemarks();
            sl--;
        });
    });
        
    //Reset Remarks
    $("#ew_project_id").on('change', function(e) {
        $("#aviationBillEntryForm").find("#remarks").val('');
    });

    //Auto Remarks Generate
    function genMainRemarks(){
        var all_project_name = '';
        var all_candidate_name = '';
        var bill_amount = parseFloat($("#total_amount_text").text());
        var $totalCandidates = $("#selected_candidate > tr");
        
        if ($totalCandidates.length>0 && bill_amount>0) {

            $totalCandidates.each(function(index, element) {
                var project_name = $(element).find('.project_name').text();
                var candidate_name = $(element).find('.candidate_name').text();
                var candidate_name = candidate_name.split("-")[0].trim();

                all_candidate_name += (all_candidate_name!='') ? ', '+candidate_name : candidate_name;

                if(all_project_name!='') {
                    var project_array = all_project_name.split(', ');
                    if(project_array.indexOf(project_name)<0) {
                        all_project_name += ', '+project_name;
                    }
                } else {
                    all_project_name += project_name;
                }
            });
            var total_candidate = ("0" + $totalCandidates.length).slice(-2);
            var per_cadidate = parseFloat(bill_amount/parseFloat(total_candidate)).toFixed(2);

            var remarks = 'Provision as air ticket purpose against Id no: ';
                remarks += all_candidate_name+' ('+total_candidate+'pax X '+per_cadidate+')='+bill_amount+' ';
                remarks += 'Proj. ('+all_project_name+')';
            $("#main_remarks").val(remarks);
        } else {
            $("#main_remarks").val('');
        }
        
    }

    function update_total_amount() {
        var total_amount = 0;
        $("#selected_candidate").find('input[name="amounts[]"]').each(function(index, element) {
            total_amount += parseFloat($(element).val());
        });
        $('#total_amount_text').text(total_amount);
        $('#total_amount_area').find('input[name="total_amount"]').val(total_amount);
    }


    function refresh_candidate_area() {
        $("#selected_candidate").html('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
        update_total_amount();
    }
</script>

