<?php

require_once 'servercommands.php';

function sendTOSERVER_INIT($username, $passphrase) {

    $packetToSend = chr(SERVER_INIT);
    $packetToSend.=chr(25);
    $packetToSend.=str_pad($username, 20, chr(0), STR_PAD_RIGHT);
    if (preg_match("/^[A-Za-z0-9]{27}$/", $passphrase) != 1) {
        die("The passphrase must be 27 characters, which may be alphanumeric, with case sensitivity.");
    }
    $packetToSend.=$passphrase . chr(0) . serializeU16(13) . serializeU16(25);
    sendPacket(formReliablePacket(formOriginalPacket("")));
    sendPacket(formOriginalPacket($packetToSend));

}

function sendTOSERVER_PLAYERPOS() {
    $packet = chr(SERVER_PLAYERPOS);
    $packet.=$knownmap->player->pos->getAsV3F100();
}

?>
