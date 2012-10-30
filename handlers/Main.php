<?php

class Main extends Handlers {

    protected $viewFile = 'dashboard.php';

    public function get() {

        $this->assign(Queries::get_stats());

        $this->getView();
    }

}