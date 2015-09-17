"use strict";

angular.module('deposito').
controller('salidasController',function($scope,$http,$modal){

	$scope.salidas = [];
  $scope.indice = 'Pro-Formas';
  $scope.salidasInsumos = [];
  $scope.cRegistro = '5';
  $scope.status = true;

	$scope.obtenerSalidas = function(){

		$http.get('/getSalidas')
			.success( function(response){$scope.salidas = response});
	};

  $scope.obtenerSalidasInsumos = function(){

    $http.get('/getInsumosSalidas')
      .success( function(response){$scope.salidasInsumos = response});
  };

  $scope.registrosProformas = function(){
    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
    $scope.status = true;
    $scope.obtenerSalidas();
  };

  $scope.registrosInsumos = function(){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
    $scope.status = false;
    $scope.obtenerSalidasInsumos();
  };

  $scope.localizarSalida = function(entrada){
    $scope.indice = 'Pro-Formas';
    $scope.status = true;
    $scope.busqueda = entrada;
  };

  $scope.detallesSalida = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesSalida',
          controller: 'detallesSalidaCtrl',
          size:'lg',
          resolve: {

             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerSalidas();

});

angular.module('deposito').controller('detallesSalidaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.salidas = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };


});
