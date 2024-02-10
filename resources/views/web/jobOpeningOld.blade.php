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


    /* =======Custom Css for Attachment list Preview======= */
    .file-ul {
        list-style: none;
        padding-left: 10px;
        margin-left: -10px;
    }
    .file-ul .file-list {
        display: inline-block;
        padding: 6px 14px;
        background-color: #f4f7f7;
        border: 1px solid #cdd0d7;
        border-radius: 14px;
        cursor: pointer;
    }
    .file-ul .file-list:hover {
        background-color: #eff0f0;
    }
    .file-ul .file-list a {
        text-decoration: none;
        padding: 2px;
    }
    .file-ul .file-list a .attachment-img {
        float: left;
    }
    .file-ul .file-list a .attachment-img img{
        height: 19px;
        width: 20px;
    }
    .file-ul .file-list a span {
        margin-left: 3px;
        padding: 2px;
    }

    li.activeCategory {
        background: #08ada7;
    }
    li.activeCategory a{
        padding: 0 10px;
        color: white;
    }
    li.activeCategory a:hover{
        color: white !important;
    }
    li.activeCategory span{
        padding: 0 10px;
        color: white;
    }
    li.activeCategory span:hover{
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
                            <h2>Job Openings</h2>
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
                            <h3 class="widget-title">Categories</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="categories-list">
                                <li class="{{empty(app('request')->input('category_id')) ? 'activeCategory' : ' '}}">
                                    <a url="{{route('viewAjaxJobList')}}" class="category makePointer">
                                        All
                                    </a>
                                </li>
                                @if (count($jobCategories)>0)
                                    @foreach ($jobCategories as $key=>$category)
                                        @if($key < 10)
                                            <li class="{{!empty(app('request')->input('category_id')) && $category->id == app('request')->input('category_id') ? 'activeCategory' : ' '}}">
                                                <a url="{{route('viewAjaxJobList', ['category_id'=>$category->id])}}" class="category makePointer">
                                                    {{$category->name}}
                                                    <span>({{$category->total_job}})</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                    @if(count($jobCategories)>10)
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
            <div class="col-sm-12 col-md-9"  id="viewAjaxJobList">
                <div class="blog-masonary">
                    @if (count($jobs)>0)
                        @foreach ($jobs as $job)
                            <div class="single-service-inner" style="margin-bottom: 20px;">
                                <div class="row" style="padding-left: 10px; padding-right: 10px;">
                                    <div class="col-xs-12 col-sm-12 col-md-9 foo abc-left"  data-sr="enter" data-sr-id="1">
                                        <div class="section-title-area-1 foo" style="opacity: 1 !important;">
                                            <a><i class="fa fa-check-circle"></i> {{$job->principal}}</a>
                                            <p class="section-title">{{$job->title}}</p>
                                        </div>
                                        <div class="single-service-content foo" data-sr="enter" data-sr-id="4">
                                            {{ Str::words(strip_tags($job->job_description), 30,'....')  }}
                                        </div>
                                        <div class="deadline">
                                            Deadline : {{date('d M Y', strtotime($job->deadline))}}
                                        </div>
                                        <div class="job-attachment">
                                            <ul class="file-ul">
                                                @foreach ($job->attachments as $attachment)
                                                    <li class="file-list">
                                                        <a href="{{url('public/uploads/job_opening_attachments/'.$attachment->attachment_name)}}" target="_blank">
                                                            <div class="attachment-img">
                                                                <img src="{{ Helper::getFileThumb($attachment->attachment_name) }}" alt="">
                                                            </div>
                                                            <span class="attachment-title">{{ $attachment->attachment_name }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3">
                                        <div class="section-title-area-1 foo abc" data-sr="enter" data-sr-id="3" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                                            <table>
                                                <tr>
                                                    <td><i class="fa fa-dollar" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{!empty($job->salary) ? $job->salary : 'N/A'}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-globe" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{$job->country}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-folder" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{$job->job_type}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-clock-o" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{!empty($job->duration) ? $job->duration : 'N/A'}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-heart" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{$job->age_from.' - '.$job->age_to}}</span></td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-venus-mars" style="color: green;"></i></td>
                                                    <td><span class="text-bold">{{$job->gender}}</span></td>
                                                </tr>
                                            </table>
                                            <a href="{{route('jobDetails', $job->id)}}" class="btn btn-success btn-sm-outline viewJobs" style="background: #08ada7" role="button">View Job</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; font-size: 20px;">
                            No Job Found
                        </p>
                    @endif

                </div>
                
                {{-- <div class="row">
                    <div class="col-sm-12" style="padding: 0px !important; margin-top: 20px;">
                        <ul class="pagination pl15">
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div> --}}
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

    $('.category').on('click',  function(e){
        e.preventDefault();
        $('.categories-list li').removeClass('activeCategory');
        $(this).parent('li').addClass('activeCategory');

        let loader = `<div class="loader" style="display: none; margin: 0 auto;"></div>`;
        $('#viewAjaxJobList').html(loader);
        $('.loader').show();
        let url = $(this).attr('url');

        $.ajax({
            url : url,
            type: "GET",
            dataType: 'html',
            success:function(data){
                $('.loader').hide();
                console.log(data);
                $('#viewAjaxJobList').html(data);
                console.log($(this).closest('li'));
                // $(this).parent().closest('li').addClass('activeCategory');
                $(this).addClass('activeCategory');
                // setTimeout(function(){ 
                // }, 2000);
            }
        });
    });
</script>
@endpush
@endSection