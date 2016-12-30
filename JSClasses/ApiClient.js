function ApiClient(server){
  this.server = server;

  this.getPlayerId = function(){
    var id;
  	$.ajax({
  		type: 'GET',
      async: false,
  		url: this.server+'/reg/api/playerId.php',
  		success: function(data){
  				id = ($.parseJSON(data))['id'];
  		}
  	});
    return id;
  };

  this.getPlayerResources = function(method){
    $.ajax({
  		type: 'GET',
  		url: '/reg/api/resources.php/Resources',
  		success: function(data){
  			resources = $.parseJSON(data);
  			method(resources);
  			}
  		});
  }

  this.getTile = function(x,y,method){
    $.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/map.php/tile/?x='+x+'&y='+y,
  		success: function(data){
  				method($.parseJSON(data));
  		}
  	});
  }

  this.getRegion = function(xFrom,xTo,yFrom,yTo,method){
    $.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/map.php/region/?xFrom='+xFrom+'&xTo='+xTo+'&yFrom='+yFrom+'&yTo='+yTo,
  		success: function(data){
  				var region = $.parseJSON(data);
  				$.each(region, function(i,row){
  					$.each(row, function(j,value){
  						method(value);
  					});
  				});
  		}
  	});
  }

  this.getBuildingDetails = function(x,y,method,element){
    $.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/map.php/building/?x='+x+'&y='+y,
  		success: function(data){
  				method($.parseJSON(data),element);
  		}
  	});
  }

  this.getBuildingsToBuild = function(method){
    $.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/building.php/buildingList',
  		success: function(data){
  				var buildingList = $.parseJSON(data);
  				$.each(buildingList, function(i,value){
  					method(value);
  				});
  		}
  	});
  }

  this.build = function(x,y,buildingType,method){
  	$.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/building.php/build/?x='+x+'&y='+y+'&BuildingType='+buildingType,
  		success: function(data){
  				var result = $.parseJSON(data);
  				if(result.hasOwnProperty('x_coord')){
  					method(result);
  				}
  		}
  	});
  }

  this.conquer = function(x,y,method){
    $.ajax({
			type: 'GET',
			url: this.server+'/reg/api/conquer.php?x='+x+'&y='+y,
			success: function(data){
				var newRegionTiles = $.parseJSON(data);
				$.each(newRegionTiles, function(i,row){
					$.each(row, function(j,value){
						method(value);
					});
				});
			}
		});

  }

}
