function ResourcesView(){

  this.playersResources = {"Wood":0,"Stone":0,"Iron":0,"Food":0};
  this.playersResourcesIncome = {"Wood":0,"Stone":0,"Iron":0,"Food":0};
  this.playersResourcesCapacity = {"Wood":0,"Stone":0,"Iron":0,"Food":0};

  this.log = function(){
    $.each(this.playersResources, function(type,value){
      console.log(type+":"+value);
    })
  }

  this.updatePlayersResources = function(resources){
    $.each(resourcesView.playersResources,function(type,value){
      resourcesView.playersResources[type] = resources[type];
    })
    resourcesView.showResources();
  }

  this.updatePlayersResourcesIncome = function(resourcesIncome){
    $.each(resourcesView.playersResourcesIncome,function(type,value){
      resourcesView.playersResourcesIncome[type] = resources[type];
    })
    resourcesView.showResources();
  }

  this.updatePlayersResourcesCapacity = function(resourcesCapacity){
    $.each(resourcesView.playersResourcesCapacity,function(type,value){
      resourcesView.playersResourcesCapacity[type] = resources[type];
    })
    resourcesView.showResources();
  }

  this.showResources = function(){
    var resourcesBar = $('#resourcesBar');
    //resources = $.map(resources, function(key,val) { return [[val,key]] });
    resourcesBar.empty();
    $.each(resourcesView.playersResources,function(type,value){
      var income = resourcesView.playersResourcesIncome[type];
      var capacity = resourcesView.playersResourcesCapacity[type];
      resourcesBar.append('<div class="gameResource">'+type+':'+value+"/"+capacity+"|+"+income+'</div>');
    });
  }
}
