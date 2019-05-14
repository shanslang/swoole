var wsUrl = "ws://192.168.23.130:9503";
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
	html = '<div class="comment">';
	html +=	'<span>'+data.user+'</span>';
	html +=	'<span>'+data.content+'</span>';
	html += '</div>';
    $("#comments").append(html);
}