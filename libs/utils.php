<?php
if (!defined('ctx')) die();

function valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function random_token($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }
    return $token;
}

function timify($time) {
    $roundedsecs = floor($time/60)*60;
    $roundedmins = floor($roundedsecs / 60);
    $roundedhrs = floor($roundedmins / 60);
    $roundeddays = floor($roundedhrs / 24);

    if($roundedsecs < 60){
        return $roundedsecs . _s("SECONDS");
    }else if($roundedsecs < 3600){
        return $roundedmins . _s("MINUTES");
    }else if($roundedsecs < 86400){
        return $roundedhrs . _s("HOURS");
    }else{
        return $roundeddays . _s("DAYS");
    }
}

function timify2($time) {
    if (!is_numeric($time))
        return "";

    $roundedsecs = floor($time/60)*60;
    $roundeddays = floor($roundedsecs / 86400);
    $roundedhrs = floor(($roundedsecs - ($roundeddays*86400)) / 3600);
    $roundedmins = floor((($roundedsecs - ($roundeddays*86400)) - ($roundedhrs*3600)) / 60);


    if ($time < 60) {
        return $roundedsecs . _s("SEC");
    } else if ($time < 3600) {
        return $roundedmins . _s("MIN");
    } else if ($time < 86400) {
        return $roundedhrs . _s("HR") . $roundedmins . _s("MIN");
    } else {
        return $roundeddays . _s("DAY1") . $roundedhrs . _s("HR") . $roundedmins . _s("MIN");
    }
}

function formatdate($date_int) {
    return date("j/m/Y H:i:s", $date_int);
}

function pass_encode($pass, $type) {
    if ($type == 'sha1') {
        return base64_encode(pack('H*', sha1(utf8_encode($pass))));
    }
    return base64_encode(hash('whirlpool', $pass, true));
}

function get_item_name($id) {
    $lang = LANGUAGE;
    if (Session::get("lang") != "" && (Session::get("lang") == "en" || Session::get("lang") == "ru"))
        $lang = Session::get("lang");

    require_once ROOT_PATH.'/itemdata_'.$lang.'.php';
    global $_ITEMS;
    if (isset($_ITEMS[$id]))
        return $_ITEMS[$id]['name'];
    else
        return "NoItemName";
}

function get_item_alt_name($id) {
    $lang = LANGUAGE;
    if (Session::get("lang") != "" && (Session::get("lang") == "en" || Session::get("lang") == "ru"))
        $lang = Session::get("lang");

    require_once ROOT_PATH.'/itemdata_'.$lang.'.php';
    global $_ITEMS;
    if (isset($_ITEMS[$id]))
        return $_ITEMS[$id]['alt_name'];
    else
        return "";
}

function get_item_icon($id) {
    $lang = LANGUAGE;
    if (Session::get("lang") != "" && (Session::get("lang") == "en" || Session::get("lang") == "ru"))
        $lang = Session::get("lang");

    require_once ROOT_PATH.'/itemdata_'.$lang.'.php';
    global $_ITEMS;
    if (isset($_ITEMS[$id]))
        return $_ITEMS[$id]['icon'].".png";
    else
        return "notfound.png";
}

function get_item_grade($id) {
    $lang = LANGUAGE;
    if (Session::get("lang") != "" && (Session::get("lang") == "en" || Session::get("lang") == "ru"))
        $lang = Session::get("lang");

    require_once ROOT_PATH.'/itemdata_'.$lang.'.php';
    global $_ITEMS;
    if (isset($_ITEMS[$id]))
        return $_ITEMS[$id]['grade'];
    else
        return "none";
}

function get_item_category($id) {
    /*require_once ROOT_PATH.'/itemdata.php';
    global $_ITEMS;
    if (isset($_ITEMS[$id]))
        return $_ITEMS[$id]['category'];
    else*/
        return 0;
}

