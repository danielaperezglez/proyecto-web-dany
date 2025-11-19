<?php
class HistoriasImpacto extends ActiveRecord {

    public function initialize() {
        // Relaciones
        $this->belongs_to('campana');   // campana_id
        $this->belongs_to('donadores');   // donador_id

        // Validaciones
        $this->validates_presence_of('titulo', [
            'message' => 'EL TÍTULO ES OBLIGATORIO'
        ]);
        $this->validates_length_of('titulo', [
            'min' => 3,
            'max' => 150,
            'message' => 'EL TÍTULO DEBE TENER ENTRE 3 Y 150 CARACTERES'
        ]);
        $this->validates_presence_of('descripcion', [
            'message' => 'LA DESCRIPCIÓN ES OBLIGATORIA'
        ]);
        $this->validates_length_of('imagen', [
            'max' => 255,
            'message' => 'EL NOMBRE DE LA IMAGEN NO PUEDE EXCEDER 255 CARACTERES'
        ]);
    }

    // Validación manual de estado
    protected function before_validation() {
        $estados_validos = ['publicada', 'no publicada'];
        if (!in_array($this->estado, $estados_validos)) {
            $this->estado = 'no publicada'; // valor por defecto si es inválido
        }
        return true;
    }

    // Método para obtener URL de la imagen
    public function getImagenUrl() {
        return $this->imagen ? "/proyecto/default/public/storage/historias_impacto/" . $this->imagen : "/proyecto/default/public/img/no-image.png";
    }
}

