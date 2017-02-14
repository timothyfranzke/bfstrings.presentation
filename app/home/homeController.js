bfApp.controller('homeController', function($scope, $http, inventoryService){
    $scope.imageConfig = {};
    var request = {
        url: 'img/imageConfiguration.json',
        method: 'GET'
    };
    $http(request)
        .success(function (data) {
            $scope.imageConfig = data;
        });
    $scope.options = {
        loop: true,
        autoplay: true,
        width:'1500'
    };
    $scope.images = [];
    for(var i = 1; i <= numberOfImages; i++)
    {
        console.log("images : " + i );

        var img = {
            'thumb':'img/slider/' + i + '.jpg',
            'full':'img/slider/' + i + '.jpg',
            'img':'img/slider/' + i + '.jpg',
            'id':i,
            'active':'1'
        };
        console.log(JSON.stringify(img));

        $scope.images.push(img);
    }
    console.log($scope.images);
});