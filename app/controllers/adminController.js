/**

 * Created by Timothy on 8/31/2015.

 */

bfApp.controller('adminController', function($stateParams, $state, $scope, inventoryService){

    $scope.imageCropResult = null;

    $scope.showImageCropper = false;

    inventoryService.getInventory().then(function(data){

        $scope.items = data;
        $scope.itemsHolder = data;

    });

    $scope.$watch('imageCropResult', function(newVal) {
        if (newVal) {
            console.log('imageCropResult', newVal);
        }
    });
	$scope.setAdminItem = function(item){
		inventoryService.setAdminItem(item);
	};

    $scope.search = function(value){
        $scope.items = [];
        $scope.itemsHolder.forEach(function(item)
        {
            var name = item.name.toLowerCase();
            value = value.toLowerCase();
            if (name.indexOf(value) > -1)
            {
                $scope.items.push(item);
            }
        })
    };
})

.controller('adminItemController', function($scope, $stateParams, $sce, $state, inventoryService){
        $scope.showMainEdit = true;
        $scope.showDescriptionEdit = false;
        $scope.showImageCropper = false;
        $scope.imageCropResult = null;
        $scope.item = {};
        $scope.item.active = true;
        $scope.description = '<h1>Add a new description!</h1><p>Use this just like a text editor</p>';
        $scope.text2 = '<p><i>This is really simple WYSIWYG for AngularJS!</i></p>';
        $scope.debug = false;
        $scope.types = [{"ID":"1","Type":"banjo"},{"ID":"2","Type":"guitar"},{"ID":"3","Type":"mandolin"},{"ID":"4","Type":"other"}];
        //$scope.item = inventoryService.getAdminItem();

        //Adding a test comment
        if ($stateParams.id !== "new" && $stateParams.id !== "working")
        {
            inventoryService.getAdminImage($stateParams.id).then(function(data){
                $scope.images = data;
                console.log(JSON.stringify(data));
            });
            inventoryService.getItemById($stateParams.id).then(function(data)
            {
                $scope.item = data;
                $scope.description = $sce.trustAsHtml($scope.item.description);
            })
            //$scope.description = $sce.trustAsHtml($scope.item.description);
        }
        else if($stateParams.id === "working")
        {
            $scope.item = inventoryService.getAdminItem();
            $scope.description = $sce.trustAsHtml($scope.item.description);
        }

        $scope.$watch('descrip', function(newVal){


        });

        $scope.save = function(descrip){
            alert(descrip);
            $scope.item.description = descrip;
            $scope.description = $sce.trustAsHtml(descrip);
            $scope.showMainEdit = true;
            $scope.showDescriptionEdit = false;
            $scope.showImageCropper = false;
        };
        $scope.editDescription = function(){
            $scope.descrip = $scope.item.description;
            $scope.showMainEdit = false;
            $scope.showDescriptionEdit = true;
            $scope.showImageCropper = false;
        };
        $scope.update = function(item){
            $scope.showSpinner = true;
            inventoryService.modifyInventory(item).then(function(){
                $scope.showSpinner = false;

                alert("Updated Successfully");
                $state.go('admin.inventory');
            },
            function(){
                $scope.showSpinner = false;
                alert("Something went wrong.  Call your son");
            });
        };
        $scope.create = function(item){
            alert(JSON.stringify(item));
            $scope.showSpinner = true;
            //inventoryService.updateImage(images).then(function(data){});
            inventoryService.modifyInventory(item).then(function(data){
                $scope.showSpinner = false;
                alert("Created Successfully.  ID: " + data);
                $state.go('admin.inventory');
            },
            function(){
                $scope.showSpinner = false;
                alert("Something went wrong.  Call your son");
            });
        };
        $scope.$watch('artistImage', function(newVal) {
            if (newVal) {
                //$scope.item.images.push(newVal);
                var image = {
                    id : $scope.item.id,
                    image : newVal
                };
                inventoryService.createImage(image).then(function(data){
                    var imageModel = {
                        folderId: $scope.item.id,
                        itemId: data,
                        active: true
                    };
                    $scope.images.push(imageModel);
                });
                //console.log('imageCropResult', newVal);
                $scope.showImageCropper = false;
                $scope.showArtistImage = true;
                $scope.showMainEdit = true;
                $scope.showDescriptionEdit = false;
            }

        });
        $scope.updateImage = function(active){
            $scope.showSpinner = true;
            inventoryService.updateImage(images).then(function(data){});
        };
		$scope.imageCropper = function(){
            $scope.imageCropResult = null;
            $scope.imageCropStep=1;
            $scope.showMainEdit = false;
            $scope.showDescriptionEdit = false;
            $scope.showImageCropper = true;
		}
})


