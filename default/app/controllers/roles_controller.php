<?php

class RolesController extends AppController
{
    public function index()
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }
        
        $this->roles = (new Rol())->find();
    }
    
    public function crear()
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }
        
        if (Input::hasPost('rol')) {
            $data = Input::post('rol');
            $rol = new Rol();
            $rol->nombre = $data['nombre'];
            $rol->descripcion = $data['descripcion'];
            $rol->icono = $data['icono'];
            $rol->activo = 1;
            
            if ($rol->save()) {
                Flash::valid('✅ Rol creado exitosamente');
                Redirect::to('roles');
            } else {
                Flash::error('❌ Error al crear rol');
            }
        }
    }
    
    public function editar($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }
        
        $this->rol = (new Rol())->find($id);
        
        if (!$this->rol) {
            Flash::error('❌ Rol no encontrado');
            Redirect::to('roles');
        }
        
        if (Input::hasPost('rol')) {
            $data = Input::post('rol');
            $this->rol->nombre = $data['nombre'];
            $this->rol->descripcion = $data['descripcion'];
            $this->rol->icono = $data['icono'];
            
            if ($this->rol->save()) {
                Flash::valid('✅ Rol actualizado exitosamente');
                Redirect::to('roles');
            } else {
                Flash::error('❌ Error al actualizar rol');
            }
        }
    }
    
    public function toggle_estado($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }
        
        $rol = (new Rol())->find($id);
        
        if ($rol) {
            // No permitir desactivar rol admin
            if ($rol->nombre === 'admin' && $rol->activo) {
                Flash::error('❌ No se puede desactivar el rol de administrador');
                Redirect::to('roles');
            }
            
            $rol->activo = $rol->activo ? 0 : 1;
            $estado = $rol->activo ? 'activado' : 'desactivado';
            
            if ($rol->save()) {
                Flash::valid("✅ Rol $estado exitosamente");
            } else {
                Flash::error('❌ Error al cambiar estado');
            }
        }
        
        Redirect::to('roles');
    }
    
    public function eliminar($id)
    {
        if (!Auth::check() || !Auth::hasRole('admin')) {
            Flash::warning('⚠️ Acceso denegado');
            Redirect::to('dashboard');
        }
        
        $rol = (new Rol())->find($id);
        
        if ($rol) {
            // No permitir eliminar rol admin
            if ($rol->nombre === 'admin') {
                Flash::error('❌ No se puede eliminar el rol de administrador');
                Redirect::to('roles');
            }
            
            // Verificar si hay usuarios con este rol
            $usuarios = (new Usuario())->count("rol = '{$rol->nombre}'");
            if ($usuarios > 0) {
                Flash::error("❌ No se puede eliminar el rol. Hay $usuarios usuarios asignados");
                Redirect::to('roles');
            }
            
            if ($rol->delete()) {
                Flash::valid('✅ Rol eliminado exitosamente');
            } else {
                Flash::error('❌ Error al eliminar rol');
            }
        }
        
        Redirect::to('roles');
    }
}