(function (angular) {
        "use strict";
        //Controlador Padrao
        var app = angular.module('produtoController',['ui.bootstrap']);
        app.filter('startFrom', function () {
            return function (input, start) {
                if (input) {
                    start = +start; //parse to int
                    return input.slice(start);
                }
                return [];
            }
        });
        app.controller('produtoCtl', [
            '$scope',
            '$http',
            '$log',
            '$timeout',
            function ($scope, $http, $log, $timeout) {
                $scope.list =  [
                            {"nome":"John", "descricao":"Doe", "minimo":"4", "maximo":"10"},
                            {"nome":"maria", "descricao":"Ddsdsdsdsdoe", "minimo":"2", "maximo":"40"},
                            
                        ] ;
                $scope.currentPage = 1; //current page
                $scope.entryLimit = 20; //max no of items to display in a page
                $scope.filteredItems = $scope.list.length; //Initially for no filter  
                $scope.totalItems = $scope.list.length;
 
                $scope.listar = true;

                $scope.produto = {};
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
                $scope.cadastrarProduto = function () {

                    $scope.listar = false;
                };
                $scope.deletarProduto = function (usuario) {
                    if (!confirm("Deseja excluir " + produto.nome.toUpperCase())) {
                        return;
                    }
                    $http({
                        method: 'POST',
                        url: "<?= base_url('produto/deletar'); ?>",
                        data: "usuario=" + JSON.stringify(usuario),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).
                            success(function (data, status, headers, config) {

                                if (data.error) {
                                    $scope.error = data.message;
                                } else {

                                    for (var i = 0; i < $scope.list.length; i++) {

                                        if ($scope.list[i].id == data.id) {

                                            $scope.list.splice(i, 1);
                                        }
                                    }
                                    $scope.success = "Exclusão Efetuada";
                                     
                                }


                            }).
                            error(function (data, status, headers, config) {

                            });
                };
            }]


                );
    })(angular)