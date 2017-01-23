var apiClient = new ApiClient('http://localhost');
var mapView = new MapView(10,10,4,4,12,apiClient);
var detailView = new DetailView();
var resourcesView = new ResourcesView();
var idPlayer = apiClient.getPlayerId();

document.addEventListener("tileSelectDbClick", function(e) {
	detailView.openDetailView(e,"Building");
	//console.log(e.detail); // Prints selected x and y
});

document.addEventListener("tileSelectOneClick", function(e) {
	detailView.openDetailView(e, "Map");
	//console.log(e.detail); // Prints selected x and y
});

$( document ).ready(function(){
	showResources();
	showMap();
	detailView.initDetailsView();
});

function showMap(){
	mapView.showMapGrid();
}

function showResources(){
	apiClient.getPlayerResources(resourcesView.updatePlayersResources);
	apiClient.getPlayerResourcesIncome(resourcesView.updatePlayersResourcesIncome);
	apiClient.getPlayerResourcesCapacity(resourcesView.updatePlayersResourcesCapacity);

}

function build(buildingType){
	var coords = mapView.getSelectedTileCoords();
	apiClient.build(coords[1],coords[0],buildingType,mv.updateTile);
}

//function setTile(x,y){
//	mapView.selectTile(x,y);
//}

//function storeTiles(tileJSON){
//	mapView.showMapTile(tileJSON);
//}
/////////
function showBuildingDetails(tileInfo){
	if(tileInfo['building']==null){
			$('#detailsView').empty();
			$('#detailsView').append("No building here.");
			selectedTile = mapView.getSelectedTile();
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
	if(mapView.getSelectedTile() == null){
		return;
	}
	var selectedTileObject = mapView.getSelectedTileObject();
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
	if(mapView.getSelectedTile() == null){
		return;
	}
	showBuildingDetails(mapView.getSelectedTileObject());
}

function conquer(){
	selectedTile = mapView.getSelectedTile();
	if(selectedTile != null){
		$.ajax({
			type: 'GET',
			url: 'http://localhost/reg/api/conquer.php?x='+selectedTile[1]+'&y='+selectedTile[0],
			success: function(data){
				var newRegionTiles = $.parseJSON(data);
				$.each(newRegionTiles, function(i,row){
					$.each(row, function(j,value){
						mapView.updateTile(value);
					});
				});
			}
		});
	}
}
