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
    #newsEventParent{
        height: 250px;
        overflow-x: hidden;
        /* overflow-y: scroll; */
    }
    #newsEvent a{
        padding: 5px 0px;
        border-bottom: 1px solid #ccc;
    }
    .sidebar-list li{
        border-bottom: 1px dotted #555;
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
    .viewJobs:hover{
        color: white !important;
        margin-top: 0px !important;
    }
    .viewJobs:focus {
        color: white !important;
    }
    /* .blink_me {
        animation: blinker 1s linear infinite;
        border-radius: 50%;
        height: 2px;
        background: red;
    }
    @keyframes blinker {  
        50% { opacity: 0; }
    } */

    #circle{
        height:10px;
        width:10px;
        margin-right: 6px;
        border-radius: 50%;
        
        opacity: 0.0;

        -webkit-animation: pulsate 1000ms ease-out;
        -webkit-animation-iteration-count: infinite; 
            
        -webkit-transition: background-color 300ms linear;
        -moz-transition: background-color 300ms linear;
        -o-transition: background-color 300ms linear;
        -ms-transition: background-color 300ms linear;
        transition: background-color 300ms linear;
    }

    @-webkit-keyframes pulsate {
        0% {opacity: 0.1;}
        40% {opacity: 1.0;}
        60% {opacity: 1.0;}
        100% {opacity: 0.1;}
    }

    .offline{
        background:red;
    }

    .online{
        background:green;
    }
    .centerAlign{
        text-align: center;
    }
    
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css">

<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Notice Board</h2>
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
                            <h3 class="widget-title">Notice Category</h3>
                        </div>
                        <div class="sidebar-list">
                            <ul class="categories-list">
                                <li class="{{empty(app('request')->input('category_id')) ? 'activeCategory' : ' '}}">
                                    <a url="{{route('viewAjaxNotice')}}" class="category makePointer">
                                        All
                                    </a>
                                </li>
                                @if (count($noticeCategories)>0)
                                    @foreach ($noticeCategories as $key=>$category)
                                        {{-- @if ($category->total_job != 0) --}}
                                        <li class="{{!empty(app('request')->input('category_id')) && $category->id == app('request')->input('category_id') ? 'activeCategory' : ' '}}">
                                            <a url="{{route('viewAjaxNotice', ['category_id'=>$category->id])}}" class="category makePointer">
                                                {{$category->name}}
                                                {{-- <span>({{$category->total_job}})</span> --}}
                                            </a>
                                        </li>
                                        {{-- @endif --}}
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="card widget-area foo" data-sr="enter" data-sr-id="4" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                        <div class="card-header widget-header">
                            <h3 class="widget-title">News & Events</h3>
                        </div>
                        <div class="card-body sidebar-list" id="newsEventParent">
                            <ul class="demo1">
                                @if (count($newsEvents) > 0)
                                    @foreach ($newsEvents as $key=>$newsEvent)
                                        <li class="news-item">
                                            @if ($newsEvent->news_event_type == 1)
                                                <a href="{{url('public/uploads/news_event_attachments/'.$newsEvent->attachment_name)}}" target="_blank">{{$newsEvent->title}}</a>
                                                <br>
                                            @else
                                                <a href="{{$newsEvent->external_link}}" target="_blank">{{$newsEvent->title}}</a>
                                                <br>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9"  id="viewAjaxNoticeList">
                <div class="blog-masonary">
                    
                    <table id="example" class="table table-striped table-bordered" style="width:100% !important">
                        <thead>
                            <tr>
                                <th class="centerAlign" width="10%">SL. </th>
                                <th class="centerAlign" width="50%">Title</th>
                                <th class="centerAlign" width="20%">Publish Date</th>
                                <th class="centerAlign" width="20%">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($notices)>0)
                                @foreach ($notices as $key=>$notice)
                                <tr>
                                    <td class="centerAlign">
                                        {{++$key}}
                                    </td>
                                    <td>
                                        @if ($notice->notice_type == 1)
                                            <a href="{{url('public/uploads/notice_attachments/'.$notice->attachment_name)}}" target="_blank">{{$notice->title}}</a>
                                            <br>
                                        @else
                                            <a href="{{$notice->external_link}}" target="_blank">{{$notice->title}}</a>
                                            <br>
                                        @endif    
                                    </td>
                                    <td>{{date('d M Y', strtotime($notice->created_at))}}</td>
                                    <td style="text-align: center;">
                                        @if ($notice->notice_type == 1)
                                            <a href="{{url('public/uploads/notice_attachments/'.$notice->attachment_name)}}" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 5px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">View</a>
                                        @else
                                            <a href="{{$notice->external_link}}" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 5px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">View</a>

                                        @endif
                                    </td>
                                    
                                </tr>
                                @endforeach
                            @else
                                <tr style="text-align: center; font-size: 20px;">
                                    <td colspan="9">
                                        No Job Found
                                    </td>
                                </tr>
                            @endif
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="centerAlign">SL</th>
                                <th class="centerAlign">Title</th>
                                <th class="centerAlign">Publish Date</th>
                                <th class="centerAlign">View</th>
                                
                            </tr>
                        </tfoot>
                    </table>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- End Blog Section -->

