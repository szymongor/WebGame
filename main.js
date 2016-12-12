var apiClient = new ApiClient('http://localhost');
var mv = new MapView(16,16,5,5,12,apiClient);
var resourcesView = new ResourcesView();
var idPlayer = apiClient.getPlayerId();

$( document ).ready(function(){
	showResources();
	showMap();
});

function showMap(){
	mv.showMapGrid();
}

function showResources(){
	apiClient.getPlayerResources(resourcesView.showResources);
}

function build(buildingType){
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
