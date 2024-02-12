<html moznomarginboxes mozdisallowselectionprint>
  <head>
    <!--<meta charset="utf-8">-->
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Cash/Bank Position</title>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('public/css/bpack-report.css') }}">
    <style>
   /* table td{
            border-right: hidden!important;
            border-left: hidden!important;
        }     
   table tr th, td{
            padding:10px !important;
        }
        th{
            text-align: center;
        }
        */
     .container{
         margin-top: 2%;
     }    
    </style>
  </head>
    
<body>
   <div class="container">
       <div class="row">
           <div class="col-md-12 text-center">
               <div class="print_button text-right">
                <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <a href="{{ @$pdf_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
            </div>
            <div class="header">
                <?php echo @Helper::companyDetails(); ?>
                <p class="project">Project: {{ @Helper::projectName($project_id)}}</p>
                <p class="title">Cash/Bank Position @if($date_range==1) {{' ('.$from_date.' - '.$to_date.')'}} @endif </p>
            </div>
           </div>
       </div>
       <div class="row">
           <div class="col-md-12">
               <table class="table table-bordered table-condensed">
                   <thead>
                       <tr>
                           <th width="5%">SI</th>
                           <th width="20%">Particulars</th>
                           <th width="20%">Opening Balance</th>
                           <th width="15%">Received</th>
                           <th width="20%">Payment</th>
                           <th width="20%">Closing Balance</th>
                       </tr>
                   </thead>
                   <tbody>

                       @foreach ($cash_bank_position as $index => $cbp)
                       <?php $opening_balance = $date_range == 0? 0: $opening_balances[$index]->opening_balance;
                       ?>
                         <tr>
                           <td>1</td>
                           <td>Cash Position</td>
                           <td>{{ $opening_balance }}</td>
                           <td>{{ $cbp->receive }}</td>
                           <td>{{ $cbp->payment }}</td>
                           <td>{{ $opening_balance + $cbp->receive - $cbp->payment}}</td>
                       </tr>  
                       @endforeach
                       
                   </tbody>
               </table>
               <table class="table table-bordered table-condensed">
                   <thead>
                       <tr>
                           <th width="5%">SI</th>
                           <th width="20%">Cash In-Flow</th>
                           <th width="20%"></th>
                           <th width="15%"></th>
                           <th width="20%"></th>
                           <th width="20%"></th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php 
                           $total_received_amount = 0; 
                       ?>

                       @foreach ($account_heads as $index1 => $account_head)
                       @foreach ($account_head->cash_in_flow as $index2 =>  $cash_in_flow)
                       <tr> 
                           <td>{{ $index1+1 }}</td>
                           <td>{{ @Helper::getAccountHead($account_head->collectable_account_id)->account_head }}</td>
                                <?php $total_received_amount += $cash_in_flow->received; ?>
                                <td></td>
                                <td>{{ $cash_in_flow->received }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                       @endforeach 
                   </tbody>
                   <tfoot>
                       <tr>
                           <th></th>
                           <th>Total Balance</th>
                           <th></th>
                           <th>{{ $total_received_amount }}</th>
                           <th></th>
                           <th></th>
                       </tr>
                   </tfoot>
               </table>
               <table class="table table-bordered table-condensed">
                   <thead>
                       <tr>
                           <th width="5%">SI</th>
                           <th width="20%">Cash Out-Flow</th>
                           <th width="20%">Opening Balance</th>
                           <th width="15%">Received</th>
                           <th width="20%">Payment</th>
                           <th width="20%">Closing Balance</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php 
                            $total_payment_account = 0;
                       ?>
                       @foreach ($cash_out_account_heads as $key => $cash_out_account_head)
                       
                        <tr>
                            @foreach ($cash_out_account_head->cash_out_flows as $cash_out_flow)
                            <?php 
                            $total_payment_account += $cash_out_flow->debit_amount;
                            ?>
                           <td>{{ $key+1 }}</td>
                           <td> {{ $cash_out_account_head->account_head }} </td>
                            <td></td>
                            <td></td>
                            <td>
                                {{ $cash_out_flow->debit_amount }}
                            </td>
                            <td></td>  
                           @endforeach
                           
                       </tr>
                       @endforeach
                   </tbody>
                   <tfoot>
                       <tr>
                           <th></th>
                           <th>Total Balance </th>
                           <th></th>
                           <th></th>
                           <th>{{ $total_payment_account }}</th>
                           <th></th>
                       </tr>
                   </tfoot>
               </table>
               <table class="table table-bordered table-condensed">
                   <thead>
                       <tr>
                           <th width="5%">SI</th>
                           <th width="20%">Bank Position</th>
                           <th width="20%"></th>
                           <th width="15%"></th>
                           <th width="20%"></th>
                           <th width="20%"></th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                            $total_bank_position = 0;
                            $total_bank_closing = 0;
                       ?>
                       @foreach ($bank_account_heads as $key=>  $bank_account_head)
                       @foreach ($bank_account_head->bank_position as $index => $bank_position)
                       <?php 
                            $opening_bank = $date_range == 0? 0: $opening[$index]->opening;
                       ?>
                          <tr>
                           <td>{{ $key +1 }}</td>
                           <td>{{ $bank_account_head->account_head }}</td>
                           <td>{{ $opening_bank }}</td>
                           <td></td>
                           <td></td>
                           <td>{{ $opening_bank + $bank_position->debit - $bank_position->credit  }}</td>
                       </tr>  
                       <?php 
                           $total_bank_position += $opening_bank;

                           $total_bank_closing += $opening_bank + $bank_position->debit - $bank_position->credit;
                       ?>
                       @endforeach
                        
                       @endforeach
                   </tbody>
                   <tfoot>
                       <tr>
                           <th></th>
                           <th>Total Bank Balance</th>
                           <th>{{ $total_bank_position }}</th>
                           <th></th>
                           <th></th>
                           <th>{{ $total_bank_closing }}</th>
                       </tr>
                   </tfoot>
               </table>
           </div>
       </div>
   </div>
</body>
<script type="text/javascript">
  function printDocument() {
      window.print();
  }
</script>
</html>