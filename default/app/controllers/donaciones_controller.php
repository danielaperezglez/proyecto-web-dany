<?php
class DonacionesController extends AppController {

    public function index() {
        $this->donaciones = (new Donaciones())->find();
    }

    public function ver($id = null) {
        $id = (int)$id;
        $this->donacion = (new Donaciones())->find_first($id);

        if (!$this->donacion) {
            Flash::error("DONACIÓN NO ENCONTRADA");
            return Redirect::to("donaciones/index");
        }
    }

    public function registrar() {
        $this->donacion = new Donaciones();

        // Para cargar select de donadores, centros y campañas
        $this->donadores = (new Donadores())->find();
        $this->centros = (new Mapa())->find();
        $this->campanas = (new Campana())->find();

        if (Input::hasPost("donacion")) {
            $params = Input::post("donacion");
            $donacion = new Donaciones($params);

            if ($donacion->create()) {
                Flash::valid("DONACIÓN REGISTRADA CORRECTAMENTE");
                return Redirect::to("donaciones/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR LA DONACIÓN");
            }
        }
    }

    public function editar($id = null) {
        $id = (int)$id;
        $this->donacion = (new Donaciones())->find_first($id);

        if (!$this->donacion) {
            Flash::error("DONACIÓN NO ENCONTRADA");
            return Redirect::to("donaciones/index");
        }

        $this->donadores = (new Donadores())->find();
        $this->centros = (new Mapa())->find();
        $this->campanas = (new Campana())->find();

        if (Input::hasPost("donacion")) {
            $params = Input::post("donacion");
            if ($this->donacion->update($params)) {
                Flash::valid("DONACIÓN ACTUALIZADA CORRECTAMENTE");
                return Redirect::to("donaciones/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR LA DONACIÓN");
            }
        }
    }
}
