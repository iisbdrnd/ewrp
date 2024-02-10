@extends('web.layouts.default')
@section('content')
<style>
    #header{
        background: rgba(0,0,0,.8);
    }
    .protfolio-widget {
        position: relative;
    }
    .protfolio-widget .editBtn {
        position: absolute;
        top: 58%;
        left: 66%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        background-color: transparent;
        color: white!important;
        font-size: 14px;
        padding: 2px 25px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        opacity: 0.5;
        background-color: gray;
        text-decoration: none;
    }
    .protfolio-widget .editBtn:hover{
        background: initial;
        color: white!important;
    }
</style>
<section class="blog-posts-area section-gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 post-list blog-post-list" id="profileTable">
                <h3 class="mb-30">Your Profile Details</h3>
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>:</td>
                            <td>{{$client->name}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>:</td>
                            <td>{{$client->mobile}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td>{{$client->email}}</td>
                        </tr>
                        <tr>
                            <th>Company</th>
                            <td>:</td>
                            <td>{{$client->company}}</td>
                        </tr>
                    </tbody>
                </table>
                
                <button class="primary-btn pull-right mb-3" id="updateBtn"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit Profile</button>
            </div>
            <div class="col-lg-8 post-list blog-post-list" id="profileForm" style="display: none;">
                <h3 class="mb-30">Update Your Profile</h3>
                <form id="updateForm" class="form">
                    @csrf
                    @method('PUT')
                    <div id="errorMsgDiv">
                        <div id="responseMsg"></div>
                    </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>:</td>
                                <td>
                                    <input name="name" placeholder="Enter your name" class="common-input mb-20 form-control" required="" type="text" value="{{$client->name}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>:</td>
                                <td>
                                    <input name="mobile" placeholder="Enter your phone number" class="common-input mb-20 form-control" type="tel" value="{{$client->mobile}}">
                                </td>
                            </tr>
                            {{-- <tr>
                                <th>Email</th>
                                <td>:</td>
                                <td>
                                    <input name="email" placeholder="Enter your " class="common-input mb-20 form-control" required="" type="text" value="{{$client->email}}">
                                </td>
                            </tr> --}}
                            <tr>
                                <th>Company</th>
                                <td>:</td>
                                <td>
                                    <input name="company" placeholder="Enter your company name" class="common-input mb-20 form-control"  type="text" value="{{$client->company}}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="primary-btn pull-right mb-3" id="updateSubmit">Update Now</button>
                </form>
                {{-- <button class="primary-btn pull-right mb-3" id="seeProfile">See Profile</button> --}}
                
            </div>

            <div class="col-lg-4 sidebar">
                <div class="single-widget protfolio-widget">
                    <span id="profileImg">
                        @if(Auth::guard('web')->user()->image)
                        <img src="{{asset('public/uploads/client/'.Auth::guard('web')->user()->image)}}" width="200" alt="">
                        @else
                        <img src="{{asset('public/web/img/userDemo.png')}}" width="200" alt="">
                        @endif
                    </span>
                    <a class="editBtn">Edit</a>
                    <a href="#">
                        <h4>{{Auth::guard('web')->user()->name}}</h4>
                    </a>
                    <p>
                        MCSE boot camps have its supporters 
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endSection

@push('javascript')
<script type="text/javascript">
    $(document).ready(function () {
        $('#updateBtn').click(function() {
            $('#profileTable').hide();
            $('#profileForm').show();
        });
        $('#seeProfile').click(function() {
            $('#profileForm').hide();
            $('#profileTable').show();
        });

        $("#updateForm").on("submit", function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
                formError();
            } else {
                e.preventDefault();
                var postData = $(this).serializeArray();
                $.ajax({
                    url : "{{route('profileUpdate')}}",
                    type: "POST",
                    data: postData,
                    dataType: 'json',
                    success:function(data){
                        var status = parseInt(data.status);
                        if(data.status ==1) {
                            $('#responseMsg').html('<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <i class="fa fa-adjust alert-icon"></i> '+data.messege+'</div>');
                            location.reload();
                        } else{
                            $('#responseMsg').html('<div class="alert alert-danger" role="alert"><button type="button" id="close_icon" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+data.messege+'</div>');
                            
                        }
                    }
                });
            }
        });

        $(('.editBtn')).click(function (e) {
            e.preventDefault();
                // var postData = $(this).serializeArray();
            $.ajax({
                url : "{{route('cropImagePage',[$client->id])}}",
                type: "get",
                // data: postData,
                dataType: 'html',
                success:function(data){
                    $('#profileTable').html(data);
                }
            });
        })
    });
</script>
@endpush