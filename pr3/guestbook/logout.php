<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

// TODO 2: ROUTING
// $_SESSION['auth'] = false;
session_destroy();
header('Location: /');
die;

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

// TODO 4: RENDER: 1) view (html) 2) data (from php)