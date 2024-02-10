@extends('web.layouts.default')
@section('content')
<style>
    .about-content p{
        margin-bottom: 8px;
        line-height: 22px;
    }
    .feature-content h4{
        margin-bottom: 13px;
    }
    .single-feature{
        margin-bottom: 6px;
    }
    .hero-caption-inner h1{
        font-size: 48px !important;
    }
    .modal-dialog{
        margin-top: 170px;
    }
</style>
<!-- Hero Section -->
<div class="hero-area" id="home">
    <div id="hero-slider-screen" class="owl-carousel owl-theme hero-slider-inner">
        @if(count($banners)>0)  
        @foreach($banners as $banner)
        <div class="item" style="opacity: 1;">
            <img src="{{asset('public/uploads/banner/'.$banner->banner)}}" alt="responsive img">
            <div class="hero-caption">
                <div class="hero-caption-inner">
                    <h3>{{$banner->mini_title}}</h3>
                    <h1>{{$banner->title}}</h1>
                    {{-- <p>Lorem consectetur adipiscing elit, sed do eiusmod tempor dolor sit amet contetur adipiscing elit, sed do eiusmod tempor incididunt </p> --}}
                    
                    {!! $banner->description !!}
                    
                    <a href="{{$banner->btn_link}}" class="btn btn-default btn-sm-outline" role="button">{{$banner->btn_text}}</a>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        {{-- <div class="item">
            <img src="{{asset('public/web/img/banner-2.png')}}" alt="responsive img">
            <div class="hero-caption">
                <div class="hero-caption-inner">
                    <h3>WE PROVIDE BEST SOLUTIONS</h3>
                    <h1>FOR YOUR BUSINESS</h1>
                    <p>Lorem consectetur adipiscing elit, sed do eiusmod tempor dolor sit amet contetur adipiscing elit, sed do eiusmod tempor incididunt </p>
                    <a href="#" class="btn btn-default btn-sm-outline" role="button">DISCOVER MORE</a>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="{{asset('public/web/img/banner-3.png')}}" alt="responsive img">
            <div class="hero-caption">
                <div class="hero-caption-inner">
                    <h3>WE PROVIDE BEST SOLUTIONS</h3>
                    <h1>FOR YOUR BUSINESS</h1>
                    <p>Lorem consectetur adipiscing elit, sed do eiusmod tempor dolor sit amet contetur adipiscing elit, sed do eiusmod tempor incididunt </p>
                    <a href="#" class="btn btn-default btn-sm-outline" role="button">DISCOVER MORE</a>
                </div>
            </div>
        </div> --}}
    </div>
</div>
<!-- End Hero Section -->
<!-- About Section -->
<div class="about-area inner-padding">
    <div class="container">
        <div class="about-area-inner">
            <div class="row">
                <div class="col-sm-12 col-md-6 foo" data-sr='enter'>
                    <div id="about-silder" class="owl-carousel owl-theme about-slider">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/1.jpg')}}" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/2.jpg')}}" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/3.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 foo" data-sr='enter'>
                    <div class="section-title-area-1">
                        <h2 class="section-title" style="font-size: 25px;">EW Human Resource Center Ltd.</h2>
                    </div>
                    <div class="about-content">
                        <p style="text-align: justify; ">
                            Established in 2006 East west Human Resource Center Ltd, which is based in Dhaka, Bangladesh, provides manpower to clients of EWHRCL. East West recognizes the scarcity of skilled workers and the growing demand by employers for Skilled categories. East west has designed solutions targeting specific client requirements and is taking pro-active steps to recruiting.
                        </p>
                        <p style="text-align: justify; ">
                            East West Human Resource Center Ltd, recognizes the global need for employment. We want to provide our clients with world-class, personalized service and to Bangladeshis the opportunity to work overseas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End About Section -->
