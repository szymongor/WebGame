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
		url: 'http://localhost/reg/api/playerId.php',
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
		url: 'http://localhost/reg/api/map.php/tile/?x='+x+'&y='+y,
		success: function(data){
				storeTiles($.parseJSON(data));
		}
	});
};

function getRegion(xFrom,xTo,yFrom,yTo){
	tiles =[];
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/map.php/region/?xFrom='+xFrom+'&xTo='+xTo+'&yFrom='+yFrom+'&yTo='+yTo,
		success: function(data){
				var region = $.parseJSON(data);
				$.each(region, function(i,row){
					$.each(row, function(j,value){
						storeTiles(value);
					});
				});
		}
	});
}

function getBuildingDetails(x,y,f,element){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/map.php/building/?x='+x+'&y='+y,
		success: function(data){
				f($.parseJSON(data),element);
		}
	});
}

function showBuildingDetails(building){
	if(building==null){
			$('#detailsView').empty();
			$('#detailsView').append("No building here.");
			var element = "tile" +selectedTile[0]+"x" +selectedTile[1];
			if($('#'+element).hasClass("ownedTile")){
				showBuildingsToBuild();
			}

		return;
	}
	var buildingStr = "Type: "+building.type;
	$('#detailsView').empty();
	$('#detailsView').append(buildingStr);
}

function showBuildingsToBuild(){
	$('#detailsView').append("<div class='gameDetailsList' id='buildingsToBuildList'></div>");
	getBuildingsToBuild();
}

function getBuildingsToBuild(){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/building.php/toBuild',
		success: function(data){
				var buildingList = $.parseJSON(data);
				$.each(buildingList, function(i,value){
					appendBuildingToBuild(value);
				});
		}
	});
}

function appendBuildingToBuild(building){
	$('#buildingsToBuildList').append("<div class='gameDetailsBuildingToBuild' onclick=build("+building.id+") id='"+building.Type+"ToBuild' ></div>");
	$('#'+building.Type+"ToBuild").append(building.Type + "<br/>");
	$('#'+building.Type+"ToBuild").append('<img src="img/Buildings/'+building.Type+'.png" height="70px" width="70px "/>');
	$('#'+building.Type+"ToBuild").append("<div class='gameDetailsBuildingToBuildResources' id='"+building.Type+"ToBuildCost' ></div>");
	$.each(building.Cost, function(i,value){
		if(value!=0)
		$('#'+building.Type+"ToBuildCost").append(i+":"+value+"<br/>");
	});

}

function storeTiles(tileJSON){
	tiles.push(tileJSON);
	var x_coord = tileJSON['x_coord']-mapXYCorner[0];
	var y_coord = tileJSON['y_coord']-mapXYCorner[1];
	var element = "tile" +y_coord+"x" +x_coord;
	var biome = tileJSON['biome'];
	$('#'+element).empty();
	$('#'+element).prepend('<img id="theImg" src="img/Biomes/'+biome+'.png" height="100%" width="100%"/>');
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
	getRegion(0,7,0,7);

	for (i=0; i < 8; i++){
		for(j=0; j < 8 ; j++){
			var element = "tile" +i+"x" +j;
			//console.log(tile['biome']);
			//getTile(mapXYCorner[0]+i,mapXYCorner[1]+j);
			$('#gameMap').append('<div class="mapTile" onclick="setTile('+i+','+j+')" ondblclick="setTile('+i+','+j+'), setDetailsBuilding()" id="'+element+'">'+'</div>');
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

	if(selectedTileObject.biome == "Fog"){
		mapTileStr = "Location is too far."
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

function conquer(){
	if(selectedTile != null){
		$.ajax({
			type: 'GET',
			url: 'http://localhost/reg/api/conquer.php?x='+selectedTile[1]+'&y='+selectedTile[0],
			success: function(data){
				var newRegionTiles = $.parseJSON(data);

				$.each(newRegionTiles, function(i,row){
					$.each(row, function(j,value){
						tiles = $.grep(tiles, function(e) {
		  				return (e.x_coord != value.x_coord || e.y_coord != value.y_coord);

						});
						storeTiles(value);
					});

				});


			}
		});
	}
}

function build(buildingTypeId){
	console.log(selectedTile[0]+", "+ selectedTile[1] +", "+ buildingTypeId);
}
