<div class="row">
    <div class=col-lg-12>
       <!-- col-lg-12 start here -->
       <table id=basic-datatables class="table table-striped table-bordered" cellspacing=0 width=100%>
          <tbody>
             <tr>
                <td>Company Name
                <td>{{@$leadInfo->company_name}}
             <tr>
                <td>Target Product
                <td>{{implode(", ", $crmLeadProducts)}}
             <tr>
                <td>Opportunity
                <td>{{@$leadInfo->currency." ".@$leadInfo->lead_opportunities_amount}}
             <tr>
                <td>Address
                <td>{{@$leadInfo->lead_address}}  
             <tr>
                <td>Country
                <td>{{@$leadInfo->country}} 
             <tr>
                <td>Mobile
                <td>{{@$leadInfo->lead_mobile}} 
             <tr>
                <td>Email
                <td>{{@$leadInfo->email_address}}    
       </table>
       <!-- End .panel -->
    </div>
</div>