function get_class_name($class_id) {
    if ($class_id == '')
        $class_id = 0;

    $classes = array(
        "Human Fighter",
        "Warrior",
        "Gladiator",
        "Warlord",
        "Human Knight",
        "Paladin",
        "Dark Avenger",
        "Rogue",
        "Treasure Hunter",
        "Hawkeye",
        "Human Mystic",
        "Human Wizard",
        "Sorcerer",
        "Necromancer",
        "Warlock",
        "Cleric",
        "Bishop",
        "Prophet",
        "Elven Fighter",
        "Elven Knight",
        "Temple Knight",
        "Swordsinger",
        "Elven Scout",
        "Plains Walker",
        "Silver Ranger",
        "Elven Mystic",
        "Elven Wizard",
        "Spellsinger",
        "Elemental Summoner",
        "Elven Oracle",
        "Elven Elder",
        "Dark Fighter",
        "Palus Knight",
        "Shillien Knight",
        "Bladedancer",
        "Assassin",
        "Abyss Walker",
        "Phantom Ranger",
        "Dark Mystic",
        "Dark Wizard",
        "Spellhowler",
        "Phantom Summoner",
        "Shillien Oracle",
        "Shillien Elder",
        "Orc Fighter",
        "Orc Raider",
        "Destroyer",
        "Orc Monk",
        "Tyrant",
        "Orc Mystic",
        "Orc Shaman",
        "Overlord",
        "Warcryer",
        "Dwarven Fighter",
        "Scavenger",
        "Bounty Hunter",
        "Artisan",
        "Warsmith",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
        "DUMMY",
	"DUMMY",
        "Duelist",
        "Dreadnought",
        "Phoenix Knight",
        "Hell Knight",
        "Sagittarius",
        "Adventurer",
        "Archmage",
        "Soultaker",
        "Arcana Lord",
        "Cardinal",
        "Hierophant",
        "Eva's Templar",
        "Sword Muse",
        "Wind Rider",
        "Moonlight Sentinel",
        "Mystic Muse",
        "Elemental Master",
        "Eva's Saint",
        "Shillien Templar",
        "Spectral Dancer",
        "Ghost Hunter",
        "Ghost Sentinel",
        "Storm Screamer",
        "Spectral Master",
        "Shillien Saint",
        "Titan",
        "Grand Khavatari",
        "Dominator",
        "Doomcryer",
        "Fortune Seeker",
        "Maestro",
	"DUMMY",
	"DUMMY",
	"DUMMY",
	"DUMMY",
	"Kamael Male Soldier",
	"Kamael Female Soldier",
	"Trooper",
	"Warder",
	"Berserker",
	"Male Soul Breaker",
	"Female Sould Breaker",
	"Arbalester",
	"Doombringer",
	"Male Soul Hound",
	"Felame Soul Hound",
	"Trickster",
	"Inspector",
	"Judicator",
	"DUMMY",
	"DUMMY",
	"Sigel Knight",
	"Tyrr Warrior",
	"Othell Rogue",
	"Yul Archer",
	"Feoh Wizard",
	"Iss Enchanter",
	"Wynn Summoner",
	"Aeore Healer",
	"Sigel Phoenix Knight",
	"Sigel Hell Knight",
	"Sigel Eva's Templar",
	"Sigel Shillien Templar",
	"Tyrr Duelist",
	"Tyrr Dreadnought",
	"Tyrr Titan",
	"Tyrr Grand Khavatari",
	"Tyrr Maestro",
	"Tyrr Doombringer",
	"Othell Adventurer",
	"Othell Wind Rider",
	"Othell Ghost Hunter",
	"Othell Fortune Seeker",
	"Yul Sagittarius",
	"Yul Moonlight Sentinel",
	"Yul Ghost Sentinel",
	"Yul Trickster",
	"Feoh Archmage",
	"Feoh Soultaker",
	"Feoh Mystic Muse",
	"Feoh Storm Screamer",
	"Feoh Soulhound",
	"Iss Hierophant",
	"Iss Sword Muse",
	"Iss Spectral Dancer",
	"Iss Dominator",
	"Iss Doomcryer",
	"Wynn Arcana Lord",
	"Wynn Elemental Master",
	"Wynn Spectral Master",
	"Aeore Cardinal",
	"Aeore Eva's Saint",
	"Aeore Shillien Saint",
	"Ertheia Warrior",
	"Ertheia Wizard",
	"Marauder",
	"Threat",
	"Hatred",
	"Storm Threat",
	"Hatred Vortex",
	"Saiha Seer"
    );

    return $classes[$class_id];
}

