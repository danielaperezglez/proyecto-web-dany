<?php
require_once CORE_PATH . 'kumbia/controller.php';

abstract class AppController extends Controller
{
    final protected function initialize()
    {
        $this->title = "WEB DANIELA";
        $this->subtitle = "SECCION";
        View::template("adminlte");
    }

    final protected function finalize()
    {

    }
}

