<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <!--<meta charset="utf-8">-->
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
                font-size: 11px;
            }
            .contact {
                font-size: 11px;
            }
            .title {
                font-size: 16px;
                text-transform: uppercase;
            }
            .border-none{
                border: 0px!important;
            }
            table td {
                font-size: {{$table_font}}
            }
            body,td,th {
                font-size: 12px;
            }
            .can-info-left tr td{
                font-weight: normal!important;
            }

            .can-info-right tr td{
                font-weight: normal!important;
            }

            .td-talign-r{
                text-align: right;
            }

            .table-border{
                border: 1px solid #ccc;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <?php echo Helper::companyDetails(); ?>
                <p class="title">Reference Report</p>
            </div>
            <div class="report-data" style="margin-top: 10px;">
                <table class="table table-responsive">
                    <tr class="reportHead">
                        <td width="15%" class="text-center"><strong>CODE</strong></td>
                        <td width="55%"><strong>NAME</strong></td>
                        <td width="30%"><strong>MOBILE NUMBER</strong></td>
                    </tr>
                    @foreach($references as $reference)
                    <tr class="reportHead">
                        <td class="text-center">{{$sn++}}</td>
                        <td>{{$reference->reference_name}}</td>
                        <td>{{$reference->reference_phone}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </body>
</html>