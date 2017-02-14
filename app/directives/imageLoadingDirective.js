bfApp.directive('imageOnLoad', function(){
    return{
        restrict:'A',
        link: function(scope, element, attrs) {
            scope.imgLoad = false;
            var image = element[0].querySelectorAll("img")[0];
            console.log(element);
            console.log(image);
            image.addEventListener('load', function () {
                console.log("LOADED");
                scope.imgLoad = true;
            });
        }
    }
});