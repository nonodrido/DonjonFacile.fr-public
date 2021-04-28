<?php	
require_once "../chat/src/pfcinfo.class.php";
$info  = new pfcInfo('main');
// NULL is used to get all the connected users, but you can specify
// a channel name to get only the connected user on a specific channel
$users = $info->getOnlineNick('Auberge');
$nb_users = count($users);
if ($nb_users !=0){echo ' ('.$nb_users.')';}