<!-- Fun Factor Section -->
<div class="fun-fact-area inner-padding">
    <div class="container">
        <div class="row">
            @foreach ($counters as $counter)  
            @if ($counter->id == 1)
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="fun-fact-item foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/counter/Workers-Deployed.png')}}" width="40" alt="responsive img">
                    <span class="counter">{{$counter->counter}}</span>
                    <p>{{$counter->title}}</p>
                </div>
            </div>
            @endif
            @if ($counter->id == 2)
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="fun-fact-item foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/counter/Countries.png')}}" width="40" alt="responsive img">
                    <span class="counter">{{$counter->counter}}</span>
                    <p>{{$counter->title}}</p>
                </div>
            </div>
            @endif
            @if ($counter->id == 3)
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="fun-fact-item foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/counter/Categories.png')}}" width="40" alt="responsive img">
                    <span class="counter">{{$counter->counter}}</span>
                    <p>{{$counter->title}}</p>
                </div>
            </div>
            @endif
            @if ($counter->id == 4)
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="fun-fact-item foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/counter/Jobs-Completed.png')}}" width="40" alt="responsive img">
                    <span class="counter">{{$counter->counter}}</span>
                    <p>{{$counter->title}}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
<!-- End Fun Factor Section -->
<!-- Feature Section -->
<div class="feature-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="feature-img foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/homePage/highlight.jpg')}}" alt="responsive img">
                </div>
            </div>
            <div class="col-sm-12 col-md-8 foo" data-sr='enter'>
                <div class="section-title-area-2" style="margin: 0px 0px 26px;">
                    <h2 class="section-title">Highlights</h2>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="feature-item">
                    <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-pencil"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Training Facilities</h4>
                            <p>
                                We recognize the increased global need of skilled workers.
                                <a data-toggle="modal" data-target="#trainingFacilities" style="cursor: pointer;">Read More...</a>
                            </p>
                        </div>
                    </div>
                    <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-crop"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Information System</h4>
                            <p>The company’s information system is the key to providing fast, accurate <a data-toggle="modal" data-target="#informationSystem" style="cursor: pointer;">Read More...</a></p>
                        </div>
                    </div>
                    <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-object-group"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Interview Management System (IMS)</h4>
                            <p>EWHRCL uses its own purpose built application for Interview and Selection. <a data-toggle="modal" data-target="#interviewManagementSystem" style="cursor: pointer;">Read More...</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="feature-item">
                    <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-file-code-o"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Internet Video Conferencing</h4>
                            <p>Employers may conduct their interviews from the comfort of their own offices <a data-toggle="modal" data-target="#internetVideoConferencing" style="cursor: pointer;">Read More...</a></p>
                        </div>
                    </div>
                    <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-desktop"></i><i class="fa fa-tablet inner-icon"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Recruitment Management System (RMS)</h4>
                            <p>The RMS is a fully integrated database system lined with ‘Interview Management System’ (IMS). <a data-toggle="modal" data-target="#recruitmentManagementSystem" style="cursor: pointer;">Read More...</a></p>
                        </div>
                    </div>
                    {{-- <div class="single-feature foo" data-sr='enter'>
                        <div class="feature-icon"><i class="fa fa-code"></i></div>
                        <div class="feature-content">
                            <h4 class="feature-title">Clean Code</h4>
                            <p>Sed do eiusmod tempor dolor sit amet, consectetur adipiscing elitsed do</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals of Highlight Section --}}
<div class="modal fade" id="trainingFacilities" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                            <h2 class="section-title">Training Facilities</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                We recognize the increased global need of skilled workers; East West is now developing the resource of qualified candidates by training them in our own fully equipped training facilities.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- End About Section -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="informationSystem" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                            <h2 class="section-title">Information System</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The company’s information system is the key to providing fast, accurate and
                                reliable service. 
                                <br>
                                <br>
                                Its network of over 35 computers is protected by a comprehensive virus
                                defense system, spam filter and firewall.
                                <br>
                                <br>
                                Its high speed server allows jobseekers to log in, post CVs and the company’s
                                Mobilization team to download, screen and upload interview data at the same
                                time.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- End About Section -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="interviewManagementSystem" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                            <h2 class="section-title">Interview Management System (IMS)</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL uses its own purpose built application for Interview and Selection. The IMS
                                automatically generate reports at the end of each interview session with all
                                particulars including candidate details, grade achieved, offered salary, etc. and can
                                hand over the report to the delegates right after the interview session.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- End About Section -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="internetVideoConferencing" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                            <h2 class="section-title">Internet Video Conferencing</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                Employers may conduct their interviews from the comfort
                                of their own offices in their home countries via Internet
                                video conferencing using Skype.
                                <br>
                                <br>
                                We have served several clients who conducted their interview through
                                Skype and selected a number of Engineers and Technicians.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- End About Section -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="recruitmentManagementSystem" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                            <h2 class="section-title">Recruitment Management System (RMS)</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The RMS is a fully integrated database system lined with ‘Interview Management
                                System’ (IMS). This system manages information from all stages of the recruitment
                                process, from recruitment to pre-screening, processing, medical fitness status to
                                deployment. Utilizing the RMS, the processing time has been dramatically cut down.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- End About Section -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- End Feature Section -->
