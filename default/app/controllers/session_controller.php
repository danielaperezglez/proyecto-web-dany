<?php
class SessionController extends AppController
{
    public function index()
    {
        if (Auth::check()) {
            Redirect::to('index');
            return;
        }

        View::template('login'); // Usa plantilla login.phtml
        View::select('index');   // Muestra app/views/session/index.phtml
    }

    public function login()
    {
        if (Input::hasPost('login')) {
            $login = Input::post('login');
            $email = $login["email"];
            $password = $login["password"];

            $usuario = (new Usuario())->find_first("email = '$email'");

            if ($usuario && password_verify($password, $usuario->password)) {
                Session::set('auth_user', $usuario->to_array());
                Flash::valid("✅ Bienvenido " . $usuario->username);
                Redirect::to('index');
            } else {
                Flash::error("❌ Credenciales incorrectas");
                Redirect::to('session/index');
            }
        }
    }

    public function logout()
    {
        Session::delete('auth_user');
        Flash::info("👋 Hasta luego");
        Redirect::to('session/index');
    }
}












