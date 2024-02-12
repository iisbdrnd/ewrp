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
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
           {{--  @if($access->create)
            <button class="add-btn btn btn-default pull-right btn-sm" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
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
                        <th width="1%">No.</th>
                        <th width="80%" data="1">Project Name</th>
                        <th width="10%">Print Preview</th>
                        {{-- <th width="9%">View Report</th> --}}

                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Project Name</th>
                        <th>Print Preview</th>
                        {{-- <th>View Report</th> --}}
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $projects; ?>
                @if(count($projects)>0)  
                <?php $i=0;?>
                @foreach($projects as $project)
                <?php $i++;?>
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>
                            {{-- reports/candidate-report/{{$project->id}} --}}
                             <a href="#" menu-active="reports" class="ajax-link hand">
                                 <span class="txt">{{ $project->project_name}}</span>
                                <span class="notifications green">
                                {{ @Helper::totalProjectCandidates($project->id) != 0
                                ? @Helper::totalProjectCandidates($project->id)
                                : '' }}
                                </span>
                            </a>
                        </td>
                        <td>
                            <button value="{{ $project->id }}" menu-active="reports" class="btn hand btn-default btn-sm preview_button"><i class="fa fa-print"></i> Print Preview</button>
                        </td>
                        {{-- <td>
                            <a href="reports/candidate-report/{{$project->id}}" 
                            menu-active="reports"  class="ajax-link hand btn btn-sm btn-default ">
                                <i class="fa fa-eye"></i> View Candidate
                            </a>
                        </td> --}}
                        
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
<style>
    .select2{
        margin-top:-10px !important;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
            $(".preview_button").on('click', function(e) {
            e.preventDefault();
            var projectId = $(this).val();
            // alert(project);
            var width = $(document).width();
            var height = $(document).height();
            var previewType = 1;
            var url = "{{ url('recruitment/report-print-preview') }}?projectId="+projectId;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('.preview_button').removeAttr('disabled').removeClass('disabled');
            });
        });

    function plusMinus(i){
        console.log(i);
        //$('#mmm'+i).hide();//removeClass('fa-plus'); 
        // $('#mmm'+i).addClass('fa-minus'); 
        // $(this).html("");
        // $(this).html('<i class="fa fa-minus"></i>');
    }

// $('label input[type=search]').prop('id', 'search');
// $('.col-md-5').append('<label style="margin-left:10px;">Filter: <select style="min-width:200px;" class="form-control select2" name="mobilizeFiltering" id="mobilizeFiltering">'
//     +'<option>Select Country</option>'
//     +'<option {{ @$filering== 1?'selected':'' }} value="1">KATAR</option>'
//     +'<option {{ @$filering== 2?'selected':'' }} value="2">KSA</option>'
//     +'</select></label>');

// $("select.select2").select2({
//         placeholder: "Select"
//     });
</script>
