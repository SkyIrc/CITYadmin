<?php

namespace skies\city;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.city
 */
class CityStatus {


    /**
     * @var \skies\city\City
     */
    private $city;

    /**
     * Array holding the raw info
     *
     * @var array
     */
    private $info = [];


    /**#@+
     * Info values
     */

    private $name = '';
    private $map = '';
    private $numPlayers = 0;
    private $maxPlayers = 0;
    private $players = [];

    /**#@-*/

    /**
     * Fetch status of a CITY server
     *
     * @param City $city
     */
    public function __construct(\skies\city\City $city) {

        // Save our city
        $this->city = $city;

        $this->info = self::serverInfoV05($this->city->getHost(), $this->city->getPort());

        if($this->info === false)
            return false;

        // Save stuff
        $this->name = $this->info['name'];
        $this->map = $this->info['map'];
        $this->maxPlayers = $this->info['maxplayers'];
        $this->numPlayers = $this->info['numplayers'];
        $this->players = $this->info['players'];

    }

    public function getMap() {
        return $this->map;
    }

    public function getMaxPlayers() {
        return $this->maxPlayers;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumPlayers() {
        return $this->numPlayers;
    }

    public function getPlayers() {
        return $this->players;
    }


    /**
     * Fetch status of a Teeworlds 0.5.x server
     *
     * @param string $server Host
     * @param int $port      Port
     *
     * @return array|bool Info
     */
    public static function serverInfoV05($server, $port) {

        $socket = stream_socket_client('udp://'.$server.':'.$port , $errno, $errstr, "2");
        stream_set_timeout($socket, "2");
        if(!fwrite($socket, "\xff\xff\xff\xff\xff\xff\xff\xff\xff\xffgief")) return false;
        stream_set_timeout($socket, "2");
        $response = fread($socket, 2048);

        if($response) {

            $test = explode("\x00",$response);
            $players = array();

            for($i = 0; $i <= $test[6]*2 ; $i += 2) {
                if(isset($test[$i+8]) && isset($test[$i+8+1]))
                    $players[] = array("name" => $test[$i+8],"score" => $test[$i+8+1]);
            }

            $tmp = array(
                "version" => str_replace("info","",str_replace("\xff","",$test[0])),
                "name" => $test[1],
                "map" => $test[2],
                "gametype" => $test[3],
                "flags" => $test[4],
                "progression" => $test[5],
                "numplayers" => $test[6],
                "maxplayers" => $test[7],
                "players" => $players);

            return $tmp;

        }

        else
            return false;

    }

}

?>