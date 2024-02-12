<style>
  .btn-success{display: none;}
</style>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Report Preview</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
        <!-- <link type="text/css" rel="stylesheet" href="{!! asset('public/css/bootstrap.css') !!}" />
        <link rel="stylesheet" href="{!! asset('public/css/font-awesome.min.css') !!}"> -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{!! asset('public/css/print-page.css') !!}">
        <style type="text/css">
            <?php
                $table_font = (isset($pdf)) ? '10px' : '11px';
            ?>
           .company_name {
                font-size: 20px;
            }
            .address {
                font-size: 14px;
            }
            .contact {
                font-size: 14px;
            }
            .title {
                font-size: 16px;
            }
            table td {
                font-size: {{$table_font}}
            }

        </style>
    </head>
    <body>
        <div class="container">
           <div class="print_button">
                <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
               {{--  <a href="{{ $pdf_url }}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a> --}}
            </div>
            <div class="header">
                {!! @Helper::companyDetails() !!}
                <p class="title">Candidate CV Preview</p>
                <p class="title">Candidate Name: {{ @Helper::single_candidate($candidate_id)->full_name }}</p>
            </div>
           
            <div class="report-data">
               <table class="table table-bordered table-condensed" width="100%" border="1">
  <tr>
    <td width="17%">Applied for:</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{ @Helper::singleTrade($tradeApplied) !==null?@Helper::singleTrade($tradeApplied)->trade_name:'' }}</td>
    <td width="24%" rowspan="7"><div align="center">Photo</div></td>
  </tr>
    <tr>
    <td width="17%">Selectted for</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{ @Helper::singleTrade($tradeSelected) !==null?@Helper::singleTrade($tradeSelected)->trade_name:'' }}</td>
  </tr>
   
    <tr>
    <td width="17%">Project</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{ @Helper::projects($cvPrints->ew_project_id)->project_name }}</td>
  </tr>
    
   <tr>
    <td width="17%">Test Location</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp;</td>
  </tr>
   <tr>
    <td width="17%">Name</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{ $cvPrints->full_name }}</td>
  </tr>
  <tr>
    <td width="17%">Passport No</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{  $cvPrints->passport_no }}</td>
  </tr>
  <tr>
    <td width="17%">Refference</td>
    <td width="1%"><b>:</b></td>
    <td width="58%">&nbsp; {{ @Helper::reference( $cvPrints->reference_id)->reference_name }}</td>
  </tr>
</table>
<table class="table table-bordered table-condensed" width="100%" border="1">
  <tr>
    <td width="27%">Communication Skills:</td>
    <td width="8%">English</td>
    <td width="11%">&nbsp;</td>
    <td width="10%">Arabic</td>
    <td width="9%">&nbsp;</td>
    <td width="9%">Hindi</td>
    <td width="9%">&nbsp;</td>
    <td width="10%">Others</td>
    <td width="7%">&nbsp;</td>
  </tr>
</table>
<table class="table table-bordered table-condensed" width="100%" border="1">
  <tr>
    <td colspan="4">Experiance Details:</td>
    <td colspan="2">Education:</td>
  </tr>
  <tr>
    <td colspan="2">Company Name &amp; Country</td>
    <td width="16%">Period</td>
    <td width="10%">Total</td>
    <td width="13%">Class Ten</td>
    <td width="11%">&nbsp;</td>
  </tr>
    <tr>
    <td width="5%">&nbsp;</td>
    <td width="45%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="13%">SSC</td>
    <td width="11%">&nbsp;</td>
  </tr>
    <tr>
    <td width="5%">&nbsp;</td>
    <td width="45%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="13%">HSC</td>
    <td width="11%">&nbsp;</td>
  </tr>
    <tr>
    <td width="5%">&nbsp;</td>
    <td width="45%">&nbsp;</td>
    <td width="16%">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="13%">Others</td>
    <td width="11%">&nbsp;</td>
  </tr>
