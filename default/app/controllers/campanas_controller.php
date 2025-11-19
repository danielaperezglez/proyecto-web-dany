<?php
class CampanasController extends AppController {

    public function index() {
        // Muestra todas las campañas ordenadas por fecha más reciente
        $this->campanas = (new Campana())->find("order: fecha_inicio desc");
    }

    public function ver($id) {
        $id = (int)$id;
        $this->campana = (new Campana())->find_first($id);

        if (!$this->campana) {
            Flash::error("CAMPAÑA NO ENCONTRADA");
            return Redirect::to("campanas/index");
        }
    }

    public function registrar() {
        $this->campana = new Campana();

        if (Input::hasPost("campanas")) {
            $params = Input::post("campanas");
            $campana = new Campana($params);

            if ($campana->create()) {
                Flash::valid("CAMPAÑA REGISTRADA CORRECTAMENTE");
                return Redirect::to("campanas/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR LA CAMPAÑA, REVISA LOS CAMPOS.");
            }
        }
    }

    public function editar($id) {
        $id = (int)$id;
        $this->campana = (new Campana())->find_first($id);

        if (!$this->campana) {
            Flash::error("CAMPAÑA NO ENCONTRADA");
            return Redirect::to("campanas/index");
        }

        if (Input::hasPost("campanas")) {
            $params = Input::post("campanas");
            if ($this->campana->update($params)) {
                Flash::valid("CAMPAÑA ACTUALIZADA CORRECTAMENTE");
                return Redirect::to("campanas/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR LA CAMPAÑA");
            }
        }
    }

    public function eliminar($id) {
        $id = (int)$id;
        $campana = (new Campana())->find_first($id);

        if (!$campana) {
            Flash::error("CAMPAÑA NO ENCONTRADA");
            return Redirect::to("campanas/index");
        }

        if ($campana->delete()) {
            Flash::valid("CAMPAÑA ELIMINADA CORRECTAMENTE");
        } else {
            Flash::error("NO SE PUDO ELIMINAR LA CAMPAÑA");
        }

        return Redirect::to("campanas/index");
    }
}