<?php

class Bform {

    protected static function attrsdefaut($attrs, $defaults)
    {
        if (!is_array($attrs)) {
            $attrs = [];
        }

        foreach ($defaults as $k => $v) {
            if (isset($attrs[$k])) {
                if (strpos($attrs[$k], $v) === false) {
                    $attrs[$k] .= ' '.$v;
                }
            } else {
                $attrs[$k] = $v;
            }
        }
        return $attrs;
    }

    // Botones
    public static function btn_aceptar($text = "Aceptar", $attrs = [])
    {
        $text = "💾 ".$text;
        $attrs = self::attrsdefaut($attrs, ["class" => "btn btn-success"]);
        return Form::submit($text, Tag::getAttrs($attrs));
    }

    public static function btn_regresar($text = "Regresar", $attrs = [])
    {
        $text = "🔙 ".$text;
        $attrs = self::attrsdefaut($attrs, ["class" => "btn btn-primary"]);
        return Form::submit($text, Tag::getAttrs($attrs));
    }

    public static function btn_cancelar($text = "Cancelar", $attrs = [])
    {
        $text = "❌ ".$text;
        $attrs = self::attrsdefaut($attrs, ["class" => "btn btn-secondary"]);
        return Form::submit($text, Tag::getAttrs($attrs));
    }

    // Input de texto
    public static function input_text($field, $attrs = [], $value = null)
    {
        $attrs = self::attrsdefaut($attrs, ["class" => "form-control"]);
        return Form::input('text', $field, Tag::getAttrs($attrs), $value);
    }

    // Texto + input juntos
    public static function text_label($label, $field, $attrs = [], $value = null)
    {
        $attrs = self::attrsdefaut($attrs, ["class" => "form-control"]);
        $html = "<label>$label</label>";
        return $html . Form::input('text', $field, Tag::getAttrs($attrs), $value);
    }

    // Label + select
    public static function select_label($label, $field, $options = [], $attrs = [], $selected = null)
    {
        $attrs = self::attrsdefaut($attrs, ["class" => "form-control"]);
        $html = "<label>$label</label>";
        $html .= Form::select($field, $options, $selected, Tag::getAttrs($attrs));
        return $html;
    }
}