var selectedTile = null;
var tiles = [];
var mapXYCorner = [0,0];
var idPlayer;

$( document ).ready(function(){
	getPlayerId();
	showResources();
	showMapGrid();
});

function getPlayerId(){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/player.php',
		success: function(data){
				idPlayer = ($.parseJSON(data))['id'];
		}
	});
};

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

function getBuildingDetails(x,y,f,element){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/building.php?xCoord='+x+'&yCoord='+y,
		success: function(data){
				f($.parseJSON(data),element);
		}
	});
}

function showBuildingDetails(building){
	if(building==null){
			$('#detailsView').empty();
			$('#detailsView').append("No building here.");
		return;
	}
	var buildingStr = "Type: "+building.type;
	$('#detailsView').empty();
	$('#detailsView').append(buildingStr);
}

function storeTiles(tileJSON){
	tiles.push(tileJSON);
	var x_coord = tileJSON['x_coord']-mapXYCorner[0];
	var y_coord = tileJSON['y_coord']-mapXYCorner[1];
	var element = "tile" +y_coord+"x" +x_coord;
	var biome = tileJSON['biome'];
	switch(biome){
		case "Forest":
			$('#'+element).prepend('<img id="theImg" src="img/Forest.png" height="100%" width="100%"/>');
			break;
		case "Desert":
			$('#'+element).prepend('<img id="theImg" src="img/Desert.png" height="100%" width="100%"/>');
			break;
		case "Swamp":
			$('#'+element).prepend('<img id="theImg" src="img/Swamp.png" height="100%" width="100%"/>');
			break;
		case "Plains":
			$('#'+element).prepend('<img id="theImg" src="img/plains.png" height="100%" width="100%"/>');
			break;
	}
	if(tileJSON['id_owner']==idPlayer){
		$('#'+element).addClass("ownedTile");
		//console.log(tileJSON);
	}
	else if (tileJSON['id_owner']!=null) {
		$('#'+element).addClass("foreignTile");
	}

	if(tileJSON['building_id']!=null){
		getBuildingDetails(x_coord,y_coord,showBuilding,element);
	}

}

function showBuilding(buildingJSON, element){
	var type = buildingJSON['type'];
	$('#'+element).append('<img id="theImgBuilding'+element+'" src="img/Buildings/'+type+'.png" height="100%" width="100%"/>');
}

function showMapGrid(){
	selectedTile = null;
	tiles = [];
	var div_content ="";
	$('#gameMap').empty();
	for (i=0; i < 8; i++){
		for(j=0; j < 8 ; j++){
			var element = "tile" +i+"x" +j;
			//console.log(tile['biome']);
			getTile(mapXYCorner[0]+i,mapXYCorner[1]+j);
			$('#gameMap').append('<div class="mapTile" onclick="setTile('+i+','+j+')" id="'+element+'">'+'</div>');
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
	setDetailsMap();

}

function setDetailsMap(){
	$('#detailsMap').addClass("gameDetailsOptionSelected");
	$('#detailsBuilding').removeClass("gameDetailsOptionSelected");
	if(selectedTile == null){
		return;
	}
	var selectedTileObject = $.grep(tiles, function(e){ return (e.x_coord == selectedTile[1]+mapXYCorner[1] && e.y_coord == selectedTile[0]+mapXYCorner[0]); })[0];


	var mapTileStr = "Biome: "+selectedTileObject.biome;

	if(selectedTileObject.id_owner != null){
		mapTileStr += "<br />";
		mapTileStr += "Owner: "+selectedTileObject.id_owner;
	}

	$('#detailsView').empty();
	$('#detailsView').append(mapTileStr);
}

function setDetailsBuilding(){
	$('#detailsBuilding').addClass("gameDetailsOptionSelected");
	$('#detailsMap').removeClass("gameDetailsOptionSelected");
	if(selectedTile == null){
		return;
	}

	getBuildingDetails(selectedTile[1]+mapXYCorner[1],selectedTile[0]+mapXYCorner[0],showBuildingDetails);

}

function selectedTiles(){
	var selectedTiles = [];
	var $items = $('.selectedTile');
	$.each($items, function(i,value){
		console.log(value.id);
	});

}

var tileNow;

function conquer(){
	if(selectedTile != null){
		$.ajax({
			type: 'GET',
			url: 'http://localhost/reg/api/conquer.php?x='+selectedTile[1]+'&y='+selectedTile[0],
			success: function(data){
				storeTiles($.parseJSON(data));
				//console.log($.parseJSON(data));
			}
		});
	}
}
