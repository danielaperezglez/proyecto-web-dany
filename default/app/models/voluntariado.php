<?php
class Voluntariado extends ActiveRecord {
    public function initialize() {
        // Relaciones
        $this->belongs_to('donador', 'model: Donadores', 'fk: donador_id');
        $this->belongs_to('centro', 'model: Mapa', 'fk: centro_id');
        $this->belongs_to('campana', 'model: Campana', 'fk: campana_id');

        // Validaciones
        $this->validates_presence_of('donador_id', ['message' => 'EL DONADOR ES OBLIGATORIO']);
        $this->validates_length_of('area_interes', ['max' => 100, 'message' => 'EL ÁREA DE INTERÉS NO PUEDE EXCEDER 100 CARACTERES']);
        $this->validates_length_of('disponibilidad', ['max' => 100, 'message' => 'LA DISPONIBILIDAD NO PUEDE EXCEDER 100 CARACTERES']);
        $this->validates_length_of('horario_preferido', ['max' => 100, 'message' => 'EL HORARIO PREFERIDO NO PUEDE EXCEDER 100 CARACTERES']);
        $this->validates_length_of('comentarios', ['max' => 1000, 'message' => 'LOS COMENTARIOS NO PUEDEN EXCEDER 1000 CARACTERES']);
    }

    public function before_save() {
        if (!in_array($this->estatus, ['activo', 'inactivo'])) {
            $this->estatus = 'activo'; // Valor por defecto
        }
        return true;
    }
}

