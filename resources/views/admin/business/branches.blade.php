@include('utils.errordiv', ['entidad' => $formData['entidad']])
<table class="w-full text-base font-medium text-left text-gray-500">
    @include('utils.theader', ['cabecera' => $cabecera])
    <tbody class="border-b border-gray-300">
        @foreach ($branches as $key => $value)
            <tr>
                <td class="py-3 px-4">{{ $value->name }}</td>
                <td class="py-3 px-4">{{ $value->address }}</td>
                <td class="py-3 px-4">{{ $value->city }}</td>
                <td class="py-3 px-4">{{ $value->phone }}</td>
                <td class="py-3 px-4">{{ $value->email }}</td>
            </tr>  
        @endforeach
    </tbody>
</table>
@include('utils.formcrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])
<input type="hidden" name="action" value="{{ $formData['action'] }}">
<div class="flex space-x-6">
    
</div>

<div class="flex w-full mt-3">
    @include('utils.modalbuttons', ['entidad' => $formData['entidad'], 'boton' => $formData['boton']])
</div>

</form>
<script type="text/javascript">
    $(document).ready(function() {
        configurarAnchoModal('450');
        init(IDFORMMANTENIMIENTO + '{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
    });
</script>