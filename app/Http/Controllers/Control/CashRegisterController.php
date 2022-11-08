<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Services\CashRegisterService;
use App\Librerias\Libreria;
use App\Models\Process;
use App\Traits\CRUDTrait;
use Illuminate\Http\Request;

class CashRegisterController extends Controller
{
    use CRUDTrait;
    protected CashRegisterService $cashRegisterService;
    protected int $businessId;
    protected int $branchId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = $request->session()->get('businessId');
            $this->branchId = $request->session()->get('branchId');
            $this->cashRegisterService  = new CashRegisterService($this->businessId, $this->branchId);
            return $next($request);
        });

        $this->model = new Process();


        $this->folderView   = 'control.cashregister';
        $this->adminTitle   = __('maintenance.admin.cashregister.title');
        $this->routes = [
            'search'  => 'cashregister.search',
            'index'   => 'cashregister.index',
            'store'   => 'cashregister.store',
            'delete'  => 'cashregister.delete',
            'create'  => 'cashregister.create',
            'edit'    => 'cashregister.edit',
            'update'  => 'cashregister.update',
            'destroy' => 'cashregister.destroy',
            'maintenance' => 'cashregister.maintenance',
            'print' => 'cashregister.print',
        ];
        $this->idForm       = 'formMantenimiento' . $this->entity;

        $this->clsLibreria = new Libreria();

        $this->headers = [
            [
                'valor'  => 'Nombre',
                'numero' => '1',
            ],
            [
                'valor'  => 'Teléfono',
                'numero' => '1',
            ],
            [
                'valor'  => 'Observación',
                'numero' => '1',
            ],
            [
                'valor'  => 'Sucursal',
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
            $name = $this->getParam($request->name);
            $businessId  = $this->getParam($request->businessId);
            $branchId = $this->getParam($request->branch_id);
            $result = $this->model::search($name, $branchId, $businessId);
            $list   = $result->get();
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
            return view($this->folderview . '.list')->with('lista', $list)->with([
                'entidad'           => $this->entity,
            ]);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function index()
    {
        try {
            return view('control.cashregister.index')->with([
                'entidad'           => $this->entity,
                'titulo_admin'      => $this->adminTitle,
                'titulo_eliminar'   => $this->deleteTitle,
                'titulo_modificar'  => $this->updateTitle,
                'titulo_registrar'  => $this->addTitle,
                'ruta'              => $this->routes,
                'cboRangeFilas'     => $this->cboRangeFilas(),
                'status'            => $this->cashRegisterService->getStatus(),
                'cboTypes'          => ['' => 'Todos', 'I' => 'Ingreso', 'E' => 'Egreso'],
            ]);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function create(Request $request)
    {
        try {
            $businessId = $request->businessId ?? auth()->user()->business_id;
            $formData = [
                'route'             => $this->routes['store'],
                'method'            => 'POST',
                'class'             => 'flex flex-col space-y-3 py-2',
                'id'                => $this->idForm,
                'autocomplete'      => 'off',
                'entidad'           => $this->entity,
                'listar'            => $this->getParam($request->input('listagain'), 'NO'),
                'boton'             => 'Registrar',
                'cboBranches'       => Branch::where('business_id', $businessId)->get()->pluck('name', 'id')->all(),
                'businessId'        => $businessId,
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function store(CashBoxRequest $request)
    {
        try {
            $error = DB::transaction(function () use ($request) {
                $this->model::create($request->all());
            });
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $businessId = $request->params['businessId'];
            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }
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
                'cboBranches'       => Branch::where('business_id', $businessId)->get()->pluck('name', 'id')->all(),
                'businessId'        => $businessId,
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function update(CashBoxRequest $request, $id)
    {
        try {
            $error = DB::transaction(function () use ($request, $id) {
                $user = $this->model->find($id);
                $user->update($request->all());
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

    public function maintenance($action, $businessId, $userId = null)
    {
        try {
            $listar = 'SI';
            $formData = [
                'route'         => array($this->routes['update'], $this->model->find($businessId)),
                'method'        => 'PUT',
                'class'         => 'form-horizontal',
                'id'            => $this->idForm,
                'autocomplete'  => 'off',
                'boton'         => 'Guardar',
                'entidad'       => $this->entity,
                'listar'        => $listar,
                'model'         => $this->model,
                'action'        => $action,
                'businessId'    => $businessId,
            ];
            switch ($action) {
                case 'LIST':
                    return $this->index($businessId);
                    break;
                default:
                    return view('utils.comfirndelete')->with(compact('formData'));
                    break;
            }
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

    public function print(Request $request)
    {
        dd($request->all());
    }
}