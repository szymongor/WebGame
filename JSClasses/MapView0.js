function MapView(width, height, xCoord, yCoord, playerId, apiClient){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];
  this.apiClient = apiClient;
  var mv = this;
  var scale = 50;

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
  }

  this.showMapGrid = function(){
    this.selectedTile = null;
  	this.tiles = [];
  	var div_content ="";
    var h = $('#gameMap').innerHeight();
    var w = $('#gameMap').innerWidth();
  	$('#gameMap').empty();
    $('#gameMap').append('<canvas id="mapViewCanv" width="'+w+'" height="'+h+'" style="border:1px solid #000000;"></canvas>')

    apiClient.getRegion(this.mapXYCorner[0],this.mapXYCorner[0]+this.width-1,
      this.mapXYCorner[1],this.mapXYCorner[1]+ this.height-1,this.showMapTile);


  };

  this.showBuilding = function(buildingJSON, element){

  }

  this.updateTile = function(tileJSON){
  }

  this.showMapTile = function(tileJSON){
    //this.tiles.push(tileJSON);
    console.log("Showing Tile");
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
  	var x_coord = tileJSON['x_coord']-mv.mapXYCorner[0];
  	var y_coord = tileJSON['y_coord']-mv.mapXYCorner[1];
  	var biome = tileJSON['biome'];

    var imgBiome = new Image();
    imgBiome.onload = function() {
      context.drawImage(imgBiome, x_coord*scale, y_coord*scale, scale, scale);
    }
    imgBiome.src = "img/Biomes/"+biome+".png";

    if(tileJSON['building']!=null){
      var buildingType = tileJSON['building']['type'];
      var imgBuilding = new Image();
      imgBuilding.onload = function() {
        context.drawImage(imgBuilding, x_coord*scale, y_coord*scale, scale, scale);
      }
      imgBuilding.src = "img/Buildings/"+buildingType+".png";
  	}

  }

}
