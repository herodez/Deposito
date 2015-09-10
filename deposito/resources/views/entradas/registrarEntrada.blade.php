@extends('panel')
@section('bodytag', 'ng-controller="registroEntradaController"')
@section('addscript')
<script src="{{asset('js/registroEntradaController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada
	</h5>

	<center>
		<h3 class="text-title-modal">Registro de Pro-Forma de entrada</h3>
	</center>
	
	<br>

	<div class="row">
		<div class="col-md-3">
			<input class="form-control" type="text" placeholder="Orden de Compra N°" ng-model="codigo">
		</div>
	</div>

	<br>
	<br>

	<div class="row">
		<div class="form-group col-md-3 text-title-modal">
  			<select class="form-control" id="provedor" ng-model="provedor">
  				<option value="" selected disabled>Proveedor</option>
  				<option value="">Sefar</option>
  			</select>
		</div>

		<div class="col-md-4 col-md-offset-1">

			<div class="input-group"> 
				<input class="form-control" type="text" placeholder="Codigo de Insumo" ng-model="codigoInsumo">	
				<div class="input-group-btn">
				    <button class="btn btn-success" ng-click="agregarInsumos()">Agregar</button>
				</div>
			</div>
		</div>
	</div>
	
	<br>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Descripción</th>
				<th>Cantidad</th>
				<th>Eliminar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="insumo in insumos">
				<td class="col-md-2">{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td class="col-md-2">
					<input class="form-control" type="text" ng-model="insumo.cantidad">
				</td>
				<td class="col-md-1">
					<button class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	
	<br>

	<center>
		<button class="btn btn-success">Registar</button>
	</center>	

@endsection