</table>

<table class="table table-bordered table-condensed" width="100%" border="1">
  <tr>
    <td width="8%" rowspan="4" >Technical Assesment</td>
    <td width="27%">Job Knowledge (Theoretical)</td>
    <td width="9%">Excellent </td>
    <td width="10%">&nbsp;</td>
    <td width="7%">Good</td>
    <td width="9%">&nbsp;</td>
    <td width="7%">Average</td>
    <td width="7%">&nbsp;</td>
    <td width="7%">Poor</td>
    <td width="9%">&nbsp;</td>
  </tr>
   <tr>
    <td width="27%">Job Knowledge (Practical)</td>
    <td width="9%">Excellent </td>
    <td width="10%">&nbsp;</td>
    <td width="7%">Good</td>
    <td width="9%">&nbsp;</td>
    <td width="7%">Average</td>
    <td width="7%">&nbsp;</td>
    <td width="7%">Poor</td>
    <td width="9%">&nbsp;</td>
  </tr>
   <tr>
    <td width="27%">Tools Knowledge </td>
    <td width="9%">Excellent </td>
    <td width="10%">&nbsp;</td>
    <td width="7%">Good</td>
    <td width="9%">&nbsp;</td>
    <td width="7%">Average</td>
    <td width="7%">&nbsp;</td>
    <td width="7%">Poor</td>
    <td width="9%">&nbsp;</td>
  </tr>
   <tr>
    <td width="27%">Mesurement Knowledge</td>
    <td width="9%">Excellent </td>
    <td width="10%">&nbsp;</td>
    <td width="7%">Good</td>
    <td width="9%">&nbsp;</td>
    <td width="7%">Average</td>
    <td width="7%">&nbsp;</td>
    <td width="7%">Poor</td>
    <td width="9%">&nbsp;</td>
  </tr>
 <tr>
    <td height="143" colspan="10">Remarks</td>
  </tr>
</table>
<table class="table table-bordered table-condensed" width="100%" border="1">
  <tr>
    <td width="12%">Evaluate by</td>
    <td width="1%"><b>:</b></td>
    <td width="41%">&nbsp;</td>
    <td width="13%">Approved by</td>
    <td width="1%"><b>:</b></td>
    <td width="32%">&nbsp;</td>
  </tr>
    <tr>
    <td width="12%">Signature</td>
    <td width="1%"><b>:</b></td>
    <td width="41%">&nbsp;</td>
    <td width="13%">Signature</td>
    <td width="1%"><b>:</b></td>
    <td width="32%">&nbsp;</td>
  </tr>
    <tr>
    <td width="12%">Name</td>
    <td width="1%"><b>:</b></td>
    <td width="41%">&nbsp;</td>
    <td width="13%">Name</td>
    <td width="1%"><b>:</b></td>
    <td width="32%">&nbsp;</td>
  </tr>
    <tr>
    <td width="12%">Designation</td>
    <td width="1%"><b>:</b></td>
    <td width="41%">&nbsp;</td>
    <td width="13%">Designation</td>
    <td width="1%"><b>:</b></td>
    <td width="32%">&nbsp;</td>
  </tr>
   <tr>
    <td width="12%">Date</td>
    <td width="1%"><b>:</b></td>
    <td width="41%">&nbsp;</td>
    <td width="13%">Date</td>
    <td width="1%"><b>:</b></td>
    <td width="32%">&nbsp;</td>
  </tr>
 
</table>
    </div>
</div>
</body>
    <script type="text/javascript">
        function printDocument() {
            window.print();
        }
    </script>
</html>



<script type="text/javascript">
// function printDiv() 
// {

//  var divToPrint=document.getElementById('cvPrintPreview');

//   var newWin=window.open('','Print-Window');

//   newWin.document.open();

//   newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

//   newWin.document.close();

//   setTimeout(function(){newWin.close();},10);

// }
	
</script>