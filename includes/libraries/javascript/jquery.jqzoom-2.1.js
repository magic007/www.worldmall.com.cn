(function(a){a.fn.jqueryzoom=function(b){var d={xzoom:200,yzoom:200,offset:10,position:"right",lens:1,preload:1};if(b){a.extend(d,b)}var c="";a(this).hover(function(){var j=a(this).parents();j.each(function(){if(a(this).css("position")=="relative"){a(this).css("position","static");a(this).attr("ectype","jqzoom_relative")}});var f=this.offsetLeft;var g=this.offsetRight;var k=a(this).get(0).offsetTop;var i=a(this).children("img").get(0).offsetWidth;var e=a(this).children("img").get(0).offsetHeight;c=a(this).children("img").attr("alt");var h=a(this).children("img").attr("jqimg");a(this).children("img").attr("alt","");if(a("div.zoomdiv").get().length==0){a(this).after("<div class='zoomdiv'><img class='bigimg' src='"+h+"'/></div>");a(this).append("<div class='jqZoomPup'>&nbsp;</div>")}if(d.position=="right"){if(f+i+d.offset+d.xzoom>screen.width){leftpos=f-d.offset-d.xzoom}else{leftpos=f+i+d.offset}}else{leftpos=f-d.xzoom-d.offset;if(leftpos<0){leftpos=f+i+d.offset}}a("div.zoomdiv").css({top:k,left:leftpos});a("div.zoomdiv").width(d.xzoom);a("div.zoomdiv").height(d.yzoom);a("div.zoomdiv").show();if(!d.lens){a(this).css("cursor","crosshair")}a(document.body).mousemove(function(q){mouse=new MouseEvent(q);var r=a(".bigimg").get(0).offsetWidth;var p=a(".bigimg").get(0).offsetHeight;var n="x";var o="y";if(isNaN(o)|isNaN(n)){var o=(r/i);var n=(p/e);var m=o<=1?i:(d.xzoom)/o;var l=n<=1?e:(d.yzoom)/n;a("div.jqZoomPup").width(m);a("div.jqZoomPup").height(l);if(d.lens){a("div.jqZoomPup").css("visibility","visible")}}xpos=mouse.x-a("div.jqZoomPup").width()/2-f;ypos=mouse.y-a("div.jqZoomPup").height()/2-k;if(d.lens){xpos=(mouse.x-a("div.jqZoomPup").width()/2<f)?0:(mouse.x+a("div.jqZoomPup").width()/2>i+f)?(i-a("div.jqZoomPup").width()-2):xpos;ypos=(mouse.y-a("div.jqZoomPup").height()/2<k)?0:(mouse.y+a("div.jqZoomPup").height()/2>e+k)?(e-a("div.jqZoomPup").height()-2):ypos}if(d.lens){a("div.jqZoomPup").css({top:ypos,left:xpos})}scrolly=ypos;a("div.zoomdiv").get(0).scrollTop=scrolly*n;scrollx=xpos;a("div.zoomdiv").get(0).scrollLeft=(scrollx)*o})},function(){var e=a(this).parents();e.each(function(){if(a(this).attr("ectype")=="jqzoom_relative"){a(this).css("position","relative")}});a(this).children("img").attr("alt",c);a(document.body).unbind("mousemove");if(d.lens){a("div.jqZoomPup").remove()}a("div.zoomdiv").remove()});count=0;if(d.preload){a("body").append("<div style='display:none;' class='jqPreload"+count+"'>sdsdssdsd</div>");a(this).each(function(){var f=a(this).children("img").attr("jqimg");var e=jQuery("div.jqPreload"+count+"").html();jQuery("div.jqPreload"+count+"").html(e+'<img src="'+f+'">')})}}})(jQuery);function MouseEvent(a){this.x=a.pageX;this.y=a.pageY};
