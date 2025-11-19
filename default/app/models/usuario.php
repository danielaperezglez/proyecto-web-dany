<?php
/**
 * Modelo Usuario
 */
class Usuario extends ActiveRecord
{
    // Indica la tabla correcta
    protected $table = 'usuarios';

    /**
     * Encripta la contraseña antes de guardar
     */
    protected function before_save()
    {
        if (isset($this->password) && !empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
    }

    /**
     * Verifica una contraseña
     *
     * @param string $password
     * @return boolean
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Busca usuario por username
     *
     * @param string $username
     * @return Usuario|null
     */
    public static function findByUsername($username)
    {
        return (new self())->find_first("username = '$username'");
    }

    /**
     * Busca usuario por email activo
     *
     * @param string $email
     * @return Usuario|null
     */
    public static function findByEmail($email)
    {
        return (new self())->find_first("email = '$email' AND activo = 1");
    }

    /**
     * Login automático después de crear usuario
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public static function loginAfterCreate($email, $password)
    {
        $usuario = self::findByEmail($email);
        if ($usuario && $usuario->verifyPassword($password)) {
            Session::set('auth_user', $usuario->to_array());
            return true;
        }
        return false;
    }
}
