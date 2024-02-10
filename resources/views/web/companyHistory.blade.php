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
                            <h2>Company History</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="feature-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="feature-img foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/img.png')}}" alt="responsive img">
                </div>
            </div>
            <div class="col-sm-12 col-md-8" data-sr='enter' style="text-align: justify;">
                {!! $companyHistory->description !!}
            </div>
        </div>
    </div>
</div>
<!-- End Feature Section -->
@endSection