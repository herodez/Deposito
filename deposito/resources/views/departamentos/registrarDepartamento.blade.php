@extends('departamentos.indexDepartamentos')
@section('addscript')
	@parent
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endsection
@section('conten')
<div>
	<h3 style="color:#54AF54;">Nuevo Departamento</h3>
	<hr>

	<form method="POST" action="/registrarDepartamento" enctype="multipart/form-data">
  		
  		{!! csrf_field() !!}

  		@if($errors->has())
  			<div class="alert alert-danger">
  				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  				{{$errors->first()}}
  			</div>
  		@endif

  		@if( isset($success) )
  			<div class="alert alert-success">
  				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  				{{$success}}
  			</div>
  		@endif

		<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<input class="form-control" type="text" placeholder="Nombre" ng-model="nombre" name="nombre">
			</div>

			<div class="col-md-4">
				<input class="form-control" type="text" placeholder="División" ng-model="division" name="division">
			</div>
		</div>
		
		<br><br>

		<div class="row">
			<center>
				<div class="col-md-4 col-md-offset-2">
					<input ngf-select ng-model="sello" type="file" name="sello"/>
				</div>

				<div class="col-md-4">
					<input ngf-select ng-model="firma" type="file" name="firma">
				</div>
			</center>
		</div>
		
		<br><br>

		<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<img ng-show="sello[0] != null" ngf-src="sello[0]" class="img-thumbnail"  width="304" height="236">
			</div>

			<div class="col-md-4">
				<img ng-show="firma[0] != null" ngf-src="firma[0]" class="img-thumbnail"  width="304" height="236">
			</div>
		</div>

		<hr>
		<center><button type="submit" class="btn btn-success">Guardar</button></center>
	</form>	
</div>
@endsection	