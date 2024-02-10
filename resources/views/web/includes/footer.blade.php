<!-- Footer Section -->
<style>
    .address-list li{
        margin-bottom: 10px;
    }
    .footer-icon-link i{
        font-size: 20px;
        padding: 30px 15px 5px 0;
    }
    a.disclaimer{
        color: white;
    }
    a.disclaimer:hover{
        color: white;
    }
    .footer-area{
        padding: 70px 0px;
    }
</style>
<footer>
    <div class="footer-area foo" data-sr='bottom'>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="footer-content">
                        <img src="{{asset('public/web/img/logo.png')}}" width="120" alt="responsive img" style="background: white; padding: 10px;">
                        <p style="font-size: 19px; padding: 12px 0px 0px; color: white;">Head Office</p>
                        <ul class="address-list" style="margin-top: 20px !important;">
                            @php($contactUs=\App\Model\ContactUs_web::valid()->first())
                            {{-- <li>
                                <i class="fa fa-map-marker"></i>
                                <div class="address-content">
                                    <p>
                                        {!!$contactUs->head_office_address!!}
                                    </p>
                                </div>
                            </li> --}}
                            <li>
                                <i class="fa fa-phone"></i>
                                <div class="address-content">
                                    <p>{{$contactUs->head_office_phone}}</</p>
                                </div>
                            </li>
                            <li>
                                <i class="fa fa-globe"></i>
                                <div class="address-content">
                                    <p>Email : {{$contactUs->head_office_email}}</p>
                                    <p>Web : www.eastwestbd.com</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="footer-content">
                        <h5>NAVIGATE TO</h5>
                        <ul class="feature-list">
                            <li>
                                <a href="{{route('jobOpening')}}">Job Openings</a>
                            </li>
                            <li>
                                <a href="{{route('ourClients')}}">Our Clients</a>
                            </li>
                            <li>
                                <a href="{{route('galleryAlbum')}}">Gallery</a>
                            </li>
                            <li>
                                <a href="{{route('contact')}}">Contact Us</a>
                            </li>
                            <li>
                                <a href="{{route('termsAndConditions')}}">Disclaimer & Privacy Policy</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-2">
                    <div class="footer-content">
                        <h5>ABOUT US</h5>
                        <ul class="feature-list">
                            <li>
                                <a href="{{route('companyHistory')}}">Company History</a>
                            </li>
                            <li>
                                <a href="{{route('facilities')}}">Facilities</a>
                            </li>
                            <li>
                                <a href="{{route('services')}}">Services</a>
                            </li>
                            <li>
                                <a href="{{route('organizationChart')}}">Organization Chart</a>
                            </li>
                            <li>
                                <a href="{{route('missionVision')}}">Mission & Vision</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-md-offset-1">
                    <div class="footer-content">
                        <h5>CONNECT WITH US</h5>
                        
                        <div class="col-md-10 col-sm-12" style="padding: 0px;">
                            @php($socialLinks=\App\Model\SocialLink_web::valid()->where('short_link', 1)->get())
                            @foreach ($socialLinks as $socialLink)
                                <a href="{{ $socialLink->social_link }}" target="_blank" class="footer-icon-link" data-toggle="tooltip" data-placement="top" title="" data-original-title="Like us on Facebook">
                                    <i class="{{$socialLink->fa_icon}}"></i>
                                </a>
                            @endforeach
                            <br class="clear">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Ends Footer Section -->
<!-- Copyright Section -->
<div class="copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <p class="footer-copyright">Copyright © 2021. All Rights Reserved By EastWest Human Resource Center Ltd.</p>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="footer-copyright">Developed By <a href="www.iisbd.com">Innovation Information System Ltd.</a></p>
            </div>
        </div>
    </div>
</div>