(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror"],mod);else mod(CodeMirror)})(function(CodeMirror){"use strict";CodeMirror.defineMode("groovy",function(config){function words(str){var obj={},words=str.split(" ");for(var i=0;i<words.length;++i)obj[words[i]]=!0;return obj}
var keywords=words("abstract as assert boolean break byte case catch char class const continue def default "+"do double else enum extends final finally float for goto if implements import in "+"instanceof int interface long native new package private protected public return "+"short static strictfp super switch synchronized threadsafe throw throws transient "+"try void volatile while");var blockKeywords=words("catch class do else finally for if switch try while enum interface def");var atoms=words("null true false this");var curPunc;function tokenBase(stream,state){var ch=stream.next();if(ch=='"'||ch=="'"){return startString(ch,stream,state)}
if(/[\[\]{}\(\),;\:\.]/.test(ch)){curPunc=ch;return null}
if(/\d/.test(ch)){stream.eatWhile(/[\w\.]/);if(stream.eat(/eE/)){stream.eat(/\+\-/);stream.eatWhile(/\d/)}
return "number"}
if(ch=="/"){if(stream.eat("*")){state.tokenize.push(tokenComment);return tokenComment(stream,state)}
if(stream.eat("/")){stream.skipToEnd();return "comment"}
if(expectExpression(state.lastToken)){return startString(ch,stream,state)}}
if(ch=="-"&&stream.eat(">")){curPunc="->";return null}
if(/[+\-*&%=<>!?|\/~]/.test(ch)){stream.eatWhile(/[+\-*&%=<>|~]/);return "operator"}
stream.eatWhile(/[\w\$_]/);if(ch=="@"){stream.eatWhile(/[\w\$_\.]/);return "meta"}
if(state.lastToken==".")return "property";if(stream.eat(":")){curPunc="proplabel";return "property"}
var cur=stream.current();if(atoms.propertyIsEnumerable(cur)){return "atom"}
if(keywords.propertyIsEnumerable(cur)){if(blockKeywords.propertyIsEnumerable(cur))curPunc="newstatement";return "keyword"}
return "variable"}
tokenBase.isBase=!0;function startString(quote,stream,state){var tripleQuoted=!1;if(quote!="/"&&stream.eat(quote)){if(stream.eat(quote))tripleQuoted=!0;else return "string"}
function t(stream,state){var escaped=!1,next,end=!tripleQuoted;while((next=stream.next())!=null){if(next==quote&&!escaped){if(!tripleQuoted){break}
if(stream.match(quote+quote)){end=!0;break}}
if(quote=='"'&&next=="$"&&!escaped&&stream.eat("{")){state.tokenize.push(tokenBaseUntilBrace());return "string"}
escaped=!escaped&&next=="\\"}
if(end)state.tokenize.pop();return "string"}
state.tokenize.push(t);return t(stream,state)}
function tokenBaseUntilBrace(){var depth=1;function t(stream,state){if(stream.peek()=="}"){depth--;if(depth==0){state.tokenize.pop();return state.tokenize[state.tokenize.length-1](stream,state)}}else if(stream.peek()=="{"){depth++}
return tokenBase(stream,state)}
t.isBase=!0;return t}
function tokenComment(stream,state){var maybeEnd=!1,ch;while(ch=stream.next()){if(ch=="/"&&maybeEnd){state.tokenize.pop();break}
maybeEnd=(ch=="*")}
return "comment"}
function expectExpression(last){return!last||last=="operator"||last=="->"||/[\.\[\{\(,;:]/.test(last)||last=="newstatement"||last=="keyword"||last=="proplabel"}
function Context(indented,column,type,align,prev){this.indented=indented;this.column=column;this.type=type;this.align=align;this.prev=prev}
function pushContext(state,col,type){return state.context=new Context(state.indented,col,type,null,state.context)}
function popContext(state){var t=state.context.type;if(t==")"||t=="]"||t=="}")
state.indented=state.context.indented;return state.context=state.context.prev}
return{startState:function(basecolumn){return{tokenize:[tokenBase],context:new Context((basecolumn||0)-config.indentUnit,0,"top",!1),indented:0,startOfLine:!0,lastToken:null}},token:function(stream,state){var ctx=state.context;if(stream.sol()){if(ctx.align==null)ctx.align=!1;state.indented=stream.indentation();state.startOfLine=!0;if(ctx.type=="statement"&&!expectExpression(state.lastToken)){popContext(state);ctx=state.context}}
if(stream.eatSpace())return null;curPunc=null;var style=state.tokenize[state.tokenize.length-1](stream,state);if(style=="comment")return style;if(ctx.align==null)ctx.align=!0;if((curPunc==";"||curPunc==":")&&ctx.type=="statement")popContext(state);else if(curPunc=="->"&&ctx.type=="statement"&&ctx.prev.type=="}"){popContext(state);state.context.align=!1}
else if(curPunc=="{")pushContext(state,stream.column(),"}");else if(curPunc=="[")pushContext(state,stream.column(),"]");else if(curPunc=="(")pushContext(state,stream.column(),")");else if(curPunc=="}"){while(ctx.type=="statement")ctx=popContext(state);if(ctx.type=="}")ctx=popContext(state);while(ctx.type=="statement")ctx=popContext(state)}
else if(curPunc==ctx.type)popContext(state);else if(ctx.type=="}"||ctx.type=="top"||(ctx.type=="statement"&&curPunc=="newstatement"))
pushContext(state,stream.column(),"statement");state.startOfLine=!1;state.lastToken=curPunc||style;return style},indent:function(state,textAfter){if(!state.tokenize[state.tokenize.length-1].isBase)return 0;var firstChar=textAfter&&textAfter.charAt(0),ctx=state.context;if(ctx.type=="statement"&&!expectExpression(state.lastToken))ctx=ctx.prev;var closing=firstChar==ctx.type;if(ctx.type=="statement")return ctx.indented+(firstChar=="{"?0:config.indentUnit);else if(ctx.align)return ctx.column+(closing?0:1);else return ctx.indented+(closing?0:config.indentUnit)},electricChars:"{}",fold:"brace"}});CodeMirror.defineMIME("text/x-groovy","groovy")})