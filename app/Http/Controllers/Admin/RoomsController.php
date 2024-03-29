<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Librerias\Libreria;
use App\Models\Branch;
use App\Models\Floor;
use App\Models\Room;
use App\Models\RoomType;
use App\Traits\CRUDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomsController extends Controller
{
    use CRUDTrait;

    public function __construct()
    {
        $this->model       = new Room();

        $this->entity      = 'rooms';
        $this->folderview  = 'admin.room';
        $this->adminTitle  = __('maintenance.admin.room.title');
        $this->addTitle    = __('maintenance.general.add', ['entity' => $this->adminTitle]);
        $this->updateTitle = __('maintenance.general.edit', ['entity' => $this->adminTitle]);
        $this->deleteTitle = __('maintenance.general.delete', ['entity' => $this->adminTitle]);
        $this->routes = [
            'search'  => 'room.search',
            'index'   => 'room.index',
            'store'   => 'room.store',
            'delete'  => 'room.delete',
            'create'  => 'room.create',
            'edit'    => 'room.edit',
            'update'  => 'room.update',
            'destroy' => 'room.destroy',
        ];
        $this->idForm       = 'formMantenimiento' . $this->entity;
        $this->clsLibreria = new Libreria();
        $this->headers = [
            [
                'valor'  => 'Nombre',
                'numero' => '1',
            ],
            [
                'valor'  => 'Número',
                'numero' => '1',
            ],
            [
                'valor' => 'Estado',
                'numero' => '1',
            ],
            [
                'valor'  => 'Tipo de dormitorio',
                'numero' => '1',
            ],
            [
                'valor'  => 'Piso',
                'numero' => '1',
            ],
            [
                'valor'  => 'Sucursal',
                'numero' => '1',
            ],
            [
                'valor'  => 'Empresa',
                'numero' => '1',
            ],
            [
                'valor'  => 'Acciones',
                'numero' => '1',
            ],
        ];
    }

    public function search(Request $request)
    {
        try {
            $paginas = $request->page;
            $filas = $request->filas;

            $nombre   = $this->getParam($request->nombre);
            $businessId = auth()->user()->business_id;
            $branchId = $this->getParam($request->branch_id);
            if ($branchId == null && auth()->user()->usertype_id != 1) {
                $branchId = auth()->user()->branch_id;
            }
            $result   = $this->model::search($nombre, $branchId, $businessId);
            $list     = $result->get();

            if (count($list) > 0) {
                $paramPaginacion = $this->clsLibreria->generarPaginacion($list, $paginas, $filas, $this->entity);
                $list = $result->paginate($filas);
                $request->replace(array('page' => $paramPaginacion['nuevapagina']));
                return view($this->folderview . '.list')->with([
                    'lista'             => $list,
                    'cabecera'          => $this->headers,
                    'titulo_admin'      => $this->adminTitle,
                    'titulo_eliminar'   => $this->deleteTitle,
                    'titulo_modificar'  => $this->updateTitle,
                    'paginacion'        => $paramPaginacion['cadenapaginacion'],
                    'inicio'            => $paramPaginacion['inicio'],
                    'fin'               => $paramPaginacion['fin'],
                    'ruta'              => $this->routes,
                    'entidad'           => $this->entity,
                ]);
            }
            return view($this->folderview . '.list')->with('lista', $list)->with('entidad', $this->entity);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function index()
    {
        try {
            return view($this->folderview . '.index')->with([
                'entidad'           => $this->entity,
                'titulo_admin'      => $this->adminTitle,
                'titulo_eliminar'   => $this->deleteTitle,
                'titulo_modificar'  => $this->updateTitle,
                'titulo_registrar'  => $this->addTitle,
                'ruta'              => $this->routes,
                'cboRangeFilas'     => $this->cboRangeFilas(),
            ]);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function create(Request $request)
    {
        $businessId = auth()->user()->business_id;

        try {
            $formData = [
                'route'             => $this->routes['store'],
                'method'            => 'POST',
                'class'             => 'flex flex-col space-y-3 py-2',
                'id'                => $this->idForm,
                'autocomplete'      => 'off',
                'entidad'           => $this->entity,
                'listar'            => $this->getParam($request->input('listagain'), 'NO'),
                'boton'             => 'Registrar',
                'cboStatus'         => config('constants.roomStatus'),
                'cboRoomType'       => RoomType::where('business_id', $businessId)->get()->pluck('name', 'id'),
                'cboFloor'          => Floor::where('business_id', $businessId)->get()->pluck('name', 'id'),
                'cboBranch'         => Branch::where('business_id', $businessId)->get()->pluck('name', 'id'),
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function store(RoomRequest $request)
    {
        try {

            $error = DB::transaction(function () use ($request) {
                $model = $this->model->create([
                    'name'          => $this->getParam($request->input('name')),
                    'number'        => $this->getParam($request->input('number')),
                    'status'        => $this->getParam($request->input('status')),
                    'room_type_id'  => $this->getParam($request->input('room_type_id')),
                    'floor_id'      => $this->getParam($request->input('floor_id')),
                    'branch_id'     => $this->getParam($request->input('branch_id')),
                    'business_id'   => auth()->user()->business_id,
                ]);
            });
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function edit(Request $request, $id)
    {
        try {

            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }

            $businessId = auth()->user()->business_id;

            $formData = [
                'route'             => array($this->routes['update'], $id),
                'method'            => 'PUT',
                'class'             => 'form-horizontal',
                'id'                => $this->idForm,
                'autocomplete'      => 'off',
                'model'             => $this->model->find($id),
                'listar'            => $this->getParam($request->input('listar'), 'NO'),
                'boton'             => 'Modificar',
                'entidad'           => $this->entity,
                'cboStatus'         => config('constants.roomStatus'),
                'cboRoomType'       => RoomType::where('business_id', $businessId)->get()->pluck('name', 'id'),
                'cboFloor'          => Floor::where('business_id', $businessId)->get()->pluck('name', 'id'),
                'cboBranch'         => Branch::where('business_id', $businessId)->get()->pluck('name', 'id'),
            ];

            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function update(RoomRequest $request, $id)
    {
        try {
            $error = DB::transaction(function () use ($request, $id) {
                $this->model->find($id)->update([
                    'name'          => $this->getParam($request->input('name')),
                    'number'        => $this->getParam($request->input('number')),
                    'status'        => $this->getParam($request->input('status')),
                    'room_type_id'  => $this->getParam($request->input('room_type_id')),
                    'floor_id'      => $this->getParam($request->input('floor_id')),
                    'branch_id'     => $this->getParam($request->input('branch_id')),
                    'business_id'   => auth()->user()->business_id,
                ]);
            });
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function delete($id, $listagain)
    {
        try {
            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }
            $listar = 'NO';
            if (!is_null($this->getParam($listagain))) {
                $listar = $listagain;
            }
            $formData = [
                'route'         => array($this->routes['destroy'], $this->model->find($id)),
                'method'        => 'DELETE',
                'class'         => 'form-horizontal',
                'id'            => $this->idForm,
                'autocomplete'  => 'off',
                'boton'         => 'Eliminar',
                'entidad'       => $this->entity,
                'listar'        => $listar,
                'modelo'        => $this->model->find($id),
            ];
            return view('utils.comfirndelete')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function destroy($id)
    {
        try {
            $error = DB::transaction(function () use ($id) {
                $this->model->find($id)->delete();
            });
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }
}