<!-- Working Policy Section -->
<div class="working-policy-area inner-padding">
    <div class="container">
        <div class="working-policy-inner" style="padding-top: 62px;">
            <div class="row">
                <div class="col-sm-12 col-md-1 foo" data-sr='enter' style="margin-top: 80px;">
                    <div class="section-title-area-3">
                        <h2 class="section-title">OUR SERVICE CHAIN</h2>
                        <p>
                            Our Service Chain is dedicated to providing the best cumulative selection process, training and other accommodation facilities to ensure a healthy and highly productive work force.
                        </p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            {{-- <div class="working-policy-list foo" data-sr='enter'>
                                <div class="policy-icon">
                                    <p class="policy-number">1</p><i class="fa fa-handshake-o"></i></div>
                                <div class="policy-short">
                                    <h4 class="policy-title">Meet</h4>
                                    <p>Sed do eiusmod tempor dolor sit amet consectetur.</p>
                                </div>
                            </div> --}}
                            <img src="{{asset('public/web/img/service-chain.png')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-2 col-md-2 col-md-offset-5" style="margin-top: 20px;">
                    <a href="{{ route('services') }}" class="btn btn-default btn-sm-outline" style="color:#07aca7 !important; border-color: #07aca7;" role="button">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Working Policy Section -->

<!-- Blog Section -->
{{-- <div class="blog-area inner-padding2">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">LATEST JOB</h2>
                    <p>
                        Latest Job" kete " Jobs Added Recently" dao and nicher kotha gula fele dao
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @if (count($jobs) > 0)
                @foreach ($jobs as $job)
                    <div class="col-sm-12 col-md-4">
                        <div class="post-row foo" data-sr='enter'>
                            <div class="post-body">
                                <div class="post-caption">
                                    <h2 class="post-heading"><a href="#">{{$job->title}}</a></h2>
                                    <div class="post-Mega">{{$job->principal}}</div>
                                    <div class="post-sticker"><small>{{date('d', strtotime($job->created_at))}}</small>
                                        <p class="month">{{date('M', strtotime($job->created_at))}}</p>
                                    </div>
                                </div>
                                <p class="post-text">
                                    {{ Str::words(strip_tags($job->job_description), 30,'....')  }}
                                </p>
                            </div>
                            <div class="post-footer">
                                <a href="{{route('jobDetails', $job->id)}}" class="btn btn-default btn-post" role="button">READ MORE<i class="fa fa-angle-double-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div> --}}
<!-- End Blog Section -->
<!-- Gallery Section -->
{{-- <div class="portfolio-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 foo" data-sr='enter'>
                <div class="section-title-area-4">
                    <h2 class="section-title" style="opacity: 1;">GALLERY</h2>
                    <p>Lorem provide best service consectetur adipiscing elit, sed do eiusmod tempor dolor sit amet consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et lor gna aliqua. Ut minim </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row foo" data-sr='enter'>
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                @if (count($galleryPhotos)>0)
                    @foreach ($galleryPhotos as $photo)
                    <div class="portfolio-item isotope {{$photo->slug}}" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner" style="height: 250px;">
                            <img src="{{asset('public/uploads/gallery/thumb/'.$photo->image_thumb)}}" alt="responsive img">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="{{asset('public/uploads/gallery/thumb/'.$photo->image_thumb)}}" data-popup="prettyPhoto[img]"><img src="{{asset('public/web/img/zoom.png')}}" alt="responsive img"></a>
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p style="font-size: 25px; text-align: center;">
                        No Image Found
                    </p>
                @endif
                
            </div>
        </div>
    </div>
</div> --}}
<!-- End Gallery Section -->
<!-- Clients Section -->
@if (count($clients)>0)
<div class="client-area inner-padding foo" data-sr='enter'>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="clients-slider" class="owl-carousel owl-theme clients-carousel">
                    @foreach ($clients as $client)
                        <div class="item" style="padding: 30px 0; background: #fff;">
                            <img src="{{asset('public/uploads/customers/'.$client->image)}}" style="width: 140px; " alt="responsive img">
                        </div>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- End Clients Section -->

@endSection