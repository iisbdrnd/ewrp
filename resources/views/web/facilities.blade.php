@extends('web.layouts.subDefault')
@section('content')
<style>
    .section-title-area-1{
        margin: 0px;
    }
    .portfolio-item-inner{
        height: 164px;
    }
</style>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Facilities</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<div class="about-area inner-padding">
    <div class="container">
        {{-- <div class="section-title-area-1" style="padding-top: 10px;">
            <h2 class="section-title">Head Office</h2>
        </div> --}}
        <div class="row">
            <div class="col-sm-12 col-md-12 foo" data-sr='enter'>
                {!! $headOfficeFacility->description !!}
                
            </div>
        </div>
    </div>
</div>

<div class="portfolio-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Head Office</h2>
                    {{-- <p>
                        <i class=""></i>2/B Rupsha Tower, 7 Kemal Ataturk Avenue Banani, Dhaka, Bangladesh
                    </p> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        {{-- <div class="row foo" data-sr="enter" data-sr-id="30" style=""> --}}
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                @if (count($headOfficePhotos)>0)
                    @foreach ($headOfficePhotos as $photo)
                    <div class="portfolio-item isotope {{$photo->slug}}" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            <img src="{{asset('public/uploads/facilityHeadOffice/thumb/'.$photo->image_thumb)}}" alt="responsive img">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="{{asset('public/uploads/facilityHeadOffice/thumb/'.$photo->image_thumb)}}" data-popup="prettyPhoto[img]"><img src="{{asset('public/web/img/zoom.png')}}" alt="responsive img"></a>
                                
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                
            </div>
        {{-- </div> --}}
    </div>
</div>
<div class="about-area inner-padding">
    <div class="container">
        <div class="about-area-inner">
            <div class="row">
                <div class="col-sm-12 col-md-6 foo" data-sr='enter'>
                    <div id="about-silder" class="owl-carousel owl-theme about-slider">
                        <div class="item">
                            <img src="{{asset('public/web/img/trainingFacilities/image1.jpg')}}" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/facility1.jpg')}}" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/facility2.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 foo" data-sr='enter'>
                    <div class="section-title-area-1" style="padding-top: 10px;">
                        <h2 class="section-title">Training Facility</h2>
                    </div>
                    <div class="about-content">
                        <p>
                            @if(count($trainingFacilities)>0)
                                @foreach($trainingFacilities as $trainingFacility)
                                {{$trainingFacility->title}} : {{$trainingFacility->description}} <br>
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Portfolio Section -->
<div class="portfolio-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Training Center</h2>
                    {{-- <p>
                        <i class=""></i>2/B Rupsha Tower, 7 Kemal Ataturk Avenue Banani, Dhaka, Bangladesh
                    </p> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        {{-- <div class="row foo" data-sr="enter" data-sr-id="30" style=""> --}}
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                @if (count($trainingFacilityPhotos)>0)
                    @foreach ($trainingFacilityPhotos as $photo)
                    <div class="portfolio-item isotope {{$photo->slug}}" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            <img src="{{asset('public/uploads/trainingFacilities/thumb/'.$photo->image_thumb)}}" alt="responsive img">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="{{asset('public/uploads/trainingFacilities/thumb/'.$photo->image_thumb)}}" data-popup="prettyPhoto[img]"><img src="{{asset('public/web/img/zoom.png')}}" alt="responsive img"></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                
            </div>
        {{-- </div> --}}
    </div>
</div>
<!-- End Portfolio Section -->
@endSection