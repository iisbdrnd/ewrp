<style>
#panelId .panel-heading .panel-controls .panel-refresh{
    display: none;
}
</style>
<div class="form-inline">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div id="myTabContent2" class="tab-content">
                <div class="tab-pane fade active in" id=home2>
                    <div class="form-inline">
                        <table id="myTable" cellspacing="0" class="responsive table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="1%">No.</th>
                                    <th  data="30%">Candidate Name</th>
                                    <th width="20%" width="" data="1">Father's Name</th>
                                    <th width="20%" width="" data="1">Passport Number</th>
                                    <th width="20%" width="" data="1">Reference</th>
                                    <th width="9%" width="" data="1">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Candidate Name</th>
                                    <th>Father's Name</th>
                                    <th>Passport Number</th>
                                    <th>Reference</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php $paginate = $candidateDetails; $i=0; ?>
                                @forelse($candidateDetails as $candidateDetail)
                                <?php $i++;?>
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                        <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                        menu-active="mobilization" class="ajax-link">{{ $candidateDetail->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ $candidateDetail->father_name }}</td>
                                        <td>{{ $candidateDetail->passport_no }}</td>
                                        <td>{{ Helper::reference($candidateDetail->reference_id)->reference_name }}</td>
                                        <td>
                                        <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                           <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                        <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                           <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                        <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}" 
                                        menu-active="mobilization" class="ajax-link hand btn btn-sm btn-default ">Mobilize Now</a> 
                                        </td>
                                    </tr>
                                    @empty
                                     <tr>
                                        <td style="border:0px;"></td>
                                        <td style="border:0px;"></td>
                                        <td style="border:0px;">Empty</td>
                                        <td style="border:0px;"></td>
                                        <td style="border:0px;"></td>
                                        <td style="border:0px;"></td>
                                    </tr>
                                @endforelse
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
        </div>
    </div>
</div>
<script type="text/javascript">
    

 $(document).ready( function () {
    $('#myTable').DataTable();
} );
$('label input[type=search]').prop('id', 'search');
$('#myTable_filter').append('<label style="margin-left:10px;">Filter: <select style="min-width:200px;" class="form-control select2" name="mobilizeFiltering" id="mobilizeFiltering">'
    +'<option>Select Filtering</option>'
    +'<option {{ $filering== 1?'selected':'' }} value="1">Completed</option>'
    +'<option {{ $filering== 2?'selected':'' }} value="2">Incompleted</option>'
    +'</select></label>');

$("select.select2").select2({
        placeholder: "Select"
    });

</script>

