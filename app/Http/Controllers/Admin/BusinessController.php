<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRequest;
use App\Http\Services\BusinessService;
use App\Librerias\Libreria;
use App\Models\Business;
use App\Traits\CRUDTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    use CRUDTrait;

    public BusinessService $businessService;

    public function __construct()
    {
        $this->model            = new Business();
        $this->businessService  = new BusinessService();

        $this->entity       = 'business';
        $this->folderview   = 'admin.business';
        $this->adminTitle   = __('maintenance.admin.business.title');
        $this->addTitle     = __('maintenance.general.add', ['entity' => $this->adminTitle]);
        $this->updateTitle  = __('maintenance.general.edit', ['entity' => $this->adminTitle]);
        $this->deleteTitle  = __('maintenance.general.delete', ['entity' => $this->adminTitle]);
        $this->routes = [
            'search'  => 'business.search',
            'index'   => 'business.index',
            'store'   => 'business.store',
            'delete'  => 'business.delete',
            'create'  => 'business.create',
            'edit'    => 'business.edit',
            'update'  => 'business.update',
            'destroy' => 'business.destroy',
            'branches' => 'branch.maintenance',
            'users' => 'user.maintenance',
            'cashboxes' => 'cashbox.maintenance',
        ];
        $this->idForm       = 'formMantenimiento' . $this->entity;

        $this->clsLibreria = new Libreria();

        $this->headers = [
            [
                'valor'  => 'Nombre',
                'numero' => '1',
            ],
            [
                'valor'  => 'Estado',
                'numero' => '1',
            ],
            [
                'valor'  => 'Email/Teléfono',
                'numero' => '1',
            ],
            [
                'valor'  => 'Dirección',
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
            $nombre      = $this->getParam($request->nombre);

            $result = $this->model::search($nombre);
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
                'cboStatus'         => ['A' => 'Activo', 'I' => 'Inactivo'],
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function store(BusinessRequest $request)
    {
        try {
            $error = DB::transaction(function () use ($request) {
                $business = $this->model->create($request->all());
                $this->businessService->storeOrUpdateBusinessBranches($business, true);
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
                'cboStatus'         => ['A' => 'Activo', 'I' => 'Inactivo'],
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $error = DB::transaction(function () use ($request, $id) {
                $this->model->find($id)->update($request->all());
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