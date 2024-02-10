<div class="blog-masonary">
    @if (count($jobs)>0)
        @foreach ($jobs as $job)
            <div class="single-service-inner" style="margin-bottom: 20px;">
                <div class="row" style="padding-left: 10px; padding-right: 10px;">
                    <div class="col-xs-12 col-sm-12 col-md-9 foo abc-left"  data-sr="enter" data-sr-id="1">
                        <div class="section-title-area-1 foo" style="opacity: 1 !important;">
                            <a><i class="fa fa-check-circle"></i> {{$job->principal}}</a>
                            <p class="section-title">{{$job->title}}</p>
                        </div>
                        <div class="single-service-content foo" data-sr="enter" data-sr-id="4">
                            {{ Str::words(strip_tags($job->job_description), 30,'....')  }}
                        </div>
                        <div class="deadline">
                            Deadline : {{date('d M Y', strtotime($job->deadline))}}
                        </div>
                        <div class="job-attachment">
                            <ul class="file-ul">
                                @foreach ($job->attachments as $attachment)
                                    <li class="file-list">
                                        <a href="{{url('public/uploads/job_opening_attachments/'.$attachment->attachment_name)}}" target="_blank">
                                            <div class="attachment-img">
                                                <img src="{{ Helper::getFileThumb($attachment->attachment_name) }}" alt="">
                                            </div>
                                            <span class="attachment-title">{{ $attachment->attachment_name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">
                        <div class="section-title-area-1 foo abc" data-sr="enter" data-sr-id="3" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                            <table>
                                <tr>
                                    <td><i class="fa fa-dollar" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{!empty($job->salary) ? $job->salary : 'N/A'}}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-globe" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{$job->country}}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-folder" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{$job->job_type}}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-clock-o" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{!empty($job->duration) ? $job->duration : 'N/A'}}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-heart" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{$job->age_from.' - '.$job->age_to}}</span></td>
                                </tr>
                                <tr>
                                    <td><i class="fa fa-venus-mars" style="color: green;"></i></td>
                                    <td><span class="text-bold">{{$job->gender}}</span></td>
                                </tr>
                            </table>
                            <a href="{{route('jobDetails', $job->id)}}" class="btn btn-success btn-sm-outline viewJobs" style="background: #08ada7" role="button">View Job</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p style="text-align: center; font-size: 20px;">
            No Job Found
        </p>
    @endif

</div>