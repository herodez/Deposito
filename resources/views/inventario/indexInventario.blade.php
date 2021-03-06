@extends('panel')
@section('bodytag', 'ng-controller="inventarioController"')
@section('front-page')

	<div data-loading class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
			<li class="nav-active"><span class="glyphicon glyphicon-equalizer"></span> Existencia</a></li>
		</ul>
	</nav>

	<br>
	<br>

	<div class="row">
		<div class="col-md-1">
    		<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>

		<div class="col-md-offset-3 col-md-4" style="padding-top:2%; text-align:center;">
			<table class="table table-striped">
				<tbody>
					<tr>
						<td class="bg-success text-success">Fecha</td>
						<td>{#dateF#}</td>
						<td class="bg-success text-success">Insumos</td>
						<td>{#insumos.length#}</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="col-md-offset-1 col-md-3" style="padding-top:2%; text-align:right;">

			@if(Auth::user()->hasPermissions(['inventory_report']))
					<button class="btn btn-success dropdown-toggle" data-toggle="dropdown" tooltip="Reportes" ng-hide="status">
					<span class="glyphicon glyphicon-list-alt"></span></button>

					<ul class="dropdown-menu" role="menu">
						<li ng-click="parcialInventario()"><a href="#">Inventario parcial</a></li>
						<li><a href="{{route('reporInv', ['report' => 'total'])}}?date={#dateF#}&filter=true" target="_blank">Inventario sin fallas</a></li>
			          	<li><a href="{{route('reporInv', ['report' => 'total'])}}?date={#dateF#}" target="_blank">Inventario total</a></li>
					</ul>

					<span ng-show="status">
						<button ng-class="thereIsSelect() ? 'active':'disabled'"  tooltip="Generar reporte" ng-click="gerenarParcial()"class="btn btn-success"><span class="glyphicon glyphicon-list-alt"></span></button>
						<button ng-click="closeSelect()" class="btn btn-danger" tooltip="Cancelar"><span class="glyphicon glyphicon-remove"></span></button>
					</span>
			@endif

			<span ng-hide="status">
	      <button class="btn btn-success" ng-click="search()" tooltip="Filtrar registros">
	        <span class="glyphicon glyphicon-search"></span></button>

				<button type="button" class="btn btn-success" ng-click="dateSelect()" tooltip="Situar el inventario en una fecha">
	        <span class="glyphicon glyphicon-calendar"></span></button>

				<button type="button" class="btn btn-success" ng-click="move()" tooltip="Insumos con movimientos"><span class="glyphicon glyphicon-transfer"></span></button>
				<button type="button" class="btn btn-success" ng-click="current()" tooltip="Situar inventario en fecha actual"><span class="glyphicon glyphicon-screenshot"></span></button>
			</span>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th ng-show="status"class="col-md-1">
					<label class="checkbox-inline">
					<input type="checkbox" ng-checked="all" ng-model="all" ng-click="select()">
					Todos</label>
				</th>
				<th class="col-md-2">Codigo</th>
				<th>Descripción</th>
				<th class="col-md-1">Exist.</th>
				@if(Auth::user()->hasPermissions(['inventory_kardex']))
					<th class="col-md-1">Kardex</th>
				@endif
			</tr>
		</thead>
		<tbody>
			<tr ng-show="barSearch">
				<td ng-show="status"></td>
				<td>
					<input type="text" class="form-control" placeholder="Codigo" ng-model="busqueda.codigo">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Descripción" ng-model="busqueda.descripcion">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Exist." ng-model="busqueda.existencia">
				</td>
        <td></td>
			</tr>
			<tr ng-click="selectInsumo(insumos.indexOf(insumo))" ng-class="insumo.color" dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro">
				<td ng-show="status">
					<input type="checkbox" ng-checked="insumo.select">
				</td>
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.existencia#}</td>
				@if(Auth::user()->hasPermissions(['inventory_kardex']))
					<td><a class="btn btn-warning btn-sm" href="/inventario/kardex?insumo={#insumo.id#}&dateI={#dateI#}&dateF={#dateF#}" target="_blank">
						<span class="glyphicon glyphicon-eye-open"></span></a>
					</td>
				@endif
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>

		<script type="text/ng-template" id="date.html">
				<div class="modal-header">
						<h3 class="modal-title text-title-modal">
							<span class="glyphicon glyphicon-calendar"></span> Situar inventario
						</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
						<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
						</div>
						<div class="col-md-offset-2 col-md-8">
							<p class="input-group">
								<input type="text" id="fechaI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="fecha" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
										<span class="input-group-btn">
											<button type="button" class="btn btn-success text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
										</span>
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<center>
						<button class="btn btn-success" ng-click="buscar()"><span class="glyphicon glyphicon glyphicon-search"></span> Buscar</button>
						<button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
					</center>
				</div>
		</script>
@endsection
