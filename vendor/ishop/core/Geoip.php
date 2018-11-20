<?php

namespace ishop;

use GeoIp2\Database\Reader;

class Geoip {

    protected $reader;

    use TSingletone;

    protected function __construct() {        
        
        $this->reader = new Reader(ROOT . '/vendor/geoip2/maxmind-db/GeoLite2-City.mmdb');
    }

    public function get($ip = null) {

        $result = self::getIp($ip);
        $ip = $result['ip_str'];

        if ($ip) {
            $record = $this->reader->city($ip);
            if (isset($record->raw['country']['names']['ru'])) {
                $result['geo_region'] = $record->raw['country']['names']['ru'] . ", ";
                $result['geo_region'] .= $record->raw['postal']['code'] . ", ";
                $result['geo_region'] .= $record->raw['subdivisions'][0]['names']['ru'] . ", ";
                $result['geo_region'] .= $record->raw['location']['time_zone'];
                $result['geo_city'] = $record->raw['city']['names']['ru'];
                $result['geo_reg'] = $record->raw['subdivisions'][0]['names']['ru'];
            }
        }

        return $result;
    }

    public static function getIp($ip = null) {
        //$ip = '94.154.90.22';
        //$ipstring = long2ip($ip);

        if (!$ip)
            $ip = detectIp();

        $result['ip_str'] = $ip;
        $result['ip_int'] = ip2long($ip);
        $result['geo_city'] = '';
        $result['geo_region'] = '';
        
        return $result; 
    }

}
