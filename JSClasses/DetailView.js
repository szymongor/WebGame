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

  this.openDetailView = function(e){
    detailView.selectedTile = e.detail;
    detailView.setDetailsMap();

  }

  this.setDetailsBuilding = function(){
    //this.selectedTile = e.detail;
    if(this.selectedTile == null){
      return;
    }
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
    $('#detailsBuilding').addClass("gameDetailsOptionSelected");
  	$('#detailsMap').removeClass("gameDetailsOptionSelected");

  }

  this.setDetailsMap = function(){
    if(this.selectedTile == null){
      return;
    }
    var selectedTile = mapView.getSelectedTileObject();
    if(selectedTile == null){
      return;
    }
    var mapTileStr = "Biome: "+selectedTile.biome;

    if(selectedTile.id_owner != null){
      mapTileStr += "  | ";
      mapTileStr += "Owner: "+selectedTile.id_owner;
    }

    if(selectedTile.biome == "Fog"){
      mapTileStr = "Location is too far."
    }

    $('#detailsView').empty();
    $('#detailsView').append(mapTileStr);
    detailView.showArmy();
    $('#detailsMap').addClass("gameDetailsOptionSelected");
    $('#detailsBuilding').removeClass("gameDetailsOptionSelected");

  }

  this.showArmy = function(){
    if(detailView.selectedTile.id_owner != mapView.playerId){
      return;
    }
    $('#detailsView').append("</br> Army: ");
    $('#detailsView').append("<div class='gameDetailsList' id='armyUnitsList'></div>");
    $('#detailsBuilding').addClass("gameDetailsOptionSelected");
  	$('#detailsMap').removeClass("gameDetailsOptionSelected");
    $.each(detailView.selectedTile.army, function(type,amount){
  		detailView.appendArmyUnits(type,amount);
  	});

  }

  this.showBuildingsToBuild = function(){
  	$('#detailsView').append("<div class='gameDetailsList' id='buildingsToBuildList'></div>");
  	apiClient.getBuildingsToBuild(DV.appendBuildingToBuild);
    $('#detailsBuilding').addClass("gameDetailsOptionSelected");
  	$('#detailsMap').removeClass("gameDetailsOptionSelected");

  }

  this.appendBuildingToBuild = function(building){
  	$('#buildingsToBuildList').append("<div class='gameDetailsBuildingToBuild' onclick=detailView.build('"+building.Type+"') id='"+building.Type+"ToBuild' ></div>");
  	$('#'+building.Type+"ToBuild").append(building.Type + "<br/>");
  	$('#'+building.Type+"ToBuild").append('<img src="img/Buildings/'+building.Type+'.png" height="70px" width="70px "/>');
  	$('#'+building.Type+"ToBuild").append("<div class='gameDetailsBuildingToBuildResources' id='"+building.Type+"ToBuildCost' ></div>");
  	$.each(building.Cost, function(i,value){
  		if(value!=0)
  		$('#'+building.Type+"ToBuildCost").append(i+":"+value+"<br/>");
  	});
  }

  this.appendArmyUnits = function(armyUnit,amount){
    $('#armyUnitsList').append("<div class='gameDetailsArmyBar' id='"+armyUnit+"Details' ></div>");
    $('#'+armyUnit+"Details").append( armyUnit+ "<br/>");
    $('#'+armyUnit+"Details").append('<img src="img/Army/'+armyUnit+'.png" height=50vw" width="50vw "/>');
    $('#'+armyUnit+"Details").append("<div class='gameDetailsArmySub' id='"+armyUnit+"Add' ></div>");
    $('#'+armyUnit+"Details").append("<div class='gameDetailsArmySub' id='"+armyUnit+"Stats' ></div>");
    $('#'+armyUnit+"Stats").append(amount+"<br/>");
    $('#'+armyUnit+"Add").append("<input type='number' id='"+armyUnit+"Number' class='gameDetailsArmyAddNumber'></input>");
    $('#'+armyUnit+"Add").append('<div class="gameDetailsOption" onclick=detailView.addUnit("'+armyUnit+'") >Add</div>');


  }

  this.addUnit = function(Type){
    var amount = $('#'+Type+'Number').val();
    console.log(Type+":"+amount);
  }

  this.build = function(buildingType){
    if(detailView.selectedTile == null){
      return;
    }
    var x = detailView.selectedTile.x_coord;
    var y = detailView.selectedTile.y_coord;

    apiClient.build(x,y,buildingType,mapView.updateTile);
  }

}
