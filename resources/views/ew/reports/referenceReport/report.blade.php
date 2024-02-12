<html moznomarginboxes mozdisallowselectionprint>
        <head>
            <link rel="stylesheet" href="{!! asset('public/css/print-page.css') !!}">
            <style type="text/css">
                @media print {
                    .header {
                        margin-top: -40px !important;
                    }
                }
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
                    margin: 20px auto;
                    font-size: 16px;
                }
                .border-none{
                    border: 0px!important;
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
            <div class="print_button">
                <button class="btn btn-default" onclick="printDocument()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <a href="{{route('ew.referenceReportPdf')}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
            </div>
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
        </body>
</html>

<script type="text/javascript">
    function printDocument() {
        window.print();
    }
</script>