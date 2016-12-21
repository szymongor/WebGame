function DetailView(){

  this.initDetailsView = function(){
    $('#gameDetails').empty();
    $('#gameDetails').append('<div id="gameDetailsOptionsContainer" class="gameDetailsOptionsContainer"></div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsMap" onclick="setDetailsMap()">Map</div>');
    $('#gameDetailsOptionsContainer').append('<div class="gameDetailsOption" id="detailsBuilding" onclick="setDetailsBuilding()">Building</div>');
    $('#gameDetails').append('<div class="gameDetailsView" id="detailsView"></div>;');
  }

  this.setDetailsMap = function(e){
    $('#detailsView').empty();
    $('#detailsView').append('Siemano');
    console.log(e.detail);

    var selectedTile = e.detail;
    if(selectedTile['building']==null){
  			$('#detailsView').empty();
  			$('#detailsView').append("No building here.")
  			var element = "tile" +selectedTile[0]+"x" +selectedTile[1];
  			if($('#'+element).hasClass("ownedTile")){
  				//showBuildingsToBuild();
          console.log("showToBuild");
  			}
  		return;
  	}
  	var buildingStr = "Type: "+selectedTile.building.type;
  	$('#detailsView').empty();
  	$('#detailsView').append(buildingStr);

  }

}
