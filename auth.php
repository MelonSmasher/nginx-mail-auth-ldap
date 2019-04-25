<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/helpers/Status.php';
require __DIR__ . '/helpers/Auth.php';
require __DIR__ . '/helpers/Helper.php';

use Dotenv\Dotenv;
use Status as AuthStatus;
use Helper as Help;

$username = $_SERVER["HTTP_AUTH_USER"];
$password = $_SERVER["HTTP_AUTH_PASS"];
$protocol = $_SERVER["HTTP_AUTH_PROTOCOL"];

// Did we get the vars we need?
if (!isset($username) || !isset($password)) {
    AuthStatus::fail();
    exit;
}

// Load our conf
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

if (!Help::checkEmail($username)) {
    $username = $username . '@' . getenv('AUTH_DOMAIN');
}

$authParams = [
    'hosts' => explode(',', getenv('LDAP_HOSTS')),
    'base_dn' => getenv('LDAP_BASE_DN'),
    'username' => $username,
    'password' => str_replace(
        '%25',
        '%',
        str_replace(
            '%20',
            ' ',
            $password
        )
    ),
];

// Set the backend ip
$backend_ip = getenv('BACKEND_IP', '127.0.0.1');

// Set the backend port
$backend_port = getenv('BACKEND_PORT_POP') ?? 110;
if ($protocol == "imap") {
    $backend_port = getenv('BACKEND_PORT_IMAP') ?? 143;
}
if ($protocol == "smtp") {
    $backend_port = getenv('BACKEND_PORT_SMTP') ?? 25;
}

// Create a new authenticator instance
$auth = new Auth($authParams);

// Authenticate the user or fail
if (!$auth->authuser()) {
    AuthStatus::fail();
    exit;
}

// We passed!
AuthStatus::pass($backend_ip, $backend_port);
