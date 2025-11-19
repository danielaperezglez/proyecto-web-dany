<?php

class UsuariosController extends AppController
{
    public function index()
    {
        // Requiere autenticación de admin
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        $this->usuarios = (new Usuario())->find();
    }

    public function crear()
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        if (Input::hasPost('usuario')) {
            $data = Input::post('usuario');
            $usuario = new Usuario();
            $usuario->username = $data['username'];
            $usuario->email = $data['email'];
            $usuario->password = $data['password'];
            $usuario->rol = $data['rol'];
            $usuario->activo = 1;

            if ($usuario->save()) {
                Flash::valid('✅ Usuario creado exitosamente');
                Redirect::to('usuarios');
            } else {
                Flash::error('❌ Error al crear usuario');
            }
        }
    }

    public function editar($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        $this->usuario = (new Usuario())->find($id);

        if (!$this->usuario) {
            Flash::error('❌ Usuario no encontrado');
            Redirect::to('usuarios');
        }

        if (Input::hasPost('usuario')) {
            $data = Input::post('usuario');
            $this->usuario->username = $data['username'];
            $this->usuario->email = $data['email'];
            $this->usuario->rol = $data['rol'];

            if ($this->usuario->save()) {
                Flash::valid('✅ Usuario actualizado exitosamente');
                Redirect::to('usuarios');
            } else {
                Flash::error('❌ Error al actualizar usuario');
            }
        }
    }

    public function cambiar_password($id)
    {
        if (!Auth::check() || (!Auth::hasRole('admin') && Auth::id() != $id)) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        $this->usuario = (new Usuario())->find($id);

        if (Input::hasPost('password')) {
            $data = Input::post('password');

            // Verificar contraseña actual si no es admin
            if (!Auth::hasRole('admin')) {
                if (!password_verify($data['current'], $this->usuario->password)) {
                    Flash::error('❌ Contraseña actual incorrecta');
                    return;
                }
            }

            if ($data['new'] !== $data['confirm']) {
                Flash::error('❌ Las contraseñas no coinciden');
                return;
            }

            $this->usuario->password = $data['new'];

            if ($this->usuario->save()) {
                Flash::valid('✅ Contraseña cambiada exitosamente');
                Redirect::to('usuarios');
            } else {
                Flash::error('❌ Error al cambiar contraseña');
            }
        }
    }

    public function restablecer_password($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        $usuario = (new Usuario())->find($id);

        if ($usuario) {
            $nuevaPassword = 'temp' . rand(1000, 9999);
            $usuario->password = $nuevaPassword;

            if ($usuario->save()) {
                Flash::info("🔑 Contraseña restablecida a: $nuevaPassword");
            } else {
                Flash::error('❌ Error al restablecer contraseña');
            }
        }

        Redirect::to('usuarios');
    }

    public function toggle_estado($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        $usuario = (new Usuario())->find($id);

        if ($usuario) {
            $usuario->activo = $usuario->activo ? 0 : 1;
            $estado = $usuario->activo ? 'activado' : 'desactivado';

            if ($usuario->save()) {
                Flash::valid("✅ Usuario $estado exitosamente");
            } else {
                Flash::error('❌ Error al cambiar estado');
            }
        }

        Redirect::to('usuarios');
    }

    public function eliminar($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }

        if (Auth::id() == $id) {
            Flash::error('❌ No puedes eliminar tu propio usuario');
            Redirect::to('usuarios');
        }

        $usuario = (new Usuario())->find($id);

        if ($usuario && $usuario->delete()) {
            Flash::valid('✅ Usuario eliminado exitosamente');
        } else {
            Flash::error('❌ Error al eliminar usuario');
        }

        Redirect::to('usuarios');
    }
}