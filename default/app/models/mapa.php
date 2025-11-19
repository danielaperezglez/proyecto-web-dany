<?php
class Mapa extends ActiveRecord {

    public function initialize() {
        // Relación ejemplo: un centro puede tener muchas donaciones
        $this->has_many('donaciones', 'model: Donaciones', 'fk: centro_id');

        // Validaciones
        $this->validates_presence_of('nombre', ['message' => 'EL NOMBRE ES OBLIGATORIO']);
        $this->validates_length_of('nombre', ['min' => 2, 'max' => 150, 'message' => 'EL NOMBRE DEBE TENER ENTRE 2 Y 150 CARACTERES']);

        $this->validates_presence_of('direccion', ['message' => 'LA DIRECCIÓN ES OBLIGATORIA']);
        $this->validates_length_of('direccion', ['min' => 5, 'max' => 200, 'message' => 'LA DIRECCIÓN DEBE TENER ENTRE 5 Y 200 CARACTERES']);

        $this->validates_presence_of('ciudad', ['message' => 'LA CIUDAD ES OBLIGATORIA']);
        $this->validates_presence_of('estado', ['message' => 'EL ESTADO ES OBLIGATORIO']);

        $this->validates_length_of('necesidades', ['max' => 1000, 'message' => 'LAS NECESIDADES NO PUEDEN EXCEDER 1000 CARACTERES']);
        $this->validates_length_of('telefono', ['max' => 20, 'message' => 'EL TELÉFONO NO PUEDE EXCEDER 20 CARACTERES']);

        $this->validates_presence_of('email', ['message' => 'EL EMAIL ES OBLIGATORIO']);
        $this->validates_format_of('email', '/^[\w\.-]+@[\w\.-]+\.\w+$/', ['message' => 'EL EMAIL NO TIENE UN FORMATO VALIDO']);
    }
}
