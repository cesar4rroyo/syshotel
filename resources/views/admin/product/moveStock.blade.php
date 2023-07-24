@include('utils.errordiv', ['entidad' => $formData['entidad']])
@include('utils.formcrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])
@if (count($formData['cboBranch'])<2)
    <div class="flex space-x-6">
        <h3>Operación no soportada, solo hay una sucursal</h3>
    </div>
    <div class="flex items-center justify-end space-x-5 py-3 w-full">
        <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" id="btnCancelar{{$formData['entidad']}}" onclick="cerrarModal();">
            {{ trans('maintenance.utils.cancel') }}
        </button>
    </div>
    
@else
<div class="flex space-x-6">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600"
            for="originbranch">Sucursal Origen</label>
        <select
            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5"
            name="originbranch" id="originbranch" required>
            @foreach ($formData['cboBranch'] as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600"
            for="finalbranch">Sucursal Destino</label>
        <select
            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5"
            name="finalbranch" id="finalbranch" required>
            @foreach ($formData['cboBranch'] as $key => $value)
                <option value="{{ $key }}">
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
    <button id="btnValidateBranches" onclick="validateBranches()" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-300 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
        {{ __('maintenance.utils.search') }}
    </button>
</div>
<div id="divContainerSearch" style="display: none;">
    @include('utils.search', [
    'placeholder' => 'Buscar Producto ...',
    'name' => 'search',
    'route' => route($formData['find']),
    'propName' => 'name',
    'propOnClick' => 'addToTableProducts',
])
</div>
<div class="flex space-x-6" id="divTableProducts" style="height: 300px">
    <table id="productsTable" class="w-full text-base font-medium text-center text-gray-500">
        <thead class="text-base font-medium text-gray-900">
            <tr>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Nombre</th>
                <th scope="col" class="py-3 px-4 border-b border-gray-300">Cantidad</th>
            </tr>
        </thead>
        <tbody class="border-b border-gray-300">
        </tbody>
    </table>
</div>
<div class="flex w-full mt-3">
    @include('utils.modalbuttons', ['entidad' => $formData['entidad'], 'boton' => $formData['boton']])
</div>
@endif
</form>
<script type="text/javascript">
    $(document).ready(function() {
        configurarAnchoModal('450');
        init(IDFORMMANTENIMIENTO + '{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
    });

    function validateBranches()
    {
        var originbranch = document.getElementById('originbranch').value;
        var finalbranch = document.getElementById('finalbranch').value;
        var divContainerSearch = document.getElementById('divContainerSearch');
        var btn = document.getElementById('btnValidateBranches');
        if(originbranch == finalbranch) {
            alert('Las sucursales deben ser diferentes');
            return;
        }
        divContainerSearch.style.display = 'block';
        btn.style.display = 'none';

        
    }

    function addToTableProducts(id, name, stock)
    {
        var container  = document.getElementById('divTableProducts');
        var table = document.getElementById('productsTable');
        var tbody = table.getElementsByTagName('tbody')[0];
        var trs = tbody.getElementsByTagName('tr');
        var exists = false;
        for (let index = 0; index < trs.length; index++) {
            const element = trs[index];
            var tds = element.getElementsByTagName('td');
            if(tds[0].innerHTML == name) {
                exists = true;
                break;
            }
        }
        if(exists) {
            alert('El producto ya existe en la tabla');
            return;
        }
        var tr = document.createElement('tr');
        var tdName = document.createElement('td');
        tdName.setAttribute('class', 'py-3 px-4 border-b border-gray-300');
        tdName.innerHTML = name;
        var tdInput = document.createElement('td');
        tdInput.setAttribute('class', 'py-3 px-4 border-b border-gray-300');
        var input = document.createElement('input');
        input.setAttribute('type', 'number');
        input.setAttribute('class', 'border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full p-2.5');
        input.setAttribute('name', 'products[' + id + ']');
        input.setAttribute('id', 'products[' + id + ']');
        input.setAttribute('value', '1');
        input.setAttribute('min', '1');
        input.setAttribute('max', stock);
        input.setAttribute('required', 'true');
        input.addEventListener('change', function() {
            if(parseInt(this.value) > parseInt(this.max)) {
                alert('La cantidad no puede ser mayor al stock');
                this.value = this.max;
            }
            if(parseInt(this.value) < 0) {
                alert('La cantidad no puede ser negativa');
                this.value = 1;
            }
        });
        tdInput.appendChild(input);
        tr.appendChild(tdName);
        tr.appendChild(tdInput);
        tbody.appendChild(tr);
        //delete row
        var tdDelete = document.createElement('td');
        tdDelete.setAttribute('class', 'py-3 px-4 border-b border-gray-300');
        var btnDelete = document.createElement('button');
        btnDelete.setAttribute('class', 'px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2');
        btnDelete.setAttribute('type', 'button');
        btnDelete.setAttribute('onclick', 'deleteRow(this)');
        var i = document.createElement('i');
        i.setAttribute('class', 'fas fa-trash');
        btnDelete.appendChild(i);
        tdDelete.appendChild(btnDelete);
        tr.appendChild(tdDelete);
    }

    function deleteRow(btn)
    {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

</script>
