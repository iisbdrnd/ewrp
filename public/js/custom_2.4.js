//------------- custom.js -------------//

function getDuration(startDate, endDate) {
    var duration='', durationValue=0;
    var startDate = dateTimeObj(startDate);
    var endDate = dateTimeObj(endDate);

    if(startDate && endDate) {
        var diff = endDate.getTime() - startDate.getTime();

        if(diff>=60000) {
            var day=0, hour=0, minute=0;
            var ttlMinute = diff/60000;
            durationValue = ttlMinute;
            var minute = ttlMinute%60;
            ttlMinute -= minute;
            if(ttlMinute>=60) {
                var ttlHour = ttlMinute/60;
                var hour = ttlHour%24;
                ttlHour -= hour;
                if(ttlHour>=24) {
                    var day = ttlHour/24;
                }
            }

            if(day==7) {
                duration+='1 week ';
            } else if(day>0) {
                duration+= (day==1) ? '1 day ' : day+' days ';
            }
            if(hour>0) {
                duration+= (hour==1) ? '1 hour ' : hour+' hours ';
            }
            if(minute>0) {
                duration+= (minute==1) ? '1 minute' : minute+' minutes';
            }
        }
    }
    return {duration:duration, durationValue:durationValue};
}

function dateTimeObj(fullDate) {
    var output = false;
    var fullDateTime = fullDate.split(" ");
    if(fullDateTime.length==3) {
        var fullDate = fullDateTime[0].split("/");
        var fullTime = fullDateTime[1].split(":");

        if(fullDate.length==3 && fullTime.length==2) {
            var day = parseInt(fullDate[0]);
            var month = parseInt(fullDate[1]) - 1;
            var year = parseInt(fullDate[2]);
            var hour = parseInt(fullTime[0]);
            var minute = parseInt(fullTime[1]);

            if(fullDateTime[2]=='AM' && hour==12) { hour=0; } else if(fullDateTime[2]=='PM' && hour!=12) { hour+=12; }

            var output = new Date(year, month, day, hour, minute);
        }
    }
    return output;
}

function dateFormat(dateObj) {
    var amPm = (dateObj.getHours()<12) ? 'AM' : 'PM';
    var hour = (dateObj.getHours()>12) ? dateObj.getHours()-12 : dateObj.getHours();
    hour = (hour==0) ? 12 : hour;
    var month = dateObj.getMonth()+1;
    month = (month<10) ? '0'+month : month;
    var minute = (dateObj.getMinutes()<10) ? '0'+dateObj.getMinutes() : dateObj.getMinutes();
    return dateObj.getDate()+'/'+month+'/'+dateObj.getFullYear()+' '+hour+':'+minute+' '+amPm;
}

function dashButtonCleaner() {
    $(".dashButton").remove();
}

