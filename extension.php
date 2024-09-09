<?php
if (!defined("INDEXED")) exit;

$ol_CustomLang = array (
    "onlinelist.UsersOnline" => "<b>Users online:</b> %s",
    "onlinelist.NoUsersOnline" => "There are no users online",
    "onlinelist.ExtraUsers" => "and %d more",
);
$lang = array_merge($ol_CustomLang, $lang);


// Define the functions

function OnlineList_Display() {
    global $db, $lang, $config, $extension_config;

    $users = array();
    $extra_users = 0;
    $result = $db->query("SELECT * FROM users");
    $OnlineList = getExtensionName(__DIR__);
    
    while ($row = $result->fetch_assoc()) {
        if (time() - $row["lastactive"] <= $config["onlinePeriod"]) {
            if (sizeof($users) > $extension_config[$OnlineList]["maxUsers"]) {
                $extra_users++;
                continue;
            }
            array_push($users,'<a id="'.$row["role"].'" href="'.genURL("user/".$row["userid"]).'">'.htmlspecialchars($row["username"]).'</a>');
        }
    }
    
    if ($extra_users != 0) {
        array_push($users,sprintf($lang["onlinelist.ExtraUsers"],$extra_users));
    }
    if (sizeof($users) == 0) {
        array_push($users,$lang["onlinelist.NoUsersOnline"]);
    }
    
    echo "<br>" . sprintf($lang["onlinelist.UsersOnline"], implode(", ", $users)) . "<br>";
}

// Hook the functions
hook("afterFooter", "OnlineList_Display");
?>
