<div class="form-inline">
  <div class="row datatables_header">
    <div class="col-md-3 col-xs-12">
      <div class="input-group">
        <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}"
          kl_virtual_keyboard_secure_input="on" placeholder="Search">
        <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input"
            class="data-search btn btn-default" type="button">Go</button></span>
      </div>
    </div>
    <div class="col-md-3 col-xs-12 pull-left">
      <button class="btn btn-default btn-sm">Total CV <strong class="text-primary">({{ $totalCV }})</strong></button>
    </div>
    <div class="col-md-6 col-xs-12">
      @if($access->create)
      <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i
          class="glyphicon glyphicon-plus mr5"></i>Add New</button>
      @endif
      @include("perPageBox")
    </div>
  </div>
</div>
<div id=myTabContent2 class=tab-content>
  <div class="tab-pane fade active in" id=home2>
    <div class="form-inline">
      <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
          <tr>
            <th width="1%">SL.No.</th>
            <th width="10%">Full Name</th>
            <th width="10%">Surname</th>
            <th width="10%">Father Name</th>
            <th width="10%">Spouse Name</th>
            <th width="10%">Emergency Contact</th>
            <th width="10%">National ID</th>
            <th width="10%">Passport No</th>
            <th width="10%">Passport Expire Date</th>
            <th width="10%">Telephone No</th>
            @if($access->edit || $access->destroy)
            <th width="9%">Action</th>
            @endif
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>SL.No.</th>
           <th>Full Name</th>
           <th>Surname</th>
           <th>Father Name</th>
           <th>Spouse Name</th>
           <th>Emergency Contact</th>
           <th>National ID</th>
           <th>Passport No</th>
           <th>Passport Expire Date</th>
           <th>Telephone No</th>
            @if($access->edit || $access->destroy)
            <th>Action</th>
            @endif
          </tr>
        </tfoot>
        <tbody class="text-center">
          <?php $paginate = $passportForms; ?>
          @if(count($passportForms)>0)
          @foreach($passportForms as $passportForm)
          <tr>
            <td>{{$sn++}}</td>
            <td>{{ $passportForm->full_name }}</td>
            <td>{{ $passportForm->surname }}</td>
            <td>{{ $passportForm->father_name }}</td>
            <td>{{ $passportForm->spouse_name }}</td>
            <td>{{ $passportForm->emergency_contact }}</td>
            <td>{{ $passportForm->national_id }}</td>
            <td>
              {{ $passportForm->passport_no }}
              {!! @Helper::passportExpiredInForm($passportForm->id) !!}
            </td>
            <td>
              {{ Carbon\Carbon::parse($passportForm->passport_expired_date)->format('d-m-Y') }}
            </td>
            <td>{{ $passportForm->telephone_no }}</td>
            <td class="text-center">
              @if ($access->edit || $access->destroy)

              @if ($access->edit)
              
              <i class="fa fa-edit" title="Update" id="edit" data="{{$passportForm->id}}"></i>

              @endif

              @if($access->destroy)

              <i class="fa fa-trash-o" id="delete" data="{{$passportForm->id}}"></i>

              @endif
            </td>
            @endif
          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="13" class="emptyMessage">Empty</td>
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
<script type="text/javascript">
  $(document).ready(function () {
    $(".preview_button").on('click', function (e) {
      e.preventDefault();
      var candidate_id = $(this).val();
      // alert(project);
      var width       = $(document).width();
      var height      = $(document).height();
      var previewType = 1;
      var url         = "{{ url('recruitment/cv-print-preview') }}?candidate_id = " + candidate_id;
      var myWindow    = window.open(url, "", "width                             = " + width + ", height = " + height);
      $('.preview_button').removeAttr('disabled').removeClass('disabled');
    });
  });
</script>