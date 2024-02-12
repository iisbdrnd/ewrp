<?php $panelTitle = "Designation Create"; ?>
@include("panelStart")
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
        {{csrf_field()}}
            <div class="form-group">
                <label class="col-lg-4 col-md-3 control-label required">Designation Name</label>
                <div class="col-lg-4 col-md-9">
                    <input autofocus required name="name" placeholder="Designation Name" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label required">Grade</label>
                <div class="col-lg-4 col-md-4">
                    <input autofocus required name="grade" placeholder="Grade" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-4 col-md-offset-4 col-lg-4 col-md-4">
                    <button type="submit" class="btn btn-default">Create Designation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include("panelEnd")