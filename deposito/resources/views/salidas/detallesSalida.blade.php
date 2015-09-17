<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title">
    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Pedido <strong>{#entrada.codigo#}</strong></h3>
</div>
<div class="modal-body">
	
	<table class="table table-bordered custon-table-bottom-off" >
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Provedor</th>
				<th>Usuario</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#entrada.fecha#}</td>
				<td>{#entrada.hora#}</td>
				<td>{#entrada.provedor#}</td>
				<td>{#entrada.usuario#}</td>
			</tr>
		</tbody>	
	</table>
		
	<table class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Descripcion</th>
				<th>Cantidad</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="insumo in insumos">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.cantidad#}</td>
			</tr>
		</tbody>
	</table>

</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>