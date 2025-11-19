ç<?php
/**
 * Clase para manejo de autenticación
 */
class Auth
{
    /**
     * Clave de sesión para el usuario
     */
    const SESSION_KEY = 'auth_user';

    /**
     * Intenta autenticar un usuario por email o username
     *
     * @param string $usernameOrEmail
     * @param string $password
     * @return boolean
     */
    public static function login($usernameOrEmail, $password)
    {
        // Buscar el usuario activo por email o username
        $user = (new Usuario())->find_first(
            "activo = 1 AND (username = '$usernameOrEmail' OR email = '$usernameOrEmail')"
        );

        if ($user && password_verify($password, $user->password)) {
            // Guardar sesión correctamente
            Session::set(self::SESSION_KEY, [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'rol_id'   => $user->rol_id
            ]);
            return true;
        }

        return false;
    }

    /**
     * Cierra la sesión del usuario
     */
    public static function logout()
    {
        Session::delete(self::SESSION_KEY);
    }

    /**
     * Verifica si hay un usuario autenticado
     *
     * @return boolean
     */
    public static function check()
    {
        return Session::has(self::SESSION_KEY);
    }

    /**
     * Obtiene los datos del usuario autenticado
     *
     * @return array|null
     */
    public static function user()
    {
        return Session::get(self::SESSION_KEY);
    }

    /**
     * Obtiene el ID del usuario autenticado
     *
     * @return int|null
     */
    public static function id()
    {
        $user = self::user();
        return $user ? $user['id'] : null;
    }

    /**
     * Verifica si el usuario tiene un rol específico
     *
     * @param int $rolId
     * @return boolean
     */
    public static function hasRole($rolId)
    {
        $user = self::user();
        return $user && $user['rol_id'] == $rolId;
    }

    /**
     * Requiere autenticación, redirige si no está logueado
     *
     * @param string $redirect
     */
    public static function require($redirect = 'session')
    {
        if (!self::check()) {
            Router::redirect($redirect);
        }
    }

    /**
     * Requiere un rol específico
     *
     * @param int $rolId
     * @param string $redirect
     */
    public static function requireRole($rolId, $redirect = 'session')
    {
        if (!self::hasRole($rolId)) {
            Router::redirect($redirect);
        }
    }
}
