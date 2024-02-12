<html moznomarginboxes mozdisallowselectionprint>
  <head>
      <!--<meta charset="utf-8">-->
      <meta http-equiv="X-UA-Compatible" content="IE-edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>Report Preview</title>
      <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
      <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="{{ asset('public/css/print-page.css') }}">
      <style type="text/css">
          body {
            font-family: Calibri (Body);
          }
          .logo{
            float: left;
          }
          .trainee_info tr > td{
            padding: 0 5px;
            border: 1px solid #000;
          }
      </style>
  </head>
    
<body>
    <div class="container">
        <div class="print_button" style="right: -130px;">
            <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
        <table width="98%" border="3" align="center" cellpadding="0" cellspacing="0" class="trainee_info">
            <tr style="background-color:#bebebe">
            <td colspan="5">
                <div class="logo">
                    <img src="{{url('public/img/ElSeif.png')}}" alt="logo">
                </div>
                <div class="header">
                <h4 style="font-family: sans-serif;">EI SEIF ENGINEERING CONTRACTING CO.LTD</h4>
                </div>
            </td>
            <td colspan="2">
                <div class="header">
                <h4 style="font-family: sans-serif;">WORKER EVALUATION FORM</h4>
                </div>
            </td>
            </tr>
            
            <tr>
            <td colspan="3" width="40%">Name: <b style="font-size:14px;">{{$cvPrints->full_name}}</b></td>
            <td colspan="2">
                Nationality:<br><br>
                DOB / Age: {{$cvPrints->age}}
            </td>
            <td colspan="2" width="40%">
                Position Applied: {{@Helper::singleTrade($cvPrints->trade_applied)->trade_name}}<br><br>
                Agency Name: EAST WEST HUMAN RESOURCE CENTER LTD.(BANGLADESH) RL-980
            </td>
            </tr>

            <tr>
            <td colspan="2" width="25%">
                Literacy / Education: <br>
                Iliterate: <br>
                Semi-Literate: <br>
                Literate:
            </td>
            <td colspan="2" width="25%">
                Trade / Technical Certificate:<br>
                MIDMAC CONTRACTING COMPANY W.I.I
            </td>
            <td colspan="2" width="25%">
                Relevant Experience: <br>
                Local: @if(!empty($cvPrints->total_home_exp))
                            {{array_sum(json_decode($cvPrints->total_home_exp, true))}} 
                        @endif yrs
                        <br>
                Overseas: 
                    @if(!empty($cvPrints->total_overs_exp))
                    {{array_sum(json_decode($cvPrints->total_overs_exp, true))}} 
                    @endif yrs
                <br>
                Total: {{$cvPrints->total_years_of_experience}} yrs
            </td>
            <td colspan="1" width="25%">
                Languages: <br>
                <div class="lang-name" style="float:left;">
                English:<br>
                Hindi/Urdu:<br>
                Arabic:<br>
                </div>
                <div class="lang-check" style="padding-left:110px;">
                    <input type="checkbox"><br>
                    <input type="checkbox"><br>
                    <input type="checkbox"><br>
                </div>
            </td>
            </tr>

            <tr>
            <td rowspan="2" colspan="1">
                Passport No: {{$cvPrints->passport_no}}<br><br>
                Expiry Date: {{$cvPrints->passport_expired_date}}
            </td>
            </tr>

            <tr>
            <td colspan="6">Other Skill if Any:</td>
            </tr>

            <tr>
            <td colspan="7" style="background-color:#888888">
                Physical Evaluation
                <span style="margin-left: 600px;">Age</span>
            </td>
            </tr>

            <tr>
            <td colspan="7">
                <div class="lang-name" style="float:left;">
                1. Visual Check
                </div>
                <div class="lang-check pull-right" style="padding-right: 100px;">
                    <input type="checkbox">
                </div>
            </td>
            </tr>

            <tr>
            <td colspan="7">
                <div class="lang-name" style="float:left;">
                1. Physical Test
                </div>
                <div class="lang-check pull-right" style="padding-right: 100px;">
                    <input type="checkbox">
                </div>
            </td>
            </tr>

            <tr>
            <td colspan="7" style="background-color:#888888">
                Skill Test
                <span style="margin-left: 600px;">Age</span>
            </td>
            </tr>

            <tr>
            <td colspan="5"></td>
            <td colspan="1">Total %</td>
            <td colspan="1">Score %</td>
            </tr>

            <tr>
            <td rowspan="3" colspan="4">1.  Physical Test</td>
            <td colspan="1">Skill</td>
            <td colspan="1">30%</td>
            <td colspan="1"></td>
            </tr>
            <tr>
            <td colspan="1">Productivity Speed</td>
            <td colspan="1">20%</td>
            <td colspan="1"></td>
            </tr>
            <tr>
            <td colspan="1">Quality</td>
            <td colspan="1">20%</td>
            <td colspan="1"></td>
            </tr>

            <tr>
            <td colspan="5">2.  Communication - Ability</td>
            <td colspan="1">15%</td>
            <td colspan="1"></td>
            </tr>

            <tr>
            <td colspan="5">3.  Attitude (Capacity to Carry out- Intstruction)</td>
            <td colspan="1">15%</td>
            <td colspan="1"></td>
            </tr>

            <tr style="background-color:#888888">
            <td colspan="5">Overall Rating</td>
            <td colspan="1">100%</td>
            <td colspan="1"></td>
            </tr>

            <tr>
            <td rowspan="2" colspan="2">Performance Factors</td>
            <td colspan="1">Poor (C)</td>
            <td colspan="1">Fair (B)</td>
            <td colspan="1">Good (B+)</td>
            <td colspan="1">Very Good (A)</td>
            <td colspan="1">Excellent (A+)</td>
            </tr>
            <tr>
            <td colspan="1">Below 50%</td>
            <td colspan="1">Up to 60%</td>
            <td colspan="1">Up to 70%</td>
            <td colspan="1">Up to 80%</td>
            <td colspan="1">Above 80%</td>
            </tr>

            <tr>
            <td colspan="6">Positive:</td>
            <td rowspan="5" colspan="1" height="200px;" align="center">Photo</td>
            </tr>

            <tr>
            <td colspan="6">Negative:</td>
            </tr>

            <tr>
            <td colspan="6">Conclusion/Remarks:</td>
            </tr>

            <tr>
            <td colspan="6">Basic Salary Offered:</td>
            </tr>

            <tr>
            <td colspan="6">Offered Accepted By (Sign):</td>
            </tr>

            <tr>
            <td colspan="2">
                <b>Evaluated By:</b><br><br>
                <div class="eval-label" style="float:left;">
                    Signature: <br><br>
                    Name: <br><br>
                    Job Title: <br><br>
                    Date: 
                </div>
                <div class="eval-name">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                </div>
            </td>
            <td colspan="3">
                <b>Reviewed By:</b><br><br>
                <div class="eval-label" style="float:left;">
                    Signature: <br><br>
                    Name: <br><br>
                    Job Title: <br><br>
                    Date: 
                </div>
                <div class="eval-name">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                </div>
            </td>
            <td colspan="3">
                <b>Approved By:</b><br><br>
                <div class="eval-label" style="float:left;">
                    Signature: <br><br>
                    Name: <br><br>
                    Job Title: <br><br>
                    Date: 
                </div>
                <div class="eval-name">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                <hr style="background-color:black; height:1px;">
                </div>
            </td>
            </tr>
            <tr height="20px;">
            <td colspan="2"></td>
            <td colspan="3"></td>
            <td colspan="3"></td>
            </tr>

        </table>
    </div>
</body>
<script type="text/javascript">
  function printDocument() {
      window.print();
  }
</script>
</html>