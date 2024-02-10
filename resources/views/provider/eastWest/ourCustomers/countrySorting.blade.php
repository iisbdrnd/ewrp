<?php $panelTitle = "Country Wise Sorting"; ?>
<style>
    #sortable {
        list-style: none;
        /* border: solid 4px #62B1F6; */
        margin: 0;
        padding: 20px;
        width: 100%;
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
    }
    #sortable li {
        display: inline-block;
        text-align: center;
        margin-bottom: 20px;
    }
    #sortable li:hover {
        cursor: pointer;
    }
    #sortable li .box {
        height: 100%;
        background: #eee;
        width: 100%;
        padding: 10px;
        overflow: hidden;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
    }
    #sortable li .box.nostatus {
        color: #333;
        background: white;
        border-color: gray;
        overflow: hidden;
    }


    #sortable li .box.nostatus img {
        width: 150px;
        /*height: 100px;*/
        overflow: hidden;
        /*margin-left: -60%;*/
        /*margin-top: -35%;*/
    }


    #sortable li .box.nostatus img .img-thumbnail {
        padding: 4px;
        line-height: 1.42857143;
        background-color: #f2f2f2;
        border: 1px solid #c4c4c4;
        border-radius: 2px;
        transition: all .2s ease-in-out;
        display: inline-block;
        max-width: 100%;
        height: auto;
    }

    .todo-task-item .close{
        margin-top: -118px;
        /*padding: 10px 5px;*/
        background-color: black;
        border-radius: 50%;
    }
    .todo-task-item .close i{
        color: white;
        font-size: 7.5px!important;
    }
</style>

<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="form-group"> 
        <div class="row mt15">
            <div class="col-sm-12">
                {{-- <h5 style="text-align:center;">Drag Boxes Around</h5> --}}
                <ul class="clearfix" id="sortable">
                    @foreach($customerWiseCountries as $country)
                        <li id="{{$country->country_id}}" >
                            <div class="box nostatus">
                                {{-- <img src="{{asset('public/uploads/managementTeam/thumb')}}/{{$country->image}}"/> --}}
                                {{-- <img src="https://lipis.github.io/flag-icon-css/flags/4x3/{{Str::lower($country->iso)}}.svg" alt="Flag"> --}}
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="flag-icon-background flag-icon-{{Str::lower($country->iso)}}" style="height: 35px; border: none;"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h4 style="width: 100%">
                                                    {{$country->country_name}}
                                                </h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row mt15">
        <div class="col-sm-12">
            <a class="btn btn-default btn-sm ajax-link" href="ourCustomers" type="button" style="margin-left: 12px;">Back to List</a>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $( "#sortable" ).sortable({
          beforeStop: function( event, ui ) {
                var sorted = $( "#sortable" ).sortable( "toArray" );
                $.ajax({
                    url: '{{route('provider.eastWest.provider.eastWest.countrySortingAction')}}',
                    data: {_token: "{{ csrf_token() }}", sorted: sorted},
                    type: 'put'
                });
            }
        });
        $( "#sortable" ).disableSelection();
    });
    
    // $('#sortable li').addClass('ui-state-default col-md-3 col-sm-4 col-xs-12');
    // $('.danger h4').prepend('<i class="fa fa-exclamation-circle"></i>');
    // $('.good h4').prepend('<i class="fa fa-check"></i>');
    // $('.excellent h4').prepend('<i class="fa fa-star"></i>');
</script>