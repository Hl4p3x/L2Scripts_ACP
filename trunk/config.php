<?php
if (!defined('ctx')) die();

// language can be ru, en or any localazied language
// Язык может быть Английский, Русский или любой другой локализованный язык
define('LANGUAGE', 'en'); 

define('DB_TYPE', 'mysql');
// database host
// Хост базы данных
define('DB_HOST', '127.0.0.1');  
// database name
// Имя базы данных
define('DB_NAME', 'l2jmobius'); 
// database user 
// Пользователь базы данных
define('DB_USER', 'root');  
// database password
// Пароль базы данных
define('DB_PASS', '');   
// absolute url, points to the acp main location
// Абсалютный путь к Мастер Акаунту
define('URL', 'http://127.0.0.1/'); 
// absolute path
// Абсалютный путь библиотеки
define('ROOT_PATH', dirname(__FILE__).'/'); 


// algorithm used for l2j password hashing whirlpool or sha1 (legacy)
// Использованный алгоритм для хешированние паролей ява серверов whirlpool или sha1 (legacy)
define('PASSWORD_HASH_ALGORITHM', 'sha1'); 

//captcha public and private keys get them from: https://www.google.com/recaptcha/intro/
//рекаптча публичный и приватный ключ, получите его тут: https://www.google.com/recaptcha/intro/
define('CAPTCHA_PUBLIC_KEY', '6LeLoCUTAAAAA*************');
define('CAPTCHA_PRIVATE_KEY', '6LeLoCUTAAAAAH***********');

// cache time in seconds
// Кешированние в секундах
define('STAT_CACHE_TIME', 300); 

//SMTP server configuration (google, yandex or your own)
//конфигурация СМТП сервера (google, yandex or your own)
define('PHPMAILER_SMTP_SERVER', 'ssl://smtp.yandex.ru');
define('PHPMAILER_SMTP_PORT', '465');
define('PHPMAILER_SMTP_USER', 'noreply@L2-Scripts.ru');
define('PHPMAILER_SMTP_PASS', '--');
define('PHPMAILER_SMTP_SECURE', 'ssl');
define('PHPMAILER_FROM_MAIL', 'noreply@L2-Scripts.ru');
define('PHPMAILER_FROM_NAME', 'L2-Scripts.ru');

//NOT USED
//Не использовать
define('USING_CLEAN_URLS', TRUE);

// algorithm used for master account password hashing
// Использованный алгоритм для хешированние паролей для Мастер Акаунта
define('HASH_ALGO', 'sha256'); 

// char ids to exclude from statistics, FORMAT IS CRITICAL EX: '268452885,268452890,268452900'
// ИД персонажей которые исключены из статистики, критически соблюдать формат: '268452885,268452890,268452900'
$_CONFIG['stat_exclusions'] = '';

// each mail header line MUST end in \r\n
// Каждый заголовок письма должен заканчиватся с \r\n
$_CONFIG['mail_headers'] = "От: L2-Scripts <admin@L2-Scripts.ru>\r\n";
$_CONFIG['mail_register_subject'] = "Добро пожаловать на сервер L2-Scripts.ru";
$_CONFIG['mail_recover_subject'] = "Восстановление доступа от Мастера Аккаунтов - L2-Scripts.ru";

// lucky wheel 1 spin cost
// Стоимость крутить 1 раз колесо удачи
$_CONFIG['luckywheel_price'] = 30;
// lucky wheel prizes, must define AT LEAST 12
// Призы за колесо удачи, должно быть МИНИМУМ 12
$_CONFIG['luckywheel_prizes'] = array(
    array('item_id' => 5592, 'count' => 50),
    array('item_id' => 57, 'count' => 50000000),
    array('item_id' => 1538, 'count' => 1),
    array('item_id' => 9627, 'count' => 1),
    array('item_id' => 6407, 'count' => 1),
    array('item_id' => 8562, 'count' => 1),
    array('item_id' => 4357, 'count' => 10),
    array('item_id' => 20067, 'count' => 1),
    array('item_id' => 3930, 'count' => 1),
    array('item_id' => 3929, 'count' => 1),
    array('item_id' => 9152, 'count' => 1),
    array('item_id' => 9153, 'count' => 1)
);

