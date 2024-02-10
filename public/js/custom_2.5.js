//------------- custom.js -------------//

function getDuration(startDate, endDate) {
    var duration = '',
        durationValue = 0;
    var startDate = dateTimeObj(startDate);
    var endDate = dateTimeObj(endDate);

    if (startDate && endDate) {
        var diff = endDate.getTime() - startDate.getTime();

        if (diff >= 60000) {
            var day = 0,
                hour = 0,
                minute = 0;
            var ttlMinute = diff / 60000;
            durationValue = ttlMinute;
            var minute = ttlMinute % 60;
            ttlMinute -= minute;
            if (ttlMinute >= 60) {
                var ttlHour = ttlMinute / 60;
                var hour = ttlHour % 24;
                ttlHour -= hour;
                if (ttlHour >= 24) {
                    var day = ttlHour / 24;
                }
            }

            if (day == 7) {
                duration += '1 week ';
            } else if (day > 0) {
                duration += (day == 1) ? '1 day ' : day + ' days ';
            }
            if (hour > 0) {
                duration += (hour == 1) ? '1 hour ' : hour + ' hours ';
            }
            if (minute > 0) {
                duration += (minute == 1) ? '1 minute' : minute + ' minutes';
            }
        }
    }
    return { duration: duration, durationValue: durationValue };
}

function dateTimeObj(fullDate) {
    var output = false;
    var fullDateTime = fullDate.split(" ");
    if (fullDateTime.length == 3) {
        var fullDate = fullDateTime[0].split("/");
        var fullTime = fullDateTime[1].split(":");

        if (fullDate.length == 3 && fullTime.length == 2) {
            var day = parseInt(fullDate[0]);
            var month = parseInt(fullDate[1]) - 1;
            var year = parseInt(fullDate[2]);
            var hour = parseInt(fullTime[0]);
            var minute = parseInt(fullTime[1]);

            if (fullDateTime[2] == 'AM' && hour == 12) { hour = 0; } else if (fullDateTime[2] == 'PM' && hour != 12) { hour += 12; }

            var output = new Date(year, month, day, hour, minute);
        }
    }
    return output;
}

function dateFormat(dateObj) {
    var amPm = (dateObj.getHours() < 12) ? 'AM' : 'PM';
    var hour = (dateObj.getHours() > 12) ? dateObj.getHours() - 12 : dateObj.getHours();
    hour = (hour == 0) ? 12 : hour;
    var month = dateObj.getMonth() + 1;
    month = (month < 10) ? '0' + month : month;
    var minute = (dateObj.getMinutes() < 10) ? '0' + dateObj.getMinutes() : dateObj.getMinutes();
    return dateObj.getDate() + '/' + month + '/' + dateObj.getFullYear() + ' ' + hour + ':' + minute + ' ' + amPm;
}

function dashButtonCleaner() {
    $(".dashButton").remove();
}

