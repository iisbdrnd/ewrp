!function(e){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=e();else if("function"==typeof define&&define.amd)define([],e);else{var f;"undefined"!=typeof window?f=window:"undefined"!=typeof global?f=global:"undefined"!=typeof self&&(f=self),f.demo=e()}}(function(){var define,module,exports;return(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){var nm=_dereq_('../../lib')
module.exports={run:runDemo,preload:preload,skins:{wf:_dereq_('../../skins/workflowy'),wb:_dereq_('../../skins/whiteboard')},pl:{Mem:_dereq_('../../lib/pl/mem'),Firebase:_dereq_('../../lib/pl/firebase')}}
function merge(a,b){for(var c in b){a[c]=b[c]}
return a}
function preload(scripts,cb){var waiting=0
scripts.forEach(function(name){waiting+=1
var node=document.createElement('script')
node.src=name
var done=!1
node.onload=node.onreadystatechange=function(){if(done||(this.readyState&&this.readyState!=='loaded'&&this.readyState!=='complete')){return}
done=!0
node.onload=node.onreadystatechange=null
waiting-=1
if(!waiting){cb()}}
document.body.appendChild(node)})}
function runDemo(options,done){var o=merge({noTitle:!1,title:'Treed Example',el:'example',Model:nm.Model,Controller:nm.Controller,View:nm.View,viewOptions:{ViewLayer:nm.ViewLayer,Node:nm.Node},style:[],data:{},ctrlOptions:{},initDB:function(){}},options);if(!o.noTitle){document.title=o.title;document.getElementById('title').textContent=o.title}
o.style.forEach(function(name){var style=document.createElement('link');style.rel='stylesheet'
style.href=name
document.head.appendChild(style)});var db=o.pl||new module.exports.pl.Mem({});db.init(function(err){if(err){return loadFailed(err)}
initDB(db,function(err,root,map,wasEmpty){window.model=new o.Model(root,map,db)
window.ctrl=window.controller=new o.Controller(model,o.ctrlOptions)
window.view=window.view=ctrl.setView(o.View,o.viewOptions);if(wasEmpty){for(var i=0;i<o.data.children.length;i++){ctrl.importData(o.data.children[i],root.id)}
if(options.initDB)options.initDB(window.model)
window.view.rebase(root.id)}
document.getElementById(o.el).appendChild(view.getNode());done&&done(window.model,window.ctrl,window.view,db)},options.data.content,options.data.meta)})}
function initDB(db,done,rootName,rootMeta){db.findAll('root',function(err,roots){if(err)return done(err)
if(!roots.length){return loadDefault(db,done,rootName,rootMeta)}
db.findAll('node',function(err,nodes){if(err)return done(new Error('Failed to load items'))
if(!nodes.length)return done(new Error("Data corrupted - could not find root node"))
var map={}
for(var i=0;i<nodes.length;i++){map[nodes[i].id]=nodes[i]}
done(null,roots[0],map,!1)})})}
function loadDefault(db,done,rootName,rootMeta){var ROOT_ID=50
db.save('root',ROOT_ID,{id:ROOT_ID},function(){var map={}
map[ROOT_ID]={id:ROOT_ID,children:[],collapsed:!1,content:rootName,meta:rootMeta,depth:0}
db.save('node',ROOT_ID,map[ROOT_ID],function(){done(null,{id:ROOT_ID},map,!0)})})}},{"../../lib":11,"../../lib/pl/firebase":17,"../../lib/pl/mem":18,"../../skins/whiteboard":26,"../../skins/workflowy":30}],2:[function(_dereq_,module,exports){module.exports=BaseNode
var keys=_dereq_('./keys'),util=_dereq_('./util')
function BaseNode(content,meta,options,isNew,modelActions){this.content=content||''
this.isNew=isNew
this.modelActions=modelActions
this.o=options
this.o.keybindings=util.merge(this.default_keys,options.keys)
this.editing=!1
this.setupNode()}
BaseNode.addAction=function(action,binding,func){if(!this.extra_actions){this.extra_actions={}}
this.extra_actions[action]={binding:binding,func:func}}
BaseNode.prototype={startEditing:function(fromStart){},stopEditing:function(){},addEditText:function(text){},setMeta:function(meta){},setAttr:function(attr,value){},isAtStart:function(){},isAtEnd:function(){},isAtBottom:function(){},isAtTop:function(){},setupNode:function(){},setInputValue:function(value){},getInputValue:function(){},setTextContent:function(value){},getSelectionPosition:function(){},focus:function(){this.startEditing()},blur:function(){this.stopEditing()},keyHandler:function(){var actions={},action
for(action in this.o.keybindings){actions[this.o.keybindings[action]]=this.actions[action]}
if(this.extra_actions){for(action in this.extra_actions){if(!actions[action]){actions[this.extra_actions[action].binding]=this.extra_actions[action].action}}}
return keys(actions).bind(this)},default_keys:{'undo':'ctrl+z','redo':'ctrl+shift+z','collapse':'alt+left','uncollapse':'alt+right','dedent':'shift+tab, shift+alt+left','indent':'tab, shift+alt+right','move up':'shift+alt+up','move down':'shift+alt+down','up':'up','down':'down','left':'left','right':'right','add after':'return','insert return':'shift+return','merge up':'backspace','stop editing':'escape',},actions:{'undo':function(){this.o.undo()},'redo':function(){this.o.redo()},'collapse':function(){this.o.toggleCollapse(!0)},'uncollapse':function(){this.o.toggleCollapse(!1)},'dedent':function(){this.o.moveLeft()},'indent':function(){this.o.moveRight()},'move up':function(){this.o.moveUp()},'move down':function(){this.o.moveDown()},'up':function(){if(this.isAtTop()){this.o.goUp()}else{return!0}},'down':function(){if(this.isAtBottom()){this.o.goDown()}else{return!0}},'left':function(){if(this.isAtStart()){return this.o.goUp()}
return!0},'right':function(){if(this.isAtEnd()){return this.o.goDown(!0)}
return!0},'insert return':function(e){return!0},'add after':function(){var ss=this.getSelectionPosition(),content=this.getVisibleValue(),rest=null
if(this.isMultiLine()){return!0}
var rest=this.splitRightOfCursor()
if(!this.isNew){this.stopEditing()}
this.o.addAfter(rest,!0)},'merge up':function(){var value=this.getInputValue()
if(!value){return this.o.remove()}
if(!this.isMultiLine()&&this.isAtStart()){return this.o.remove(value)}
return!0},'stop editing':function(){this.stopEditing()}},}},{"./keys":13,"./util":21}],3:[function(_dereq_,module,exports){var commands=_dereq_('./commands')
module.exports=Commandeger
function makeCommand(type,args,commands){var names=commands[type].args,data={}
for(var i=0;i<names.length;i++){data[names[i]]=args[i]}
return{type:type,data:data}}
function Commandeger(model,extra_commands){this.history=[]
this.histpos=0
this.view=null
this.listeners={}
this.working=!1
this.model=model
this.commands=commands
if(extra_commands){for(var name in extra_commands){this.commands[name]=extra_commands[name]}}}
Commandeger.prototype={executeCommands:function(type,args){if(this.working)return
var cmds=[];var results=[];var i
for(i=0;i<arguments.length;i+=2){cmds.push(makeCommand(arguments[i],arguments[i+1],this.commands))}
if(this.histpos>0){this.history=this.history.slice(0,-this.histpos)
this.histpos=0}
this.history.push(cmds)
for(i=0;i<cmds.length;i++){results.push(this.doCommand(cmds[i]))}
this.trigger('change')
return results},trigger:function(what){var rest=[].slice.call(arguments,1)
for(var item in this.listeners[what]){this.listeners[what][item].apply(null,rest)}},on:function(what,cb){if(!this.listeners[what]){this.listeners[what]=[]}
this.listeners[what].push(cb)},undo:function(){document.activeElement.blur()
var pos=this.histpos?this.histpos+1:1,ix=this.history.length-pos
if(ix<0){return!1}
var cmds=this.history[ix]
for(var i=cmds.length-1;i>=0;i--){this.undoCommand(cmds[i])}
this.histpos+=1
this.trigger('change')
return!0},redo:function(){var pos=this.histpos?this.histpos-1:-1,ix=this.history.length-1-pos
if(ix>=this.history.length){return!1}
var cmds=this.history[ix]
for(var i=0;i<cmds.length;i++){this.redoCommand(cmds[i])}
this.histpos-=1
this.trigger('change')
return!0},setView:function(view){this.view=view},doCommand:function(cmd){this.working=!0
var result=this.commands[cmd.type].apply.call(cmd.data,this.view,this.model)
this.working=!1
return result},undoCommand:function(cmd){this.working=!0
this.commands[cmd.type].undo.call(cmd.data,this.view,this.model)
this.working=!1},redoCommand:function(cmd){this.working=!0
var c=this.commands[cmd.type];(c.redo||c.apply).call(cmd.data,this.view,this.model)
this.working=!1},}},{"./commands":4}],4:[function(_dereq_,module,exports){function copy(one){if('object'!==typeof one)return one
var two={}
for(var attr in one){two[attr]=one[attr]}
return two}
module.exports={collapse:{args:['id','doCollapse'],apply:function(view,model){model.setCollapsed(this.id,this.doCollapse)
view.setCollapsed(this.id,this.doCollapse)
view.goTo(this.id)},undo:function(view,model){model.setCollapsed(this.id,!this.doCollapse)
view.setCollapsed(this.id,!this.doCollapse)
view.goTo(this.id)},},appendText:{args:['id','text'],apply:function(view,model){this.oldtext=model.ids[this.id].content
model.appendText(this.id,this.text)
view.appendText(this.id,this.text)},undo:function(view,model){model.setContent(this.id,this.oldtext)
view.setContent(this.id,this.oldtext)}},changeContent:{args:['id','content'],apply:function(view,model){this.oldcontent=model.ids[this.id].content
model.setContent(this.id,this.content)
view.setContent(this.id,this.content)
view.goTo(this.id)},undo:function(view,model){model.setContent(this.id,this.oldcontent)
view.setContent(this.id,this.oldcontent)
view.goTo(this.id)}},changeNodeAttr:{args:['id','attr','value'],apply:function(view,model){this.oldvalue=copy(model.ids[this.id].meta[this.attr])
model.setAttr(this.id,this.attr,this.value)
view.setAttr(this.id,this.attr,this.value)
view.goTo(this.id)},undo:function(view,model){model.setAttr(this.id,this.attr,this.oldvalue)
view.setAttr(this.id,this.attr,this.oldvalue)
view.goTo(this.id)}},changeNode:{args:['id','newmeta'],apply:function(view,model){this.oldmeta=copy(model.ids[this.id].meta)
model.setMeta(this.id,this.newmeta)
view.setMeta(this.id,this.newmeta)
view.goTo(this.id)},undo:function(view,model){model.setMeta(this.id,this.oldmeta)
view.setMeta(this.id,this.oldmeta)
view.goTo(this.id)}},newNode:{args:['pid','index','text','meta','type'],apply:function(view,model){var cr=model.create(this.pid,this.index,this.text,this.type,this.meta)
this.id=cr.node.id
view.add(cr.node,cr.before)},undo:function(view,model){var ed=view.editing
view.remove(this.id)
this.saved=model.remove(this.id)
var nid=model.ids[this.pid].children[this.index-1]
if(nid===undefined)nid=this.pid
if(ed){view.startEditing(nid)}else{view.setActive(nid)}},redo:function(view,model){var before=model.readd(this.saved)
view.add(this.saved.node,before)}},move:{args:['id','pid','index'],apply:function(view,model){this.opid=model.ids[this.id].parent
this.oindex=model.ids[this.opid].children.indexOf(this.id)
var before=model.move(this.id,this.pid,this.index)
var parent=model.ids[this.opid],lastchild=parent.children.length===0
view.move(this.id,this.pid,before,this.opid,lastchild)
view.goTo(this.id)},undo:function(view,model){var before=model.move(this.id,this.opid,this.oindex),lastchild=model.ids[this.pid].children.length===0
view.move(this.id,this.opid,before,this.pid,lastchild)
view.goTo(this.id)}},remove:{args:['id'],apply:function(view,model){var closest=model.closestNonChild(this.id)
view.remove(this.id)
this.saved=model.remove(this.id)
view.startEditing(closest)},undo:function(view,model){var before=model.readd(this.saved)
view.addTree(this.saved.node,before)}},copy:{args:['ids'],apply:function(view,model){var items=this.ids.map(function(id){return model.dumpData(id,!0)})
model.clipboard=items},undo:function(view,model){}},cut:{args:['ids'],apply:function(view,model){var items=this.ids.map(function(id){view.remove(id,!0)
return model.dumpData(id,!0)})
model.clipboard=items
var id=this.ids[this.ids.length-1]
var closest=model.closestNonChild(id,this.ids)
this.saved=this.ids.map(function(id){return model.remove(id)})
if(view.editing){view.startEditing(closest)}else{view.setActive(closest)}},undo:function(view,model){var before
for(var i=this.saved.length-1;i>=0;i--){before=model.readd(this.saved[i])
view.addTree(this.saved[i].node,before)}
if(this.ids.length>1){view.setSelection(this.ids)
view.setActive(this.ids[this.ids.length-1])}}},importData:{args:['pid','index','data'],apply:function(view,model){var pid=this.pid,index=this.index,ed=view.editing,item=this.data
var cr=model.createNodes(pid,index,item)
view.addTree(cr.node,cr.before)
view.setCollapsed(cr.node.parent,!1)
model.setCollapsed(cr.node.parent,!1)
this.newid=cr.node.id
if(ed){view.startEditing(this.newid)}else{view.setActive(this.newid)}},undo:function(view,model){var id=this.newid
var closest=model.closestNonChild(id)
view.remove(id)
this.saved=model.remove(id)
if(view.editing){view.startEditing(closest)}else{view.setActive(closest)}
model.clipboard=this.saved},redo:function(view,model){var before=model.readd(this.saved)
view.addTree(this.saved.node,before)
if(view.editing){view.startEditing(this.newid)}else{view.setActive(this.newid)}}},paste:{args:['pid','index'],apply:function(view,model){var pid=this.pid,index=this.index,ed=view.editing
var ids=model.clipboard.map(function(item){var cr=model.createNodes(pid,index,item)
view.addTree(cr.node,cr.before)
if(model.ids[cr.node.parent].collapsed){view.setCollapsed(cr.node.parent,!1)
model.setCollapsed(cr.node.parent,!1)}
index+=1
return cr.node.id})
this.newids=ids
if(ids.length==1){if(ed){view.startEditing(this.newids[0])}else{view.setActive(this.newids[0])}}else{view.setSelection(ids)
view.setActive(ids[ids.length-1])}},undo:function(view,model){var id=this.newids[this.newids.length-1]
var closest=model.closestNonChild(id)
this.saved=this.newids.map(function(id){view.remove(id)
return model.remove(id)})
if(view.editing){view.startEditing(closest)}else{view.setActive(closest)}},redo:function(view,model){this.saved.map(function(item){var before=model.readd(item)
view.addTree(item.node,before)})}},}},{}],5:[function(_dereq_,module,exports){module.exports=Controller
var Commandeger=_dereq_('./commandeger'),util=_dereq_('./util')
function Controller(model,o){this.o=util.extend({noCollapseRoot:!0,extra_commands:!1},o||{})
this.model=model
this.cmd=new Commandeger(this.model,this.o.extra_commands)
this.model.db.listen('node',this.addFromDb.bind(this),this.updateFromDb.bind(this))
var actions={}
for(var action in this.actions){if('string'===typeof this.actions[action])actions[action]=this.actions[action]
else actions[action]=this.actions[action].bind(this)}
this.actions=actions
this.listeners={}}
Controller.prototype={addFromDb:function(id,data){this.view.update(id,data)
this.model.ids[id]=data},updateFromDb:function(id,data){this.view.update(id,data)
this.model.ids[id]=data},setView:function(View,options){var oview=this.view
this.view=new View(this.bindActions.bind(this),this.model,this.actions,options)
var root=(oview?oview.root:this.model.root);var node=this.view.initialize(root)
if(oview){oview.getNode().parentNode.replaceChild(node,oview.getNode())}
this.cmd.setView(this.view)
return this.view},undo:function(){this.cmd.undo()},redo:function(){this.cmd.redo()},on:function(evt,func){if(!this.listeners[evt]){this.listeners[evt]=[]}
this.listeners[evt].push(func)},off:function(evt,func){if(!this.listeners[evt])return!1
var i=this.listeners[evt].indexOf(func)
if(i===-1)return!1
this.listeners[evt].splice(i,1)
return!0},trigger:function(evt){if(!this.listeners[evt])return
var args=[].slice.call(arguments,1)
for(var i=0;i<this.listeners[evt].length;i++){this.listeners[evt][i].apply(null,args)}},bindActions:function(id){var actions={},val
for(var action in this.actions){val=this.actions[action]
if('string'===typeof val){val=this[val][action].bind(this[val],id)}else{val=val.bind(this,id)}
actions[action]=val}
return actions},importData:function(data,parent){if(arguments.length===1){parent=this.view.getActive()}
if(parent==="new"){this.view.removeNew()
parent=this.view.root}
this.executeCommands('importData',[parent,0,data])},exportData:function(){return this.model.dumpData(this.model.root,!0)},executeCommands:function(){var res
if(arguments.length===1&&Array.isArray(arguments[0])){res=this.cmd.executeCommands.apply(this.cmd,arguments[0])}else{res=this.cmd.executeCommands.apply(this.cmd,arguments)}
this.trigger('change')
return res},setCollapsed:function(id,doCollapse){if(this.o.noCollapseRoot&&id===this.view.root)return
if(!this.model.hasChildren(id))return
if(this.model.isCollapsed(id)===doCollapse)return
this.executeCommands('collapse',[id,doCollapse])},addBefore:function(id,text){var nw=this.model.idNew(id,!0)
this.executeCommands('newNode',[nw.pid,nw.index,text])},actions:{trigger:function(){this.trigger.apply(this,arguments)},setActive:function(id){this.view.setActive(id)},goUp:function(id){if(id===this.view.root)return
if(id==='new')return this.view.goTo(this.view.root)
var above=this.model.idAbove(id)
if(above===undefined)return
this.view.startEditing(above)},goDown:function(id,fromStart){if(id==='new')return this.view.goTo(this.view.root)
var below=this.model.idBelow(id,this.view.root)
if(below===undefined)return
this.view.startEditing(below,fromStart)},goLeft:function(id){if(id==='new')return this.view.goTo(this.view.root)
if(id===this.view.root)return
var parent=this.model.getParent(id)
if(!parent)return
this.view.startEditing(parent)},goRight:function(id){if(id==='new')return this.view.goTo(this.view.root)
var child=this.model.getChild(id)
if(!child)return
this.view.startEditing(child)},startMoving:function(id){if(id==='new')return
if(id===this.view.root)return
this.view.startMoving(id)},undo:function(){this.cmd.undo()},redo:function(){this.cmd.redo()},cut:function(ids){if(ids===this.view.root)return
if(!Array.isArray(ids)){ids=[ids]}
this.executeCommands('cut',[ids])},copy:function(ids){if(!Array.isArray(ids)){ids=[ids]}
this.executeCommands('copy',[ids])},pasteAbove:function(id){return this.actions.paste(id,!0)},paste:function(id,above){if(!this.model.clipboard)return
var nw=this.model.idNew(id,above)
this.executeCommands('paste',[nw.pid,nw.index])},changeContent:function(id,content){if(id==='new'){if(!content)return
var nw=this.view.removeNew()
this.executeCommands('newNode',[nw.pid,nw.index,content,{}])
return}
this.executeCommands('changeContent',[id,content])},changed:function(id,attr,value){if(id==='new'){if(!value)return
var nw=this.view.removeNew()
var meta={}
meta[attr]=value
this.executeCommands('newNode',[nw.pid,nw.index,'',meta])
return}
this.executeCommands('changeNodeAttr',[id,attr,value])},move:function(where,id,target){var action={before:'ToBefore',after:'ToAfter',child:'Into',lastChild:'IntoLast'}[where]
this.actions['move'+action](id,target)},moveToBefore:function(id,sid){if(id===this.view.root)return
if(id==='new')return
var place=this.model.moveBeforePlace(sid,id)
if(!place)return
this.executeCommands('move',[id,place.pid,place.ix])},moveToAfter:function(id,sid){if(id===this.view.root)return
if(id==='new')return
var place=this.model.moveAfterPlace(sid,id)
if(!place)return
this.executeCommands('move',[id,place.pid,place.ix])},moveInto:function(id,pid){if(id===this.view.root)return
if(id==='new')return
if(this.model.samePlace(id,{pid:pid,ix:0}))return
if(!this.model.isCollapsed(pid)){return this.executeCommands('move',[id,pid,0])}
this.executeCommands('collapse',[pid,!1],'move',[id,pid,0])},moveIntoLast:function(id,pid){if(id===this.view.root)return
if(id==='new')return
var ix=this.model.ids[pid].children.length
if(this.model.samePlace(id,{pid:pid,ix:ix}))return
if(!this.model.isCollapsed(pid)){return this.executeCommands('move',[id,pid,ix])}
this.executeCommands('collapse',[pid,!1],'move',[id,pid,ix])},moveRight:function(id){if(id===this.view.root)return
if(id==='new')return
var sib=this.model.prevSibling(id,!0)
if(undefined===sib)return
if(!this.model.isCollapsed(sib)){return this.executeCommands('move',[id,sib,!1])}
this.executeCommands('collapse',[sib,!1],'move',[id,sib,!1])},moveLeft:function(id){if(id===this.view.root)return
if(id==='new')return
if(this.model.ids[id].parent===this.view.root)return
var place=this.model.shiftLeftPlace(id)
if(!place)return
this.executeCommands('move',[id,place.pid,place.ix])},moveUp:function(id){if(id===this.view.root)return
if(id==='new')return
var place=this.model.shiftUpPlace(id)
if(!place)return
if(place.pid===this.model.ids[this.view.root].parent)return
this.executeCommands('move',[id,place.pid,place.ix])},moveDown:function(id){if(id===this.view.root)return
if(id==='new')return
var place=this.model.shiftDownPlace(id)
if(!place)return
if(place.pid===this.model.ids[this.view.root].parent)return
this.executeCommands('move',[id,place.pid,place.ix])},moveToTop:function(id){if(id===this.view.root)return
if(id==='new')return
var first=this.model.firstSibling(id)
if(undefined===first)return
var pid=this.model.ids[first].parent
if(pid===undefined)return
var ix=this.model.ids[pid].children.indexOf(first)
this.executeCommands('move',[id,pid,ix])},moveToBottom:function(id){if(id===this.view.root)return
if(id==='new')return
var last=this.model.lastSibling(id)
if(undefined===last)return
var pid=this.model.ids[last].parent
if(pid===undefined)return
var ix=this.model.ids[pid].children.indexOf(last)
this.executeCommands('move',[id,pid,ix+1])},toggleCollapse:function(id,yes){if(this.o.noCollapseRoot&&id===this.view.root)return
if(id==='new')return
if(arguments.length===1){yes=!this.model.hasChildren(id)||!this.model.isCollapsed(id)}
if(yes){id=this.model.findCollapser(id)
if(this.o.noCollapseRoot&&id===this.view.root)return
if(!this.model.hasChildren(id)||this.model.isCollapsed(id))return}else{if(!this.model.hasChildren(id)||!this.model.isCollapsed(id))return}
this.executeCommands('collapse',[id,yes])},addChild:function(pid,index,content,config){this.executeCommands('newNode',[pid,index,content,config])},commands:function(){this.executeCommands.apply(this,arguments)},addBefore:function(id,text,focus){if(id===this.view.root)return
if(id==='new'){return}
var nw=this.model.idNew(id,!0)
this.executeCommands('newNode',[nw.pid,nw.index,text])
if(focus)this.view.startEditing()},addAfter:function(id,text,focus){var nw
var ed=focus||this.view.mode==='insert'
if(id==='new'){nw=this.view.removeNew()
this.executeCommands('newNode',[nw.pid,nw.index+1,''])
if(ed)this.view.startEditing()
return}
if(id===this.view.root){var node=this.model.ids[id]
if(!node.children||!node.children.length){if(this.view.newNode)return this.view.startEditing('new')
this.view.addNew(id,0)
this.view.startEditing('new')
return}}
nw=this.model.idNew(id,!1,this.view.root)
this.executeCommands('newNode',[nw.pid,nw.index,text])
if(ed)this.view.startEditing()},remove:function(id,addText){if(id===this.view.root)return
if(id==='new')return
var before=this.model.idAbove(id)
this.executeCommands('remove',[id],'appendText',[before,addText||''])},setEditing:'view',doneEditing:'view'}}},{"./commandeger":3,"./util":21}],6:[function(_dereq_,module,exports){module.exports={visual:{'select up':'k, up','select down':'j, down','select to bottom':'shift+g','select to top':'g g','stop selecting':'v, shift+v, escape','edit':'a, shift+a','edit start':'i, shift+i','cut':'d, shift+d, ctrl+x','copy':'y, shift+y, ctrl+c','undo':'u, ctrl+z','redo':'shift+r, ctrl+shift+z'},view:{base:{'cut':'cmd+x, delete, d d','copy':'cmd+c, y y','paste':'p, cmd+v','paste above':'shift+p, cmd+shift+v','visual mode':'v, shift+v','change':'c c, shift+c','edit':'return, a, shift+a, f2','edit start':'i, shift+i','first sibling':'shift+[','last sibling':'shift+]','move to first sibling':'ctrl+shift+[','move to last sibling':'ctrl+shift+]','new after':'o','new before':'shift+o','jump to top':'g g','jump to bottom':'shift+g','up':'up, k','down':'down, j','left':'left, h','right':'right, l','next sibling':'alt+j, alt+down','prev sibling':'alt+k, alt+up','toggle collapse':'z','collapse':'alt+h, alt+left','uncollapse':'alt+l, alt+right','indent':'tab, shift+alt+l, shift+alt+right','dedent':'shift+tab, shift+alt+h, shift+alt+left','move down':'shift+alt+j, shift+alt+down','move up':'shift+alt+k, shift+alt+i, shift+alt+up','undo':'ctrl+z, u','redo':'ctrl+shift+z, shift+r',},mac:{},pc:{}},}},{}],7:[function(_dereq_,module,exports){module.exports=DefaultNode
var BaseNode=_dereq_('./base-node')
if(window.marked){var renderer=new marked.Renderer()
renderer.link=function(href,title,text){return '<a href="'+href+'" target="_blank" title="'+title+'">'+text+'</a>'}
marked.setOptions({gfm:!0,sanitize:!0,tables:!0,breaks:!0,pedantic:!1,sanitize:!1,smartLists:!0,smartypants:!0,renderer:renderer})}
function DefaultNode(content,meta,options,isNew,modelActions){BaseNode.call(this,content,meta,options,isNew,modelActions)}
DefaultNode.prototype=Object.create(BaseNode.prototype)
DefaultNode.prototype.constructor=DefaultNode
function tmerge(a,b){for(var c in b){a[c]=b[c]}}
function escapeHtml(str){if(!str)return '';var div=document.createElement('div');div.appendChild(document.createTextNode(str));return div.innerHTML};function unEscapeHtml(str){if(!str)return '';return str.replace(/<div>/g,'\n').replace(/<br>/g,'\n').replace(/<\/div>/g,'').replace(/\u200b/g,'')}
tmerge(DefaultNode.prototype,{setInputValue:function(value){this.input.innerHTML=value},getInputValue:function(){return unEscapeHtml(this.input.innerHTML)},getVisibleValue:function(){if(!this.input.firstChild)return ''
return this.input.firstChild.textContent},isMultiLine:function(){return this.input.innerHTML.match(/(<div>|<br|\n)/g)},splitRightOfCursor:function(){var text=this.getVisibleValue(),s=this.getSelectionPosition(),left=escapeHtml(text.slice(0,s)),right=escapeHtml(text.slice(s))
if(!right)return
this.setInputValue(left)
this.setTextContent(left)
if(!this.isNew)this.o.changeContent(left)
return right},setTextContent:function(value){this.text.innerHTML=value?marked(value+''):''},setupNode:function(){this.node=document.createElement('div')
this.input=document.createElement('div')
this.input.setAttribute('contenteditable',!0)
this.input.classList.add('treed__input')
this.text=document.createElement('div')
this.text.classList.add('treed__text')
this.node.classList.add('treed__default-node')
this.setTextContent(this.content)
this.node.appendChild(this.text);this.registerListeners()},isAtTop:function(){var bb=this.input.getBoundingClientRect(),selr=window.getSelection().getRangeAt(0).getClientRects()[0]
return selr.top<bb.top+5},isAtBottom:function(){var bb=this.input.getBoundingClientRect(),selr=window.getSelection().getRangeAt(0).getClientRects()[0]
return selr.bottom>bb.bottom-5},getSelectionPosition:function(){var sel=window.getSelection(),ran=sel.getRangeAt(0)
return ran.startOffset},startEditing:function(fromStart){this.setInputValue(this.content)
this.input.focus();this.setSelection(!fromStart)
this.o.setEditing()},stopEditing:function(){if(!this.editing)return
console.log('stop eddint',this.isNew)
var value=this.getInputValue()
this.editing=!1
this.node.replaceChild(this.text,this.input)
this.o.doneEditing();if(this.content!=value||this.isNew){this.setTextContent(value)
this.content=value
this.o.changeContent(this.content)}},isAtStart:function(){return this.getSelectionPosition()===0},isAtEnd:function(){console.warn("THIS IS WRONG")
return!1},addEditText:function(text){var pl=this.content.length
this.content+=text
this.setInputValue(this.content)
this.setTextContent(this.content)
if(!this.editing){this.editing=!0;this.node.replaceChild(this.input,this.text)
this.o.setEditing()}
this.setSelection(pl)},setContent:function(content){this.content=content
this.setInputValue(content)
this.setTextContent(content)},registerListeners:function(){this.text.addEventListener('mousedown',function(e){if(e.target.nodeName=='A'){return}
this.startEditing();e.preventDefault()
return!1}.bind(this))
this.input.addEventListener('blur',function(e){this.stopEditing();e.preventDefault()
return!1}.bind(this));var keyHandler=this.keyHandler()
this.input.addEventListener('keydown',function(e){e.stopPropagation()
return keyHandler(e)})},setSelection:function(end){var sel=window.getSelection()
sel.selectAllChildren(this.input)
try{sel['collapseTo'+(end?'End':'Start')]()}catch(e){}},})},{"./base-node":2}],8:[function(_dereq_,module,exports){module.exports=DungeonsAndDragons
function findTarget(targets,e){for(var i=0;i<targets.length;i++){if(targets[i].top>e.clientY){return targets[i>0?i-1:0]}}
return targets[targets.length-1]}
function DungeonsAndDragons(vl,action,findFunction){this.vl=vl
this.action=action
this.findFunction=findFunction||findTarget}
DungeonsAndDragons.prototype={startMoving:function(targets,id){this.moving={targets:targets,shadow:this.vl.makeDropShadow(),current:null}
this.vl.setMoving(id,!0)
var onMove=function(e){this.drag(id,e)}.bind(this)
var onUp=function(e){document.body.style.cursor=''
document.removeEventListener('mousemove',onMove)
document.removeEventListener('mouseup',onUp)
this.drop(id,e)}.bind(this)
document.body.style.cursor='move'
document.addEventListener('mousemove',onMove)
document.addEventListener('mouseup',onUp)},drag:function(id,e){if(this.moving.current){this.vl.setDropping(this.moving.current.id,!1,this.moving.current.place==='child')}
var target=this.findFunction(this.moving.targets,e)
this.moving.shadow.moveTo(target)
this.moving.current=target
this.vl.setDropping(target.id,!0,this.moving.current.place==='child')},drop:function(id,e){this.moving.shadow.remove()
var current=this.moving.current
this.vl.setMoving(id,!1)
if(!this.moving.current)return
this.vl.setDropping(current.id,!1,current.place==='child')
if(current.id===id)return
this.action(current.place,id,current.id)
this.moving=!1},}},{}],9:[function(_dereq_,module,exports){var DropShadow=_dereq_('./drop-shadow'),slideDown=_dereq_('./slide-down'),slideUp=_dereq_('./slide-up'),util=_dereq_('./util')
module.exports=DomViewLayer
function DomViewLayer(o){this.dom={}
this.root=null
this.o=util.merge({animate:!0},o)}
DomViewLayer.prototype={clear:function(){this.dom={}},rebase:function(root){if(root.parentNode){root.parentNode.replaceChild(this.root,root)}},dropTargets:function(root,model,moving,top){var targets=[],bc=this.dom[root].head.getBoundingClientRect(),target,childTarget
if(!top){targets.push({id:root,top:bc.top,left:bc.left,width:bc.width,height:bc.height,place:'before',show:{left:bc.left,width:bc.width,y:bc.top}})}
if(root===moving)return targets
if(model.isCollapsed(root)&&!top)return targets
var ch=model.ids[root].children
if(ch){for(var i=0;i<ch.length;i++){targets=targets.concat(this.dropTargets(ch[i],model,moving))}}
if(top){var bodyBox=this.dom[root].ul.getBoundingClientRect()
targets.push({id:root,top:bodyBox.bottom,left:bodyBox.left,width:bodyBox.width,height:bc.height,place:'lastChild',show:{left:bodyBox.left,width:bodyBox.width,y:bodyBox.bottom}})}
return targets},makeDropShadow:function(){return new DropShadow()},remove:function(id,pid,lastchild){var n=this.dom[id]
if(!n||!n.main.parentNode)return
try{n.main.parentNode.removeChild(n.main)}catch(e){return}
delete this.dom[id]
if(lastchild){this.dom[pid].main.classList.remove('treed__item--parent')}},addNew:function(node,bounds,modelActions,before,children){var dom=this.makeNode(node.id,node.content,node.meta,node.depth-this.rootDepth,bounds,modelActions)
this.add(node.parent,before,dom,children)
if(node.collapsed&&node.children.length){this.setCollapsed(node.id,!0)}},add:function(parent,before,dom,children){var p=this.dom[parent]
if(before===!1){p.ul.appendChild(dom)}else{var bef=this.dom[before]
p.ul.insertBefore(dom,bef.main)}
p.main.classList.add('treed__item--parent')
if(children){dom.classList.add('treed__item--parent')}},clearChildren:function(id){var ul=this.dom[id].ul
while(ul.lastChild){ul.removeChild(ul.lastChild)}},body:function(id){if(!this.dom[id])return
return this.dom[id].body},move:function(id,pid,before,ppid,lastchild){var d=this.dom[id]
d.main.parentNode.removeChild(d.main)
if(lastchild){this.dom[ppid].main.classList.remove('treed__item--parent')}
if(before===!1){this.dom[pid].ul.appendChild(d.main)}else{this.dom[pid].ul.insertBefore(d.main,this.dom[before].main)}
this.dom[pid].main.classList.add('treed__item--parent')},clearSelection:function(selection){for(var i=0;i<selection.length;i++){if(!this.dom[selection[i]])continue;this.dom[selection[i]].main.classList.remove('selected')}},showSelection:function(selection){if(!selection.length)return
for(var i=0;i<selection.length;i++){this.dom[selection[i]].main.classList.add('selected')}},clearActive:function(id){if(!this.dom[id])return
this.dom[id].main.classList.remove('active')},showActive:function(id){if(!this.dom[id])return console.warn('Trying to activate a node that is not rendered')
util.ensureInView(this.dom[id].body.node)
this.dom[id].main.classList.add('active')},setCollapsed:function(id,isCollapsed){this.dom[id].main.classList[isCollapsed?'add':'remove']('collapsed')},animateOpen:function(id){this.setCollapsed(id,!1)
slideDown(this.dom[id].ul)},animateClosed:function(id,done){slideUp(this.dom[id].ul,function(){this.setCollapsed(id,!0)}.bind(this))},setMoving:function(id,isMoving){this.root.classList[isMoving?'add':'remove']('moving')
this.dom[id].main.classList[isMoving?'add':'remove']('moving')},setDropping:function(id,isDropping,isChild){var cls='dropping'+(isChild?'-child':'')
this.dom[id].main.classList[isDropping?'add':'remove'](cls)},makeRoot:function(node,bounds,modelActions){var dom=this.makeNode(node.id,node.content,node.meta,0,bounds,modelActions),root=document.createElement('div')
root.classList.add('treed')
root.appendChild(dom)
if(node.children.length){dom.classList.add('treed__item--parent')}
if(node.collapsed&&node.children.length){this.setCollapsed(node.id,!0)}
this.root=root
this.rootDepth=node.depth
return root},makeHead:function(body,actions){var head=document.createElement('div'),collapser=document.createElement('div')
collapser.addEventListener('mousedown',function(e){if(e.button!==0)return
actions.toggleCollapse()
e.preventDefault()})
collapser.classList.add('treed__collapser')
head.classList.add('treed__head')
head.appendChild(collapser)
head.appendChild(body.node);return head},makeNode:function(id,content,meta,level,bounds,modelActions){var dom=document.createElement('li'),body=this.bodyFor(id,content,meta,bounds,modelActions)
dom.classList.add('treed__item')
var head=this.makeHead(body,bounds)
dom.appendChild(head)
var ul=document.createElement('ul')
ul.classList.add('treed__children')
dom.appendChild(ul)
this.dom[id]={main:dom,body:body,ul:ul,head:head}
return dom},bodyFor:function(id,content,meta,bounds,modelActions){var dom=new this.o.Node(content,meta,bounds,id==='new',modelActions)
dom.node.classList.add('treed__body')
return dom},}},{"./drop-shadow":10,"./slide-down":19,"./slide-up":20,"./util":21}],10:[function(_dereq_,module,exports){module.exports=DropShadow;function DropShadow(height,clsName){this.node=document.createElement('div')
this.node.classList.add(clsName||'treed__drop-shadow')
this.height=height||10
document.body.appendChild(this.node)}
DropShadow.prototype={moveTo:function(target){this.node.style.top=target.show.y-this.height/2+'px'
this.node.style.left=target.show.left+'px'
this.node.style.height=this.height+'px'
this.node.style.width=target.show.width+'px'},remove:function(){this.node.parentNode.removeChild(this.node)}}},{}],11:[function(_dereq_,module,exports){module.exports={Node:_dereq_('./default-node'),View:_dereq_('./view'),ViewLayer:_dereq_('./dom-vl'),Model:_dereq_('./model'),Controller:_dereq_('./controller'),}},{"./controller":5,"./default-node":7,"./dom-vl":9,"./model":14,"./view":23}],12:[function(_dereq_,module,exports){module.exports=keyHandler
function keyHandler(keys,actions,ctrlactions){var bound={}
for(var action in keys){if(!actions[action]){throw new Error('invalid configuration: trying to bind unknown action. '+action)}
bound[keys[action]]=bindAction(action,actions[action],ctrlactions)}
return bound}
function bindAction(name,action,ctrlactions){var pre=makePre(action.active)
var type=typeof action.action
var main
switch(typeof action.action){case 'string':main=ctrlactions[action.action];break;case 'undefined':main=ctrlactions[camel(name)];break;case 'function':main=action.action;break;default:throw new Error('unknown action '+action.action)}
if(!main){throw new Error('Invalid action configuration '+name)}
if(!pre){return main}
return function(){if(!pre.call(this))return
return main.call(this,this.active)}}
function makePre(active){switch(active){case true:return function(main){return this.active}
case '!new':return function(main){return this.active&&this.active!=='new'}
case '!root':return function(main){return this.active&&this.active!==this.root}
default:return null}}
function camel(string){return string.replace(/ (\w)/,function(a,x){return x.toUpperCase()})}},{}],13:[function(_dereq_,module,exports){module.exports=keys
keys.keyName=keyName
var KEYS={8:'backspace',9:'tab',13:'return',27:'escape',37:'left',38:'up',39:'right',40:'down',46:'delete',113:'f2',219:'[',221:']'}
function keyName(code){if(code<=90&&code>=65){return String.fromCharCode(code+32)}
if(code>=48&&code<=57){return String.fromCharCode(code)}
return KEYS[code]}
function keys(config){var kmap={},prefixes={},cur_prefix=null,parts,part,seq
for(var key in config){parts=key.split(',')
for(var i=0;i<parts.length;i++){part=parts[i].trim()
kmap[part]=config[key]
if(part.indexOf(' ')!==-1){seq=part.split(/\s+/g)
var n=''
for(var j=0;j<seq.length-1;j++){n+=seq[j]
prefixes[n]=!0}}}}
return function(e){var key=keyName(e.keyCode)
if(!key){return console.log(e.keyCode)}
if(e.altKey)key='alt+'+key
if(e.shiftKey)key='shift+'+key
if(e.ctrlKey)key='ctrl+'+key
if(e.metaKey)key='cmd+'+key
if(cur_prefix){key=cur_prefix+' '+key
cur_prefix=null}
if(!kmap[key]){if(prefixes[key]){cur_prefix=key}else{cur_prefix=null}
return}
if(kmap[key].call(this,e)!==!0){e.preventDefault()
e.stopPropagation()
return!1}}}},{}],14:[function(_dereq_,module,exports){var uuid=_dereq_('./uuid')
module.exports=Model
function Model(rootNode,ids,db){this.ids=ids
this.root=rootNode.id
this.rootNode=rootNode
this.db=db
this.nextid=100
this.clipboard=!1
this.boundActions=this.bindActions()}
Model.prototype={newid:function(){return uuid()},bindActions:function(){var bound={}
for(var name in this.actions){bound[name]=this.actions[name].bind(this)}
return bound},actions:{},dumpData:function(id,noids){if(arguments.length===0){id=this.root}
var res={meta:{},},n=this.ids[id]
res.content=n.content
res.created=n.created
res.type=n.type
res.modified=n.modified
for(var attr in n.meta){res.meta[attr]=n.meta[attr]}
if(n.children&&n.children.length){res.children=[]
for(var i=0;i<n.children.length;i++){res.children.push(this.dumpData(n.children[i],noids))}}
if(!noids)res.id=id
res.collapsed=n.collapsed||!1
return res},createNodes:function(pid,index,data){var cr=this.create(pid,index,data.content,data.type,data.meta)
cr.node.collapsed=data.collapsed||!1
if(data.children){for(var i=0;i<data.children.length;i++){this.createNodes(cr.node.id,i,data.children[i])}}
return cr},getBefore:function(pid,index){var before=!1
if(index<this.ids[pid].children.length-1){before=this.ids[pid].children[index+1]}
return before},create:function(pid,index,text,type,meta){var node={id:this.newid(),content:text||'',collapsed:!1,type:type||'base',meta:meta||{},parent:pid,children:[]}
this.ids[node.id]=node
if(!this.ids[pid].children){this.ids[pid].children=[]}
this.ids[pid].children.splice(index,0,node.id)
var before=!1
if(index<this.ids[pid].children.length-1){before=this.ids[pid].children[index+1]}
setTimeout(function(){this.db.save('node',node.id,node)
this.db.update('node',pid,{children:this.ids[pid].children})}.bind(this))
return{node:node,before:before}},remove:function(id){if(id===this.root)return
var n=this.ids[id],p=this.ids[n.parent],ix=p.children.indexOf(id)
p.children.splice(ix,1)
delete this.ids[id]
setTimeout(function(){this.db.remove('node',id)
this.db.update('node',n.parent,{children:p.children})}.bind(this))
return{id:id,node:n,ix:ix}},setContent:function(id,content){this.ids[id].content=content
this.db.update('node',id,{content:content})},setAttr:function(id,attr,value){this.ids[id].meta[attr]=value
this.db.update('node',id,{meta:this.ids[id].meta})},setMeta:function(id,meta){for(var attr in meta){this.ids[id].meta[attr]=meta[attr]}
this.db.update('node',id,{meta:meta})},setCollapsed:function(id,isCollapsed){this.ids[id].collapsed=isCollapsed
this.db.update('node',id,{collapsed:isCollapsed})},isCollapsed:function(id){return this.ids[id].collapsed},hasChildren:function(id){return this.ids[id].children&&this.ids[id].children.length},readd:function(saved){this.ids[saved.id]=saved.node
var children=this.ids[saved.node.parent].children
children.splice(saved.ix,0,saved.id)
var before=!1
if(saved.ix<children.length-1){before=children[saved.ix+1]}
this.db.save('node',saved.node.id,saved.node)
this.db.update('node',saved.node.parent,{children:children})
return before},move:function(id,pid,index){var n=this.ids[id],opid=n.parent,p=this.ids[opid],ix=p.children.indexOf(id)
p.children.splice(ix,1)
if(!this.ids[pid].children){this.ids[pid].children=[]}
if(index===!1)index=this.ids[pid].children.length
this.ids[pid].children.splice(index,0,id)
this.ids[id].parent=pid
setTimeout(function(){this.db.update('node',opid,{children:p.children})
this.db.update('node',id,{parent:pid})
this.db.update('node',pid,{children:this.ids[pid].children})}.bind(this))
var before=!1
if(index<this.ids[pid].children.length-1){before=this.ids[pid].children[index+1]}
return before},appendText:function(id,text){this.ids[id].content+=text
this.db.update('node',id,{content:this.ids[id].content})},getParent:function(id){return this.ids[id].parent},commonParent:function(one,two){if(one===two)return one
var ones=[one],twos=[two]
while(this.ids[one].parent||this.ids[two].parent){if(this.ids[one].parent){one=this.ids[one].parent
if(twos.indexOf(one)!==-1)return one
ones.push(one)}
if(this.ids[two].parent){two=this.ids[two].parent
if(ones.indexOf(two)!==-1)return two
twos.push(two)}}
return null},getChild:function(id){if(this.ids[id].children&&this.ids[id].children.length){return this.ids[id].children[0]}
return this.nextSibling(id)},prevSibling:function(id,noparent){var pid=this.ids[id].parent
if(undefined===pid)return
var ix=this.ids[pid].children.indexOf(id)
if(ix>0)return this.ids[pid].children[ix-1]
if(!noparent)return pid},closestNonChild:function(id,others){var closest=this.nextSibling(id,!0)
if(undefined===closest||closest===!1){if(others){closest=this.idAbove(others[0])}else{closest=this.idAbove(id)}}
return closest},nextSibling:function(id,strict){var pid=this.ids[id].parent
if(undefined===pid)return!strict&&this.ids[id].children[0]
var ix=this.ids[pid].children.indexOf(id)
if(ix<this.ids[pid].children.length-1)return this.ids[pid].children[ix+1]
if(this.ids[id].collapsed){return!strict&&this.nextSibling(pid,strict)}
return!strict&&this.ids[id].children[0]},lastSibling:function(id,strict){var pid=this.ids[id].parent
if(undefined===pid)return!strict&&this.ids[id].children[0]
var ix=this.ids[pid].children.indexOf(id)
if(ix===this.ids[pid].children.length-1)return!strict&&this.ids[id].children[0]
return this.ids[pid].children[this.ids[pid].children.length-1]},firstSibling:function(id,strict){var pid=this.ids[id].parent
if(undefined===pid)return
var ix=this.ids[pid].children.indexOf(id)
if(ix===0)return!strict&&pid
return this.ids[pid].children[0]},lastOpen:function(id){var node=this.ids[id]
while(node.children.length&&(node.id===id||!node.collapsed)){node=this.ids[node.children[node.children.length-1]]}
return node.id},idAbove:function(id){var pid=this.ids[id].parent,parent=this.ids[pid]
if(!parent)return
var ix=parent.children.indexOf(id)
if(ix===0){return pid}
var previd=parent.children[ix-1]
while(this.ids[previd].children&&this.ids[previd].children.length&&!this.ids[previd].collapsed){previd=this.ids[previd].children[this.ids[previd].children.length-1]}
return previd},shiftLeftPlace:function(id){var pid=this.ids[id].parent,parent=this.ids[pid]
if(!parent)return
var ppid=parent.parent,pparent=this.ids[ppid]
if(!pparent)return
var pix=pparent.children.indexOf(pid)
return{pid:ppid,ix:pix+1}},shiftUpPlace:function(id){var pid=this.ids[id].parent,parent=this.ids[pid]
if(!parent)return
var ix=parent.children.indexOf(id)
if(ix===0){var pl=this.shiftLeftPlace(id)
if(!pl)return
pl.ix-=1
return pl}
return{pid:pid,ix:ix-1}},shiftDownPlace:function(id){var pid=this.ids[id].parent,parent=this.ids[pid]
if(!parent)return
var ix=parent.children.indexOf(id)
if(ix>=parent.children.length-1){return this.shiftLeftPlace(id)}
return{pid:pid,ix:ix+1}},moveBeforePlace:function(id,tid){var sib=this.ids[id],pid=sib.parent,opid=this.ids[tid].parent
if(undefined===pid)return
var parent=this.ids[pid]
var nix=parent.children.indexOf(id)
if(pid===opid&&parent.children.indexOf(tid)<nix){nix-=1}
return{pid:pid,ix:nix}},moveAfterPlace:function(id,oid){var sib=this.ids[id],pid=sib.parent,opid=this.ids[oid].parent
if(undefined===pid)return
var oix=this.ids[opid].children.indexOf(oid)
var parent=this.ids[pid],ix=parent.children.indexOf(id)+1
if(pid===opid&&ix>oix)ix-=1
return{pid:pid,ix:ix}},idBelow:function(id,root){if(this.ids[id].children&&this.ids[id].children.length&&(id===root||!this.ids[id].collapsed)){return this.ids[id].children[0]}
var pid=this.ids[id].parent,parent=this.ids[pid]
if(!parent)return
var ix=parent.children.indexOf(id)
while(ix===parent.children.length-1){if(parent.id===root)return
parent=this.ids[parent.parent]
if(!parent)return
ix=parent.children.indexOf(pid)
pid=parent.id}
return parent.children[ix+1]},idNew:function(id,before,root){var pid=this.ids[id].parent,parent,nix
if(before){parent=this.ids[pid]
nix=parent.children.indexOf(id)}else if(id===this.root||root===id||(this.ids[id].children&&this.ids[id].children.length&&!this.ids[id].collapsed)){pid=id
nix=0}else{parent=this.ids[pid]
nix=parent.children.indexOf(id)+1}
return{pid:pid,index:nix}},samePlace:function(id,place){var pid=this.ids[id].parent
if(!pid||pid!==place.pid)return!1
var parent=this.ids[pid],ix=parent.children.indexOf(id)
return ix===place.ix},findCollapser:function(id){if((!this.ids[id].children||!this.ids[id].children.length||this.ids[id].collapsed)&&this.ids[id].parent!==undefined){id=this.ids[id].parent}
return id},}},{"./uuid":22}],15:[function(_dereq_,module,exports){module.exports={'undo':{help:'Undo the last action',},'redo':{help:'Undo the last action',},'cut':{help:'remove the currnetly selected item and place it in the clipboard',active:!0,},'copy':{help:'place the currently selected item in the clipboard',active:!0,},'paste':{help:'insert the contents of the clipboard, into or below the currently selected item',active:!0,},'paste above':{help:'insert the contents of the clipboard above the currently selected item',active:!0,},'visual mode':{help:'enter multi-select mode',active:'!root',action:function(){this.setSelection([this.active])},},'change':{help:'clear the contents of this node and start editing',active:!0,action:function(){this.vl.body(this.active).setContent('')
this.vl.body(this.active).startEditing()},},edit:{help:'start editing this node at the end',active:!0,action:function(){this.vl.body(this.active).startEditing()}},'edit start':{help:'start editing this node at the start',active:!0,action:function(){this.vl.body(this.active).startEditing(!0)},},'first sibling':{help:'jump to the first sibling',active:'!new',action:function(){var first=this.model.firstSibling(this.active)
if(undefined===first)return
this.setActive(first)}},'last sibling':{help:'jump to the last sibling',active:'!new',action:function(){var last=this.model.lastSibling(this.active)
if(undefined===last)return
this.setActive(last)},},'jump to top':{help:'jump to the top',action:function(){this.setActive(this.root)},},'jump to bottom':{help:'jump to the last node',action:function(){this.setActive(this.model.lastOpen(this.root))
console.log('bottom')},},'up':{help:'go to the previous node',active:!0,action:function(){var above
if(this.active==='new'){above=this.root}else{var top=this.active
above=this.model.idAbove(top)
if(above===undefined)above=top}
if(above===this.root&&this.o.noSelectRoot){return}
this.setActive(above)},},'down':{help:'go down to the next node',active:'!new',action:function(){if(this.active===this.root&&!this.model.ids[this.root].children.length){return this.setActive('new')}
var top=this.active,above=this.model.idBelow(top,this.root)
if(above===undefined)above=top
this.setActive(above)}},'left':{help:'go up a level to the parent',active:!0,action:function(){if(this.active===null){return this.setActive(this.root)}
if(this.active==='new')return this.setActive(this.root)
var left=this.model.getParent(this.active)
if(undefined===left)return
this.setActive(left)},},'right':{help:'go down a level to the first child',active:'!now',action:function(){if(this.active===this.root&&!this.model.ids[this.root].children.length){return this.setActive('new')}
var right=this.model.getChild(this.active)
if(this.model.isCollapsed(this.active))return
if(undefined===right)return
this.setActive(right)},},'next sibling':{help:'jump to the next sibling (skipping children)',active:'!new',action:function(){var sib=this.model.nextSibling(this.active)
if(undefined===sib)return
this.setActive(sib)},},'prev sibling':{help:'jump to the previous sibling (skipping children)',active:'!new',action:function(){var sib=this.model.prevSibling(this.active)
if(undefined===sib)return
this.setActive(sib)},},'move to first sibling':{help:'move this node to be the first child if its parent',active:'!new',action:'moveToTop'},'move to last sibling':{help:'move this to be the last child of its parent',active:'!new',action:'moveToBottom'},'new before':{help:'create a node above this one and start editing',active:!0,action:function(){this.ctrlactions.addBefore(this.active,'',!0)}},'new after':{help:'create a node after this one and start editing',active:!0,action:function(){if(this.active==='new')return this.startEditing()
this.ctrlactions.addAfter(this.active,'',!0)},},'toggle collapse':{help:'toggle collapse',active:!0,},'collapse':{help:'collapse the node',active:!0,action:function(){this.ctrlactions.toggleCollapse(this.active,!0)},},'uncollapse':{help:'expand the node',active:!0,action:function(){this.ctrlactions.toggleCollapse(this.active,!1)}},'indent':{help:'indent the node',active:!0,action:function(){this.ctrlactions.moveRight(this.active)},},'dedent':{help:'dedent the node',active:!0,action:function(){this.ctrlactions.moveLeft(this.active)},},'move down':{help:'move the current node down',active:!0},'move up':{help:'move the current node up',active:!0,},}},{}],16:[function(_dereq_,module,exports){function merge(a){for(var i=1;i<arguments.length;i++){for(var name in arguments[i]){a[name]=arguments[i][name]}}
return a}
module.exports=Base
function noop(){throw new Error("Not implemented!")}
function Base(){this._listeners={}}
Base.extend=function(fn,obj){fn.prototype=merge(Object.create(Base.prototype),obj)
fn.prototype.constructor=fn}
Base.prototype={init:function(done){done()},listen:function(type,add,change){},save:noop,update:noop,findAll:noop,remove:noop,load:noop,dump:noop,removeBatch:function(type,ids){for(var i=0;i<ids.length;i++){this.remove(type,ids[i])}},emit:function(evt){var args=[].slice.call(arguments,1)
if(!this._listeners[evt])return!1
for(var i=0;i<this._listeners[evt].length;i++){this._listeners[evt][i].apply(this,args)}},on:function(evt,handler){if(!this._listeners[evt]){this._listeners[evt]=[]}
this._listeners[evt].push(handler)},off:function(evt,handler){if(!this._listeners[evt])return!1
var i=this._listeners[evt].indexOf(handler)
if(i===-1)return!1
this._listeners[evt].splice(i,1)},}},{}],17:[function(_dereq_,module,exports){var Base=_dereq_('./base')
var uuid=_dereq_('../uuid')
module.exports=FirePL
var COLORS='#42b9bd #a405fa #7e6c93 #fee901 #a8ff99'.split(' ')
function randColor(){return COLORS[parseInt(Math.random()*COLORS.length)]}
function FirePL(options){Base.call(this)
this.db=new Firebase(options.url);this.data={}}
Base.extend(FirePL,{init:function(done){var id=uuid();this._userid=id
this.db.once('value',function(snapshot){this.data=snapshot.val()
var user=this.db.child('users').child(id)
user.set({selection:!1,color:randColor()})
user.onDisconnect().remove()
done()}.bind(this))
var users=this.db.child('users')
users.on('child_added',function(snapshot){var id=snapshot.name()
var user=snapshot.val()
if(id===this._userid)user.self=!0
this.emit('addActive',id,user)}.bind(this))
users.on('child_changed',function(snapshot){var id=snapshot.name()
var user=snapshot.val()
if(id===this._userid)user.self=!0
this.emit('changeActive',id,user)}.bind(this))
users.on('child_removed',function(snapshot){var id=snapshot.name()
var user=snapshot.val()
if(id===this._userid)user.self=!0
this.emit('removeActive',id,user)}.bind(this))},setPresence:function(selection){this.db.child('users').child(this._userid).update({selection:selection})},listen:function(type,onAdd,onChanged){this.db.child(type).on('child_changed',function(snapshot){var id=snapshot.name()
var data=snapshot.val()
this.data[type][id]=data
onChanged(id,data)}.bind(this))
this.db.child(type).on('child_added',function(snapshot){var id=snapshot.name()
var data=snapshot.val()
this.data[type][id]=data
onAdd(id,data)}.bind(this))},save:function(type,id,data,done){this.data[type][id]=data
this.db.child(type).child(id).set(data,done)},update:function(type,id,update,done){this.db.child(type).child(id).update(update,done)},findAll:function(type,done){this.db.child(type).once('value',function(snapshot){var items=[]
var val=snapshot.val()
for(var name in val){items.push(val[name])}
done(null,items)})},remove:function(type,id,done){this.db.child(type).child(id).remove(done)},load:function(data,done,clear){},dump:function(done){},})},{"../uuid":22,"./base":16}],18:[function(_dereq_,module,exports){var Base=_dereq_('./base')
module.exports=MemPL
function MemPL(){this.data={}}
Base.extend(MemPL,{init:function(done){done()},save:function(type,id,data,done){if(!this.data[type]){this.data[type]={}}
this.data[type][id]=data
done&&done()},update:function(type,id,update,done){for(var attr in update){this.data[type][id][attr]=update[attr]}
done&&done()},findAll:function(type,done){var items=[]
if(this.data[type]){for(var id in this.data[type]){items.push(this.data[type][id])}}
done(null,items)},remove:function(type,id,done){delete this.data[type][id]
done&&done()},load:function(data,done,clear){done&&done()},dump:function(done){done(null,{nodes:{}})}})},{"./base":16}],19:[function(_dereq_,module,exports){module.exports=function slideDown(node){var style=window.getComputedStyle(node),height=style.height;if(!parseInt(height)){return}
var speed=parseInt(height)/700
node.style.height=0
node.style.transition='height '+speed+'s ease'
node.style.overflow='hidden'
console.log(height)
setTimeout(function(){console.log('y',height)
node.style.height=height},0)
node.addEventListener('transitionend',fin)
function fin(){node.removeEventListener('transitionend',fin)
node.style.removeProperty('transition')
node.style.removeProperty('height')
node.style.removeProperty('overflow')}
$("#d3view").find(".popoverText").popover({placement:"bottom",html:"true",trigger:"hover",container:$("#d3view")})}},{}],20:[function(_dereq_,module,exports){module.exports=function slideUp(node,done){var style=window.getComputedStyle(node),height=style.height
if(!parseInt(height)){return}
node.style.height=height
var speed=parseInt(height)/700
node.style.transition='height '+speed+'s ease'
node.style.overflow='hidden'
setTimeout(function(){node.style.height=0},0)
node.addEventListener('transitionend',fin)
function fin(){node.removeEventListener('transitionend',fin)
node.style.removeProperty('transition')
node.style.removeProperty('height')
node.style.removeProperty('overflow')
done()}}},{}],21:[function(_dereq_,module,exports){module.exports={extend:extend,merge:merge,ensureInView:ensureInView,make_listed:make_listed,isMac:isMac,}
function isMac(){return window.navigator.platform.indexOf('Mac')===0}
function merge(a,b){var c={},d
for(d in a){c[d]=a[d]}
for(d in b){c[d]=b[d]}
return c}
function ensureInView(item){var bb=item.getBoundingClientRect()
if(bb.top<0)return item.scrollIntoView()
if(bb.bottom>window.innerHeight){item.scrollIntoView(!1)}}
function extend(dest){[].slice.call(arguments,1).forEach(function(src){for(var attr in src){dest[attr]=src[attr]}})
return dest}
function load(db,tree){var res=make_listed(tree,undefined,!0)
db.save('root',{id:res.id})
for(var i=0;i<res.tree.length;i++){db.save('node',res.tree[i])}}
function make_listed(data,nextid,collapse){var ids={},children=[],ndata={},res,i
if(undefined===nextid)nextid=100
if(data.children){for(i=0;i<data.children.length;i++){res=make_listed(data.children[i],nextid,collapse)
for(var id in res.tree){ids[id]=res.tree[id]
ids[id].depth+=1}
children.push(res.id)
nextid=res.id+1}}
for(var attr in data){if(attr==='children')continue;ndata[attr]=data[attr]}
ndata.done=!1
var theid=data.id||nextid
ids[theid]={id:theid,data:ndata,children:children,collapsed:!!collapse,depth:0}
for(i=0;i<children.length;i++){ids[children[i]].parent=theid}
return{id:theid,tree:ids}}},{}],22:[function(_dereq_,module,exports){module.exports=uuid
var CHARS='0123456789abcdefghijklmnopqrstuvwxyz'
function uuid(ln){ln=ln||32
var id=''
for(var i=0;i<ln;i++){id+=CHARS[parseInt(Math.random()*CHARS.length)]}
return id}},{}],23:[function(_dereq_,module,exports){var keyHandler=_dereq_('./key-handler'),normalActions=_dereq_('./normal-actions'),visualActions=_dereq_('./visual-actions'),util=_dereq_('./util')
module.exports=View
function eqlist(a,b){if(a==b)return!0
if(!a||!b)return!1
if(a.length!==b.length){return!1}
for(var i=0;i<a.length;i++){if(a[i]!==b[i])return!1}
return!0}
var DomViewLayer=_dereq_('./dom-vl'),DefaultNode=_dereq_('./default-node'),DungeonsAndDragons=_dereq_('./dnd'),keys=_dereq_('./keys'),util=_dereq_('./util'),defaultKeys=_dereq_('./default-keys')
function View(bindActions,model,actions,options){options=options||{}
this.mode='normal'
this.selection=null
this.sel_inverted=!1
this.active=null
this.o=util.extend({Node:DefaultNode,ViewLayer:DomViewLayer,noSelectRoot:!1,animate:!0},options)
this.o.keybindings=util.merge(this.getDefaultKeys(),options.keys)
this.vl=new this.o.ViewLayer(this.o)
this.bindActions=bindActions
this.model=model
this.ctrlactions=actions
this.modelActions=model.boundActions
this.dnd=new DungeonsAndDragons(this.vl,actions.move)
this.lazy_children={}
this._listeners={}
this.newNode=null
this.attachListeners()}
View.prototype={getNode:function(){return this.vl.root},emit:function(evt){var args=[].slice.call(arguments,1)
if(!this._listeners[evt])return!1
for(var i=0;i<this._listeners[evt].length;i++){this._listeners[evt][i].apply(this,args)}},on:function(evt,handler){if(!this._listeners[evt]){this._listeners[evt]=[]}
this._listeners[evt].push(handler)},off:function(evt,handler){if(!this._listeners[evt])return!1
var i=this._listeners[evt].indexOf(handler)
if(i===-1)return!1
this._listeners[evt].splice(i,1)},getDefaultKeys:function(){return util.merge(defaultKeys.view.base,defaultKeys.view[util.isMac()?'mac':'pc'])},rebase:function(newroot,trigger){this.vl.clear()
document.activeElement.blur()
if(!this.model.ids[newroot])newroot=this.model.root
var root=this.vl.root
this.initialize(newroot)
this.vl.rebase(root)
this.ctrlactions.trigger('rebase',newroot)},initialize:function(root){var node=this.model.ids[root],rootNode=this.vl.makeRoot(node,this.bindActions(root),this.modelActions)
this.active=null
this.selection=null
this.lazy_children={}
this.root=root
this.populateChildren(root)
if(!node.children.length){this.addNew(this.root,0)}else{this.removeNew()}
this.selectSomething()
return rootNode},startMoving:function(id){var targets=this.vl.dropTargets(this.root,this.model,id,!0)
this.dnd.startMoving(targets,id)},addNew:function(pid,index){this.newNode={pid:pid,index:index}
var before=this.model.getBefore(pid,index-1)
this.vl.addNew({id:'new',content:'',meta:{},parent:pid},this.bindActions('new'),this.modelActions,before)},removeNew:function(){if(!this.newNode)return!1
var nw=this.newNode,lastchild=!this.model.ids[nw.pid].children.length
this.vl.remove('new',nw.pid,lastchild)
this.newNode=null
return nw},selectSomething:function(){var child
if(!this.model.ids[this.root].children.length){child='new'}else{child=this.model.ids[this.root].children[0]}
this.goTo(child)},populateChildren:function(id,node){node=node||this.model.ids[id]
if(!node)return
if(node.collapsed&&id!==this.root){this.lazy_children[id]=!0
return}
this.lazy_children[id]=!1
this.vl.clearChildren(id)
if(!node.children||!node.children.length)return
for(var i=0;i<node.children.length;i++){this.add(this.model.ids[node.children[i]]||{id:node.children[i],parent:id,content:'',children:[]},!1,!0)
this.populateChildren(node.children[i])}},goTo:function(id){if(this.mode==='insert'){this.startEditing(id)}else{this.setActive(id)}},extra_actions:{},keyHandler:function(){var normal=keyHandler(defaultKeys.view.base,normalActions,this.ctrlactions)
if(this.extra_actions){for(var action in this.extra_actions){normal[this.extra_actions[action].binding]=this.extra_actions[action].action}}
var visual=keyHandler(defaultKeys.visual,visualActions,this.ctrlactions)
var handlers={'insert':function(){},'normal':keys(normal),'visual':keys(visual),}
return function(){return handlers[this.mode].apply(this,arguments)}.bind(this)},attachListeners:function(){var keydown=this.keyHandler()
window.addEventListener('keydown',function(e){if(e.target.nodeName==='INPUT')return
if(this.mode==='insert')return
keydown.call(this,e)}.bind(this))},addTree:function(node,before){if(!this.vl.body(node.parent)){return this.rebase(node.parent,!0)}
this.removeNew()
this.add(node,before)
if(!node.children||!node.children.length)return
for(var i=0;i<node.children.length;i++){this.addTree(this.model.ids[node.children[i]],!1)}},add:function(node,before,dontfocus){var ed=this.mode==='insert',children=node.children&&!!node.children.length
if(!this.vl.body(node.parent)){return this.rebase(node.parent,!0)}
this.vl.addNew(node,this.bindActions(node.id),this.modelActions,before,children)
if(!dontfocus){if(ed){this.vl.body(node.id).startEditing()}else{this.setActive(node.id)}}},update:function(id,node){var old=this.model.ids[id]||{}
console.log('update!',id,node,old)
var body=this.vl.body(id)
if(!body)return console.warn('no body for update')
if(node.content!==old.content){body.setContent(node.content)}
if(!eqlist(node.children,old.children)){this.populateChildren(id,node)}
body.setMeta(node.meta||{})
if(node.collapsed!==old.collapsed){this.setCollapsed(id,node.collapsed)}},remove:function(id,ignoreActive){var pid=this.model.ids[id].parent,parent=this.model.ids[pid]
if(!this.vl.body(id)){return this.rebase(pid,!0)}
if(id===this.active&&!ignoreActive){this.setActive(this.root)}
this.vl.remove(id,pid,parent&&parent.children.length===1)
if(parent.children.length===1){if(pid===this.root){setTimeout(function(){this.addNew(pid,0)}.bind(this),0)}}},setContent:function(id,content){if(!this.vl.body(id)){return this.rebase(id,!0)}
this.vl.body(id).setContent(content)
if(this.mode==='insert'){this.vl.body(id).startEditing()}},setAttr:function(id,attr,value,quiet){if(!this.vl.body(id)){if(quiet)return
return this.rebase(id,!0)}
this.vl.body(id).setAttr(attr,value)
if(this.mode==='insert'&&!quiet){this.vl.body(id).startEditing()}},replaceMeta:function(id,meta){this.vl.body(id).replaceMeta(meta)
if(this.mode==='insert'){this.vl.body(id).startEditing()}},appendText:function(id,text){this.vl.body(id).addEditText(text)},move:function(id,pid,before,ppid,lastchild){if(!this.vl.body(id)){return this.rebase(this.model.commonParent(pid,ppid),!0)}
var ed=this.mode==='insert'
this.vl.move(id,pid,before,ppid,lastchild)
if(ed)this.startEditing(id)},startEditing:function(id,fromStart){if(arguments.length===0){id=this.active!==null?this.active:this.root}
if(id===this.root&&this.o.noSelectRoot){return}
var body=this.vl.body(id)
if(!body)return
body.startEditing(fromStart)},stopEditing:function(){if(this.mode!=='insert')return
if(this.active===null)return
this.vl.body(this.active).stopEditing()},setEditing:function(id){if(this.mode==='visual'){this.stopSelecting()}
this.mode='insert'
this.setActive(id)},doneEditing:function(){this.mode='normal'},setActive:function(id){if(id===this.active)return this.vl.showActive(id)
if(this.active!==null){this.vl.clearActive(this.active)}
if(!this.vl.dom[id]){id=this.root}
this.active=id
this.vl.showActive(id)},getActive:function(){if(!this.vl.dom[this.active]){return this.root}
return this.active},addToSelection:function(id,invert){var ix=this.selection.indexOf(id)
if(ix===-1){this.selection.push(id)
this.vl.showSelection([id])
this.sel_inverted=invert}else{this.vl.clearSelection(this.selection.slice(ix+1))
this.selection=this.selection.slice(0,ix+1)
if(this.selection.length===1){this.sel_inverted=!1}}
this.setActive(id)
console.log(this.sel_inverted)},setSelection:function(sel){this.mode='visual'
this.sel_inverted=!1
if(this.selection){this.vl.clearSelection(this.selection)}
this.selection=sel
this.vl.showSelection(sel)},stopSelecting:function(){if(this.selection!==null){this.vl.clearSelection(this.selection)
this.selection=null}
this.mode='normal'},setCollapsed:function(id,what){if(what){if(this.mode==='insert'){this.startEditing(id)}else{this.setActive(id)}
if(this.o.animate){this.vl.animateClosed(id)}else{this.vl.setCollapsed(id,!0)}}else{if(this.lazy_children[id]){this.populateChildren(id)}
if(this.o.animate){this.vl.animateOpen(id)}else{this.vl.setCollapsed(id,!1)}}},goUp:function(id){var above=this.model.idAbove(id)
if(above===!1)return
if(above===this.root&&this.o.noSelectRoot){return}
this.vl.body(above).body.startEditing()},goDown:function(id,fromStart){var below=this.model.idBelow(id,this.root)
if(below===!1)return
this.vl.body(below).body.startEditing(fromStart)},}},{"./default-keys":6,"./default-node":7,"./dnd":8,"./dom-vl":9,"./key-handler":12,"./keys":13,"./normal-actions":15,"./util":21,"./visual-actions":24}],24:[function(_dereq_,module,exports){function reversed(items){var nw=[]
for(var i=items.length;i>0;i--){nw.push(items[i-1])}
return nw}
module.exports={'select up':{help:'move the cursor up',action:function(){var prev=this.model.prevSibling(this.active,!0)
if(!prev)return
this.addToSelection(prev,!0)},},'select down':{help:'move the cursor down',action:function(){var next=this.model.nextSibling(this.active,!0)
if(!next)return
this.addToSelection(next,!1)},},'select to bottom':{help:'move the cursor to the bottom',action:function(){var n=this.model.ids[this.selection[0]],ch=this.model.ids[n.parent].children,ix=ch.indexOf(this.selection[0])
this.setSelection(ch.slice(ix))
this.sel_inverted=!1
this.setActive(ch[ch.length-1])},},'select to top':{help:'move the cursor to the top',action:function(){var n=this.model.ids[this.selection[0]],ch=this.model.ids[n.parent].children,ix=ch.indexOf(this.selection[0]),items=[]
for(var i=0;i<=ix;i++){items.unshift(ch[i])}
this.setSelection(items)
this.sel_inverted=items.length>1
this.setActive(ch[0])},},'stop selecting':{help:'quit selection mode',action:function(){this.stopSelecting()},},'edit':{help:'start editing the active node',action:function(){this.startEditing(this.active)},},'edit start':{help:'edit at the start of the node',action:function(){this.startEditing(this.active,!0)},},'cut':{help:'cut the current selection',action:function(){var items=this.selection.slice()
if(this.sel_inverted){items=reversed(items)}
this.ctrlactions.cut(items)
this.stopSelecting()},},'copy':{help:'copy the current selection',action:function(){var items=this.selection.slice()
if(this.sel_inverted){items=reversed(items)}
this.ctrlactions.copy(items)
this.stopSelecting()},},'undo':{help:'undo the last change',action:function(){this.stopSelecting()
this.ctrlactions.undo()},},'redo':{help:'redo the last undo',action:function(){this.stopSelecting()
this.ctrlactions.redo()},},}},{}],25:[function(_dereq_,module,exports){module.exports=Block
function unEscapeHtml(str){if(!str)return '';return str.replace(/<div>/g,'\n').replace(/<br>/g,'\n').replace(/<\/div>/g,'').replace(/\u200b/g,'')}
function Block(data,children,config,options){this.o=options
this.editing=!1
this._moved=!1
this.setupNode(data,children)
this.reposition(config.left,config.top,!0)}
Block.prototype={setupNode:function(data,children){this.node=document.createElement('div')
this.node.className='whiteboard-item'
this.node.addEventListener('mouseup',this._onMouseUp.bind(this))
this.node.addEventListener('mousemove',this._onMouseMove.bind(this))
this.node.addEventListener('mousedown',this._onMouseDown.bind(this))
this.title=document.createElement('div')
this.title.className='whiteboard-item_title'
this.title.addEventListener('click',this._onClick.bind(this))
this.title.addEventListener('dblclick',this.o.onZoom)
this.input=document.createElement('div')
this.input.setAttribute('contenteditable',!0)
this.input.className='whiteboard-item_input'
this.input.addEventListener('blur',this._onBlur.bind(this))
this.body=document.createElement('ul')
this.body.className='whiteboard-item_body'
var zoom=document.createElement('div')
zoom.className='whiteboard-item_zoom'
zoom.innerHTML='<i class="fa fa-expand"/>'
zoom.addEventListener('click',this.o.onZoom)
this.children={}
children.forEach(function(child){var node=this.createChild(child)
this.body.appendChild(node)
this.children[child.id]=node}.bind(this))
this.node.appendChild(this.title)
this.node.appendChild(this.body)
this.node.appendChild(zoom)
this.setTextContent(data.content)
this.content=data.content
return this.node},remove:function(){this.node.parentNode.removeChild(this.node)
return!0},getChildTargets:function(cid,bid,children){var targets=children?children.map(this.childTarget.bind(this,bid)):[]
targets.push(this.wholeTarget(bid,children.length))
return targets},childTarget:function(pid,id,i){var box=this.children[id].getBoundingClientRect(),magic=10
return{hit:{left:box.left,right:box.right,top:box.top-magic,bottom:box.bottom-magic},pos:i,pid:pid,draw:{left:box.left,width:box.width,top:box.top-magic/2,height:magic}}},wholeTarget:function(id,last){var box=this.node.getBoundingClientRect(),magic=10
return{hit:box,pid:id,pos:last,draw:{top:box.bottom-magic,left:box.left+magic/2,height:magic,width:box.width-magic}}},updateConfig:function(config){this.reposition(config.left,config.top,!0)},setContent:function(content){if(content===this.content)return
this.setTextContent(content)
this.setInputValue(content)},_onBlur:function(e){this.stopEditing()
e.preventDefault()
return!1},_onMouseMove:function(e){if(e.target.classList.contains('handle')){return}
if(!e.shiftKey)return
var rect=this.node.getBoundingClientRect()
if(this.o.startMoving(e,rect,!0)){this.node.classList.add('whiteboard-item--moving')}},_onMouseUp:function(e){},_onClick:function(e){if(this._moved){this._moved=!1
return}
this.startEditing()
e.preventDefault()
return!1},_onMouseMoveChild:function(id,e){if(!e.shiftKey)return
e.preventDefault()
var clone=this.children[id].lastChild.cloneNode(!0)
if(this.o.startMovingChild(e,id,clone,!0)){this.children[id].classList.add('whiteboard-item_child--moving')}},_onMouseDownChild:function(id,e){e.stopPropagation()
e.preventDefault()
var clone=this.children[id].lastChild.cloneNode(!0)
if(this.o.startMovingChild(e,id,clone)){this.children[id].classList.add('whiteboard-item_child--moving')}},_onMouseDown:function(e){if(e.button!==0){return}
this._moved=!1
if(e.target!==this.input){e.preventDefault()
document.activeElement.blur()}
var rect=this.node.getBoundingClientRect()
this.node.classList.add('whiteboard-item--moving')
this.o.startMoving(e,rect)
return!1},removeChild:function(id){if(!this.children[id]){return!1}
this.children[id].parentNode.removeChild(this.children[id])
delete this.children[id]},addChild:function(child,id,before){var node=this.createChild(child)
if(before===!1){this.body.appendChild(node)}else{this.body.insertBefore(node,this.children[before])}
this.children[id]=node},createChild:function(child){var node=document.createElement('li')
node.className='whiteboard-item_child'
if(child.children&&child.children.length){node.classList.add('whiteboard-item_child--parent')}
var body=document.createElement('div')
body.innerHTML=child.content?marked(child.content):'<em>Click here to edit</em>'
var handle=document.createElement('div')
handle.className='handle'
handle.innerHTML='<i class="fa fa-circle"/>'
handle.addEventListener('mousemove',this._onMouseMoveChild.bind(this,child.id))
handle.addEventListener('mousedown',this._onMouseDownChild.bind(this,child.id))
node.appendChild(handle)
node.appendChild(body)
return node},doneMoving:function(){this.node.classList.remove('whiteboard-item--moving')},doneMovingChild:function(id){this.children[id].classList.remove('whiteboard-item_child--moving')},startEditing:function(fromStart){if(this.editing)return
this.node.classList.add('whiteboard-item--editing')
this.editing=!0;this.setInputValue(this.content)
this.node.replaceChild(this.input,this.title)
this.input.focus();this.setSelection(!fromStart)},stopEditing:function(){if(!this.editing)return
this.node.classList.remove('whiteboard-item--editing')
console.log('stop eddint',this.isNew)
var value=this.getInputValue()
this.editing=!1
this.node.replaceChild(this.title,this.input)
if(this.content!=value){this.setTextContent(value)
this.content=value
this.o.changeContent(this.content)}},setSelection:function(end){var sel=window.getSelection()
sel.selectAllChildren(this.input)
try{sel['collapseTo'+(end?'End':'Start')]()}catch(e){}},focus:function(){this.startEditing()},setTextContent:function(value){this.title.innerHTML=value?marked(value):''},setInputValue:function(value){this.input.innerHTML=value},getInputValue:function(){return unEscapeHtml(this.input.innerHTML)},reposition:function(x,y,silent){if(x!==this.x||y!==this.y){this._moved=!0}
this.x=x
this.y=y
this.node.style.top=y+'px'
this.node.style.left=x+'px'
if(!silent){this.saveConfig()}},resize:function(width,height,silent){this.width=width
this.height=height
this.node.style.width=width+'px'
this.node.style.height=height+'px'
if(!silent){this.saveConfig()}},saveConfig:function(){this.o.saveConfig({left:this.x,top:this.y,width:this.width,height:this.height})},saveContent:function(){this.o.saveContent(this.content)},mouseMove:function(e){},mouseUp:function(e){},click:function(e){this.startEditing()},blur:function(){this.stopEditing()},keyDown:function(e){}}},{}],26:[function(_dereq_,module,exports){module.exports={View:_dereq_('./view')}},{"./view":27}],27:[function(_dereq_,module,exports){var DungeonsAndDragons=_dereq_('../../lib/dnd.js')
var Block=_dereq_('./block')
module.exports=View
function View(bindActions,model,actions,options){this.mode='normal'
this.active=null
this.ids={}
this.bindActions=bindActions
this.model=model
this.ctrlactions=actions
this._boundMove=this._onMouseMove.bind(this)
this._boundUp=this._onMouseUp.bind(this)
document.addEventListener('keyup',this._onKeyUp.bind(this))}
View.prototype={initialize:function(root){var node=this.model.ids[root]
this.setupRoot()
this.root=root
this.makeBlocks(root)
return this.rootNode},setupRoot:function(){var rootNode=document.createElement('div')
rootNode.className='whiteboard'
rootNode.addEventListener('click',this._onClick.bind(this))
rootNode.addEventListener('mousedown',this._onMouseDown.bind(this))
rootNode.addEventListener('wheel',this._onWheel.bind(this))
this.head=document.createElement('div')
this.head.className='whiteboard-head'
this.head.addEventListener('click',this._onClickHead.bind(this))
this.input=document.createElement('input')
this.input.setAttribute('contenteditable',!0)
this.input.className='whiteboard-input-head'
this.input.addEventListener('blur',this._onBlurHead.bind(this))
this.container=document.createElement('div')
this.container.className='whiteboard-container'
this.controls=document.createElement('div')
this.controls.className='whiteboard-controls'
var b1=document.createElement('button')
b1.innerHTML='1:1'
b1.addEventListener('click',this.resetContainer.bind(this))
var b2=document.createElement('button')
b2.innerHTML='<i class="fa fa-th-large"/>'
b2.addEventListener('click',this.resetPositions.bind(this))
this.controls.appendChild(b1)
this.controls.appendChild(b2)
this.dropShadow=document.createElement('div')
this.dropShadow.className='whiteboard-dropshadow'
this.body=document.createElement('div')
this.body.appendChild(this.container)
this.body.className='whiteboard-body'
this.body.addEventListener('dblclick',this._onDoubleClick.bind(this))
this.vline=document.createElement('div')
this.vline.className='whiteboard_vline'
this.hline=document.createElement('div')
this.hline.className='whiteboard_hline'
this.body.appendChild(this.vline)
this.body.appendChild(this.hline)
this.body.appendChild(this.dropShadow)
this.body.appendChild(this.controls)
rootNode.appendChild(this.head)
rootNode.appendChild(this.body)
this.rootNode=rootNode
this.setContainerZoom(1)
this.setContainerPos(0,0)},getActive:function(){return this.root},addTree:function(node,before){if(node.parent!==this.root)return;this.makeBlock(node.id,0)},add:function(node,before,dontfocus){if(node.parent===this.root){var block=this.makeBlock(node.id,0)
block.node.style.zIndex=Object.keys(this.ids).length
if(!dontfocus){block.focus()}
return}
if(!this.ids[node.parent]){return}
this.ids[node.parent].addChild(node,this.model)},setCollapsed:function(){},startEditing:function(){},setActive:function(){},setSelection:function(){},move:function(id,pid,before,opid,lastchild){if(this.ids[opid]){this.ids[opid].removeChild(id)}else if(opid==this.root){this.ids[id].remove()
delete this.ids[id]}
if(this.ids[pid]){return this.ids[pid].addChild(this.model.ids[id],id,before)}
if(pid!==this.root){return}
this.add(this.model.ids[id],before)},remove:function(id){console.warn("FIX??")
this.container.removeChild(this.ids[id].node)
delete this.ids[id]},goTo:function(){console.warn('FIX!')},clear:function(){for(var id in this.ids){this.container.removeChild(this.ids[id].node)}
this.ids={}
this.setContainerPos(0,0)
this.setContainerZoom(1)},rebase:function(newroot,trigger){this.clear()
this.root=newroot
this.makeBlocks(newroot)
this.ctrlactions.trigger('rebase',newroot)},setAttr:function(id,attr,value){if(!this.ids[id]){return}
if(attr==='whiteboard'){if(!value||!value.top){var ch=this.model.ids[this.root].children,i=ch.indexOf(id),defaultWidth=300,defaultHeight=100,margin=10
value={top:10+parseInt(i/4)*(defaultHeight+margin),left:10+(i%4)*(defaultWidth+margin)}}
this.ids[id].updateConfig(value)}},setContent:function(id,content){if(!this.ids[id]){return}
this.ids[id].setContent(content)},setRootContent:function(content){this.head.innerHTML=marked(content)},makeBlocks:function(root){this.setRootContent(this.model.ids[root].content);var children=this.model.ids[root].children
if(!children)return
children.forEach(this.makeBlock.bind(this))},makeBlock:function(id,i){var node=this.model.ids[id],config=node.meta.whiteboard,defaultWidth=300,defaultHeight=100,margin=10
if(!config){config={top:10+parseInt(i/4)*(defaultHeight+margin),left:10+(i%4)*(defaultWidth+margin)}}
var children=(node.children||[]).map(function(id){return this.model.ids[id]}.bind(this));var block=new Block(node,children,config,{saveConfig:function(config){this.ctrlactions.changed(node.id,'whiteboard',config)}.bind(this),saveContent:function(content){this.ctrlactions.changeContent(node.id,content)}.bind(this),changeContent:function(content){this.ctrlactions.changeContent(node.id,content)}.bind(this),startMoving:this._onStartMoving.bind(this,node.id),startMovingChild:this._onStartMovingChild.bind(this,node.id),onZoom:function(){this.rebase(node.id)}.bind(this),})
this.ids[id]=block
this.container.appendChild(block.node)
return block},updateDropTarget:function(x,y){var t
for(var i=0;i<this.moving.targets.length;i++){t=this.moving.targets[i]
if(x>=t.hit.left&&x<=t.hit.right&&y>=t.hit.top&&y<=t.hit.bottom){this.moving.currentTarget=t
this.showDropShadow(t.draw)
return!0}}
this.moving.currentTarget=null
this.hideDropShadow()
return!1},findTargets:function(children,id,isChild){var targets=[],snaps=[],root=this.body.getBoundingClientRect()
for(var i=children.length-1;i>=0;i--){if(id==children[i])continue;var childids=this.model.ids[children[i]].children,child=this.ids[children[i]],whole=child.wholeTarget(id,childids.length)
targets=targets.concat(child.getChildTargets(id,children[i],childids))
targets.push(whole)
if(!isChild){snaps.push({top:whole.hit.top-root.top,left:whole.hit.left-root.left,right:whole.hit.right-root.left,bottom:whole.hit.bottom-root.top})}}
return{targets:targets,snaps:snaps}},trySnap:function(x,y){x=x*this._zoom+this.x
y=y*this._zoom+this.y
var h=this.moving.height,w=this.moving.width,b=y+h,r=x+w,allowance=20*this._zoom,space=10*this._zoom
if(allowance<2){return!1}
var lines=[],dx=!1,dy=!1
this.moving.snaps.forEach(function(snap){if(!dy){if(Math.abs(snap.top-space-b)<allowance){y=snap.top-space-h
dy=[snap.left,snap.right,snap.top-space/2]}else if(Math.abs(snap.top-y)<allowance){y=snap.top
dy=[snap.left,snap.right,snap.top-space/2]}else if(Math.abs(snap.bottom+space-y)<allowance){y=snap.bottom+space
dy=[snap.left,snap.right,snap.bottom+space/2]}else if(Math.abs(snap.bottom-b)<allowance){y=snap.bottom-h
dy=[snap.left,snap.right,snap.bottom+space/2]}}
if(!dx){if(Math.abs(snap.left-space-r)<allowance){x=snap.left-space-w
dx=[snap.top,snap.bottom,snap.left-space/2]}else if(Math.abs(snap.left-x)<allowance){x=snap.left
dx=[snap.top,snap.bottom,snap.left-space/2]}else if(Math.abs(snap.right+space-x)<allowance){x=snap.right+space
dx=[snap.top,snap.bottom,snap.right+space/2]}else if(Math.abs(snap.right-r)<allowance){x=snap.right-w
dx=[snap.top,snap.bottom,snap.right+space/2]}}})
if(dx){var ht=Math.min(dx[0],y),hb=Math.max(dx[1],y+h)
this.vline.style.left=dx[2]-1+'px'
this.vline.style.top=ht-space/2+'px'
this.vline.style.height=(hb-ht)+space+'px'
this.vline.style.display='block'}else{this.vline.style.display='none'}
if(dy){var vl=Math.min(dy[0],x),vr=Math.max(dy[1],x+w)
this.hline.style.top=dy[2]-1+'px'
this.hline.style.left=vl-space/2+'px'
this.hline.style.width=(vr-vl)+space+'px'
this.hline.style.display='block'}else{this.hline.style.display='none'}
if(dx||dy){return{x:(x-this.x)/this._zoom,y:(y-this.y)/this._zoom}}
return!1},getByZIndex:function(){var items=[];for(var id in this.ids){items.push([+this.ids[id].node.style.zIndex,id])}
items.sort(function(a,b){return a[0]-b[0]})
return items.map(function(item){return item[1]})},shuffleZIndices:function(top){var items=this.getByZIndex()
for(var i=0;i<items.length;i++){this.ids[items[i]].node.style.zIndex=i}
this.ids[top].node.style.zIndex=items.length
return items},_onClickHead:function(e){e.preventDefault()
this.startEditing()},_onBlurHead:function(e){e.preventDefault()
this.stopEditing()},startEditing:function(){this.input.value=this.model.ids[this.root].content
this.rootNode.replaceChild(this.input,this.head)
this.input.focus()
this.input.selectionStart=this.input.selectionEnd=this.input.value.length},stopEditing:function(){this.ctrlactions.changeContent(this.root,this.input.value)
this.setRootContent(this.input.value)
this.rootNode.replaceChild(this.head,this.input)},_onClick:function(e){if(e.target===this.rootNode){document.activeElement.blur()}},_onDoubleClick:function(e){if(e.target!==this.body){return}
var box=this.container.getBoundingClientRect()
var x=e.clientX-50-box.left,y=e.clientY-10-box.top,idx=this.model.ids[this.root].children.length
this.ctrlactions.addChild(this.root,idx,'',{whiteboard:{top:y,left:x}})},_onWheel:function(e){e.preventDefault()
if(this.moving){return}
var x,y
var deltaX=-e.deltaX,deltaY=-e.deltaY
if(e.shiftKey){var root=this.body.getBoundingClientRect()
x=e.clientX-root.left
y=e.clientY-root.top
this.zoomMove((deltaY/500),x,y)
return}
x=this.x
y=this.y
this.setContainerPos(x+deltaX,y+deltaY)},_onMouseDown:function(e){if(e.target!==this.rootNode){return}
var box=this.container.getBoundingClientRect()
var x=e.clientX-box.left,y=e.clientY-box.top
this.moving={x:x,y:y,}
e.preventDefault()
document.addEventListener('mousemove',this._boundMove)
document.addEventListener('mouseup',this._boundUp)},_onStartMoving:function(id,e,rect,shiftMove){if(this.moving)return!1;var y=e.clientY/this._zoom-rect.top/this._zoom,x=e.clientX/this._zoom-rect.left/this._zoom
var children=this.shuffleZIndices(id)
var boxes=this.findTargets(children,id)
this.moving={shift:shiftMove,targets:boxes.targets,snaps:boxes.snaps,width:rect.width,height:rect.height,atx:this.ids[id].x,aty:this.ids[id].y,id:id,x:x,y:y,}
document.addEventListener('mousemove',this._boundMove)
document.addEventListener('mouseup',this._boundUp)
this.rootNode.classList.add('whiteboard--moving')
return!0},_onStartMovingChild:function(id,e,cid,handle,shiftMove){if(this.moving)return!1;var box=this.container.getBoundingClientRect()
var x=e.clientX/this._zoom-box.left/this._zoom,y=e.clientY/this._zoom-box.top/this._zoom
var children=this.getByZIndex()
var boxes=this.findTargets(children,cid,!0)
this.moving={shift:shiftMove,targets:boxes.targets,snaps:boxes.snaps,handle:handle,child:cid,parent_id:id,oty:x,otx:y,x:x,y:y}
this.container.appendChild(handle)
this.updateDropTarget(e.clientX,e.clientY)
handle.className='whiteboard_child-handle'
handle.style.top=y+'px'
handle.style.left=x+'px'
document.addEventListener('mousemove',this._boundMove)
document.addEventListener('mouseup',this._boundUp)
this.rootNode.classList.add('whiteboard--moving')
return!0},_onKeyUp:function(e){if(e.keyCode===16&&this.moving&&this.moving.shift){this.stopMoving()}},_onMouseMove:function(e){if(!this.moving){return this._onMouseUp(e)}
e.preventDefault()
if(this.moving.child){var box=this.container.getBoundingClientRect()
var x=e.clientX/this._zoom-box.left/this._zoom,y=e.clientY/this._zoom-box.top/this._zoom
this.moving.handle.style.top=y+'px'
this.moving.handle.style.left=x+'px'
this.moving.x=x
this.moving.y=y
this.updateDropTarget(e.clientX,e.clientY)
return!1}
if(this.moving.id){var box=this.container.getBoundingClientRect()
var x=e.clientX/this._zoom-box.left/this._zoom-this.moving.x,y=e.clientY/this._zoom-box.top/this._zoom-this.moving.y
if(!this.updateDropTarget(e.clientX,e.clientY)){var pos=this.trySnap(x,y)
if(pos){x=pos.x
y=pos.y}}
this.moving.atx=x
this.moving.aty=y
this.ids[this.moving.id].reposition(x,y,!0)
return!1}
var box=this.body.getBoundingClientRect()
var x=e.clientX-box.left-this.moving.x,y=e.clientY-box.top-this.moving.y
this.setContainerPos(x,y)
return!1},_onMouseUp:function(e){e.preventDefault()
this.stopMoving()
return!1},resetContainer:function(){this.setContainerPos(0,0)
this.setContainerZoom(1)},resetPositions:function(){var cmds=[]
this.model.ids[this.root].children.forEach(function(id){cmds.push('changeNodeAttr')
cmds.push([id,'whiteboard',null])});this.ctrlactions.commands(cmds)},zoomMove:function(delta,x,y){var next=this._zoom*delta,nz=this._zoom+next,scale=this._zoom/nz,nx=x-x/scale,ny=y-y/scale
this.setContainerPos(this.x/scale+nx,this.y/scale+ny)
this.setContainerZoom(nz)},setContainerZoom:function(num){this._zoom=num
this.container.style.WebkitTransform='scale('+num+')'
this.container.style.transform='scale('+num+')'},setContainerPos:function(x,y){this.x=x
this.y=y
this.container.style.left=x+'px'
this.container.style.top=y+'px'},stopMovingChild:function(){this.moving.handle.parentNode.removeChild(this.moving.handle)
var pos=this.model.ids[this.root].children.length
if(this.moving.currentTarget){var pos=this.moving.currentTarget.pos
if(this.moving.currentTarget.pid==this.moving.parent_id){if(pos>this.model.ids[this.moving.parent_id].children.indexOf(this.moving.child)){pos-=1}}
this.ctrlactions.commands('move',[this.moving.child,this.moving.currentTarget.pid,pos],'changeNodeAttr',[this.moving.child,'whiteboard',null])}else{this.ctrlactions.commands('changeNodeAttr',[this.moving.child,'whiteboard',{top:this.moving.y,left:this.moving.x}],'move',[this.moving.child,this.root,pos])}
this.ids[this.moving.parent_id].doneMoving()},showDropShadow:function(rect){var box=this.body.getBoundingClientRect(),realheight=rect.height*this._zoom,yoff=(rect.height-realheight)/2
this.dropShadow.style.top=rect.top-box.top+yoff+'px'
this.dropShadow.style.left=rect.left-box.left+'px'
this.dropShadow.style.width=rect.width+'px'
this.dropShadow.style.height=realheight+'px'
this.dropShadow.style.display='block'},hideDropShadow:function(){this.dropShadow.style.display='none'},stopMovingMain:function(){this.ids[this.moving.id].reposition(this.moving.atx,this.moving.aty)
this.ids[this.moving.id].doneMoving()
if(this.moving.currentTarget){this.ctrlactions.commands('move',[this.moving.id,this.moving.currentTarget.pid,this.moving.currentTarget.pos],'changeNodeAttr',[this.moving.id,'whiteboard',null])}},stopMoving:function(){if(this.moving.child){this.stopMovingChild()}else if(this.moving.id){this.stopMovingMain()}
if(this.moving.currentTarget){this.hideDropShadow()}
this.moving=null
document.removeEventListener('mousemove',this._boundMove)
document.removeEventListener('mouseup',this._boundUp)
this.vline.style.display='none'
this.hline.style.display='none'
this.rootNode.classList.remove('whiteboard--moving')},getNode:function(){return this.rootNode}}},{"../../lib/dnd.js":8,"./block":25}],28:[function(_dereq_,module,exports){module.exports={addTag:{args:['name'],apply:function(view,model){if(!model.hasTagRoot()){var cr=model.addTagRoot()
this.tagRoot=view.add(cr.node,cr.before,!0)}
var nr=model.addTag(this.name)
view.add(nr.node,nr.before,!0)
this.node=nr.node
return this.node},undo:function(view,model){model.remove(this.node.id)
if(this.tagRoot){model.removeTagRoot()
view.remove(this.tagRoot.node.id)}}},setTags:{args:['id','tags'],apply:function(view,model){this.oldTags=model.setTags(this.id,this.tags)
view.setTags(this.id,this.tags,this.oldTags)},undo:function(view,model){model.setTags(this.id,this.oldTags)
view.setTags(this.id,this.oldTags,this.tags)},},}},{}],29:[function(_dereq_,module,exports){var Controller=_dereq_('../../lib/controller'),util=_dereq_('../../lib/util'),WFNode=_dereq_('./node'),WFView=_dereq_('./view'),WFVL=_dereq_('./vl'),commands=_dereq_('./commands')
module.exports=WFController
function WFController(model,options){options.extra_commands=util.extend(options.extra_commands||{},commands)
Controller.call(this,model,options)
this.on('rebase',function(id){this.trigger('bullet',this.model.getLineage(id))}.bind(this))}
WFController.prototype=util.extend(Object.create(Controller.prototype),{refreshBullet:function(){this.trigger('bullet',this.model.getLineage(this.model.root))}})
WFController.prototype.actions=util.extend({clickBullet:function(id){if(id==='new')return
this.view.rebase(id)},rebase:function(id,toid){this.view.rebase(toid)},backALevel:function(){var root=this.view.root,pid=this.model.ids[root].parent
if(!this.model.ids[pid])return
this.actions.clickBullet(pid)},setTags:function(id,ids){this.executeCommands('setTags',[id,ids])},addTag:function(id,contents){return this.executeCommands('addTag',[contents])[0]},},Controller.prototype.actions)},{"../../lib/controller":5,"../../lib/util":21,"./commands":28,"./node":32,"./view":34,"./vl":35}],30:[function(_dereq_,module,exports){module.exports={Controller:_dereq_('./controller'),Model:_dereq_('./model'),Node:_dereq_('./node'),View:_dereq_('./view'),ViewLayer:_dereq_('./vl'),}},{"./controller":29,"./model":31,"./node":32,"./view":34,"./vl":35}],31:[function(_dereq_,module,exports){var Model=_dereq_('../../lib/model')
module.exports=WFModel
function WFModel(){Model.apply(this,arguments)}
WFModel.prototype=Object.create(Model.prototype)
WFModel.prototype.actions={resolveTags:function(tags){if(!tags)return[]
return tags.map(function(id){return this.ids[id]}.bind(this))},getAllTags:function(){var tags=[]
for(var id in this.ids){tags.push(this.ids[id])}
return tags}}
WFModel.prototype.hasTagRoot=function(){return!!this.rootNode.tagRoot}
WFModel.prototype.addTagRoot=function(){var index=this.ids[this.root].children?this.ids[this.root].children.length:0
var cr=model.create(this.root,index,'Tags')
this.rootNode.tagRoot=cr.node.id
this.db.update('root',this.root,{tagRoot:cr.node.id})
return cr}
WFModel.prototype.addTag=function(name){var tagRoot=this.rootNode.tagRoot
var index=this.ids[tagRoot].children?this.ids[tagRoot].children.length:0
var cr=model.create(tagRoot,index,name)
return cr}
WFModel.prototype.readd=function(saved){this.ids[saved.id]=saved.node
var children=this.ids[saved.node.parent].children
children.splice(saved.ix,0,saved.id)
var before=!1
if(saved.ix<children.length-1){before=children[saved.ix+1]}
var upRefs={}
var upTags={}
var ids=this.ids
function process(node){for(var i=0;i<node.children.length;i++){process(ids[node.children[i]])}
if(node.meta.tags){node.meta.tags.forEach(function(id){var refs=ids[id].meta.references
if(!refs){refs=ids[id].meta.references=[]}
if(refs.indexOf(node.id)!==-1)return console.warn('duplicate ref on readd')
refs.push(node.id)
upRefs[id]=!0})}
if(node.meta.references){node.meta.references.forEach(function(id){ids[id].meta.tags.push(node.id)
var tags=ids[id].meta.tags
if(!tags){tags=ids[id].meta.tags=[]}
if(tags.indexOf(node.id)!==-1)return console.warn('duplicate tag on readd')
tags.push(node.id)
upTags[id]=!0})}}
process(this.ids[saved.id])
this.db.save('node',saved.node.id,saved.node)
this.db.update('node',saved.node.parent,{children:children})
for(id in upTags){this.db.update('node',id,{tags:this.ids[id].tags})}
for(id in upRefs){this.db.update('node',id,{references:this.ids[id].references})}
return before}
WFModel.prototype.dumpData=function(id,noids){var data=Model.prototype.dumpData.call(this,id,noids)
if(!noids)return data
delete data.meta.references
delete data.meta.tags
return data}
WFModel.prototype.remove=function(id){if(id===this.root)return
var n=this.ids[id],p=this.ids[n.parent],ix=p.children.indexOf(id)
var upRefs={}
var upTags={}
var ids=this.ids
var removed=[]
function process(node){if(node.meta.tags){node.meta.tags.forEach(function(id){var refs=ids[id].meta.references
upRefs[id]=!0
refs.splice(refs.indexOf(node.id),1)})}
if(node.meta.references){node.meta.references.forEach(function(id){var tags=ids[id].meta.tags
upTags[id]=!0
tags.splice(tags.indexOf(node.id),1)})}
for(var i=0;i<node.children.length;i++){process(ids[node.children[i]])}
delete ids[node.id]
removed.push(node.id)}
process(n)
p.children.splice(ix,1)
delete this.ids[id]
setTimeout(function(){this.db.removeBatch('node',removed)
this.db.update('node',n.parent,{children:p.children})
if(id===this.rootNode.tagRoot){delete this.rootNode.tagRoot
this.db.update('root',this.root,{tagRoot:null})}
for(id in upTags){if(this.ids[id]){this.db.update('node',id,{tags:this.ids[id].meta.tags})}}
for(id in upRefs){if(this.ids[id]){this.db.update('node',id,{references:this.ids[id].meta.references})}}}.bind(this))
return{id:id,node:n,ix:ix}}
WFModel.prototype.setTags=function(id,tags){var old=this.ids[id].meta.tags
var used={}
if(old)old=old.slice()
if(tags){for(var i=0;i<tags.length;i++){used[tags[i]]=!0
var refs=this.ids[tags[i]].meta.references
if(!refs){refs=this.ids[tags[i]].meta.references=[]}
if(refs.indexOf(id)===-1){refs.push(id)}}}
if(old){for(var i=0;i<old.length;i++){if(used[old[i]])continue;var refs=this.ids[old[i]].meta.references
refs.splice(refs.indexOf(id),1)
used[old[i]]=!0}}
this.ids[id].meta.tags=tags
this.db.update(id,{meta:this.ids[id].meta})
for(var oid in used){this.db.update(oid,{meta:this.ids[oid].meta})}
return old}
WFModel.prototype.getLineage=function(id){var lineage=[]
while(this.ids[id]){lineage.unshift({content:this.ids[id].content,id:id})
id=this.ids[id].parent}
return lineage}
WFModel.prototype.search=function(text){var items=[],frontier=[this.root]
text=text.toLowerCase()
while(frontier.length){var next=[]
for(var i=0;i<frontier.length;i++){var content=this.ids[frontier[i]].content
if(content&&content.toLowerCase().indexOf(text)!==-1){items.push({id:frontier[i],text:this.ids[frontier[i]].content})}
var children=this.ids[frontier[i]].children
if(children){next=next.concat(children)}}
frontier=next}
return items}},{"../../lib/model":14}],32:[function(_dereq_,module,exports){var DefaultNode=_dereq_('../../lib/default-node')
var Tags=_dereq_('./tags')
module.exports=WFNode
function WFNode(content,meta,actions,isNew,modelActions){DefaultNode.call(this,content,meta,actions,isNew,modelActions)
this.done=meta.done
this.tags=new Tags(modelActions.resolveTags(meta.tags),actions,modelActions)
this.node.appendChild(this.tags.node)
if(meta.done){this.node.classList.add('treed__default-node--done')}}
WFNode.prototype=Object.create(DefaultNode.prototype)
WFNode.prototype.constructor=WFNode
WFNode.prototype.setAttr=function(attr,value){if(attr==='tags'){return this.setTags(value)}
if(attr==='done'){return this.setDone(value)}
DefaultNode.prototype.setAttr.call(this,attr,value)}
WFNode.prototype.addTag=function(node){this.tags.add(node)}
WFNode.prototype.removeTag=function(tid){this.tags.removeFull(tid)}
WFNode.prototype.setTags=function(tags){this.tags.set(this.modelActions.resolveTags(tags))}
WFNode.prototype.setDone=function(isDone){this.done=isDone
if(isDone){this.node.classList.add('treed__default-node--done')}else{this.node.classList.remove('treed__default-node--done')}}
WFNode.prototype.extra_actions={'rebase':{binding:'alt+return',action:function(){this.o.clickBullet()}},'back a level':{binding:'shift+alt+return',action:function(){this.o.backALevel()}},'toggle done':{binding:'ctrl+return',action:function(){this.blur()
this.o.changed('done',!this.done)
this.focus()
if(this.done){this.o.goDown()}}}}},{"../../lib/default-node":7,"./tags":33}],33:[function(_dereq_,module,exports){module.exports=Tags
function Tags(tags,actions,modelactions){this.actions=actions
this.modelactions=modelactions
this.setupNode()
this.set(tags)}
Tags.prototype={setupNode:function(){this.node=document.createElement('div')
this.node.className='treed_tags'
this.handle=document.createElement('div')
this.handle.className='treed_tags_handle'
this.handle.addEventListener('click',this.startEditing.bind(this))
this.handle.innerHTML='<i class="fa fa-tag"/>'
this.tags=document.createElement('div')
this.tags.className='treed_tags_list'
this.editor=document.createElement('div')
this.editor.className='treed_tags_editor'
this.input=document.createElement('input')
this.input.className='treed_tags_input'
this.input.addEventListener('keydown',this.keyDown.bind(this))
this.input.addEventListener('keyup',this.keyUp.bind(this))
this.input.addEventListener('blur',this.onBlur.bind(this))
this.resultsNode=document.createElement('ul')
this.resultsNode.className='treed_tags_results'
this.tags.addEventListener('mousedown',function(e){e.preventDefault()})
this.resultsNode.addEventListener('mousedown',function(e){e.preventDefault()})
this.newNode=document.createElement('div')
this.newNode.className='treed_tags_new'
this.newNode.addEventListener('mousedown',function(e){e.preventDefault()})
this.newNode.addEventListener('click',this.onNew.bind(this))
this.newNode.innerText='Create new tag'
this.node.appendChild(this.tags)
this.node.appendChild(this.handle)
this.node.appendChild(this.editor)
this.editor.appendChild(this.input)
this.editor.appendChild(this.resultsNode)
this.editor.appendChild(this.newNode)
this.dom={}},startEditing:function(e){if(this.editing)return
this.actions.setActive()
this.editing=!0
this.node.classList.add('treed_tags--open')
this.fullResults=this.modelactions.getAllTags()
this.filterBy('')
this.selection=0
this.input.value=''
this.input.focus()
this.showResults()},doneEditing:function(e){if(!this.editing)return
this.editing=!1
this.node.classList.remove('treed_tags--open')
this.actions.setTags(this.value.map(function(x){return x.id}))},onBlur:function(){this.doneEditing()},keys:{27:function(e){e.preventDefault()
this.doneEditing()},9:function(e){e.preventDefault()
this.addCurrent()},13:function(e){e.preventDefault()
this.addCurrent()
this.doneEditing()},8:function(e){if(!this.input.value){e.preventDefault()
this.removeLast()}},},keyDown:function(e){var action=this.keys[e.keyCode]
if(action)return action.call(this,e)},keyUp:function(e){this.filterBy(this.input.value)
this.showResults()},filterBy:function(needle){var used={}
for(var i=0;i<this.value.length;i++){used[this.value[i].id]=!0}
if(!needle){this.results=this.fullResults.filter(function(tag){return!used[tag.id]})}else{needle=needle.toLowerCase()
this.results=this.fullResults.filter(function(tag){return!used[tag.id]&&tag.content.toLowerCase().indexOf(needle)!==-1})}},showResults:function(){while(this.resultsNode.lastChild){this.resultsNode.removeChild(this.resultsNode.lastChild)}
var num=5
if(num>this.results.length)num=this.results.length
var click=function(tag,e){e.preventDefault()
this.addCurrent(tag)}
for(var i=0;i<num;i++){var node=document.createElement('li')
node.innerText=this.results[i].content
node.className='treed_tags_result'
node.addEventListener('click',click.bind(this,this.results[i]))
this.resultsNode.appendChild(node)}},onNew:function(){if(!this.input.value.length)return
var tag=this.actions.addTag(this.input.value)
this.addCurrent(tag)},addCurrent:function(tag){if(!tag){if(!this.input.value.length)return
if(!this.results.length){tag=this.actions.addTag(this.input.value)}else{tag=this.results[this.selection]}}
if(this.value.indexOf(tag)!==-1)return this.resetSearch()
this.value.push(tag)
this.add(tag)
this.resetSearch()},resetSearch:function(){this.input.value=''
this.filterBy('')
this.selection=0
this.showResults()},removeLast:function(){if(!this.value.length)return
var last=this.value.pop()
this.remove(last.id)
this.resetSearch()},remove:function(id){this.tags.removeChild(this.dom[id])
delete this.dom[id]},removeFull:function(id){for(var i=0;i<this.value.length;i++){if(this.value[i].id===id){this.value.splice(i,1)
this.remove(id)
this.resetSearch()
return}}},set:function(tags){this.value=tags||[]
while(this.tags.lastChild)this.tags.removeChild(this.tags.lastChild)
this.dom={}
this.value.map(this.add.bind(this))},add:function(tag){if(this.dom[tag.id])return console.warn('tried to add duplicate tag')
var node=document.createElement('div')
this.dom[tag.id]=node
node.className='treed_tag'
var content=document.createElement('span')
content.innerText=tag.content
node.appendChild(content)
var remove=document.createElement('span')
remove.className='treed_tag_remove'
remove.innerHTML=' &times;'
var rmFunc=this.removeFull.bind(this,tag.id)
remove.addEventListener('click',function(e){e.preventDefault()
e.stopPropagation()
rmFunc()})
node.appendChild(remove)
node.addEventListener('click',function(e){e.preventDefault()
e.stopPropagation()
if(this.editing)return
this.actions.rebase(tag.id)}.bind(this))
this.tags.appendChild(node)},}},{}],34:[function(_dereq_,module,exports){var View=_dereq_('../../lib/view')
module.exports=WFView
function WFView(){View.apply(this,arguments)}
WFView.prototype=Object.create(View.prototype)
WFView.prototype.initialize=function(root){var rootNode=View.prototype.initialize.call(this,root),node=this.model.ids[root]
if(node.meta.references){this.vl.setReferences(node.meta.references.map(function(id){return this.model.ids[id]}.bind(this)),this.rebase.bind(this))}
return rootNode}
WFView.prototype.addTree=function(node,before){if(!this.vl.body(node.parent)){return this.rebase(node.parent,!0)}
this.add(node,before)
if(node.meta.tags){node.meta.tags.forEach(function(id){if(id===this.root){this.vl.addReference(this.model.ids[node.id],this.rebase.bind(this,node.id))}}.bind(this))}
if(node.meta.references){node.meta.references.forEach(function(id){this.vl.addTag(id,node)}.bind(this))}
if(!node.children||!node.children.length)return
for(var i=0;i<node.children.length;i++){this.addTree(this.model.ids[node.children[i]],!1)}}
WFView.prototype.remove=function(id,ignoreActive){var node=this.model.ids[id],pid=node.parent,parent=this.model.ids[pid]
if(!this.vl.body(id)){return this.rebase(pid,!0)}
if(id===this.active&&!ignoreActive){this.setActive(this.root)}
this.vl.remove(id,pid,parent&&parent.children.length===1)
if(parent.children.length===1){if(pid===this.root){setTimeout(function(){this.addNew(pid,0)}.bind(this),0)}}
var ids=this.model.ids
function process(node){for(var i=0;i<node.children.length;i++){process.call(this,ids[node.children[i]])}
if(node.meta.references){node.meta.references.forEach(function(rid){this.vl.removeTag(rid,node.id)}.bind(this))}
if(node.meta.tags){node.meta.tags.forEach(function(tid){this.vl.removeReference(tid,node.id)}.bind(this))}}
process.call(this,node)}
WFView.prototype.setAttr=function(id,attr,value,quiet){var res=View.prototype.setAttr.apply(this,arguments)
if(attr!=='references')return res
if(id!==this.root)return
this.vl.setReferences(value&&value.map(function(id){return this.model.ids[id]}.bind(this)),this.rebase.bind(this))
return res}
WFView.prototype.setTags=function(id,tags,oldTags){var used={}
for(var i=0;i<tags.length;i++){used[tags[i]]=!0}
this.setAttr(id,'tags',tags)
for(var i=0;i<tags.length;i++){this.setAttr(tags[i],'references',this.model.ids[tags[i]].meta.references,!0)}
if(oldTags){for(var i=0;i<oldTags.length;i++){if(used[oldTags[i]])continue;this.setAttr(oldTags[i],'references',this.model.ids[oldTags[i]].meta.references,!0)}}}
WFView.prototype.extra_actions={'edit tags':{binding:'shift+3',action:function(){this.vl.editTags(this.active)},},'rebase':{binding:'alt+return',action:function(){this.ctrlactions.clickBullet(this.active)}},'back a level':{binding:'shift+alt+return',action:function(){this.ctrlactions.backALevel()}},'toggle done':{binding:'ctrl+return',action:function(){if(this.active===null)return
var id=this.active,done=!this.model.ids[id].meta.done,next=this.model.idBelow(id,this.root)
if(next===undefined)next=id
this.ctrlactions.changed(this.active,'done',done)
if(done){this.goTo(next)}}}}},{"../../lib/view":23}],35:[function(_dereq_,module,exports){var DomViewLayer=_dereq_('../../lib/dom-vl')
module.exports=WFVL
function WFVL(){DomViewLayer.apply(this,arguments)}
WFVL.prototype=Object.create(DomViewLayer.prototype)
WFVL.prototype.removeTag=function(id,tid){var body=this.body(id)
if(!body)return
body.removeTag(tid)}
WFVL.prototype.addTag=function(id,node){var body=this.body(id)
if(!body)return
body.addTag(node)}
WFVL.prototype.editTags=function(id){this.body(id).tags.startEditing()}
WFVL.prototype.makeHead=function(body,actions){var head=DomViewLayer.prototype.makeHead.call(this,body,actions),bullet=document.createElement('div')
bullet.classList.add('treed__bullet')
bullet.addEventListener('mousedown',actions.clickBullet)
head.insertBefore(bullet,head.childNodes[1])
return head}
WFVL.prototype.makeRoot=function(node,bounds,modelActions){var root=DomViewLayer.prototype.makeRoot.call(this,node,bounds,modelActions)
var refContainer=document.createElement('div')
refContainer.className='treed_references'
refContainer.innerHTML='<h1 class="treed_references_title">References</h1>'
this.references=document.createElement('div')
this.references.className='treed_references_list'
this.rfs={}
refContainer.appendChild(this.references)
root.appendChild(refContainer)
this.refContainer=refContainer
return root}
WFVL.prototype.setReferences=function(nodes,action){this.clearReferences()
if(!nodes||!nodes.length){this.refContainer.classList.remove('treed_references--shown')
return}
this.refContainer.classList.add('treed_references--shown')
nodes.forEach(function(node){this.addReference(node,action.bind(null,node.id))}.bind(this))}
WFVL.prototype.clearReferences=function(){while(this.references.lastChild){this.references.removeChild(this.references.lastChild)}
this.rfs={}}
WFVL.prototype.addReference=function(node,action){this.refContainer.classList.add('treed_references--shown')
var div=document.createElement('div')
div.className='treed_reference'
div.innerHTML=marked(node.content)
div.addEventListener('click',action)
this.rfs[node.id]=div
this.references.appendChild(div)}
WFVL.prototype.removeReference=function(id,rid){}},{"../../lib/dom-vl":9}]},{},[1])(1)})