@extends('panel')
@section('bodytag', 'ng-controller="inventarioController"')
@section('addscript')
<script src="{{asset('js/inventarioController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-th"></span> Inventario
	</h5>
	<br>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th>Descripcion</th>
				<th class="col-md-2">Existencia en Unidades</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:2">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.existencia#}</td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>

@endsection
