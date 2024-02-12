<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @include("perPageBox")
           <a href="{{ url('recruitment#mobilization/single-candidate/'.$projectId.'/'.$candidateId) }}" class="ajax-link hand btn btn-md btn-default pull-right mr5"><i class="fa fa-arrow-left"></i> Back</a>
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
                        <th data="1" width="1%">No.</th>
                        <th data="2" width="15%">Candidate Name</th>
                        <th data="3" width="10%">Subject</th>
                        <th data="4" width="10%">Note For</th>
                        <th data="5" width="19%">Note Date</th>
                        <th data="6" width="45%">Note</th>
                       
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Candidate Name</th>
                        <th>Note Subject</th>
                        <th>Note For</th>
                        <th>Note Date</th>
                        <th>Note</th>
                       
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $allNotes; ?>
                @if(count($allNotes)>0)  
                @foreach($allNotes as $allNote)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{Helper::single_candidate($allNote->ew_candidatescv_id)->full_name}}</td>
                        <td>{{$allNote->note_subject}}</td>
                        <td>
                        	{{ $allNote->call_type == 1?
                                 'E-Wakala'            :($allNote->call_type == 2?
                                 'GAMCA'               :($allNote->call_type == 3?
                                 'Online'              :($allNote->call_type == 4?
                                 'SELF'                :($allNote->call_type == 5?
                                 'MOFA'                :($allNote->call_type == 6?
                                 'Flip Card Received'  :($allNote->call_type == 7?
                                 'PCC'                 :($allNote->call_type == 8?
                                 'Embassy Submission'  :($allNote->call_type == 9?
                                 'Document Sent Online':($allNote->call_type == 10?
                                 'Document Sent Print':''))))))))) }}
                        </td>
                        <td>{{$allNote->call_date}}</td>
                        <td>{{$allNote->remarks}}</td>
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
