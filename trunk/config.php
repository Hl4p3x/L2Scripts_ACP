<?php
if (!defined('ctx')) die();

// language can be ru, en or any localazied language
// Язык может быть Английский, Русский или любой другой локализованный язык
define('LANGUAGE', 'en'); 

define('DB_TYPE', 'mysql');
// database host
// Хост базы данных
define('DB_HOST', '46.105.30.162');  
// database name
// Имя базы данных
define('DB_NAME', 'kdjhhyrudjkJJjdhyes'); 
// database user 
// Пользователь базы данных
define('DB_USER', 'infoalqowirjfhvmxh6384%#*03716');  
// database password
// Пароль базы данных
define('DB_PASS', '?5k}NE56wSUB?!m');  
// absolute url, points to the acp main location
// Абсалютный путь к Мастер Акаунту
define('URL', 'https://cp.nationwarriors.com/'); 
// absolute path
// Абсалютный путь библиотеки
define('ROOT_PATH', dirname(__FILE__).'/'); 


// algorithm used for l2j password hashing whirlpool or sha1 (legacy)
// Использованный алгоритм для хешированние паролей ява серверов whirlpool или sha1 (legacy)
define('PASSWORD_HASH_ALGORITHM', 'sha1'); 

//captcha public and private keys get them from: https://www.google.com/recaptcha/intro/
//рекаптча публичный и приватный ключ, получите его тут: https://www.google.com/recaptcha/intro/
define('CAPTCHA_PUBLIC_KEY', '6Ldrz0sUAAAAAOtITCyuKbCtL6ScKL63-BkHcI60');
define('CAPTCHA_PRIVATE_KEY', '6Ldrz0sUAAAAAO9el0zSoJN_yXLtqwWGmXjQceoF');

// cache time in seconds
// Кешированние в секундах
define('STAT_CACHE_TIME', 300); 

//SMTP server configuration (google, yandex or your own)
//конфигурация СМТП сервера (google, yandex or your own)
define('PHPMAILER_SMTP_SERVER', 'nationwarriors.com');
define('PHPMAILER_SMTP_PORT', '465');
define('PHPMAILER_SMTP_USER', 'no-reply@nationwarriors.com');
define('PHPMAILER_SMTP_PASS', '49@Codorna');
define('PHPMAILER_SMTP_SECURE', 'ssl');
define('PHPMAILER_FROM_MAIL', 'no-reply@nationwarriors.com');
define('PHPMAILER_FROM_NAME', 'Nation Warriors Classic Server');

//NOT USED
//Не использовать
define('USING_CLEAN_URLS', TRUE);

// algorithm used for master account password hashing
// Использованный алгоритм для хешированние паролей для Мастер Акаунта
define('HASH_ALGO', 'sha256'); 

// char ids to exclude from statistics, FORMAT IS CRITICAL EX: '268452885,268452890,268452900'
// ИД персонажей которые исключены из статистики, критически соблюдать формат: '268452885,268452890,268452900'
$_CONFIG['stat_exclusions'] = '268459181,268458872,268458897,268461227';

// each mail header line MUST end in \r\n
// Каждый заголовок письма должен заканчиватся с \r\n
$_CONFIG['mail_headers'] = "От: Nation Warriors <noreply@nationwarriors.com>\r\n";
$_CONFIG['mail_register_subject'] = "Welcome adventurer to the server Nation Warriors";
$_CONFIG['mail_recover_subject'] = "Restore access from the Master Account  - NationWarriors.com";

// lucky wheel 1 spin cost
// Стоимость крутить 1 раз колесо удачи
$_CONFIG['luckywheel_price'] = 5;
// lucky wheel prizes, must define AT LEAST 12
// Призы за колесо удачи, должно быть МИНИМУМ 12
$_CONFIG['luckywheel_prizes'] = array(
    array('item_id' => 57, 'count' => 300000),
    array('item_id' => 29650, 'count' => 5),
	array('item_id' => 29649, 'count' => 5),
	array('item_id' => 70114, 'count' => 1),
	array('item_id' => 49000, 'count' => 1),
	array('item_id' => 29012, 'count' => 5),
	array('item_id' => 70094, 'count' => 1),
	array('item_id' => 49512, 'count' => 1),
	array('item_id' => 70000, 'count' => 3),
	array('item_id' => 49493, 'count' => 1),
	array('item_id' => 49509, 'count' => 1),
	array('item_id' => 13016, 'count' => 10),
	array('item_id' => 13015, 'count' => 2),
	array('item_id' => 20033, 'count' => 5),
	array('item_id' => 1463, 'count' => 5000),
	array('item_id' => 1464, 'count' => 5000),
	array('item_id' => 1465, 'count' => 5000),
	array('item_id' => 3948, 'count' => 5000),
	array('item_id' => 3949, 'count' => 5000),
	array('item_id' => 3950, 'count' => 5000),
	
);

