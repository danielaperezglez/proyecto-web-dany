<?php
class ImpactoSocialController extends AppController {

    public function index() {
        $this->impactos = (new ImpactoSocial())->find();
    }

    public function ver($id) {
        $id = (int)$id;
        $this->impacto_social = (new ImpactoSocial())->find_first($id);

        if (!$this->impacto_social) {
            Flash::error("REGISTRO DE IMPACTO SOCIAL NO ENCONTRADO");
            return Redirect::to("impacto_social/index");
        }
    }

    public function registrar() {
        $this->impacto_social = new ImpactoSocial();

        // Obtener los donadores para el select
        $this->donadores = (new Donadores())->find();

        if (Input::hasPost("impacto_social")) {
            $params = Input::post("impacto_social");
            $impacto = new ImpactoSocial($params);

            if ($impacto->create()) {
                Flash::valid("IMPACTO SOCIAL REGISTRADO CORRECTAMENTE");
                return Redirect::to("impacto_social/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR EL IMPACTO SOCIAL");
            }
        }
    }

    public function editar($id) {
        $id = (int)$id;
        $this->impacto_social = (new ImpactoSocial())->find_first($id);

        // Obtener los donadores para el select
        $this->donadores = (new Donadores())->find();

        if (!$this->impacto_social) {
            Flash::error("REGISTRO DE IMPACTO SOCIAL NO ENCONTRADO");
            return Redirect::to("impacto_social/index");
        }

        if (Input::hasPost("impacto_social")) {
            $params = Input::post("impacto_social");
            if ($this->impacto_social->update($params)) {
                Flash::valid("IMPACTO SOCIAL ACTUALIZADO CORRECTAMENTE");
                return Redirect::to("impacto_social/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR EL IMPACTO SOCIAL");
            }
        }
    }

    public function refrescar() {
        $impacto = new ImpactoSocial();
        $impacto->query("TRUNCATE TABLE impacto_social");

        $impacto->query("
            INSERT INTO impacto_social (donador_id, tipo_donacion, cantidad_total, descripcion)
            SELECT 
                d.id AS donador_id,
                dn.tipo_donacion,
                SUM(dn.cantidad) AS cantidad_total,
                CONCAT('Total de ', SUM(dn.cantidad), ' unidades de ', dn.tipo_donacion, ' donadas.') AS descripcion
            FROM donaciones dn
            JOIN donadores d ON dn.donador_id = d.id
            GROUP BY d.id, dn.tipo_donacion
            ORDER BY cantidad_total DESC
        ");

        Flash::valid("Estadísticas de Impacto Social actualizadas correctamente.");
        return Redirect::to("impacto_social/index");
    }
}