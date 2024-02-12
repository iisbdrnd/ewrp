<style type="text/css">
   .tabs .tab-content{
      border:none !important;
   }
</style>
<div class="row">
   <div class="col-lg-12 sortable-layout ui-sortable">
      <main class="topTabs" id="detailsPanel">
         <input id="tab1" type="radio" name="tabs" value="1" checked="">
         <label id="activities" class="topTabs-label" for="tab1">Activities</label>
         <input id="tab2" type="radio" name="tabs" value="2">
         <label class="topTabs-label" id="activityDetails" for="tab2">Activity Details</label>
         <section id="content1">
            <div class="tabs mb20">
               <ul id="myTab2" class="nav nav-tabs nav-justified">
                  <li class="active"><a href="#call" data-toggle="tab" aria-expanded="false">Call</a></li>
                  <li class=""  id="direct-contact-form"><a href="#directContact" data-toggle="tab" aria-expanded="false">At Office</a></li>
                  <li class=""><a href="#note" id="noteActivities" data-toggle="tab" aria-expanded="false">Note</a></li>
               </ul>
                <div id="myTabContent2" class="tab-content">
      		         <div  class="tab-pane fade active in" id="call">
      		         	<div id="call-activity"></div>
      		         </div>
      		         <div class="tab-pane fade" id="directContact">
      		           <div id="direct-call-activity-form"></div>
      		         </div>
      		         <div class="tab-pane fade" id="note">
      		            <div id="note-activity-form"></div>
      		         </div>
		          </div>
            </div>
         </section> 
         <section id="content2">
            <div class="tabs mb20">
               <ul id="myTab2" class="nav nav-tabs nav-justified">
                  <li class="active" ><a id="callActivity" href="#callActivityDetails" data-toggle="tab" aria-expanded="false">Call</a></li>
                  <li class=""><a href="#direct-contact-details" data-toggle="tab" aria-expanded="false">At Office </a></li>
                  <li class=""><a href="#noteActivityDetails" data-toggle="tab" aria-expanded="false">Note</a></li>
               </ul>
               <div id="myTabContent2" class="tab-content">
                     <div  class="tab-pane fade active in" id="callActivityDetails">
                       
                     </div>
                     <div class="tab-pane fade" id="direct-contact-details">
                        
                     </div>
                     <div class="tab-pane fade" id="noteActivityDetails">
                        
                     </div>
                </div>
            </div>
         </section> 
      </main>
   </div>
</div>

<script type="text/javascript">

   $('.topTabs-label').on('click', function(){
      let url = '{{ url('recruitment/mobilization/call-activities/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';
      let callDetailsUrl = '{{ url('recruitment/mobilization/call-activities-details/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';

      let directCallDetailsUrl = '{{ url('recruitment/mobilization/direct-contact-details/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';

      let noteActivityDetailsUrl = '{{ url('recruitment/mobilization/note-activity-details/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';
      
      $.ajax({
         mimeType: 'text/html; charset=utf-8',
         type: 'GET',
         url:url,
         data: {mobilizeId:i},
         processData: false,
         contentType: false,
         success: function(data){
          $('#call-activity').load(url); 
          $('#callActivityDetails').load(callDetailsUrl); 
         $('#direct-contact-details').load(directCallDetailsUrl);
         $('#noteActivityDetails').load(noteActivityDetailsUrl);
        }
      });
   });

   $('#direct-contact-form').on('click', function(){
      var url = '{{ url('recruitment/mobilization/direct-contact/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';
      $.ajax({
         mimeType: 'text/html; charset=utf-8',
         type: 'GET',
         url:url,
         data: {},
         processData: false,
         contentType: false,
         success: function(data){
          $('#direct-call-activity-form').load(url);
        }
      });
   });

   $('#direct-contact-details').on('click', function(){
      var url = '{{ url('recruitment/mobilization/direct-contact-details/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';
      $.ajax({
         mimeType: 'text/html; charset=utf-8',
         type: 'GET',
         url:url,
         data: {},
         processData: false,
         contentType: false,
         success: function(data){
          $('#direct-contact-details').load(url);
        }
      });
   }); 

   $('#noteActivities').on('click', function(){
      var url = '{{ url('recruitment/mobilization/note-activities/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';

      $.ajax({
         mimeType: 'text/html; charset=utf-8',
         type: 'GET',
         url:url,
         data: {},
         processData: false,
         contentType: false,
         success: function(data){
          $('#note-activity-form').load(url);
        }
      });
   });


 $('#activityDetails').on('click', function(){
      var url = '{{ url('recruitment/mobilization/note-activities/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}';

      $.ajax({
         mimeType: 'text/html; charset=utf-8',
         type: 'GET',
         url:url,
         data: {},
         processData: false,
         contentType: false,
         success: function(data){
          $('#note-activity-form').load(url);
        }
      });
   });

$("#activities" ).trigger( "click" );
$('#guidance1').show();
$('#guidance2').hide();
$('#invitation').on('click',function(){
$('#guidance1').show();
$('#guidance2').hide();
});
$('#slipReceived').on('click',function(){
$('#guidance2').show();
$('#guidance1').hide();
});

</script>