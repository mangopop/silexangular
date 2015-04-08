var App = angular.module('App', ['ngResource']);

App.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('%');
    $interpolateProvider.endSymbol('%');
});

App.factory("GET", function($resource) {
    return $resource("http://awesome.dev/angular/products");
});

App.controller('productsCtrl', ['$scope','$resource', function ($scope, $resource) {
    $scope.cheese = 'Smelly';
    $scope.shopProducts = [];
    $scope.friends = [
        {name:'John', age:25, gender:'boy'},
        {name:'Jessie', age:30, gender:'girl'},
        {name:'Johanna', age:28, gender:'girl'},
        {name:'Joy', age:15, gender:'girl'},
        {name:'Mary', age:28, gender:'girl'},
        {name:'Peter', age:95, gender:'boy'},
        {name:'Sebastian', age:50, gender:'boy'},
        {name:'Erika', age:27, gender:'girl'},
        {name:'Patrick', age:40, gender:'boy'},
        {name:'Samantha', age:60, gender:'girl'}
    ];

    var Products = $resource('/angular/products',
        { query: {method: 'GET'}}
    );

    //var result = Products.query().$promise.then(function(result) {
    //    console.log(result);
    //});

    var result = Products.query(function(result) {
        //console.log(result[1]);
        $scope.shopProducts = result;
        _.each(result, function(element,index,list) {
            console.log(element);
        })
    });

    //example
    //var src = $resource('api/1/todo/:id:cmd',
    //    {id: "@id", cmd: "@cmd"}, //parameters default
    //    {
    //        ListTodos: { method: "GET", params: {} },
    //        GetTodo: { method: "GET", params: { id: 0 } },
    //        CreateTodo: { method: "POST", params: { content: "", order: 0, done: false } },
    //        UpdateTodo: { method: "PATCH", params: { /*...*/ } },
    //        DeleteTodo: { method: "DELETE", params: { id: 0 } },
    //        ResetTodos: { method: "GET", params: { cmd: "reset" } },
    //    });

    ///console.log(result);
}]);

App.controller('productCtrl', ['$scope','$resource', function ($scope, $resource) {
    $scope.product = [];

    var Product = $resource('/angular/product/:id',
        { query: {method: 'GET'}}
    );

    var result = Product.query(function(result) {
        //console.log(result[1]);
        $scope.product = result;

    });
}]);