// amount of experience per dollar spent in the master account
// Количество ЕХП пер рубль за трату денег в Мастер Акаунте
$_CONFIG['exp_per_dollar'] = 0;
// amount of experience per dollar donated
// Количество ЕХП пер рубль за донат в Мастер Акаунт
$_CONFIG['exp_per_donate'] = 1;

// account experience table
// Таблица опыта
$_CONFIG['account_level_exp'] = array(
    -1, // must always be -1 ; Должно быть -1
	0, // exp for level 1; ЕХП для 1го уровня
    300, // exp for level 2; ЕХП для 2го уровня
    1000, // exp for level 3; ЕХП для 3го уровня
    2000, // exp for level 4; ЕХП для 4го уровня
    5000, // exp for level 5; ЕХП для 5го уровня
    8000, // exp for level 6; ЕХП для 6го уровня
    15000, // exp for level 7; ЕХП для 7го уровня
    20000, // exp for level 8; ЕХП для 8го уровня
    23000, // exp for level 9; ЕХП для 9го уровня
    26000, // exp for level 10; ЕХП для 10го уровня
);

// account level discounts per level in %
// Уровень скидки на каждом уровне в %
$_CONFIG['account_level_discount'] = array(
    0, // starting discount; Начальная скидка
	0, // level 1 discount; Скидка для 1го уровня
    2, // level 2 discount; Скидка для 2го уровня
    3, // level 3 discount; Скидка для 3го уровня
    5, // level 4 discount; Скидка для 4го уровня
    8, // level 5 discount; Скидка для 5го уровня
    12, // level 6 discount; Скидка для 6го уровня
    15, // level 7 discount; Скидка для 7го уровня
    18, // level 8 discount; Скидка для 8го уровня
    20, // level 9 discount; Скидка для 9го уровня
    22, // level 10 discount; Скидка для 10го уровня
);

// list of item ids which cannot be auctioned (by default everything can be auctioned
// Лист вещей которые нельзя выставлять на аукцион (по умолчанию выставить можно все)
$_CONFIG['auction_item_blocklist'] = array(

);

//paymentwall donation API configuration  
//paymentwall настройка доната
define('PAYMENTWALL_WIDGET_URL', '[USER_ID]');
define('PAYMENTWALL_SECRET_KEY', '');
define('DONATE_CURRENCY', 'USD');
define('DONATE_CURRENCY_SYMBOL', '₽');
define('COINS_PER_DOLLAR', 1);

// g2a configuration params
define('G2A_API_HASH', '');
define('G2A_API_SECRET', '');

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
	'vote' => true,
	'donate' => true
);

//configuration for votes
//Конфигурация призов за голосование
$_CONFIG['vote_sites'] = array(
    0 => array(
        'display_name' => 'Hopzone',
        'coins_per_vote' => 1,
        'vote_delay' => 12,
        'vote_button' => '<a href="http://vgw.hopzone.net/site/vote/101195/1" target="_blank"><img style="width: auto; height: 28px;" src="http://642507963.r.cdnsun.net/img/_vbanners/lineage2/lineage2-90x60-3.gif" alt="Vote our sever on HopZone.Net" border="0"></a>',
        'type' => 'apicall',
        'api_url' => 'http://api.hopzone.net/lineage2/vote?token=RZTjHqTuYKgPFTHQ&ip_address=[IP]',
        'api_handler' => 'HopZoneAPI'
    ),
    1 => array(
        'display_name' => 'L2TopZone',
        'coins_per_vote' => 1,
        'vote_delay' => 12,
        'vote_button' => '<a href="http://l2topzone.com/vote/id/13699" target="_blank" title="l2topzone"><img style="width: auto; height: 28px;" src="http://l2topzone.com/88x31.png" alt="New server Lineage 2, the list of servers, announcements of Lineage 2" title="New server Lineage 2, the list of servers, announcements of Lineage 2" border="0"></a>',
        'type' => 'apicall',
        'api_url' => 'http://api.l2topzone.com/v1/vote?token=be98987bc668c2cbb651dcc706eb00cb&ip=[IP]',
        'api_handler' => 'TopZoneAPI'
    ),
);
