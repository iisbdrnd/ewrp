@extends('web.layouts.subDefault')
@section('content')
<style>
    iframe{
        display: none;
    }
    .portfolio-item .portfolio-caption{
        opacity: .5;
    }
    .portfolio-caption-content{
        opacity: .5;
    }
    .portfolio-item-inner{
        height: 250px;
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
                            <h2>Albums</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="portfolio-area inner-padding">
    <div class="container-fluid">
        <div class="row foo" data-sr="enter" data-sr-id="30" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                @if (count($albums)>0)
                    @foreach ($albums as $album)
                    
                    <div class="portfolio-item isotope" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            @if (!empty($album->gallery_thumb))
                                <img src="{{asset('public/uploads/gallery/thumb/'.$album->gallery_thumb)}}" alt="responsive img">
                            @else
                                <img src="{{asset('public/web/img/work-1.png')}}" alt="responsive img">
                            @endif
                            <a href="{{route('galleryPhotos', $album->id)}}">
                                <div class="portfolio-caption">
                                    <div class="portfolio-caption-content">
                                        <p style="color: white; font-size: 17px; margin: 0 0 3px; font-weight: 0;">
                                            {{$album->gallery_name}}
                                        </p>
                                        {{-- <span style="color: rgb(161, 161, 161);"> {{date('d M Y', strtotime($album->created_at))}} </span> --}}
                                        {{-- <a href="#">Category</a> --}}
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif
                
            </div>
        </div>
    </div>
</div>



@endSection