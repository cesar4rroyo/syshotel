@include('utils.errordiv', ['entidad' => $formData['entidad']])
@include('utils.formcrud', [
    'entidad' => $formData['entidad'],
    'formData' => $formData,
    'method' => $formData['method'],
    'route' => $formData['route'],
    'model' => isset($formData['model']) ? $formData['model'] : null,
])
<div class="flex space-x-6">
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="number">{{ trans('maintenance.bookings.number') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="number" id="number" readonly
            value="{{ isset($formData['model']) ? $formData['model']->number : $formData['number'] }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="date">{{ trans('maintenance.bookings.datefrom') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="date" name="datefrom" id="datefrom"
            value="{{ isset($formData['model']) ? $formData['model']->datefrom : $formData['day'] }}" required>
    </div>
    <div class="flex flex-col space-y-1 w-full">
        <label class="font-medium text-sm text-gray-600" for="date">{{ trans('maintenance.bookings.dateto') }}</label>
        <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="date" name="dateto" id="dateto"
            value="{{ isset($formData['model']) ? $formData['model']->dateto : null }}" required>
        <button type="button" class="px-5 py-2 rounded-lg bg-green-500 text-white text-sm flex items-center space-x-2" id="searchRoomsBtn"
            onclick="searchRooms()"
            {{ isset($formData['model']) ? 'disabled' : null }}>
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>
</div>
<div id="divBookingData" style="{{ isset($formData['model']) ? '' : 'display:none;' }}">
    <div class="flex space-x-6 mt-2">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="room_id">{{ trans('maintenance.bookings.room') }}</label>
            @if (isset($formData['model']))
            <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="room_id" id="room_id">
                <option value="{{ $formData['model']->room_id }}" selected>{{ $formData['room'] }}</option>
            </select>
            @else
            <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="room_id" id="room_id">
            </select>
            @endif
        </div>
    </div>
    <div class="flex space-x-6 mt-2">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="client_id">{{ trans('maintenance.bookings.client') }}
            @if (! isset($formData['model']))
                <span onclick="modal('{{URL::route($formData['client_route'], ['from'=>'booking', 'room_id' => null])}}', 'Agregar Nuevo Cliente', this);" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2 dark:bg-gray-700 dark:text-gray-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    {{ __('maintenance.utils.new') }}
                  </span>
            @endif
            </label>
            <select class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" name="client_id" id="booking_client_id">
                @foreach ($formData['cboClients'] as $key => $value)
                    <option value="{{ $key }}" {{ isset($formData['model']) && $formData['model']->client_id == $key ? 'selected' : null }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="days">{{ trans('maintenance.bookings.days') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="number" name="days" id="days"
                value="{{ isset($formData['model']) ? $formData['model']->days : null }}" required readonly>
        </div>
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="amount">{{ trans('maintenance.bookings.amount') }}</label>
            <input class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="number" step="0.01" name="amount" id="amount"
                value="{{ isset($formData['model']) ? $formData['model']->amount : null }}" required>
        </div>
    </div>
    <div class="flex space-x-6 mt-2">
        <div class="flex flex-col space-y-1 w-full">
            <label class="font-medium text-sm text-gray-600" for="notes">{{ trans('maintenance.bookings.notes') }}</label>
            <textarea class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block w-full px-4 py-2.5" type="text" name="notes" id="notes"
                value="{{ isset($formData['model']) ? $formData['model']->notes : null }}" required>
                {{ isset($formData['model']) ? $formData['model']->notes : null }}
            </textarea>
        </div>
    </div>
</div>
<div class="flex w-full mt-3">
    <div class="flex items-center justify-end space-x-5 py-3 w-full">
        @if (! isset($formData['model']))
        <button type="button" class="px-5 py-2 rounded-lg bg-blue-corp text-white flex items-center space-x-2 bg-blue-500" id="btnGuardar" onclick="saveBooking(this);">
            <i class="far fa-save"></i>
            <p>{{$formData['boton']}}</p>
        </button>
        @else
        <button type="button" class="px-5 py-2 rounded-lg bg-fuchsia-500 text-white flex items-center space-x-2" id="btnGuardar" onclick="cancelBooking(this);">
            {{ trans('maintenance.bookings.cancel') }}
        </button>   
        @endif
        <button class="px-5 py-2 rounded-lg bg-red-500 text-white flex items-center space-x-2" id="btnCancelar{{$formData['entidad']}}" onclick="cerrarModal();">
            {{ trans('maintenance.utils.close') }}
        </button>
    </div>
</div>

</form>
<script type="text/javascript">
    $(document).ready(function() {
        configurarAnchoModal('450');
        init(IDFORMMANTENIMIENTO + '{!! $formData['entidad'] !!}', 'M', '{!! $formData['entidad'] !!}');
    });

    function cancelBooking(e)
    {
        var route = "{{$formData['url_cancel']}}";
        axios.delete(route).then((response) => {
            if(response.data.success){
                var route = "{{URL::route($formData['index_route'])}}";
                cerrarModal();
                cargarRuta(route, 'main-container');
            }else{
                alert('Hubo un error al cancelar la reserva');
            }
        });
        
    }


    function saveBooking(e)
    {
        var formDataId = "{{ $formData['id'] }}";
        var form = document.getElementById(formDataId);
        var formAction = form.action;
        var formMethod = form.method;
        $.ajax({
            url: formAction,
            type: formMethod,
            data: new FormData(form),
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success) {
                    var route = "{{URL::route($formData['index_route'])}}";
                    cerrarModal();
                    cargarRuta(route, 'main-container');
                } else {
                    alert('Hubo un error al guardar los datos');
                }
            },
            error: function (error) {
                if(error.status == 422){
                    var errors = error.responseJSON.errors;
                    var errorDiv = document.getElementById('divMensajeError{!! $formData['entidad'] !!}');
                    var cadenaError = '<div style="border-color:orange; background-color:rgba(255,250,240,90);" class="bg-orange border-l-4 text-orange-700 p-4" role="alert"><strong style="color:red;">Por favor corrige los siguentes errores:</strong><ul>';
                    $.each(errors, function (index, value) {
                        cadenaError += '<li>' + value[0] + '</li>';
                    });
                    errorDiv.innerHTML = cadenaError + '</ul></div>';
                }else{
                    alert('Hubo un error al guardar los datos');
                }
            }
        });

    }

    function searchRooms()
    {
        let datefrom = document.getElementById('datefrom').value;
        let dateto = document.getElementById('dateto').value;
        let room_id = document.getElementById('room_id');
        //days  = dateFrom - dateto
        let days = (new Date(dateto) - new Date(datefrom)) / (1000 * 60 * 60 * 24);
        if(days <= 0 ){
            alert('La fecha de salida debe ser mayor a la fecha de entrada');
            document.getElementById('days').value = '';
            document.getElementById('dateto').value = '';
            room_id.innerHTML = '';
            document.getElementById('divBookingData').style.display = 'none';
            return;
        }
        if(datefrom == '' || dateto == ''){
            alert('Debe ingresar las fechas de inicio y fin de la reserva');
            return;
        }else{
            let url = '{{ route('booking.rooms') }}';
            let data = {
                'datefrom': datefrom,
                'dateto': dateto
            };
            axios.get(url, {params: data}).then(response => {
                let data = response.data;
                if(data.success){
                    let rooms = data.data;
                    room_id.innerHTML = '';
                    rooms.forEach(room => {
                        let option = document.createElement('option');
                        option.value = room.id;
                        option.text = room.name + (room.number ?? '') + ' - ' + room.type + ' - ' + room.price;
                        room_id.appendChild(option);
                    });
                    document.getElementById('days').value = days;
                    document.getElementById('divBookingData').style.display = 'block';
                }else{
                    Intranet.notificaciones(data.message, "Error!" , "error");
                }
            }).catch(error => {
                Intranet.notificaciones("Ha Ocurrido un error intentelo de nuevo", "Error!" , "error");
            });
        }
    }

</script>
