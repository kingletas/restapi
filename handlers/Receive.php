<?php

class Receive extends Handlers {

    function get_xhr() {
        echo json_encode(array("payload" => Queries::receive_payload()));
    }

}