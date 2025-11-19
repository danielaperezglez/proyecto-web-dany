<?php
class Donaciones extends ActiveRecord {

    public function initialize() {
        $this->belongs_to('donador');
        $this->belongs_to('centro', 'model: Mapa', 'fk: centro_id');
        $this->belongs_to('campana');

        // Validaciones
        $this->validates_presence_of('donador_id', ['message'=>'El donador es obligatorio']);
        $this->validates_presence_of('tipo_donacion', ['message'=>'El tipo de donación es obligatorio']);
        $this->validates_numericality_of('cantidad', ['message'=>'La cantidad debe ser numérica', 'allow_null'=>true]);

        // Validar longitud de descripción
        $this->validates_length_of('descripcion', ['max'=>1000, 'message'=>'La descripción no puede exceder 1000 caracteres']);
    }

    // Validación personalizada para tipo_donacion
    protected function before_save() {
        $tipos_validos = ['comida', 'medicinas', 'juguetes', 'otro'];
        if (!in_array($this->tipo_donacion, $tipos_validos)) {
            Flash::error("El tipo de donación no es válido");
            return false; // Evita guardar el registro
        }
        return true;
    }
}
