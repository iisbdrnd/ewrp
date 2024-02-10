@extends('web.layouts.subDefault')
@section('content')
<style>
    table tr td{
        padding: 20px !important;
    }
    .section-title-area-1{
        margin-top: 30px;
    }
    .section-title{
        font-size: 23px;
    }
    /* .text-bold{
        margin-left: 5px;
    } */
    .abc p{
        line-height: 5px;
    }
    table tr{
        line-height: 1px;
    }
    table tr td{
        padding: 5px !important;
    }
    .viewJobs:hover{
        background: #08ada7;
        color: white !important;
        margin-top: 5px;
    }
    .single-service-inner{
        display: blog;
    }
    .single-service-inner:hover{
        box-shadow: 10px 10px 20px 0 rgb(0 0 0 / 25%);
        transition: all 300ms ease-in-out;
    }
    .abc-left{
        padding: 0px 40px;
        text-align: justify;
        border-right: .7px solid rgb(214, 211, 211);
        background: #fffdf3;
        padding-bottom: 63px;
    }
    .btn-sm-outline{
        width: 100%;
        padding: 4px 18px;
    }
    li.activeLicense {
        background: #08ada7;
    }
    li.activeLicense a{
        padding: 0 10px;
        color: white;
    }
    li.activeLicense a:hover{
        color: white !important;
    }
    .post-grid2{
        width: 100%;
    }
    a, a:visited{
        font-size: 22px
    }
    .viewJobs:hover{
        color: white !important;
        margin-top: 0px !important;
    }
    .viewJobs:focus {
        color: white !important;
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
                            <h2>Compliance</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Blog Section -->
<div class="blog-area inner-padding7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="sidebar">
                    <div class="widget-area foo" data-sr="enter" data-sr-id="1" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                        <div class="widget-header">
                            <h3 class="widget-title">Compliance List</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="categories-list">
                                {{-- SUB CATEGORY END --}}
                                <li class="{{empty(app('request')->input('license_id')) ? 'activeLicense' : ' '}}">
                                    <a url="{{route('viewSingleLicense')}}" class="license makePointer">
                                        All
                                    </a>
                                </li>
                                @if (count($licenseList)>0)
                                    @foreach ($licenseList as $key=>$license)
                                        @if($key < 10)
                                            <li class="{{!empty(app('request')->input('license_id')) && $license->id == app('request')->input('license_id') ? 'activeLicense' : ' '}}">
                                                <a url="{{route('viewSingleLicense', ['license_id'=>$license->id])}}" class="license makePointer">
                                                    {{$license->title}}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                    @if(count($licenseList)>10)
                                        <div style="margin: 10px 0;">
                                            <a class="btn btn-success btn-sm-outline viewJobs" id="seeCategory" data-toggle="modal" data-target="#seeAllCategory" style="cursor: pointer; background: #08ada7" role="button">See More</a>
                                        </div>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9" id="viewLicense">
                <div class="blog-masonary">
                    @if (count($licenses)>0)
                        @foreach ($licenses as $license)
                            <div class="post-grid2" style="position: absolute; left: 0px; top: 0px;">
                                <div class="post-row foo" data-sr="enter" data-sr-id="6" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                                    <div class="post-body">
                                        <div class="section-title-area-1 foo" style="opacity: 1 !important;">
                                            <a><i class="fa fa-check-circle"></i> {{$license->title}}</a>
                                        </div>
                                        <p class="post-text">
                                            {{strip_tags($license->description)}}
                                        </p>
                                    </div>
                                    {{-- <div class="post-footer">
                                        <a href="#" class="btn btn-default btn-post" role="button">READ MORE<i class="fa fa-angle-double-right"></i></a>
                                    </div> --}}
                                    <div class="col-md-2" style="padding: 0;">
                                        <a href="{{url('public/uploads/license_attachments/'.$license->attachment_name)}}" target="_blank" class="btn btn-success btn-sm-outline viewJobs" style="background: #08ada7" role="button">View File</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; font-size: 20px;">
                            No Compliance Found
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Blog Section -->
{{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"></div> --}}

<div class="modal fade" id="exampleModal" role="dialog" style="z-index: 999999;"></div>

@push('js')
<script>
    $('#seeCategory').on('click',  function(e){
        e.preventDefault();
        $('.loader').show();
        // console.log(postData);
        $.ajax({
            url : "{{route('allJobCategory')}}",
            type: "GET",
            dataType: 'html',
            success:function(data){
                $('.loader').hide();
                $('#exampleModal').html(data);
                $('#exampleModal').modal('show'); 
            }
        });
    });
    $('.license').on('click',  function(e){
        e.preventDefault();
        $('.categories-list li').removeClass('activeLicense');
        $(this).parent('li').addClass('activeLicense');

        let loader = `<div class="loader" style="display: none; margin: 0 auto;"></div>`;
        $('#viewLicense').html(loader);
        $('.loader').show();
        let url = $(this).attr('url');

        $.ajax({
            url : url,
            type: "GET",
            dataType: 'html',
            success:function(data){
                $('.loader').hide();
                console.log(data);
                $('#viewLicense').html(data);
                console.log($(this).closest('li'));
                // $(this).parent().closest('li').addClass('activeLicense');
                $(this).addClass('activeLicense');
                // setTimeout(function(){ 
                // }, 2000);
            }
        });
    });
</script>
@endpush
@endSection