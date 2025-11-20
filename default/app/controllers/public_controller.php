<?php

class PublicController extends AppController {

    public function index() {
        View::select(null);
        echo "KumbiaPHP funcionando correctamente";
    }

}
