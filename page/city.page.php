<?php
$curCity =& \skies\city\City::$curCity;
?>

<h1><?=$curCity->getTitle()?></h1>

<?php


$info = $curCity->getInfo();

// Split the playerlist in 3 columns
$i = 1;
$info_players = [];

$players = $info->getPlayers();

// Sort by Score
if($info->getNumPlayers() > 0) {
    sortBySubkey($players, "score", SORT_DESC);


    foreach($players as $player) {

    if($i <= $info->getMaxPlayers()/4) {
    $info_players[0][] = $player;
    }
    elseif($i <= $info->getMaxPlayers()/2) {
    $info_players[1][] = $player;
    }
    elseif($i <= (3*$info->getMaxPlayers())/4) {
    $info_players[2][] = $player;
    }
    else {
    $info_players[3][] = $player;
    }

    $i++;
    }
}
?>
<table style="display: inline-table;">
    <tr>
        <td><strong>Status:</strong></td>
        <td style="color: #54a000;">Online</td>
    </tr>
    <tr>
        <td><strong>Players:</strong></td>
        <td><?=$info->getNumPlayers()?>/<?=$info->getMaxPlayers()?></td>
    </tr>
    <tr>
        <td><strong>Map:</strong></td>
        <td><?=$info->getMap()?></td>
    </tr>
    <tr>
        <td><strong>Adress:</strong></td>
        <td><?=$curCity->getHost().':'.$curCity->getPort()?></td>
    </tr>
</table>
<table style="display: inline-table;">
    <tr class="nohover">
        <td><strong>Players online:</strong></td>
        <?php
        if($info->getNumPlayers() > 0) {
            foreach ($info_players as $pl_list) {

                ?>
                <td>
            <table style="width: 100%;">
<?php
                // Output the Playerlist in 4 coloumns
                foreach($pl_list as $player) {

                    $color = false;
                    $prefix = "";
                    $playername = $player['name'];


                    // POLICE?
                    if(substr($player['name'],0,8) == "[POLICE]") {
                        $color = "0048ac";
                        $prefix = "<strong>[POLICE]</strong> ";
                        $playername = str_replace("[POLICE] ", "", $player['name']);
                    }

                    // wanted?
                    if(substr($player['name'],0,3) == "[W]") {
                        $color = false;
                        $prefix = "<span style=\"color: #990000; font-weight: bold;\">[W]</span> ";
                        $playername = str_replace("[W] ", "", $player['name']);
                    }

                    $playeroutp = $prefix.htmlentities($playername, ENT_COMPAT, "ISO-8859-1");

                    ?>
                    <tr>
                    <td<?php if($color !== false) {?> style="color: #<?=$color?>;"<?php } ?>><?=$playeroutp?></td>
                    <td><em><?=$player['score']?></em></td>
                </tr>
                    <?php
                }
                ?>
            </table>
        </td>
                <?php

            }
        }
        // No players online?
        else {
            ?>

            <td>
            <em>None</em>
        </td>

            <?php
        }
        ?>
    </tr>
</table>


<h2>Some statistics</h2>

<table style="margin: 0 auto;">
    <thead>
        <tr class="header">
            <th>Best KD (Kills/Deaths Ratio)</th>
            <th>Most Kills</th>
            <th>Most Deaths</th>
        </tr>
    </thead>

    <tbody>
        <tr class="nohover">

            <!-- KD -->
            <td style="padding-right: 120px;">
                <table>
                    <?php

                    $i = 1;

                    foreach($curCity->getTop(10, \skies\city\City::TOP_KD) as $curPlayer) {

                        /** @var $curPlayer \skies\city\Player */

                        ?>
                        <tr>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=$i?>.</td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?> style="padding-right: 50px;"><?=$curPlayer->getName()?></td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=number_format($curPlayer->getNumKills()/$curPlayer->getNumDeaths(), 2)?></td>
                        </tr>
                        <?php

                        $i++;

                    }

                    ?>
                </table>
            </td>

            <!-- Kills -->
            <td style="padding-right: 120px;">
                <table>
                    <?php

                    $i = 1;

                    foreach($curCity->getTop(10, \skies\city\City::TOP_KILLS) as $curPlayer) {

                        ?>
                        <tr>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=$i?>.</td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?> style="padding-right: 50px;"><?=$curPlayer->getName()?></td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=number_format($curPlayer->getNumKills(), 0, '.', '&nbsp;')?></td>
                        </tr>
                        <?php

                        $i++;

                    }

                    ?>
                </table>
            </td>

            <!-- Deaths -->
            <td>
                <table>
                    <?php

                    $i = 1;

                    foreach($curCity->getTop(10, \skies\city\City::TOP_DEATHS) as $curPlayer) {

                        ?>
                        <tr>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=$i?>.</td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?> style="padding-right: 50px;"><?=$curPlayer->getName()?></td>
                            <td<?=$i <= 3 ? ' class="strong"' : ''?>><?=number_format($curPlayer->getNumDeaths(), 0, '.', '&nbsp;')?></td>
                        </tr>
                        <?php

                        $i++;

                    }

                    ?>
                </table>
            </td>

        </tr>
    </tbody>

</table>