<?php
class ImpactoSocial extends ActiveRecord {

    public function initialize() {
        $this->belongs_to('donadores', 'model: Donadores', 'fk: donador_id');

        $this->validates_presence_of('donador_id', ['message' => 'EL DONADOR ES OBLIGATORIO']);
        $this->validates_presence_of('tipo_donacion', ['message' => 'EL TIPO DE DONACIÓN ES OBLIGATORIO']);
        $this->validates_numericality_of('cantidad_total', ['message' => 'LA CANTIDAD TOTAL DEBE SER NUMÉRICA']);
        $this->validates_length_of('descripcion', ['max' => 1000, 'message' => 'LA DESCRIPCIÓN NO PUEDE EXCEDER 1000 CARACTERES']);
    }

    // Validación de valores permitidos
    public function before_save() {
        $permitidos = ['comida', 'medicinas', 'juguetes', 'otro'];
        if (!in_array($this->tipo_donacion, $permitidos)) {
            Flash::error('EL TIPO DE DONACIÓN NO ES VÁLIDO');
            return false; // cancela el guardado
        }
    }
}
