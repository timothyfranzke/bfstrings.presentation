bfApp.controller('homeController', function($scope,inventoryService){
    $scope.options = {
        loop: true,
        autoplay: true,
        width:'1500'
    };

    inventoryService.getImage(1).then(function(data){
        $scope.images = data;
        for(var i = 1; i <= numberOfImages; i++)
        {
            var img = {};
            img.active = "1",
                img.id = i;

            img.thumb = 'img/slider/' + i + '.jpg';
            img.full = 'img/slider/' + i + '.jpg';
            img.img = 'img/slider/' + i + '.jpg';
            $scope.images.push(img);
        }
    });

});