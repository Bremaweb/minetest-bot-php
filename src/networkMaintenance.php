<?php
$packetsAwaitingSend=array();
$lastSend=microtime(true);
function FiveSecPoll(){
    global $lastSend;
    global $packetsAwaitingSend;
    foreach ($packetsAwaitingSend as $seqnumToSend => $data) {
        FormReliablePacketWithSeqnum($seqnum, $data);
    }
    if ((microtime(true)-$lastSend)>=4){
        SendPing();
    }
}
//function
?>
