function DetailView(){

  var DV= this;

  this.initDetailsView = function(){
    $('#gameDetails').empty();
    $('#gameDetails').append('<div id="gameDetailsOptionsContainer" class="gameDetailsOptionsContainer"></div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsMap" onclick="setDetailsMap()">Map</div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsBuilding" onclick="setDetailsBuilding()">Building</div>');
    $('#gameDetails').append('<div class="gameDetailsView" id="detailsView"></div>;');
  }

  this.setDetailsMap = function(e){
    //console.log(e.detail);

    var selectedTile = e.detail;
    if(selectedTile['building']==null){
  			$('#detailsView').empty();
  			$('#detailsView').append("No building here.")
  			var element = "tile" +selectedTile[0]+"x" +selectedTile[1];
  			if(selectedTile.id_owner == idPlayer){
  				DV.showBuildingsToBuild();
          console.log("showToBuild");
  			}
  		return;
  	}
  	var buildingStr = "Type: "+selectedTile.building.type;
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

}
