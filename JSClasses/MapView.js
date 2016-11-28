function MapView(width, height, xCoord, yCoord, playerId){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];

  this.loguj = function(){
    console.log(this.mapXYCorner);
  };

  this.loguj = function(content){
    console.log(content);
  };

  this.showMapGrid = function(){
    this.selectedTile = null;
  	this.tiles = [];
  	var div_content ="";
  	$('#gameMap').empty();
  	//getRegion(0,7,0,7);

  	for (i=0; i < this.width ; i++){
  		for(j=0; j < this.height ; j++){
  			var element = "tile" +i+"x" +j;
  			//console.log(tile['biome']);
  			//getTile(mapXYCorner[0]+i,mapXYCorner[1]+j);
  			$('#gameMap').append('<div class="mapTile" onclick="setTile('+i+','+j+')" ondblclick="setTile('+i+','+j+'), setDetailsBuilding()" id="'+element+'">'+'</div>');
  			//console.log('<div class="mapTile" onclick="setTile('+j+','+i+')" id="'+element+'">'+'</div>');
  		}
  		$('#gameMap').append('<div style="clear:both;"></div>');
  		//div_content = div_content + '<div style="clear:both;"></div>';
  	}
  };

  this.showMapTile = function(){
    
  }

}
