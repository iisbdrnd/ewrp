<form type="update" panelTitle="Update Admin" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="name" placeholder="Name" class="form-control" value="{{$admin->name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control" value="{{$admin->email}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Username</label>
        <div class="col-lg-10 col-md-9">
            <input required name="username" placeholder="Username" class="form-control" value="{{$admin->username}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Password</label>
        <div class="col-lg-10 col-md-9">
            <input name="password" type="password" placeholder="No Change" class="form-control" kl_virtual_keyboard_secure_input="on">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Confirm Password</label>
        <div class="col-lg-10 col-md-9">
            <input data-fv-identical="true" data-fv-identical-field="password" name="password_confirmation" type="password" placeholder="Confirm Password" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update User</button>
        </div>
    </div>
</form>

