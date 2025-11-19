<?php
class HistoriasImpactoController extends AppController {

    public function index() {
        // Listar todas las historias de impacto
        $this->historias = (new HistoriasImpacto())->find();
    }

    public function ver($id) {
        $id = (int)$id;
        $this->historia = (new HistoriasImpacto())->find_first($id);

        if (!$this->historia) {
            Flash::error("HISTORIA DE IMPACTO NO ENCONTRADA");
            return Redirect::to("historias_impacto/index");
        }
    }

    public function registrar() {
        $this->historia = new HistoriasImpacto();

        // Obtener campañas y donadores para los selects
        $this->campanas  = (new Campana())->find();
        $this->donadores = (new Donadores())->find();

        if (Input::hasPost("historia")) {
            $params = Input::post("historia");

            // Asignar manualmente atributos
            $this->historia->titulo      = $params['titulo'] ?? '';
            $this->historia->descripcion = $params['descripcion'] ?? '';
            $this->historia->estado      = $params['estado'] ?? 'no publicada';
            $this->historia->campana_id  = $params['campana_id'] ?? null;
            $this->historia->donador_id  = $params['donador_id'] ?? null;

            // Manejo de imagen subida
            if (!empty($_FILES['historia']['name']['imagen'])) {
                $tmpName = $_FILES['historia']['tmp_name']['imagen'];
                $fileName = $_FILES['historia']['name']['imagen'];
                $uploadDir = "public/uploads/historias/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($tmpName, $uploadDir . $fileName);
                $this->historia->imagen = $fileName;
            }

            if ($this->historia->create()) {
                Flash::valid("HISTORIA DE IMPACTO REGISTRADA CORRECTAMENTE");
                return Redirect::to("historias_impacto/index");
            } else {
                Flash::error("NO SE PUDO REGISTRAR LA HISTORIA DE IMPACTO");
            }
        }
    }

    public function editar($id) {
        $id = (int)$id;
        $this->historia = (new HistoriasImpacto())->find_first($id);

        if (!$this->historia) {
            Flash::error("HISTORIA DE IMPACTO NO ENCONTRADA");
            return Redirect::to("historias_impacto/index");
        }

        // Obtener campañas y donadores para los selects
        $this->campanas  = (new Campana())->find();
        $this->donadores = (new Donadores())->find();

        if (Input::hasPost("historia")) {
            $params = Input::post("historia");

            // Asignar manualmente atributos
            $this->historia->titulo      = $params['titulo'] ?? $this->historia->titulo;
            $this->historia->descripcion = $params['descripcion'] ?? $this->historia->descripcion;
            $this->historia->estado      = $params['estado'] ?? $this->historia->estado;
            $this->historia->campana_id  = $params['campana_id'] ?? $this->historia->campana_id;
            $this->historia->donador_id  = $params['donador_id'] ?? $this->historia->donador_id;

            // Manejo de imagen subida (si se reemplaza)
            if (!empty($_FILES['historia']['name']['imagen'])) {
                $tmpName = $_FILES['historia']['tmp_name']['imagen'];
                $fileName = $_FILES['historia']['name']['imagen'];
                $uploadDir = "public/uploads/historias/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                move_uploaded_file($tmpName, $uploadDir . $fileName);
                $this->historia->imagen = $fileName;
            }

            if ($this->historia->update()) {
                Flash::valid("HISTORIA DE IMPACTO ACTUALIZADA CORRECTAMENTE");
                return Redirect::to("historias_impacto/index");
            } else {
                Flash::error("NO SE PUDO ACTUALIZAR LA HISTORIA DE IMPACTO");
            }
        }
    }
}
