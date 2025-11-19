<?php

class PerfilController extends AppController
{

    public function index()
    {
        $this->title = '👤 Mi Perfil';
        $this->usuario = (new Usuario())->find(Auth::user()["id"]);

    }

    public function editar()
    {
        $this->title = '✏️ Editar Perfil';
        $this->usuario = (new Usuario())->find(Auth::user()["id"]);

        if (Input::hasPost('usuario')) {
            $data = Input::post('usuario');

            // Validar email único (excepto el actual)
            if ($data['email'] !== $this->usuario->email) {
                $existe = (new Usuario())->find_first("email = '{$data['email']}'");
                if ($existe) {
                    Flash::error('❌ El email ya está en uso');
                    return;
                }
            }

            // Si hay nueva contraseña, validar
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    Flash::error('❌ La contraseña debe tener al menos 6 caracteres');
                    return;
                }
                if ($data['password'] !== $data['password_confirm']) {
                    Flash::error('❌ Las contraseñas no coinciden');
                    return;
                }
            } else {
                // Si no hay nueva contraseña, mantener la actual
                unset($data['password']);
            }

            unset($data['password_confirm']);

            if ($this->usuario->update($data)) {
                // Actualizar sesión con nuevos datos
                Auth::login($this->usuario->email, $data["password"]);
                Flash::info('✅ Perfil actualizado correctamente');
                Redirect::to('perfil');
            } else {
                Flash::error('❌ Error al actualizar el perfil');
            }
        }
    }
}