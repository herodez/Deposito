"use strict";


angular.module('deposito').
controller('provedoresController',function($scope,$http,$modal){

	$scope.provedores = [];
  $scope.cRegistro = '5';

	$scope.registraProvedor = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registraProvedor',
      		size:'lg',
      		controller: 'registraProvedorCtrl',
      		resolve: {
       			 obtenerProvedores: function () {
          			return $scope.obtenerProvedores;
        		 }
      		}
	    });
	}

	$scope.obtenerProvedores = function(){

		$http.get('/getProvedores')
			.success( function(response){$scope.provedores = response});
	};


	$scope.editarProvedor = function(index){

		$modal.open({

			animation: true,
      		templateUrl: '/editarProvedor',
      		size:'lg',
      		controller: 'editarProvedorCtrl',
      		resolve: {
       			 obtenerProvedores: function () {
          			return $scope.obtenerProvedores;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };


  $scope.elimProvedor = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/elimProvedor',
          controller: 'elimProvedorCtrl',
          resolve: {
             obtenerProvedores: function () {
                return $scope.obtenerProvedores;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerProvedores();

});

angular.module('deposito').controller('registraProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores) {

  $scope.btnVisivilidad = true;
  $scope.rif = '';
  $scope.nombre = '';

  $scope.registrar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {
		'rif'		:  $scope.rif,
		'nombre'	:  $scope.nombre
 	};


 	$http.post('/registraProvedor',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerProvedores();

 	});
 };

});


angular.module('deposito').controller('editarProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores,id) {

  $scope.btnVisivilidad = true;
  $scope.rif = '';
  $scope.nombre = '';
 
  $http.get('/getProvedor/' + id)
    .success(function(response){

        $scope.rif       = response.rif;
        $scope.nombre    = response.nombre;
  });


  $scope.modificar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {
		'nombre'	:  $scope.nombre,
 	};

 	$http.post('/editProvedor/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerProvedores();

 	});
 };

});

angular.module('deposito').controller('elimProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/elimProvedor/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerProvedores();
  });

 };

});