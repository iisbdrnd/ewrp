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