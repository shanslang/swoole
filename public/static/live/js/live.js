var wsUrl = "ws://192.168.23.130:9502";
var websocket = new WebSocket(wsUrl);

websocket.onopen = function(evt) {
    //websocket.send("hello-hh");
	console.log('conected-swoole-success');
}

websocket.onmessage = function(evt) {
  	push(evt.data);
	console.log('ws-server-return-data:'+evt.data); // 这里的data是服务端通过push发过来的
}

websocket.onclose = function(evt) {
	console.log('cloes');
}

websocket.onerror = function(evt, e) {
	console.log("error:"+evt.data);
}

function push(data){
    data = JSON.parse(data);
    //data = eval(data);
  	html = '<div class="frame">';
  	html += '<h3 class="frame-header">';
	html += '<i class="icon iconfont icon-shijian"></i>第'+data.type+'节 01：30';
  	html += '</h3>';
	html += '<div class="frame-item">';
	html += '<span class="frame-dot"></span>';
	html +=	'<div class="frame-item-author">';
  	if(data.log){
    	html += '<img src="'+data.logo+'" width="20px" height="20px" />';
    }
	
  	html += data.title;
	html += '</div>';
	html += '<p>'+data.content+'</p>';
	html += '</div>';
	html += '</div>';
  	$('#match-result').prepend(html);
}