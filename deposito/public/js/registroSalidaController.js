"use strict";

angular.module('deposito').
controller('registroSalidaController',function($scope,$http,$modal){

  $scope.codigoInsumo = '';
  $scope.departamentos = [];
  $scope.servicio = '';
  $scope.insumos = [];
  $scope.alert = {};
  
  getDepartamentos();
  
  $scope.agregarInsumos = function(){

    if( $scope.codigoInsumo  == ''){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.codigoInsumo) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta salida"};
      return; 
    }

    $http.get('/getInsumoCode/' + $scope.codigoInsumo)
      .success(

        function(response){
          if( response.status == 'danger'){
            $scope.alert = {"type":response.status , "msg":response.menssage};
          }
          else{
            $scope.insumos.push(response);
            $scope.codigoInsumo = '';
          }
        }
      );
  }

  $scope.registroEntrada = function(){

    var $data = {
      'departamento': $scope.servicio,
      'insumos'     : empaquetaData()
    };

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique valores validos para cada insumo"};
      return;
    }

    $http.post('/registrarSalida', $data)
      .success( 
        function(response){
  
          if(response.status == 'unexist'){

            marcaInsumos(response.data);
            $scope.alert = {type:'danger', msg:'La cantidad de los insumos marcados son insuficientes'};
            return;
          }

          if( response.status == 'success'){
            
            $modal.open({
                animation: true,
                templateUrl: 'successRegister.html',
                controller: 'successRegisterCtrl',
                resolve: {
                  response: function () {
                    return response;
                  }
                }
            });

            restablecer();
            return;
          }

          $scope.alert = {type:response.status , msg: response.menssage};
        }
      );
  }

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  function insumoExist(codigo){

    var index;

    for(index in $scope.insumos){
      if($scope.insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(){
    var index;

    for( index in $scope.insumos){
      if( !$scope.insumos[index].despachado || $scope.insumos[index].despachado < 0 || 
          !Number.isInteger($scope.insumos[index].despachado) || 
          $scope.insumos[index].solicitado < $scope.insumos[index].despachado)
        return false;
    }

    return true;
  }

  function getDepartamentos(){

    $http.get('/getDepartamentos')
      .success( function(response){ $scope.departamentos = response;});
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'solicitado':$scope.insumos[index].solicitado, 
        'despachado':$scope.insumos[index].despachado});
    }

    return insumos;
  }

  function marcaInsumos(ids){
    var index;
    var id;

    for(index in $scope.insumos){
      $scope.insumos[index].style = '';
    }

    for( id in ids){
      for(index = 0; index < $scope.insumos.length; index++)

        if($scope.insumos[index].id == ids[id] ){
          $scope.insumos[index].style = 'danger';
          break;
        }
    }
  }

  function restablecer(){
    $scope.insumos  = [];
    $scope.servicio = '';
    $scope.alert = {};
  }

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});