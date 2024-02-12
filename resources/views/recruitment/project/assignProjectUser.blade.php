<?php $panelTitle = "Assign User"; ?>
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
  {{csrf_field()}}
  <div class="panel panel-default chart">
    <div class="panel-body">
      <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">User Name</label>
        <div class="col-lg-9 col-md-8">
          <select required name="assign_user[]" id="assign_user" class="select2 form-control" multiple placeholder="Choose User">
            <option>Choose User</option>
            @foreach($users as $k =>  $user)
              <option {{  @$assignUsers[$user->id]== $user->id?'selected':'' }} value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      
      <div class="form-group">
          <label class="col-lg-3 col-md-4 control-label required">Co-ordinator</label>
          <div class="col-lg-9 col-md-8">
            <select required name="coordinator" id="coordinator" class=" form-control" placeholder="Select Co-ordinator">
              <option></option>
            </select>
          </div>
        </div>
    </div>
  </div>
</form>

<script type="text/javascript">
  $("select.select2").select2({
    placeholder: "Select"
  });

$("select#assign_user").change(function() {
    var str = "";
    $( "select#assign_user option:selected" ).each(function() {
      str += "<option value='"+$( this ).val()+"'>"+$( this ).text()+"</option>";
    });
    $( "select#coordinator" ).html( str );
  }).trigger( "change" );
$('select#coordinator').val('{{ $coordinators }}').attr('selected', 'selected');
</script>