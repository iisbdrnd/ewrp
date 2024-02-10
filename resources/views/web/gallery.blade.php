@extends('web.layouts.subDefault')
@section('content')
<style>
    iframe{
        display: none;
    }
    .img-view{
        /* z-index: -1!important; */
        height: 100%!important;
        /* min-width: 100%!important; */
        /* min-height: 100%; */
        /* transform: translate(-50%, -50%); */
        object-fit: cover!important;
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
                            <h2>Gallery</h2>
                            
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
            <p class="text-center">
                <span style="font-size: 25px; font-weight: 600;">{{$album->gallery_name}}</span> <br>
                {{$album->description}}
            </p>
            {{-- <p class="text-center"></p> --}}
            <a href="{{route('galleryAlbum')}}" style="text-align: right; padding-left: 10px; font-size: 14px">Back To Gallery</a>
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px; margin-top: 16px;">
                @if (count($galleryPhotos)>0)
                    @foreach ($galleryPhotos as $photo)
                    <div class="portfolio-item isotope {{$photo->slug}}" style="position: absolute; left: 395px; top: 0px; height: 240px;">
                        <div class="portfolio-item-inner">
                            <img src="{{asset('public/uploads/gallery/thumb/'.$photo->image_thumb)}}" alt="responsive img" class="img-view">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="{{asset('public/uploads/gallery/thumb/'.$photo->image_thumb)}}" data-popup="prettyPhoto[img]"><img src="{{asset('public/web/img/zoom.png')}}" alt="responsive img"></a>
                                {{-- <div class="portfolio-caption-content">
                                    <h4><a href="#">Project Name</a></h4>
                                    <a href="#">Category</a>
                                </div> --}}
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
</div>



@endSection