<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-4">
            <div class="input-group">
                <span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="from_date" name="closed_date" placeholder="From Date" class="form-control" data-fv-trigger="blur">
            </div>
        </div>
        <div class="col-md-3 col-xs-4">
            <div class="input-group">
                <span class=input-group-addon><i class="fa fa-calendar"></i></span><input required id="to_date" name="closed_date" placeholder="To Date" class="form-control" data-fv-trigger="blur">
            </div>
        </div>
        <div class="col-md-1 col-xs-1">
            <span class="input-group-btn"><button name="search" id="dateWiseSearch" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Search</button></span>
        </div>
        <div class="col-md-2 col-xs-2">
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
       
    });
  
</script>
