@include("urlParaMeter")
<?php $tableTitle =@Helper::projects($projectId)->project_name; $loadUrl = "accountsTransferCandidateData?projectId=".$projectId; ?>
@include("dataListFrame")