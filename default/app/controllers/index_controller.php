<?php

class IndexController extends AppController {

    public function index() {
        $this->title = "PROYECTO";
        $this->subtitle = "Karla Daniela Pérez González - 21590486";

        // Campañas activas
        if (class_exists('Campana')) {
            $this->campanas = (new Campana())->find(
                "conditions: estado = 'activa'",
                "limit: 3"
            );
        }

        // Donadores más recientes
        if (class_exists('Donadores')) {  // <-- plural y coincide con el archivo
            $this->donadores = (new Donadores())->find(
                "order: id DESC",
                "limit: 3"
            );
        }

        // Historias de impacto publicadas
        if (class_exists('HistoriasImpacto')) {
            $this->historias = (new HistoriasImpacto())->find(
                "conditions: estado = 'publicada'",
                "order: id DESC",
                "limit: 4"
            );
        }
    }
}