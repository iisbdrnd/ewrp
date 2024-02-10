@extends('web.layouts.subDefault')
@section('content')
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Job Details</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Post Details Section -->
<div class="blog-area inner-padding7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <div class="single-post-row foo" data-sr='enter'>
                    {{-- <div class="single-post-header">
                        <div class="single-post-feature">
                            <img src="img/single-post-feature-1.png" alt="responsive img">
                        </div>
                    </div> --}}
                    <div class="single-post-body">
                        <div class="single-post-caption">
                            <h2 class="single-post-heading"><a style="cursor: pointer;">{{$job->title}}</a></h2>
                            <div class="single-post-meta">{{$job->principal}}</div>
                            <div class="single-post-sticker"><small>{{date('d', strtotime($job->created_at))}}</small>
                                <p class="month">{{date('M', strtotime($job->created_at))}}</p>
                            </div>
                        </div>
                        <div class="">
                            <h2 style="padding-bottom: 10px;">Job Detail</h2>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Offerd Salary</b> <br> {{!empty($job->salary) ? $job->salary : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Gender</b> <br> {{!empty($job->gender) ? $job->gender : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Experience</b> <br> {{!empty($job->experience) ? $job->experience : 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Job Type</b> <br> {{!empty($job->job_type) ? $job->job_type : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Country</b> <br> {{!empty($job->country) ? $job->country : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Education</b> <br> {{!empty($job->education) ? $job->education : 'N/A'}}
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Age</b> <br> {{!empty($job->age_from && $job->age_to) ? $job->age_from.' - '.$job->age_to : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Religion</b> <br> {{!empty($job->religion) ? $job->religion : 'N/A'}}
                                    </p>
                                    <p>
                                        <b>Duration</b> <br> {{!empty($job->duration) ? $job->duration : 'N/A'}}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <h2 style="padding: 10px 0;">Job Description</h2>
                        <p class="post-text">
                            {{ strip_tags($job->job_description)  }}
                            <br>
                            <br>
                            <span style="font-weight: 600;">Deadline : {{date('d M Y', strtotime($job->deadline))}}</span>
                        </p>
                    </div>
                </div>
            </div>
            {{-- <div class="col-sm-12 col-md-3">
                <div class="sidebar sidebar-right">
                    <div class="widget-area foo" data-sr='enter'>
                        <div class="widget-header">
                            <h3 class="widget-title">Categories</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="categories-list">
                                <li><a href="#">Business<span>(15)</span></a></li>
                                <li><a href="#sublist" data-toggle="collapse">Corporate<span>(12)</span></a>
                                    <ul id="sublist" class="sub-list collapse">
                                        <li><a href="#">Design<span>(25)</span></a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Creative<span>(15)</span></a></li>
                                <li><a href="#">Technology<span>(18)</span></a></li>
                                <li><a href="#">Development<span>(20)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-area foo" data-sr='enter'>
                        <div class="widget-header">
                            <h3 class="widget-title">Recent Post</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul>
                                <li class="post-item">
                                    <div class="recent-post-feature">
                                        <img src="img/recent-post-1.png" class="img-responsive" alt="responsive item">
                                    </div>
                                    <div class="post-contant">
                                        <h2 class="recent-post-title"><a href="#">Business Ideas</a></h2>
                                        <div class="widget-post-meta"><a href="#">Ronchi / 06 Jun, 2016</a></div>
                                        <p>Lorem must explain to ten how mistakenea </p>
                                    </div>
                                </li>
                                <li class="post-item">
                                    <div class="recent-post-feature">
                                        <img src="img/recent-post-2.png" class="img-responsive" alt="responsive item">
                                    </div>
                                    <div class="post-contant">
                                        <h2 class="recent-post-title"><a href="#">Design Trends</a></h2>
                                        <div class="widget-post-meta"><a href="#">Silvia / 10 Jun, 2016</a></div>
                                        <p>Lorem must explain to ten how mistakenea </p>
                                    </div>
                                </li>
                                <li class="post-item">
                                    <div class="recent-post-feature">
                                        <img src="img/recent-post-3.png" class="img-responsive" alt="responsive item">
                                    </div>
                                    <div class="post-contant">
                                        <h2 class="recent-post-title"><a href="#">Development Tips</a></h2>
                                        <div class="widget-post-meta"><a href="#">Silvia / 10 Jun, 2016</a></div>
                                        <p>Lorem must explain to ten how mistakenea </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-area foo" data-sr='enter'>
                        <div class="widget-header">
                            <h3 class="widget-title">Archive</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="archive-list">
                                <li><a href="#">Cooperations<span>(1)</span></a></li>
                                <li><a href="#">Design<span>(3)</span></a></li>
                                <li><a href="#">Events & Festivals<span>(3)</span></a></li>
                                <li><a href="#">Links<span>(1)</span></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget-area foo" data-sr='enter'>
                        <div class="widget-header">
                            <h3 class="widget-title">Latest Tweets</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="tweets-list">
                                <li><a href="#">@Lorem ipsum </a>
                                    <p>dolor sit amet, costetur adipiscing elit, sed do eiusmod tempor </p>
                                    <div class="tweets-meta">Ronchi / 3 hour ago</div>
                                </li>
                                <li><a href="#">@Lorem ipsum </a>
                                    <p>dolor sit amet, costetur adipiscing elit, sed do eiusmod tempor </p>
                                    <div class="tweets-meta">Ronchi / 3 hour ago</div>
                                </li>
                                <li><a href="#">@Lorem ipsum </a>
                                    <p>dolor sit amet, costetur adipiscing elit, sed do eiusmod tempor </p>
                                    <div class="tweets-meta">Ronchi / 3 hour ago</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-widget foo" data-sr='enter'>
                        <div class="widget-header">
                            <h3 class="widget-title">Tag</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="tag-cloud">
                                <li><a href="#">Corporate</a></li>
                                <li><a href="#">Business</a></li>
                                <li><a href="#">UX</a></li>
                                <li><a href="#">Web</a></li>
                                <li><a href="#">Creative</a></li>
                                <li><a href="#">Photoshop</a></li>
                                <li><a href="#">Minimal</a></li>
                                <li><a href="#">Development</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
<!-- End Post Details Section -->

@endSection