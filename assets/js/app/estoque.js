(function (angular) {
    "use strict";
    //Controlador Padrao
    var app = angular.module('estoqueController', ['ui.bootstrap']);





    app.filter('startFrom', function () {
        return function (input, start) {
            if (input) {
                start = +start; //parse to int
                return input.slice(start);
            }
            return [];
        }
    });




    app.controller('estoqueCtl', [
        '$scope',
        '$http',
        '$timeout',
        function ($scope, $http, $timeout) {


            var serviceBase = window.location.origin + '/ged/assets/js/app/api/v1';
            $http.get(serviceBase + '/estoque').then(function (results) {
                $scope.list = results.data;
                $scope.filteredItems = $scope.list.length; //Initially for no filter  
                $scope.totalItems = $scope.list.length;

            });


            $scope.currentPage = 1; //current page
            $scope.entryLimit = 20; //max no of items to display in a page


            $scope.listar = true;

            $scope.estoque = {};
            $scope.setPage = function (pageNo) {
                $scope.currentPage = pageNo;
            };
            $scope.filter = function () {
                $timeout(function () {
                    $scope.filteredItems = $scope.filtered.length;
                }, 10);
            };
            $scope.sort_by = function (predicate) {
                $scope.predicate = predicate;
                $scope.reverse = !$scope.reverse;
            };
            $scope.cadastrarEstoque = function () {

                $scope.listar = false;
            };


           

        }]


            );
})(angular)