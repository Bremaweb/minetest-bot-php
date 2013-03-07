<?php
$nodes=array();
ini_set('memory_limit', '1024M');
$nodes[0][0][0]="CONTENT_IGNORE";//Initialize array, overwritten later anyway.
require_once 'servercommands.php';
require_once 'miscdefines.php';
require_once 'hardwiredContentTypes.php';
require_once 'network.php';
require_once 'processCommunication.php';
require_once 'packet.php';
require_once 'serialize.php';
require_once 'lowLevelNet.php';
require_once 'networkMaintenance.php';
require_once 'sendComm.php';
var_dump(unpack("n",serializeU16(65500)));
var_dump(unpack('n', "\xff\xdc"));

require_once 'knownMap.php';
//define("CMDTOSERVER", 1); //Otherwise to client
//define("CMDSENDRELIABLE", 2);
//define("CMDACKNEEDED", 4);
//define("CMDACTIONRELIABLE", 8);
if (PHP_SAPI != "cli") {
    die("Main [FATAL]:Must be run in CLI mode!");
}
if ($argc != 6) {
    var_dump($argv);
    var_dump($argc);
    die("Main [FATAL]:Improper arguments!\r\n            Usage: minetestbot.php <host> <port> <username> <pass> <controlfile>");

}
$serverIp = $argv[1];
$serverPort = $argv[2];
$username = $argv[3];
$password = $argv[4];
$controlFile = $argv[5];
$serverSocket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

if (false == (socket_connect($serverSocket, $serverIp, $serverPort))) {
    die("Connect [FATAL]:Connect failed: reason: " . socket_strerror(socket_last_error($serverSocket)) . "\n");
}
socket_set_nonblock($serverSocket);
//socket_write($serverSocket, "FOOBARFOOBAR");
require_once $controlFile;
//$controlHandle = fopen($controlFile, "rb");
//$fileReadBuffer = fread($controlHandle, 8);
//if (strcasecmp($fileReadBuffer, "mtbotpurephp")!=0){
//    die("File [FATAL]:Impropper file header, perhaps not a MTBot control file?");
//}
////TODO: Get password from file
sendTOSERVER_INIT($username, $password);
//$connectSeqNum = sendPacket(FormReliablePacket(FormOriginalPacket("")));
//blockingLoopWaitPacketAck($connectSeqNum);
//while($ourPeerId==0){
//    readNetworkPacket($serverSocket);
//}

while(true){
    usleep(100);
    readNetworkPacket($serverSocket);
}
?>
