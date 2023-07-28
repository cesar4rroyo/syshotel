<div class="flex flex-col w-full space-y-6 mb-4 mt-3">
	@include('control.cashregister.buttons', ['ruta' => $ruta, 'entidad' => $entidad, 'status' => $status, 'titles' => $titles])
</div>
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
			<td class="py-3 px-4 text-sm">{{ $value->created_at }}</td>
			@if ($value->processtype_id == \App\Models\ProcessType::SELL_ID)
				<td class="py-3 px-4 text-sm">{{ 'Mov. Nro. ' . $value->number }}</td>
			@else
				<td class="py-3 px-4 text-sm">{{ 'Venta Nro. ' . $value->number }}</td>
			@endif
			@if ( $value->concept->type == \App\Models\Concept::TYPE_INCOME )
				<td class="py-3 px-4">
					<span style="font-size: 0.5rem" class="px-2 py-1 leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
						{{ $value->concept->name }}
					</span>
				</td>
			@else
				<td class="py-3 px-4">
					<span class="px-2 py-1 leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
						{{ $value->concept->name }}
					</span>
				</td>
			@endif
			<td class="py-3 px-4 text-sm">{{ $value->amount }}</td>
			<td class="py-3 px-4 text-sm">{{ $value->client?->name ?? '-' }}</td>
			<td class="py-3 px-4 text-sm">{{ $value->notes ?? '-' }}</td>
			{{-- <td class="py-3 px-4">
				<div class="flex items-center space-x-4 text-lg">
					@include('utils.basebuttons', ['ruta' => $ruta, 'id' => $value->id, 'titulo_modificar' => $titulo_modificar, 'titulo_eliminar' => $titulo_eliminar])
				</div>
			</td> --}}
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
<div class="flex flex-col w-full space-y-6 mb-4 mt-3">
@include('control.cashregister.totals', ['resumeData' => $resumeData, 'routes' => $ruta])'
</div>