<?php

namespace skies\city;

/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies.city
 */
class Player {

    const LEVEL_CITIZEN = 1;
    const LEVEL_WLIST = 50;
    const LEVEL_ADMIN = 100;

    /**
     * City this user is registered in
     *
     * @var \skies\city\City
     */
    private $city;

    /**
     * ID in the player table
     *
     * @var int
     */
    private $id = 0;

    /**
     * Name of the account
     *
     * @var string
     */
    private $name = '';

    /**
     * Number of kills
     *
     * @var int
     */
    private $numKills = 0;

    /**
     * Number of Deaths
     *
     * @var int
     */
    private $numDeaths = 0;

    /**
     * UNIX Timestamp of the last login
     *
     * @var int
     */
    private $lastLoginTime = 0;

    /**
     * UNIX Timestamp of the registration
     *
     * @var int
     */
    private $registerTime = 0;

    /**
     * Level of this account (see \skies\city\City::LEVEL_* constants)
     *
     * @var int
     */
    private $level = 0;

    /**
     * Save point
     *
     * @var array
     */
    private $savePoint = [];

    /**
     * Home point
     *
     * @var array
     */
    private $homePoint = [];

    /**
     * Is this user a police officer?
     *
     * @var bool
     */
    private $police = false;

    /**
     * Is this user a terrorist?
     *
     * @var bool
     */
    private $terrorist = false;

    /**
     * Is this user able to save and load positions?
     *
     * @var bool
     */
    private $saveLoad = false;

    /**
     * Is this user able to move through walls?
     *
     * @var bool
     */
    private $move = false;

    /**
     * Is this user able to become invisible?
     *
     * @var bool
     */
    private $invisible = false;

    /**
     * Is this user able to become an FBI agent?
     *
     * @var bool
     */
    private $fbi = false;


    /**
     * Setup an user by the given ID
     *
     * @param int $userID User's ID
     *
     * @return bool Success?
     */
    public function initID($userID) {

        // Save the ID
        $this->id = $userID;

        // Fetch the other stuff
        $query = 'SELECT * FROM `'.$this->city->getTable().'` WHERE `ID` = '.\escape($this->id).' LIMIT 1';
        $line = @$this->city->getDb()->query($query)->fetch_array(MYSQLI_ASSOC);

        if(empty($line) || $line === false)
            return false;

        return $this->initLine($line);

    }

    /**
     * Setup an user by the given line from the DB
     *
     * @param array $line Line of the user from the DB
     *
     * @return bool
     */
    public function initLine($line) {

        $this->fbi = ($line['can_fbi'] == 1);
        $this->homePoint = [$line['home_x'], $line['home_y']];
        $this->invisible = ($line['can_invisible'] == 1);
        $this->lastLoginTime = strtotime($line['last_logged_in']);
        $this->move = ($line['can_move']);
        $this->name = $line['Name'];
        $this->numDeaths = $line['Deaths'];
        $this->numKills = $line['Kills'];
        $this->police = ($line['police'] == 1);
        $this->registerTime = strtotime($line['register_date']);
        $this->saveLoad = ($line['can_saveload'] == 1);
        $this->savePoint = [$line['save_x'], $line['save_y']];
        $this->terrorist = ($line['terrorist'] == 1);

        // Level
        switch($line['level']) {

            case 100:
                $this->level = self::LEVEL_ADMIN;
                break;
            case 50:
                $this->level = self::LEVEL_WLIST;
                break;
            default:
                $this->level = self::LEVEL_CITIZEN;

        }

        return true;

    }

    /**
     * Save the fields to the DB
     *
     * @return bool Success?
     */
    public function save() {

        $query = 'UPDATE `'.$this->city->getTable().'` SET (
                `Name` = \''.\escape($this->name).'\',
                `level` = '.\escape($this->level).',
                `save_x` = '.\escape($this->savePoint[0]).',
                `save_x` = '.\escape($this->savePoint[1]).',
                `home_x` = '.\escape($this->homePoint[0]).',
                `home_x` = '.\escape($this->homePoint[1]).',
                `police` = '.($this->police ? '1' : '0').',
                `terrorist` = '.($this->terrorist ? '1' : '0').',
                `can_saveload` = '.($this->saveLoad ? '1' : '0').',
                `can_move` = '.($this->move ? '1' : '0').',
                `can_invisible` = '.($this->invisible ? '1' : '0').',
                `can_fbi` = '.($this->fbi ? '1' : '0').'
            ) WHERE ID = '.\escape($this->id);

        // Aaaand save the stuff
        return ($this->city->getDb()->query($query) === true);


    }

    /*
     * ############# GETTERS ################
     */

    /**
     * @return boolean
     */
    public function getTerrorist() {

        return $this->terrorist;
    }

    /**
     * @return boolean
     */
    public function getFbi() {

        return $this->fbi;
    }

    /**
     * @return array
     */
    public function getHomePoint() {

        return $this->homePoint;
    }

    /**
     * @return int
     */
    public function getId() {

        return $this->id;
    }

    /**
     * @return boolean
     */
    public function getInvisible() {

        return $this->invisible;
    }

    /**
     * @return int
     */
    public function getLastLoginTime() {

        return $this->lastLoginTime;
    }

    /**
     * @return int
     */
    public function getLevel() {

        return $this->level;
    }

    /**
     * @return boolean
     */
    public function getMove() {

        return $this->move;
    }

    /**
     * @return string
     */
    public function getName() {

        return $this->name;
    }

    /**
     * @return int
     */
    public function getNumDeaths() {

        return $this->numDeaths;
    }

    /**
     * @return int
     */
    public function getNumKills() {

        return $this->numKills;
    }

    /**
     * @return boolean
     */
    public function getPolice() {

        return $this->police;
    }

    /**
     * @return int
     */
    public function getRegisterTime() {

        return $this->registerTime;
    }

    /**
     * @return boolean
     */
    public function getSaveLoad() {

        return $this->saveLoad;
    }

    /**
     * @return array
     */
    public function getSavePoint() {

        return $this->savePoint;
    }

    /**
     * @return \skies\city\City
     */
    public function getCity() {

        return $this->city;
    }

    /*
     * ########### SETTERS #############
     */

    /**
     * @param boolean $terrorist
     */
    public function setTerrorist($terrorist) {

        $this->terrorist = $terrorist;
    }

    /**
     * @param array $savePoint
     */
    public function setSavePoint($savePoint) {

        $this->savePoint = $savePoint;
    }

    /**
     * @param boolean $saveLoad
     */
    public function setSaveLoad($saveLoad) {

        $this->saveLoad = $saveLoad;
    }

    /**
     * @param boolean $police
     */
    public function setPolice($police) {

        $this->police = $police;
    }

    /**
     * @param boolean $move
     */
    public function setMove($move) {

        $this->move = $move;
    }

    /**
     * @param int $level
     */
    public function setLevel($level) {

        $this->level = $level;
    }

    /**
     * @param boolean $invisible
     */
    public function setInvisible($invisible) {

        $this->invisible = $invisible;
    }

    /**
     * @param boolean $fbi
     */
    public function setFbi($fbi) {

        $this->fbi = $fbi;
    }




}

?>