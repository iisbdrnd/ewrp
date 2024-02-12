<form type="create" action="{{url('eastWest/mobilization-list')}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="trade_name" placeholder="e.g.GENERAL WORKER" class="form-control">
        </div>
    </div>
</form>