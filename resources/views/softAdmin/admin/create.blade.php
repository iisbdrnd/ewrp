<form type="create" panelTitle="Create Admin" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Username</label>
        <div class="col-lg-10 col-md-9">
            <input required name="username" placeholder="Username" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Password</label>
        <div class="col-lg-10 col-md-9">
            <input required name="password" type="password" placeholder="Password" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Confirm Password</label>
        <div class="col-lg-10 col-md-9">
            <input required data-fv-identical="true" data-fv-identical-field="password" name="password_confirmation" type="password" placeholder="Confirm Password" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Admin</button>
        </div>
    </div>
</form>

