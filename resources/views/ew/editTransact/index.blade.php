<?php $panelTitle = "Edit Transact"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="voucherEditForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                    <input id="transaction_no" name="transaction_no" placeholder="Enter Transaction No." class="form-control" data-fv-row=".col-md-4" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                    <div class="input-group">
                        <input id="date" name="date" placeholder="Date: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-4" required>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-4 col-md-8 btn-pb15">
            <button id="preview_button" type="submit" class="btn btn-default">Edit</button>
        </div>
    </form>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});

        $("#voucherEditForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var transaction_no = $('#transaction_no').val();
            var date = $('#date').val();

            var url = "{{route('ew.deleteTransactView')}}";
            loadDataListForm('', 'edit-transact-view', '', 'modal', {title:'Edit Transact', data:{date:date,transaction_no:transaction_no}}, '', false, {viewType:'modal'});
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
