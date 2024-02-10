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
                            <h2>Organizational Chart</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<!-- Team Section -->
<div class="team-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Board of Directors</h2>
                    <p>
                        At EWPCI we gratefully acknowledge the dedication of our Board of Directors. EWPCI leaders are passionate advocates of the EWPCI vision and work on behalf of the global network to achieve SITE's strategic goals.
                        <br>
                        As of July 2021, the Board of Directors comprises the following members:
                    </p>
                </div>
            </div>
        </div>
        @if (count($managementTeams) > 0)
        <div class="row">
            @foreach($managementTeams as $key=>$managementTeam)
                @if ($key == 0)
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-4">
                        <div class="team-member foo" data-sr='enter'>
                            <div class="team-header">
                                <img src="{{asset('public/uploads/managementTeam/'.$managementTeam->image)}}" alt="responsive img">
                            </div>
                            <div class="team-body">
                                <div class="short-info" style="padding: 50px 0px 78px;">
                                    <h3 class="member-name">
                                        {{$managementTeam->name}}
                                    </h3>
                                    <h5 class="designation" style="font-size: 11px;">{{$managementTeam->designation}}</h5>
                                </div>
                                <p style="font-size: 10px;">
                                    Mobile: {{$managementTeam->phone}} <br>
                                    Email : {{$managementTeam->email}}
                                </p>
                                {{-- <ul class="social-profile">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                </ul> --}}
                            </div>
                            <div class="team-footer">
                                <h4 class="short-designation">
                                    {{$managementTeam->name}} <br>
                                    <small>{{$managementTeam->designation}}</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                
                    <div class="col-xs-12 col-sm-6 {{count($managementTeams) > 4 ? 'col-md-3' : 'col-md-4'}}">
                        <div class="team-member foo" data-sr='enter'>
                            <div class="team-header">
                                <img src="{{asset('public/uploads/managementTeam/'.$managementTeam->image)}}" alt="responsive img">
                            </div>
                            <div class="team-body">
                                <div class="short-info" style="padding: 50px 0px 78px;">
                                    <h3 class="member-name">{{$managementTeam->name}}</h3>
                                    <h5 class="designation" style="font-size: 11px;">{{$managementTeam->designation}}</h5>
                                </div>
                                <p style="font-size: 10px;">
                                    Mobile: {{$managementTeam->phone}} <br>
                                    Email : {{$managementTeam->email}}
                                </p>
                            </div>
                            <div class="team-footer">
                                <h4 class="short-designation">
                                    {{$managementTeam->name}}<br>
                                    <small>{{$managementTeam->designation}}</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                
                @endif
            @endforeach
        </div>
        @endif
    </div>
</div>
<div class="team-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Company Organogram</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <img src="{{asset('public/web/img/organogram.jpg')}}" alt="">
            </div>
        </div>
    </div>
</div>
<!-- End Team Section -->
@endSection