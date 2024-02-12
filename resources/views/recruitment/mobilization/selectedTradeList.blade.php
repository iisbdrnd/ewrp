<style>
  #panelId .panel-heading .panel-controls .panel-refresh {
    display: none;
  }

  div.dataTables_filter label {

    /* float: right !important;
  margin-top: -34px !important; */
  }


  /* DataTable print, excel, csv and pdf button customizing design */
  div.dt-buttons {

    /* position: absolute !important; */
    margin-left: 40% !important;
  }

  label {
    margin-bottom: 9px;
  }

  a.buttons-copy {
    background: #056E11;
    color: #fff;
    border: 1px solid #36A9CA !important;
  }

  a.buttons-excel {
    background: #056E11;
    color: #fff;
    border: 1px solid #1C6C49 !important;
  }

  a.buttons-csv {
    background: #056E11;
    color: #fff;
    border: 1px solid #056E18 !important;
  }

  a.buttons-pdf {
    background: #056E11;
    color: #fff;
    border: 1px solid #056E18 !important;
  }

  a.buttons-print {
    background: #056E11;
    color: #fff;
    border: 1px solid #0F5BA1 !important;
  }

  a.buttons-copy:hover {
    background: #056E11 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-excel:hover {
    background: #1C6C49 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-csv:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-pdf:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }

  a.buttons-print:hover {
    background: #056E18 !important;
    color: #fff !important;
    border: 1px solid !important;
  }
</style>
<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: 100%; height: auto;">
    <div class="table-responsive" style="overflow: hidden; width: 100%; height: auto;">
        <table id="tades" class="table table-hover">
            <thead>
                <tr>
                    <th class="per5">#</th>
                    <th class="per40">Trade</th>
                    <th class="per40">No. of Candidates</th>
                    <th class="per40">Trade Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($selectedTrades as $index => $selectedTrade)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$selectedTrade->trade_name}}</td>
                    <td>{{@Helper::noOfCandidates($projectId, $selectedTrade->id)}}</td>
                    <td>{{@Helper::noOfTradeQty($projectId, $selectedTrade->id)}}</td>
                </tr>
                @endforeach
            </tbody>
             <tfoot>
            <tr>
                <th>#</th>
                <th>Trade</th>
                <th>No. of Candidates</th>
                <th>Trade Quantity</th>
            </tr>
        </tfoot>
        </table>
    </div>
    <div class="slimScrollBar ui-draggable"
        style="background: rgb(243, 243, 243); height: 5px; position: absolute; bottom: 3px; opacity: 0.4; display: none; border-radius: 5px; z-index: 99; width: 508px; left: 0px;">
    </div>
    <div class="slimScrollRail"
        style="width: 100%; height: 5px; position: absolute; bottom: 3px; display: none; border-radius: 5px; background: rgb(51, 51, 51); opacity: 0.3; z-index: 90;">
    </div>
</div>
<script>
  $(document).ready(function () {

    $('#tades').DataTable({
      dom: 'Blfrtip',
    searching: false,
    "paging":   true,
    "ordering": false,
    // "info":     false,
      buttons: [{
          extend: 'excelHtml5',
          footer: false,
        //   exportOptions: {
        //     /* ANY FILE ONLY SHOWING THESE COLUMN */
        //     // columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        //   }
        },
        // {
        //   extend: 'csvHtml5',
        //   footer: false,
        //   exportOptions: {
        //     columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
        //   }

        // },
        // {
        //   extend: 'pdfHtml5',
        //   footer: false,
        //   orientation: 'landscape',
        //   pageSize: 'LEGAL',
        // //   exportOptions: {
        // //     // columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        // //   }
        // },
        {
          extend: 'print',
          footer: false,
        //   exportOptions: {
        //     // columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        //   }
        }
      ],
      //   columnDefs: [
      //   {
      //       targets: -1,
      //       className: 'dt-body-right'
      //   }
      // ]  
    });
  });
</script>