<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Report Preview</title>
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
            table tr:last-child {
               border: none !important;
            }
            table tr:last-child td {
               border: none !important;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="print_button">
                <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <a href="{{$pdf_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
            </div>
            <div class="header">
                <?php echo Helper::companyDetails(); ?>
                <p class="title">Ticket Bill Preview</p>
                <p class="title">Aviation Name: {{$aviation_name}}</p>
            </div>
            <div class="report-data">
                <table class="table table-responsive">
                    <tr>
                        <!-- <td><strong>Aviation Name</strong></td> -->
                        <td><strong>Project</strong></td>
                        <td><strong>Candidate</strong></td>
                        <td><strong>Bill Type</strong></td>
                        <td><strong>NOS</strong></td>
                        <td><strong>Remarks</strong></td>
                        <td width="12%" class="text-center"><strong>Payment Amount</strong></td>
                    </tr>
                        <?php $total_balance = 0; ?>
                        @foreach($ledgers as $ledgers)

                        <?php
                            $balance = $ledgers->bill_amount;
                            $total_balance += $balance;
                        ?>
                        <tr>
                            <!-- <td>{{$ledgers->company_name}}[{{$ledgers->account_code}}]</td> -->
                            <td>{{$ledgers->project_name}}</td>
                            <td>{{$ledgers->candidate_name}}</td>
                            <td>{{$ledgers->type_name}}</td>
                            <td>{{$ledgers->nos}}</td>
                            <td>{{$ledgers->remarks}}</td>
                            <td class="text-center">{{number_format($ledgers->bill_amount,2)}}</td>
                        </tr>
                        @endforeach
                  
                    <tr>
                        <td colspan="5" class="text-right"><strong>Total</strong></td>
                        <td class="text-center"><strong>{{number_format($total_balance,2)}}</strong></td>
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
