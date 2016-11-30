function MapView(width, height, xCoord, yCoord, playerId, apiClient){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];
  this.apiClient = apiClient;

  this.loguj = function(){
    console.log(this.mapXYCorner);
  };

  this.loguj = function(content){
    console.log(content);
  };

  this.getTiles = function(){
    return this.tiles;
  }

  this.getSelectedTileObject = function(){
    var selectedCoords = this.getSelectedTileCoords();
    var selectedTile = $.grep(this.tiles, function(e){ return (e.x_coord == selectedCoords[1] && e.y_coord == selectedCoords[0]); })[0];
    return selectedTile;
  }

  this.getSelectedTile = function(){
    return this.selectedTile;
  }

  this.getSelectedTileCoords = function(){
    var coords = [this.selectedTile[0]-this.mapXYCorner[0],this.selectedTile[1]-this.mapXYCorner[1]];
    return coords;
  }

  this.selectTile = function(x,y){
    if(this.selectedTile != null){
  		var element = "tile" +this.selectedTile[0]+ "x"+this.selectedTile[1];
  		$('#'+element).toggleClass("selectedTile");
  	}
  	var coords = [x,y];
  	var element = "tile" +x+ "x"+y;
  	$('#'+element).toggleClass("selectedTile");
  	this.selectedTile = coords;
  	setDetailsMap();
  }

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

  this.showBuilding = function(buildingJSON, element){
  	var type = buildingJSON['type'];
  	$('#'+element).append('<img id="theImgBuilding'+element+'" src="img/Buildings/'+type+'.png" height="100%" width="100%"/>');
  }

  this.updateTile = function(tileJSON){
    this.tiles = $.grep(tiles, function(e) {
  		return (e.x_coord != tileJSON.x_coord || e.y_coord != tileJSON.y_coord);
  	});
    console.log(tileJSON);
  	this.showMapTile(tileJSON);
  }

  this.showMapTile = function(tileJSON){
    this.tiles.push(tileJSON);
  	var x_coord = tileJSON['x_coord']-mapXYCorner[0];
  	var y_coord = tileJSON['y_coord']-mapXYCorner[1];
  	var element = "tile" +y_coord+"x" +x_coord;
  	var biome = tileJSON['biome'];
  	$('#'+element).empty();
  	$('#'+element).prepend('<img id="theImg" src="img/Biomes/'+biome+'.png" height="100%" width="100%"/>');
  	if(tileJSON['id_owner']==idPlayer){
  		$('#'+element).addClass("ownedTile");
  	}
  	else if (tileJSON['id_owner']!=null) {
  		$('#'+element).addClass("foreignTile");
  	}
  	if(tileJSON['building']!=null){
      this.showBuilding(tileJSON['building'],element);
  	}
  }

}
