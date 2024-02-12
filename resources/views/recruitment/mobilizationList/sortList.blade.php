<?php $panelTitle = "Payment Status"; ?>
@include("panelStart")
<form type="update" callback="ConfigureStatus" action="{{route('recruit.generateMobilizeListAction')}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}

<div class="row mt20">
    <div class="dhe-example-section-content">
        <div id="example-1-2">
            <div class="column left first col-lg-6 col-lg-offset-2">
                <h4 class="text-center text-info">Default Mobilize List</h4><hr>
                <ul class="sortable-list" id="defaultItem">
                    <?php $i=0; ?>
                    @foreach($mobilizations as $mobilization)
                       <?php $i++ ?>
                        <li class="sortable-item">
                            <input type="hidden" name="mobilization_id[]" value="{{ $mobilization->id }}">
                            {{ $mobilization->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- END: XHTML for example 1.2 -->
    </div>
</div>

@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function() {
        // Example 1.2: Sortable and connectable lists
        $('#example-1-2 .sortable-list').sortable({
            connectWith: '#example-1-2 .sortable-list',

        });

    });

    function ConfigureStatus(data) {
        bootbox.hideAll();
    };



</script>

