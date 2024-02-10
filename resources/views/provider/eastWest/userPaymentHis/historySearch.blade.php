<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="input-group">
                <span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="from_date" name="from_date" placeholder="From Date" class="form-control" data-fv-trigger="blur">
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="input-group">
                <span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="to_date" name="to_date" placeholder="To Date" class="form-control" data-fv-trigger="blur">
            </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <span class="input-group-btn"><button name="search" id="dateWiseSearch" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Search</button></span>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">All History Search</button></span>
        </div>

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $('#from_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#to_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#dateWiseSearch").on("click", function(){
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            $.ajax({
                url : "userPaymentHistoryData",
                type: "GET",
                data: {fromDate:fromDate},
                dataType: "json",
                success:function(data){
                
				}
            });
        });
    });
  
</script>
