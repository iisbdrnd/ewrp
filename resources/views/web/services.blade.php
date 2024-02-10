@extends('web.layouts.subDefault')
@section('content')
<style>
    .section-title-area-1{
        margin: 0px;
    }
    .modal-dialog{
        margin-top: 170px;
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
                            <h2>Services</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<!-- Service Section -->
<div class="service-area inner-padding5">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/mobilization.png')}}" width="45" alt="responsive img">
                    <h4>Mobilization</h4>
                    <p>
                        EWHRCL maintains a large computerized database of applicants for easy reference.
                    </p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#mobilization" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/tradetest.png')}}" width="45" alt="responsive img">
                    <h4>Trade Test</h4>
                    <p>
                        The company has technical and medical evaluators who can prescreen the applicants. 
                    </p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#tradeTest" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/pre-screening.png')}}" width="45" alt="responsive img">
                    <h4>Pre-Screen</h4>
                    <p>EWHRCL is staffed with qualified Technical Evaluators with long experiences in the Middle East.</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#preScreen" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/selection.png')}}" width="45" alt="responsive img">
                    <h4>Selection</h4>
                    <p>EWHRCL has a highly qualified technical team who can perform the selection of workers in behalf</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#selection" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/travel-document.png')}}" width="45" alt="responsive img">
                    <h4 style="font-size: 17px;">Travel Documents Processing</h4>
                    <p>EWHRCL also assists in the processing of the applicants’ documents, like their passports, Smart Card... </p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#travelDocuments" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/reports.png')}}" width="45" alt="responsive img">
                    <h4>Reports</h4>
                    <p>EWHRCL regularly sends its clients various weekly reports to help them monitor the progress...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#reports" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/deployment.png')}}" width="45" alt="responsive img">
                    <h4>Deployment Notification</h4>
                    <p>The workers’ flight schedules are forwarded to their respective employers three days...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#deployMentNotify" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/preflight-briefing.png')}}" width="45" alt="responsive img">
                    <h4>Pre-Flight Briefing</h4>
                    <p>The company has had a PR Section to address our relations with the deployed workers...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#preFlight" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/airline-tickets.png')}}" width="45" alt="responsive img">
                    <h4>Airline Tickets</h4>
                    <p>The clients may either send the PTA or have EWHRCL purchase the tickets locally in their behalf...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#airlineTicket" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/client-accommodation.png')}}" width="45" alt="responsive img">
                    <h4>Client Accommodation</h4>
                    <p>EWHRCL maintains corporate accounts in various Five Star and Three Star hotels at discounted...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#clientAccomodation" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/airport.png')}}" width="45" alt="responsive img">
                    <h4>Airport</h4>
                    <p>EWHRCL has Public Relations Officers to assist our clients in their Accommodations...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#airport" role="button">READ MORE</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 service" style="padding-bottom: 30px;">
                <div class="service-widget foo" data-sr='enter'>
                    <img src="{{asset('public/web/img/serviceIcon/deployment-period.png')}}" width="45" alt="responsive img">
                    <h4>Deployment Period</h4>
                    <p>EWHRCL requires a lead-time of 30 working days for deployment of required workers...</p>
                    <a class="btn btn-default btn-readmore" data-toggle="modal" data-target="#deploymentPeriod" role="button">READ MORE</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mobilization" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Mobilization</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL maintains a large computerized database of applicants for
                                easy reference. <br>
                                We currently have over 15,200 active jobseekers in our database
                                who applied through our website www.bdeastwest.com

                                <h3>And the number is increasing everyday.</h3>
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
<div class="modal fade" id="tradeTest" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Trade Test</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The company has technical and medical evaluators who can prescreen the applicants. For trade testing, we rely on our certified personnel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="preScreen" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Pre-Screen</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL is staffed with qualified Technical Evaluators with long
                                experiences in the Middle East. If the client sends representatives, the
                                number of applicants we normally pre-screen and line up is 3 times
                                the number that they require, unless otherwise specified by the client.
                                <br>
                                <br>
                                Apart from that, our Technical Evaluators Pre-Screen
                                every single applicant who apply through our website
                                and send them in to our ‘Screened Database’.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="selection" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Selection</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL has a highly qualified technical team who can perform the
                                selection of workers in behalf of the clients. Some of our clients prefer that we
                                conduct the selection for them. The company takes full responsibility for the
                                qualifications of each worker that it selects.
                                <br>
                                <b>
                                    In 2014 & 2015, J&P, Oman has employed 200+ personnel selected by EWHRCL with zero complain.
                                </b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="travelDocuments" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Travel Documents Processing</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL also assists in the processing of the applicants’ documents, like
                                their passports, Smart Card, Medical, Police Clearance and the likes, to
                                expedite their deployment. This is hastened by the assistance of our Liaison
                                Officers to various institutions and government offices.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reports" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Reports</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL regularly sends its clients various weekly reports to help them monitor
                                the progress of the recruitment. We also answer all client queries within a
                                span of 24 hours after receiving such queries.
                                <br>
                                <br>
                                After a project’s completion, the company submits a final report to the client,
                                which indicates visa and ticket utilization with the corresponding applicant
                                names and the date of deployment.
                                <br>
                                <br>
                                In near future, all these reports will be available to our clients anytime through
                                our interactive website. Queries and other correspondences can be
                                facilitated in the website.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deployMentNotify" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Deployment Notification</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The workers’ flight schedules are forwarded to their respective employers
                                three days in advance. Employers are immediately notified of any changes.
                                EWHRCL also notifies the employers of the categories, salaries, blood groups,
                                photo and EW Jackets along with other information.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="preFlight" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Pre-Flight Briefing</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The company has had a PR Section to address our relations with the
                                deployed workers on a more personal level.
                                <br>
                                <b>
                                    Our pre-departure orientations impart to the workers how to manage
                                    employer-worker problems or disputes to keep it from escalating.
                                </b>
                                <br>
                                We also brief them regarding their Travel Plan, Emergency Contacts, Culture
                                & Customs of the destination country, Working Environment and so on.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="airlineTicket" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Airline Tickets</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                The clients may either send the PTA or have EWHRCL purchase the tickets
                                locally in their behalf. We can acquire the tickets here directly from the
                                various airlines affording our clients more favorable rates and booking
                                privileges.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="clientAccomodation" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Client Accommodation</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL maintains corporate accounts in various Five Star and
                                Three Star hotels at discounted rates close to corporate office and
                                training center.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="airport" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Airport</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL has Public Relations Officers to assist our clients in their
                                Accommodations and chauffeured service to and from the airport.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deploymentPeriod" role="dialog" style="z-index: 999999;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="item">
                            <img src="{{asset('public/web/img/homePage/service.jpg')}}" alt="responsive img">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="section-title-area-1">
                            <h2 class="section-title">Deployment Period</h2>
                        </div>
                        <div class="about-content">
                            <p>
                                EWHRCL requires a lead-time of 30 working days for deployment of required
                                workers after receiving E-Wakala from the employer. 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Service Section -->
@endSection

@push('js')
<script>
    $('.btn-readmore').on('click', function(){
        // console.log('hello');
        // alert(132);
    });
</script>
@endpush