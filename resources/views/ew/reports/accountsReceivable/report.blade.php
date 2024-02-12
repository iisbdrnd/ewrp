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
                <p class="project">Project: {{Helper::projectName($project_id)}}</p>
                <p class="title">Accounts Receivable Balance @if($date_range){{'upto '.$date}}@endif</p>
            </div>
            <div class="report-data">
                <table class="table table-responsive">
                    <tr>
                        <td width="10%"><strong>Code</strong></td>
                        <td><strong>Particulars</strong></td>
                        <td width="12%" class="text-center"><strong>Balance</strong></td>
                    </tr>
                    <?php $total_balance = 0; ?>
                    @foreach($ledgers as $ledger)
                        <?php
                            $balance = $ledger->debit_amount-$ledger->credit_amount;
                            $total_balance += $balance;
                        ?>
                        <tr>
                            <td>{{$ledger->account_code}}</td>
                            <td>{{$chart_of_accounts[$ledger->account_code]->account_head}}</td>
                            <td class="text-center">{{number_format($balance,2)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
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
