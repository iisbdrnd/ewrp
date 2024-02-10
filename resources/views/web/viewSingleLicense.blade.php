<div class="blog-masonary">
    @if (count($licenses)>0)
        @foreach ($licenses as $license)
            <div class="post-grid2" style="left: 0px; top: 0px;">
                <div class="post-row foo" data-sr="enter" data-sr-id="6" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                    <div class="post-body">
                        <div class="section-title-area-1 foo" style="opacity: 1 !important;">
                            <a><i class="fa fa-check-circle"></i> {{$license->title}}</a>
                        </div>
                        <p class="post-text">
                            {{strip_tags($license->description)}}
                        </p>
                    </div>
                    {{-- <div class="post-footer">
                        <a href="#" class="btn btn-default btn-post" role="button">READ MORE<i class="fa fa-angle-double-right"></i></a>
                    </div> --}}
                    <div class="col-md-2" style="padding: 0;">
                        <a href="{{url('public/uploads/license_attachments/'.$license->attachment_name)}}" target="_blank" class="btn btn-success btn-sm-outline viewJobs" style="background: #08ada7" role="button">View File</a>
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