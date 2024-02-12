<style>.notifications {
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
      @if($access->create)
      <button class="add-btn btn btn-default pull-right btn-sm" modal-size="large" view-type="modal" type="button"
        style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
      @endif
      @include("perPageBox")
    </div>
  </div>
</div>
<div id=myTabContent2 class=tab-content>
  <div class="tab-pane fade active in" id=home2>
    {{-- <h1>{{ $ew_project_id }}</h1> --}}
    <div class="form-inline">
      <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
          <tr>
            <th width="5%">No.</th>
            <th width="25%" data="1">Project Name</th>
            <th width="25%" data="2">Trades</th>
            <th width="10%" data="3">Start Date</th>
            @if($access->edit || $access->destroy)
            <th width="15%" data="4">Assign User</th>
            <th width="5%" data="5">Status</th>
            <th width="10%" data="6">Project Configure</th>
            <th width="5%" data="7">Action</th>
            @endif
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>No.</th>
            <th>Project Name</th>
            <th>Trades</th>
            <th>Start Date</th>
            <th>Assign User</th>
            <th>Status</th>
            <th>Project Configure</th>
            @if($access->edit || $access->destroy)
            <th>Action</th>
            @endif
          </tr>
        </tfoot>
        <tbody>
          <?php $paginate = $ewProjects; ?>
          @if(count($ewProjects)>0)
          @foreach($ewProjects as $ewProject)
          <tr>
            <td>{{$sn++}}</td>
            <td>
              <span class="txt">{{ $ewProject->project_name}}</span>
              <span class="notifications green">
              {{ @Helper::totalProjectCandidates($ewProject->id) != 0
                ? @Helper::totalProjectCandidates($ewProject->id)
                : '' }}
              </span>
            </td>

            <td class="text-center">
              <p style="font-weight: 700;font-size: 14px;margin-top:3%;float: left;"># Require Quantity: {{ $ewProject->required_quantity }}</p>

              <div class="clearfix"></div>

              @if(count($ewProject->agency) > 0)
              {{--     <p style="font-size: 14px;font-weight: 700"><u># Agency</u></p>--}}
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="text-align: center;width: 70%">Agency Name</th>
                    <th style="text-align: center;width: 30%">Agency Qty</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ewProject->agency as $key => $agency)
                  <tr>
                    <td>{{ $agency->agency_name }}</td>
                    <td>{{ $agency->quantity }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

              @else
              <p style="font-size: 14px;font-weight:700;float: left;"># No Agency</p>
              @endif

              <div class="clearfix"></div>

              @if(count($ewProject->trades) > 0)
              {{--             <p style="font-size: 14px;font-weight: 700"><u># Trade</u></p> --}}
              <table class="table table-bordered" style="margin-top: 3%">
                <thead>
                  <tr>
                    <th style="text-align: center;width: 70%">Trade Name</th>
                    <th style="text-align: center;width: 30%">Trade Qty</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($ewProject->trades as $key => $trade)
                  <tr>
                    <td>{{ $trade->trade_name }}</td>
                    <td>{{ $trade->trade_qty }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p style="font-size: 14px;font-weight: 700; float: left;"># No Trade</p>
              @endif
            </td>

            <td>@if($ewProject->start_date != "0000-00-00")
                {{ date('d-m-Y', strtotime($ewProject->start_date)) }}
                @else
                {{ date('d-m-Y', strtotime($ewProject->created_at)) }}
                @endif
            </td>
            <td>
              <button type="button" url="assign-project-user/{{ $ewProject->id }}" view-type="modal"
                class="add-btn btn {{ $ewProject->assign_user != ""
                ?'btn-success'
                : 'btn-default' }}  btn-xs"> <i class="fa fa-plus"></i> {{ $ewProject->assign_user != ""
                ? 'User Assigned'
                : 'Assign User' }}
              </button>
                <p>
                  <strong class="text-primary">{{ @Helper::assignUser($ewProject->coordinator) }}
                    <small class="text-dark" style="font-size:8px;">
                    {{ !empty($ewProject->coordinator)
                    ? 'Co-ordinator'
                    : '' }}
                    </small>
                  </strong>
                </p>

              @if (!empty($ewProject->assign_user))

              <?php $i = 0;?>

              @foreach (json_decode($ewProject->assign_user, true) as $userId)

              <?php $i++;?>
              <p style="font-size:10px"><strong>
                  {{ $i }}. {{ @Helper::assignUser($userId) }}
                </strong>
              </p>

              @endforeach

              @endif

            </td>
            <td>
              <button type="button" 
                url="project-status-form/{{ $ewProject->id }}" 
                view-type="modal" 
                class="add-btn btn btn-xs  
                {{ $ewProject->status == 1
                ? 'btn-success'
                : ($ewProject->status == 2
                ? 'btn-danger'
                : 'btn-default') }}">
                <i class="fa fa-pencil"></i>
                {{ $ewProject->status == 1
                ? 'Status Running'
                : ($ewProject->status == 2
                ? 'Status Close'
                : 'Change Status') }}
              </button>
            </td>
            <td> 

              @if ($ewProject->configuration == 1)

              <a class="btn btn-xs btn-success" href="{{ url('recruitment#configure/'.$ewProject->id) }}">
                <i class="fa fa-cogs"></i> Configured
              </a>

              @else

              <a class="btn btn-xs btn-default" href="{{ url('recruitment#configure/'.$ewProject->id) }}">
                <i class="fa fa-cogs"></i> Configure
              </a>

              @endif

            </td>

            @if($access->edit || $access->destroy)

            <td>

              @if($access->edit)

              <i class="fa fa-edit" view-type="modal" title="Update" id="edit" data="{{$ewProject->id}}"></i>

              @endif

              @if($access->destroy)

              <i class="fa fa-trash-o" id="delete" data="{{$ewProject->id}}"></i>

              @endif

            </td>
            @endif
          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="5" class="emptyMessage">Empty</td>
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
  </div>
</div>