<?php

namespace guestbook\Controllers;

class LogoutController {

    // TODO 3.raw: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3.raw) transforming data
    public function execute() {
        // $_SESSION['auth'] = false;
        session_destroy();
        header('Location: /');
        die;
    }

    // TODO 4: RENDER: 1) view (html) 2) data (from php)
    public function renderView($arguments = []) {

    }
}