<?php

require_once 'servercommands.php';

$testPacket = "\x4f\x45\x74\x03\x00\x00\x00\x01\x00\x10\x19\x72\x61\x72\x6b\x65\x6e\x69\x6e\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x55\x62\x2b\x48\x57\x64\x71\x4a\x34\x38\x36\x47\x49\x73\x32\x47\x4e\x74\x6e\x47\x31\x4c\x64\x4a\x48\x4e\x4d\x00\x00\x0d\x00\x10";

function processCommandToClient($packet) {
    global $recommended_send_interval;
    global $privsarray;
    global $mapseed;
    global $playerpos;
    global $knownMap;
    $headerByte = ord(substr($packet, 0, 1));
    $datasize = strlen($packet);
    //print $headerByte . "\n";
    //print ord(substr($packet, 0, 1));
    //print "\n";
    print CLIENT_INIT;
    switch ($headerByte) {
        case CLIENT_INIT:
            var_dump(($magicNum), ($senderPeerId), ($packetChannel), $packet);
            print("DUMMY!");
            sendCmd(SERVER_INIT2, array());
            /* TODO: CRITICAL Implement sending SERVER_INIT2 */
            switch ($datasize) {
                case 2 + 1 + 6 + 8 + 4:
                /* TODO: Unpack codes for data... */
            }
            break;
        case CLIENT_ACCESS_DENIED:
            die("Connect [FATAL]:Connect failed reason: Access denied");
            break;
        case CLIENT_REMOVENODE:
            $nodepos = deserializeNodePos(substr($packet, 1, 6));
            removeNode($nodepos);
            break;
        case CLIENT_ADDNODE:
            $nodepos = deserializeNodePos(substr($packet, 1, 6));
            $node = deserializeNode(substr($packet, 7));
            addNode($nodepos, $node);
            break;
        case CLIENT_BLOCKDATA:
            /* TODO: CRITICAL FIGURE OUT MapNode::deSerialize in mapNode.cpp, as well as deserialize bulk. */
            print("Packet Handling [TODO]: CLIENT_BLOCKDATA received but not processed.\n");
            break;
        case CLIENT_INVENTORY:
            /* TODO: MEDIUM Figure out Inventory serialization!!!
             * * void InventoryList::deSerialize(std::istream &is) in inventory.cpp
             * void Inventory::deSerialize(std::istream &is) in inventory.cpp */
            print("Packet Handling [TODO]: CLIENT_INVENTORY received but not processed.\n");
            break;
        case CLIENT_CHAT_MESSAGE:
            $length = deserializeU16(substr($packet, 1, 2));
            $message = minetestWideToNarrow(substr($packet));
            processChatMsg($message);
            break;
        case CLIENT_MOVE_PLAYER:
            $playerpos = readV3F100(substr($packet, 1, 12));
            break;
        case CLIENT_DEATHSCREEN:
            print("Player [INFO]: Player died at " . $playerpos . toString() . ".\n");
            sendCmd(SERVER_RESPAWN, array());
            break;
        case CLIENT_PRIVILEGES:
            $numPrivs = deserializeU16(substr($packet, 1, 2));
            $privsarray = array();
            $packetPos = 3;
            for ($i = 1; $i <= numPrivs; $i++) {
                $thisIterationLength = deserializeU16(substr($packet, $packetPos, 2));
                $packetPos+=2;
                array_push($privsarray, substr($packet, $packetpos, $thisIterationLength));
                $packetPos+=$thisIterationLength;
            }
            $privs = implode(", ", $privsarray);
            print("Player [INFO]: Got privs: $privs\n");
            break;
        case CLIENT_INVENTORY_FORMSPEC:
            $length = deserializeU32(substr($packet, 1, 4));
            $invSpec = substr($packet, 5, $length);
            break;
        case CLIENT_DETACHED_INVENTORY:
            $nameLength=deserializeU16(substr($packet, 1, 2));
            $invName=substr($packet,3, $nameLength);
            $invBody=substr($packet,3+$nameLength);
            $detachedInvs[$invName]=deserializeInv($invBody); /* TODO: MEDUIM Deserialize Invs. Doesn't look that hard.
             * void InventoryList::deSerialize(std::istream &is) in inventory.cpp
             * void Inventory::deSerialize(std::istream &is) in inventory.cpp
             */
            break;
        case CLIENT_SHOW_FORMSPEC:
            print("ServerAction [INFO]: Not showing formspec, ignored.");
            break;

    }
}



function processChatMsg($message) {
    print "received chat message: ".$message . "\n";
}



?>
