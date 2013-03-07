<?php

define("POINTEDTHING_NOTHING", 0);
define("POINTEDTHING_NODE", 1);
define("POINTEDTHING_OBJECT", 2);

function deserializeNodePos($in) {

}

function deserializeNode($in) {
    global $knownNodes;
}

function deserializeU16($in) {
    $retval = unpack("nout", $in);
    return $retval[out];
}

function minetestWideToNarrow($in) {
    $out = "";
    $length = strlen($in);
    for ($i = 1; $i < $length; $i+=2) {
        $out = $out . $in[$i];
    }
    return $out;
}

function serializePointedThing($abovesurface, $undersurface, $type) {
    /*
     * Undersurface gets punched
     * Abovesurface is target for node place
     */
    $out = "";
    $out = $out . chr(0);
    $out.=chr($type);
    if ($type == POINTEDTHING_NODE) {
        $out = $out . serializeNodePos($undersurface);
        $out = $out . serializeNodePos($abovesurface);
    }return $out;
}

function serializeU16($in){
    $retval=pack("n", $in);
    //echo("Serialized $in to $retval");
    return $retval;

}

?>
