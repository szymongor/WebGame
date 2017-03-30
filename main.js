var apiClient = new ApiClient('http://localhost');
var detailView = new DetailView();
var resourcesView = new ResourcesView();
var playerInfo;
var mapView;
apiClient.getPlayerInfo();

var armyData = {'Army':{"Swordman":5,"Bowman":15}};

document.addEventListener("tileSelectDbClick", function(e) {
	detailView.openDetailView(e,"Building");
	//console.log(e.detail); // Prints selected x and y
});

document.addEventListener("tileSelectOneClick", function(e) {
	detailView.openDetailView(e, "Map");
	//console.log(e.detail); // Prints selected x and y
});

function startGame(){
	showResources();
	showMap();
	detailView.initDetailsView();
}

$( document ).ready(function(){
	//showResources();
	//showMap();
	//detailView.initDetailsView();
});

function showMap(){
	mapView.showMapGrid();
}

function showResources(){
	apiClient.getPlayerResources(resourcesView.updatePlayersResources);
	apiClient.getPlayerResourcesIncome(resourcesView.updatePlayersResourcesIncome);
	apiClient.getPlayerResourcesCapacity(resourcesView.updatePlayersResourcesCapacity);

}
