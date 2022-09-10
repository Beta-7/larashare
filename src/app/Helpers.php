<?php
namespace App;
class Helpers{
    static function getDownloadEpoch($userInput){
        switch ($userInput) {
            case "never":
                return -1;
            case "delete":
                return 0;
            case 1:
            case 2:
            case 3:
            case 5:
            case 10:
            case 14:
                return now()->addDay($userInput)->timestamp;
        }
    }
    static function getReadableTime($input){
        if($input == -1) return "Never";
        if($input == 0) return "One time download";
        return  date("Y-m-d H:i:s", substr($input, 0, 10));

    }
}