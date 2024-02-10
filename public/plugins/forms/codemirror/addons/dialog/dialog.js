(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror"],mod);else mod(CodeMirror)})(function(CodeMirror){function dialogDiv(cm,template,bottom){var wrap=cm.getWrapperElement();var dialog;dialog=wrap.appendChild(document.createElement("div"));if(bottom)
dialog.className="CodeMirror-dialog CodeMirror-dialog-bottom";else dialog.className="CodeMirror-dialog CodeMirror-dialog-top";if(typeof template=="string"){dialog.innerHTML=template}else{dialog.appendChild(template)}
return dialog}
function closeNotification(cm,newVal){if(cm.state.currentNotificationClose)
cm.state.currentNotificationClose();cm.state.currentNotificationClose=newVal}
CodeMirror.defineExtension("openDialog",function(template,callback,options){if(!options)options={};closeNotification(this,null);var dialog=dialogDiv(this,template,options.bottom);var closed=!1,me=this;function close(newVal){if(typeof newVal=='string'){inp.value=newVal}else{if(closed)return;closed=!0;dialog.parentNode.removeChild(dialog);me.focus();if(options.onClose)options.onClose(dialog)}}
var inp=dialog.getElementsByTagName("input")[0],button;if(inp){if(options.value){inp.value=options.value;inp.select()}
if(options.onInput)
CodeMirror.on(inp,"input",function(e){options.onInput(e,inp.value,close)});if(options.onKeyUp)
CodeMirror.on(inp,"keyup",function(e){options.onKeyUp(e,inp.value,close)});CodeMirror.on(inp,"keydown",function(e){if(options&&options.onKeyDown&&options.onKeyDown(e,inp.value,close)){return}
if(e.keyCode==27||(options.closeOnEnter!==!1&&e.keyCode==13)){inp.blur();CodeMirror.e_stop(e);close()}
if(e.keyCode==13)callback(inp.value,e)});if(options.closeOnBlur!==!1)CodeMirror.on(inp,"blur",close);inp.focus()}else if(button=dialog.getElementsByTagName("button")[0]){CodeMirror.on(button,"click",function(){close();me.focus()});if(options.closeOnBlur!==!1)CodeMirror.on(button,"blur",close);button.focus()}
return close});CodeMirror.defineExtension("openConfirm",function(template,callbacks,options){closeNotification(this,null);var dialog=dialogDiv(this,template,options&&options.bottom);var buttons=dialog.getElementsByTagName("button");var closed=!1,me=this,blurring=1;function close(){if(closed)return;closed=!0;dialog.parentNode.removeChild(dialog);me.focus()}
buttons[0].focus();for(var i=0;i<buttons.length;++i){var b=buttons[i];(function(callback){CodeMirror.on(b,"click",function(e){CodeMirror.e_preventDefault(e);close();if(callback)callback(me)})})(callbacks[i]);CodeMirror.on(b,"blur",function(){--blurring;setTimeout(function(){if(blurring<=0)close()},200)});CodeMirror.on(b,"focus",function(){++blurring})}});CodeMirror.defineExtension("openNotification",function(template,options){closeNotification(this,close);var dialog=dialogDiv(this,template,options&&options.bottom);var closed=!1,doneTimer;var duration=options&&typeof options.duration!=="undefined"?options.duration:5000;function close(){if(closed)return;closed=!0;clearTimeout(doneTimer);dialog.parentNode.removeChild(dialog)}
CodeMirror.on(dialog,'click',function(e){CodeMirror.e_preventDefault(e);close()});if(duration)
doneTimer=setTimeout(close,duration);return close})})