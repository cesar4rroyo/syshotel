@include('utils.errorDiv', ['entidad' => $formData['entidad']])
@include('utils.formCrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])
<input type="hidden" name="status" id="status" value="Mantenimiento">
<input type="hidden" name="roomId" id="roomId" value="{{$formData['roomId']}}">
<div class="callout callout-danger">
	<p class="text-danger">{{ trans('maintenance.utils.change_status') }}</p>
</div>
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		<button class="btn btn-danger btn-sm" id="btnGuardar" onclick="guardar('{{$formData['entidad']}}', this);">
			<i class="fa fa-trash"></i>
			<span>{{$formData['boton']}}</span>
		</button>
		<button class="btn btn-default btn-sm" id="btnCancelar{{$formData['entidad']}}" onclick="cerrarModal((contadorModal - 1));">
			<i class="fa fa-undo"></i>
			<span>{{ trans('maintenance.utils.cancel') }}</span>
		</button>
	</div>
</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
		configurarAnchoModal('400');
	}); 
	function guardar (entidad, idboton, entidad2) {
		var idformulario = IDFORMMANTENIMIENTO + entidad;
		var data         = submitForm(idformulario);
		var respuesta    = '';
		var listar       = 'NO';
		if ($(idformulario + ' :input[id = "listar"]').length) {
			var listar = $(idformulario + ' :input[id = "listar"]').val();
		};
		var btn = $(idboton);
		// btn.button('loading');
		data.done(function(msg) {
			respuesta = msg;
		}).fail(function(xhr, textStatus, errorThrown) {
			respuesta = xhr.responseText;
			if(JSON.parse(respuesta).message.trim()){
				mostrarErrores(xhr.responseText, idformulario, entidad, 1);
			}
			respuesta = 'ERROR';
		}).always(function() {
			var resp = respuesta.trim();
			if(resp === 'ERROR'){
			}else {
				if (resp === 'OK') {
					cerrarModal();
					cargarRuta('{{ URL::to('management') }}', 'main-container');
				} else {
					mostrarErrores(respuesta, idformulario, entidad);
				}
			}
	});
}
</script>