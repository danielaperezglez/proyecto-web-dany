<?php
class Campana extends ActiveRecord {

    public function initialize() {
        // Relación: una campaña puede tener muchas donaciones
        $this->has_many('donaciones', 'model: Donaciones', 'fk: campana_id');

        // Relación: una campaña puede tener muchas historias_impacto de impacto
        $this->has_many('historias_impacto', 'model: HistoriasImpacto', 'fk: campana_id');

        // Validaciones básicas
        $this->validates_presence_of('nombre', ['message' => 'EL NOMBRE ES OBLIGATORIO']);
        $this->validates_length_of('nombre', ['min' => 2, 'max' => 150, 'message' => 'EL NOMBRE DEBE TENER ENTRE 2 Y 150 CARACTERES']);
        $this->validates_length_of('descripcion', ['max' => 2000, 'message' => 'LA DESCRIPCIÓN NO PUEDE EXCEDER 2000 CARACTERES']);

    }
}

