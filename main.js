$( document ).ready(function(){
	showResources();
	showMapGrid();
});

function showResources(){
	var $resourcesBar = $('#resourcesBar');
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/resources.php',
		success: function(data){
			resources = $.parseJSON(data);
			resources = $.map(resources, function(key,val) { return [[val,key]] });
			$resourcesBar.empty();
			$.each(resources,function(id,resource){
				console.log('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
				$resourcesBar.append('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
			});
		}
	});
};

function showMapGrid(){
	var div_content ="";
	$('#gameMap').empty();
	for (i=0; i < 8; i++){
		for(j=0; j < 8 ; j++){
			var element = "tile" +j+"x" +i;
			//div_content = div_content + '<div class="mapTile" onclick="setSquare('+j+','+i+')" id="'+element+'">'+'</div>';
			$('#gameMap').append('<div class="mapTile" onclick="setTile('+j+','+i+')" id="'+element+'">'+'</div>');
			console.log('<div class="mapTile" onclick="setTile('+j+','+i+')" id="'+element+'">'+'</div>');
		}
		$('#gameMap').append('<div style="clear:both;"></div>');
		//div_content = div_content + '<div style="clear:both;"></div>';
	}
}

function setTile(x,y){
	var element = "tile" +x+ "x"+y;
	$('#'+element).addClass("selectedTile");
	console.log(element);
}