function loadTimeDuration(selector, loadUrl, cur_date) {
    var curType = "month";
    if ($(".btn-day").hasClass("fc-state-active")) { curType = "day"; } else if ($(".btn-week").hasClass("fc-state-active")) { curType = "week"; }
    selector.on("click", ".btn-day", function(e) {
        e.preventDefault();
        if (!$(this).hasClass("fc-state-active")) {
            var inputArray = { type: "day", cur_date: cur_date };
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-week", function(e) {
        e.preventDefault();
        if (!$(this).hasClass("fc-state-active")) {
            var inputArray = { type: "week", cur_date: cur_date };
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-month", function(e) {
        e.preventDefault();
        if (!$(this).hasClass("fc-state-active")) {
            var inputArray = { type: "month", cur_date: cur_date };
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
    selector.on("click", ".btn-previous", function(e) {
        e.preventDefault();
        var inputArray = { type: curType, cur_date: cur_date, direction: "previous" };
        loadDurationData(selector, loadUrl, inputArray);
    });
    selector.on("click", ".btn-next", function(e) {
        e.preventDefault();
        var inputArray = { type: curType, cur_date: cur_date, direction: "next" };
        loadDurationData(selector, loadUrl, inputArray);
    });
    selector.on("click", ".btn-today", function(e) {
        e.preventDefault();
        if (!$(this).hasClass("fc-state-disabled")) {
            var inputArray = { type: curType };
            loadDurationData(selector, loadUrl, inputArray);
        }
    });
}

function loadDurationData(selector, loadUrl, inputArray) {
    if (typeof inputArray === typeof undefined || inputArray === "") { inputArray = {}; }
    preLoader(selector);
    $.ajax({
        url: appUrl.getSiteAction('/' + loadUrl),
        data: inputArray,
        type: 'GET',
        dataType: "html",
        success: function(data) {
            if (parseInt(data) === 0) {
                location.replace(appUrl.loginPage());
            } else {
                preLoaderHide(selector);
                selector.parents(".panel-body").html(data);
            }
        }
    });
}

function notificationAlert(data, i = 0) {
    setTimeout(function() {
        var dataObj = data[i];
        var notification = new Notification(dataObj.title, {
            body: dataObj.details,
            icon: (dataObj.type == "meeting") ? appUrl.baseUrl('/public/img/meeting_notify.png') : appUrl.baseUrl('/public/img/call_notify.png')
        });
        document.getElementById("notifySound").play();

        notification.onclick = function() {
            swal({
                    title: "Are you sure?",
                    text: "You want to permanently close this notification!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, close it!",
                    closeOnConfirm: true
                },
                function() {
                    $.ajax({
                        url: appUrl.baseUrl('/close-notification'),
                        data: { id: dataObj.id, type: dataObj.type },
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
        if (i < data.length) { notificationAlert(data, i); }
    }, 500);
}

function NotificationAssign(data) {
    var notifyIcon = "";
    switch (data.type) {
        case "meeting":
            notifyIcon = appUrl.baseUrl('/public/img/meeting_notify.png');
            break;
        case "task":
            notifyIcon = appUrl.baseUrl('/public/img/task_notify.png');
            break;
        case "call":
            notifyIcon = appUrl.baseUrl('/public/img/call_notify.png');
            break;
    }
    var notification = new Notification(data.title, {
        body: data.details,
        icon: notifyIcon
    });

    notification.onclick = function() {
        if (typeof data.activity_id !== typeof undefined) {
            var notifyUrl = 'activities/' + data.type + '/' + data.activity_id + '/' + data.activity_name;
            var notifyMenuActiveLink = 'activities/' + data.type;
            window.location.hash = notifyUrl;
            menuActive(notifyMenuActiveLink);
            LoadAjaxContent(notifyUrl, notifyMenuActiveLink);
        }
    };
    headerAssignNotification(data);
}

function headerAssignNotification(data) {
    //Notify Remove
    if (typeof data.notifyRemove !== typeof undefined) {
        var $liInput = $("#header .notification").find("input[name='notify_id[]'][value='" + data.notifyRemove + "']");
        if ($liInput.length > 0) {
            $liInput.parents("li").first().remove();

            if ((data.headerNotify != "") && ($("#header .notif").find('li').length <= 2)) {
                $("#header .notif").find('.header').after('<li class="blank-notify"><div>No notification found</div></li>');
            }

            //Notify Number
            var ttlNotify = parseInt($("#header .notification").html());
            ttlNotify = (ttlNotify > 0) ? ttlNotify - 1 : 0;
            ttlNotify = (ttlNotify == 0) ? '' : ttlNotify;
            $("#header .notification").html(ttlNotify);
        }
    }
    //Notify Add
    if (data.headerNotify != "") {
        if ($("#header .notif").find('.blank-notify').length > 0) {
            $("#header .notif").find('.blank-notify').remove();
        }
        var notifyIcon = "";
        switch (data.type) {
            case "meeting":
                notifyIcon = 'icomoon-icon-briefcase';
                break;
            case "task":
                notifyIcon = 'icomoon-icon-clipboard';
                break;
            case "call":
                notifyIcon = 'icomoon-icon-call-outgoing';
                break;
        }
        var notifyContent = '<li><span><input type="hidden" name="notify_id[]" value="' + data.notify_id + '">';
        if (notifyIcon != "") {
            notifyContent += '<span class=icon><i class="s16 ' + notifyIcon + '"></i></span>';
        }
        notifyContent += '<span class=event>' + data.headerNotify + '</span>';
        if (data.assignBtn) {
            notifyContent += '<span class="event-btn"><button class="btn btn-success btn-xs mr5 notifyAssignAccept" data="' + data.notify_id + '" type="button"><i class="icomoon-icon-checkmark-3"></i>Accept</button>';
            notifyContent += '<button class="btn btn-danger btn-xs notifyAssignDeny" data="' + data.notify_id + '" type="button"><i class="fa fa-times"></i>Deny</button></span>';
        }
        notifyContent += '</span></li>';
        $("#header .notif").find('.header').after(notifyContent);

        if ($("#header .notif>li").length > 5) {
            $("#header .notif>li").not(".view-all").last().remove();
        }

        //Notify Number
        var ttlNotify = parseInt($("#header .notification").html());
        ttlNotify = (ttlNotify > 0) ? ttlNotify + 1 : 1;
        $("#header .notification").html(ttlNotify);
    }
    document.getElementById("notifySound").play();
}

function notification(data, notifyType) {
    if (!Notification) {
        if (notifyType == "alertNotification") {
            swal("Sorry!!", "Desktop notifications not available in your browser.", "error");
        }
    } else {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        } else {
            if (notifyType == "alertNotification") {
                notificationAlert(data);
            } else if (notifyType == "assignNotification") {
                NotificationAssign(data);
                notifyType = "";
            }
        }
    }
    if (notifyType == "assignNotification") {
        headerAssignNotification(data);
    }
}

function activityAssignNotification(data) {
    if (data.notifyNow) {
        socket.emit("notify now", { notifyType: "assignNotification", userData: data.notifyUserData });
    }
}

function multipleFileUpload(selector, postFix = '') {
    var $attachment_area = $("#attachment_area_" + selector);
    var $parentSelector = $('#' + selector).parents().first();
    var $selector = $('#' + selector);
    var buttonText = $selector.html();
    var filePath = $selector.attr('file-path');
    var inputPrefix = $selector.attr('input-prefix');
    inputPrefix = (typeof inputPrefix !== typeof undefined) ? inputPrefix + "_" : "";
    var csrf_token = $selector.attr('_token');
    var callback = $selector.attr('callback');
    var fileAttachDirection = $selector.attr('file-attach-direction');
    var fileAttachDirection = ((typeof fileAttachDirection !== typeof undefined) && fileAttachDirection == 'last') ? 'last' : 'first';
    var reqWidth = '';
    if ($selector.attr('reqwidth')) { reqWidth = $selector.attr('reqwidth'); }
    var reqHeight = '';
    if ($selector.attr('reqheight')) { reqHeight = $selector.attr('reqheight'); }
    var strokeUrl = $selector.attr('stroke-url');
    var idParameter = $selector.attr('id-parameter');

    var extArray = '';
    var acceptExt = '';
    if ($selector.attr('ext')) {
        extArray = new Array();
        var extAr = $selector.attr('ext');
        extAr = extAr.split(',');
        var j = 0;
        for (var i = 0; i < extAr.length; i++) {
            var extBl = extAr[i].replace(/\s+/g, '');
            if (extBl) {
                acceptExt += '.' + extBl + ((extArray.length != (i + 1)) ? ',' : '');
                extArray[j] = extBl;
                j++;
            }
        }
    }
    //console.log(selector);
    $(function() {
        if (typeof callback !== typeof undefined) {
            var callback_param = {};
            $selector.each(function() {
                $.each(this.attributes, function(index) {
                    if (this.specified) {
                        var callback_param_identifier = this.name.split('-');
                        if (callback_param_identifier[0] == 'callback' && callback_param_identifier.length > 1) {
                            callback_param_identifier.shift();
                            callback_param[callback_param_identifier.join('-')] = this.value;
                        }
                    }
                });
            });
        }

        var status = $('#status_' + selector);
        $selector.after('<input type="file" multiple class="filestyle" id="' + selector + '">');
        $selector.remove();
        $('#' + selector).filestyle({
            input: !0,
            icon: !0,
            buttonBefore: !1,
            disabled: !1,
            size: "",
            buttonText: buttonText,
            buttonName: "btn-default",
            iconName: "fa fa-upload"
        });
        if (acceptExt != '') { $('#' + selector).attr('accept', acceptExt); }

        $('#' + selector).change(function() {
            var files = $(this)[0].files;
            /*            console.log(files[0].name);*/
            for (var i = 0; i < files.length; i++) //Anas (for multiple file upload)
            {
                var myfile = files[i].name;
                var extExist = 0;

                if (myfile == '') {
                    alert('Please enter file name and select file');
                    return;
                } else {
                    var filenameSlit = myfile.split(".");
                    var ext = filenameSlit[filenameSlit.length - 1];
                    ext = ext.toLowerCase();

                    var mainfile = filenameSlit[filenameSlit.length - 2];


                    if (extArray) { for (var i = 0; i < extArray.length; i++) { if (ext == extArray[i]) { extExist = 1; } } }
                    if (!ext || (extArray && extExist == 0)) {
                        var extNames = '';
                        for (var i = 0; i < extArray.length; i++) {
                            if (extArray.length > 1 && i == extArray.length - 1) { extNames += ' or '; } else if (i > 0) { extNames += ', '; }
                            extNames += extArray[i];
                        }
                        status.text('Only ' + extNames + ' files are allowed');
                        return;
                    } else if (!(ext && /^(jpg|png|jpeg|gif|doc|docx|pdf|ppt|pptx|csv|xls|xlsx|zip|rar|tar|mng|avchd|wmv|avi|mov|mp4|mpg)$/.test(ext))) {
                        // extension is not allowed
                        status.html('<div class="alert alert-warning"><i class="fa fa-warning mrl"></i>File type is not allowed.<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                        return;
                    } else if (files[i].size > 524288000) {
                        status.html('<div class="alert alert-warning"><i class="fa fa-warning mrl"></i>File size exceed the limit (500 MB)<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                        return;
                    } else {
                        status.html('');
                        $("label[for='" + selector + "']").parents().first().html($("label[for='" + selector + "']").parents().first().html());
                        $("label[for='" + selector + "']").addClass('disabled').attr('for', '');
                        status.before('<div class="progress"><div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div></div><div class="close_x hand" style="float:right; margin-top:-25px; margin-right:-15px;"><span class="fa fa-close remove_files"></span> </div>');

                        var formData = new FormData();
                        formData.append('uploadfile' + postFix, files[i]);
                        formData.append('filePath', filePath);
                        formData.append('_token', csrf_token);
                        formData.append('postFix', postFix);
                        formData.append('reqWidth', reqWidth);
                        formData.append('reqHeight', reqHeight);
                        formData.append('multiple_file', true);
                        formData.append('mainfile', mainfile);
                        formData.append('idParameter', idParameter);
                        formData.append('strokeUrl', strokeUrl);

                        $.ajax({
                            url: appUrl.baseUrl('/customFileUpload'),
                            data: formData,
                            multiple: true,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            // this part is progress bar
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        percentComplete = parseInt(percentComplete * 100);
                                        $parentSelector.find('.myprogress').text(percentComplete + '%');
                                        $parentSelector.find('.myprogress').css('width', percentComplete + '%');
                                        //cancel upload on progess.
                                        $('.remove_files').click(function(e) {
                                            xhr.abort();
                                            //On completion clear the status
                                            $parentSelector.find("label[for='']").removeClass('disabled').attr('for', selector);
                                            $parentSelector.find('.progress').remove();
                                            $parentSelector.find('.remove_files').remove();
                                            $parentSelector.find('.input-').val('');
                                        });
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(response) {
                                var file = filenameSlit[filenameSlit.length - 2];
                                file = file.split("\\");
                                file = file[file.length - 1];
                                response = response.split('~');
                                console.log(response);
                                console.log(file);
                                //Add uploaded file to list
                                if (response[1] === "success") {

                                    if ($attachment_area.find(".no-attachments").is(":visible")) {
                                        $attachment_area.find(".no-attachments").hide();
                                    }

                                    var file_name = response[2];
                                    var file_name_array = file_name.split(".");
                                    var file_ext = file_name_array[file_name_array.length - 1];

                                    if (file_ext == 'jpg' || file_ext == 'jpeg' || file_ext == 'png' || file_ext == 'gif') {
                                        var upload_thumb = appUrl.baseUrl('/' + filePath + '/thumb/' + file_name);
                                        var imgClass = 'class=igniterImg';
                                        var file_source = appUrl.baseUrl('/' + filePath + '/' + file_name);
                                    } else {
                                        var upload_thumb = getFileThumb(file_ext);
                                        var imgClass = '';
                                        var file_source = 'https://docs.google.com/viewerng/viewer?url=' + appUrl.baseUrl('/' + filePath + '/' + file_name);
                                    }
                                    
                                    var attachmentAreaHtml = '<div class="attachment-item clearfix image"><div style="background-image: url('+upload_thumb+')" class="attachment-img"></div><div class="attachment-content"><div class="close_x" ><span class="fa fa-close remove_files" file_name="'+file_name+'" filePath="'+filePath+'" ></span> </div><div class="attachment-title" ><a '+imgClass+' href="'+file_source+'" target="_blank">'+file+'</a></div> <div class="attachment-date"> Uploaded '+response[5]+' </div> <div class="attachment-size"> '+response[4]+' </div> <input type="hidden" name="'+inputPrefix+'attachment_id[]" value="0"/> <input type="hidden" name="'+inputPrefix+'attachment[]" value="'+file_name+'"/> <input type="hidden" name="'+inputPrefix+'attachment_real_name[]" value="'+file+'"/> <input type="hidden" name="'+inputPrefix+'attachment_size[]" value="'+response[3]+'"/> </div>';
                                    
                                    if(fileAttachDirection=='first') { $attachment_area.prepend(attachmentAreaHtml); }
                                    else { $attachment_area.append(attachmentAreaHtml); }

                                    //On completion clear the status
                                    $parentSelector.find("label[for='']").removeClass('disabled').attr('for', selector);
                                    $parentSelector.find('.progress').remove();
                                    $parentSelector.find('.close_x').remove();

                                    if (typeof callback !== typeof undefined) {
                                        callback_param.file_name = file_name;
                                        callback_param.file_path = filePath;
                                        callback_param.file_real_name = file;
                                        callback_param.file_size = response[3];
                                        // callback_param.attachmentAreaHtml = attachmentAreaHtml;
                                        callBackFunction(callback, [callback_param]);
                                    }
                                } else {
                                    status.html('<div class="alert alert-warning "><i class="fa fa-warning mrl"></i>' + response[1] + '<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                                }
                            }
                        });
                    }
                }

            }

        });
    });

    //Remove
    var removeCallback = $selector.attr('remove-callback');
    $attachment_area.on('click', '.remove_files', function(e) {
        e.preventDefault();
        var auto_remove = $selector.attr('auto-remove');
        var auto_remove_individual = $(this).attr('auto-remove');
        var file_name = $(this).attr('file_name');
        var filePath = $(this).attr('filePath');

        $(this).parents('.attachment-item').remove();
        var $attachment_item = $attachment_area.find(".attachment-item");
        if ($attachment_item.length == 0) {
            $('.no-attachments').show();
        }

        if (typeof removeCallback !== typeof undefined) {
            callBackFunction(removeCallback, [{ file_name: file_name, filePath: filePath }]);
        }

        if (typeof auto_remove_individual === typeof undefined || auto_remove_individual == 'true') { //Default = True
            if (typeof auto_remove === typeof undefined || auto_remove == 'true') { //Default = True
                $.ajax({
                    url: appUrl.baseUrl('/customFileUnlink'),
                    type: "POST",
                    data: { filePath: filePath, image: file_name, _token: csrf_token }
                });
            }
        }
    });
}

function galleryImageUpload(selector, postFix = '') {
    var $attachment_area = $("#attachment_area_" + selector);
    var $parentSelector = $('#' + selector).parents().first();
    var $selector = $('#' + selector);
    var buttonText = $selector.html();
    var filePath = $selector.attr('file-path');
    var inputPrefix = $selector.attr('input-prefix');
    inputPrefix = (typeof inputPrefix !== typeof undefined) ? inputPrefix + "_" : "";
    var csrf_token = $selector.attr('_token');
    var callback = $selector.attr('callback');
    var fileAttachDirection = $selector.attr('file-attach-direction');
    var fileAttachDirection = ((typeof fileAttachDirection !== typeof undefined) && fileAttachDirection == 'last') ? 'last' : 'first';
    var reqWidth = '';
    if ($selector.attr('reqwidth')) { reqWidth = $selector.attr('reqwidth'); }
    var reqHeight = '';
    if ($selector.attr('reqheight')) { reqHeight = $selector.attr('reqheight'); }
    var strokeUrl = $selector.attr('stroke-url');
    var typeOfImage = $selector.attr('id');
    var idParameter = $selector.attr('id-parameter');
    console.log('strokeUrl', strokeUrl);
    console.log('typeOfImage', typeOfImage);
    var extArray = '';
    var acceptExt = '';
    if ($selector.attr('ext')) {
        extArray = new Array();
        var extAr = $selector.attr('ext');
        extAr = extAr.split(',');
        var j = 0;
        for (var i = 0; i < extAr.length; i++) {
            var extBl = extAr[i].replace(/\s+/g, '');
            if (extBl) {
                acceptExt += '.' + extBl + ((extArray.length != (i + 1)) ? ',' : '');
                extArray[j] = extBl;
                j++;
            }
        }
    }
    //console.log(selector);
    $(function() {
        if (typeof callback !== typeof undefined) {
            var callback_param = {};
            $selector.each(function() {
                $.each(this.attributes, function(index) {
                    if (this.specified) {
                        var callback_param_identifier = this.name.split('-');
                        if (callback_param_identifier[0] == 'callback' && callback_param_identifier.length > 1) {
                            callback_param_identifier.shift();
                            callback_param[callback_param_identifier.join('-')] = this.value;
                        }
                    }
                });
            });
        }

        var status = $('#status_' + selector);
        $selector.after('<input type="file" multiple class="filestyle" id="' + selector + '">');
        $selector.remove();
        $('#' + selector).filestyle({
            input: !0,
            icon: !0,
            buttonBefore: !1,
            disabled: !1,
            size: "",
            buttonText: buttonText,
            buttonName: "btn-default",
            iconName: "fa fa-upload"
        });
        if (acceptExt != '') { $('#' + selector).attr('accept', acceptExt); }

        $('#' + selector).change(function() {
            var files = $(this)[0].files;
            /*            console.log(files[0].name);*/
            for (var i = 0; i < files.length; i++) //Anas (for multiple file upload)
            {
                var myfile = files[i].name;
                var extExist = 0;

                if (myfile == '') {
                    alert('Please enter file name and select file');
                    return;
                } else {
                    var filenameSlit = myfile.split(".");
                    var ext = filenameSlit[filenameSlit.length - 1];
                    ext = ext.toLowerCase();

                    var mainfile = filenameSlit[filenameSlit.length - 2];


                    if (extArray) { for (var i = 0; i < extArray.length; i++) { if (ext == extArray[i]) { extExist = 1; } } }
                    if (!ext || (extArray && extExist == 0)) {
                        var extNames = '';
                        for (var i = 0; i < extArray.length; i++) {
                            if (extArray.length > 1 && i == extArray.length - 1) { extNames += ' or '; } else if (i > 0) { extNames += ', '; }
                            extNames += extArray[i];
                        }
                        status.text('Only ' + extNames + ' files are allowed');
                        return;
                    } else if (!(ext && /^(jpg|png|jpeg|gif|doc|docx|pdf|ppt|pptx|csv|xls|xlsx|zip|rar|tar|mng|avchd|wmv|avi|mov|mp4|mpg)$/.test(ext))) {
                        // extension is not allowed
                        status.html('<div class="alert alert-warning"><i class="fa fa-warning mrl"></i>File type is not allowed.<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                        return;
                    } else if (files[i].size > 524288000) {
                        status.html('<div class="alert alert-warning"><i class="fa fa-warning mrl"></i>File size exceed the limit (500 MB)<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                        return;
                    } else {
                        status.html('');
                        $("label[for='" + selector + "']").parents().first().html($("label[for='" + selector + "']").parents().first().html());
                        $("label[for='" + selector + "']").addClass('disabled').attr('for', '');
                        status.before('<div class="progress"><div class="progress-bar progress-bar-success myprogress" role="progressbar" style="width:0%">0%</div></div><div class="close_x hand" style="float:right; margin-top:-25px; margin-right:-15px;"><span class="fa fa-close remove_files"></span> </div>');

                        var formData = new FormData();
                        formData.append('uploadfile' + postFix, files[i]);
                        formData.append('filePath', filePath);
                        formData.append('_token', csrf_token);
                        formData.append('postFix', postFix);
                        formData.append('reqWidth', reqWidth);
                        formData.append('reqHeight', reqHeight);
                        formData.append('multiple_file', true);
                        formData.append('mainfile', mainfile);
                        formData.append('idParameter', idParameter);
                        formData.append('strokeUrl', strokeUrl);
   
                        $.ajax({
                            url:appUrl.baseUrl('/'+strokeUrl+'/'+idParameter),
                            data: formData,
                            multiple: true,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            // this part is progress bar
                            xhr: function() {
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function(evt) {
                                    if (evt.lengthComputable) {
                                        var percentComplete = evt.loaded / evt.total;
                                        percentComplete = parseInt(percentComplete * 100);
                                        $parentSelector.find('.myprogress').text(percentComplete + '%');
                                        $parentSelector.find('.myprogress').css('width', percentComplete + '%');
                                        //cancel upload on progess.
                                        $('.remove_files').click(function(e) {
                                            xhr.abort();
                                            //On completion clear the status
                                            $parentSelector.find("label[for='']").removeClass('disabled').attr('for', selector);
                                            $parentSelector.find('.progress').remove();
                                            $parentSelector.find('.remove_files').remove();
                                            $parentSelector.find('.input-').val('');
                                        });
                                    }
                                }, false);
                                return xhr;
                            },
                            success: function(response) {
                                var file = filenameSlit[filenameSlit.length - 2];
                                file = file.split("\\");
                                file = file[file.length - 1];
                                response = response.split('~');
                                //Add uploaded file to list
                                if (response[1] === "success") {
                                    if ($attachment_area.find(".no-attachments").is(":visible")) {
                                        $attachment_area.find(".no-attachments").hide();
                                    }

                                    var file_name = response[2];
                                    var file_name_array = file_name.split(".");
                                    var file_ext = file_name_array[file_name_array.length - 1];

                                    if (file_ext == 'jpg' || file_ext == 'jpeg' || file_ext == 'png' || file_ext == 'gif') {
                                        var upload_thumb = appUrl.baseUrl('/' + filePath + '/thumb/' + file_name);
                                        var imgClass = 'class=igniterImg';
                                        var file_source = appUrl.baseUrl('/' + filePath + '/' + file_name);
                                    } else {
                                        var upload_thumb = getFileThumb(file_ext);
                                        var imgClass = '';
                                        var file_source = 'https://docs.google.com/viewerng/viewer?url=' + appUrl.baseUrl('/' + filePath + '/' + file_name);
                                    }
                                    
                                    // var attachmentAreaHtml = '<div class="attachment-item clearfix image"><div style="background-image: url('+upload_thumb+')" class="attachment-img"></div><div class="attachment-content"><div class="close_x" ><span class="fa fa-close remove_files" file_name="'+file_name+'" filePath="'+filePath+'" ></span> </div><div class="attachment-title" ><a '+imgClass+' href="'+file_source+'" target="_blank">'+file+'</a></div> <div class="attachment-date"> Uploaded '+response[5]+' </div> <div class="attachment-size"> '+response[4]+' </div> <input type="hidden" name="'+inputPrefix+'attachment_id[]" value="0"/> <input type="hidden" name="'+inputPrefix+'attachment[]" value="'+file_name+'"/> <input type="hidden" name="'+inputPrefix+'attachment_real_name[]" value="'+file+'"/> <input type="hidden" name="'+inputPrefix+'attachment_size[]" value="'+response[3]+'"/> </div>';
                                    
                                    // if(fileAttachDirection=='first') { $attachment_area.prepend(attachmentAreaHtml); }
                                    // else { $attachment_area.append(attachmentAreaHtml); }

                                    //On completion clear the status
                                    $parentSelector.find("label[for='']").removeClass('disabled').attr('for', selector);
                                    $parentSelector.find('.progress').remove();
                                    $parentSelector.find('.close_x').remove();

                                    if (typeof callback !== typeof undefined) {
                                        callback_param.file_name = file_name;
                                        callback_param.file_path = filePath;
                                        callback_param.file_real_name = file;
                                        callback_param.file_size = response[3];
                                        // callback_param.attachmentAreaHtml = attachmentAreaHtml;
                                        callBackFunction(callback, [callback_param]);
                                    }
                                } else {
                                    status.html('<div class="alert alert-warning "><i class="fa fa-warning mrl"></i>' + response[1] + '<span class="close fa fa-times fs16" data-dismiss="alert" href="#"></span></div>');
                                }
                            }
                        });
                    }
                }

            }

        });
    });

    //Remove
    var removeCallback = $selector.attr('remove-callback');
    $attachment_area.on('click', '.remove_files', function(e) {
        e.preventDefault();
        var auto_remove = $selector.attr('auto-remove');
        var auto_remove_individual = $(this).attr('auto-remove');
        var file_name = $(this).attr('file_name');
        var filePath = $(this).attr('filePath');

        $(this).parents('.attachment-item').remove();
        var $attachment_item = $attachment_area.find(".attachment-item");
        if ($attachment_item.length == 0) {
            $('.no-attachments').show();
        }

        if (typeof removeCallback !== typeof undefined) {
            callBackFunction(removeCallback, [{ file_name: file_name, filePath: filePath }]);
        }

        if (typeof auto_remove_individual === typeof undefined || auto_remove_individual == 'true') { //Default = True
            if (typeof auto_remove === typeof undefined || auto_remove == 'true') { //Default = True
                $.ajax({
                    url: appUrl.baseUrl('/customFileUnlink'),
                    type: "POST",
                    data: { filePath: filePath, image: file_name, _token: csrf_token }
                });
            }
        }
    });
}

function activitiesFileUnlink(selector, removeLink) {
    var $attachment_area = $("#attachment_area_" + selector);
    $attachment_area.on('click', '.remove_files', function(e) {
        var file_id = $(this).attr('file_id');
        var file_name = $(this).attr('file_name');
        var file_name_array = file_name.split('.');
        $.ajax({
            url: appUrl.baseUrl('/' + removeLink + '/' + file_id),
            type: "GET",
            dataType: "json",
        });
        $attachment_area.find("#" + file_name_array[0]).remove();
        var $attachment_item = $attachment_area.find(".attachment-item");
        if ($attachment_item.length == 0) {
            $('.no-attachments').show();
        }
    });
}

function getFileThumb(file_ext) {
    var thumb = appUrl.baseUrl('/public/file_icon/zip.png'); //default
    if (file_ext == 'doc' || file_ext == 'docx') {
        thumb = appUrl.baseUrl('/public/file_icon/doc.png');
    } else if (file_ext == 'ppt' || file_ext == 'pptx') {
        thumb = appUrl.baseUrl('/public/file_icon/ppt.png');
    } else if (file_ext == 'xls' || file_ext == 'xlsx') {
        thumb = appUrl.baseUrl('/public/file_icon/xls.png');
    } else if (file_ext == 'zip' || file_ext == 'rar' || file_ext == 'tar') {
        thumb = appUrl.baseUrl('/public/file_icon/zip.png');
    } else if (file_ext == 'pdf') {
        thumb = appUrl.baseUrl('/public/file_icon/pdf.png');
    } else if (file_ext == 'csv') {
        thumb = appUrl.baseUrl('/public/file_icon/csv.png');
    } else if (file_ext == 'mp4') {
        thumb = appUrl.baseUrl('/public/file_icon/mp4.jpg');
    } else if (file_ext == 'mpg') {
        thumb = appUrl.baseUrl('/public/file_icon/mpg.jpg');
    }

    return thumb;
}

//For Tab Load of Details Panel
function detailsPanelTabLoad($selector, tabLoad, tabNo, loadLink) {
    $selector.parents('.panel').first().find('.panel-title').html($('#detailsPanel').find('label[for="tab' + tabNo + '"]').html());

    if (tabLoad[tabNo]) {
        tabLoad[tabNo] = false;
        $.ajax({
            url: appUrl.getSiteAction(loadLink),
            type: 'GET',
            dataType: "html",
            success: function(data) {
                if (parseInt(data) === 0) {
                    location.replace(appUrl.loginPage(loadLink));
                } else {
                    $('#content' + tabNo).html(data);
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
    $.each(columnList, function(index, value) {
        columns[i++] = { data: index };
        html_th += '<th>' + value + '</th>';
    });
    return { columns: columns, html_th: html_th };
}

function customDataTable(allData) {
    var $selector = allData.selector,
        columns = allData.columns;
    var tableData = (typeof allData.data !== typeof undefined) ? allData.data : false;
    var tableTitle = (typeof allData.title !== typeof undefined) ? allData.title : "";
    var type = (typeof allData.type !== typeof undefined) ? allData.type : "json";
    var autoSl = (typeof allData.autoSl !== typeof undefined) ? allData.autoSl : true;
    var ordering = (typeof allData.ordering !== typeof undefined) ? allData.ordering : true;
    var searching = (typeof allData.searching !== typeof undefined) ? allData.searching : true;

    //Show Details
    $selector.on("click", ".show-details", function(e) {
        var detailsUrl = $(this).attr("url");
        var title = $(this).html();
        var data = false;
        var modalSize = "";
        showDetails(title, detailsUrl, data, modalSize);

        e.preventDefault();
    });

    //Data Table
    if (type == "json") {
        var tableResource = customDataTableResource(columns);
        var columns = tableResource.columns,
            html_th = tableResource.html_th;
        $selector.html('<table id="tabletools" class="table table-striped table-bordered" cellspacing=0 width=100%><thead><tr>' + html_th + '</tr></thead><tfoot><tr>' + html_th + '</tr></tfoot></table>');
    }

    var sl = 1;
    var dataTable = {
        columns: columns,
        oLanguage: {
            sSearch: "",
            sLengthMenu: "<span>_MENU_</span>"
        },
        sDom: "<'row'<'col-md-1 col-xs-12 'l><'col-md-3 col-xs-12'f><'col-md-8 col-xs-12 text-right'B>>t<'row'<'col-md-4 col-xs-12'i><'col-md-8 col-xs-12'p>>",
        buttons: [{
                extend: 'copy',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: (autoSl) ? {
                    format: {
                        body: function(inner, coldex, rowdex) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if (autoSl && rowdex == 0) {
                                return coldex + 1;
                            } else if (el) {
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item) {
                                        result = result + item.textContent;
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
                text: 'Portrait PDF',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function(inner, coldex, rowdex) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if (autoSl && rowdex == 0) {
                                return coldex + 1;
                            } else if (el) {
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item) {
                                        if (item.nodeName == '#text') {
                                            result = result + item.textContent;
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
                customize: function(doc) {
                    $.each(doc.content[1].table.body, function(index, item) {
                        for (var i = item.length - 1; i >= 0; i--) {
                            if (item[i]) {
                                var item2 = item[i];
                                var $aa = $.parseHTML(item2.text);
                                if ($aa) {
                                    if (($aa[0].attributes) && $aa[0].attributes[0].nodeName == 'hide') {
                                        item.splice(i, 1);
                                    } else {
                                        if ($aa[0].attributes) {
                                            if ($aa[0].attributes[0].nodeName == 'colspan') {
                                                item2.colSpan = $aa[0].attributes[0].textContent;
                                            }
                                        }
                                        if ($aa[0].nodeName == 'STRONG') {
                                            item2.bold = true;
                                        }
                                        if ($aa[0].nodeName != '#text') {
                                            item2.text = $aa[0].textContent;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    var colCount = new Array(),
                        j = 1;
                    $("#tabletools").find('tbody tr:first-child td').each(function() {
                        if ($(this).is(':visible')) {
                            if ($(this).attr('colspan')) {
                                for (var i = 1; i <= $(this).attr('colspan'); i++) {
                                    if (j == 2) { colCount.push('*'); } else { colCount.push('auto'); }
                                    j++;
                                }
                            } else {
                                if (j == 2) { colCount.push('*'); } else { colCount.push('auto'); }
                                j++;
                            }
                        }
                    });
                    doc.content[1].table.widths = colCount;
                }
            },
            {
                extend: 'pdf',
                text: 'Landscape PDF',
                orientation: 'landscape',
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function(inner, coldex, rowdex) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if (autoSl && rowdex == 0) {
                                return coldex + 1;
                            } else if (el) {
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item) {
                                        if (item.nodeName == '#text') {
                                            result = result + item.textContent;
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
                customize: function(doc) {
                    $.each(doc.content[1].table.body, function(index, item) {
                        for (var i = item.length - 1; i >= 0; i--) {
                            if (item[i]) {
                                var item2 = item[i];
                                var $aa = $.parseHTML(item2.text);
                                if ($aa) {
                                    if (($aa[0].attributes) && $aa[0].attributes[0].nodeName == 'hide') {
                                        item.splice(i, 1);
                                    } else {
                                        if ($aa[0].attributes) {
                                            if ($aa[0].attributes[0].nodeName == 'colspan') {
                                                item2.colSpan = $aa[0].attributes[0].textContent;
                                            }
                                        }
                                        if ($aa[0].nodeName == 'STRONG') {
                                            item2.bold = true;
                                        }
                                        if ($aa[0].nodeName != '#text') {
                                            item2.text = $aa[0].textContent;
                                        }
                                    }
                                }
                            }
                        }
                    });
                    var colCount = new Array(),
                        j = 1;
                    $("#tabletools").find('tbody tr:first-child td').each(function() {
                        if ($(this).is(':visible')) {
                            if ($(this).attr('colspan')) {
                                for (var i = 1; i <= $(this).attr('colspan'); i++) {
                                    if (j == 2) { colCount.push('*'); } else { colCount.push('auto'); }
                                    j++;
                                }
                            } else {
                                if (j == 2) { colCount.push('*'); } else { colCount.push('auto'); }
                                j++;
                            }
                        }
                    });
                    doc.content[1].table.widths = colCount;
                }
            },
            {
                extend: 'print',
                //autoPrint: false,
                className: 'btn btn-default',
                title: tableTitle,
                exportOptions: {
                    format: {
                        body: function(inner, coldex, rowdex) {
                            if (inner.length == 0) return inner;
                            var el = $.parseHTML(inner);
                            if (autoSl && rowdex == 0) {
                                return coldex + 1;
                            } else if (el) {
                                var result = '';
                                $.each(el, function(index, item) {
                                    if (item) {
                                        if (item.nodeName == '#text') {
                                            result = result + item.textContent;
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
                customize: function(win) {
                    $(win.document.body).find('[colspan]').each(function(index) {
                        $(this).parents('td').first().attr('colspan', $(this).attr('colspan'));
                    });
                    $(win.document.body).find('[hide]').parent('td').remove();
                }
            }
        ],
        ordering: ordering,
        searching: searching,
        order: [
            [1, 'asc']
        ]
    };

    if (autoSl && (!ordering && !searching)) {
        dataTable["fnRowCallback"] = function(nRow, aData, iDisplayIndex) {
            if (typeof($("td:first", nRow).attr("no-sl")) === typeof undefined) {
                $("td:first", nRow).html(sl++);
            }
            return nRow;
        };
    }
    if (type == "json") {
        dataTable["data"] = tableData;
    }

    var t = $selector.find("#tabletools").DataTable(dataTable);

    if (autoSl && (ordering || searching)) {
        t.on('order.dt search.dt', function() {
            t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
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

function showDetailsPopup($selector) {
    $selector.on("click", ".show-details-popup", function(e) {
        var detailsUrl = $(this).attr("url");
        if (typeof detailsUrl === typeof undefined) {
            var detailsUrl = $(this).attr("href");
        }
        var title = $(this).html();
        var data = false;
        var modalSize = "";
        showDetails(title, detailsUrl, data, modalSize);
        e.preventDefault();
    });
}

function assignNotifyPermit(id, permit, $notif_btn, $lst_btn, $dtl_btn) {
    if ($notif_btn.length > 0) {
        var $notif_parent = $notif_btn.parents('li').first();
        customPreloader($notif_parent);
    }
    if ($lst_btn.length > 0) {
        var $lst_parent = $lst_btn.parents('tr').first();
        customPreloader($lst_parent);
    }
    if ($dtl_btn.length > 0) {
        var $dtl_parent = $dtl_btn.parents('.panel').first();
        customPreloader($dtl_parent);
    }

    $.ajax({
        url: appUrl.getSiteAction('/assignNotifyPermit'),
        data: { id: id, permit: permit },
        type: 'GET',
        dataType: "json",
        success: function(data) {
            if (parseInt(data.auth) === 0) {
                location.replace(appUrl.loginPage(loadLink));
            } else {
                if (data.msgType == 'success') {
                    swal("Done!", data.messege, "success")
                } else {
                    swal("Opps!!", data.messege, "error");
                }
                if (data.selfNotifyRemove) {
                    if ($notif_btn.length > 0) {
                        var $parentLi = $notif_btn.parents("li").first();
                        $parentLi.remove();
                        if ($("#header .notif").find('li').length <= 2) {
                            $("#header .notif").find('.header').after('<li class="blank-notify"><div>No notification found</div></li>');
                        }
                        //Notify Number
                        var ttlNotify = parseInt($("#header .notification").html());
                        ttlNotify = (ttlNotify > 0) ? ttlNotify - 1 : 0;
                        ttlNotify = (ttlNotify == 0) ? '' : ttlNotify;
                        $("#header .notification").html(ttlNotify);
                    }
                    if ($lst_btn.length > 0) {
                        var $parentPanel = $lst_parent.parents(".panel").first();
                        $parentPanel.find(".panel-refresh").trigger("click");
                    }

                    if ($dtl_btn.length > 0) {
                        var $parentLi = $dtl_btn.parents("li").first();
                        $parentLi.remove();
                        customPreloaderHide($dtl_parent);
                    }
                } else if (data.notifyContent != '') {
                    if ($notif_btn.length > 0) {
                        var $parentLi = $notif_btn.parents("li").first();
                        $parentLi.find(".event-btn").remove();
                        var $parentLiEvent = $parentLi.find(".event").first();
                        $parentLiEvent.html(data.notifyContent);
                        customPreloaderHide($notif_parent);
                    }

                    if ($lst_btn.length > 0) {
                        var $parentTD = $lst_btn.parents("td").first();
                        $parentTD.html('N/A');
                        $lst_parent.find(".ntf-details").html(data.notifyContent);
                        customPreloaderHide($lst_parent);
                    }

                    if ($dtl_btn.length > 0) {
                        var $parentLi = $dtl_btn.parents("li").first();
                        $parentLi.remove();
                        customPreloaderHide($dtl_parent);
                    }

                    //Notify Number
                    var ttlNotify = parseInt($("#header .notification").html());
                    ttlNotify = (ttlNotify > 0) ? ttlNotify - 1 : 0;
                    ttlNotify = (ttlNotify == 0) ? '' : ttlNotify;
                    $("#header .notification").html(ttlNotify);
                } else {
                    if ($notif_btn.length > 0) { customPreloaderHide($notif_parent); }
                    if ($lst_btn.length > 0) { customPreloaderHide($lst_parent); }
                    if ($dtl_btn.length > 0) { customPreloaderHide($dtl_parent); }
                }
                if (data.notifyNow) {
                    activityAssignNotification(data);
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal("Cancelled", errorThrown, "error");
        }
    });
}

$(document).on('click', '.notify-bell', function(e) {
    var expandStatus = $(this).attr('aria-expanded');
    var ttlNotify = parseInt($("#header .notification").html());
    if (expandStatus == "true" && ttlNotify > 0) {
        $.ajax({
            url: appUrl.getSiteAction('/assignNotifyRead'),
            data: $("#notify-bell-form").serializeArray(),
            type: 'GET',
            dataType: "json",
            success: function(data) {
                if (parseInt(data.auth) !== 0) {
                    ttlNotify = (data.ttlNotification == 0) ? '' : data.ttlNotification;
                    $("#header .notification").html(ttlNotify);
                }
            }
        });
    }
    e.preventDefault();
});

$(document).on('click', '.notifyAssignAccept', function(e) {
    var id = $(this).attr('data');
    var $notif_btn = $(".notif .notifyAssignAccept[data='" + id + "']");
    var $lst_btn = $(".notif-tbl-lst .notifyAssignAccept[data='" + id + "']");
    var $dtl_btn = $(".notif-tbl-dtl .notifyAssignAccept[data='" + id + "']");

    assignNotifyPermit(id, 1, $notif_btn, $lst_btn, $dtl_btn);
    e.preventDefault();
});

$(document).on('click', '.notifyAssignDeny', function(e) {
    var id = $(this).attr('data');
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this data!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, deny it!",
        closeOnConfirm: !0
    }, function() {
        var $notif_btn = $(".notif .notifyAssignDeny[data='" + id + "']");
        var $lst_btn = $(".notif-tbl-lst .notifyAssignDeny[data='" + id + "']");
        var $dtl_btn = $(".notif-tbl-dtl .notifyAssignDeny[data='" + id + "']");
        assignNotifyPermit(id, 2, $notif_btn, $lst_btn, $dtl_btn);
    });
    e.preventDefault();
});

//Modal documents viewer
$(document).on('click', '.documentModal', function() {
    var document_src = $(this).attr('data-src');
    bootbox.dialog({
        message: '<iframe id="pdf_iframe" src="https://docs.google.com/gview?url=' + document_src + '&embedded=true" width="100%" height="' + ($(window).height() - 70) + '" style="border:0;"></iframe>',
        size: "large",
    }).on('hidden.bs.modal', function() { modalScroll() });
});