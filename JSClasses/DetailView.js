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

  this.openDetailView = function(e,openWindow){
    detailView.selectedTile = e.detail;
    if(openWindow == "Map"){
      detailView.setDetailsMap();
    }
    else if (openWindow == "Building") {
      detailView.setDetailsBuilding();
    }


  }

  this.setDetailsBuilding = function(){
    //this.selectedTile = e.detail;
    if(this.selectedTile == null){
      return;
    }
    $('#detailsBuilding').addClass("gameDetailsOptionSelected");
  	$('#detailsMap').removeClass("gameDetailsOptionSelected");
    if(this.selectedTile['building']==null){
  			$('#detailsView').empty();
  			$('#detailsView').append("No building here.")
  			var element = "tile" +this.selectedTile[0]+"x" +this.selectedTile[1];
  			if(this.selectedTile.id_owner == playerInfo['id']){
  				DV.showBuildingsToBuild();
  			}
  		return;
  	}
  	var buildingStr = "Type: "+this.selectedTile.building.type;
  	$('#detailsView').empty();
  	$('#detailsView').append("<div>"+buildingStr+"</div>");

    DV.setBuildingsFunctions();
    //console.log(this.selectedTile.building);
  }

  this.setBuildingsFunctions = function(){
    if(DV.selectedTile.id_owner != playerInfo['id']){
      return;
    }
    $('#detailsView').append('<div class="gameDetailsOption" id="detailsProduction" onclick="detailView.appendBuildingTypeFunction(\'Production\')" style ="width : 30%">Production</div>');
    $('#detailsView').append('<div class="gameDetailsOption" id="detailsRecruit" onclick="detailView.appendBuildingTypeFunction(\'Recruit\')" style ="width : 30%">Recruit</div>');
    $('#detailsView').append('<div class="gameDetailsOption" id="detailsTechnology" onclick="detailView.appendBuildingTypeFunction(\'Technology\')" style ="width : 30%">Technology</div>');
    $('#detailsView').append("<div class='gameDetailsList' id='buildingsFunctionsList'></div>");
    //DV.appendBuildingsFunctions(1,1);
    DV.appendBuildingTypeFunction("Production");
  }

  this.appendBuildingTypeFunction = function(functionType){
    $('#detailsProduction').removeClass("gameDetailsOptionSelected");
    $('#detailsRecruit').removeClass("gameDetailsOptionSelected");
    $('#detailsTechnology').removeClass("gameDetailsOptionSelected");

    $('#details'+functionType).addClass("gameDetailsOptionSelected")


    var x = DV.selectedTile.x_coord;
    var y = DV.selectedTile.y_coord;
    apiClient.getBuildingsFunctions(x,y,functionType,DV.appendBuildingsFunctions);
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

    if(selectedTile.x_coord != null && selectedTile.y_coord != null){
      mapTileStr += "  | ";
      mapTileStr += "X : "+selectedTile.x_coord+" | Y : "+selectedTile.y_coord;
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
  		$('#'+building.Type+"ToBuildCost").append(i+":"+-value+"<br/>");
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

  this.appendBuildingsFunctions = function(x,y,builsingsFunctions,functionType){
    $('#buildingsFunctionsList').empty();
    if(builsingsFunctions == null){
      return;
    }
    var functions = builsingsFunctions[functionType];
    $.each(functions,function(i,func){
      $('#buildingsFunctionsList').append("<div class='gameDetailsBuildingToBuild' onclick='detailView.addBuildingTask("+x+","+y+",\""+func["Name"]+"\",10)' id='functions"+
      func["Name"]+"' ></div>");
      $('#functions'+func["Name"]).append(func["Name"]+"</br>");
      $('#functions'+func["Name"]).append("<div class='gameDetailsBuildingToBuildResources' id='function"+func["Name"]+"Cost' ></div>");
      $.each(func["Cost"],function(i,cost){
        $("#function"+func["Name"]+"Cost").append(i+" : "+-cost +"</br>");
      })
    });
  }

  this.addBuildingTask = function(x, y, taskType, amount){
    console.log(taskType);
    apiClient.makeBuildingTask(x,y,taskType,amount);
  }

  this.addUnit = function(Type){
    var amount = $('#'+Type+'Number').val();
    //console.log(Type+":"+amount);
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
