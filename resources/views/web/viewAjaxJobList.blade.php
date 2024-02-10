<div class="blog-masonary">
                    
    {{-- <table id="example" class="table table-striped table-bordered" style="width:100% !important">
        <thead>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th>Qty.</th>
                <th>Salary</th>
                <th>Food</th>
                <th>Room</th>
                <th>Interview</th>
                <th>Circular</th>
            </tr>
        </thead>
        <tbody>
            @if (count($jobs)>0)
                @foreach ($jobs as $key=>$job)
                <tr>
                    <td>{{$job->company_name}}</td>
                    <td>{{$job->country_name}}</td>
                    <td>{{$job->category_name}}</td>
                    <td>{{$job->quantity ? $job->quantity : 'N/A'}}</td>
                    <td>{{$job->salary ? $job->salary : 'Negotiable'}}</td>
                    <td>{{$job->food_status == 1 ? 'Company' : 'Self'}}</td>
                    <td>{{$job->acomodation_status == 1 ? 'Company' : 'Self'}}</td>
                    @if($job->interview_status == 1)
                    <td style="display: flex; justify-content: space-around; align-items: center">
                        <div id="circle" class="offline"></div>
                        <div> {{$job->interview_date}} </div>
                    </td>
                    @else
                    <td></td>
                    @endif
                    <td>
                        @if ($job->attachment_name)
                        <a href="{{url('public/uploads/job_opening_attachments/'.$job->attachment_name)}}" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: #08ada7; padding: 2px; font-size:12px; text-transform: inherit; color: white;" role="button">Circular</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr style="text-align: center; font-size: 15px;">
                    <td colspan="9">
                        No Job Found
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th>Qty.</th>
                <th>Salary</th>
                <th>Food</th>
                <th>Room</th>
                <th>Interview</th>
                <th>Circular</th>
            </tr>
        </tfoot>
    </table> --}}
    <table id="example" class="table table-striped table-bordered" style="width:100% !important">
        <thead>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th class="centerAlign">Qty.</th>
                <th class="centerAlign">Salary</th>
                <th class="centerAlign">Food</th>
                <th class="centerAlign">Room</th>
                <th class="centerAlign">Age</th>
                <th class="centerAlign">Interview</th>
                <th class="centerAlign" style="width: 55px !important;">Circular</th>
            </tr>
        </thead>
        <tbody>
            @if (count($jobs)>0)
                @foreach ($jobs as $key=>$job)
                <tr>
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
                    <td class="centerAlign">
                        @if ($job->attachment_name)
                        <a href="{{url('public/uploads/job_opening_attachments/'.$job->attachment_name)}}" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 2px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">Circular</a>
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
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th class="centerAlign">Qty.</th>
                <th class="centerAlign">Salary</th>
                <th class="centerAlign">Food</th>
                <th class="centerAlign">Room</th>
                <th class="centerAlign">Age</th>
                <th class="centerAlign">Interview</th>
                <th class="centerAlign">Circular</th>
            </tr>
        </tfoot>
    </table>
    

</div>