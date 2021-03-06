<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 * @package   skies
 */

/**
 * Alias for Skies::$db->escape_string($string)
 *
 * @param $string
 *
 * @return string
 * @see skies\system\database\MySQL::escape_string()
 */
function escape($string) {

    return Skies::$db->escape_string($string);

}

function sortBySubkey(&$array, $subkey, $sortType = SORT_ASC) {
    foreach ($array as $subarray) {
        $keys[] = $subarray[$subkey];
    }
    array_multisort($keys, $sortType, $array);
}

?>