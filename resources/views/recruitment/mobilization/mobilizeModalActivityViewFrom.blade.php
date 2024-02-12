<style>
    #submitButton, #directContactButton, #activitiesNoteFormButton{
        display:none;
    }
</style>
<input type="hidden" name="projectId" class="projectId">
<input type="hidden" name="candidateId" class="candidateId">
<input type="hidden" name="mobilizeId" class="mobilizeId">
<input type="hidden" name="templateType" id="templateType">
<main class="topTabs" id="detailsPanel">
  <input id="tab1" type="radio" name="tabs" value="1" checked="">
  <label id="activities" class="topTabs-label" for="tab1">Activities</label>
  <input id="tab2" type="radio" name="tabs" value="2">
  <label class="topTabs-label" id="activityDetails" for="tab2">Activity Details</label>
  <section id="content1">
    <div class="tabs mb20">
      <ul id="myTab2" class="nav nav-tabs nav-justified">
        <li class="active"><a href="#call" data-toggle="tab" aria-expanded="false" id="callDetails">Call</a></li>
        <li class="" id="direct-contact-form"><a href="#directContact" data-toggle="tab" aria-expanded="false" id="directDetails">At Office</a></li>
        <li class=""><a href="#note" id="noteActivities" data-toggle="tab" aria-expanded="false">Note</a></li>
      </ul>
      <div id="myTabContent2" class="tab-content">
        <div class="tab-pane fade active in" id="call">
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
        <li class="active"><a href="#callActivityDetails" data-toggle="tab" aria-expanded="false">Call</a></li>
        <li class=""><a href="#direct-contact-details" data-toggle="tab" aria-expanded="false">At Office </a></li>
        <li class=""><a href="#noteActivityDetails" data-toggle="tab" aria-expanded="false">Note</a></li>
      </ul>
      <div id="myTabContent2" class="tab-content">
         
        <div class="tab-pane fade active in" id="callActivityDetails">
          
        </div>
        <div class="tab-pane fade" id="direct-contact-details">

        </div>
        <div class="tab-pane fade" id="noteActivityDetails">

        </div>
      </div>
    </div>
  </section>
</main>
<script>
  
$('a#callDetails').on('click', function () {
        $('#templateType').val(1);
    });
    $('a#directDetails').on('click', function () {
        $('#templateType').val(2);
    });
    $('a#noteActivities').on('click', function () {
        $('#templateType').val(3);
    });
</script>
    