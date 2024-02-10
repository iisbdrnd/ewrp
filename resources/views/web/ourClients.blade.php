@extends('web.layouts.subDefault')
@section('content')
<style>
    .short-info{
        padding: 15px 0px 18px;
    }

    .acordion-head {
        width: 100%;
        height: auto;
        background-color: #fafafa;
        padding: 10px 10px 10px;
        border: 0px;
    }
    .acordion-head img{
        height: 100%;
        width: 25px;
        margin-right: 5px;
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
                            <h2>Our Clients</h2>
                            {{-- <p><a href="#">Home </a> > About</p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<!-- Team Section -->
<div class="team-area inner-padding">
    <div class="container">

        <div class="panel-group" id="choose-why">
            @if (count($country_ids) > 0)
                @foreach ($country_ids as $key => $country_data)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title acordion-head @if ($key == 0) active @endif" style="padding-bottom: 0px;">
                                <a data-toggle="collapse" data-parent="#choose-why" href="#choose-why{{$key}}">
                                    {{-- <div class="panel-icon @if ($key == 0) active @endif">
                                        
                                    </div> --}}
                                    
                                    {{-- <img src="https://lipis.github.io/flag-icon-css/flags/4x3/{{Str::lower($country_data->iso)}}.svg" alt="Flag"> --}}
                                    {{-- <span class="flag-icon-background flag-icon-us"></span> --}}
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td width="5%">
                                                    <div class="panel-icon @if ($key == 0) active @endif">
                                                        @if ($key == 0)
                                                            <i class="fa fa-minus"></i>
                                                        @else
                                                            <i class="fa fa-plus"></i>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="flag-icon-background flag-icon-{{Str::lower($country_data->iso)}}" style="width: 5%;"></td>
                                                <td>{{$country_data->country_name}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </a>
                            </h4>
                        </div>
                        <div id="choose-why{{$key}}" class="panel-collapse collapse @if ($key == 0) in @endif">
                            <div class="panel-body">
                                <div class="row">
                                    @if (count($country_data->clients) > 0)
                                        @foreach ($country_data->clients as $client)
                                        <div class="col-xs-12 col-sm-6 col-md-3" style="height: 100px; margin-bottom: 20px;">
                                            <div class="team-member" data-sr='enter'>
                                                <div class="team-header">
                                                    <img src="{{asset('public/uploads/customers/'.$client->image)}}" width="180" alt="responsive img">
                                                </div>
                                                <div class="team-body" style="height: 100%;">
                                                    <div class="short-info">
                                                        <h3 class="member-name"><b>{{$client->name}}</b></h3>
                                                        <h4 class="designation">{{$client->address}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>        
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
            @endif
            

            {{-- <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title acordion-head">
                        <a data-toggle="collapse" data-parent="#choose-why" href="#choose-why2">
                            <div class="panel-icon">
                                <i class="fa fa-plus"></i>
                            </div>Retina Display</a>
                    </h4>
                </div>
                <div id="choose-why2" class="panel-collapse collapse">
                    <div class="panel-body">
                        <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae </p>
                    </div>
                </div>
            </div> --}}

        </div>
        
    </div>
</div>
<!-- End Team Section -->
@endSection