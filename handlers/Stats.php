<?php

class Stats extends Handlers {

    public function get_xhr() {
        echo json_encode(Queries::get_stats());
    }

}