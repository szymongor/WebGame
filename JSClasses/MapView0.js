function MapView(width, height, xCoord, yCoord, playerId, apiClient){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];
  this.apiClient = apiClient;
  this.mapGridReady = 0;
  this.playerId = playerId;
  var scale = 40;
  this.mousePosition = null;
  this.mapCornerVec = null;
  this.selectedTile = null;


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
    //var selectedCoords = this.getSelectedTileCoords();
    //var selectedTile = $.grep(this.tiles, function(e){ return (e.x_coord == selectedCoords[1] && e.y_coord == selectedCoords[0]); })[0];
    return mapView.selectedTile;
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
    mapView.mapCornerVec = mapView.mapXYCorner;
    mapView.mousePosition = [x,y];
  }

  this.mouseUp = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var selectedTileNow =  mapView.tiles[x][y];
    if(mapView.mousePosition[0] == x && mapView.mousePosition[1] == y){
      if(mapView.selectedTile != null){
        if(mapView.selectedTile.x_coord - mapView.mapXYCorner[0] == x && mapView.selectedTile.y_coord - mapView.mapXYCorner[1]  == y){
          return;
        }
        mapView.updateTile(mapView.selectedTile);
      }
      var context=canvas.getContext('2d');
      context.globalAlpha = 0.4;
      context.fillStyle="#0000FF";
      context.fillRect(x*scale,y*scale,scale,scale);
      context.globalAlpha = 1;
      mapView.selectedTile = selectedTileNow;



    }else{
      var moveVector = [x-mapView.mousePosition[0],y-mapView.mousePosition[1]]
      var newMapCorener =  [mapView.mapXYCorner[0] - moveVector[0],mapView.mapXYCorner[1] - moveVector[1]];
      mapView.mapXYCorner = newMapCorener
      mapView.updateMapGrid();
      mapView.drawBorders();
    }
    mapView.mousePosition = null;
    mapView.mapCornerVec = mapView.mapXYCorner;

    var event = new CustomEvent("tileSelectOneClick", { "detail":selectedTileNow });
    document.dispatchEvent(event);
  }

  this.dbclick = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var selectedTile = mapView.tiles[x][y];
    //this.selectedTile = selctedTile;
    var event = new CustomEvent("tileSelectDbClick", { "detail":selectedTile });
    document.dispatchEvent(event);

    //document.addEventListener("name-of-event", function(e) {
    //  console.log(e.detail); // Prints selected x and y
    //});

  }

  this.mouseMove = function(event){
    if(mapView.mousePosition == null){
      return;
    }
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var moveVector = [x-mapView.mousePosition[0], y-mapView.mousePosition[1]];
    var newMapCorener =  [mapView.mapCornerVec[0] - moveVector[0], mapView.mapCornerVec[1] - moveVector[1]];
    //console.log(newMapCorener);
    if(newMapCorener[0] != mapView.mapXYCorner[0] || newMapCorener[1] != mapView.mapXYCorner[1]){
      mapView.mapXYCorner = newMapCorener;
      mapView.updateMapGrid();
    }
  }

  this.selectTile = function(event){}

  this.showMapGrid = function(){
    mapView.mapGridReady = 0;
    this.selectedTile = null;
  	this.tiles = [];
    for(i = 0 ; i < mapView.width ; i++ ){
      var row = [];
      for(j = 0 ; j < mapView.height ; j++ ){
        row.push([]);
      }
      mapView.tiles.push(row);
    }
  	var div_content ="";
    var h = $('#gameMap').innerHeight();
    var w = $('#gameMap').innerWidth();
  	$('#gameMap').empty();
    $('#gameMap').append('<canvas id="mapViewCanv" width="'+w+'" height="'+h+'" style="border:1px solid #000000;"></canvas>');
    $('#mapViewCanv').mousedown('function',this.mouseDown);
    $('#mapViewCanv').mouseup('someFunction',this.mouseUp);
    //$('#mapViewCanv').mousemove('someFunction',this.mouseMove);
    $('#mapViewCanv').dblclick('someFunction',this.dbclick);
    apiClient.getRegion(this.mapXYCorner[0],this.mapXYCorner[0]+this.width-1,
      this.mapXYCorner[1],this.mapXYCorner[1]+ this.height-1,this.showMapTile);
  };

  this.updateMapGrid = function(){
    mapView.mapGridReady = 0;
    this.selectedTile = null;
  	this.tiles = [];
    for(i = 0 ; i < mapView.width ; i++ ){
      var row = [];
      for(j = 0 ; j < mapView.height ; j++ ){
        row.push([]);
      }
      mapView.tiles.push(row);
    }
    apiClient.getRegion(this.mapXYCorner[0],this.mapXYCorner[0]+this.width-1,
      this.mapXYCorner[1],this.mapXYCorner[1]+ this.height-1,this.showMapTile);
  }

  this.updateTile = function(tileJSON){
    var x = tileJSON.x_coord - mapView.mapXYCorner[0];
    var y = tileJSON.y_coord - mapView.mapXYCorner[1];
    mapView.tiles[x][y] = tileJSON;
    mapView.showMapTile(tileJSON);
    setTimeout(function(){
      mapView.drawTileBorder(tileJSON);
      }, 25);

  }

  this.drawBorders = function(){

    for(i = 0 ; i < mapView.width ; i ++){
      for( j = 0 ; j < mapView.height ; j++){
        mapView.drawTileBorder(mapView.tiles[i][j]);
      }
    }
  }

  this.drawTileBorder = function(tile){
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
    context.globalAlpha = 1;
    context.fillStyle="#00FF00";
    var x = tile.x_coord - mapView.mapXYCorner[0];
    var y = tile.y_coord - mapView.mapXYCorner[1];
    if(tile.id_owner != null){
      if(tile.id_owner == mapView.playerId){
        context.fillStyle="#00FF00";
      }else{
        context.fillStyle="#FF0000";
      }
      if(x>0){
        if(mapView.tiles[x-1][y].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale,scale/10,scale);
        }
      }
      if(x<mapView.width-1){
        if(mapView.tiles[x+1][y].id_owner != tile.id_owner){
          context.fillRect(x*scale+9*scale/10,y*scale,scale/10,scale);
        }
      }
      if(y>0){
        if(mapView.tiles[x][y-1].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale,scale,scale/10);
        }
      }
      if(y<mapView.width-1){
        if(mapView.tiles[x][y+1].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale+9*scale/10,scale,scale/10);
        }
      }
    }
  }

  this.showMapTile = function(tileJSON){
    //mapView.tiles.push(tileJSON);
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
  	var x_coord = tileJSON['x_coord']-mapView.mapXYCorner[0];
  	var y_coord = tileJSON['y_coord']-mapView.mapXYCorner[1];
  	var biome = tileJSON['biome'];
    mapView.tiles[x_coord][y_coord] = tileJSON;

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
    mapView.mapGridReady+=1;
    if(mapView.mapGridReady==mapView.width*mapView.height){
      setTimeout(function(){
        mapView.drawBorders();
      }, 50);
    }
  }

}
