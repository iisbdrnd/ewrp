<style>
    body {
        margin: 0px;
        padding: 0px;
    }

    #topLoader {
        width: 256px;
        height: 256px;
        margin-bottom: 32px;
    }

    #container {
        width: 274px;
        padding: 10px;
        margin-left: auto;
        margin-right: auto;
    }

    #animateButton {
        width: 256px;
    }
</style>

<?php $panelTitle = "Compile Employee Organogram"; ?>
@include("panelStart")
<div id="container">
    {{csrf_field()}}
    <div id="topLoader"></div>
    <button id="animateButton" class="btn @if($project->compile_status==0){{'btn-danger'}}@else{{'btn-success'}}@endif">Compile For Report</button>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(function() {
        var $topLoader = $("#topLoader").percentageLoader({width: 256, height: 256, controllable : true, progress : 0, onProgressUpdate : function(val) {
            $topLoader.setValue(Math.round(val * 100.0));
        }});

        var topLoaderRunning = false;
        var kb = 0;
        var totalKb = parseInt({{$ttlUser}})*50;
        if(totalKb<500) { totalKb = 500; }
        $("#animateButton").click(function() {
            topLoaderRunning = true;
            kb = 0;
            $topLoader.setProgress(0);
            $topLoader.setValue('0kb');
            setTimeout(animateLoader, 25);

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "{{route('admin.compileEmployeeOrganogram')}}",
                data: {'_token': $("input[name='_token']").val()},
                cache: false,
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("Cancelled", thrownError, "error");
                },
                complete: function () {
                    kb = totalKb;
                    animateLoader();
                    topLoaderRunning = false;
                },
                success: function (data) {
                    if(data.msgType=='success') {
                        $("#animateButton").removeClass("btn-danger").addClass("btn-success");
                        $.gritter.add({
                            title: "Done !!!",
                            text: data.messege,
                            time: "",
                            close_icon: "entypo-icon-cancel s12",
                            icon: "icomoon-icon-checkmark-3",
                            class_name: "success-notice"
                        });
                    } else if(data.msgType=='danger') {
                        $.gritter.add({
                            title: "Sorry !!!",
                            text: data.messege,
                            time: "",
                            close_icon: "entypo-icon-cancel s12",
                            icon: "icomoon-icon-close",
                            class_name: "error-notice"
                        });
                    }
                }
            });
        });

        var animateLoader = function() {
            if (topLoaderRunning) {
                if((kb / totalKb)<0.98) {
                    kb += 17;
                }
                $topLoader.setProgress(kb / totalKb);
                var userKb = Math.round(kb/4);
                $topLoader.setValue(userKb.toString() + 'kb');

                if (kb < totalKb) {
                    setTimeout(animateLoader, 25);
                } else {
                    topLoaderRunning = false;
                }
            }
        }
    });
</script>