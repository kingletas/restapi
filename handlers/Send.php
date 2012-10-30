<?php

class Send extends Handlers {

    function post() {
        if (isset($_POST['payload']) && strlen(trim($_POST['payload'])) > 0) {
            Queries::send_payload($_POST['payload']);
        }
        header("Location: /");
    }

}