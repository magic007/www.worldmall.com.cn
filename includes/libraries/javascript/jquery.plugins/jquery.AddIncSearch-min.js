(function(c){var b="AddIncSearch";c[b]={defaultOptions:{maxListSize:20,maxMultiMatch:50,maxListWidth:false,onSelect:false,warnMultiMatch:"top {0} matches ...",warnNoMatch:"no matches ...",selectBoxHeight:"30ex",zIndex:"auto",searchName:false}};var a={moveInputFocus:function(g,f){var e=g.parents("form").eq(0).find("button:visible,input:visible,textarea:visible,select:visible");var d=e.index(g);if(d>-1&&d+f<e.length&&d+f>=0){e.eq(d+f).focus();return true}else{return false}},reEscape:function(d){return d.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1")},action:function(h,y){if(this.nodeName!="SELECT"||this.size>1){return this}var n=this;var C=c(n);if(C.data("AICelements")){return this}var A=C.parent();var E="AIS_"+Math.floor(10000000000*Math.random()).toString(36);var p=h;if(c.meta){p=c.extend({},h,C.data())}var f=c('<option value="_E_M_P_T_Y_"></option>');C.append(f);var o=c("<div>"+p.warnMultiMatch.replace(/\{0\}/g,p.maxMultiMatch)+"</div>").css({color:"#bbb"});var i=c("<div>"+p.warnNoMatch+"</div>").css({color:"#bbb"});var j=c("<div/>").css({position:"absolute",width:C.outerWidth(),height:C.outerHeight(),backgroundColor:"#FFFFFF",opacity:"0.01",filter:"Alpha(opacity=1)"}).appendTo(A);var e=c('<input type="text"/>').hide().addClass("addIncSearch").css({position:"absolute",backgroundColor:"transparent",outlineStyle:"none",borderColor:"transparent",borderStyle:"solid",borderBottomWidth:C.css("border-bottom-width"),borderLeftWidth:C.css("border-left-width"),borderRightWidth:C.css("border-right-width"),borderTopWidth:C.css("border-top-width"),marginBottom:C.css("margin-bottom"),marginLeft:C.css("margin-left"),marginRight:C.css("margin-right"),marginTop:C.css("margin-top"),paddingBottom:C.css("padding-bottom"),paddingLeft:C.css("padding-left"),paddingRight:C.css("padding-right"),paddingTop:C.css("padding-top")}).width(C.innerWidth()).height(C.outerHeight()).appendTo(A);if(p.searchName!==false){e.attr("name",p.searchName)}var D=c("<div/>").addClass("AddIncSearchChooser").hide().css({position:"absolute",height:p.selectBoxHeight.toString(),width:p.maxListWidth?p.maxListWidth:C.outerWidth()-6,overflow:"auto",borderColor:C.css("border-color")||"#000",borderStyle:"solid",borderWidth:"1px",padding:"2px",backgroundColor:C.css("background-color"),fontFamily:C.css("font-family"),fontSize:C.css("font-size"),cursor:"pointer",MozUserSelect:"none",WebkitUserSelect:"none",userSelect:"none",boxShadow:"3px 3px 5px #bbb",MozBoxShadow:"3px 3px 5px #bbb",WebkitBoxShadow:"3px 3px 5px #bbb"});D.xClear=function(){this.xIdArr=[];this.xCurrentRow=null};D.xHiLite=function(L){if(this.xCurrentRow!=null){c("#"+E+this.xIdArr[this.xCurrentRow].toString(36)).css({color:C.css("color"),backgroundColor:"transparent"})}if(L>=this.xIdArr.length){L=this.xIdArr.length-1}else{if(L<0){L=0}}var H=c("#"+E+this.xIdArr[L].toString(36)).css({color:"#fff",backgroundColor:"#444"});var J=H.get(0);if(J){var K=H.position().top;var F=D.scrollTop();var G=H.height();var I=D.height()-G;if(K>=I){D.scrollTop(F+K-I+G)}else{if(K<0){D.scrollTop(F+K)}}}this.xCurrentRow=L};D.xNextRow=function(){if(this.xCurrentRow<this.xIdArr.length-1){this.xHiLite(this.xCurrentRow+1)}};D.xPrevRow=function(){if(this.xCurrentRow>0){this.xHiLite(this.xCurrentRow-1)}};D.xNextPage=function(){if(this.xCurrentRow<this.xIdArr.length-1){this.xHiLite(this.xCurrentRow+5)}};D.xPrevPage=function(){if(this.xCurrentRow>0){this.xHiLite(this.xCurrentRow-5)}};D.xClear();D.xClickify=function(){for(var F=0;F<this.xIdArr.length;F++){var G="#"+E+this.xIdArr[F].toString(36);(function(){var H=F;c(G).click(function(){D.xHiLite(H);d();m(n)})})()}};var r=D.get(0);var z=/^\d+$/.test(C.css("z-index"))?C.css("z-index"):1;if(p.zIndex&&/^\d+$/.test(p.zIndex)){z=p.zIndex}j.css("z-index",z.toString(10));e.css("z-index",(z+1).toString(10));D.css("z-index",(z+1).toString(10));D.appendTo(A);function q(){var F=C.position();D.css({top:F.top+C.outerHeight()+2,left:F.left+2});e.css({top:F.top,left:F.left+2});j.css({top:F.top,left:F.left})}C.resize(q);c(window).resize(q);q();var v=false;j.mouseover(function(){v=true});j.mouseout(function(){v=false});var B=false;D.mouseover(function(){B=true});D.mouseout(function(){B=false});D.click(function(F){e.focus();F.stopPropagation()});var g;var t;var x;var u=null;function l(){if(x==t){u=null;return true}D.xClear();x=t;t=a.reEscape(t);var M;var J=0;var O=n.length;var F=n.options;var L=new RegExp(t,"i");var K=new RegExp("(.*?)("+t+")(.*)","i");var G="";var N;var H;for(var I=0;I<O&&J<p.maxMultiMatch;I++){if(L.test(F[I].text)){N=K.exec(F[I].text);J++;D.xIdArr.push(I);H='<div id="'+E+I.toString(36)+'">'+N[1]+'<span style="background-color: #8f8; color: #000;">'+N[2]+"</span>"+N[3]+"</div>";G+=H;M=I}}if(J==1&&O<p.maxListSize){G="";D.xClear();for(var I=0;I<O;I++){D.xIdArr.push(I);if(I==M){G+=H}else{G+='<div id="'+E+I.toString(36)+'">'+F[I].text+"</div>"}}D.html(G);D.xHiLite(M)}else{if(J>=1){D.html(G);D.xHiLite(0)}else{D.empty();D.append(i)}}if(J>=p.maxMultiMatch){D.append(o)}D.xClickify();u=null}function m(F){if(typeof p.onSelect=="function"){p.onSelect.call(this,F)}}function w(){g=n.selectedIndex;n.selectedIndex=n.length;t="";x="dymmy";if(g!=undefined&&g>=0){t=n.options[g].text;e.val(t)}e.show();e.focus();e.select();D.show();u=setTimeout(l,100)}function d(){if(D.xCurrentRow!=null){n.selectedIndex=D.xIdArr[D.xCurrentRow];C.change()}else{n.selectedIndex=g}e.val(n.options[n.selectedIndex].text);e.hide();D.hide();D.empty()}e.val(C.find("option:selected").text());j.click(function(F){if(C.attr("disabled")){return false}w();F.stopPropagation()});C.bind("focus.AIC",function(F){F.preventDefault();w()});C.bind("click.AIC",function(F){F.preventDefault();w()});e.blur(function(F){if(!v&&!B){d();return true}return false;F.stopPropagation()});e.keyup(function(F){if(c.inArray(F.keyCode,new Array(9,13,40,38,34,33))>0){return true}t=c.trim(e.val());if(u!=null){clearTimeout(u)}u=setTimeout(l,100)});var k=r.size;function s(F){switch(F.keyCode){case 9:d();a.moveInputFocus(C,F.shiftKey?-1:1);break;case 13:d();a.moveInputFocus(C,1);m(n);break;case 40:D.xNextRow();break;case 38:D.xPrevRow();break;case 34:D.xNextPage();break;case 33:D.xPrevPage();break;default:return true}F.stopPropagation();return false}e.keydown(s);C.data("AICelements",[D,j,e,f]);return this}};c.fn[b]=function(d){if(c.browser.msie){var f=(parseInt(c.browser.version));if(f<7){return this}}var e=c.extend({},c[b].defaultOptions,d);return this.each(function(g){a.action.call(this,e,g)})};c.fn.RemoveIncSearch=function(){return this.each(function(){var f=c(this);var e=f.data("AICelements");if(e){for(var d=0;d<e.length;d++){e[d].empty().remove()}f.removeData("AICelements");f.unbind("click.AIC");f.unbind("focus.AIC")}})}})(jQuery);
