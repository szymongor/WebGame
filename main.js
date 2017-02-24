var apiClient = new ApiClient('http://localhost');
var mapView = new MapView(10,10,4,4,12,apiClient);
var detailView = new DetailView();
var resourcesView = new ResourcesView();
var idPlayer = apiClient.getPlayerId();

var armyData = {'Army':{"Swordman":4,"Bowman":3,"Wizard":2}};

document.addEventListener("tileSelectDbClick", function(e) {
	detailView.openDetailView(e,"Building");
	//console.log(e.detail); // Prints selected x and y
});

document.addEventListener("tileSelectOneClick", function(e) {
	detailView.openDetailView(e, "Map");
	//console.log(e.detail); // Prints selected x and y
});

$( document ).ready(function(){
	showResources();
	showMap();
	detailView.initDetailsView();
});

function showMap(){
	mapView.showMapGrid();
}

function showResources(){
	apiClient.getPlayerResources(resourcesView.updatePlayersResources);
	apiClient.getPlayerResourcesIncome(resourcesView.updatePlayersResourcesIncome);
	apiClient.getPlayerResourcesCapacity(resourcesView.updatePlayersResourcesCapacity);

}
