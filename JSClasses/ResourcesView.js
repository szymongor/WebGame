function ResourcesView(){

  this.showResources = function(resources){
    var resourcesBar = $('#resourcesBar');
    resources = $.map(resources, function(key,val) { return [[val,key]] });
    resourcesBar.empty();
    $.each(resources,function(id,resource){
      //console.log(resources);
      resourcesBar.append('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
    });
  }
}
