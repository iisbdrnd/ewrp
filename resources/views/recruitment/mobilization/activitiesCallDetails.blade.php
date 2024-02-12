<div class="list-body">
   <ul class="timeline timeline-icons timeline-advanced mb20">
      @forelse($callActivityDetails as $callActivityDetail)
      @if($callActivityDetail->activities_type ==1)
      <li>
         <h5 class="timeline-title"><a class="ajax-link" menu-active="activities/meeting" href="activities/meeting/53">{{
               @Helper::single_mobilization($callActivityDetail->call_type)->name }}</a></h5>
         <span class="timeline-icon"><i class="icomoon-icon-briefcase"></i></span>
         <p class="timeline-content">{{ $callActivityDetail->remarks }}</p>
         <span class="timeline-content">{{ Carbon\Carbon::parse($callActivityDetail->call_date)->format('d-m-y') }}</span>
         <span class="timeline-date">{{ $callActivityDetail->created_at->diffForHumans() }}</span>
      </li>
      @endif
      @empty
      <tr><span class="text-danger">Call info not found!</span></tr>
      @endforelse
   </ul>
</div>