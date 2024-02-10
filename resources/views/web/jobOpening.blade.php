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

    #myInput {
        /* background-image: url('/web/img/searchicon.png'); */
        background-position: 10px 12px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 6px 12px 6px 16px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
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
    <div class="container-big">
        <div class="row">
            <div class="col-sm-12 col-md-2">
                <div class="sidebar">
                    <div class="widget-area foo" data-sr="enter" data-sr-id="1" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
                        <div class="widget-header">
                            <h3 class="widget-title">Trade List</h3>
                            <input type="text" id="myInput" onkeyup="tradeSearch()" placeholder="Search for trade.." title="Type in a name">
                        </div>
                        <div class="sidebar-list">
                            <ul class="categories-list" id="myUL">
                                <li class="{{empty(app('request')->input('category_id')) ? 'activeCategory' : ' '}}">
                                    <a url="{{route('viewAjaxJobList')}}" class="category makePointer">
                                        All
                                    </a>
                                </li>
                                @if (count($jobCategories)>0)
                                    @foreach ($jobCategories as $key=>$category)
                                        @if ($category->total_job != 0)
                                        <li class="{{!empty(app('request')->input('category_id')) && $category->id == app('request')->input('category_id') ? 'activeCategory' : ' '}}">
                                            <a url="{{route('viewAjaxJobList', ['category_id'=>$category->id])}}" class="category makePointer">
                                                {{$category->name}}
                                                {{-- <span>({{$category->total_job}})</span> --}}
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-10"  id="viewAjaxJobList">
                <div class="blog-masonary">
                    
                    <table id="example" class="table table-striped table-bordered" style="width:100% !important">
                        <thead>
                            <tr>
                                <th class="centerAlign" style="width: 55px !important;">Circular</th>
                                <th>Company</th>
                                <th>Country</th>
                                <th>Trade</th>
                                <th class="centerAlign">Qty.</th>
                                <th class="centerAlign">Salary</th>
                                <th class="centerAlign">Food</th>
                                <th class="centerAlign">Room</th>
                                <th class="centerAlign">Age</th>
                                <th class="centerAlign">Interview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($jobs)>0)
                                @foreach ($jobs as $key=>$job)
                                <tr>
                                    <td class="centerAlign">
                                        @if ($job->attachment_name)
                                        <a href="{{url('public/uploads/job_opening_attachments/'.$job->attachment_name)}}" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 2px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">Circular</a>
                                        @endif
                                    </td>
                                    <td>{{$job->company_name}}</td>
                                    <td>{{$job->country_name}}</td>
                                    <td>{{$job->category_name}}</td>
                                    <td class="centerAlign">{{$job->quantity ? $job->quantity : 'N/A'}}</td>
                                    <td class="centerAlign">{{$job->salary ? $job->salary : 'Negotiable'}}</td>
                                    <td class="centerAlign">{{$job->food_status}}</td>
                                    <td class="centerAlign">{{$job->accommodation_status}}</td>
                                    <td class="centerAlign">{{$job->age}}</td>
                                    @if($job->interview_status == 1)
                                    <td style="display: flex; align-items: center;">
                                        <div id="circle" class="offline"></div>
                                        <div>{{$job->interview_date}}</div>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
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
                                <th class="centerAlign">Circular</th>
                                <th>Company</th>
                                <th>Country</th>
                                <th>Trade</th>
                                <th class="centerAlign">Qty.</th>
                                <th class="centerAlign">Salary</th>
                                <th class="centerAlign">Food</th>
                                <th class="centerAlign">Room</th>
                                <th class="centerAlign">Age</th>
                                <th class="centerAlign">Interview</th>
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
    } );

    function tradeSearch() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }

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