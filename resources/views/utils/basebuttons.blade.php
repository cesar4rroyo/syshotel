<button class="btn" onclick="modal('{{URL::route($ruta['edit'], array($id, 'listagain'=>'SI'))}}', '{{$titulo_modificar}}', this);" >
    <i style="color: blue" class="fa fa-pen-alt"></i>
</button>
<button class="btn"  onclick="modal('{{URL::route($ruta['delete'], array($id, 'listagain'=>'SI'))}}', '{{$titulo_eliminar}}', this);">
    <i style="color: red" class="fas fa-trash"></i>
</button>