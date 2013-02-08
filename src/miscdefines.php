<?php

define("MINETEST_MAGIC_NUMBER","\x4f\x45\x74\x03");
define("SER_FMT_VER_INVALID", 255);
// Highest supported serialization version
define("SER_FMT_VER_HIGHEST", 25);
// Lowest supported serialization version
define("SER_FMT_VER_LOWEST", 0);
define("LATEST_PROTOCOL_VERSION", 16);

// Server's supported network protocol range
define("SERVER_PROTOCOL_VERSION_MIN", 13);
define("SERVER_PROTOCOL_VERSION_MAX", LATEST_PROTOCOL_VERSION);

// Client's supported network protocol range
define("CLIENT_PROTOCOL_VERSION_MIN", 13);
define("CLIENT_PROTOCOL_VERSION_MAX", LATEST_PROTOCOL_VERSION);

// Constant that differentiates the protocol from random data and other protocols
define("PROTOCOL_ID", 0x4f457403);

define("PASSWORD_SIZE", 28);
// Maximum password length. Allows for
                               // base64-encoded SHA-1 (27+\0).
define("IACTION_MOVE", 0);
define("IACTION_DROP", 1);
define("IACTION_CRAFT", 2);
?>
