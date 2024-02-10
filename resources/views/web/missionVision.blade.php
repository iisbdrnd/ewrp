@extends('web.layouts.subDefault')
@section('content')
<style>
    .missionDiv{
        margin: 32px 0;
        text-align: center;
        padding: 20px;
    }
    .visionDiv{
        margin: 32px 0;
        text-align: center;
        padding: 20px;
    }
    .mission{
        width: 500px;
        margin: 0 auto;
        text-align: center;
    }
    .vision{
        width: 500px;
        margin: 0 auto;
        text-align: center;
    }
    .section-title-area-1{
        margin: 0px !important;
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
                            <h2>Company Mission & Vision</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<div class="about-area inner-padding5">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4">
                <div class="owl-item cloned">
                    <div class="item">
                        <img src="{{asset('public/web/img/mission.png')}}" alt="responsive img">
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                <div class="section-title-area-1 foo" data-sr="enter" data-sr-id="2">
                    <h2 class="section-title">Our Mission</h2>
                </div>
                <div class="about-content foo" data-sr="enter" data-sr-id="3">
                    <p>
                        “Empowering Man to Work” <br>
                        East West Human Resource Center Ltd, recognizes the global need for employment.
                        We want to provide our clients with world-class, personalized service and to
                        Bangladeshis the opportunity to work overseas. <br><br>
                        <b>To achieve this aim:</b> <br>
                        • Continually provide our clients with worker who are competent, reliable and
                        dedicated <br>
                        • Help Bangladesh enhance their competence thereby raising the competitiveness of
                        the workforce <br>
                        • Constantly work alongside with government agencies in the welfare of our clients and
                        workers <br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="about-area inner-padding1" style="padding-bottom: 40px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="section-title-area-1 foo" data-sr="enter" data-sr-id="2">
                    <h2 class="section-title">Our Vision</h2>
                </div>
                <div class="about-content foo" data-sr="enter" data-sr-id="3">
                    <p>
                        “We Think Ahead to Stay Ahead” <br>
                        East West Human Resource Center Ltd. shall be the undisputed Leader in the
                        country’s Recruitment Industry by continually providing our clients with world
                        class service and competent workforce, by constantly upgrading its facilities
                        and systems and by strengthening ties with our clients. <br><br>
                        East West is an advocate of the Bangladeshi workers and believes in their
                        innate capabilities and their desire to work, and we intend to bring the
                        Bangladeshi workers to every corner of the world to make them more
                        competitive in the global market.
                    </p>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="owl-item cloned">
                    <div class="item">
                        <img src="{{asset('public/web/img/vission.png')}}" alt="responsive img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- 
<div class="about-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 foo" data-sr='enter'>
                <div class="missionDiv">
                    <img class="mission" src="{{ asset('public/web/img/homePage/mission.jpg') }}" alt="">
                </div>
                <p>
                    “Empowering Man to Work” <br>
                    East West Human Resource Center Ltd, recognizes the global need for employment.
                    We want to provide our clients with world-class, personalized service and to
                    Bangladeshis the opportunity to work overseas. <br><br>
                    <b>To achieve this aim:</b> <br>
                    • Continually provide our clients with worker who are competent, reliable and
                    dedicated <br>
                    • Help Bangladesh enhance their competence thereby raising the competitiveness of
                    the workforce <br>
                    • Constantly work alongside with government agencies in the welfare of our clients and
                    workers <br>
                </p>
                <div class="missionDiv">
                    <img class="vision" src="{{ asset('public/web/img/homePage/vision.jpg') }}" alt="">
                </div>
                <p>
                    “We Think Ahead to Stay Ahead” <br>
                    East West Human Resource Center Ltd. shall be the undisputed Leader in the
                    country’s Recruitment Industry by continually providing our clients with world
                    class service and competent workforce, by constantly upgrading its facilities
                    and systems and by strengthening ties with our clients. <br><br>
                    East West is an advocate of the Bangladeshi workers and believes in their
                    innate capabilities and their desire to work, and we intend to bring the
                    Bangladeshi workers to every corner of the world to make them more
                    competitive in the global market.
                </p>
            </div>
        </div>
    </div>
</div> --}}
@endSection