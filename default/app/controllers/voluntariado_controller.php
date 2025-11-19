<?php
class VoluntariadoController extends AppController
{
    public function index()
    {
        // Lista todos los voluntarios
        $this->voluntarios = (new Voluntariado())->find();
    }

    public function ver($id = null)
    {
        if (!$id) {
            Flash::error("ID DE VOLUNTARIO NO PROPORCIONADO");
            return Redirect::to("voluntariado/index");
        }

        $this->voluntario = (new Voluntariado())->find_first((int)$id);

        if (!$this->voluntario) {
            Flash::error("VOLUNTARIO NO ENCONTRADO");
            return Redirect::to("voluntariado/index");
        }
    }

    public function registrar()
    {
        $this->voluntario = new Voluntariado();

        // Preparar arrays para selects
        $this->donadores = $this->getArrayDonadores();
        $this->centros   = $this->getArrayCentros();
        $this->campanas  = $this->getArrayCampanas();

        if (Input::hasPost("voluntario")) {
            $params = Input::post("voluntario");
            $voluntario = new Voluntariado($params);

            if ($voluntario->create()) {
                Flash::valid("VOLUNTARIO REGISTRADO CORRECTAMENTE");
                return Redirect::to("voluntariado/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR EL VOLUNTARIO");
                $this->voluntario = $voluntario; // Mantener datos del formulario
            }
        }
    }

    public function editar($id = null)
    {
        if (!$id) {
            Flash::error("ID DE VOLUNTARIO NO PROPORCIONADO");
            return Redirect::to("voluntariado/index");
        }

        $this->voluntario = (new Voluntariado())->find_first((int)$id);

        if (!$this->voluntario) {
            Flash::error("VOLUNTARIO NO ENCONTRADO");
            return Redirect::to("voluntariado/index");
        }

        // Preparar arrays para selects
        $this->donadores = $this->getArrayDonadores();
        $this->centros   = $this->getArrayCentros();
        $this->campanas  = $this->getArrayCampanas();

        if (Input::hasPost("voluntario")) {
            $params = Input::post("voluntario");

            if ($this->voluntario->update($params)) {
                Flash::valid("VOLUNTARIO ACTUALIZADO CORRECTAMENTE");
                return Redirect::to("voluntariado/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR EL VOLUNTARIO");
            }
        }
    }

    public function eliminar($id = null)
    {
        if (!$id) {
            Flash::error("ID DE VOLUNTARIO NO PROPORCIONADO");
            return Redirect::to("voluntariado/index");
        }

        $voluntario = (new Voluntariado())->find_first((int)$id);

        if (!$voluntario) {
            Flash::error("VOLUNTARIO NO ENCONTRADO");
            return Redirect::to("voluntariado/index");
        }

        if ($voluntario->delete()) {
            Flash::valid("VOLUNTARIO ELIMINADO CORRECTAMENTE");
        } else {
            Flash::error("NO SE PUDO ELIMINAR EL VOLUNTARIO");
        }

        return Redirect::to("voluntariado/index");
    }

    // --- Métodos privados para convertir a arrays clave=>valor ---
    private function getArrayDonadores()
    {
        $array = [];
        foreach ((new Donadores())->find() as $d) {
            $array[$d->id] = $d->nombre;
        }
        return $array;
    }

    private function getArrayCentros()
    {
        $array = [];
        foreach ((new Mapa())->find() as $c) {
            $array[$c->id] = $c->nombre;
        }
        return $array;
    }

    private function getArrayCampanas()
    {
        $array = [];
        foreach ((new Campana())->find() as $c) {
            $array[$c->id] = $c->nombre;
        }
        return $array;
    }
}
