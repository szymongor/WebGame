function MapView(width, height, xCoord, yCoord, playerId, apiClient){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];
  this.apiClient = apiClient;
  this.mapGridReady = 0;
  var mv = this;
  var scale = 50;
  var mousePosition = null;


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

  this.mouseDown = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    mv.mousePosition = [x,y];
  }

  this.mouseUp = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var selectedTileNow =  $.grep(mv.tiles, function(e) {
      return (e.x_coord == x && e.y_coord == y);
    });
    if(mv.mousePosition[0] == x && mv.mousePosition[1] == y){
      if(mv.selectedTile != null){
        if(mv.selectedTile[0].x_coord == x && mv.selectedTile[0].y_coord == y){
          return;
        }
        mv.updateTile(mv.selectedTile);
      }
      var context=canvas.getContext('2d');
      context.globalAlpha = 0.4;
      context.fillStyle="#0000FF";
      context.fillRect(x*scale,y*scale,scale,scale);
      context.globalAlpha = 1;
      mv.selectedTile = selectedTileNow;

    }else{
      console.log("Move:");
      console.log([mv.mousePosition[0]-x,mv.mousePosition[1]-y]);
    }
  }

  this.selectTile = function(event){

  }

  this.showMapGrid = function(){
    mv.ready = 0;
    this.selectedTile = null;
  	this.tiles = [];
  	var div_content ="";
    var h = $('#gameMap').innerHeight();
    var w = $('#gameMap').innerWidth();
  	$('#gameMap').empty();
    $('#gameMap').append('<canvas id="mapViewCanv" width="'+w+'" height="'+h+'" style="border:1px solid #000000;"></canvas>')
    $('#mapViewCanv').mousedown('function',this.mouseDown);
    $('#mapViewCanv').mouseup('someFunction',this.mouseUp);
    apiClient.getRegion(this.mapXYCorner[0],this.mapXYCorner[0]+this.width-1,
      this.mapXYCorner[1],this.mapXYCorner[1]+ this.height-1,this.showMapTile);
  };

  this.updateTile = function(tileJSON){
    mv.tiles = $.grep(mv.tiles, function(e) {
  		return (e.x_coord != tileJSON.x_coord || e.y_coord != tileJSON.y_coord);
  	});
    mv.showMapTile(tileJSON[0]);
  }

  this.drawBorders = function(){

    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
    context.globalAlpha = 0.9;
    context.fillStyle="#00FF00";

    //LEFT
    //context.fillRect(value.x_coord*scale,value.x_coord*scale,scale/10,scale);
    //UP
    //context.fillRect(value.x_coord*scale,value.x_coord*scale,scale,scale/10);
    //DOWN
    //context.fillRect(value.x_coord*scale,value.x_coord*scale+9*scale/10,scale,scale/10);
    //RIGHT
    //context.fillRect(value.x_coord*scale+value.x_coord*scale/10,3*scale,scale/10,scale);

    $.each(this.tiles,function(i,value){

      switch(i) {
        case 1:
            context.fillRect(value.x_coord*scale,value.y_coord*scale,scale/10,scale);
            break;
        case 2:
            context.fillRect(value.x_coord*scale,value.y_coord*scale,scale,scale/10);
            break;
        case 3:
            context.fillRect(value.x_coord*scale,value.y_coord*scale+9*scale/10,scale,scale/10);
            break;
        case 0:
            context.fillRect(value.x_coord*scale+9*scale/10,value.y_coord*scale,scale/10,scale);
            break;
        default:
      }
      console.log(i%3);
      //context.fillRect(mv.scale * value.x_coord, mv.scale * value.y_coord,mv.scale * value.x_coord,mv.scale * value.x_coord);
      //console.log(value);
    })
  }

  this.showMapTile = function(tileJSON){
    mv.tiles.push(tileJSON);

    //console.log(tileJSON);
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
  	var x_coord = tileJSON['x_coord']-mv.mapXYCorner[0];
  	var y_coord = tileJSON['y_coord']-mv.mapXYCorner[1];
  	var biome = tileJSON['biome'];
    //mv.tiles[x_coord][y_coord] = tileJSON;

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
    mv.mapGridReady+=1;
    if(mv.mapGridReady==64){
      setTimeout(function(){
        mv.drawBorders();
      }, 1);
    }
  }

}
