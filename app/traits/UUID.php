<?php
    namespace app\traits;

    class UUID {
        public static function UUIDv4():string {
            $data = random_bytes(length: 16);
            $data[6] = chr(codepoint: ord(character: $data[6]) & 0x0f | 0x40);
            $data[8] = chr(codepoint: ord(character: $data[8]) & 0x3f | 0x80);
            return vsprintf(format: '%s%s-%s-%s-%s-%s%s%s', values: str_split(string: bin2hex(string: $data), length: 4));
        }
    }