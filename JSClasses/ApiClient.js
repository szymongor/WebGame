function ApiClient(server){
  this.server = server;

  this.getPlayerInfo = function(){
  	$.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/playerInfo.php/info',
  		success: function(data){
          playerInfo = $.parseJSON(data);
          mapView = new MapView(10,10,playerInfo['location']['x'],playerInfo['location']['y'],playerInfo['id'],apiClient);
          startGame();
  		}
  	});
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

  this.getPlayerResourcesIncome = function(method){
    $.ajax({
  		type: 'GET',
  		url: '/reg/api/resources.php/ResourcesIncome',
  		success: function(data){
  			resources = $.parseJSON(data);
  			method(resources);
  			}
  		});
  }

  this.getPlayerResourcesCapacity = function(method){
    $.ajax({
  		type: 'GET',
  		url: '/reg/api/resources.php/ResourcesCapacity',
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

  this.getBuildingsFunctions = function(x,y,functionType,method){
  	$.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/building.php/buildingFunctions/?x='+x+'&y='+y,
  		success: function(data){
  				var result = $.parseJSON(data);
  				if(result != "Not owned" ){
            method(x,y,result[0], functionType);
          }

  		}
  	});
  }

  this.makeBuildingTask = function(x,y,type,amount){
    $.ajax({
  		type: 'GET',
  		url: this.server+'/reg/api/building.php/addTask/?x='+x+'&y='+y+'&Task='+type+'&Amount='+amount,
  		success: function(data){
        //console.log(data);
  		}
  	});
  }

  this.conquerTile = function(x,y,armyData,method){
    $.ajax({
			type: 'POST',
			url: this.server+'/reg/api/army.php/conquer/?x='+x+'&y='+y,
      data: armyData,
			success: function(data){
				console.log(data);
			}
		});

  }

  this.attackTile = function(x,y,armyData,metohd){
    $.ajax({
			type: 'POST',
			url: this.server+'/reg/api/army.php/attack/?x='+x+'&y='+y,
      data: armyData,
			success: function(data){
				console.log(data);
			}
		});
  }

  this.addArmyToTile = function(x,y,armyData,metohd){
    $.ajax({
			type: 'POST',
			url: this.server+'/reg/api/army.php/addArmy/?x='+x+'&y='+y,
      data: armyData,
			success: function(data){
				console.log(data);
			}
		});
  }


}
