<?php

$packetBuffer = array();

//require_once 'index.php';
define("BASE_HEADER_SIZE", 7);
define("PEER_ID_INEXISTENT", 0);
define("PEER_ID_SERVER", 1);
define("CHANNEL_COUNT", 3);
define("TYPE_CONTROL", 0);
define("CONTROLTYPE_ACK", 0);
define("CONTROLTYPE_SET_PEER_ID", 1);
define("CONTROLTYPE_PING", 2);
define("CONTROLTYPE_DISCO", 3);
define("TYPE_SPLIT", 2);
define("TYPE_RELIABLE", 3);
define("RELIABLE_HEADER_SIZE", 3);
define("SEQNUM_INITIAL", 65500);
$seqnumOut = SEQNUM_INITIAL;
$seqnumIn = SEQNUM_INITIAL;
$ourPeerId = PEER_ID_INEXISTENT;
$serverPeerId = PEER_ID_SERVER;
$bufferedPackets = array();

function readNetworkPacket($socket) {
    global $seqnumIn;
    global $seqnumOut;
    global $ourPeerId;
    global $serverPeerId;
    global $bufferedPackets;
    global $packetsAwaitingSend;
    $packet = socket_read($socket, 4096, PHP_BINARY_READ);
    if (strlen($packet) > 0) {
        echo 'Got packet!';
        $protocolId = substr($packet, 0, 4);
        $senderPeerId = deserializeU16(substr($packet, 4, 2));
        $channel = ord(substr($packet, 6, 1));
        $packetType = ord(substr($packet, 7, 1));
        switch ($packetType) {
            case TYPE_CONTROL:
                $controlType = ord(substr($packet, 8, 1));
                switch ($controlType) {
                    case CONTROLTYPE_ACK:
                        $seqnumAckd = deserializeU16(substr($packet, 9, 2));
                        unset($packetsAwaitingSend[$seqnumAckd]);
                        break;
                    case CONTROLTYPE_SET_PEER_ID:
                        $peerId = deserializeU16(substr($packet, 9, 1));
                        $ourPeerId = $peerId;
                        echo "Got our peer id!";
                        return true;
                        break;
                    case CONTROLTYPE_DISCO:
                        exit("Disconnected.");
                        break;
                }
                break;
            case TYPE_RELIABLE:
                $incomingSeqnum = deserializeU16(substr($packet, 8, 1));
                sendAck($incomingSeqnum);
                $packetPayload = substr($packet, 10);
                if (isset($bufferedPackets[$seqnumIn])) {
                    $seqnumIn++;
                    $seqnumIn = $seqnumIn % 65536;
                    readNetworkPacket($bufferedPackets[$seqnumIn]);
                }

                break;
            case TYPE_ORIGINAL:
                processCommandToClient(substr($packet, 8));
                break;
            case TYPE_SPLIT:
                //TODO Split packet
                break;
            default:
                return false;
                break;
        }
    }
}

function readNetworkPacketNested($packIn) {
    global $seqnumIn;
    global $seqnumOut;
    global $ourPeerId;
    global $serverPeerId;
    global $packetsAwaitingSend;
    global $bufferedPackets;
    $packetType = ord(substr($packet, 0, 1));
    switch ($packetType) {
        case TYPE_CONTROL:
            $controlType = ord(substr($packet, 1, 1));
            switch ($controlType) {
                case CONTROLTYPE_ACK:
                    $seqnumAckd = deserializeU16(substr($packet, 2, 2));
                    unset($packetsAwaitingSend[$seqnumAckd]);
                case CONTROLTYPE_SET_PEER_ID:
                    $peerId = deserializeU16(substr($packet, 2, 2));
                    $ourPeerId = $peerId;
                    echo "Got our peer ID!";
                    return true;
                    break;
                case CONTROLTYPE_DISCO:
                    exit("Disconnected.");
                    break;
            }
            break;
        case TYPE_RELIABLE:
            $incomingSeqnum = deserializeU16(substr($packet, 1, 1));
            sendAck($incomingSeqnum);
            $packetPayload = substr($packet, 3);
            if (isset($bufferedPackets[$seqnumIn])) {
                $seqnumIn++;
                $seqnumIn = $seqnumIn % 65536;
                readNetworkPacketNested($bufferedPackets[$seqnumIn]);
            }

            break;
        case TYPE_ORIGINAL:
            processCommandToClient(substr($packet, 1));
            break;
        case TYPE_SPLIT:
            //TODO Split packet
            break;
        default:
            return false;
            break;
    }
}

function sendPacket($packet) {
    global $serverSocket;
    socket_write($serverSocket, "\x4f\x45\x74\x03\x00\x01\x00" . $packet);
}

function formReliablePacket($packet) {
    global $seqnumOut;
    $packetOut = chr(3) . serializeU16($seqnumOut) . $packet;
    $seqnumOut++;
    return $packetOut;
}

function formOriginalPacket($packet) {
    if(strlen($packet)!=0){
    return chr(1) . chr(0) . $packet;
    }
    else{
        return chr(1);
    }
}

function sendPing() {
    global $serverSocket;
    $pingPacket = "\x4f\x45\x74\x03\x00\x01\x00" . chr(TYPE_CONTROL) . chr(CONTROLTYPE_PING);
    socket_write($serverSocket, $pingPacket);
}

function sendAck($seqnumToAck) {
    global $serverSocket;
    $ackpacket = "\x4f\x45\x74\x03\x00\x01\x00" . chr(TYPE_CONTROL) . chr(CONTROLTYPE_ACK) . serializeU16($seqnumToAck);
    socket_write($serverSocket, $ackPacket);
}

?>