<div class="modal fade" id="exampleModal" role="dialog" style="z-index: 999999;"></div>

@push('js')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js"></script>
<script src="{{asset('public/web/js/easy-ticker.min.js')}}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            // "bPaginate": false
            // pageLength: 10,
            bFilter: true,
            // deferRender: true,
            // scrollY: 200,
            // scrollCollapse: true,
            // scroller: true
        });

        
        // setInterval(function(){
        //     var pos = $('#newsEvent').scrollTop();
        //     $('#newsEvent').scrollTop(pos + 2);
        // }, 500);

        // if ($('#newsEvent').height() > $('#newsEventParent').height()) {
        //     setInterval(function () {
        //         start();
        //     }, 3000);
        // }

        $(function(){
            $('#newsEventParent').easyTicker();
        });

    } );
    // //AUTO SCROLL START
    // function animateContent(direction) {  
    //     var animationOffset = $('#newsEventParent').height() - $('#newsEvent').height()-30;
    //     if (direction == 'up') {
    //         animationOffset = 0;
    //     }
        
    //     $('#newsEvent').animate({ "marginTop": (animationOffset)+ "px" }, 5000);
    // }

    // function up(){
    //     animateContent("up")
    // }
    // function down(){
    //     animateContent("down")
    // }

    // function start(){
    //     setTimeout(function () {
    //         down();
    //     }, 5000);
    //     setTimeout(function () {
    //         up();
    //     }, 5000);
    //     setTimeout(function () {
    //         console.log("wait...");
    //     }, 5000);
    // }  

    $('#newsEventParent').easyTicker({
        // or 'down'
        direction: 'up',

        // easing function
        easing: 'swing',

        // animation speed
        speed: 'slow',

        // animation delay
        interval: 2000,

        // height
        height: 'auto',

        // the number of visible elements of the list
        visible: 0,

        // enables pause on hover
        mousePause: 1,

        // custom controls
        controls: {
            up: '',
            down: '',
            toggle: '',
            playText: 'Play',
            stopText: 'Stop'
        },

        // callbacks
        callbacks: {
            before: function(ul, li){
                // do something
            },
            after: function(ul, li){
                // do something
            }
        }
    });
    //AUTO SCROLL END

    $('.category').on('click',  function(e){
        e.preventDefault();
        $('.categories-list li').removeClass('activeCategory');
        $(this).parent('li').addClass('activeCategory');

        let loader = `<div class="loader" style="display: none; margin: 0 auto;"></div>`;
        $('#viewAjaxNoticeList').html(loader);
        $('.loader').show();
        let url = $(this).attr('url');

        $.ajax({
            url : url,
            type: "GET",
            dataType: 'html',
            success:function(data){
                $('.loader').hide();
                console.log(data);
                $('#viewAjaxNoticeList').html(data);
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