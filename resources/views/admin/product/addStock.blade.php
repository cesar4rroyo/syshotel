@include('utils.errordiv', ['entidad' => $formData['entidad']])
@include('utils.formcrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])
@include('utils.search', [
    'placeholder' => 'Buscar Producto ...',
    'name' => 'search',
    'route' => route($formData['find']),
    'propName' => 'name',
    'propOnClick' => 'addToTableProducts',
])
<div style="display: none">
    <select
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5"
        name="branches" id="branches">
        @foreach ($formData['cboBranch'] as $key => $value)
            <option value="{{ $key }}">
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>
<div class="flex space-x-6" id="divTableProducts" style="height: 300px">
    <table id="productsTable" class="w-full text-base font-medium text-center text-gray-500">
        <thead class="text-base font-medium text-gray-900">
            <tr>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Nombre</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Cantidad</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Sucursal</th>
            </tr>
        </thead>
        <tbody class="border-b border-gray-300">
        </tbody>
    </table>
</div>

<div class="flex w-full mt-3">
    <div class="flex items-center justify-end space-x-5 py-3 w-full">
        <button class="px-5 py-2 rounded-lg bg-blue-corp text-white flex items-center space-x-2" id="btnGuardar" onclick="save('{{$formData['entidad']}}', this)">
            <i class="far fa-save"></i>
            <p>Guardar</p>
        </button>
        <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" type="button" id="btnCancelar{{$formData['entidad']}}" onclick="cerrarModal2();">
            {{ trans('maintenance.utils.cancel') }}
        </button>
    </div>    
</div>

</form>
<script type="text/javascript">
    $(document).ready(function() {
        configurarAnchoModal('450');
        init(IDFORMMANTENIMIENTO + '{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
    });

    function cerrarModal2()
    {
        document.body.lastElementChild.remove();
        document.body.lastElementChild.remove();
        document.body.lastElementChild.remove();
    }

    function save(entidad, id)
    {
        var idformulario = IDFORMMANTENIMIENTO + entidad;
        var branches = document.getElementsByName('branches[]');
        for (let index = 0; index < branches.length; index++) {
            if(branches[index].value == ''){
                Intranet.notificaciones("Debe seleccionar una sucursal", "Error" , "error");
                return 1;
            }
        }
        if(branches.length == 0) {
            Intranet.notificaciones("Debe agregar al menos un producto", "Error" , "error");
            return 1;
        }
        var data         = submitForm(idformulario);
        var respuesta    = '';
        var listar       = 'NO';
        if ($(idformulario + ' :input[id = "listar"]').length) {
            var listar = $(idformulario + ' :input[id = "listar"]').val();
        };
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
                    Intranet.notificaciones("Accion realizada correctamente", "Realizado" , "success");
                    if (listar.trim() === 'SI') {
                        if(typeof entidad2 != 'undefined' && entidad2 !== ''){
                            entidad = entidad2;
                        }
                        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                    }
                    buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                } else {
                    mostrarErrores(respuesta, idformulario, entidad);
                }
            }
        });
    }

    function addToTableProducts(id, name)
    {
        var container  = document.getElementById('divTableProducts');
        var branches = document.getElementById('branches');
        var table = document.getElementById('productsTable');
        var tbody = table.getElementsByTagName('tbody')[0];
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var input = document.createElement('input');
        var td3 = document.createElement('td');
        var select = document.createElement('select');
        var option = document.createElement('option');
        option.setAttribute('value', '');
        option.innerHTML = 'Seleccione';
        select.appendChild(option);
        for (let index = 0; index < branches.length; index++) {
            var option = document.createElement('option');
            option.setAttribute('value', branches[index].value);
            option.innerHTML = branches[index].innerHTML;
            select.appendChild(option);
        }
        select.setAttribute('name', 'branches[]');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'products[]');
        input.setAttribute('value', id);
        td1.setAttribute('class', 'py-3 px-4');
        td1.innerHTML = name;
        td2.setAttribute('class', 'py-3 px-4');
        td2.appendChild(input);
        td2.appendChild(document.createTextNode(' '));
        var inputNumber = document.createElement('input');
        inputNumber.setAttribute('type', 'number');
        inputNumber.setAttribute('name', 'quantities[]');
        inputNumber.setAttribute('value', '1');
        inputNumber.setAttribute('min', '1');
        // inputNumber.setAttribute('max', '100');
        inputNumber.setAttribute('class', 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5');
        td2.appendChild(inputNumber);
        td3.setAttribute('class', 'py-3 px-4');
        td3.appendChild(select);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tbody.appendChild(tr);
        var td4 = document.createElement('td');
        var button = document.createElement('button');
        button.setAttribute('type', 'button');
        button.setAttribute('class', 'btn btn-danger');
        button.setAttribute('onclick', 'deleteRow(this)');
        button.innerHTML = 'Eliminar';
        td4.appendChild(button);
        tr.appendChild(td4);
        tbody.appendChild(tr);
        container.setAttribute('class', 'flex space-x-6 overflow-y-auto');
    }

    function deleteRow(btn)
    {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>
