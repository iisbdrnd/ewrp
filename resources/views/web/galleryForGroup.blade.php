@extends('web.layouts.subDefault')
@section('content')

<section class="">
    <div class="container">
        <div class="section-top-border">
            <h3 style="padding: 5px;">Image Gallery <span class="pull-right"><a href="{{ route('gallery') }}" style="color: #fab700;">Back To Gallery</a></span></h3>
            <div class="row gallery-item">
                @foreach ($galleries as $gallery)
                    <div class="col-md-4 pb-3">
                        <a href="{{url('public/uploads/gallery/'.$gallery->image_thumb)}}" class="img-pop-up"><div class="single-gallery-image" style="background: url({{url('public/uploads/gallery/thumb/'.$gallery->image_thumb)}}"></div></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


@endSection