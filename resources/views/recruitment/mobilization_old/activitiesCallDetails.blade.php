<table id="table" class="table">
<thead>
   <td><div class="list-body"><ul class="timeline timeline-icons timeline-advanced mb20"><li><h5 class="timeline-title"><a class="ajax-link" menu-active="activities/note" href="activities/note/35">test</a></h5><span class="timeline-content"><p><span class="st">The <em>American Broadcasting Company</em> (<em>ABC</em>) is...</span><span class="timeline-icon"><i class="icomoon-icon-notebook"></i></span> <span class="timeline-date">2018-04-24 12:10:13</span></p></span></li><li><h5 class="timeline-title"><a class="ajax-link" menu-active="activities/meeting" href="activities/meeting/53">rtter</a></h5><span class="timeline-content">rterter</span><span class="timeline-icon"><i class="icomoon-icon-briefcase"></i></span> <span class="timeline-date">2018-12-18 00:00:00</span></li></ul></div></td>
   @forelse($callActivityDetails as $callActivityDetail)
    @if($callActivityDetail->activities_type ==1)
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