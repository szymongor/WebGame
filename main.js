var selectedTile = null;
var tiles = [];

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
				//console.log('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
				$resourcesBar.append('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
			});
		}
	});
};

function getTile(x,y){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/map.php?x='+x+'&y='+y,
		success: function(data){
				storeTiles($.parseJSON(data));
		}
	});
};

function storeTiles(tileJSON){
	tiles.push(tileJSON);
	var x_coord = tileJSON['x_coord'];
	var y_coord = tileJSON['y_coord'];
	var element = "tile" +y_coord+"x" +x_coord;
	var biome = tileJSON['biome'];
	$('#'+element).append(biome[0]);
}

function showMapGrid(){
	selectedTile = null;
	var div_content ="";
	$('#gameMap').empty();
	for (i=0; i < 8; i++){
		for(j=0; j < 8 ; j++){
			var element = "tile" +j+"x" +i;
			var value = "["+i+","+j+"]";
			//console.log(tile['biome']);
			getTile(i,j);
			$('#gameMap').append('<div class="mapTile" onclick="setTile('+j+','+i+')" id="'+element+'">'+'</div>');
			//console.log('<div class="mapTile" onclick="setTile('+j+','+i+')" id="'+element+'">'+'</div>');
		}
		$('#gameMap').append('<div style="clear:both;"></div>');
		//div_content = div_content + '<div style="clear:both;"></div>';
	}
}

function setTile(x,y){
	if(selectedTile != null){
		var element = "tile" +selectedTile[0]+ "x"+selectedTile[1];
		$('#'+element).toggleClass("selectedTile");
	}
	var coords = [x,y];
	var element = "tile" +x+ "x"+y;
	$('#'+element).toggleClass("selectedTile");
	selectedTile = coords;

}

function selectedTiles(){
	var selectedTiles = [];
	var $items = $('.selectedTile');
	$.each($items, function(i,value){
		console.log(value.id);
	});

}
