"use strict";


angular.module('deposito').
controller('usersController',function($scope,$http,$modal){

	$scope.usuarios = [];
  $scope.cRegistro = '5';

	$scope.registrarUser = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registrarUser',
      		windowClass: 'large-Modal',
      		controller: 'registraUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 }
      		}
	    });
	}

	$scope.obtenerUsuarios = function(){

		$http.get('/getUsuarios')
			.success( function(response){$scope.usuarios = response;});
	};

	$scope.editarUsuario = function(index){

	  $modal.open({

			animation: true,
      		templateUrl: '/editarUsuario',
      		windowClass: 'large-Modal',
      		controller: 'editarUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.elimUsuario = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/eliminarUsuario',
          controller: 'elimUsuarioCtrl',
          resolve: {
             obtenerUsuarios: function () {
                return $scope.obtenerUsuarios;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerUsuarios();

});

angular.module('deposito').controller('registraUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios) {

  $scope.btnVisivilidad = true;
  $scope.depositos = [];
	$scope.roles = [];
	$scope.alert = false;

  $http.get('/depositos/getDepositos')
      .success( function(response){$scope.depositos = response;});

	$http.get('/roles/all')
      .success( function(response){$scope.roles = response;});

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
  	$scope.alert = false;
  };

  $scope.save = function(){

 	$http.post('/registrarUsuario',$scope.data)
 		.success(function(response){
 			$scope.alert = {"type":response.status , "msg":response.menssage};
      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
      obtenerUsuarios();
 	});
 };

});

angular.module('deposito').controller('editarUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;
  $scope.data  = {};
  $scope.depositos = [];
	$scope.roles = [];

  $http.get('/depositos/getDepositos')
      .success( function(response){
					$scope.depositos = response;

					$http.get('/getUsuario/' + id)
				    .success(function(response){
				      $scope.data = response.usuario;
							$scope.data.deposito =  String(response.usuario.deposito);
							$scope.data.rol = String(response.usuario.rol);
				  });
	 });

	$http.get('/roles/all')
			.success( function(response){$scope.roles = response;});

  $scope.modificar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
  	$scope.alert = false;
  };

	$scope.save = function(){
		$http.post('/editarUsuario/' + id ,$scope.data)
			.success(function(response){
				$scope.alert = {"type":response.status , "msg":response.menssage};

	    if( response.status == "success"){
				$scope.btnVisivilidad = false;
		    obtenerUsuarios();
			}

		});
	};

});

angular.module('deposito').controller('elimUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

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

  $http.post('/eliminarUsuario/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});

      $scope.btnVisivilidad = ( response.status == "success") ? false : true;

      obtenerUsuarios();
  });

 };

});
