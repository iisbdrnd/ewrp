(function(mod){if(typeof exports=="object"&&typeof module=="object")
mod(require("../../lib/codemirror"));else if(typeof define=="function"&&define.amd)
define(["../../lib/codemirror"],mod);else mod(CodeMirror)})(function(CodeMirror){"use strict";CodeMirror.defineMode("apl",function(){var builtInOps={".":"innerProduct","\\":"scan","/":"reduce","⌿":"reduce1Axis","⍀":"scan1Axis","¨":"each","⍣":"power"};var builtInFuncs={"+":["conjugate","add"],"−":["negate","subtract"],"×":["signOf","multiply"],"÷":["reciprocal","divide"],"⌈":["ceiling","greaterOf"],"⌊":["floor","lesserOf"],"∣":["absolute","residue"],"⍳":["indexGenerate","indexOf"],"?":["roll","deal"],"⋆":["exponentiate","toThePowerOf"],"⍟":["naturalLog","logToTheBase"],"○":["piTimes","circularFuncs"],"!":["factorial","binomial"],"⌹":["matrixInverse","matrixDivide"],"<":[null,"lessThan"],"≤":[null,"lessThanOrEqual"],"=":[null,"equals"],">":[null,"greaterThan"],"≥":[null,"greaterThanOrEqual"],"≠":[null,"notEqual"],"≡":["depth","match"],"≢":[null,"notMatch"],"∈":["enlist","membership"],"⍷":[null,"find"],"∪":["unique","union"],"∩":[null,"intersection"],"∼":["not","without"],"∨":[null,"or"],"∧":[null,"and"],"⍱":[null,"nor"],"⍲":[null,"nand"],"⍴":["shapeOf","reshape"],",":["ravel","catenate"],"⍪":[null,"firstAxisCatenate"],"⌽":["reverse","rotate"],"⊖":["axis1Reverse","axis1Rotate"],"⍉":["transpose",null],"↑":["first","take"],"↓":[null,"drop"],"⊂":["enclose","partitionWithAxis"],"⊃":["diclose","pick"],"⌷":[null,"index"],"⍋":["gradeUp",null],"⍒":["gradeDown",null],"⊤":["encode",null],"⊥":["decode",null],"⍕":["format","formatByExample"],"⍎":["execute",null],"⊣":["stop","left"],"⊢":["pass","right"]};var isOperator=/[\.\/⌿⍀¨⍣]/;var isNiladic=/⍬/;var isFunction=/[\+−×÷⌈⌊∣⍳\?⋆⍟○!⌹<≤=>≥≠≡≢∈⍷∪∩∼∨∧⍱⍲⍴,⍪⌽⊖⍉↑↓⊂⊃⌷⍋⍒⊤⊥⍕⍎⊣⊢]/;var isArrow=/←/;var isComment=/[⍝#].*$/;var stringEater=function(type){var prev;prev=!1;return function(c){prev=c;if(c===type){return prev==="\\"}
return!0}};return{startState:function(){return{prev:!1,func:!1,op:!1,string:!1,escape:!1}},token:function(stream,state){var ch,funcName,word;if(stream.eatSpace()){return null}
ch=stream.next();if(ch==='"'||ch==="'"){stream.eatWhile(stringEater(ch));stream.next();state.prev=!0;return "string"}
if(/[\[{\(]/.test(ch)){state.prev=!1;return null}
if(/[\]}\)]/.test(ch)){state.prev=!0;return null}
if(isNiladic.test(ch)){state.prev=!1;return "niladic"}
if(/[¯\d]/.test(ch)){if(state.func){state.func=!1;state.prev=!1}else{state.prev=!0}
stream.eatWhile(/[\w\.]/);return "number"}
if(isOperator.test(ch)){return "operator apl-"+builtInOps[ch]}
if(isArrow.test(ch)){return "apl-arrow"}
if(isFunction.test(ch)){funcName="apl-";if(builtInFuncs[ch]!=null){if(state.prev){funcName+=builtInFuncs[ch][1]}else{funcName+=builtInFuncs[ch][0]}}
state.func=!0;state.prev=!1;return "function "+funcName}
if(isComment.test(ch)){stream.skipToEnd();return "comment"}
if(ch==="∘"&&stream.peek()==="."){stream.next();return "function jot-dot"}
stream.eatWhile(/[\w\$_]/);word=stream.current();state.prev=!0;return "keyword"}}});CodeMirror.defineMIME("text/apl","apl")})