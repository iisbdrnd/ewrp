<style>
  .notifications {
    padding: 0 7px;
    color: #fff;
    background: #ed7a53;
    border-radius: 2px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    font-weight: 700;
    font-size: 12px;
    font-family: Tahoma;
    position: absolute;
    box-shadow: 0 1px 0 0 rgba(0, 0, 0, .2);
    text-shadow: none;
    margin-left: 5px;
  }

  .notifications.green {
    background: #9fc569;
  }
</style>
<div class="form-inline">
  <div class="row datatables_header">
    <div class="col-md-5 col-xs-12">
      <div class="input-group">
        <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}"
          kl_virtual_keyboard_secure_input="on" placeholder="Search">
        <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input"
            class="data-search btn btn-default" type="button">Go</button></span>
      </div>
    </div>
    <div class="col-md-7 col-xs-12">
      {{--  @if($access->create)
                <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
      @include("perPageBox")
    </div>
  </div>
  <table cellspacing="0" class="responsive table table-striped table-bordered">
    <thead>
      <tr>
        <th width="1%">No.</th>
        <th data="50%" data="1">Project Name</th>
        <th width="15%" width="" data="2">Req: Quantity</th>
        <th width="12%" width="" data="2">Co-ordinator</th>
        <th width="10%" width="" data="3">Accounts Room</th>
        <th width="8%" width="" data="4">Mobilization</th>
    </thead>
    <tfoot>
      <tr>
        <th>No.</th>
        <th>Project Name</th>
        <th>Req. Qty</th>
        <th>Co-ordinator</th>
        <th>Accounts Room</th>
        <th>Mobilization</th>
      </tr>
    </tfoot>
    <tbody>
      <?php $paginate = $ewProjects; ?>
      @if(count($ewProjects)>0)
      @foreach($ewProjects as $ewProject)
      <tr>
        <td>{{$sn++}}</td>
        <td>
          <a href="mobilization/mobilization-room/{{ $ewProject->id }}/{{ $ewProject->project_country_id }}" id="mobilizingRoom" menu-active="mobilization"
            class="ajax-link">
            <span class="txt">{{ $ewProject->project_name}}</span>
            <span class="notifications green">
              {{ @Helper::totalProjectCandidates($ewProject->id) != 0
                ? @Helper::totalProjectCandidates($ewProject->id)
                : '' }}
              </span>
            </a>
          </td>
          <td class="text-center">{{ $ewProject->required_quantity }}</td>
          <td>
            <strong class="text-primary">
              {{ $ewProject->coordinator_name }}
            </strong>
          </td>
          <td>
            @if(@Helper::userModuleAccess('eastWest'))
               <a href="mobilization/accounts-transfer-candidate/{{ $ewProject->id }}" id="" menu-active="mobilization" class="ajax-link hand btn btn-sm btn-default"><i class="fa fa-arrow-right"></i> Accounts Room</a>
            @else
               <a href="#" class="btn btn-sm btn-default" disabled=""></i>Not Allowed</a>
            @endif
          </td>
          <td class="tac">

              @if (@Helper::checkAssignuser($ewProject->id) == "notAllowed")

              <button menu-active="mobilization" class="btn btn-sm btn-default " disabled> Not Allowed</button>

              @else

              <a href="mobilization/mobilization-room/{{ $ewProject->id }}/{{ $ewProject->project_country_id }}" id="mobilizingRoom" menu-active="mobilization"
                class="ajax-link hand btn btn-sm btn-default"><i class="fa fa-arrow-right"></i> Mobilizing Room</a>

                <a href="mobilization/mobilization-activity-room/{{$ewProject->id}}" style="display:none;"
                  id="mobilizeActivityBlade" menu-active="mobilize-activity" class="ajax-link hand btn btn-sm btn-default"><i
                  class="fa fa-arrow-right"></i> Mobilizing Activity Room</a>

                  @endif
          </td>
        </tr>
      @endforeach
      @else
      <tr>
        <td colspan="10" class="emptyMessage">Empty</td>
      </tr>
      @endif
    </tbody>
  </table>
  <div class="row">
    <div class="col-md-12 col-xs-12">
      @include("pagination")
    </div>
  </div>
</div>

