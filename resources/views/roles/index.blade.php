@extends('panel')
@section('bodytag', 'ng-controller="rolesController"')
@section('front-page')

	<div data-loading class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-cog"></span> Administració</li>
			<li class="nav-active"><span class="glyphicon glyphicon-compressed"></span> Roles</li>
		</ul>
	</nav>
	<br>

	@if( Auth::user()->hasPermissions(['roles_register']) )
		<button class="btn btn-success" ng-click="registrarRol()"><span class="glyphicon glyphicon-plus"></span> Nuevo Rol</button>
	@endif

	<br>
	<br>
	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
    		<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-10">Nombre</th>
				@if( Auth::user()->hasPermissions(['roles_edit', 'roles_delete'], true))
					<th colspan="2" class="table-edit">Modificaciones</th>
				@elseif( Auth::user()->hasPermissions(['roles_edit', 'roles_delete']))
					<th class="table-edit">Modificaciones</th>
				@endif
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="rol in roles | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#rol.nombre | capitalize#}</td>
				@if( Auth::user()->hasPermissions(['roles_edit']))
					<td class="table-edit"><button class="btn btn-warning" ng-click="editarRol(rol.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				@endif
				@if( Auth::user()->hasPermissions(['roles_delete']))
					<td class="table-edit"><button class="btn btn-danger" ng-click="eliminarRol(rol.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
				@endif
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>
@endsection