function loadTimeDuration(selector, loadUrl, cur_date) {
    var curType = "month";
    if($(".btn-day").hasClass("fc-state-active")) { curType = "day"; }
    else if($(".btn-week").hasClass("fc-state-active")) { curType = "week"; }
    selector.on("click", ".btn-day", function(e) {
        e.preventDefault();
        if(!$(this).hasClass("fc-state-active")) {
            var inputArray = {type: "day", cur_date: cur_date};
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-week", function(e) {
        e.preventDefault();
        if(!$(this).hasClass("fc-state-active")) {
            var inputArray = {type: "week", cur_date: cur_date};
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-month", function(e) {
        e.preventDefault();
        if(!$(this).hasClass("fc-state-active")) {
            var inputArray = {type: "month", cur_date: cur_date};
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-previous", function(e) {
        e.preventDefault();
        var inputArray = {type: curType, cur_date: cur_date, direction: "previous"};
        loadDurationData(selector, loadUrl, inputArray);
    });
    selector.on("click", ".btn-next", function(e) {
        e.preventDefault();
        var inputArray = {type: curType, cur_date: cur_date, direction: "next"};
        loadDurationData(selector, loadUrl, inputArray);
    });
    selector.on("click", ".btn-today", function(e) {
        e.preventDefault();
        if(!$(this).hasClass("fc-state-disabled")) {
            var inputArray = {type: curType};
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
}

function loadDurationData(selector, loadUrl, inputArray) {
    if (typeof inputArray===typeof undefined || inputArray==="") { inputArray={}; }
    preLoader(selector);
    $.ajax({
        url: appUrl.getSiteAction('/'+loadUrl),
        data: inputArray,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if(parseInt(data)===0) {
                location.replace(appUrl.loginPage());
            } else {
                preLoaderHide(selector);
                selector.parents(".panel-body").html(data);
            }
        }
    });
}

function activityAssignNotification(data) {
    if(data.notifyNow) {
        socket.emit("notify now", {notifyType:"assignNotification", userData:data.notifyUserData});
    }
}

function notificationAlert(data, i=0) {
    setTimeout(function () {
        var dataObj = data[i];
        var notification = new Notification(dataObj.title, {
            body: dataObj.details,
            // icon: (dataObj.type=="emergencySchedule") ? appUrl.baseUrl('/public/img/meeting_notify.png') : appUrl.baseUrl('/public/img/call_notify.png')
            icon: appUrl.baseUrl('/public/img/meeting_notify.png')
        });
        document.getElementById("notifySound").play();

        notification.onclick = function () {
            swal({
                title: "Are you sure?",
                text: "You want to permanently close this notification!",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, close it!",
                closeOnConfirm: true
            },
            function(){
                $.ajax({
                    url: appUrl.baseUrl('/close-notification'),
                    data: {id: dataObj.id, type: dataObj.type},
                    type: 'GET',
                    dataType: "json",
                    error: function(jqXHR, textStatus, errorThrown) {
                        swal("Cancelled", errorThrown, "error");
                    }
                });
            });
        };

        //Loop
        i++;
        if(i<data.length) { notificationAlert(data, i); }
    }, 500);
}

function notification(data, notifyType) {
    if (!Notification) {
        if(notifyType=="alertNotification") {
            swal("Sorry!!", "Desktop notifications not available in your browser.", "error");
        }
    } else {
        if (Notification.permission !== "granted")
        {
            Notification.requestPermission();
        } else {
            if(notifyType=="alertNotification") {
                notificationAlert(data);
            }
        }
    }
    if(notifyType=="assignNotification") {
        headerAssignNotification(data);
    }
}

function headerAssignNotification(data) {
    alert(data.temi);
    $('a[href="maintenance-index"]').find('.notification').html(data.temi);
    var notification = new Notification(data.title, {
            body: data.details,
            icon: appUrl.baseUrl('/public/img/meeting_notify.png')
        });
        document.getElementById("notifySound").play();

}

function multipleFileUpload(selector) {
    var $attachment_area = $("#attachment_area_"+selector);
    var $selector = $('#'+selector);
    var filePath = $selector.attr('file-path');
    var csrf_token = $selector.attr('_token');
    var callback = $selector.attr('callback');
	//console.log(selector);
	$(function() {
		var btnUpload = $selector;
		
		if(typeof callback!==typeof undefined) {
			var callback_param  = {};
			$selector.each(function() {
				$.each(this.attributes, function(index) {
					if(this.specified) {
						var callback_param_identifier = this.name.split('-');
						if(callback_param_identifier[0]=='callback' && callback_param_identifier.length > 1) {
							callback_param_identifier.shift();
							callback_param[callback_param_identifier.join('-')] = this.value;
						}
					}
				});
			});
			//console.log(callback_param);
		}
		
		var status = $('#status_'+selector);
		new AjaxUpload(btnUpload, {
			action: appUrl.baseUrl('/customFileUpload'),
			name: 'uploadfile',
			data: {"filePath": filePath, "_token": csrf_token, "multiple_file": true},
			onSubmit: function(file, ext) {
				if (! (ext && /^(jpg|png|jpeg|gif|doc|docx|pdf|ppt|pptx|csv|xls|xlsx|zip|rar|tar)$/.test(ext))){
					// extension is not allowed
					status.html('<div class="alert alert-warning"><i class="fa fa-warning mrl"></i>File type is not allowed.<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
					return false;
				}
				btnUpload.button('loading');
			},
			onComplete: function(file, response) {
				//console.log(response);
				response=response.split('~');
				//Add uploaded file to list
				if(response[1]==="success") {
					
					if($attachment_area.find(".no-attachments").is(":visible")) {
						$attachment_area.find(".no-attachments").hide();
					}
					
					var file_name = response[2];
					var file_name_array = file_name.split(".");
					var file_ext = file_name_array[file_name_array.length-1];
					
					if(file_ext=='jpg' || file_ext=='jpeg' || file_ext=='png' || file_ext=='gif') {
						var upload_thumb = appUrl.baseUrl('/'+filePath+'/thumb/'+file_name);
						var imgClass = 'class=igniterImg';
						var file_source = appUrl.baseUrl('/'+filePath+'/'+file_name);
					} else {
						var upload_thumb = getFileThumb(file_ext);
						var imgClass = '';
						var file_source = 'https://docs.google.com/viewerng/viewer?url='+appUrl.baseUrl('/'+filePath+'/'+file_name);
					}
					
					if(typeof callback!==typeof undefined) {
						callback_param.file_name = file_name;
						callback_param.file_path = filePath;
						callback_param.file_real_name = file;
						callback_param.file_size = response[3];
						callBackFunction(callback, [callback_param]);
					}
					
					$attachment_area.prepend('<div class="attachment-item clearfix image"><div style="background-image: url('+upload_thumb+')" class="attachment-img"></div><div class="attachment-content"><div class="close_x" ><span class="fa fa-close remove_files" file_name="'+file_name+'" filePath="'+filePath+'" ></span> </div><div class="attachment-title" ><a '+imgClass+' href="'+file_source+'" target="_blank">'+file+'</a></div> <div class="attachment-date"> Uploaded '+response[5]+' </div> <div class="attachment-size"> '+response[4]+' </div> <input type="hidden" name="attachment[]" value="'+file_name+'"/> <input type="hidden" name="attachment_real_name[]" value="'+file+'"/> <input type="hidden" name="attachment_size[]" value="'+response[3]+'"/> </div>');
					//On completion clear the status
					btnUpload.button('reset');
				} else {
					status.html('<div class="alert alert-warning "><i class="fa fa-warning mrl"></i>'+response[1]+'<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
				}
			}
		});
	});
	
	//Remove
	var removeCallback = $selector.attr('remove-callback');
	$attachment_area.on('click', '.remove_files', function(e) {
		e.preventDefault();
		var auto_remove = $selector.attr('auto-remove');
		var file_name = $(this).attr('file_name');
		var filePath = $(this).attr('filePath');
		
        $(this).parents('.attachment-item').remove();
        var $attachment_item = $attachment_area.find(".attachment-item");
        if($attachment_item.length == 0) {
            $('.no-attachments').show();
        }

        if(typeof auto_remove===typeof undefined || auto_remove=='true') { //Default = True
            $.ajax({
                url: appUrl.baseUrl('/customFileUnlink'),
                type: "POST",
                data: {filePath: filePath, image:file_name, _token: csrf_token}
            });
        }
	});
}

function activitiesFileUnlink(selector, removeLink) {
    var $attachment_area = $("#attachment_area_"+selector);
	$attachment_area.on('click', '.remove_files', function(e) {
		var file_id = $(this).attr('file_id');
		var file_name = $(this).attr('file_name');
		var file_name_array = file_name.split('.');
		$.ajax({
			url: appUrl.baseUrl('/'+removeLink+'/'+file_id),
			type: "GET",
			dataType: "json",
		});
		$attachment_area.find("#"+file_name_array[0]).remove();
		var $attachment_item = $attachment_area.find(".attachment-item");
		if($attachment_item.length == 0) {
			$('.no-attachments').show();
		}
	});
}

function getFileThumb(file_ext) {
	var thumb = appUrl.baseUrl('/public/file_icon/zip.png'); //default
	if(file_ext=='doc' || file_ext=='docx') {
		thumb = appUrl.baseUrl('/public/file_icon/doc.png');
	} else if(file_ext=='ppt' || file_ext=='pptx') {
		thumb = appUrl.baseUrl('/public/file_icon/ppt.png');
	} else if(file_ext=='xls' || file_ext=='xlsx') {
		thumb = appUrl.baseUrl('/public/file_icon/xls.png');
	} else if(file_ext=='zip' || file_ext=='rar' || file_ext=='tar') {
		thumb = appUrl.baseUrl('/public/file_icon/zip.png');
	} else if(file_ext=='pdf') {
		thumb = appUrl.baseUrl('/public/file_icon/pdf.png');
	} else if(file_ext=='csv') {
		thumb = appUrl.baseUrl('/public/file_icon/csv.png');
	}
	return thumb;
}

//For Tab Load of Details Panel
function detailsPanelTabLoad($selector, tabLoad, tabNo, loadLink) {
    $selector.parents('.panel').first().find('.panel-title').html($('#detailsPanel').find('label[for="tab'+tabNo+'"]').html());

    if(tabLoad[tabNo]) {
        tabLoad[tabNo] = false;
        $.ajax({
            url: appUrl.getSiteAction(loadLink),
            type: 'GET',
            dataType: "html",
            success: function(data) {
                if(parseInt(data)===0) {
                    location.replace(appUrl.loginPage(loadLink));
                } else {
                    $('#content'+tabNo).html(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal("Cancelled", errorThrown, "error");
            }
        });
    }
}

function customDataTableResource(columnList) {
    var columns = [{
            data: 'id',
            searchable: false,
            orderable: false
        }];
    var html_th = '<th>SL</th>';
    var i = 1;
    $.each(columnList, function(index, value){
        columns[i++] = {data: index};
        html_th += '<th>'+value+'</th>';
    });
    return {columns:columns, html_th:html_th};
}

function customDataTable(allData) {
    var $selector=allData.selector, columns=allData.columns;
    var tableData=(typeof allData.data!==typeof undefined) ? allData.data : false;
    var tableTitle=(typeof allData.title!==typeof undefined) ? allData.title : "";
    var type=(typeof allData.type!==typeof undefined) ? allData.type : "json";
    var autoSl=(typeof allData.autoSl!==typeof undefined) ? allData.autoSl : true;
    var ordering=(typeof allData.ordering!==typeof undefined) ? allData.ordering : true;
    var searching=(typeof allData.searching!==typeof undefined) ? allData.searching : true;

    //Show Details
    $selector.on("click", ".show-details", function(e){
        var detailsUrl = $(this).attr("url");
        var title = $(this).html();
        var data = false;
        var modalSize = "";
        showDetails(title, detailsUrl, data, modalSize);

        e.preventDefault();
    });

    //Data Table
    if(type=="json") {
        var tableResource = customDataTableResource(columns);
        var columns=tableResource.columns, html_th=tableResource.html_th;
        $selector.html('<table id="tabletools" class="table table-striped table-bordered" cellspacing=0 width=100%><thead><tr>'+html_th+'</tr></thead><tfoot><tr>'+html_th+'</tr></tfoot></table>');
    }

    var sl=1;
    var dataTable = {
        oLanguage: {
            sSearch: "",
            sLengthMenu: "<span>_MENU_</span>"
        },
        sDom: "<'row'<'col-md-1 col-xs-12 'l><'col-md-3 col-xs-12'f><'col-md-8 col-xs-12 text-right'B>>t<'row'<'col-md-4 col-xs-12'i><'col-md-8 col-xs-12'p>>",
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: (autoSl) ? {
                    format: {
                        body: function ( inner, coldex, rowdex ) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if(autoSl && rowdex==0) {
                                return coldex+1;
                            } else if(el) {
                                var result='';
                                $.each( el, function (index, item) {
                                    if(item) {
                                        result = result+item.textContent;
                                    }
                                });
                                return result;
                            } else {
                                return inner;
                            }
                        }
                    }
                } : {},
            },
            {
                extend: 'csv',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: (autoSl) ? { columns: ':not([aria-label="SL"])' } : {}
            },
            {
                extend: 'excel',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: (autoSl) ? { columns: ':not([aria-label="SL"])' } : {}
            },
            {
                extend: 'pdf',
                text:'Portrait PDF',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function ( inner, coldex, rowdex ) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if(autoSl && rowdex==0) {
                                return coldex+1;
                            } else if(el) {
                                var result = '';
                                $.each( el, function (index, item) {
                                    if(item) {
                                        if (item.nodeName == '#text') {
                                            result = result+item.textContent;
                                        } else {
                                            result = result + item.outerHTML;
                                        }
                                    }
                                });
                                return result;
                            } else {
                                return inner;
                            }
                        }
                    }
                },
                customize : function(doc){
                    $.each(doc.content[1].table.body, function (index, item) {
                        for(var i=item.length-1; i>=0; i--){
                            if(item[i]) {
                                var item2 = item[i];
                                var $aa = $.parseHTML(item2.text);
                                if($aa) {
                                    if (($aa[0].attributes) && $aa[0].attributes[0].nodeName == 'hide') {
                                        item.splice(i, 1);
                                    } else {
                                        if($aa[0].attributes) {
                                            if ($aa[0].attributes[0].nodeName == 'colspan') {
                                                item2.colSpan = $aa[0].attributes[0].textContent;
                                            }
                                        }
                                        if ($aa[0].nodeName == 'STRONG') {
                                            item2.bold = true;
                                        }
                                        if($aa[0].nodeName != '#text') {
                                            item2.text = $aa[0].textContent;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    var colCount = new Array(), j=1;
                    $("#tabletools").find('tbody tr:first-child td').each(function(){
                        if($(this).is(':visible')){
                            if($(this).attr('colspan')){
                                for(var i=1;i<=$(this).attr('colspan');i++){
                                    if(j==2) { colCount.push('*'); } else { colCount.push('auto'); }
                                    j++;
                                }
                            }else{
                                if(j==2) { colCount.push('*'); } else { colCount.push('auto'); }
                                j++;
                            }
                        }
                    });
                    doc.content[1].table.widths = colCount;
                }
            },
            {
                extend:'pdf',
                text:'Landscape PDF',
                orientation:'landscape',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function ( inner, coldex, rowdex ) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if(autoSl && rowdex==0) {
                                return coldex+1;
                            } else if(el) {
                                var result = '';
                                $.each( el, function (index, item) {
                                    if(item) {
                                        if (item.nodeName == '#text') {
                                            result = result+item.textContent;
                                        } else {
                                            result = result + item.outerHTML;
                                        }
                                    }
                                });
                                return result;
                            } else {
                                return inner;
                            }
                        }
                    }
                },
                customize : function(doc){
                    $.each(doc.content[1].table.body, function (index, item) {
                        for(var i=item.length-1; i>=0; i--){
                            if(item[i]) {
                                var item2 = item[i];
                                var $aa = $.parseHTML(item2.text);
                                if($aa) {
                                    if (($aa[0].attributes) && $aa[0].attributes[0].nodeName == 'hide') {
                                        item.splice(i, 1);
                                    } else {
                                        if($aa[0].attributes) {
                                            if ($aa[0].attributes[0].nodeName == 'colspan') {
                                                item2.colSpan = $aa[0].attributes[0].textContent;
                                            }
                                        }
                                        if ($aa[0].nodeName == 'STRONG') {
                                            item2.bold = true;
                                        }
                                        if($aa[0].nodeName != '#text') {
                                            item2.text = $aa[0].textContent;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    var colCount = new Array(), j=1;
                    $("#tabletools").find('tbody tr:first-child td').each(function(){
                        if($(this).is(':visible')){
                            if($(this).attr('colspan')){
                                for(var i=1;i<=$(this).attr('colspan');i++){
                                    if(j==2) { colCount.push('*'); } else { colCount.push('auto'); }
                                    j++;
                                }
                            }else{
                                if(j==2) { colCount.push('*'); } else { colCount.push('auto'); }
                                j++;
                            }
                        }
                    });
                    doc.content[1].table.widths = colCount;
                }
            },
            {
                extend: 'print',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function ( inner, coldex, rowdex ) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if(autoSl && rowdex==0) {
                                return coldex+1;
                            } else if(el) {
                                var result='';
                                $.each( el, function (index, item) {
                                    if(item) {
                                        if (item.nodeName == '#text') {
                                            result = result+item.textContent;
                                        } else {
                                            result = result + item.outerHTML;
                                        }
                                    }
                                });
                                return result;
                            } else {
                                return inner;
                            }
                        }
                    }
                },
                customize: function (win) {
                    $(win.document.body).find('[colspan]').each(function(index){
                        $(this).parents('td').first().attr('colspan', $(this).attr('colspan'));
                    });
                    $(win.document.body).find('[hide]').parent('td').remove();
                }
            }
        ],
        ordering: ordering,
        searching: searching,
        order: [[ 1, 'asc' ]]
    };

    if(autoSl && (!ordering && !searching)) {
        dataTable["fnRowCallback"] = function(nRow, aData, iDisplayIndex){
            if(typeof ($("td:first", nRow).attr("no-sl"))===typeof undefined) {
                $("td:first", nRow).html(sl++);
            }
            return nRow;
        };
    }
    if(type=="json") {
        dataTable["columns"] = columns;
        dataTable["data"] = tableData;
    }

    var t = $selector.find("#tabletools").DataTable(dataTable);

    if(autoSl && (ordering || searching)) {
        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    }
}

function customPreloader(that) {
    that.waitMe({
        effect: "rotateplane",
        text: "",
        bg: "rgba(255,255,255,0.7)",
        color: "#616469"
    });
}

function customPreloaderHide(that) {
    that.waitMe("hide");
}