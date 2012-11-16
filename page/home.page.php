<h1>CITY overview</h1>
<p>
    <strong>What is CITY?</strong><br />
    CITY is a modification for Teeworlds (<a href="http://teeworlds.com/">teeworlds.com</a>).
    It is a mod mainly established in Teeworlds version 0.5.2 on the servers <em>Mikael96's City Server</em> and <em>moro's City Server</em>, but also known from other servers.
    There are some permission ranks in CITY, like <em>POLICE</em>, <em>whitelisted</em> or <em>administrator</em>.
</p>
<p>
    <strong>What is this here?</strong><br />
    You're in the online panel for the CITY modification at the moment. This panel allows you to administer your account, for admins it allows
    to administer all accounts. It also shows some statistics and rank lists. Here are some features that are implemented now:
</p>

<ul>
    <li>Login for all CITY users with their in-game accounts</li>
    <li>Editing profile for all users (Password, Terrorist status, etc.)</li>
    <li>Account list for POLICE, whitelisted and Admins</li>
    <li>Editing all account for special Account Admins</li>
</ul>

<h2>Choose a CITY</h2>

<ul>

<?php

foreach(\skies\city\City::$cities as $city) {

    /** @var $city \skies\city\City */
    $info = $city->getInfo();

    ?>

    <li><a href="/city/<?=$city->getName()?>"><?=$city->getTitle()?></a> (<?=$info->getNumPlayers()?>/<?=$info->getMaxPlayers()?>)</li>

    <?php

}

?>

</ul>