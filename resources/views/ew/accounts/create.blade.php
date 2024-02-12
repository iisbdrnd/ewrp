<?php $panelTitle = "Create Account"; ?>
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" callback="accountFormRefresh">
    {{csrf_field()}}
    <input type="hidden" id="main_code_clone" value="0">
    <input type="hidden" id="classified_code_clone" value="0">
    <input type="hidden" id="control_code_clone" value="00">
    <input type="hidden" id="subsidiary_code_clone" value="000000">
    <input type="hidden" id="account_code_clone" value="">
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 sortable-layout">
           <div class="panel panel-default chart mb15">
                <div class="panel-body">
                    <div class=simple-chart>
					    <div class="form-group">
					        <label for="main_code" class="col-lg-3 col-md-3 control-label required">Main</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="number" autofocus required id="main_code" name="main_code" min="0" max="4" value="0" class="form-control" data-fv-icon="false">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="mainClass">Main Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="classified_code" class="col-lg-3 col-md-3 control-label required">General</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="number" required id="classified_code" name="classified_code" min="0" max="9" value="0" class="form-control" data-fv-icon="false">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="classifiedClass">General Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="control_code" class="col-lg-3 col-md-3 control-label required">Control</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="text" required id="control_code" name="control_code" min="0" max="99" value="00" class="form-control" data-fv-icon="false">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="controlClass">Control Classification</span></label>
					    </div>
					    <div class="form-group">
					        <label for="subsidiary_code" class="col-lg-3 col-md-3 control-label required">Subsidiary</label>
					        <div class="col-lg-4 col-md-4">
					            <input type="text" required id="subsidiary_code" name="subsidiary_code" value="000000" class="form-control" data-fv-icon="false">
					        </div>
					        <label class="col-lg-5 col-md-5 control-label" style="text-align: left;">&rArr; <span id="subsidiaryClass">Subsidiary Classification</span></label>
					    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 sortable-layout">
           <div class="panel panel-default chart mb0">
                <div class="panel-body">
                    <div class=simple-chart>
                    	<div class="form-group">
					        <label for="account_code" class="col-lg-3 col-md-3 control-label required">Account Code</label>
					        <div class="col-lg-8 col-md-8">
					            <input required id="account_code" name="account_code" placeholder="0000000000" class="form-control">
					        </div>
					    </div>
					    <div class="form-group">
					        <label for="account_head" class="col-lg-3 col-md-3 control-label required">Account Head</label>
					        <div class="col-lg-8 col-md-8">
					            <input required id="account_head" name="account_head" placeholder="Account Head" class="form-control">
					        </div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    var accounts = JSON.parse('<?php echo $accounts; ?>');

    $(document).ready(function(){
        $("#main_code").change(function(){
            var main_code = $(this).val();
            var main_code_clone = $("#main_code_clone").val();
            if(main_code!=main_code_clone) {
                main_code_eventFunction(main_code);
            }
        });
        $("#main_code").keyup(function(){
            var main_code = $(this).val();
            var main_code_clone = $("#main_code_clone").val();
            if(main_code!=main_code_clone) {
                main_code_eventFunction(main_code);
            }
        });
        function main_code_eventFunction(main_code) {
            $("#main_code_clone").val(main_code);
            var account_code = main_code+'000000000';
            var account = accounts[account_code];
            $("#account_code").val(account_code);
            $("#account_code_clone").val(account_code);
            changeMainCode(account);
            changeAccountCode(account);
            clearClassifiedCode();
            clearControlCode();
            clearSubsidiaryCode();
        }

        $("#classified_code").change(function(){
            var classified_code = $(this).val();
            var classified_code_clone = $("#classified_code_clone").val();
            if(classified_code!=classified_code_clone) {
                classified_code_eventFunction(classified_code);
            }
        });
        $("#classified_code").keyup(function(){
            var classified_code = $(this).val();
            var classified_code_clone = $("#classified_code_clone").val();
            if(classified_code!=classified_code_clone) {
                classified_code_eventFunction(classified_code);
            }
        });
        function classified_code_eventFunction(classified_code) {
            $("#classified_code_clone").val(classified_code);
            var main_code = $("#main_code").val();
            var main_code = parseInt(main_code);
            if(main_code>0 && main_code<=4) {
                var account_code = main_code+''+classified_code+'00000000';
                var account = accounts[account_code];
                $("#account_code").val(account_code);
                $("#account_code_clone").val(account_code);
                changeClassifiedCode(account);
                changeAccountCode(account);
                clearControlCode();
                clearSubsidiaryCode();
            }
        }

        $("#control_code").keyup(function(){
            var control_code = $(this).val();
            var control_code_clone = $("#control_code_clone").val();
            if(control_code!=control_code_clone) {
                $("#control_code_clone").val(control_code);
                var main_code = $("#main_code").val();
                var main_code = parseInt(main_code);
                if(main_code>0 && main_code<=4) {
                    var classified_code = $("#classified_code").val();
                    var classified_code = parseInt(classified_code);
                    if(classified_code>0 && classified_code<=9) {
                        var account_code = main_code+''+classified_code+control_code+'000000';
                        var account = accounts[account_code];
                        $("#account_code").val(account_code);
                        $("#account_code_clone").val(account_code);
                        changeControlCode(account);
                        changeAccountCode(account);
                        clearSubsidiaryCode();
                    }
                }
            }
        });

        $("#subsidiary_code").keyup(function(){
            var subsidiary_code = $(this).val();
            var subsidiary_code_clone = $("#subsidiary_code_clone").val();
            if(subsidiary_code!=subsidiary_code_clone) {
                $("#subsidiary_code_clone").val(subsidiary_code);
                var main_code = $("#main_code").val();
                var main_code = parseInt(main_code);
                if(main_code>0 && main_code<=4) {
                    var classified_code = $("#classified_code").val();
                    var classified_code = parseInt(classified_code);
                    if(classified_code>0 && classified_code<=9) {
                        var control_code = $("#control_code").val();
                        var account_code = main_code+''+classified_code+control_code+subsidiary_code;
                        var account = accounts[account_code];
                        $("#account_code").val(account_code);
                        $("#account_code_clone").val(account_code);
                        changeSubsidiaryCode(account);
                        changeAccountCode(account);
                    }
                }
            }
        });

        $("#account_code").keyup(function(){
            var account_code = $(this).val();
            var account_code_clone = $("#account_code_clone").val();
            if(account_code!=account_code_clone) {
                $("#account_code_clone").val(account_code);
                var account = accounts[account_code];
                changeAccountCode(account);

                var accountSplit = account_code.split('');
                var accountLenth = accountSplit.length;
                if(accountLenth>=1) {
                    var cur_main_code = $("#main_code").val();
                    var main_code = accountSplit[0];
                    if(cur_main_code!=main_code) { $("#main_code").val(main_code); $("#main_code_clone").val(main_code); }
                    var account_code = main_code+'000000000';
                    var account = accounts[account_code];
                    changeMainCode(account);
                    if(accountLenth>=2) {
                        var cur_classified_code = $("#classified_code").val();
                        var classified_code = accountSplit[1];
                        if(cur_classified_code!=classified_code) { $("#classified_code").val(classified_code); $("#classified_code_clone").val(classified_code); }
                        var account_code = main_code+classified_code+'00000000';
                        var account = accounts[account_code];
                        changeClassifiedCode(account);
                        if(accountLenth>=4) {
                            var cur_control_code = $("#control_code").val();
                            var control_code = accountSplit[2]+accountSplit[3];
                            if(cur_control_code!=control_code) { $("#control_code").val(control_code); $("#control_code_clone").val(control_code); }
                            var account_code = main_code+classified_code+control_code+'000000';
                            var account = accounts[account_code];
                            changeControlCode(account);
                            if(accountLenth>=10) {
                                var cur_subsidiary_code = $("#subsidiary_code").val();
                                var subsidiary_code = accountSplit[4]+accountSplit[5]+accountSplit[6]+accountSplit[7]+accountSplit[8]+accountSplit[9];
                                if(cur_subsidiary_code!=subsidiary_code) { $("#subsidiary_code").val(subsidiary_code); $("#subsidiary_code_clone").val(subsidiary_code); }
                                var account_code = main_code+classified_code+control_code+subsidiary_code;
                                var account = accounts[account_code];
                                changeSubsidiaryCode(account);
                            } else {
                                clearSubsidiaryCode();
                            }
                        } else {
                            clearControlCode();
                            clearSubsidiaryCode();
                        }
                    } else {
                        clearClassifiedCode();
                        clearControlCode();
                        clearSubsidiaryCode();
                    }
                } else {
                    clearMainCode();
                    clearClassifiedCode();
                    clearControlCode();
                    clearSubsidiaryCode();
                }
            }
        });

        //Change function
        function changeMainCode(account) {
            if((typeof account!==typeof undefind) && (account['account_level']==1)) {
                var account_head = account['account_head'];
                $("#mainClass").html(account_head);
            } else {
                $("#mainClass").html("Main Classification");
            }
        }
        function changeClassifiedCode(account) {
            if((typeof account !== typeof undefind) && (account['account_level']==2)) {
                var account_head = account['account_head'];
                $("#classifiedClass").html(account_head);
            } else {
                $("#classifiedClass").html("General Classification");
            }
        }
        function changeControlCode(account) {
            if((typeof account !== typeof undefind) && (account['account_level']==3)) {
                var account_head = account['account_head'];
                $("#controlClass").html(account_head);
            } else {
                $("#controlClass").html("Control Classification");
            }
        }
        function changeSubsidiaryCode(account) {
            if((typeof account !== typeof undefind) && (account['account_level']==4)) {
                var account_head = account['account_head'];
                $("#subsidiaryClass").html(account_head);
            } else {
                $("#subsidiaryClass").html("Subsidiary Classification");
            }
        }
        function changeAccountCode(account) {
            if(typeof account !== typeof undefind) {
                var account_head = account['account_head'];
                $("#account_head").val(account_head);
                $("#account_head_clone").val(account_head);
                $("#account_head").attr("placeholder", "Account Head");
            } else {
                $("#account_head").val('');
                $("#account_head_clone").val('');
                $("#account_head").attr("placeholder", "Not Exist");
            }
        }

        //Clear Function
        function clearMainCode() {
            $("#main_code").val(0);
            $("#main_code_clone").val(0);
            $("#mainClass").html("Main Classification");
        }
        function clearClassifiedCode() {
            $("#classified_code").val(0);
            $("#classified_code_clone").val(0);
            $("#classifiedClass").html("General Classification");
        }
        function clearControlCode() {
            $("#control_code").val('00');
            $("#control_code_clone").val('00');
            $("#controlClass").html("Control Classification");
        }
        function clearSubsidiaryCode() {
            $("#subsidiary_code").val('000000');
            $("#subsidiary_code_clone").val('000000');
            $("#subsidiaryClass").html("Subsidiary Classification");
        }
    });

    function accountFormRefresh(data) {
        var dataAccount = JSON.parse(data.account);
        accounts[dataAccount.account_code] = dataAccount;
    }
</script>
