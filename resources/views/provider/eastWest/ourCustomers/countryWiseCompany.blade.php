
            @foreach($customerWiseCountries as $country)
                <li id="{{$country->id}}" >
                    <div class="box nostatus">
                        {{-- <img src="{{asset('public/uploads/managementTeam/thumb')}}/{{$country->image}}"/> --}}
                        <img src="{{asset('public/uploads/customers/'.$country->image)}}" alt="{{$country->name}}">
                        <h4 style="width: 100%">
                            {{$country->name}}
                        </h4>
                    </div>
                </li>
            @endforeach

        