.controller('imageTestController', function($scope, $location, inventoryService){

		$scope.showImageCropper = true;

		$scope.imageCropResult = null;

		$scope.item = inventoryService.getAdminItem();
		console.log($scope.item);
		if ($scope.item.images === undefined)

		{

			$scope.item.images = [];

		}

		$scope.$watch('artistImage', function(newVal) {

			if (newVal) {



				$scope.item.images.push(newVal);

				//console.log('imageCropResult', newVal);

				$scope.showImageCropper = false;

				$scope.showArtistImage = true;
				if ($scope.item.id != undefined)
				{
					$location.path('admin/' + $scope.item.id);
				}
				else
				{
					console.log($scope.item);
					$location.path('admin/working');
				}

			}

		});

	}).controller('itemDescriptionController', function($scope, $location, inventoryService){
        $scope.item = inventoryService.getAdminItem();
        $scope.description = $scope.item.description;
        $scope.text1 = '<h1>Lorem ipsum</h1><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Saepe maxime similique, ab voluptate dolorem incidunt, totam dolores illum eum ad quas odit. Magnam rerum doloribus vitae magni quasi molestias repellat.</p><ul><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus tempora explicabo fugit unde maxime alias.</li><li>Numquam, nihil. Fugiat aspernatur suscipit voluptatum dolorum nisi numquam, fugit at, saepe alias assumenda autem.</li><li>Iste dolore sed placeat aperiam alias modi repellat dolorem, temporibus odio adipisci obcaecati, est facere!</li><li>Quas totam itaque voluptatibus dolore ea reprehenderit ut quibusdam, odit beatae aliquam, deleniti unde tempora!</li><li>Rerum quis soluta, necessitatibus. Maxime repudiandae minus at eum, dicta deserunt dignissimos laborum doloribus. Vel.</li></ul><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis enim illum, iure cumque amet. Eos quisquam, nemo voluptates. Minima facilis, recusandae atque ullam illum quae iure impedit nihil dolorum hic?</p><ol><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae ex repudiandae ratione, autem? Nulla voluptatem et soluta dolores facilis reiciendis, porro repudiandae, aperiam commodi minima repellat voluptas dignissimos corrupti itaque.</li><li>Quisquam cupiditate odit voluptatem eum quibusdam modi, facilis.</li><li>Obcaecati molestias quisquam numquam deserunt nobis recusandae perferendis.</li><li>Totam sequi quam omnis fuga, laboriosam suscipit libero.</li></ol><h1>Dolor sit amet</h1><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur repellendus et impedit aspernatur quae fugiat doloribus possimus laborum eveniet atque quisquam quis qui quaerat, eum consectetur, id libero a facilis.</p>';
        $scope.text2 = '<p><i>This is really simple WYSIWYG for AngularJS!</i></p>';
        console.log("inside image description controller");
        console.log($scope);

        $scope.save = function(){
            $scope.item.description = $scope.description;
            inventoryService.setAdminItem($scope.item);
            if ($scope.item.id != undefined)
            {
                $location.path('admin/inventory/' + $scope.item.id);
            }
            else
            {
                console.log($scope.item);
                $location.path('admin/inventory/working');
            }

        }
    }).config(['ngWigToolbarProvider', function (ngWigToolbarProvider) {
        ngWigToolbarProvider.setButtons(['formats', 'list1', 'list2', 'bold', 'italic', 'link']);
        ngWigToolbarProvider.addStandartButton('underline', 'Underline', 'underline', 'fa-underline');
    }])