<?php
class Marcas extends Controller
{
    public function __construct()
    {
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        parent::__construct();
        if ($_SESSION['id_usuario'] != 1) {
            header("location: " . base_url);
        }
    }
    public function index()
    {
        $this->views->getView($this, "index");
    }
    public function listar()
    {
        $id_user = $_SESSION['id_usuario'];
        $data = $this->model->getMarcas(1);
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
            $data[$i]['editar'] = '<button class="btn btn-outline-primary" type="button" onclick="btnEditarMarca(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>';
            $data[$i]['eliminar'] = '<button class="btn btn-outline-danger" type="button" onclick="btnEliminarMarca(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function registrar()
    {
        $marca = strClean($_POST['nombre']);
        $id = strClean($_POST['id']);
        if (empty($marca)) {
            $msg = array('msg' => 'Introduza o nome', 'icono' => 'warning');
        } else {
            if ($id == "") {
                $data = $this->model->registrarMarca($marca);
                if ($data == "ok") {
                    $msg = array('msg' => 'Marca registada com sucesso', 'icono' => 'success');
                } else if ($data == "existe") {
                    $msg = array('msg' => 'A marca ja existe', 'icono' => 'warning');
                } else {
                    $msg = array('msg' => 'Erro ao registar', 'icono' => 'error');
                }
            } else {
                $data = $this->model->modificarMarca($marca, $id);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Marca modificada', 'icono' => 'success');
                } else {
                    $msg = array('msg' => 'Erro ao modificar', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id)
    {
        $data = $this->model->editarMarca($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {
        $data = $this->model->accionMarca(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Eliminado com sucesso', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error ao eliminar', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionMarca(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Marca re-inserida', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Erro ao re-inserir', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function inactivos()
    {
        $data['marcas'] = $this->model->getMarcas(0);
        $this->views->getView($this, "inactivos", $data);
    }
}
