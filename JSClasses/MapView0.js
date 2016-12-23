function MapView(width, height, xCoord, yCoord, playerId, apiClient){
  this.width = width;
  this.height = height;
  this.mapXYCorner = [xCoord-Math.floor(width/2),yCoord-Math.floor(height/2)];
  this.selectedTile = null;
  this.tiles = [];
  this.apiClient = apiClient;
  this.mapGridReady = 0;
  this.playerId = playerId;
  var mv = this;
  var scale = 25;
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
    return mv.selectedTile;
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
    mv.mapCornerVec = mv.mapXYCorner;
    mv.mousePosition = [x,y];
  }

  this.mouseUp = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var selectedTileNow =  mv.tiles[x][y];
    if(mv.mousePosition[0] == x && mv.mousePosition[1] == y){
      if(mv.selectedTile != null){
        if(mv.selectedTile.x_coord - mv.mapXYCorner[0] == x && mv.selectedTile.y_coord - mv.mapXYCorner[1]  == y){
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
      var moveVector = [x-mv.mousePosition[0],y-mv.mousePosition[1]]
      var newMapCorener =  [mv.mapXYCorner[0] - moveVector[0],mv.mapXYCorner[1] - moveVector[1]];
      mv.mapXYCorner = newMapCorener
      mv.updateMapGrid();
      mv.drawBorders();
    }
    mv.mousePosition = null;
    mv.mapCornerVec = mv.mapXYCorner;
  }

  this.dbclick = function(event){
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var selectedTile = mv.tiles[x][y];
    //this.selectedTile = selctedTile;
    var event = new CustomEvent("tileSelect", { "detail":selectedTile });
    document.dispatchEvent(event);

    //document.addEventListener("name-of-event", function(e) {
    //  console.log(e.detail); // Prints selected x and y
    //});

  }

  this.mouseMove = function(event){
    if(mv.mousePosition == null){
      return;
    }
    canvas=document.getElementById("mapViewCanv");
    var rect = canvas.getBoundingClientRect();
    var x = Math.floor(event.clientX - rect.left);
    var y = Math.floor(event.clientY - rect.top);
    var x = Math.floor(x/scale);
    var y = Math.floor(y/scale);
    var moveVector = [x-mv.mousePosition[0], y-mv.mousePosition[1]];
    var newMapCorener =  [mv.mapCornerVec[0] - moveVector[0], mv.mapCornerVec[1] - moveVector[1]];
    //console.log(newMapCorener);
    if(newMapCorener[0] != mv.mapXYCorner[0] || newMapCorener[1] != mv.mapXYCorner[1]){
      mv.mapXYCorner = newMapCorener;
      mv.updateMapGrid();
    }
  }

  this.selectTile = function(event){}

  this.showMapGrid = function(){
    mv.mapGridReady = 0;
    this.selectedTile = null;
  	this.tiles = [];
    for(i = 0 ; i < mv.width ; i++ ){
      var row = [];
      for(j = 0 ; j < mv.height ; j++ ){
        row.push([]);
      }
      mv.tiles.push(row);
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
    mv.mapGridReady = 0;
    this.selectedTile = null;
  	this.tiles = [];
    for(i = 0 ; i < mv.width ; i++ ){
      var row = [];
      for(j = 0 ; j < mv.height ; j++ ){
        row.push([]);
      }
      mv.tiles.push(row);
    }
    apiClient.getRegion(this.mapXYCorner[0],this.mapXYCorner[0]+this.width-1,
      this.mapXYCorner[1],this.mapXYCorner[1]+ this.height-1,this.showMapTile);
  }

  this.updateTile = function(tileJSON){
    var x = tileJSON.x_coord - mv.mapXYCorner[0];
    var y = tileJSON.y_coord - mv.mapXYCorner[1];
    console.log(mv.tiles[x][y]);
    mv.tiles[x][y] = tileJSON;
    mv.showMapTile(tileJSON);
    setTimeout(function(){
      mv.drawTileBorder(tileJSON);
    }, 1);

  }

  this.drawBorders = function(){

    for(i = 0 ; i < mv.width ; i ++){
      for( j = 0 ; j < mv.height ; j++){
        //console.log(mv.tiles[i][j]);
        mv.drawTileBorder(mv.tiles[i][j]);
      }
    }
  }

  this.drawTileBorder = function(tile){
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
    context.globalAlpha = 1;
    context.fillStyle="#00FF00";
    var x = tile.x_coord - mv.mapXYCorner[0];
    var y = tile.y_coord - mv.mapXYCorner[1];
    if(tile.id_owner != null){
      if(tile.id_owner == mv.playerId){
        context.fillStyle="#00FF00";
      }else{
        context.fillStyle="#FF0000";
      }
      if(x>0){
        if(mv.tiles[x-1][y].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale,scale/10,scale);
        }
      }
      if(x<mv.width-1){
        if(mv.tiles[x+1][y].id_owner != tile.id_owner){
          context.fillRect(x*scale+9*scale/10,y*scale,scale/10,scale);
        }
      }
      if(y>0){
        if(mv.tiles[x][y-1].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale,scale,scale/10);
        }
      }
      if(y<mv.width-1){
        if(mv.tiles[x][y+1].id_owner != tile.id_owner){
          context.fillRect(x*scale,y*scale+9*scale/10,scale,scale/10);
        }
      }
    }
  }

  this.showMapTile = function(tileJSON){
    //mv.tiles.push(tileJSON);

    //console.log(tileJSON);
    var canvas=document.getElementById("mapViewCanv");
    var context=canvas.getContext('2d');
  	var x_coord = tileJSON['x_coord']-mv.mapXYCorner[0];
  	var y_coord = tileJSON['y_coord']-mv.mapXYCorner[1];
  	var biome = tileJSON['biome'];
    mv.tiles[x_coord][y_coord] = tileJSON;

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