function get_avatar($baseclass, $sex) {
    $avatars = array(
        0 => "human_fighter",
        1 => "human_fighter",
        2 => "human_fighter",
        3 => "human_fighter",
        4 => "human_fighter",
        5 => "human_fighter",
        6 => "human_fighter",
        7 => "human_fighter",
        8 => "human_fighter",
        9 => "human_fighter",
        88 => "human_fighter",
        89 => "human_fighter",
        90 => "human_fighter",
        91 => "human_fighter",
        92 => "human_fighter",
        93 => "human_fighter",
        10 => "human_mystic",
        11 => "human_mystic",
        12 => "human_mystic",
        13 => "human_mystic",
        14 => "human_mystic",
        15 => "human_mystic",
        16 => "human_mystic",
        17 => "human_mystic",
        94 => "human_mystic",
        95 => "human_mystic",
        96 => "human_mystic",
        97 => "human_mystic",
        98 => "human_mystic",
        18 => "elf",
        19 => "elf",
        20 => "elf",
        21 => "elf",
        22 => "elf",
        23 => "elf",
        24 => "elf",
        99 => "elf",
        100 => "elf",
        101 => "elf",
        102 => "elf",
        25 => "elf",
        26 => "elf",
        27 => "elf",
        28 => "elf",
        29 => "elf",
        30 => "elf",
        103 => "elf",
        104 => "elf",
        105 => "elf",
        31 => "dark_elf",
        32 => "dark_elf",
        33 => "dark_elf",
        34 => "dark_elf",
        35 => "dark_elf",
        36 => "dark_elf",
        37 => "dark_elf",
        106 => "dark_elf",
        107 => "dark_elf",
        108 => "dark_elf",
        109 => "dark_elf",
        38 => "dark_elf",
        39 => "dark_elf",
        40 => "dark_elf",
        41 => "dark_elf",
        42 => "dark_elf",
        43 => "dark_elf",
        110 => "dark_elf",
        111 => "dark_elf",
        112 => "dark_elf",
        44 => "orc_fighter",
        45 => "orc_fighter",
        46 => "orc_fighter",
        47 => "orc_fighter",
        48 => "orc_fighter",
        113 => "orc_fighter",
        114 => "orc_fighter",
        49 => "orc_mystic",
        50 => "orc_mystic",
        51 => "orc_mystic",
        52 => "orc_mystic",
        115 => "orc_mystic",
        116 => "orc_mystic",
        53 => "dwarf",
        54 => "dwarf",
        55 => "dwarf",
        56 => "dwarf",
        57 => "dwarf",
        117 => "dwarf",
        118 => "dwarf",
        123 => "kamael",
        124 => "kamael",
        125 => "kamael",
        126 => "kamael",
        127 => "kamael",
        128 => "kamael",
        129 => "kamael",
	130 => "kamael",
	131 => "kamael",
	132 => "kamael",
	133 => "kamael",
	134 => "kamael",
	135 => "kamael",
	136 => "kamael",
	182 => "ertheia",
	183 => "ertheia",
	184 => "ertheia",
	185 => "ertheia",
	186 => "ertheia",
	187 => "ertheia",
	188 => "ertheia",
	189 => "ertheia",
    );

    return $avatars[$baseclass].($sex==0?"0":"1").".png";
}
function get_account_level($exp) {
    global $_CONFIG;
    for ($i=0; $i<count($_CONFIG['account_level_exp']); $i++) {
        if ($exp >= $_CONFIG['account_level_exp'][$i]) {
            if (!isset($_CONFIG['account_level_exp'][$i+1]) || $exp < $_CONFIG['account_level_exp'][$i+1]) {
                return $i;
            }
        }
    }
    return 1;
}
function get_exp_percent($exp) {
    global $_CONFIG;
    $level = get_account_level($exp);
    if (!isset($_CONFIG['account_level_exp'][$level+1])) {
        return 100;
    }

    $last_exp = $level > 0 ? $_CONFIG['account_level_exp'][$level] : 0;
    $next_exp = $_CONFIG['account_level_exp'][$level+1];

    $percent = (($exp - $last_exp) / ($next_exp - $last_exp)) * 100;
    return intval($percent);
}