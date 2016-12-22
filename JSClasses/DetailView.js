function DetailView(){

  var DV= this;
  this.selectedTile = null;

  this.initDetailsView = function(){
    $('#gameDetails').empty();
    $('#gameDetails').append('<div id="gameDetailsOptionsContainer" class="gameDetailsOptionsContainer"></div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsMap" onclick="detailView.setDetailsMap()">Map</div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsBuilding" onclick="detailView.setDetailsBuilding()">Building</div>');
    $('#gameDetails').append('<div class="gameDetailsView" id="detailsView"></div>;');
  }

  this.setDetailsBuilding = function(e){
    this.selectedTile = e.detail;
    if(this.selectedTile['building']==null){
  			$('#detailsView').empty();
  			$('#detailsView').append("No building here.")
  			var element = "tile" +this.selectedTile[0]+"x" +this.selectedTile[1];
  			if(this.selectedTile.id_owner == idPlayer){
  				DV.showBuildingsToBuild();
          console.log("showToBuild");
  			}
  		return;
  	}
  	var buildingStr = "Type: "+this.selectedTile.building.type;
  	$('#detailsView').empty();
  	$('#detailsView').append(buildingStr);

  }

  this.showBuildingsToBuild = function(){
  	$('#detailsView').append("<div class='gameDetailsList' id='buildingsToBuildList'></div>");
  	apiClient.getBuildingsToBuild(DV.appendBuildingToBuild);
  }

  this.appendBuildingToBuild = function(building){
  	$('#buildingsToBuildList').append("<div class='gameDetailsBuildingToBuild' onclick=build('"+building.Type+"') id='"+building.Type+"ToBuild' ></div>");
  	$('#'+building.Type+"ToBuild").append(building.Type + "<br/>");
  	$('#'+building.Type+"ToBuild").append('<img src="img/Buildings/'+building.Type+'.png" height="70px" width="70px "/>');
  	$('#'+building.Type+"ToBuild").append("<div class='gameDetailsBuildingToBuildResources' id='"+building.Type+"ToBuildCost' ></div>");
  	$.each(building.Cost, function(i,value){
  		if(value!=0)
  		$('#'+building.Type+"ToBuildCost").append(i+":"+value+"<br/>");
  	});

  }

  this.setDetailsMap = function(){
    console.log(this.selectedTile);
  	$('#detailsMap').addClass("gameDetailsOptionSelected");
  	$('#detailsBuilding').removeClass("gameDetailsOptionSelected");
    var selectedTile = mv.getSelectedTileObject();
  	if(selectedTile == null){
  		return;
  	}
  	var mapTileStr = "Biome: "+selectedTile.biome;

  	if(selectedTile.id_owner != null){
  		mapTileStr += "<br />";
  		mapTileStr += "Owner: "+selectedTile.id_owner;
  	}

  	if(selectedTile.biome == "Fog"){
  		mapTileStr = "Location is too far."
  	}

  	$('#detailsView').empty();
  	$('#detailsView').append(mapTileStr);
  }

}
