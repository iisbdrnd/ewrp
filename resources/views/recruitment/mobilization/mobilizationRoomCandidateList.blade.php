@include("urlParaMeter")
<?php $tableTitle = Helper::projects($projectId)->project_name; $loadUrl = "mobilizationRoomCandidateData?projectId=".$projectId."&"."projectCountryId=".$projectCountryId; ?>
@include("dataListFrame")
