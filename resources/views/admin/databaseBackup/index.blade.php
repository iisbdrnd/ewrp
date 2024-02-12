<?php $panelTitle = "Database Backup"; ?>
@include("panelStart")
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" callback="dbBackUpDownload">
            {{csrf_field()}}
            <div class="form-group">
                <label class="col-lg-4 col-md-3 control-label required">Password</label>
                <div class="col-lg-4 col-md-9">
                    <input autofocus required name="password" type="password" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-4 col-md-offset-4 col-lg-4 col-md-4">
                    <button type="submit" class="btn btn-default">Download</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include("panelEnd")

<script type="text/javascript">
    function dbBackUpDownload(data) {
        //window.open(data.url, "", "width=50,height=50");
        location.replace(data.url);
    }
</script>
