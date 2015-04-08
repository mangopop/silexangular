var App = angular.module('App', ['ngResource']);

App.factory('Message', ['$resource', function($resource) {
    return $resource('http://192.168.56.101/silexangular/web/index.php/resource/:id');
        //{'get': {method: 'GET'}});
}]);

//App.factory("GET", function($resource) {
//    return $resource('http://ecommerce.dev/index.php/resource/:id');
//    //return $resource("http://awesome.dev/angular/products");
//});

App.controller('messagesCtrl', ['$scope','$resource', 'Message', function ($scope, $resource, Message) {

    //var Message = $resource('/index.php/resource',
    //    {
    //        query: {
    //            method: 'GET'
    //        }
    //    }
    //);

    var currentResource;
    var resetForm = function () {
        $scope.addMode = true;
        $scope.author = undefined;
        $scope.message = undefined;
        $scope.selectedIndex = undefined;
    };

    $scope.messages = Message.query();
    $scope.addMode = true;

    $scope.add = function () {
        var key = {};
        var value = {author: $scope.author, message: $scope.message};

        //can pass the key and value which get sent and data repsonse is returned which is then saved to scope and displayed.
        Message.save(key, value, function (data) {
            $scope.messages.push(data);
            resetForm();
        });
    };

    $scope.update = function () {
        var key = {id: currentResource.id}; //get from selectMessage index
        var value = {author: $scope.author, message: $scope.message};
        Message.save(key, value, function (data) {
            currentResource.author = data.author;
            currentResource.message = data.message;
            resetForm();
        });
    };

    $scope.refresh = function () {
        $scope.messages = Message.query();
        resetForm();
    };

    $scope.deleteMessage = function (index, id) {
        Message.delete({id: id}, function () {
            $scope.messages.splice(index, 1);
            resetForm();
        });
    };

    $scope.selectMessage = function (index) {
        currentResource = $scope.messages[index];
        $scope.addMode = false;
        $scope.author = currentResource.author;
        $scope.message = currentResource.message;
    };

    $scope.cancel = function () {
        resetForm();
    };
}]);