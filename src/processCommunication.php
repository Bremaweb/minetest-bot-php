<?php
require_once 'servercommands.php';
//$testPacket="\x4f\x45\x74\x03\x00\x00\x00\x01\x00\x10\x19\x72\x61\x72\x6b\x65\x6e\x69\x6e\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x55\x62\x2b\x48\x57\x64\x71\x4a\x34\x38\x36\x47\x49\x73\x32\x47\x4e\x74\x6e\x47\x31\x4c\x64\x4a\x48\x4e\x4d\x00\x00\x0d\x00\x10";
function processCommandToClient($packet) {
    $magicNum=substr($packet,0,4);
    $senderPeerId=substr($packet, 4,2);
    $packetChannel=substr($packet,6,1);

    $payload=substr($packet, 9);
    global $recommended_send_interval;
    global $mapseed;
    global $playerpos;
    global $knownMap;
    $headerByte = ord(substr($payload, 0, 1));
    $datasize = strlen($payload);
    print $headerByte;
    print "\n";
    print CLIENT_INIT;
    switch ($headerByte) {
        case CLIENT_INIT:
            //var_dump(($magicNum),($senderPeerId), ($packetChannel), $payload);
           // print("DUMMY!");
            /*sendCmd(SERVER_INIT2); TODO: Implement sending SERVER_INIT2*/
            switch ($datasize){
                case 2+1+6+8+4:
                    /*TODO: Unpack codes for data... */
            }
            break;
        case CLIENT_ACCESS_DENIED:
            die("Connect [FATAL]:Connect failed reason: Access denied");
            break;
        case CLIENT_REMOVENODE:
            $nodepos=deserializeNodePos(substr($payload,2));
    }
}
function deserializeNodePos($string){
    /*TODO: IMPLEMENT*/
}

//processCommandToClient($testPacket);
?>
