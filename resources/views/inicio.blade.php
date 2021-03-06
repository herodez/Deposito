@extends('panel')
@section('front-page')

	<div id="bienvenida">
		<div>
			<h1 class="text-title">Bienvenido</h1>
			<hr>
			<h3>Sistema para la Gestión de Almacenes <b>(SIGAL)</b><p>Servicio Autónomo Hospital Universitario de Maracaibo</p></h3>		
		</div>
	</div>

	<div id="contenido">

		<h1 class="text-title">Gestión Inteligente</h1>

		<div id="contenedor-imagen">
			<img id="imagen" src="{{asset('imagen/sistema.png')}}" class="img-rounded custon-imagen">
		</div>

		<div class="row">
			<div class="col-md-4">
				<h3 class="text-title">Consultas Eficientes</h3>
				<p class="text-muted">Consultas al inventario con eficiencia y eficacia. Control de ingresos y salidas de productos, así como busquedas avanzadas relacionadas con todo el movimiento causado en el inventario.</p>
			</div>

			<div class="col-md-4">
				<h3 class="text-title">Kardex Digital</h3>
				<p class="text-muted">Consultas el movimiento de un producto a través de un Módulo Avanzado de Busqueda, permitiendo generar el Kardex Digital, respetando los críterios de la publicación °15 que provee la Oficina de Contraloría y Consumo.</p>
			</div>

			<div class="col-md-4">
				<h3 class="text-title">Control de Usuarios y Existencias</h3>
				<p class="text-muted">Control y revisión de los movimientos causados por cada usuario sobre cualquier producto. Configuración de alertas automáticas respecto a cada producto, según la necesidad de notificación que establezca el almacén.</p>
			</div>

		</div>
	</div>
@endsection
