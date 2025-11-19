<?php
class DonacionesController extends AppController
{
    public function index()
    {
        // Mostrar todas las donaciones
        $this->donaciones = (new Donaciones())->find();
    }

    public function ver($id = null)
    {
        $id = (int)$id;
        $this->donacion = (new Donaciones())->find_first($id);

        if (!$this->donacion) {
            Flash::error("DONACIÓN NO ENCONTRADA");
            return Redirect::to("donaciones/index");
        }
    }

    public function nuevo()
    {
        // Instancia inicial para el formulario
        $this->donacion = new Donaciones();

        // Verifica si se envió el formulario
        if (Input::hasPost("donacion")) {
            $this->donacion = new Donaciones(Input::post("donacion"));

            if ($this->donacion->save()) {
                Flash::valid("¡Donación registrada correctamente! Gracias por tu apoyo.");
                return Redirect::to("donaciones/index");
            } else {
                Flash::error("No se pudo registrar la donación. Verifica los datos.");
            }
        }
    }

    public function editar($id = null)
    {
        $id = (int)$id;
        $this->donacion = (new Donaciones())->find_first($id);

        if (!$this->donacion) {
            Flash::error("DONACIÓN NO ENCONTRADA");
            return Redirect::to("donaciones/index");
        }

        if (Input::hasPost("donacion")) {
            if ($this->donacion->update(Input::post("donacion"))) {
                Flash::valid("Donación actualizada correctamente");
                return Redirect::to("donaciones/index");
            } else {
                Flash::error("No se pudo actualizar la donación.");
            }
        }
    }

    public function eliminar($id = null)
    {
        $id = (int)$id;
        $donacion = (new Donaciones())->find_first($id);

        if (!$donacion) {
            Flash::error("DONACIÓN NO ENCONTRADA");
        } else {
            if ($donacion->delete()) {
                Flash::valid("Donación eliminada correctamente");
            } else {
                Flash::error("No se pudo eliminar la donación");
            }
        }

        return Redirect::to("donaciones/index");
    }
}
