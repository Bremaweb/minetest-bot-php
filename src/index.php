<?php

define("CMDTOSERVER", 1); //Otherwise to client
define("CMDSENDRELIABLE", 2);
define("CMDACKNEEDED", 4);
define("CMDACTIONRELIABLE", 8);
if (PHP_SAPI != "cli") {
    die("Main [FATAL]:Must be run in CLI mode!");
}
if ($argc != 3) {
    die("Main [FATAL]:Improper arguments!\r\n            Usage: minetestbot.php host port controlfile");
}
$serverIp = $argv[1];
$serverPort = $argv[2];
$controlFile = $argv[3];
$serverSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (false == (@socket_connect($serverSocket, $serverIp, $serverPort))) {
   die("Connect [FATAL]:Connect failed: reason: " . socket_strerror(socket_last_error($serverSocket)) . "\n");
}
$controlHandle = fopen($controlFile, "rb");
$fileReadBuffer = fread($controlHandle, 8);
if (strcasecmp($fileReadBuffer, "mtbotpurephp")!=0){
    die("File [FATAL]:Impropper file header, perhaps not a MTBot control file?");
}
?>
