<table class="table">
                           <thead>
                              @forelse($callActivityDetails as $callActivityDetail)
                              @if($callActivityDetail->activities_type==2)
                              <tr><td style="background: #eee;" colspan="6">{{ $callActivityDetail->created_at->diffForHumans() }}</td></tr>
                              <tr>
                                 <td width="10%"><strong>Call For</strong></td>
                                 <td width="50%"><b>: </b>{{ $callActivityDetail->call_type == 1?
                                 'E-Wakala'            :($callActivityDetail->call_type == 2?
                                 'GAMCA'               :($callActivityDetail->call_type == 3?
                                 'Online'              :($callActivityDetail->call_type == 4?
                                 'SELF'                :($callActivityDetail->call_type == 5?
                                 'MOFA'                :($callActivityDetail->call_type == 6?
                                 'Flip Card Received'  :($callActivityDetail->call_type == 7?
                                 'PCC'                 :($callActivityDetail->call_type == 8?
                                 'Embassy Submission'  :($callActivityDetail->call_type == 9?
                                 'Document Sent Online':($callActivityDetail->call_type == 10?
                                 'Document Sent Print':''))))))))) }}
                                 </td>
                              </tr>
                              <tr>
                                 <td width="10%"><strong>Call Date</strong></td>
                                 <td width="50%"><b>: </b>{{ $callActivityDetail->call_date  }}</td>
                              </tr>
                              <tr>
                                 <td width="10%"><strong>Remarks</strong></td>
                                 <td width="50%"> {{ $callActivityDetail->remarks }}</td>
                              </tr>
                              @endif
                              @empty
                              <tr><td width="100%" class="text-danger">Call info not found!</td></tr>
                              @endforelse
                           </thead>
                        </table>