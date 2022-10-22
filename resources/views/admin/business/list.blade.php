@if(count($lista) == 0)
@include('utils.noresult')
@else
<table id="example1" class="w-full text-base font-medium text-left text-gray-500">
	@include('utils.theader', ['cabecera' => $cabecera])
	<tbody class="border-b border-gray-300">
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
        <tr>
			<td class="py-3 px-4">{{ $value->name }}</td>
			<td class="py-3 px-4">{{ $value->statusBusiness }}</td>
			<td class="py-3 px-4">{{ $value->email . ' ' . $value->phone }}</td>
			<td class="py-3 px-4">{{ $value->address }}</td>
			<td class="py-3 px-4">
				<div class="flex items-center space-x-4 text-lg">
					<button class="btn"  onclick="modal('{{URL::route($ruta['maintenance'], array($value->id, 'action'=>'SETTINGS'))}}', '{{$settings_title}}', this);">
						<i style="color: orange" class="fas fa-wrench"></i>
					</button>
					<button class="btn"  onclick="modal('{{URL::route($ruta['maintenance'], array($value->id, 'action'=>'BRANCHES'))}}', '{{$branches_title}}', this);">
						<i style="color: purple" class="fas fa-building"></i>
					</button>
					<button class="btn"  onclick="modal('{{URL::route($ruta['maintenance'], array($value->id, 'action'=>'USERS'))}}', '{{$users_title}}', this);">
						<i style="color: green" class="fas fa-users"></i>
					</button>
					@include('utils.basebuttons', ['ruta' => $ruta, 'id' => $value->id, 'titulo_modificar' => $titulo_modificar, 'titulo_eliminar' => $titulo_eliminar])
					<button class="btn"  onclick="modal('{{URL::route($ruta['maintenance'], array($value->id, 'action'=>'PHOTO'))}}', '{{$users_title}}', this);">
						<i style="color: black" class="fas fa-images"></i>
					</button>
				</div>
			</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
    <caption style="caption-side:bottom">
        {!! $paginacion!!}
    </caption>
</table>
@endif
