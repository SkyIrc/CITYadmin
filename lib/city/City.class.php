<?php

namespace skies\city;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.ciyt
 */
class City {

    /*
     * CONSTs
     */

    const TOP_KD = 1;
    const TOP_KILLS = 2;
    const TOP_DEATHS = 3;

    /*
     * end CONSTs
     */

    /**
     * Array holding all citys
     *
     * @var array<City>
     */
    public static $cities = [];

    /**
     * Current city
     *
     * @var \skies\city\City
     */
    public static $curCity;

    /**
     * How many CITYs do we have?
     *
     * @var int
     */
    public static $number = 0;

    /**
     * How is this city called (shortened) (e.g. moro)
     *
     * @var string
     */
    private $name = '';

    /**
     * Long title (e.g. moro's CITY server)
     *
     * @var string
     */
    private $title = '';

    /**
     * Our very own MySQL!
     *
     * @var \skies\system\database\MySQL
     */
    private $db;

    /**
     * Table name for accounts
     *
     * @var string
     */
    private $table = '';

    /**
     * Short title
     *
     * @var string
     */
    private $shortTitle = '';

    /**
     * How many accounts do we have? (Buffer for self::getAccountCount())
     *
     * @var int
     */
    private $accountCount = 0;

    /**
     * Hostname of the server
     *
     * @var string
     */
    private $host = '';

    /**
     * Port of the server
     *
     * @var int
     */
    private $port = 0;

    /**
     * Info
     *
     * @var \skies\city\CityStatus
     */
    private $info;


    /**
     * Initialise our city
     *
     * @param string $name  Short name of the city
     * @param string $title Title (long name) of the city
     * @param string $host  Hostname of the server
     * @param int    $port  Port of the server
     */
    public function __construct($name, $title, $shortTitle, $host, $port) {

        // Increase counter
        self::$number += 1;

        // Names
        $this->name = $name;
        $this->title = $title;
        $this->shortTitle = $shortTitle;

        // Address
        $this->host = $host;
        $this->port = $port;

    }

    /**
     * Setup an MySQL connection to the user db
     *
     * @param string $host       Host of the MySQL server
     * @param string $user       Username
     * @param string $password   Password
     * @param string $database   Database
     * @param string $tbl_prefix Prefix of the account table (e.g. %prefix%_account)
     *
     * @return bool Fail?
     */
    public function setupDb($host, $user, $password, $database, $tbl_prefix) {

        // Connect
        $this->db = new \skies\system\database\MySQL($host, $user, $password, $database, $tbl_prefix);

        if($this->db === false)
            return false;

        // Sorry, the city uses this shit
        $this->db->set_charset("latin1");

        // Save prefix
        $this->table = $tbl_prefix.'_account';

    }

    /*
     * User lists
     */

    /**
     * Get the account list from $start to $stop
     *
     * @param int $start Start value
     * @param int $stop  Stop value
     *
     * @return array<\skies\city\Player> Accountlist
     */
    public function getAccountlist($start, $stop) {

        // Query to get the list
        $query = 'SELECT * FROM `'.$this->table.'`';

        // Order
        $query .= ' ORDER BY ID';

        // Append limit
        $query .= ' LIMIT '.$start.','.$stop;


        // Exec Query
        if(!$result = $this->db->query($query)) {
            return false;
        }

        // Save data in array
        $i = 0;

        $accountList = [];
        while($line = $result->fetch_array(MYSQLI_ASSOC)) {

            $accountList[$i] = new \skies\city\Player();
            $accountList[$i]->initLine($line);

            $i++;

        }

        // return array
        return $accountList;

    }

    /**
     * How many accounts do we have?
     *
     * @return int Number of accounts
     */
    public function getAccountCount() {

        if(isset($this->accountCount))
            return $this->accountCount;

        // Query
        $query = 'SELECT COUNT(*) FROM `'.$this->table.'`';

        // Exec
        if(!$res = $this->db->query($query)) {
            return false;
        }

        // Get the number
        $data = $res->fetch_array();
        $result = $data[0];

        $this->accountCount = $result;

        return $result;
    }

    /**
     * Get top lists of the given type (see consts)
     *
     * @param int $num  Number of players to show
     * @param int $type See \skies\city\City consts
     *
     * @return array Accountlist of top players
     */
    public function getTop($num, $type) {

        // Check $num
        if(empty($num) || $num < 0) {
            return false;
        }


        // Query to get the list
        $query = 'SELECT * FROM `'.$this->table.'` WHERE `Kills` IS NOT NULL
            AND `Deaths` IS NOT NULL
            AND `Kills` != 0
            AND `Deaths` != 0';

        // Order
        switch($type) {
            case self::TOP_KD:
                $query .= ' ORDER BY `Kills` / `Deaths` DESC';
                break;
            case self::TOP_KILLS:
                $query .= ' ORDER BY `Kills` DESC';
                break;
            case self::TOP_DEATHS:
                $query .= ' ORDER BY `Deaths` DESC';
                break;
            default:
                return false;
        }

        // Append limit
        $query .= ' LIMIT '.$num;

        // Exec Query
        if(!$result = $this->db->query($query)) {
            return false;
        }

        // Save data in array
        $i = 0;

        $accountList = [];
        while($line = $result->fetch_array(MYSQLI_ASSOC)) {

            $accountList[$i] = new \skies\city\Player();
            $accountList[$i]->initLine($line);

            $i++;

        }

        // return array
        return $accountList;

    }

    /**
     * Init the citys
     */
    public static function initCitys() {

        foreach(\Skies::$config['citys'] as $curCity) {

            self::$cities[$curCity['name']] = new City($curCity['name'], $curCity['title'], $curCity['short_title'], $curCity['host'], $curCity['port']);
            self::$cities[$curCity['name']]->setupDb($curCity['dbHost'], $curCity['dbUser'], $curCity['dbPassword'], $curCity['dbName'], $curCity['dbPrefix']);

        }

        // Do we have a current city?
        if(isset($_GET['_1']) && isset(self::$cities[$_GET['_1']]))
            self::$curCity =& self::$cities[$_GET['_1']];
        else
            self::$curCity = false;
    }


    /**
     * Get the status of this city
     *
     * @return CityStatus
     */
    public function getInfo() {

        if(!empty($this->info))
            return $this->info;

        $this->info = new \skies\city\CityStatus($this);

        return $this->info;

    }

    /**
     * @return \skies\system\database\MySQL
     */
    public function getDb() {
        return $this->db;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getShortTitle() {
        return $this->shortTitle;
    }

    /**
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }


}

?>