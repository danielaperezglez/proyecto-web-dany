<?php
class Donadores extends ActiveRecord {

    public function initialize() {
        // Relación: un donador puede tener muchas donaciones
        $this->has_many('donaciones', 'model: Donaciones', 'fk: donador_id');

        // Validaciones
        $this->validates_presence_of('nombre', ['message' => 'EL NOMBRE ES OBLIGATORIO']);
        $this->validates_length_of('nombre', ['min' => 2, 'max' => 100, 'message' => 'EL NOMBRE DEBE TENER ENTRE 2 Y 100 CARACTERES']);

        $this->validates_length_of('telefono', ['max' => 20, 'message' => 'EL TELÉFONO NO PUEDE EXCEDER 20 CARACTERES']);
        $this->validates_length_of('email', ['max' => 120, 'message' => 'EL EMAIL NO PUEDE EXCEDER 120 CARACTERES']);
        $this->validates_length_of('direccion', ['max' => 160, 'message' => 'LA DIRECCIÓN NO PUEDE EXCEDER 160 CARACTERES']);
        $this->validates_length_of('frecuencia_donacion', ['max' => 50, 'message' => 'LA FRECUENCIA NO PUEDE EXCEDER 50 CARACTERES']);

        $this->validates_presence_of('tipo', ['message' => 'EL TIPO DE DONADOR ES OBLIGATORIO']);
    }

    // Validación personalizada para tipo
    protected function before_save() {
        $tipos_validos = ['individual', 'empresa'];

        if (!in_array($this->tipo, $tipos_validos)) {
            Flash::error("Tipo de donador no válido");
            return false; // Evita guardar
        }

        // Normalizar email a minúsculas
        if ($this->email) {
            $this->email = strtolower($this->email);
        }

        return true;
    }
}

