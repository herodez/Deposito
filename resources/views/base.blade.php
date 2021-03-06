<!DOCTYPE html>
<html lang="en" ng-app="deposito">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Depósito</title>
	<link rel="icon" type="image/png" href="{{asset('imagen/isigal.ico')}}" />
	<link rel="stylesheet" href="{{asset('css/vendor/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/vendor/bootstrap-theme.min.css')}}">
	<link rel="stylesheet" href="{{asset('css/vendor/select.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/global.css')}}">
	@yield('addstyle')
</head>
<body @yield('bodytag')>

	<div id="bar-menu" ng-controller="menuController" >
		<nav class="navbar navbar-inverse custon-bar navbar-fixed-top" >
		  <div class="container-fluid">
		    <div class="navbar-header">
		      <a class="navbar-brand" href="/inicio"><img id="imagen" src="{{asset('imagen/lsigal.png')}}"></a>
		    </div>
		    <div class="collapse navbar-collapse">
			    @if(Auth::check())
			      <ul class="nav navbar-nav navbar-right">
					<li class="active active-green">
						<a><span class="glyphicon glyphicon-inbox"></span> {{Auth::user()->getDepositoName()}}</a>
					</li>
			      	@if( Auth::user()->hasPermissions(['inventory_notification_alert']) && ( $var = App\Inventario::alert() ) > 0 )
				      	<li>
							<a href="{{route('invenHerraNiveles')}}">
							  <span class="glyphicon glyphicon-bell"></span> <span class="badge">{{$var}}</span>
							</a>
						</li>
					@endif
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="false">
							<span class="glyphicon glyphicon-user"></span> {{ ucwords(Auth::user()->nombre." ".Auth::user()->apellido)}} <span class="caret"></span>
						</a>
				  	    <ul class="dropdown-menu" role="menu">
				        	@if( Auth::user()->hasPermissions(['stores_multiple']))
				        		<li ng-click="deposito()"><a href="#"><span class="glyphicon glyphicon-inbox"></span> Cambiar almacén</a></li>
				        		<li class="divider"></li>
				        	@endif
				        	<li ng-click="password()"><a href="#"><span class="glyphicon glyphicon-lock"></span> Cambiar contraseña</a></li>
				        	<li class="divider"></li>
				        	<li><a href="/auth/logout"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
				        </ul>
					</li>
			      </ul>
			    @endif
		    </div>
		  </div>
		</nav>
	</div>

	<div>
		@yield('conten')
	</div>

	<script src="{{asset('js/vendor/jquery-2.1.4.min.js')}}"></script>
	<script src="{{asset('js/vendor/bootstrap.min.js')}}"></script>
	<script src="{{asset('js/vendor/angular.min.js')}}"></script>
	<script src="{{asset('js/vendor/ui-bootstrap-tpls-0.13.0.min.js')}}"></script>
	<script src="{{asset('js/vendor/dirPagination.js')}}"></script>
	<script src="{{asset('js/vendor/angular-sanitize.min.js')}}"></script>
	<script src="{{asset('js/vendor/select.min.js')}}"></script>
	<script src="{{asset('js/deposito.js')}}"></script>

	@yield('addscript')

</body>
</html>
