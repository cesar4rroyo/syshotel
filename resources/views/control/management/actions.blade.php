@if ($room['status'] == 'Mantenimiento')
<button onclick="modal('{{URL::route($routes['create'], ['status'=>$room['status'], 'id' => $room['id'], 'type' => 'H'])}}', 'Actualizar Estado', this);" type="button" id="btnAction" data-status={{ $room['status'] }} data-room="{{ $room['id'] }}" class="text-black bg-white hover:bg-white-800 focus:ring-4 focus:outline-none focus:ring-white-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 dark:bg-white-600 dark:hover:bg-white-700 dark:focus:ring-white-800">
  <i class="{{ $room['iconActionButton'] }} mr-2"></i>
  {{ $room['textActionButton'] }}
</button>
@else
<button onclick="cargarRuta('{{URL::route($routes['create'], ['status'=>$room['status'], 'id' => $room['id'], 'type' => 'H'])}}', 'main-container');" type="button" id="btnAction" data-status={{ $room['status'] }} data-room="{{ $room['id'] }}" class="text-black bg-white hover:bg-white-800 focus:ring-4 focus:outline-none focus:ring-white-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 dark:bg-white-600 dark:hover:bg-white-700 dark:focus:ring-white-800">
  <i class="{{ $room['iconActionButton'] }} mr-2"></i>
  {{ $room['textActionButton'] }}
</button>    
@endif
@if ($room['status'] =='Disponible')
<select style="width: 90px" name="type" id="type" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-0 focus:border-gray-300 focus:outline-none block px-4 py-2.5" onchange="handleChangeMode(this)">
  <option value="H">Horas</option>
  <option value="D">Dias</option>
</select>
@endif
<script>
  function handleChangeMode(e)
  {
    var value = e.value;
    var status = e.parentNode.querySelector('#btnAction').getAttribute('data-status');
    var room = e.parentNode.querySelector('#btnAction').getAttribute('data-room');
    var url_hours = "{{URL::route($routes['create'])}}" + "?status=" + status + "&id=" + room + "&type=H";
    var url_days = "{{URL::route($routes['create'])}}" + "?status=" + status + "&id=" + room + "&type=D";
    var btn = e.parentNode.querySelector('#btnAction');
    if(value == 'H'){
      btn.setAttribute('onclick', "cargarRuta('"+url_hours+"', 'main-container');");
    }else{
      btn.setAttribute('onclick', "cargarRuta('"+url_days+"', 'main-container');");
    }
  }
</script>