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
          /* table tr:last-child {
              border: none !important;
          } */
          /* table tr:last-child td {
              border: none !important;
          } */


          body {
            font-family: Calibri (Body);
          }
          .trainee_info tr > td{
            padding: 0 5px;
            border: 1px solid #000;
          }

          .cv_logo img{
            height: 70px;
            margin-bottom: -35px;
            margin-left: 20px;
            opacity: 0.8;
            font:bold;
          }
      </style>
  </head>
    
<body>
    <div class="print_button" style="right: 57px;">
        <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
    </div>
    <div class="container">
      <!-- TRAINEE INFO -->
      <div class="cv_logo">
      <img src="{{url('public/img/lntlogo.png')}}" alt="Logo">
      </div>
      <table width="98%" border="2" align="center" cellpadding="0" cellspacing="0" class="trainee_info">
        <tr>
          <td colspan="4">
            <div class="header">
                <h4 style="font-family: sans-serif;">LARSEN & TOUBRO LIMITED</h4>
                <p class="project">E&C (GCC Operations)</p>
                <p class="title">INTERVIEW EVALUATION FORM (WORKMAN)</p>
              </div>
          </td>
        </tr>
        <tr>
          <td class="positon_applied" colspan="1" align="center" style="font-size: 12px"></td>
          <td colspan="3" style="font-size: 12px; padding: 5px 0;">Position Applied: {{@Helper::singleTrade($cvPrints->trade_applied)->trade_name}}</td>
        </tr>

        <tr>
          <td colspan="4">Full Name (in Block letter, as in Passport): {{$cvPrints->full_name}}</td>
        </tr>

        <tr>
          <td width="20%" style="font-size: 12px">Qualification-1</td>
          <td width="30%" style="font-size: 12px"></td>
          <td width="20%" style="font-size: 12px">Year of Pass</td>
          <td width="30%" style="font-size: 12px"></td>
        </tr>

        <tr>
          <td width="20%" style="font-size: 12px">Qualification-2</td>
          <td width="30%" style="font-size: 12px"></td>
          <td width="20%" style="font-size: 12px">Year of Pass</td>
          <td width="30%" style="font-size: 12px"></td>
        </tr>

        <tr>
        <td colspan="2" style="font-size: 12px">Total Experience: {{$cvPrints->total_years_of_experience}} yrs</td>
          <td style="font-size: 12px">Date of Birth</td>
          <td align="center" style="font-size: 12px">{{$cvPrints->date_of_birth}}</td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px">Experience (in Native Country):
            @if(!empty($cvPrints->total_home_exp))
               {{array_sum(json_decode($cvPrints->total_home_exp, true))}} 
            @endif yrs
        </td>
          <td style="font-size: 12px">Age</td>
          <td align="center" style="font-size: 12px">{{$cvPrints->age}}</td>
        </tr>

        <tr>
          <td colspan="4">Experience Overseas (Specify Country):
            @if(!empty($cvPrints->total_overs_exp))
            {{array_sum(json_decode($cvPrints->total_overs_exp, true))}} 
            @endif yrs
          </td>
        </tr>

        <tr>
          <td width="20%" style="font-size: 12px">Passport No.</td>
          <td width="30%" style="font-size: 12px">{{$cvPrints->passport_no}}</td>
          <td width="20%" style="font-size: 12px">Passport Expiry Date</td>
          <td width="30%" style="font-size: 12px">{{$cvPrints->passport_expired_date}}</td>
        </tr>

        <tr>
          <td width="20%" style="font-size: 12px">Phone No. (Native)</td>
          <td width="30%" style="font-size: 12px"></td>
          <td width="20%" style="font-size: 12px">Mob. No. (Local)</td>
          <td width="30%" style="font-size: 12px">{{$cvPrints->contact_no}}</td>
        </tr>

        <tr>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td colspan="2" align="center" style="font-size: 12px; background-color:gray"> <label>Service Terms</label> </td>
          <td colspan="1" style="font-size: 12px">Initial Visa Period</td>
          <td colspan="1" align="center" style="font-size: 12px">2 Years</td>
        </tr>

        <tr>
          <td colspan="3" style="font-size: 12px">Food, Transport (official duty), Medical (Insurance), Dormitory Camp Accommodation</td>
          <td colspan="1" align="center" style="font-size: 12px">By Company</td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px"></td>
          <td colspan="1" style="font-size: 12px">Working Hrs./day</td>
          <td colspan="1" align="center" style="font-size: 12px">8 Hrs.</td>
        </tr>

        <tr>
          <td colspan="4">Leave Eligibility: 42 Days after 24 months with flight ticket by Co.</td>
        </tr>

        <tr>
          <td colspan="1" style="font-size: 12px">Salary Offered (SAR)</td>
          <td colspan="1" style="font-size: 12px"></td>
          <td colspan="1" style="font-size: 12px">Contract Duration</td>
          <td colspan="1" align="center" style="font-size: 12px">2 Years</td>
        </tr>

        <tr>
          <td colspan="4">Transfer Service:</td>
        </tr>

        <tr>
          <td colspan="4"> <label style="text-decoration: underline">Termination of Service:</label> <br> 
            <p>Either party will be entitled to terminate the said employment by giving one months’ notice or one months’ pay in lieu thereof.
            </p>

            <p>In case of employee terminating the contract before completion of initial contract period for personal   reasons, resignations, etc., he would be required to refund to the Company the total expenses incurred on the employee towards visa charges, air fare, training and other expenses(if any).
            </p>

            <p>If, in the opinion of the company, you have to be repatriated back due to insubordination, dereliction of duty or on charges of committing unethical practices or on the advice of client / consultant for whatever reason, the total expenses incurred for your repatriation including air fare will be recovered from you through salary or otherwise. Company further reserves the right to recover all joining expense incurred on the employee, in case of such repatriation occurring within first two years of service.
            </p>
          </td>
        </tr>

        <tr>
          <td colspan="4">General:</td>
        </tr>

        <tr>
          <td colspan="4" height="60px"> 
            <label>I have been Explained the Service terms and conditions and accept the same</label> <br>
            <p style="float:left; margin-top:3px; margin-bottom: 0px">I will join duty by:</p> 
            <label style="float:right; margin-top:3px">Signature of Candidate</label></td>
        </tr>
        
        <tr>
          <td colspan="4"></td>
        </tr>

        <tr>
          <td colspan="4" align="center" style="background-color:gray">
             <label>For office use only (to be filled by interview panel and HR Dept.)</label>
          </td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px">Place of Interview:</td>
          <td colspan="2" style="font-size: 12px">Interview Date:</td>
        </tr>

        <tr>
          <td colspan="1" style="font-size: 12px"> <label>Selected / Rejected / Hold</label> </td>
          <td colspan="3" align="center" style="font-size: 12px">Interview Rating: <label>Excellent / Very Good / Good / Average / Poor</label> </td>
        </tr>

        <tr>
          <td rowspan="2" style="font-size: 12px">Name (Initial) and Signature of Interview Panel member(s) (L&T Executives)</td>
          <td colspan="1" style="font-size: 12px">sign</td>
          <td colspan="1" style="font-size: 12px">sign</td>
          <td colspan="1" style="font-size: 12px">sign</td>
        </tr>
        <tr>
          <td colspan="1" style="font-size: 12px">Initial</td>
          <td colspan="1" style="font-size: 12px">Initial</td>
          <td colspan="1" style="font-size: 12px">Initial</td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px"> <label>Position Selected for:</label> </td>
          <td colspan="2" style="font-size: 12px">Unit / SBG / Dept:</td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px">Recruitment Requisition No:</td>
          <td colspan="2" style="font-size: 12px">Position earmarked:</td>
        </tr>

        <tr>
          <td colspan="2" style="font-size: 12px">Offer Ref.: L&T/E&C/HR/W</td>
          <td colspan="2" style="font-size: 12px">Offer Date:</td>
        </tr>

        <tr>
          <td colspan="4" style="font-size: 12px">Service Provider (Agency/Referral) Name:</td>
        </tr>

        <tr>
          <td colspan="4" style="font-size: 12px">Remarks:</td>
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
