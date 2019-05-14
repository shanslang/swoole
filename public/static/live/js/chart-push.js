$(function(){
	$("#discuss-box").keydown(function(evnt){
		if(event.keyCode == 13){
			var text = $(this).val();
			var url = "http://192.168.23.130:9502/?s=index/chart/index";
			var data = {'content':text, 'game_id': 1};
			$.post(url, data, function(result){
              console.log('cp'+result);
				$(this).val('');
			}, 'json');
		}
	});
});