// amount of experience per dollar spent in the master account
// Количество ЕХП пер рубль за трату денег в Мастер Акаунте
$_CONFIG['exp_per_dollar'] = 0;
// amount of experience per dollar donated
// Количество ЕХП пер рубль за донат в Мастер Акаунт
$_CONFIG['exp_per_donate'] = 0;

// account experience table
// Таблица опыта
$_CONFIG['account_level_exp'] = array(
    -1, // must always be -1 ; Должно быть -1
	99999, // exp for level 1; ЕХП для 1го уровня
    999999, // exp for level 2; ЕХП для 2го уровня
    9999999, // exp for level 3; ЕХП для 3го уровня
    99999999, // exp for level 4; ЕХП для 4го уровня
    999999999, // exp for level 5; ЕХП для 5го уровня
    9999999999, // exp for level 6; ЕХП для 6го уровня
    99999999999, // exp for level 7; ЕХП для 7го уровня
    999999999999, // exp for level 8; ЕХП для 8го уровня
    9999999999999, // exp for level 9; ЕХП для 9го уровня
    99999999999999, // exp for level 10; ЕХП для 10го уровня
);

// account level discounts per level in %
// Уровень скидки на каждом уровне в %
$_CONFIG['account_level_discount'] = array(
    0, // starting discount; Начальная скидка
	0, // level 1 discount; Скидка для 1го уровня
    0, // level 2 discount; Скидка для 2го уровня
    0, // level 3 discount; Скидка для 3го уровня
    0, // level 4 discount; Скидка для 4го уровня
    0, // level 5 discount; Скидка для 5го уровня
    0, // level 6 discount; Скидка для 6го уровня
    0, // level 7 discount; Скидка для 7го уровня
    0, // level 8 discount; Скидка для 8го уровня
    0, // level 9 discount; Скидка для 9го уровня
    0, // level 10 discount; Скидка для 10го уровня
);

// list of item ids which cannot be auctioned (by default everything can be auctioned
// Лист вещей которые нельзя выставлять на аукцион (по умолчанию выставить можно все)
$_CONFIG['auction_item_blocklist'] = array(

);

//paymentwall donation API configuration  
//paymentwall настройка доната
define('PAYMENTWALL_WIDGET_URL', '[USER_ID]');
define('PAYMENTWALL_SECRET_KEY', 'a05e8be9c97da42263ff0be563948bfb');
define('DONATE_CURRENCY', 'EUR');
define('DONATE_CURRENCY_SYMBOL', '€');
define('COINS_PER_DOLLAR', 10);

// g2a configuration params
define('G2A_API_HASH', '2af4c4e7-806b-4117-84e8-ac23160122e8');
define('G2A_API_SECRET', '=M@E0IRLVyVPU*@R%^3+6qo5d*YquU$kq_yr7D?c*V_TUvuQsxHyb!~2!PsmtnKX');

// PayPal configuration params
define('PAYPAL_CURRENCY', 'EUR');
define('PAYPAL_RECEIVER_EMAIL', 'sarah.b@nationwarriors.com');

//Unitpay API configuration
//Unitpay настройка доната
define('UNITPAY_PUBLIC_KEY', '71--9b');
define('UNITPAY_SECRET_KEY', '9303839f0c--174782ab8');

// Safely enable/disable functions in 1 click
// Можно безопасно выключать функции Мастер акаунта
$_CONFIG['features_enabled'] = array(
	'stats' => true,
	'ticket' => true,
	'auction' => true,
	'luckywheel' => true,
	'vote' => false,
	'donate' => true
);

//configuration for votes
//Конфигурация призов за голосование
$_CONFIG['vote_sites'] = array(

    1 => array(
        'display_name' => 'L2TopZone',
        'coins_per_vote' => 1,
        'vote_delay' => 12,
        'vote_button' => '<a href="https://l2topzone.com/vote/id/15317" target="_blank" title="l2topzone" ><img src="https://l2topzone.com/vb/l2topzone-Lineage2-vote-banner-normal-2.png" alt="l2topzone.com" border="0"></a>',
        'type' => 'apicall',
        'api_url' => 'https://api.l2topzone.com/v1/server_f6f4c32fb97d65017804fedcf1f00062/getUserData',
        'api_handler' => 'TopZoneAPI'
    ),
);
