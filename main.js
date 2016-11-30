var apiClient = new ApiClient('http://localhost');
var mv = new MapView(9,8,4,4,12,apiClient);
var resourcesView = new ResourcesView();
//var selectedTile = null;
var tiles = [];
var mapXYCorner = [0,0];
var idPlayer = apiClient.getPlayerId();

$( document ).ready(function(){
	showResources();
	showMap();
});

function showMap(){
	mv.showMapGrid();
	apiClient.getRegion(0,7,0,7,storeTiles);
}

function showResources(){
	apiClient.getPlayerResources(resourcesView.showResources);
}

function build(buildingType){
	console.log(buildingType);
	var coords = mv.getSelectedTileCoords();
	apiClient.build(coords[1],coords[0],buildingType,mv.updateTile);
}

function setTile(x,y){
	mv.selectTile(x,y);
}

function storeTiles(tileJSON){
	mv.showMapTile(tileJSON);
}
/////////
/*
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
*/

function showBuildingDetails(tileInfo){
	if(tileInfo['building']==null){
			$('#detailsView').empty();
			$('#detailsView').append("No building here.");
			selectedTile = mv.getSelectedTile();
			var element = "tile" +selectedTile[0]+"x" +selectedTile[1];
			if($('#'+element).hasClass("ownedTile")){
				showBuildingsToBuild();
			}
		return;
	}
	var buildingStr = "Type: "+tileInfo.building.type;
	$('#detailsView').empty();
	$('#detailsView').append(buildingStr);
}

function showBuildingsToBuild(){
	$('#detailsView').append("<div class='gameDetailsList' id='buildingsToBuildList'></div>");
	//getBuildingsToBuild();
	apiClient.getBuildingsToBuild(appendBuildingToBuild);
}

function getBuildingsToBuild(){
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/building.php/buildingList',
		success: function(data){
				var buildingList = $.parseJSON(data);
				$.each(buildingList, function(i,value){
					appendBuildingToBuild(value);
				});
		}
	});
}

function appendBuildingToBuild(building){
	$('#buildingsToBuildList').append("<div class='gameDetailsBuildingToBuild' onclick=build('"+building.Type+"') id='"+building.Type+"ToBuild' ></div>");
	$('#'+building.Type+"ToBuild").append(building.Type + "<br/>");
	$('#'+building.Type+"ToBuild").append('<img src="img/Buildings/'+building.Type+'.png" height="70px" width="70px "/>');
	$('#'+building.Type+"ToBuild").append("<div class='gameDetailsBuildingToBuildResources' id='"+building.Type+"ToBuildCost' ></div>");
	$.each(building.Cost, function(i,value){
		if(value!=0)
		$('#'+building.Type+"ToBuildCost").append(i+":"+value+"<br/>");
	});

}

/*
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
*/



/*
function updateTile(tileJSON){
	tiles = $.grep(tiles, function(e) {
		return (e.x_coord != tileJSON.x_coord || e.y_coord != tileJSON.y_coord);

	});
	storeTiles(tileJSON);
}
/*
function showBuilding(buildingJSON, element){
	var type = buildingJSON['type'];
	$('#'+element).append('<img id="theImgBuilding'+element+'" src="img/Buildings/'+type+'.png" height="100%" width="100%"/>');
}
*/


function setDetailsMap(){
	$('#detailsMap').addClass("gameDetailsOptionSelected");
	$('#detailsBuilding').removeClass("gameDetailsOptionSelected");
	if(mv.getSelectedTile() == null){
		return;
	}
	var selectedTileObject = mv.getSelectedTileObject();
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
	if(mv.getSelectedTile() == null){
		return;
	}
	var selectedCoord = mv.getSelectedTileCoords();
	//apiClient.getBuildingDetails(selectedCoord[1],selectedCoord[0],showBuildingDetails);
	showBuildingDetails(mv.getSelectedTileObject());

}

function conquer(){
	selectedTile = mv.getSelectedTile();
	if(selectedTile != null){
		$.ajax({
			type: 'GET',
			url: 'http://localhost/reg/api/conquer.php?x='+selectedTile[1]+'&y='+selectedTile[0],
			success: function(data){
				var newRegionTiles = $.parseJSON(data);

				$.each(newRegionTiles, function(i,row){
					$.each(row, function(j,value){
						mv.updateTile(value);
					});

				});


			}
		});
	}
}
