<?php
function execPrint($command) {
    $result = array();
    exec($command, $result);
    print("<pre>");
    foreach ($result as $line) {
        print($line . "\n");
    }
    print("</pre>");
}

function verify_signature($body, $secret) {
    $headers = getallheaders();
    return hash_equals('sha256='.hash_hmac('sha256', $body, $secret), $headers['x-hub-signature-256']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Declare variables from config.php
     *
     * @var $github_secret_webhook string The secret to authenticate the webhook
     */
    include_once('config.php');

    if (verify_signature(stream_get_contents(STDIN), $github_secret_webhook)) {
        if ($_POST['ref'] == 'refs/heads/main') {
            execPrint("git pull");
        }
    }
}