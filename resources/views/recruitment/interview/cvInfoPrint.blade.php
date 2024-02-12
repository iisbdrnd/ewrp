<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{@Helper::projects($projectId)->project_name}}</title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"/>

<style>
table, thead,tbody, tr, th, td{
    font-size: 10px;
}
/* DataTable print, excel, csv and pdf button customizing design */
div.dt-buttons {

position: absolute !important;
margin-left: 42% !important;

}
label{
  margin-bottom: 9px;
}
a.buttons-copy{
  background: #36A9CB;
  color: #fff;
  border:1px solid #36A9CA !important;
}
a.buttons-excel{
  background: #1C6C40;
color: #fff;
border:1px solid #1C6C49 !important;
}
a.buttons-csv{
  background: #056E11;
  color: #fff;
  border:1px solid #056E18 !important;
}
a.buttons-pdf{
  background: #D60B0B;
  color: #fff;
  border:1px solid #D60B0A !important;
}
a.buttons-print{
  background: #0F5BA1;
  color: #fff;
  border:1px solid #0F5BA1 !important;
}
a.buttons-copy:hover{
  background: #36A9CA !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-excel:hover{
  background: #1C6C49 !important;
color: #fff !important;
border:1px solid !important;
}
a.buttons-csv:hover{
  background: #056E18 !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-pdf:hover{
  background: #D60B0A !important;
  color: #fff !important;
  border:1px solid !important;
}
a.buttons-print:hover{
  background: #0F5BA1 !important;
  color: #fff !important;
  border:1px solid !important;
}
</style>
</head>
<body>
    <div class="page-header text-center">
      <h2>{{@Helper::projects($projectId)->project_name}}</h2>
    </div>
    <table id="myTable" style="width:100%">
        <thead>
            <tr>
                <td>SI.No.</td>
                <td>Name</td>
                <td>Father Name</td>
                <td>PP.No.</td>
                <td>PP.Exp. Date</td>
                <td>Passport Status</td>
                <td>DOB</td>
                <td>Age</td>
                <td>Contact No.</td>
                <td>Reference</td>
                <td>Dealer</td>
                <td>Trade Applied</td>
                <td>Trade Selected</td>
                <td>Grade</td>
                <td>Score</td>
                <td>Salary</td>
                <td>Food</td>
                <td>OT</td>
                <td>Salary A/D</td>
                <td>Result</td>
                <td>Education</td>
                <td>Home Exp.</td>
                <td>Overs Exp.</td>
                <td>Process</td>
                <td>WQRT No.</td>
                <td>WQRT Rpt.</td>
                <td>R.T Test</td>
                <td>Selection Date</td>
                <td>Remarks</td>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0;?>
            @foreach($cvInfoPrints as $index => $cvInfoPrint)
            <?php $i++;?>
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{  $cvInfoPrint->full_name }}</td>
                <td>{{  $cvInfoPrint->father_name }}</td>
                <td>{{  $cvInfoPrint->passport_no }}</td>
                <td>{{  $cvInfoPrint->passport_expired_date }}</td>
                <td>{{  $cvInfoPrint->passport_status == 1 ? 'In Office' : ($cvInfoPrint->passport_status == 2 ? 'Yes' : ($cvInfoPrint->passport_status == 3 ? 'No' : '')) }}</td>
                <td>{{  $cvInfoPrint->date_of_birth }}</td>
                <td>{{  $cvInfoPrint->age }}</td>
                <td>{{  $cvInfoPrint->contact_no }}</td>
                <td>{{ @Helper::reference($cvInfoPrint->reference_id)->reference_name }}</td>
                <td>
                    @if(!empty(@Helper::reference($cvInfoPrint->reference_id)->dealer))
                    @foreach(json_decode(@Helper::reference($cvInfoPrint->reference_id)->dealer, true) as $dealerId)
                      {{ @Helper::dealer($dealerId)->name }}
                    @endforeach 
                    @endif
                   
            </td>
                <td>{{ @Helper::singleTrade($cvInfoPrint->trade_applied)->trade_name }}</td>
                <td>{{ @Helper::singleTrade($cvInfoPrint->selected_trade)->trade_name }}</td>
                <td>{{  $cvInfoPrint->grade }}</td>
                <td>{{  $cvInfoPrint->score }}</td>
                <td>{{  $cvInfoPrint->salary }}</td>
                <td>{{  $cvInfoPrint->food == 1?'Company':($cvInfoPrint->food == 2?'Self':'') }}</td>
                <td>{{  $cvInfoPrint->ot == 1?'Yes':( $cvInfoPrint->ot == 2?'No':'') }}</td>
                <td>{{  $cvInfoPrint->salary_ad == 1?'Accepted':($cvInfoPrint->salary_ad == 2?'Not Accepted':'') }}</td>
                <td>
                {{  
                $cvInfoPrint->interview_selected_status == 1?'Pass':($cvInfoPrint->interview_selected_status == 2?'Fail':($cvInfoPrint->interview_selected_status == 3?'Waiting':($cvInfoPrint->interview_selected_status == 4?'Hold':''))) 
                }}
                </td>
                <td>{{  $cvInfoPrint->education }}</td>
                <td>{{  array_sum(json_decode($cvInfoPrint->total_home_exp, true)) }}</td>
                <td>{{  array_sum(json_decode($cvInfoPrint->total_overs_exp, true)) }}</td>
                <td>{{  $cvInfoPrint->process == 1?'SMAW':($cvInfoPrint->process == 2?'GTAW + SMAW':'') }}</td>
                <td>{{  $cvInfoPrint->wqrt_no }}</td>
                <td>{{  $cvInfoPrint->wqrt_test_report == 1?'Accepted':($cvInfoPrint->wqrt_test_report == 2?'Denied':'') }}</td>
                <td>{{  $cvInfoPrint->rt_test_result == 1?'Accepted':($cvInfoPrint->rt_test_result == 2?'Rejected':'') }}</td>
                <td>{{ Carbon\Carbon::parse($cvInfoPrint->selection_date)->format('d-m-Y') }}</td>
                <td>{{  $cvInfoPrint->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


<script>
    $(document).ready(function() {
        var data = [];
        // for ( var i=0 ; i<50000 ; i++ ) {
        //     data.push( [ i, i, i, i, i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i,i, ] );
        // }    
    $('#myTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {   extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {   extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {   extend: 'print',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
        ]
    } );
} );
</script>
</body>
</html>
