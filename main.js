var resources=1;

function getResources(){
	
	var $resourcesBar = $('#resourcesBar');
	
	$.ajax({
		type: 'GET',
		url: 'http://localhost/reg/api/resources.php',
		success: function(data){
			resources = $.parseJSON(data);
			resources = $.map(resources, function(key,val) { return [[val,key]] });
			$resourcesBar.empty();
			$.each(resources,function(id,resource){
				console.log('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
				$resourcesBar.append('<div class="gameResource">'+resource[0]+':'+resource[1]+'</div>');
			});
		}
		
	
	});
};

function getLastUpdateTime(){
	
	$.ajax({
		type: 'PUT',
		url: 'http://localhost/reg/api/resources.php',
		success: function(data){
			console.log(data);
		}		
	
	});
};
