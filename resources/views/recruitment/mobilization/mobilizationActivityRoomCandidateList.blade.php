<style>
#submitButton, #directContactButton, #activitiesNoteFormButton{
    display:none;
}
</style>
@include("urlParaMeter")
<?php $tableTitle = Helper::projects($projectId)->project_name; $loadUrl = "mobilizationActivityRoomCandidateListData?projectId=".$projectId; ?>
@include("dataListFrame")
<script>
</script>


