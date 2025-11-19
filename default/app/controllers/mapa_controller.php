<?php
class MapaController extends AppController {

    public function index() {
        $this->centros = (new Mapa())->find();
    }

    public function ver($id) {
        $id = (int)$id;
        $this->centro = (new Mapa())->find_first($id);

        if (!$this->centro) {
            Flash::error("CENTRO DE ACOPIO NO ENCONTRADO");
            return Redirect::to("mapa/index");
        }
    }

    public function registrar() {
        $this->centro = new Mapa();

        if (Input::hasPost("centro")) {
            $params = Input::post("centro");
            $centro = new Mapa($params);

            if ($centro->create()) {
                Flash::valid("CENTRO DE ACOPIO REGISTRADO CORRECTAMENTE");
                return Redirect::to("mapa/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR EL CENTRO DE ACOPIO");
            }
        }
    }

    public function editar($id) {
        $id = (int)$id;
        $this->centro = (new Mapa())->find_first($id);

        if (!$this->centro) {
            Flash::error("CENTRO DE ACOPIO NO ENCONTRADO");
            return Redirect::to("mapa/index");
        }

        if (Input::hasPost("centro")) {
            $params = Input::post("centro");
            if ($this->centro->update($params)) {
                Flash::valid("CENTRO DE ACOPIO ACTUALIZADO CORRECTAMENTE");
                return Redirect::to("mapa/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR EL CENTRO DE ACOPIO");
            }
        }
    }
}
