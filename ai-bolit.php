<?php
///////////////////////////////////////////////////////////////////////////
// Created and developed by Greg Zemskov, Revisium Company
// Email: audit@revisium.com, http://revisium.com/ai/

// Commercial usage is not allowed without a license purchase or written permission of the author
// Source code and signatures usage is not allowed

// Certificated in Federal Institute of Industrial Property in 2012
// http://revisium.com/ai/i/mini_aibolit.jpg

////////////////////////////////////////////////////////////////////////////
// Запрещено использование скрипта в коммерческих целях без приобретения лицензии.
// Запрещено использование исходного кода скрипта и сигнатур.
//
// По вопросам приобретения лицензии обращайтесь в компанию "Ревизиум": http://www.revisium.com
// audit@revisium.com
// На скрипт получено авторское свидетельство в Роспатенте
// http://revisium.com/ai/i/mini_aibolit.jpg
///////////////////////////////////////////////////////////////////////////
ini_set('memory_limit', '1G');
ini_set('xdebug.max_nesting_level', 500);

//@mb_internal_encoding('');

$int_enc = @ini_get('mbstring.internal_encoding');
        
define('SHORT_PHP_TAG', strtolower(ini_get('short_open_tag')) == 'on' || strtolower(ini_get('short_open_tag')) == 1 ? true : false);

// Put any strong password to open the script from web
// Впишите вместо put_any_strong_password_here сложный пароль	 

define('PASS', '????????????????'); 

//////////////////////////////////////////////////////////////////////////

if (isCli()) {
	if (strpos('--eng', $argv[$argc - 1]) !== false) {
		  define('LANG', 'EN');  
	}
} else {
   define('NEED_REPORT', true);
}
	
if (!defined('LANG')) {
   define('LANG', 'RU');  
}	

// put 1 for expert mode, 0 for basic check and 2 for paranoic mode
// установите 1 для режима "Эксперта", 0 для быстрой проверки и 2 для параноидальной проверки (для лечения сайта) 
define('AI_EXPERT_MODE', 1); 

define('REPORT_MASK_PHPSIGN', 1);
define('REPORT_MASK_SPAMLINKS', 2);
define('REPORT_MASK_DOORWAYS', 4);
define('REPORT_MASK_SUSP', 8);
define('REPORT_MASK_CANDI', 16);
define('REPORT_MASK_WRIT', 32);
define('REPORT_MASK_FULL', REPORT_MASK_PHPSIGN | REPORT_MASK_DOORWAYS | REPORT_MASK_SUSP
/* <-- remove this line to enable "recommendations"  

| REPORT_MASK_SPAMLINKS 

 remove this line to enable "recommendations" --> */
);

define('AI_HOSTER', 0); 

define('AI_EXTRA_WARN', 0);

$defaults = array(
	'path' => dirname(__FILE__),
	'scan_all_files' => 0, // full scan (rather than just a .js, .php, .html, .htaccess)
	'scan_delay' => 0, // delay in file scanning to reduce system load
	'max_size_to_scan' => '600K',
	'site_url' => '', // website url
	'no_rw_dir' => 0,
    	'skip_ext' => '',
        'skip_cache' => false,
	'report_mask' => REPORT_MASK_FULL
);


define('DEBUG_MODE', 0);

define('AIBOLIT_START_TIME', time());

define('DIR_SEPARATOR', '/');

define('AIBOLIT_MAX_NUMBER', 200);

define('DOUBLECHECK_FILE', 'AI-BOLIT-DOUBLECHECK.php');

if ((isset($_SERVER['OS']) && stripos('Win', $_SERVER['OS']) !== false)/* && stripos('CygWin', $_SERVER['OS']) === false)*/) {
   define('DIR_SEPARATOR', '\\');
}

$g_SuspiciousFiles = array('cgi', 'pl', 'o', 'so', 'py', 'sh', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7', 'pht', 'shtml', 'susp', 'suspected');
$g_SensitiveFiles = array_merge(array('php', 'js', 'htaccess', 'html', 'htm', 'tpl', 'inc', 'css', 'txt', 'sql'), $g_SuspiciousFiles);
$g_CriticalFiles = array('php', 'htaccess', 'cgi', 'pl', 'o', 'so', 'py', 'sh', 'phtml', 'php3', 'php4', 'php5', 'php6', 'php7', 'pht', 'shtml', 'susp', 'suspected', 'infected', 'vir');
$g_CriticalEntries = '<\?php|<\?=|#!/usr|#!/bin|eval|assert|base64_decode|system|create_function|exec|popen|fwrite|fputs|file_get_|call_user_func|file_put_|\$_REQUEST|ob_start|\$_GET|\$_POST|\$_SERVER|\$_FILES|move|copy|array_|reg_replace|mysql_|chr|fsockopen|\$GLOBALS|sqliteCreateFunction';
$g_VirusFiles = array('js', 'html', 'htm', 'suspicious');
$g_VirusEntries = '<\s*script|<\s*iframe|<\s*object|<\s*embed|fromCharCode|setTimeout|setInterval|location\.|document\.|window\.|navigator\.|\$(this)\.';
$g_PhishFiles = array('js', 'html', 'htm', 'suspected', 'php', 'pht', 'php7');
$g_PhishEntries = '<\s*title|<\s*html|<\s*form|<\s*body|bank|account';
$g_ShortListExt = array('php', 'php3', 'php4', 'php5', 'php6', 'php7', 'pht', 'html', 'htm', 'phtml', 'shtml', 'khtml');

if (LANG == 'RU') {
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// RUSSIAN INTERFACE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg1 = "\"Отображать по _MENU_ записей\"";
$msg2 = "\"Ничего не найдено\"";
$msg3 = "\"Отображается c _START_ по _END_ из _TOTAL_ файлов\"";
$msg4 = "\"Нет файлов\"";
$msg5 = "\"(всего записей _MAX_)\"";
$msg6 = "\"Поиск:\"";
$msg7 = "\"Первая\"";
$msg8 = "\"Предыдущая\"";
$msg9 = "\"Следующая\"";
$msg10 = "\"Последняя\"";
$msg11 = "\": активировать для сортировки столбца по возрастанию\"";
$msg12 = "\": активировать для сортировки столбцов по убыванию\"";

define('AI_STR_001', 'Отчет сканера <a href="https://revisium.com/ai/">AI-Bolit</a> v@@VERSION@@:');
define('AI_STR_002', 'Обращаем внимание на то, что большинство CMS <b>без дополнительной защиты</b> рано или поздно <b>взламывают</b>.<p> Компания <a href="https://revisium.com/">"Ревизиум"</a> предлагает услугу превентивной защиты сайта от взлома с использованием уникальной <b>процедуры "цементирования сайта"</b>. Подробно на <a href="https://revisium.com/ru/client_protect/">странице услуги</a>. <p>Лучшее лечение &mdash; это профилактика.');
define('AI_STR_003', 'Не оставляйте файл отчета на сервере, и не давайте на него прямых ссылок с других сайтов. Информация из отчета может быть использована злоумышленниками для взлома сайта, так как содержит информацию о настройках сервера, файлах и каталогах.');
define('AI_STR_004', 'Путь');
define('AI_STR_005', 'Изменение свойств');
define('AI_STR_006', 'Изменение содержимого');
define('AI_STR_007', 'Размер');
define('AI_STR_008', 'Конфигурация PHP');
define('AI_STR_009', "Вы установили слабый пароль на скрипт AI-BOLIT. Укажите пароль не менее 8 символов, содержащий латинские буквы в верхнем и нижнем регистре, а также цифры. Например, такой <b>%s</b>");
define('AI_STR_010', "Сканер AI-Bolit запускается с паролем. Если это первый запуск сканера, вам нужно придумать сложный пароль и вписать его в файле ai-bolit.php в строке №34. <p>Например, <b>define('PASS', '%s');</b><p>
После этого откройте сканер в браузере, указав пароль в параметре \"p\". <p>Например, так <b>http://mysite.ru/ai-bolit.php?p=%s</b>. ");
define('AI_STR_011', 'Текущая директория не доступна для чтения скрипту. Пожалуйста, укажите права на доступ <b>rwxr-xr-x</b> или с помощью командной строки <b>chmod +r имя_директории</b>');
define('AI_STR_012', "Затрачено времени: <b>%s</b>. Сканирование начато %s, сканирование завершено %s");
define('AI_STR_013', 'Всего проверено %s директорий и %s файлов.');
define('AI_STR_014', '<div class="rep" style="color: #0000A0">Внимание, скрипт выполнил быструю проверку сайта. Проверяются только наиболее критические файлы, но часть вредоносных скриптов может быть не обнаружена. Пожалуйста, запустите скрипт из командной строки для выполнения полного тестирования. Подробнее смотрите в <a href="https://revisium.com/ai/faq.php">FAQ вопрос №10</a>.</div>');
define('AI_STR_015', '<div class="title">Критические замечания</div>');
define('AI_STR_016', 'Эти файлы могут быть вредоносными или хакерскими скриптами');
define('AI_STR_017', 'Вирусы и вредоносные скрипты не обнаружены.');
define('AI_STR_018', 'Эти файлы могут быть javascript вирусами');
define('AI_STR_019', 'Обнаружены сигнатуры исполняемых файлов unix и нехарактерных скриптов. Они могут быть вредоносными файлами');
define('AI_STR_020', 'Двойное расширение, зашифрованный контент или подозрение на вредоносный скрипт. Требуется дополнительный анализ');
define('AI_STR_021', 'Подозрение на вредоносный скрипт');
define('AI_STR_022', 'Символические ссылки (symlinks)');
define('AI_STR_023', 'Скрытые файлы');
define('AI_STR_024', 'Возможно, каталог с дорвеем');
define('AI_STR_025', 'Не найдено директорий c дорвеями');
define('AI_STR_026', 'Предупреждения');
define('AI_STR_027', 'Подозрение на мобильный редирект, подмену расширений или автовнедрение кода');
define('AI_STR_028', 'В не .php файле содержится стартовая сигнатура PHP кода. Возможно, там вредоносный код');
define('AI_STR_029', 'Дорвеи, реклама, спам-ссылки, редиректы');
define('AI_STR_030', 'Непроверенные файлы - ошибка чтения');
define('AI_STR_031', 'Невидимые ссылки. Подозрение на ссылочный спам');
define('AI_STR_032', 'Невидимые ссылки');
define('AI_STR_033', 'Отображены только первые ');
define('AI_STR_034', 'Подозрение на дорвей');
define('AI_STR_035', 'Скрипт использует код, который часто встречается во вредоносных скриптах');
define('AI_STR_036', 'Директории из файла .adirignore были пропущены при сканировании');
define('AI_STR_037', 'Версии найденных CMS');
define('AI_STR_038', 'Большие файлы (больше чем %s). Пропущено');
define('AI_STR_039', 'Не найдено файлов больше чем %s');
define('AI_STR_040', 'Временные файлы или файлы(каталоги) - кандидаты на удаление по ряду причин');
define('AI_STR_041', 'Потенциально небезопасно! Директории, доступные скрипту на запись');
define('AI_STR_042', 'Не найдено директорий, доступных на запись скриптом');
define('AI_STR_043', 'Использовано памяти при сканировании: ');
define('AI_STR_044', 'Просканированы только файлы, перечисленные в ' . DOUBLECHECK_FILE . '. Для полного сканирования удалите файл ' . DOUBLECHECK_FILE . ' и запустите сканер повторно.');
define('AI_STR_045', '<div class="rep">Внимание! Выполнена экспресс-проверка сайта. Просканированы только файлы с расширением .php, .js, .html, .htaccess. В этом режиме могут быть пропущены вирусы и хакерские скрипты в файлах с другими расширениями. Чтобы выполнить более тщательное сканирование, поменяйте значение настройки на <b>\'scan_all_files\' => 1</b> в строке 50 или откройте сканер в браузере с параметром full: <b><a href="ai-bolit.php?p=' . PASS . '&full">ai-bolit.php?p=' . PASS . '&full</a></b>. <p>Не забудьте перед повторным запуском удалить файл ' . DOUBLECHECK_FILE . '</div>');
define('AI_STR_050', 'Замечания и предложения по работе скрипта и не обнаруженные вредоносные скрипты присылайте на <a href="mailto:ai@revisium.com">ai@revisium.com</a>.<p>Также будем чрезвычайно благодарны за любые упоминания скрипта AI-Bolit на вашем сайте, в блоге, среди друзей, знакомых и клиентов. Ссылочку можно поставить на <a href="https://revisium.com/ai/">https://revisium.com/ai/</a>. <p>Если будут вопросы - пишите <a href="mailto:ai@revisium.com">ai@revisium.com</a>. ');
define('AI_STR_051', 'Отчет по ');
define('AI_STR_052', 'Эвристический анализ обнаружил подозрительные файлы. Проверьте их на наличие вредоносного кода.');
define('AI_STR_053', 'Много косвенных вызовов функции');
define('AI_STR_054', 'Подозрение на обфусцированные переменные');
define('AI_STR_055', 'Подозрительное использование массива глобальных переменных');
define('AI_STR_056', 'Дробление строки на символы');
define('AI_STR_057', 'Сканирование выполнено в экспресс-режиме. Многие вредоносные скрипты могут быть не обнаружены.<br> Рекомендуем проверить сайт в режиме "Эксперт" или "Параноидальный". Подробно описано в <a href="https://revisium.com/ai/faq.php">FAQ</a> и инструкции к скрипту.');
define('AI_STR_058', 'Обнаружены фишинговые страницы');

define('AI_STR_059', 'Мобильных редиректов');
define('AI_STR_060', 'Вредоносных скриптов');
define('AI_STR_061', 'JS Вирусов');
define('AI_STR_062', 'Фишинговых страниц');
define('AI_STR_063', 'Исполняемых файлов');
define('AI_STR_064', 'IFRAME вставок');
define('AI_STR_065', 'Пропущенных больших файлов');
define('AI_STR_066', 'Ошибок чтения файлов');
define('AI_STR_067', 'Зашифрованных файлов');
define('AI_STR_068', 'Подозрительных (эвристика)');
define('AI_STR_069', 'Символических ссылок');
define('AI_STR_070', 'Скрытых файлов');
define('AI_STR_072', 'Рекламных ссылок и кодов');
define('AI_STR_073', 'Пустых ссылок');
define('AI_STR_074', 'Сводный отчет');
define('AI_STR_075', 'Скрипт бесплатный только для личного некоммерческого использования. Есть <a href="https://revisium.com/ai/faq.php#faq11" target=_blank>коммерческая лицензия</a> (пункт №11).');

$tmp_str = <<<HTML_FOOTER
   <div class="disclaimer"><span class="vir">[!]</span> Отказ от гарантий: невозможно гарантировать обнаружение всех вредоносных скриптов. Поэтому разработчик сканера не несет ответственности за возможные последствия работы сканера AI-Bolit или неоправданные ожидания пользователей относительно функциональности и возможностей.
   </div>
   <div class="thanx">
      Замечания и предложения по работе скрипта, а также не обнаруженные вредоносные скрипты вы можете присылать на <a href="mailto:ai@revisium.com">ai@revisium.com</a>.<br/>
      Также будем чрезвычайно благодарны за любые упоминания сканера AI-Bolit на вашем сайте, в блоге, среди друзей, знакомых и клиентов. <br/>Ссылку можно поставить на страницу <a href="https://revisium.com/ai/">https://revisium.com/ai/</a>.<br/> 
     <p>Получить консультацию или задать вопросы можно по email <a href="mailto:ai@revisium.com">ai@revisium.com</a>.</p> 
	</div>
HTML_FOOTER;

define('AI_STR_076', $tmp_str);
define('AI_STR_077', "Подозрительные параметры времени изменения файла");
define('AI_STR_078', "Подозрительные атрибуты файла");
define('AI_STR_079', "Подозрительное местоположение файла");
define('AI_STR_080', "Обращаем внимание, что обнаруженные файлы не всегда являются вирусами и хакерскими скриптами. Сканер старается минимизировать число ложных обнаружений, но это не всегда возможно, так как найденный фрагмент может встречаться как во вредоносных скриптах, так и в обычных.");
define('AI_STR_081', "Уязвимости в скриптах");
define('AI_STR_082', "Добавленные файлы");
define('AI_STR_083', "Измененные файлы");
define('AI_STR_084', "Удаленные файлы");
define('AI_STR_085', "Добавленные каталоги");
define('AI_STR_086', "Удаленные каталоги");
define('AI_STR_087', "Изменения в файловой структуре");

$l_Offer =<<<OFFER
    <div>
	 <div class="crit" style="font-size: 17px;"><b>Внимание! Наш сканер обнаружил подозрительный или вредоносный код</b>.</div> 
	 <br/>Скорее всего, ваш сайт был взломан и заражен. Рекомендуем срочно <a href="https://revisium.com/ru/order/" target=_blank>обратиться за консультацией</a> к специалистам по информационной безопасности.
	</div>
	<br/>
	<div>
	   Пришлите нам отчет в архиве .zip на <a href="mailto:ai@revisium.com">ai@revisium.com</a> для проверки вашего сайта на вирусы и взлом.<p>
	   Компания "<a href="https://revisium.com/">Ревизиум</a>" - лечение сайта от вирусов и защита от взлома.
	   <p><hr size=1></p>
	   Также выполните дополнительный анализ новым <b><a href="http://rescan.pro/?utm=aibolit" target=_blank>веб-сканером ReScan.Pro</a></b>.
	</div>
	<br/>
	
    <div class="caution">@@CAUTION@@</div>
OFFER;

} else {
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ENGLISH INTERFACE
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$msg1 = "\"Display _MENU_ records\"";
$msg2 = "\"Not found\"";
$msg3 = "\"Display from _START_ to _END_ of _TOTAL_ files\"";
$msg4 = "\"No files\"";
$msg5 = "\"(total _MAX_)\"";
$msg6 = "\"Filter/Search:\"";
$msg7 = "\"First\"";
$msg8 = "\"Previous\"";
$msg9 = "\"Next\"";
$msg10 = "\"Last\"";
$msg11 = "\": activate to sort row ascending order\"";
$msg12 = "\": activate to sort row descending order\"";

define('AI_STR_001', 'AI-Bolit v@@VERSION@@ Scan Report:');
define('AI_STR_002', '');
define('AI_STR_003', 'Caution! Do not leave either ai-bolit.php or report file on server and do not provide direct links to the report file. Report file contains sensitive information about your website which could be used by hackers. So keep it in safe place and don\'t leave on website!');
define('AI_STR_004', 'Path');
define('AI_STR_005', 'iNode Changed');
define('AI_STR_006', 'Modified');
define('AI_STR_007', 'Size');
define('AI_STR_008', 'PHP Info');
define('AI_STR_009', "Your password for AI-BOLIT is too weak. Password must be more than 8 character length, contain both latin letters in upper and lower case, and digits. E.g. <b>%s</b>");
define('AI_STR_010', "Open AI-BOLIT with password specified in the beggining of file in PASS variable. <br/>E.g. http://you_website.com/ai-bolit.php?p=<b>%s</b>");
define('AI_STR_011', 'Current folder is not readable. Please change permission for <b>rwxr-xr-x</b> or using command line <b>chmod +r folder_name</b>');
define('AI_STR_012', "<div class=\"rep\">%s malicious signatures known, %s virus signatures and other malicious code. Elapsed: <b>%s</b
>.<br/>Started: %s. Stopped: %s</div> ");
define('AI_STR_013', 'Scanned %s folders and %s files.');
define('AI_STR_014', '<div class="rep" style="color: #0000A0">Attention! Script has performed quick scan. It scans only .html/.js/.php files  in quick scan mode so some of malicious scripts might not be detected. <br>Please launch script from a command line thru SSH to perform full scan.');
define('AI_STR_015', '<div class="title">Critical</div>');
define('AI_STR_016', 'Shell script signatures detected. Might be a malicious or hacker\'s scripts');
define('AI_STR_017', 'Shell scripts signatures not detected.');
define('AI_STR_018', 'Javascript virus signatures detected:');
define('AI_STR_019', 'Unix executables signatures and odd scripts detected. They might be a malicious binaries or rootkits:');
define('AI_STR_020', 'Suspicious encoded strings, extra .php extention or external includes detected in PHP files. Might be a malicious or hacker\'s script:');
define('AI_STR_021', 'Might be a malicious or hacker\'s script:');
define('AI_STR_022', 'Symlinks:');
define('AI_STR_023', 'Hidden files:');
define('AI_STR_024', 'Files might be a part of doorway:');
define('AI_STR_025', 'Doorway folders not detected');
define('AI_STR_026', 'Warnings');
define('AI_STR_027', 'Malicious code in .htaccess (redirect to external server, extention handler replacement or malicious code auto-append):');
define('AI_STR_028', 'Non-PHP file has PHP signature. Check for malicious code:');
define('AI_STR_029', 'This script has black-SEO links or linkfarm. Check if it was installed by yourself:');
define('AI_STR_030', 'Reading error. Skipped.');
define('AI_STR_031', 'These files have invisible links, might be black-seo stuff:');
define('AI_STR_032', 'List of invisible links:');
define('AI_STR_033', 'Displayed first ');
define('AI_STR_034', 'Folders contained too many .php or .html files. Might be a doorway:');
define('AI_STR_035', 'Suspicious code detected. It\'s usually used in malicious scrips:');
define('AI_STR_036', 'The following list of files specified in .adirignore has been skipped:');
define('AI_STR_037', 'CMS found:');
define('AI_STR_038', 'Large files (greater than %s! Skipped:');
define('AI_STR_039', 'Files greater than %s not found');
define('AI_STR_040', 'Files recommended to be remove due to security reason:');
define('AI_STR_041', 'Potentially unsafe! Folders which are writable for scripts:');
define('AI_STR_042', 'Writable folders not found');
define('AI_STR_043', 'Memory used: ');
define('AI_STR_044', 'Quick scan through the files from ' . DOUBLECHECK_FILE . '. For full scan remove ' . DOUBLECHECK_FILE . ' and launch scanner once again.');
define('AI_STR_045', '<div class="notice"><span class="vir">[!]</span> Ai-BOLIT is working in quick scan mode, only .php, .html, .htaccess files will be checked. Change the following setting \'scan_all_files\' => 1 to perform full scanning.</b>. </div>');
define('AI_STR_050', "I'm sincerely appreciate reports for any bugs you may found in the script. Please email me: <a href=\"mailto:audit@revisium.com\">audit@revisium.com</a>.<p> Also I appriciate any reference to the script in your blog or forum posts. Thank you for the link to download page: <a href=\"https://revisium.com/aibo/\">https://revisium.com/aibo/</a>");
define('AI_STR_051', 'Report for ');
define('AI_STR_052', 'Heuristic Analyzer has detected suspicious files. Check if they are malware.');
define('AI_STR_053', 'Function called by reference');
define('AI_STR_054', 'Suspected for obfuscated variables');
define('AI_STR_055', 'Suspected for $GLOBAL array usage');
define('AI_STR_056', 'Abnormal split of string');
define('AI_STR_057', 'Scanning has been done in simple mode. It is strongly recommended to perform scanning in "Expert" mode. See readme.txt for details.');
define('AI_STR_058', 'Phishing pages detected:');

define('AI_STR_059', 'Mobile redirects');
define('AI_STR_060', 'Malware');
define('AI_STR_061', 'JS viruses');
define('AI_STR_062', 'Phishing pages');
define('AI_STR_063', 'Unix executables');
define('AI_STR_064', 'IFRAME injections');
define('AI_STR_065', 'Skipped big files');
define('AI_STR_066', 'Reading errors');
define('AI_STR_067', 'Encrypted files');
define('AI_STR_068', 'Suspicious (heuristics)');
define('AI_STR_069', 'Symbolic links');
define('AI_STR_070', 'Hidden files');
define('AI_STR_072', 'Adware and spam links');
define('AI_STR_073', 'Empty links');
define('AI_STR_074', 'Summary');
define('AI_STR_075', 'For non-commercial use only. Please, purchase the license for commercial usage of the scanner. Email us: ai@revisium.com');

$tmp_str =<<<HTML_FOOTER
		   <div class="disclaimer"><span class="vir">[!]</span> Disclaimer: We're not liable to you for any damages, including general, special, incidental or consequential damages arising out of the use or inability to use the script (including but not limited to loss of data or report being rendered inaccurate or failure of the script). There's no warranty for the program. Use at your own risk. 
		   </div>
		   <div class="thanx">
		      We're greatly appreciate for any references in the social networks, forums or blogs to our scanner AI-BOLIT <a href="https://revisium.com/aibo/">https://revisium.com/aibo/</a>.<br/> 
		     <p>Contact us via email if you have any questions regarding the scanner or need report analysis: <a href="mailto:ai@revisium.com">ai@revisium.com</a>.</p> 
			</div>
HTML_FOOTER;
define('AI_STR_076', $tmp_str);
define('AI_STR_077', "Suspicious file mtime and ctime");
define('AI_STR_078', "Suspicious file permissions");
define('AI_STR_079', "Suspicious file location");
define('AI_STR_081', "Vulnerable Scripts");
define('AI_STR_082', "Added files");
define('AI_STR_083', "Modified files");
define('AI_STR_084', "Deleted files");
define('AI_STR_085', "Added directories");
define('AI_STR_086', "Deleted directories");
define('AI_STR_087', "Integrity Check Report");

$l_Offer =<<<HTML_OFFER_EN
<div>
 <div class="crit" style="font-size: 17px;"><b>Attention! The scanner has detected malicious files.</b></div> 
 <br/>Most likely the website has been compromised. Please, <a href="https://revisium.com/en/contacts/" target=_blank>contact website security experts</a> or experienced webmaster as soon as possible to check the report or remove the malware from files.
 <p><hr size=1></p>
 Also, online website analysis is available using our new <b><a href="http://rescan.pro/?utm=aibo" target=_blank>ReScan.Pro Web Scanner</a></b>.
</div>
<br/>
<div>
   <a href="mailto:ai@revisium.com">ai@revisium.com</a>, <a href="https://revisium.com/en/contacts/">https://revisium.com/en/home/</a>
</div>
<div class="caution">@@CAUTION@@</div>
HTML_OFFER_EN;

define('AI_STR_080', "Notice! Some of detected files may not contain malicious code. Scanner tries to minimize a number of false positives, but sometimes it's impossible, because same piece of code may be used either in malware or in normal scripts.");
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$l_Template =<<<MAIN_PAGE
<html>
<head>
<!-- revisium.com/ai/ -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<META NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW">
<title>@@HEAD_TITLE@@</title>
<style type="text/css" title="currentStyle">
	@import "https://revisium.com/extra/media/css/demo_page2.css";
	@import "https://revisium.com/extra/media/css/jquery.dataTables2.css";
</style>

<script type="text/javascript" language="javascript" src="https://yandex.st/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="https://datatables.net/download/build/jquery.dataTables.js"></script>

<style type="text/css">
 body 
 {
   font-family: Tahoma;
   color: #5a5a5a;
   background: #FFFFFF;
   font-size: 14px;
   margin: 20px;
   padding: 0;
 }

.header
 {
   font-size: 34px;
   margin: 0 0 10px 0;
 }

 .hidd
 {
    display: none;
 }
 
 .ok
 {
    color: green;
 }
 
 .line_no
 {
   -webkit-border-radius: 6px;
   -moz-border-radius: 6px;
   border-radius: 6px;

   background: #DAF2C1;
   padding: 2px 5px 2px 5px;
   margin: 0 5px 0 5px;
 }
 
 .credits_header 
 {
  -webkit-border-radius: 6px;
   -moz-border-radius: 6px;
   border-radius: 6px;

   background: #F2F2F2;
   padding: 10px;
   font-size: 11px;
    margin: 0 0 10px 0;
 }
 
 .marker
 {
    color: #FF0090;
	font-weight: 100;
	background: #FF0090;
	padding: 2px 0px 2px 0px;
	width: 2px;
 }
 
 .title
 {
   font-size: 24px;
   margin: 20px 0 10px 0;
   color: #9CA9D1;
}

.summary 
{
  float: left;
  width: 500px;
}

.summary TD
{
  font-size: 12px;
  border-bottom: 1px solid #F0F0F0;
  font-weight: 700;
  padding: 10px 0 10px 0;
}
 
.crit, .vir
{
  color: #D84B55;
}

.intitem
{
  color:#4a6975;
}

.spacer
{
   margin: 0 0 50px 0;
   clear:both;
}

.warn
{
  color: #F6B700;
}

.clear
{
   clear: both;
}

.offer
{
  -webkit-border-radius: 6px;
   -moz-border-radius: 6px;
   border-radius: 6px;

   width: 500px;
   background: #ECF7DE;
   color: #747474;
   font-size: 11px;
   font-family: Arial;
   padding: 20px;
   margin: 20px 0 0 500px;
   
   font-size: 16px;
}
 
.flist
{
   font-family: Arial;
}

.flist TD
{
   font-size: 11px;
   padding: 5px;
}

.flist TH
{
   font-size: 12px;
   height: 30px;
   padding: 5px;
   background: #CEE9EF;
}


.it
{
   font-size: 14px;
   font-weight: 100;
   margin-top: 10px;
}

.crit .it A {
   color: #E50931; 
   line-height: 25px;
   text-decoration: none;
}

.warn .it A {
   color: #F2C900; 
   line-height: 25px;
   text-decoration: none;
}



.details
{
   font-family: Calibri;
   font-size: 12px;
   margin: 10px 10px 10px 0px;
}

.crit .details
{
   color: #A08080;
}

.warn .details
{
   color: #808080;
}

.details A
{
  color: #FFF;
  font-weight: 700;
  text-decoration: none;
  padding: 2px;
  background: #E5CEDE;
  -webkit-border-radius: 7px;
   -moz-border-radius: 7px;
   border-radius: 7px;
}

.details A:hover
{
   background: #A0909B;
}

.ctd
{
   margin: 10px 0px 10px 0;
   align:center;
}

.ctd A 
{
   color: #0D9922;
}

.disclaimer
{
   color: darkgreen;
   margin: 10px 10px 10px 0;
}

.note_vir
{
   margin: 10px 0 10px 0;
   //padding: 10px;
   color: #FF4F4F;
   font-size: 15px;
   font-weight: 700;
   clear:both;
  
}

.note_warn
{
   margin: 10px 0 10px 0;
   color: #F6B700;
   font-size: 15px;
   font-weight: 700;
   clear:both;
}

.note_int
{
   margin: 10px 0 10px 0;
   color: #60b5d6;
   font-size: 15px;
   font-weight: 700;
   clear:both;
}

.updateinfo
{
  color: #FFF;
  text-decoration: none;
  background: #E5CEDE;
  -webkit-border-radius: 7px;
   -moz-border-radius: 7px;
   border-radius: 7px;

  margin: 10px 0 10px 0px;   
  padding: 10px;
}


.caution
{
  color: #EF7B75;
  text-decoration: none;
  margin: 20px 0 0px 0px;   
  font-size: 12px;
}

.footer
{
  color: #303030;
  text-decoration: none;
  background: #F4F4F4;
  -webkit-border-radius: 7px;
   -moz-border-radius: 7px;
   border-radius: 7px;

  margin: 80px 0 10px 0px;   
  padding: 10px;
}

.rep
{
  color: #303030;
  text-decoration: none;
  background: #94DDDB;
  -webkit-border-radius: 7px;
   -moz-border-radius: 7px;
   border-radius: 7px;

  margin: 10px 0 10px 0px;   
  padding: 10px;
  font-size: 12px;
}

</style>

</head>
<body>

<div class="header">@@MAIN_TITLE@@ @@PATH_URL@@ (@@MODE@@)</div>
<div class="credits_header">@@CREDITS@@</div>
<div class="details_header">
   @@STAT@@<br/>
   @@SCANNED@@ @@MEMORY@@.
 </div>

 @@WARN_QUICK@@
 
 <div class="summary">
@@SUMMARY@@
 </div>
 
 <div class="offer">
@@OFFER@@
 </div>
  
 <div class="clear"></div>
 
 @@MAIN_CONTENT@@
 
	<div class="footer">
	@@FOOTER@@
	</div>
	
<script language="javascript">

function hsig(id) {
  var divs = document.getElementsByTagName("tr");
  for(var i = 0; i < divs.length; i++){
     
     if (divs[i].getAttribute('o') == id) {
        divs[i].innerHTML = '';
     }
  }

  return false;
}


$(document).ready(function(){
    $('#table_crit').dataTable({
       "aLengthMenu": [[100 , 500, -1], [100, 500, "All"]],
       "aoColumns": [
                                     {"iDataSort": 7, "width":"70%"},
                                     {"iDataSort": 5},
                                     {"iDataSort": 6},
                                     {"bSortable": true},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false}
                     ],
		"paging": true,
       "iDisplayLength": 500,
		"oLanguage": {
			"sLengthMenu": $msg1,
			"sZeroRecords": $msg2,
			"sInfo": $msg3,
			"sInfoEmpty": $msg4,
			"sInfoFiltered": $msg5,
			"sSearch":       $msg6,
			"sUrl":          "",
			"oPaginate": {
				"sFirst": $msg7,
				"sPrevious": $msg8,
				"sNext": $msg9,
				"sLast": $msg10
			},
			"oAria": {
				"sSortAscending": $msg11,
				"sSortDescending": $msg12	
			}
		}

     } );

});

$(document).ready(function(){
    $('#table_vir').dataTable({
       "aLengthMenu": [[100 , 500, -1], [100, 500, "All"]],
		"paging": true,
       "aoColumns": [
                                     {"iDataSort": 7, "width":"70%"},
                                     {"iDataSort": 5},
                                     {"iDataSort": 6},
                                     {"bSortable": true},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false}
                     ],
       "iDisplayLength": 500,
		"oLanguage": {
			"sLengthMenu": $msg1,
			"sZeroRecords": $msg2,
			"sInfo": $msg3,
			"sInfoEmpty": $msg4,
			"sInfoFiltered": $msg5,
			"sSearch":       $msg6,
			"sUrl":          "",
			"oPaginate": {
				"sFirst": $msg7,
				"sPrevious": $msg8,
				"sNext": $msg9,
				"sLast": $msg10
			},
			"oAria": {
				"sSortAscending":  $msg11,
				"sSortDescending": $msg12	
			}
		},

     } );

});

if ($('#table_warn0')) {
    $('#table_warn0').dataTable({
       "aLengthMenu": [[100 , 500, -1], [100, 500, "All"]],
		"paging": true,
       "aoColumns": [
                                     {"iDataSort": 7, "width":"70%"},
                                     {"iDataSort": 5},
                                     {"iDataSort": 6},
                                     {"bSortable": true},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false}
                     ],
			         "iDisplayLength": 500,
			  		"oLanguage": {
			  			"sLengthMenu": $msg1,
			  			"sZeroRecords": $msg2,
			  			"sInfo": $msg3,
			  			"sInfoEmpty": $msg4,
			  			"sInfoFiltered": $msg5,
			  			"sSearch":       $msg6,
			  			"sUrl":          "",
			  			"oPaginate": {
			  				"sFirst": $msg7,
			  				"sPrevious": $msg8,
			  				"sNext": $msg9,
			  				"sLast": $msg10
			  			},
			  			"oAria": {
			  				"sSortAscending":  $msg11,
			  				"sSortDescending": $msg12	
			  			}
		}

     } );
}

if ($('#table_warn1')) {
    $('#table_warn1').dataTable({
       "aLengthMenu": [[100 , 500, -1], [100, 500, "All"]],
		"paging": true,
       "aoColumns": [
                                     {"iDataSort": 7, "width":"70%"},
                                     {"iDataSort": 5},
                                     {"iDataSort": 6},
                                     {"bSortable": true},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false},
                                     {"bVisible": false}
                     ],
			         "iDisplayLength": 500,
			  		"oLanguage": {
			  			"sLengthMenu": $msg1,
			  			"sZeroRecords": $msg2,
			  			"sInfo": $msg3,
			  			"sInfoEmpty": $msg4,
			  			"sInfoFiltered": $msg5,
			  			"sSearch":       $msg6,
			  			"sUrl":          "",
			  			"oPaginate": {
			  				"sFirst": $msg7,
			  				"sPrevious": $msg8,
			  				"sNext": $msg9,
			  				"sLast": $msg10
			  			},
			  			"oAria": {
			  				"sSortAscending":  $msg11,
			  				"sSortDescending": $msg12	
			  			}
		}

     } );
}


</script>
<!-- @@SERVICE_INFO@@  -->
 </body>
</html>
MAIN_PAGE;

$g_AiBolitAbsolutePath = dirname(__FILE__);

if (file_exists($g_AiBolitAbsolutePath . '/ai-design.html')) {
  $l_Template = file_get_contents($g_AiBolitAbsolutePath . '/ai-design.html');
}

$l_Template = str_replace('@@MAIN_TITLE@@', AI_STR_001, $l_Template);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//BEGIN_SIG 20/08/2017 12:34:16
$g_DBShe = unserialize(base64_decode("YTo0Mzk6e2k6MDtzOjE1OiJ3NGwzWHpZMyBNYWlsZXIiO2k6MTtzOjEwOiJDb2RlZF9ieV9WIjtpOjI7czozNToibW92ZV91cGxvYWRlZF9maWxlKCRfRklMRVNbPHFxPkYxbDMiO2k6MztzOjEzOiJCeTxzMT5LeW1Mam5rIjtpOjQ7czoxMzoiQnk8czE+U2g0TGluayI7aTo1O3M6MTY6IkJ5PHMxPkFub25Db2RlcnMiO2k6NjtzOjQ2OiIkdXNlckFnZW50cyA9IGFycmF5KCJHb29nbGUiLCAiU2x1cnAiLCAiTVNOQm90IjtpOjc7czo2OiJbM3Jhbl0iO2k6ODtzOjEwOiJEYXduX2FuZ2VsIjtpOjk7czo4OiJSM0RUVVhFUyI7aToxMDtzOjIwOiJ2aXNpdG9yVHJhY2tlcl9pc01vYiI7aToxMTtzOjI0OiJjb21fY29udGVudC9hcnRpY2xlZC5waHAiO2k6MTI7czoxNzoiPHRpdGxlPkVtc1Byb3h5IHYiO2k6MTM7czoxMzoiYW5kcm9pZC1pZ3JhLSI7aToxNDtzOjE1OiI9PT06OjptYWQ6Ojo9PT0iO2k6MTU7czo1OiJINHhPciI7aToxNjtzOjg6IlI0cEg0eDByIjtpOjE3O3M6ODoiTkc2ODlTa3ciO2k6MTg7czoxMToiZm9wby5jb20uYXIiO2k6MTk7czo5OiI2NC42OC44MC4iO2k6MjA7czo4OiJIYXJjaGFMaSI7aToyMTtzOjE1OiJ4eFI5OW11c3ZpZWkweDAiO2k6MjI7czoxMToiUC5oLnAuUy5wLnkiO2k6MjM7czoxNDoiX3NoZWxsX2F0aWxkaV8iO2k6MjQ7czo5OiJ+IFNoZWxsIEkiO2k6MjU7czo2OiIweGRkODIiO2k6MjY7czoxNDoiQW50aWNoYXQgc2hlbGwiO2k6Mjc7czoxMjoiQUxFTWlOIEtSQUxpIjtpOjI4O3M6MTY6IkFTUFggU2hlbGwgYnkgTFQiO2k6Mjk7czo5OiJhWlJhaUxQaFAiO2k6MzA7czoyMjoiQ29kZWQgQnkgQ2hhcmxpY2hhcGxpbiI7aTozMTtzOjc6IkJsMG9kM3IiO2k6MzI7czoxMjoiQlkgaVNLT1JQaVRYIjtpOjMzO3M6MTE6ImRldmlselNoZWxsIjtpOjM0O3M6MzA6IldyaXR0ZW4gYnkgQ2FwdGFpbiBDcnVuY2ggVGVhbSI7aTozNTtzOjk6ImMyMDA3LnBocCI7aTozNjtzOjIyOiJDOTkgTW9kaWZpZWQgQnkgUHN5Y2gwIjtpOjM3O3M6MTc6IiRjOTlzaF91cGRhdGVmdXJsIjtpOjM4O3M6OToiQzk5IFNoZWxsIjtpOjM5O3M6MjI6ImNvb2tpZW5hbWUgPSAid2llZWVlZSIiO2k6NDA7czozODoiQ29kZWQgYnkgOiBTdXBlci1DcnlzdGFsIGFuZCBNb2hhamVyMjIiO2k6NDE7czoxMjoiQ3J5c3RhbFNoZWxsIjtpOjQyO3M6MjM6IlRFQU0gU0NSSVBUSU5HIC0gUk9ETk9DIjtpOjQzO3M6MTE6IkN5YmVyIFNoZWxsIjtpOjQ0O3M6NzoiZDBtYWlucyI7aTo0NTtzOjEzOiJEYXJrRGV2aWx6LmlOIjtpOjQ2O3M6MjQ6IlNoZWxsIHdyaXR0ZW4gYnkgQmwwb2QzciI7aTo0NztzOjMzOiJEaXZlIFNoZWxsIC0gRW1wZXJvciBIYWNraW5nIFRlYW0iO2k6NDg7czoxNToiRGV2ci1pIE1lZnNlZGV0IjtpOjQ5O3M6MzI6IkNvbWFuZG9zIEV4Y2x1c2l2b3MgZG8gRFRvb2wgUHJvIjtpOjUwO3M6MjA6IkVtcGVyb3IgSGFja2luZyBURUFNIjtpOjUxO3M6MjA6IkZpeGVkIGJ5IEFydCBPZiBIYWNrIjtpOjUyO3M6MjE6IkZhVGFMaXNUaUN6X0Z4IEZ4MjlTaCI7aTo1MztzOjI3OiJMdXRmZW4gRG9zeWF5aSBBZGxhbmRpcmluaXoiO2k6NTQ7czoyMjoidGhpcyBpcyBhIHByaXYzIHNlcnZlciI7aTo1NTtzOjEzOiJHRlMgV2ViLVNoZWxsIjtpOjU2O3M6MTE6IkdIQyBNYW5hZ2VyIjtpOjU3O3M6MTQ6Ikdvb2cxZV9hbmFsaXN0IjtpOjU4O3M6MTM6IkdyaW5heSBHbzBvJEUiO2k6NTk7czoyOToiaDRudHUgc2hlbGwgW3Bvd2VyZWQgYnkgdHNvaV0iO2k6NjA7czoyNToiSGFja2VkIEJ5IERldnItaSBNZWZzZWRldCI7aTo2MTtzOjE3OiJIQUNLRUQgQlkgUkVBTFdBUiI7aTo2MjtzOjMyOiJIYWNrZXJsZXIgVnVydXIgTGFtZXJsZXIgU3VydW51ciI7aTo2MztzOjExOiJpTUhhQmlSTGlHaSI7aTo2NDtzOjk6IktBX3VTaGVsbCI7aTo2NTtzOjc6IkxpejB6aU0iO2k6NjY7czoxMToiTG9jdXM3U2hlbGwiO2k6Njc7czozNjoiTW9yb2NjYW4gU3BhbWVycyBNYS1FZGl0aW9OIEJ5IEdoT3NUIjtpOjY4O3M6MTA6Ik1hdGFtdSBNYXQiO2k6Njk7czo1MDoiT3BlbiB0aGUgZmlsZSBhdHRhY2htZW50IGlmIGFueSwgYW5kIGJhc2U2NF9lbmNvZGUiO2k6NzA7czo2OiJtMHJ0aXgiO2k6NzE7czo1OiJtMGh6ZSI7aTo3MjtzOjEwOiJNYXRhbXUgTWF0IjtpOjczO3M6MTY6Ik1vcm9jY2FuIFNwYW1lcnMiO2k6NzQ7czoxNToiJE15U2hlbGxWZXJzaW9uIjtpOjc1O3M6OToiTXlTUUwgUlNUIjtpOjc2O3M6MTk6Ik15U1FMIFdlYiBJbnRlcmZhY2UiO2k6Nzc7czoyNzoiTXlTUUwgV2ViIEludGVyZmFjZSBWZXJzaW9uIjtpOjc4O3M6MTQ6Ik15U1FMIFdlYnNoZWxsIjtpOjc5O3M6ODoiTjN0c2hlbGwiO2k6ODA7czoxNjoiSGFja2VkIGJ5IFNpbHZlciI7aTo4MTtzOjE2OiJIYUNrZUQgQnkgRmFsbGFnIjtpOjgyO3M6ODoiZXJpdmZ2aHoiO2k6ODM7czo3OiJOZW9IYWNrIjtpOjg0O3M6MTA6IkJ5IEt5bUxqbmsiO2k6ODU7czoxMDoiQmlnIFIzenVsdCI7aTo4NjtzOjIxOiJOZXR3b3JrRmlsZU1hbmFnZXJQSFAiO2k6ODc7czoyMDoiTklYIFJFTU9URSBXRUItU0hFTEwiO2k6ODg7czoyNjoiTyBCaVIgS1JBTCBUQUtMaVQgRURpbEVNRVoiO2k6ODk7czoxODoiUEhBTlRBU01BLSBOZVcgQ21EIjtpOjkwO3M6MjE6IlBJUkFURVMgQ1JFVyBXQVMgSEVSRSI7aTo5MTtzOjIxOiJhIHNpbXBsZSBwaHAgYmFja2Rvb3IiO2k6OTI7czoyMDoiTE9URlJFRSBQSFAgQmFja2Rvb3IiO2k6OTM7czozMToiTmV3cyBSZW1vdGUgUEhQIFNoZWxsIEluamVjdGlvbiI7aTo5NDtzOjk6IlBIUEphY2thbCI7aTo5NTtzOjIwOiJQSFAgSFZBIFNoZWxsIFNjcmlwdCI7aTo5NjtzOjEzOiJwaHBSZW1vdGVWaWV3IjtpOjk3O3M6MzU6IlBIUCBTaGVsbCBpcyBhbmludGVyYWN0aXZlIFBIUC1wYWdlIjtpOjk4O3M6NjoiUEhWYXl2IjtpOjk5O3M6MjY6IlBQUyAxLjAgcGVybC1jZ2kgd2ViIHNoZWxsIjtpOjEwMDtzOjIyOiJQcmVzcyBPSyB0byBlbnRlciBzaXRlIjtpOjEwMTtzOjIyOiJwcml2YXRlIFNoZWxsIGJ5IG00cmNvIjtpOjEwMjtzOjU6InIwbmluIjtpOjEwMztzOjY6IlI1N1NxbCI7aToxMDQ7czoxMzoicjU3c2hlbGxcLnBocCI7aToxMDU7czoxNToicmdvZGBzIHdlYnNoZWxsIjtpOjEwNjtzOjIwOiJyZWFsYXV0aD1TdkJEODVkSU51MyI7aToxMDc7czoxNjoiUnUyNFBvc3RXZWJTaGVsbCI7aToxMDg7czoyMToiS0Fkb3QgVW5pdmVyc2FsIFNoZWxsIjtpOjEwOTtzOjEwOiJDckB6eV9LaW5nIjtpOjExMDtzOjIwOiJTYWZlX01vZGUgQnlwYXNzIFBIUCI7aToxMTE7czoxNzoiU2FyYXNhT24gU2VydmljZXMiO2k6MTEyO3M6MjU6IlNpbXBsZSBQSFAgYmFja2Rvb3IgYnkgREsiO2k6MTEzO3M6MTk6IkctU2VjdXJpdHkgV2Vic2hlbGwiO2k6MTE0O3M6MjU6IlNpbW9yZ2ggU2VjdXJpdHkgTWFnYXppbmUiO2k6MTE1O3M6MjA6IlNoZWxsIGJ5IE1hd2FyX0hpdGFtIjtpOjExNjtzOjEzOiJTU0kgd2ViLXNoZWxsIjtpOjExNztzOjExOiJTdG9ybTdTaGVsbCI7aToxMTg7czo5OiJUaGVfQmVLaVIiO2k6MTE5O3M6OToiVzNEIFNoZWxsIjtpOjEyMDtzOjEzOiJ3NGNrMW5nIHNoZWxsIjtpOjEyMTtzOjI4OiJkZXZlbG9wZWQgYnkgRGlnaXRhbCBPdXRjYXN0IjtpOjEyMjtzOjMyOiJXYXRjaCBZb3VyIHN5c3RlbSBTaGFueSB3YXMgaGVyZSI7aToxMjM7czoxMjoiV2ViIFNoZWxsIGJ5IjtpOjEyNDtzOjEzOiJXU08yIFdlYnNoZWxsIjtpOjEyNTtzOjMzOiJOZXR3b3JrRmlsZU1hbmFnZXJQSFAgZm9yIGNoYW5uZWwiO2k6MTI2O3M6Mjc6IlNtYWxsIFBIUCBXZWIgU2hlbGwgYnkgWmFDbyI7aToxMjc7czoxMDoiTXJsb29sLmV4ZSI7aToxMjg7czo2OiJTRW9ET1IiO2k6MTI5O3M6OToiTXIuSGlUbWFuIjtpOjEzMDtzOjU6ImQzYn5YIjtpOjEzMTtzOjE2OiJDb25uZWN0QmFja1NoZWxsIjtpOjEzMjtzOjEwOiJCWSBNTU5CT0JaIjtpOjEzMztzOjI2OiJPTEI6UFJPRFVDVDpPTkxJTkVfQkFOS0lORyI7aToxMzQ7czoxMDoiQzBkZXJ6LmNvbSI7aToxMzU7czo3OiJNckhhemVtIjtpOjEzNjtzOjk6InYwbGQzbTBydCI7aToxMzc7czo2OiJLIUxMM3IiO2k6MTM4O3M6MTA6IkRyLmFib2xhbGgiO2k6MTM5O3M6MzA6IiRyYW5kX3dyaXRhYmxlX2ZvbGRlcl9mdWxscGF0aCI7aToxNDA7czo4NDoiPHRleHRhcmVhIG5hbWU9XCJwaHBldlwiIHJvd3M9XCI1XCIgY29scz1cIjE1MFwiPiIuQCRfUE9TVFsncGhwZXYnXS4iPC90ZXh0YXJlYT48YnI+IjtpOjE0MTtzOjE2OiJjOTlmdHBicnV0ZWNoZWNrIjtpOjE0MjtzOjk6IkJ5IFBzeWNoMCI7aToxNDM7czoxNzoiJGM5OXNoX3VwZGF0ZWZ1cmwiO2k6MTQ0O3M6MTQ6InRlbXBfcjU3X3RhYmxlIjtpOjE0NTtzOjE3OiJhZG1pbkBzcHlncnVwLm9yZyI7aToxNDY7czo3OiJjYXN1czE1IjtpOjE0NztzOjEzOiJXU0NSSVBULlNIRUxMIjtpOjE0ODtzOjQ3OiJFeGVjdXRlZCBjb21tYW5kOiA8Yj48Zm9udCBjb2xvcj0jZGNkY2RjPlskY21kXSI7aToxNDk7czoxMToiY3RzaGVsbC5waHAiO2k6MTUwO3M6MTU6IkRYX0hlYWRlcl9kcmF3biI7aToxNTE7czo4NjoiY3JsZi4ndW5saW5rKCRuYW1lKTsnLiRjcmxmLidyZW5hbWUoIn4iLiRuYW1lLCAkbmFtZSk7Jy4kY3JsZi4ndW5saW5rKCJncnBfcmVwYWlyLnBocCIiO2k6MTUyO3M6MTA1OiIvMHRWU0cvU3V2MFVyL2hhVVlBZG4zak1Rd2Jib2NHZmZBZUMyOUJOOXRtQmlKZFYxbGsrallEVTkyQzk0amR0RGlmK3hPWWpHNkNMaHgzMVVvOXg5L2VBV2dzQks2MGtLMm1Md3F6cWQiO2k6MTUzO3M6MTE1OiJtcHR5KCRfUE9TVFsndXInXSkpICRtb2RlIHw9IDA0MDA7IGlmICghZW1wdHkoJF9QT1NUWyd1dyddKSkgJG1vZGUgfD0gMDIwMDsgaWYgKCFlbXB0eSgkX1BPU1RbJ3V4J10pKSAkbW9kZSB8PSAwMTAwIjtpOjE1NDtzOjM3OiJrbGFzdmF5di5hc3A/eWVuaWRvc3lhPTwlPWFrdGlma2xhcyU+IjtpOjE1NTtzOjEyMjoibnQpKGRpc2tfdG90YWxfc3BhY2UoZ2V0Y3dkKCkpLygxMDI0KjEwMjQpKSAuICJNYiAiIC4gIkZyZWUgc3BhY2UgIiAuIChpbnQpKGRpc2tfZnJlZV9zcGFjZShnZXRjd2QoKSkvKDEwMjQqMTAyNCkpIC4gIk1iIDwiO2k6MTU2O3M6NzY6ImEgaHJlZj0iPD9lY2hvICIkZmlzdGlrLnBocD9kaXppbj0kZGl6aW4vLi4vIj8+IiBzdHlsZT0idGV4dC1kZWNvcmF0aW9uOiBub24iO2k6MTU3O3M6Mzg6IlJvb3RTaGVsbCEnKTtzZWxmLmxvY2F0aW9uLmhyZWY9J2h0dHA6IjtpOjE1ODtzOjkwOiI8JT1SZXF1ZXN0LlNlcnZlclZhcmlhYmxlcygic2NyaXB0X25hbWUiKSU+P0ZvbGRlclBhdGg9PCU9U2VydmVyLlVSTFBhdGhFbmNvZGUoRm9sZGVyLkRyaXYiO2k6MTU5O3M6MTYwOiJwcmludCgoaXNfcmVhZGFibGUoJGYpICYmIGlzX3dyaXRlYWJsZSgkZikpPyI8dHI+PHRkPiIudygxKS5iKCJSIi53KDEpLmZvbnQoJ3JlZCcsJ1JXJywzKSkudygxKTooKChpc19yZWFkYWJsZSgkZikpPyI8dHI+PHRkPiIudygxKS5iKCJSIikudyg0KToiIikuKChpc193cml0YWJsIjtpOjE2MDtzOjE2MToiKCciJywnJnF1b3Q7JywkZm4pKS4nIjtkb2N1bWVudC5saXN0LnN1Ym1pdCgpO1wnPicuaHRtbHNwZWNpYWxjaGFycyhzdHJsZW4oJGZuKT5mb3JtYXQ/c3Vic3RyKCRmbiwwLGZvcm1hdC0zKS4nLi4uJzokZm4pLic8L2E+Jy5zdHJfcmVwZWF0KCcgJyxmb3JtYXQtc3RybGVuKCRmbikiO2k6MTYxO3M6MTE6InplaGlyaGFja2VyIjtpOjE2MjtzOjM5OiJKQCFWckAqJlJIUnd+Skx3Lkd8eGxobkxKfj8xLmJ3T2J4YlB8IVYiO2k6MTYzO3M6Mzk6IldTT3NldGNvb2tpZShtZDUoJF9TRVJWRVJbJ0hUVFBfSE9TVCddKSI7aToxNjQ7czoxNDE6IjwvdGQ+PHRkIGlkPWZhPlsgPGEgdGl0bGU9XCJIb21lOiAnIi5odG1sc3BlY2lhbGNoYXJzKHN0cl9yZXBsYWNlKCJcIiwgJHNlcCwgZ2V0Y3dkKCkpKS4iJy5cIiBpZD1mYSBocmVmPVwiamF2YXNjcmlwdDpWaWV3RGlyKCciLnJhd3VybGVuY29kZSI7aToxNjU7czoxNjoiQ29udGVudC1UeXBlOiAkXyI7aToxNjY7czo4NjoiPG5vYnI+PGI+JGNkaXIkY2ZpbGU8L2I+ICgiLiRmaWxlWyJzaXplX3N0ciJdLiIpPC9ub2JyPjwvdGQ+PC90cj48Zm9ybSBuYW1lPWN1cnJfZmlsZT4iO2k6MTY3O3M6NDg6Indzb0V4KCd0YXIgY2Z6diAnIC4gZXNjYXBlc2hlbGxhcmcoJF9QT1NUWydwMiddKSI7aToxNjg7czoxNDI6IjVqYjIwaUtXOXlJSE4wY21semRISW9KSEpsWm1WeVpYSXNJbUZ3YjNKMElpa2diM0lnYzNSeWFYTjBjaWdrY21WbVpYSmxjaXdpYm1sbmJXRWlLU0J2Y2lCemRISnBjM1J5S0NSeVpXWmxjbVZ5TENKM1pXSmhiSFJoSWlrZ2IzSWdjM1J5YVhOMGNpZ2siO2k6MTY5O3M6NzY6IkxTMGdSSFZ0Y0ROa0lHSjVJRkJwY25Wc2FXNHVVRWhRSUZkbFluTm9NMnhzSUhZeExqQWdZekJrWldRZ1lua2djakJrY2pFZ09rdz0iO2k6MTcwO3M6NjU6ImlmIChlcmVnKCdeW1s6Ymxhbms6XV0qY2RbWzpibGFuazpdXSsoW147XSspJCcsICRjb21tYW5kLCAkcmVncykpIjtpOjE3MTtzOjQ2OiJyb3VuZCgwKzk4MzAuNCs5ODMwLjQrOTgzMC40Kzk4MzAuNCs5ODMwLjQpKT09IjtpOjE3MjtzOjEyOiJQSFBTSEVMTC5QSFAiO2k6MTczO3M6MjA6IlNoZWxsIGJ5IE1hd2FyX0hpdGFtIjtpOjE3NDtzOjIyOiJwcml2YXRlIFNoZWxsIGJ5IG00cmNvIjtpOjE3NTtzOjEzOiJ3NGNrMW5nIHNoZWxsIjtpOjE3NjtzOjIxOiJGYVRhTGlzVGlDel9GeCBGeDI5U2giO2k6MTc3O3M6NDI6Ildvcmtlcl9HZXRSZXBseUNvZGUoJG9wRGF0YVsncmVjdkJ1ZmZlciddKSI7aToxNzg7czo0MDoiJGZpbGVwYXRoPUByZWFscGF0aCgkX1BPU1RbJ2ZpbGVwYXRoJ10pOyI7aToxNzk7czo4ODoiJHJlZGlyZWN0VVJMPSdodHRwOi8vJy4kclNpdGUuJF9TRVJWRVJbJ1JFUVVFU1RfVVJJJ107aWYoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddKSI7aToxODA7czoxNzoicmVuYW1lKCJ3c28ucGhwIiwiO2k6MTgxO3M6NTQ6IiRNZXNzYWdlU3ViamVjdCA9IGJhc2U2NF9kZWNvZGUoJF9QT1NUWyJtc2dzdWJqZWN0Il0pOyI7aToxODI7czo0NDoiY29weSgkX0ZJTEVTW3hdW3RtcF9uYW1lXSwkX0ZJTEVTW3hdW25hbWVdKSkiO2k6MTgzO3M6NTg6IlNFTEVDVCAxIEZST00gbXlzcWwudXNlciBXSEVSRSBjb25jYXQoYHVzZXJgLCAnQCcsIGBob3N0YCkiO2k6MTg0O3M6MjE6IiFAJF9DT09LSUVbJHNlc3NkdF9rXSI7aToxODU7czo0ODoiJGE9KHN1YnN0cih1cmxlbmNvZGUocHJpbnRfcihhcnJheSgpLDEpKSw1LDEpLmMpIjtpOjE4NjtzOjU2OiJ4aCAtcyAiL3Vzci9sb2NhbC9hcGFjaGUvc2Jpbi9odHRwZCAtRFNTTCIgLi9odHRwZCAtbSAkMSI7aToxODc7czoxODoicHdkID4gR2VuZXJhc2kuZGlyIjtpOjE4ODtzOjEyOiJCUlVURUZPUkNJTkciO2k6MTg5O3M6MzE6IkNhdXRhbSBmaXNpZXJlbGUgZGUgY29uZmlndXJhcmUiO2k6MTkwO3M6MzI6IiRrYT0nPD8vL0JSRSc7JGtha2E9JGthLidBQ0svLz8+IjtpOjE5MTtzOjg1OiIkc3Viaj11cmxkZWNvZGUoJF9HRVRbJ3N1J10pOyRib2R5PXVybGRlY29kZSgkX0dFVFsnYm8nXSk7JHNkcz11cmxkZWNvZGUoJF9HRVRbJ3NkJ10pIjtpOjE5MjtzOjM5OiIkX19fXz1AZ3ppbmZsYXRlKCRfX19fKSl7aWYoaXNzZXQoJF9QT1MiO2k6MTkzO3M6Mzc6InBhc3N0aHJ1KGdldGVudigiSFRUUF9BQ0NFUFRfTEFOR1VBR0UiO2k6MTk0O3M6ODoiQXNtb2RldXMiO2k6MTk1O3M6NTA6ImZvcig7JHBhZGRyPWFjY2VwdChDTElFTlQsIFNFUlZFUik7Y2xvc2UgQ0xJRU5UKSB7IjtpOjE5NjtzOjU5OiIkaXppbmxlcjI9c3Vic3RyKGJhc2VfY29udmVydChAZmlsZXBlcm1zKCRmbmFtZSksMTAsOCksLTQpOyI7aToxOTc7czo0MjoiJGJhY2tkb29yLT5jY29weSgkY2ZpY2hpZXIsJGNkZXN0aW5hdGlvbik7IjtpOjE5ODtzOjIzOiJ7JHtwYXNzdGhydSgkY21kKX19PGJyPiI7aToxOTk7czoyOToiJGFbaGl0c10nKTsgXHJcbiNlbmRxdWVyeVxyXG4iO2k6MjAwO3M6MjY6Im5jZnRwcHV0IC11ICRmdHBfdXNlcl9uYW1lIjtpOjIwMTtzOjM2OiJleGVjbCgiL2Jpbi9zaCIsInNoIiwiLWkiLChjaGFyKikwKTsiO2k6MjAyO3M6MzE6IjxIVE1MPjxIRUFEPjxUSVRMRT5jZ2ktc2hlbGwucHkiO2k6MjAzO3M6Mzg6InN5c3RlbSgidW5zZXQgSElTVEZJTEU7IHVuc2V0IFNBVkVISVNUIjtpOjIwNDtzOjIzOiIkbG9naW49QHBvc2l4X2dldHVpZCgpOyI7aToyMDU7czo2MDoiKGVyZWcoJ15bWzpibGFuazpdXSpjZFtbOmJsYW5rOl1dKiQnLCAkX1JFUVVFU1RbJ2NvbW1hbmQnXSkpIjtpOjIwNjtzOjI1OiIhJF9SRVFVRVNUWyJjOTlzaF9zdXJsIl0pIjtpOjIwNztzOjUzOiJQblZsa1dNNjMhQCNAJmRLeH5uTURXTX5Efy9Fc25+eH82REAjQCZQfn4sP25ZLFdQe1BvaiI7aToyMDg7czozNjoic2hlbGxfZXhlYygkX1BPU1RbJ2NtZCddIC4gIiAyPiYxIik7IjtpOjIwOTtzOjM1OiJpZighJHdob2FtaSkkd2hvYW1pPWV4ZWMoIndob2FtaSIpOyI7aToyMTA7czo2MToiUHlTeXN0ZW1TdGF0ZS5pbml0aWFsaXplKFN5c3RlbS5nZXRQcm9wZXJ0aWVzKCksIG51bGwsIGFyZ3YpOyI7aToyMTE7czozNjoiPCU9ZW52LnF1ZXJ5SGFzaHRhYmxlKCJ1c2VyLm5hbWUiKSU+IjtpOjIxMjtzOjgzOiJpZiAoZW1wdHkoJF9QT1NUWyd3c2VyJ10pKSB7JHdzZXIgPSAid2hvaXMucmlwZS5uZXQiO30gZWxzZSAkd3NlciA9ICRfUE9TVFsnd3NlciddOyI7aToyMTM7czo5MToiaWYgKG1vdmVfdXBsb2FkZWRfZmlsZSgkX0ZJTEVTWydmaWxhJ11bJ3RtcF9uYW1lJ10sICRjdXJkaXIuIi8iLiRfRklMRVNbJ2ZpbGEnXVsnbmFtZSddKSkgeyI7aToyMTQ7czoyMzoic2hlbGxfZXhlYygndW5hbWUgLWEnKTsiO2k6MjE1O3M6NDc6ImlmICghZGVmaW5lZCRwYXJhbXtjbWR9KXskcGFyYW17Y21kfT0ibHMgLWxhIn07IjtpOjIxNjtzOjYwOiJpZihnZXRfbWFnaWNfcXVvdGVzX2dwYygpKSRzaGVsbE91dD1zdHJpcHNsYXNoZXMoJHNoZWxsT3V0KTsiO2k6MjE3O3M6ODQ6IjxhIGhyZWY9JyRQSFBfU0VMRj9hY3Rpb249dmlld1NjaGVtYSZkYm5hbWU9JGRibmFtZSZ0YWJsZW5hbWU9JHRhYmxlbmFtZSc+U2NoZW1hPC9hPiI7aToyMTg7czo2NjoicGFzc3RocnUoICRiaW5kaXIuIm15c3FsZHVtcCAtLXVzZXI9JFVTRVJOQU1FIC0tcGFzc3dvcmQ9JFBBU1NXT1JEIjtpOjIxOTtzOjY2OiJteXNxbF9xdWVyeSgiQ1JFQVRFIFRBQkxFIGB4cGxvaXRgIChgeHBsb2l0YCBMT05HQkxPQiBOT1QgTlVMTCkiKTsiO2k6MjIwO3M6ODc6IiRyYTQ0ICA9IHJhbmQoMSw5OTk5OSk7JHNqOTggPSAic2gtJHJhNDQiOyRtbCA9ICIkc2Q5OCI7JGE1ID0gJF9TRVJWRVJbJ0hUVFBfUkVGRVJFUiddOyI7aToyMjE7czo1MjoiJF9GSUxFU1sncHJvYmUnXVsnc2l6ZSddLCAkX0ZJTEVTWydwcm9iZSddWyd0eXBlJ10pOyI7aToyMjI7czo3MToic3lzdGVtKCIkY21kIDE+IC90bXAvY21kdGVtcCAyPiYxOyBjYXQgL3RtcC9jbWR0ZW1wOyBybSAvdG1wL2NtZHRlbXAiKTsiO2k6MjIzO3M6Njk6In0gZWxzaWYgKCRzZXJ2YXJnID1+IC9eXDooLis/KVwhKC4rPylcQCguKz8pIFBSSVZNU0cgKC4rPykgXDooLispLykgeyI7aToyMjQ7czo2OToic2VuZChTT0NLNSwgJG1zZywgMCwgc29ja2FkZHJfaW4oJHBvcnRhLCAkaWFkZHIpKSBhbmQgJHBhY290ZXN7b30rKzs7IjtpOjIyNTtzOjE4OiIkZmUoIiRjbWQgIDI+JjEiKTsiO2k6MjI2O3M6Njg6IndoaWxlICgkcm93ID0gbXlzcWxfZmV0Y2hfYXJyYXkoJHJlc3VsdCxNWVNRTF9BU1NPQykpIHByaW50X3IoJHJvdyk7IjtpOjIyNztzOjUyOiJlbHNlaWYoQGlzX3dyaXRhYmxlKCRGTikgJiYgQGlzX2ZpbGUoJEZOKSkgJHRtcE91dE1GIjtpOjIyODtzOjcyOiJjb25uZWN0KFNPQ0tFVCwgc29ja2FkZHJfaW4oJEFSR1ZbMV0sIGluZXRfYXRvbigkQVJHVlswXSkpKSBvciBkaWUgcHJpbnQiO2k6MjI5O3M6ODk6ImlmKG1vdmVfdXBsb2FkZWRfZmlsZSgkX0ZJTEVTWyJmaWMiXVsidG1wX25hbWUiXSxnb29kX2xpbmsoIi4vIi4kX0ZJTEVTWyJmaWMiXVsibmFtZSJdKSkpIjtpOjIzMDtzOjg3OiJVTklPTiBTRUxFQ1QgJzAnICwgJzw/IHN5c3RlbShcJF9HRVRbY3BjXSk7ZXhpdDsgPz4nICwwICwwICwwICwwIElOVE8gT1VURklMRSAnJG91dGZpbGUiO2k6MjMxO3M6Njg6ImlmICghQGlzX2xpbmsoJGZpbGUpICYmICgkciA9IHJlYWxwYXRoKCRmaWxlKSkgIT0gRkFMU0UpICRmaWxlID0gJHI7IjtpOjIzMjtzOjI5OiJlY2hvICJGSUxFIFVQTE9BREVEIFRPICRkZXoiOyI7aToyMzM7czoyNDoiJGZ1bmN0aW9uKCRfUE9TVFsnY21kJ10pIjtpOjIzNDtzOjM4OiIkZmlsZW5hbWUgPSAkYmFja3Vwc3RyaW5nLiIkZmlsZW5hbWUiOyI7aToyMzU7czo0ODoiaWYoJyc9PSgkZGY9QGluaV9nZXQoJ2Rpc2FibGVfZnVuY3Rpb25zJykpKXtlY2hvIjtpOjIzNjtzOjQ2OiI8JSBGb3IgRWFjaCBWYXJzIEluIFJlcXVlc3QuU2VydmVyVmFyaWFibGVzICU+IjtpOjIzNztzOjMzOiJpZiAoJGZ1bmNhcmcgPX4gL15wb3J0c2NhbiAoLiopLykiO2k6MjM4O3M6NTU6IiR1cGxvYWRmaWxlID0gJHJwYXRoLiIvIiAuICRfRklMRVNbJ3VzZXJmaWxlJ11bJ25hbWUnXTsiO2k6MjM5O3M6MjY6IiRjbWQgPSAoJF9SRVFVRVNUWydjbWQnXSk7IjtpOjI0MDtzOjM4OiJpZigkY21kICE9ICIiKSBwcmludCBTaGVsbF9FeGVjKCRjbWQpOyI7aToyNDE7czoyOToiaWYgKGlzX2ZpbGUoIi90bXAvJGVraW5jaSIpKXsiO2k6MjQyO3M6Njk6Il9fYWxsX18gPSBbIlNNVFBTZXJ2ZXIiLCJEZWJ1Z2dpbmdTZXJ2ZXIiLCJQdXJlUHJveHkiLCJNYWlsbWFuUHJveHkiXSI7aToyNDM7czo1OToiZ2xvYmFsICRteXNxbEhhbmRsZSwgJGRibmFtZSwgJHRhYmxlbmFtZSwgJG9sZF9uYW1lLCAkbmFtZSwiO2k6MjQ0O3M6Mjc6IjI+JjEgMT4mMiIgOiAiIDE+JjEgMj4mMSIpOyI7aToyNDU7czo1MjoibWFwIHsgcmVhZF9zaGVsbCgkXykgfSAoJHNlbF9zaGVsbC0+Y2FuX3JlYWQoMC4wMSkpOyI7aToyNDY7czoyMjoiZndyaXRlICgkZnAsICIkeWF6aSIpOyI7aToyNDc7czo1MToiU2VuZCB0aGlzIGZpbGU6IDxJTlBVVCBOQU1FPSJ1c2VyZmlsZSIgVFlQRT0iZmlsZSI+IjtpOjI0ODtzOjQyOiIkZGJfZCA9IEBteXNxbF9zZWxlY3RfZGIoJGRhdGFiYXNlLCRjb24xKTsiO2k6MjQ5O3M6Njc6ImZvciAoJHZhbHVlKSB7IHMvJi8mYW1wOy9nOyBzLzwvJmx0Oy9nOyBzLz4vJmd0Oy9nOyBzLyIvJnF1b3Q7L2c7IH0iO2k6MjUwO3M6NzQ6ImNvcHkoJF9GSUxFU1sndXBrayddWyd0bXBfbmFtZSddLCJray8iLmJhc2VuYW1lKCRfRklMRVNbJ3Vwa2snXVsnbmFtZSddKSk7IjtpOjI1MTtzOjg2OiJmdW5jdGlvbiBnb29nbGVfYm90KCkgeyRzVXNlckFnZW50ID0gc3RydG9sb3dlcigkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10pO2lmKCEoc3RycCI7aToyNTI7czo3NToiY3JlYXRlX2Z1bmN0aW9uKCImJCIuImZ1bmN0aW9uIiwiJCIuImZ1bmN0aW9uID0gY2hyKG9yZCgkIi4iZnVuY3Rpb24pLTMpOyIpIjtpOjI1MztzOjQ2OiJsb25nIGludDp0KDAsMyk9cigwLDMpOy0yMTQ3NDgzNjQ4OzIxNDc0ODM2NDc7IjtpOjI1NDtzOjQ2OiI/dXJsPScuJF9TRVJWRVJbJ0hUVFBfSE9TVCddKS51bmxpbmsoUk9PVF9ESVIuIjtpOjI1NTtzOjM2OiJjYXQgJHtibGtsb2dbMl19IHwgZ3JlcCAicm9vdDp4OjA6MCIiO2k6MjU2O3M6OTc6IkBwYXRoMT0oJ2FkbWluLycsJ2FkbWluaXN0cmF0b3IvJywnbW9kZXJhdG9yLycsJ3dlYmFkbWluLycsJ2FkbWluYXJlYS8nLCdiYi1hZG1pbi8nLCdhZG1pbkxvZ2luLyciO2k6MjU3O3M6ODc6IiJhZG1pbjEucGhwIiwgImFkbWluMS5odG1sIiwgImFkbWluMi5waHAiLCAiYWRtaW4yLmh0bWwiLCAieW9uZXRpbS5waHAiLCAieW9uZXRpbS5odG1sIiI7aToyNTg7czo2ODoiUE9TVCB7JHBhdGh9eyRjb25uZWN0b3J9P0NvbW1hbmQ9RmlsZVVwbG9hZCZUeXBlPUZpbGUmQ3VycmVudEZvbGRlcj0iO2k6MjU5O3M6MzA6IkBhc3NlcnQoJF9SRVFVRVNUWydQSFBTRVNTSUQnXSI7aToyNjA7czo2MToiJHByb2Q9InN5Ii4icyIuInRlbSI7JGlkPSRwcm9kKCRfUkVRVUVTVFsncHJvZHVjdCddKTskeydpZCd9OyI7aToyNjE7czoxNToicGhwICIuJHdzb19wYXRoIjtpOjI2MjtzOjc3OiIkRmNobW9kLCRGZGF0YSwkT3B0aW9ucywkQWN0aW9uLCRoZGRhbGwsJGhkZGZyZWUsJGhkZHByb2MsJHVuYW1lLCRpZGQpOnNoYXJlZCI7aToyNjM7czo1MToic2VydmVyLjwvcD5cclxuPC9ib2R5PjwvaHRtbD4iO2V4aXQ7fWlmKHByZWdfbWF0Y2goIjtpOjI2NDtzOjk1OiIkZmlsZSA9ICRfRklMRVNbImZpbGVuYW1lIl1bIm5hbWUiXTsgZWNobyAiPGEgaHJlZj1cIiRmaWxlXCI+JGZpbGU8L2E+Ijt9IGVsc2Uge2VjaG8oImVtcHR5Iik7fSI7aToyNjU7czo2MDoiRlNfY2hrX2Z1bmNfbGliYz0oICQocmVhZGVsZiAtcyAkRlNfbGliYyB8IGdyZXAgX2Noa0BAIHwgYXdrIjtpOjI2NjtzOjQwOiJmaW5kIC8gLW5hbWUgLnNzaCA+ICRkaXIvc3Noa2V5cy9zc2hrZXlzIjtpOjI2NztzOjMzOiJyZS5maW5kYWxsKGRpcnQrJyguKiknLHByb2dubSlbMF0iO2k6MjY4O3M6NjA6Im91dHN0ciArPSBzdHJpbmcuRm9ybWF0KCI8YSBocmVmPSc/ZmRpcj17MH0nPnsxfS88L2E+Jm5ic3A7IiI7aToyNjk7czo4MzoiPCU9UmVxdWVzdC5TZXJ2ZXJ2YXJpYWJsZXMoIlNDUklQVF9OQU1FIiklPj90eHRwYXRoPTwlPVJlcXVlc3QuUXVlcnlTdHJpbmcoInR4dHBhdGgiO2k6MjcwO3M6NzE6IlJlc3BvbnNlLldyaXRlKFNlcnZlci5IdG1sRW5jb2RlKHRoaXMuRXhlY3V0ZUNvbW1hbmQodHh0Q29tbWFuZC5UZXh0KSkpIjtpOjI3MTtzOjExMToibmV3IEZpbGVTdHJlYW0oUGF0aC5Db21iaW5lKGZpbGVJbmZvLkRpcmVjdG9yeU5hbWUsIFBhdGguR2V0RmlsZU5hbWUoaHR0cFBvc3RlZEZpbGUuRmlsZU5hbWUpKSwgRmlsZU1vZGUuQ3JlYXRlIjtpOjI3MjtzOjkwOiJSZXNwb25zZS5Xcml0ZSgiPGJyPiggKSA8YSBocmVmPT90eXBlPTEmZmlsZT0iICYgc2VydmVyLlVSTGVuY29kZShpdGVtLnBhdGgpICYgIlw+IiAmIGl0ZW0iO2k6MjczO3M6MTA0OiJzcWxDb21tYW5kLlBhcmFtZXRlcnMuQWRkKCgoVGFibGVDZWxsKWRhdGFHcmlkSXRlbS5Db250cm9sc1swXSkuVGV4dCwgU3FsRGJUeXBlLkRlY2ltYWwpLlZhbHVlID0gZGVjaW1hbCI7aToyNzQ7czo2NDoiPCU9ICJcIiAmIG9TY3JpcHROZXQuQ29tcHV0ZXJOYW1lICYgIlwiICYgb1NjcmlwdE5ldC5Vc2VyTmFtZSAlPiI7aToyNzU7czo1MDoiY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1VSTCwgImh0dHA6Ly8kaG9zdDoyMDgyIikiO2k6Mjc2O3M6NTg6IkhKM0hqdXRja29SZnBYZjlBMXpRTzJBd0RSclJleTl1R3ZUZWV6NzlxQWFvMWEwcmd1ZGtaa1I4UmEiO2k6Mjc3O3M6MzE6IiRpbmlbJ3VzZXJzJ10gPSBhcnJheSgncm9vdCcgPT4iO2k6Mjc4O3M6MzE6ImZ3cml0ZSgkZnAsIlx4RUZceEJCXHhCRiIuJGJvZHkiO2k6Mjc5O3M6MTg6InByb2Nfb3BlbignSUhTdGVhbSI7aToyODA7czoyNDoiJGJhc2xpaz0kX1BPU1RbJ2Jhc2xpayddIjtpOjI4MTtzOjMwOiJmcmVhZCgkZnAsIGZpbGVzaXplKCRmaWNoZXJvKSkiO2k6MjgyO3M6Mzk6IkkvZ2NaL3ZYMEExMEREUkRnN0V6ay9kKzMrOHF2cXFTMUswK0FYWSI7aToyODM7czoxNjoieyRfUE9TVFsncm9vdCddfSI7aToyODQ7czoyOToifWVsc2VpZigkX0dFVFsncGFnZSddPT0nZGRvcyciO2k6Mjg1O3M6MTQ6IlRoZSBEYXJrIFJhdmVyIjtpOjI4NjtzOjM5OiIkdmFsdWUgPX4gcy8lKC4uKS9wYWNrKCdjJyxoZXgoJDEpKS9lZzsiO2k6Mjg3O3M6MTE6Ind3dy50MHMub3JnIjtpOjI4ODtzOjMwOiJ1bmxlc3Mob3BlbihQRkQsJGdfdXBsb2FkX2RiKSkiO2k6Mjg5O3M6MTI6ImF6ODhwaXgwMHE5OCI7aToyOTA7czoxMToic2ggZ28gJDEuJHgiO2k6MjkxO3M6MjY6InN5c3RlbSgicGhwIC1mIHhwbCAkaG9zdCIpIjtpOjI5MjtzOjEzOiJleHBsb2l0Y29va2llIjtpOjI5MztzOjIxOiI4MCAtYiAkMSAtaSBldGgwIC1zIDgiO2k6Mjk0O3M6MjU6IkhUVFAgZmxvb2QgY29tcGxldGUgYWZ0ZXIiO2k6Mjk1O3M6MTU6Ik5JR0dFUlMuTklHR0VSUyI7aToyOTY7czo0NzoiaWYoaXNzZXQoJF9HRVRbJ2hvc3QnXSkmJmlzc2V0KCRfR0VUWyd0aW1lJ10pKXsiO2k6Mjk3O3M6ODM6InN1YnByb2Nlc3MuUG9wZW4oY21kLCBzaGVsbCA9IFRydWUsIHN0ZG91dD1zdWJwcm9jZXNzLlBJUEUsIHN0ZGVycj1zdWJwcm9jZXNzLlNURE9VIjtpOjI5ODtzOjY5OiJkZWYgZGFlbW9uKHN0ZGluPScvZGV2L251bGwnLCBzdGRvdXQ9Jy9kZXYvbnVsbCcsIHN0ZGVycj0nL2Rldi9udWxsJykiO2k6Mjk5O3M6Njc6InByaW50KCJbIV0gSG9zdDogIiArIGhvc3RuYW1lICsgIiBtaWdodCBiZSBkb3duIVxuWyFdIFJlc3BvbnNlIENvZGUiO2k6MzAwO3M6NDI6ImNvbm5lY3Rpb24uc2VuZCgic2hlbGwgIitzdHIob3MuZ2V0Y3dkKCkpKyI7aTozMDE7czo1MDoib3Muc3lzdGVtKCdlY2hvIGFsaWFzIGxzPSIubHMuYmFzaCIgPj4gfi8uYmFzaHJjJykiO2k6MzAyO3M6MzI6InJ1bGVfcmVxID0gcmF3X2lucHV0KCJTb3VyY2VGaXJlIjtpOjMwMztzOjU3OiJhcmdwYXJzZS5Bcmd1bWVudFBhcnNlcihkZXNjcmlwdGlvbj1oZWxwLCBwcm9nPSJzY3R1bm5lbCIiO2k6MzA0O3M6NTc6InN1YnByb2Nlc3MuUG9wZW4oJyVzZ2RiIC1wICVkIC1iYXRjaCAlcycgJSAoZ2RiX3ByZWZpeCwgcCI7aTozMDU7czo1OToiJGZyYW1ld29yay5wbHVnaW5zLmxvYWQoIiN7cnBjdHlwZS5kb3duY2FzZX1ycGMiLCBvcHRzKS5ydW4iO2k6MzA2O3M6Mjg6ImlmIHNlbGYuaGFzaF90eXBlID09ICdwd2R1bXAiO2k6MzA3O3M6MTc6Iml0c29rbm9wcm9ibGVtYnJvIjtpOjMwODtzOjQ1OiJhZGRfZmlsdGVyKCd0aGVfY29udGVudCcsICdfYmxvZ2luZm8nLCAxMDAwMSkiO2k6MzA5O3M6OToiPHN0ZGxpYi5oIjtpOjMxMDtzOjU5OiJlY2hvIHkgOyBzbGVlcCAxIDsgfSB8IHsgd2hpbGUgcmVhZCA7IGRvIGVjaG8geiRSRVBMWTsgZG9uZSI7aTozMTE7czoxMToiVk9CUkEgR0FOR08iO2k6MzEyO3M6NzY6ImludDMyKCgoJHogPj4gNSAmIDB4MDdmZmZmZmYpIF4gJHkgPDwgMikgKyAoKCR5ID4+IDMgJiAweDFmZmZmZmZmKSBeICR6IDw8IDQiO2k6MzEzO3M6Njk6IkBjb3B5KCRfRklMRVNbZmlsZU1hc3NdW3RtcF9uYW1lXSwkX1BPU1RbcGF0aF0uJF9GSUxFU1tmaWxlTWFzc11bbmFtZSI7aTozMTQ7czo0NjoiZmluZF9kaXJzKCRncmFuZHBhcmVudF9kaXIsICRsZXZlbCwgMSwgJGRpcnMpOyI7aTozMTU7czoyODoiQHNldGNvb2tpZSgiaGl0IiwgMSwgdGltZSgpKyI7aTozMTY7czo1OiJlLyouLyI7aTozMTc7czozNzoiSkhacGMybDBZMjkxYm5RZ1BTQWtTRlJVVUY5RFQwOUxTVVZmViI7aTozMTg7czozNToiMGQwYTBkMGE2NzZjNmY2MjYxNmMyMDI0NmQ3OTVmNzM2ZDciO2k6MzE5O3M6MTk6ImZvcGVuKCcvZXRjL3Bhc3N3ZCciO2k6MzIwO3M6NzY6IiR0c3UyW3JhbmQoMCxjb3VudCgkdHN1MikgLSAxKV0uJHRzdTFbcmFuZCgwLGNvdW50KCR0c3UxKSAtIDEpXS4kdHN1MltyYW5kKDAiO2k6MzIxO3M6MzM6Ii91c3IvbG9jYWwvYXBhY2hlL2Jpbi9odHRwZCAtRFNTTCI7aTozMjI7czoyMDoic2V0IHByb3RlY3QtdGVsbmV0IDAiO2k6MzIzO3M6Mjc6ImF5dSBwcjEgcHIyIHByMyBwcjQgcHI1IHByNiI7aTozMjQ7czozMDoiYmluZCBmaWx0IC0gIlwwMDFBQ1RJT04gKlwwMDEiIjtpOjMyNTtzOjUwOiJyZWdzdWIgLWFsbCAtLSAsIFtzdHJpbmcgdG9sb3dlciAkb3duZXJdICIiIG93bmVycyI7aTozMjY7czozNToia2lsbCAtQ0hMRCBcJGJvdHBpZCA+L2Rldi9udWxsIDI+JjEiO2k6MzI3O3M6MTA6ImJpbmQgZGNjIC0iO2k6MzI4O3M6MjQ6InI0YVRjLmRQbnRFL2Z6dFNGMWJIM1JIMCI7aTozMjk7czoxMzoicHJpdm1zZyAkY2hhbiI7aTozMzA7czoyMjoiYmluZCBqb2luIC0gKiBnb3Bfam9pbiI7aTozMzE7czo0Mzoic2V0IGdvb2dsZShkYXRhKSBbaHR0cDo6ZGF0YSAkZ29vZ2xlKHBhZ2UpXSI7aTozMzI7czoyNjoicHJvYyBodHRwOjpDb25uZWN0IHt0b2tlbn0iO2k6MzMzO3M6MTM6InByaXZtc2cgJG5pY2siO2k6MzM0O3M6MTE6InB1dGJvdCAkYm90IjtpOjMzNTtzOjEyOiJ1bmJpbmQgUkFXIC0iO2k6MzM2O3M6Mjk6Ii0tRENDRElSIFtsaW5kZXggJFVzZXIoJGkpIDJdIjtpOjMzNztzOjEwOiJDeWJlc3RlcjkwIjtpOjMzODtzOjQxOiJmaWxlX2dldF9jb250ZW50cyh0cmltKCRmWyRfR0VUWydpZCddXSkpOyI7aTozMzk7czoyMToidW5saW5rKCR3cml0YWJsZV9kaXJzIjtpOjM0MDtzOjI3OiJiYXNlNjRfZGVjb2RlKCRjb2RlX3NjcmlwdCkiO2k6MzQxO3M6MjE6Imx1Y2lmZmVyQGx1Y2lmZmVyLm9yZyI7aTozNDI7czo0ODoiJHRoaXMtPkYtPkdldENvbnRyb2xsZXIoJF9TRVJWRVJbJ1JFUVVFU1RfVVJJJ10pIjtpOjM0MztzOjQ3OiIkdGltZV9zdGFydGVkLiRzZWN1cmVfc2Vzc2lvbl91c2VyLnNlc3Npb25faWQoKSI7aTozNDQ7czo3NDoiJHBhcmFtIHggJG4uc3Vic3RyICgkcGFyYW0sIGxlbmd0aCgkcGFyYW0pIC0gbGVuZ3RoKCRjb2RlKSVsZW5ndGgoJHBhcmFtKSkiO2k6MzQ1O3M6MzY6ImZ3cml0ZSgkZixnZXRfZG93bmxvYWQoJF9HRVRbJ3VybCddKSI7aTozNDY7czo2NToiaHR0cDovLycuJF9TRVJWRVJbJ0hUVFBfSE9TVCddLnVybGRlY29kZSgkX1NFUlZFUlsnUkVRVUVTVF9VUkknXSkiO2k6MzQ3O3M6ODA6IndwX3Bvc3RzIFdIRVJFIHBvc3RfdHlwZSA9ICdwb3N0JyBBTkQgcG9zdF9zdGF0dXMgPSAncHVibGlzaCcgT1JERVIgQlkgYElEYCBERVNDIjtpOjM0ODtzOjM3OiIkdXJsID0gJHVybHNbcmFuZCgwLCBjb3VudCgkdXJscyktMSldIjtpOjM0OTtzOjQ3OiJwcmVnX21hdGNoKCcvKD88PVJld3JpdGVSdWxlKS4qKD89XFtMXCxSXD0zMDJcXSI7aTozNTA7czo0NToicHJlZ19tYXRjaCgnIU1JRFB8V0FQfFdpbmRvd3MuQ0V8UFBDfFNlcmllczYwIjtpOjM1MTtzOjYwOiJSMGxHT0RsaEV3QVFBTE1BQUFBQUFQLy8vNXljQU03T1kvLy9uUC8venYvT25QZjM5Ly8vL3dBQUFBQUEiO2k6MzUyO3M6NjU6InN0cl9yb3QxMygkYmFzZWFbKCRkaW1lbnNpb24qJGRpbWVuc2lvbi0xKSAtICgkaSokZGltZW5zaW9uKyRqKV0pIjtpOjM1MztzOjc1OiJpZihlbXB0eSgkX0dFVFsnemlwJ10pIGFuZCBlbXB0eSgkX0dFVFsnZG93bmxvYWQnXSkgJiBlbXB0eSgkX0dFVFsnaW1nJ10pKXsiO2k6MzU0O3M6MTY6Ik1hZGUgYnkgRGVsb3JlYW4iO2k6MzU1O3M6NDY6Im92ZXJmbG93LXk6c2Nyb2xsO1wiPiIuJGxpbmtzLiRodG1sX21mWydib2R5J10iO2k6MzU2O3M6NDM6ImZ1bmN0aW9uIHVybEdldENvbnRlbnRzKCR1cmwsICR0aW1lb3V0ID0gNSkiO2k6MzU3O3M6NjoiZDNsZXRlIjtpOjM1ODtzOjE1OiJsZXRha3Nla2FyYW5nKCkiO2k6MzU5O3M6ODoiWUVOSTNFUkkiO2k6MzYwO3M6MjE6IiRPT08wMDAwMDA9dXJsZGVjb2RlKCI7aTozNjE7czoyMDoiLUkvdXNyL2xvY2FsL2JhbmRtaW4iO2k6MzYyO3M6Mzc6ImZ3cml0ZSgkZnBzZXR2LCBnZXRlbnYoIkhUVFBfQ09PS0lFIikiO2k6MzYzO3M6MjU6Imlzc2V0KCRfUE9TVFsnZXhlY2dhdGUnXSkiO2k6MzY0O3M6MTU6IldlYmNvbW1hbmRlciBhdCI7aTozNjU7czoxNDoiPT0gImJpbmRzaGVsbCIiO2k6MzY2O3M6ODoiUGFzaGtlbGEiO2k6MzY3O3M6MjU6ImNyZWF0ZUZpbGVzRm9ySW5wdXRPdXRwdXQiO2k6MzY4O3M6NjoiTTRsbDNyIjtpOjM2OTtzOjIwOiJfX1ZJRVdTVEFURUVOQ1JZUFRFRCI7aTozNzA7czo3OiJPb05fQm95IjtpOjM3MTtzOjEzOiJSZWFMX1B1TmlTaEVyIjtpOjM3MjtzOjg6ImRhcmttaW56IjtpOjM3MztzOjU6IlplZDB4IjtpOjM3NDtzOjQwOiJhYmFjaG98YWJpemRpcmVjdG9yeXxhYm91dHxhY29vbnxhbGV4YW5hIjtpOjM3NTtzOjM2OiJwcGN8bWlkcHx3aW5kb3dzIGNlfG10a3xqMm1lfHN5bWJpYW4iO2k6Mzc2O3M6NDc6IkBjaHIoKCRoWyRlWyRvXV08PDQpKygkaFskZVsrKyRvXV0pKTt9fWV2YWwoJGQpIjtpOjM3NztzOjExOiIkc2gzbGxDb2xvciI7aTozNzg7czoxMDoiUHVua2VyMkJvdCI7aTozNzk7czoxODoiPD9waHAgZWNobyAiIyEhIyI7IjtpOjM4MDtzOjc1OiIkaW09c3Vic3RyKCRpbSwwLCRpKS5zdWJzdHIoJGltLCRpMisxLCRpNC0oJGkyKzEpKS5zdWJzdHIoJGltLCRpNCsxMixzdHJsZW4iO2k6MzgxO3M6NTU6IigkaW5kYXRhLCRiNjQ9MSl7aWYoJGI2ND09MSl7JGNkPWJhc2U2NF9kZWNvZGUoJGluZGF0YSkiO2k6MzgyO3M6MTc6IigkX1BPU1RbImRpciJdKSk7IjtpOjM4MztzOjE3OiJIYWNrZWQgQnkgRW5ETGVTcyI7aTozODQ7czoxMDoiYW5kZXh8b29nbCI7aTozODU7czoxMDoibmRyb2l8aHRjXyI7aTozODY7czoxMDoiPGRvdD5JcklzVCI7aTozODc7czoyMToiN1AxdGQrTldsaWFJL2hXa1o0Vlg5IjtpOjM4ODtzOjE1OiJOaW5qYVZpcnVzIEhlcmUiO2k6Mzg5O3M6MzI6IiRpbT1zdWJzdHIoJHR4LCRwKzIsJHAyLSgkcCsyKSk7IjtpOjM5MDtzOjY6IjN4cDFyMyI7aTozOTE7czoyMDoiJG1kNT1tZDUoIiRyYW5kb20iKTsiO2k6MzkyO3M6Mjg6Im9UYXQ4RDNEc0U4JyZ+aFUwNkNDSDU7JGdZU3EiO2k6MzkzO3M6MTI6IkdJRjg5QTs8P3BocCI7aTozOTQ7czoxNToiQ3JlYXRlZCBCeSBFTU1BIjtpOjM5NTtzOjM0OiJQYXNzd29yZDo8cz4iLiRfUE9TVFs8cT5wYXNzd2Q8cT5dIjtpOjM5NjtzOjE1OiJOZXRAZGRyZXNzIE1haWwiO2k6Mzk3O3M6MjQ6IiRpc2V2YWxmdW5jdGlvbmF2YWlsYWJsZSI7aTozOTg7czoxMToiQmFieV9EcmFrb24iO2k6Mzk5O3M6MzA6ImZ3cml0ZShmb3BlbihkaXJuYW1lKF9fRklMRV9fKSI7aTo0MDA7czoxMzoiXV0pKTt9fWV2YWwoJCI7aTo0MDE7czoyNzoiZXJlZ19yZXBsYWNlKDxxPiZlbWFpbCY8cT4sIjtpOjQwMjtzOjE5OiIpOyAkaSsrKSRyZXQuPWNocigkIjtpOjQwMztzOjU3OiIkcGFyYW0ybWFzay4iKVw9W1w8cXE+XCJdKC4qPykoPz1bXDxxcT5cIl0gKVtcPHFxPlwiXS9zaWUiO2k6NDA0O3M6OToiLy9yYXN0YS8vIjtpOjQwNTtzOjIwOiI8IS0tQ09PS0lFIFVQREFURS0tPiI7aTo0MDY7czoxMzoicHJvZmV4b3IuaGVsbCI7aTo0MDc7czoxMzoiTWFnZWxhbmdDeWJlciI7aTo0MDg7czo4OiJaT0JVR1RFTCI7aTo0MDk7czoxMzoiRmFrZVNlbmRlciBieSI7aTo0MTA7czoyMToiZGF0YTp0ZXh0L2h0bWw7YmFzZTY0IjtpOjQxMTtzOjg6IlNfXUBfXlVeIjtpOjQxMjtzOjEzOiJAJF9QT1NUWyhjaHIoIjtpOjQxMztzOjEyOiJaZXJvRGF5RXhpbGUiO2k6NDE0O3M6MTI6IlN1bHRhbkhhaWthbCI7aTo0MTU7czoxMToiQ291cGRlZ3JhY2UiO2k6NDE2O3M6OToiYXJ0aWNrbGVAIjtpOjQxNztzOjE1OiJnbml0cm9wZXJfcm9ycmUiO2k6NDE4O3M6MjM6ImN1dHRlclthdF1yZWFsLnhha2VwLnJ1IjtpOjQxOTtzOjI5OiJpZigkd3BfX3dwPUBnemluZmxhdGUoJHdwX193cCI7aTo0MjA7czo2OiJyMDBuaXgiO2k6NDIxO3M6MjE6IiRmdWxsX3BhdGhfdG9fZG9vcndheSI7aTo0MjI7czozMDoiPGI+RG9uZSA9PT4gJHVzZXJmaWxlX25hbWU8L2I+IjtpOjQyMztzOjEyOiI+RGFyayBTaGVsbDwiO2k6NDI0O3M6MTU6Ii8uLi8qL2luZGV4LnBocCI7aTo0MjU7czozMjoiaWYoaXNfdXBsb2FkZWRfZmlsZS8qOyovKCRfRklMRVMiO2k6NDI2O3M6MjM6ImV4ZWMoJGNvbW1hbmQsICRvdXRwdXQpIjtpOjQyNztzOjIwOiJAaW5jbHVkZV9vbmNlKCcvdG1wLyI7aTo0Mjg7czo4MToidHJpbSgnaHR0cDovLycuJHNjLjxxcT4/Q29tbWFuZD1HZXRGb2xkZXJzQW5kRmlsZXMmVHlwZT1GaWxlJkN1cnJlbnRGb2xkZXI9JTJGJTAwIjtpOjQyOTtzOjU5OiIkc2NyaXB0X2ZpbmQgPSBzdHJfcmVwbGFjZSgibG9hZGVyIiwgImZpbmQiLCAkc2NyaXB0X2xvYWRlciI7aTo0MzA7czoxODoiPHRpdGxlPi4vSGFja2VkIEJ5IjtpOjQzMTtzOjg6IkJ5IFRhM2VzIjtpOjQzMjtzOjE0OiIkbmV3X21vdXN0YWNoZSI7aTo0MzM7czoxMzoiL1dhcENsaWNrLnBocCI7aTo0MzQ7czoxMzoicHJvZmV4b3IuaGVsbCI7aTo0MzU7czoxMjoiZXZhbCgkbW9vbik7IjtpOjQzNjtzOjM5OiJzdHJfcm90MTMoZ3ppbmZsYXRlKGJhc2U2NF9kZWNvZGUoJGNvZGUiO2k6NDM3O3M6MTY6Ijt9fWV4aXQ7Jyk7JHsiXHgiO2k6NDM4O3M6MTE6Ilx4MmVwaHAiOy8qIjt9"));
$gX_DBShe = unserialize(base64_decode("YTo2Mjp7aTowO3M6NzoiZGVmYWNlciI7aToxO3M6MjQ6IllvdSBjYW4gcHV0IGEgbWQ1IHN0cmluZyI7aToyO3M6ODoicGhwc2hlbGwiO2k6MztzOjYyOiI8ZGl2IGNsYXNzPSJibG9jayBidHlwZTEiPjxkaXYgY2xhc3M9ImR0b3AiPjxkaXYgY2xhc3M9ImRidG0iPiI7aTo0O3M6ODoiYzk5c2hlbGwiO2k6NTtzOjg6InI1N3NoZWxsIjtpOjY7czo3OiJOVERhZGR5IjtpOjc7czo4OiJjaWhzaGVsbCI7aTo4O3M6NzoiRnhjOTlzaCI7aTo5O3M6MTI6IldlYiBTaGVsbCBieSI7aToxMDtzOjExOiJkZXZpbHpTaGVsbCI7aToxMTtzOjI1OiJIYWNrZWQgYnkgQWxmYWJldG9WaXJ0dWFsIjtpOjEyO3M6ODoiTjN0c2hlbGwiO2k6MTM7czoxMToiU3Rvcm03U2hlbGwiO2k6MTQ7czoxMToiTG9jdXM3U2hlbGwiO2k6MTU7czoxMjoicjU3c2hlbGwucGhwIjtpOjE2O3M6OToiYW50aXNoZWxsIjtpOjE3O3M6OToicm9vdHNoZWxsIjtpOjE4O3M6MTE6Im15c2hlbGxleGVjIjtpOjE5O3M6ODoiU2hlbGwgT2siO2k6MjA7czoxNDoiZXhlYygicm0gLXIgLWYiO2k6MjE7czoxODoiTmUgdWRhbG9zIHphZ3J1eml0IjtpOjIyO3M6NTE6IiRtZXNzYWdlID0gZXJlZ19yZXBsYWNlKCIlNUMlMjIiLCAiJTIyIiwgJG1lc3NhZ2UpOyI7aToyMztzOjE5OiJwcmludCAiU3BhbWVkJz48YnI+IjtpOjI0O3M6NDA6InNldGNvb2tpZSggIm15c3FsX3dlYl9hZG1pbl91c2VybmFtZSIgKTsiO2k6MjU7czozNzoiZWxzZWlmKGZ1bmN0aW9uX2V4aXN0cygic2hlbGxfZXhlYyIpKSI7aToyNjtzOjU5OiJpZiAoaXNfY2FsbGFibGUoImV4ZWMiKSBhbmQgIWluX2FycmF5KCJleGVjIiwkZGlzYWJsZWZ1bmMpKSI7aToyNztzOjM0OiJpZiAoKCRwZXJtcyAmIDB4QzAwMCkgPT0gMHhDMDAwKSB7IjtpOjI4O3M6MTA6ImRpciAvT0cgL1giO2k6Mjk7czozNjoiaW5jbHVkZSgkX1NFUlZFUlsnSFRUUF9VU0VSX0FHRU5UJ10pIjtpOjMwO3M6NzoiYnIwd3MzciI7aTozMTtzOjQ5OiInaHR0cGQuY29uZicsJ3Zob3N0cy5jb25mJywnY2ZnLnBocCcsJ2NvbmZpZy5waHAnIjtpOjMyO3M6MzQ6Ii9wcm9jL3N5cy9rZXJuZWwveWFtYS9wdHJhY2Vfc2NvcGUiO2k6MzM7czoyMzoiZXZhbChmaWxlX2dldF9jb250ZW50cygiO2k6MzQ7czoxODoiaXNfd3JpdGFibGUoIi92YXIvIjtpOjM1O3M6MTQ6IiRHTE9CQUxTWydfX19fIjtpOjM2O3M6NTU6ImlzX2NhbGxhYmxlKCdleGVjJykgYW5kICFpbl9hcnJheSgnZXhlYycsICRkaXNhYmxlZnVuY3MiO2k6Mzc7czo2OiJrMGQuY2MiO2k6Mzg7czoyNjoiZ21haWwtc210cC1pbi5sLmdvb2dsZS5jb20iO2k6Mzk7czo3OiJ3ZWJyMDB0IjtpOjQwO3M6MTE6IkRldmlsSGFja2VyIjtpOjQxO3M6NzoiRGVmYWNlciI7aTo0MjtzOjExOiJbIFBocHJveHkgXSI7aTo0MztzOjg6Iltjb2RlcnpdIjtpOjQ0O3M6MzI6IjwhLS0jZXhlYyBjbWQ9IiRIVFRQX0FDQ0VQVCIgLS0+IjtpOjQ1O3M6MTI6Il1bcm91bmQoMCldKCI7aTo0NjtzOjExOiJTaW1BdHRhY2tlciI7aTo0NztzOjE1OiJEYXJrQ3Jld0ZyaWVuZHMiO2k6NDg7czo3OiJrMmxsMzNkIjtpOjQ5O3M6NzoiS2tLMTMzNyI7aTo1MDtzOjE1OiJIQUNLRUQgQlkgU1RPUk0iO2k6NTE7czoxNDoiTWV4aWNhbkhhY2tlcnMiO2k6NTI7czoxNToiTXIuU2hpbmNoYW5YMTk2IjtpOjUzO3M6OToiRGVpZGFyYX5YIjtpOjU0O3M6MTA6IkppbnBhbnRvbXoiO2k6NTU7czo5OiIxbjczY3QxMG4iO2k6NTY7czoxNDoiS2luZ1NrcnVwZWxsb3MiO2k6NTc7czoxMDoiSmlucGFudG9teiI7aTo1ODtzOjk6IkNlbmdpekhhbiI7aTo1OTtzOjk6InIzdjNuZzRucyI7aTo2MDtzOjk6IkJMQUNLVU5JWCI7aTo2MTtzOjk6ImFydGlja2xlQCI7fQ=="));
$g_FlexDBShe = unserialize(base64_decode("YTozNTU6e2k6MDtzOjQzOiIoXCRcd3sxLDIwfShcW1snIl0uWyciXVxdKT9ccypcLlxzKil7MTAsMjB9IjtpOjE7czozODoiXCRccyooPzpce1tee31dK1x9XHMqKStcKFteKCldKlwkXHMqXHsiO2k6MjtzOjEwMToiYXJyYXlfZmlsdGVyXChccyooL1wqLis/XCovKT9ccypAezAsfWFycmF5XChccyooL1wqLis/XCovKT9AezAsfVwkeyJfKEdFVHxQT1NUfENPT0tJRXxSRVFVRVNUfFNFUlZFUikiO2k6MztzOjU3OiJcJFx3K1xzKj1ccypcJFx3K1xzKlwoXHMqXCRcdytccyosXHMqXCRcdytccyosXHMqJydccypcKTsiO2k6NDtzOjEwNjoiKFwkXHcrXHMqPVxzKihbJyJdXHcqWyciXXxcZCspO1xzKil7Mix9XCRcdytccyo9XHMqYXJyYXlccypcKChbIiddW2EtekEtWjAtOS8rPV9dezIsfVsiJ11ccyooLFxzKik/KXsxMCx9PyI7aTo1O3M6NTI6Ilwke1xzKlx3K1xzKlwoXHMqWyInOl1bXic6Il0rWyInXVwpXHMqfVxzKlwoXHcrXHMqXCgiO2k6NjtzOjE1NDoiaWZccypcKFxzKmZpbGVfcHV0X2NvbnRlbnRzXHMqXChccypfX0ZJTEVfX1xzKixccypiYXNlNjRfZGVjb2RlXChccypcJF8oR0VUfFBPU1R8Q09PS0lFfFNFUlZFUnxSRVFVRVNUKVxbXHMqWyciXVx3K1snIl1ccypcXVxzKlwpXHMqXClccypcKVxzKmVjaG9ccyonT0snOyI7aTo3O3M6MTY1OiJcYigocGljc3xob3R8b25saW5lKXwoY3Vtc2hvdHxibG93am9ifHVuY2Vuc29yZWR8dmlhZ3JhfGNpYWxpc3xsZXZpdHJhfHRyYW1hZG9sfG51ZGV8Y2VsZWJyaXR5fHBvcm5vP3xnYXl8dGVlbnM/fHNxdWlydHxzZXhpZXN0fHNleHxmdWNrfHRpdHM/fGxlc2JpYW4pXGJbXHNcLV0rKXsyLH0iO2k6ODtzOjM1OiJkZWZhdWx0X2FjdGlvblxzKj1ccypcXFsnIl1GaWxlc01hbiI7aTo5O3M6MzM6ImRlZmF1bHRfYWN0aW9uXHMqPVxzKlsnIl1GaWxlc01hbiI7aToxMDtzOjEwMDoiSU86OlNvY2tldDo6SU5FVC0+bmV3XChQcm90b1xzKj0+XHMqInRjcCJccyosXHMqTG9jYWxQb3J0XHMqPT5ccyozNjAwMFxzKixccypMaXN0ZW5ccyo9PlxzKlNPTUFYQ09OTiI7aToxMTtzOjEwMjoiXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1xzKlsnIl17MCwxfXAyWyciXXswLDF9XHMqXF1ccyo9PVxzKlsnIl17MCwxfWNobW9kWyciXXswLDF9IjtpOjEyO3M6MjM6IkNhcHRhaW5ccytDcnVuY2hccytUZWFtIjtpOjEzO3M6MTE6ImJ5XHMrR3JpbmF5IjtpOjE0O3M6MTk6ImhhY2tlZFxzK2J5XHMrSG1laTciO2k6MTU7czozMzoic3lzdGVtXHMrZmlsZVxzK2RvXHMrbm90XHMrZGVsZXRlIjtpOjE2O3M6MTcwOiJcJGluZm8gXC49IFwoXChcJHBlcm1zXHMqJlxzKjB4MDA0MFwpXHMqXD9cKFwoXCRwZXJtc1xzKiZccyoweDA4MDBcKVxzKlw/XHMqXFxbJyJdc1xcWyciXVxzKjpccypcXFsnIl14XFxbJyJdXHMqXClccyo6XChcKFwkcGVybXNccyomXHMqMHgwODAwXClccypcP1xzKidTJ1xzKjpccyonLSdccypcKSI7aToxNztzOjc4OiJXU09zZXRjb29raWVccypcKFxzKm1kNVxzKlwoXHMqQD9cJF9TRVJWRVJcW1xzKlxcWyciXUhUVFBfSE9TVFxcWyciXVxzKlxdXHMqXCkiO2k6MTg7czo3NDoiV1NPc2V0Y29va2llXHMqXChccyptZDVccypcKFxzKkA/XCRfU0VSVkVSXFtccypbJyJdSFRUUF9IT1NUWyciXVxzKlxdXHMqXCkiO2k6MTk7czoxMDc6Indzb0V4XHMqXChccypcXFsnIl1ccyp0YXJccypjZnp2XHMqXFxbJyJdXHMqXC5ccyplc2NhcGVzaGVsbGFyZ1xzKlwoXHMqXCRfUE9TVFxbXHMqXFxbJyJdcDJcXFsnIl1ccypcXVxzKlwpIjtpOjIwO3M6NDA6ImV2YWxccypcKD9ccypiYXNlNjRfZGVjb2RlXHMqXCg/XHMqQD9cJF8iO2k6MjE7czo3ODoiZmlsZXBhdGhccyo9XHMqQD9yZWFscGF0aFxzKlwoXHMqXCRfUE9TVFxzKlxbXHMqXFxbJyJdZmlsZXBhdGhcXFsnIl1ccypcXVxzKlwpIjtpOjIyO3M6NzQ6ImZpbGVwYXRoXHMqPVxzKkA/cmVhbHBhdGhccypcKFxzKlwkX1BPU1RccypcW1xzKlsnIl1maWxlcGF0aFsnIl1ccypcXVxzKlwpIjtpOjIzO3M6NDc6InJlbmFtZVxzKlwoXHMqXHMqWyciXXswLDF9d3NvXC5waHBbJyJdezAsMX1ccyosIjtpOjI0O3M6OTc6IlwkTWVzc2FnZVN1YmplY3Rccyo9XHMqYmFzZTY0X2RlY29kZVxzKlwoXHMqXCRfUE9TVFxzKlxbXHMqWyciXXswLDF9bXNnc3ViamVjdFsnIl17MCwxfVxzKlxdXHMqXCkiO2k6MjU7czo4NzoiU0VMRUNUXHMrMVxzK0ZST01ccytteXNxbFwudXNlclxzK1dIRVJFXHMrY29uY2F0XChccypgdXNlcmBccyosXHMqJ0AnXHMqLFxzKmBob3N0YFxzKlwpIjtpOjI2O3M6NTY6InBhc3N0aHJ1XHMqXCg/XHMqZ2V0ZW52XHMqXCg/XHMqWyciXUhUVFBfQUNDRVBUX0xBTkdVQUdFIjtpOjI3O3M6NTg6InBhc3N0aHJ1XHMqXCg/XHMqZ2V0ZW52XHMqXCg/XHMqXFxbJyJdSFRUUF9BQ0NFUFRfTEFOR1VBR0UiO2k6Mjg7czo1NToie1xzKlwkXHMqe1xzKnBhc3N0aHJ1XHMqXCg/XHMqXCRjbWRccypcKVxzKn1ccyp9XHMqPGJyPiI7aToyOTtzOjg4OiJydW5jb21tYW5kXHMqXChccypbJyJdc2hlbGxoZWxwWyciXVxzKixccypbJyJdKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylbJyJdIjtpOjMwO3M6MzE6Im5jZnRwcHV0XHMqLXVccypcJGZ0cF91c2VyX25hbWUiO2k6MzE7czozNzoiXCRsb2dpblxzKj1ccypAP3Bvc2l4X2dldHVpZFwoP1xzKlwpPyI7aTozMjtzOjQ5OiIhQD9cJF9SRVFVRVNUXHMqXFtccypbJyJdYzk5c2hfc3VybFsnIl1ccypcXVxzKlwpIjtpOjMzO3M6NTM6InNldGNvb2tpZVwoP1xzKlsnIl1teXNxbF93ZWJfYWRtaW5fdXNlcm5hbWVbJyJdXHMqXCk/IjtpOjM0O3M6MTQzOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChbJyJdXCRjbWRccysxPlxzKi90bXAvY21kdGVtcFxzKzI+JjE7XHMqY2F0XHMrL3RtcC9jbWR0ZW1wO1xzKnJtXHMrL3RtcC9jbWR0ZW1wWyciXVwpOyI7aTozNTtzOjI4OiJcJGZlXChbJyJdXCRjbWRccysyPiYxWyciXVwpIjtpOjM2O3M6MTAyOiJcJGZ1bmN0aW9uXHMqXCg/XHMqQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxzKlxbXHMqWyciXXswLDF9Y21kWyciXXswLDF9XHMqXF1ccypcKT8iO2k6Mzc7czo5OToiXCRjbWRccyo9XHMqXChccypAP1wkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXHMqXFtccypbJyJdezAsMX0uKz9bJyJdezAsMX1ccypcXVxzKlwpIjtpOjM4O3M6MjA6ImV2YTFbYS16QS1aMC05X10rU2lyIjtpOjM5O3M6ODg6IlwkaW5pXHMqXFtccypbJyJdezAsMX11c2Vyc1snIl17MCwxfVxzKlxdXHMqPVxzKmFycmF5XHMqXChccypbJyJdezAsMX1yb290WyciXXswLDF9XHMqPT4iO2k6NDA7czozMzoicHJvY19vcGVuXHMqXChccypbJyJdezAsMX1JSFN0ZWFtIjtpOjQxO3M6MTM1OiJbJyJdezAsMX1odHRwZFwuY29uZlsnIl17MCwxfVxzKixccypbJyJdezAsMX12aG9zdHNcLmNvbmZbJyJdezAsMX1ccyosXHMqWyciXXswLDF9Y2ZnXC5waHBbJyJdezAsMX1ccyosXHMqWyciXXswLDF9Y29uZmlnXC5waHBbJyJdezAsMX0iO2k6NDI7czo4NzoiXHMqe1xzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXHMqXFtccypbJyJdezAsMX1yb290WyciXXswLDF9XHMqXF1ccyp9IjtpOjQzO3M6NDY6InByZWdfcmVwbGFjZVxzKlwoP1xzKlsnIl17MCwxfS9cLlwqL2VbJyJdezAsMX0iO2k6NDQ7czozNjoiZXZhbFxzKlwoP1xzKmZpbGVfZ2V0X2NvbnRlbnRzXHMqXCg/IjtpOjQ1O3M6NzQ6IkA/c2V0Y29va2llXHMqXCg/XHMqWyciXXswLDF9aGl0WyciXXswLDF9LFxzKjFccyosXHMqdGltZVxzKlwoP1xzKlwpP1xzKlwrIjtpOjQ2O3M6NDE6ImV2YWxccypcKD9AP1xzKnN0cmlwc2xhc2hlc1xzKlwoP1xzKkA/XCRfIjtpOjQ3O3M6NTk6ImV2YWxccypcKD9AP1xzKnN0cmlwc2xhc2hlc1xzKlwoP1xzKmFycmF5X3BvcFxzKlwoP1xzKkA/XCRfIjtpOjQ4O3M6NDM6ImZvcGVuXHMqXCg/XHMqWyciXXswLDF9L2V0Yy9wYXNzd2RbJyJdezAsMX0iO2k6NDk7czoyNDoiXCRHTE9CQUxTXFtbJyJdezAsMX1fX19fIjtpOjUwO3M6MjE3OiJpc19jYWxsYWJsZVxzKlwoP1xzKlsnIl17MCwxfVxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilbJyJdezAsMX1cKT9ccythbmRccyshaW5fYXJyYXlccypcKD9ccypbJyJdezAsMX1cYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pWyciXXswLDF9XHMqLFxzKlwkZGlzYWJsZWZ1bmNzIjtpOjUxO3M6MTE4OiJmaWxlX2dldF9jb250ZW50c1xzKlwoP1xzKnRyaW1ccypcKFxzKlwkLis/XFtcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9Lis/WyciXXswLDF9XF1cXVwpXCk7IjtpOjUyO3M6MTM2OiJ3cF9wb3N0c1xzK1dIRVJFXHMrcG9zdF90eXBlXHMqPVxzKlsnIl17MCwxfXBvc3RbJyJdezAsMX1ccytBTkRccytwb3N0X3N0YXR1c1xzKj1ccypbJyJdezAsMX1wdWJsaXNoWyciXXswLDF9XHMrT1JERVJccytCWVxzK2BJRGBccytERVNDIjtpOjUzO3M6MjA6ImV4ZWNccypcKFxzKlsnIl1pcGZ3IjtpOjU0O3M6NDI6InN0cnJldlwoP1xzKlsnIl17MCwxfXRyZXNzYVsnIl17MCwxfVxzKlwpPyI7aTo1NTtzOjQ5OiJzdHJyZXZcKD9ccypbJyJdezAsMX1lZG9jZWRfNDZlc2FiWyciXXswLDF9XHMqXCk/IjtpOjU2O3M6NzA6ImZ1bmN0aW9uXHMrdXJsR2V0Q29udGVudHNccypcKD9ccypcJHVybFxzKixccypcJHRpbWVvdXRccyo9XHMqXGQrXHMqXCkiO2k6NTc7czo3MToiZndyaXRlXHMqXCg/XHMqXCRmcHNldHZccyosXHMqZ2V0ZW52XHMqXChccypbJyJdSFRUUF9DT09LSUVbJyJdXHMqXClccyoiO2k6NTg7czo2NjoiaXNzZXRccypcKD9ccypcJF9QT1NUXHMqXFtccypbJyJdezAsMX1leGVjZ2F0ZVsnIl17MCwxfVxzKlxdXHMqXCk/IjtpOjU5O3M6MjA2OiJVTklPTlxzK1NFTEVDVFxzK1snIl17MCwxfTBbJyJdezAsMX1ccyosXHMqWyciXXswLDF9PFw/IHN5c3RlbVwoXFxcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbY3BjXF1cKTtleGl0O1xzKlw/PlsnIl17MCwxfVxzKixccyowXHMqLDBccyosXHMqMFxzKixccyowXHMrSU5UT1xzK09VVEZJTEVccytbJyJdezAsMX1cJFsnIl17MCwxfSI7aTo2MDtzOjE0OToiXCRHTE9CQUxTXFtbJyJdezAsMX0uKz9bJyJdezAsMX1cXT1BcnJheVxzKlwoXHMqYmFzZTY0X2RlY29kZVxzKlwoXHMqWyciXXswLDF9Lis/WyciXXswLDF9XHMqXClccyosXHMqYmFzZTY0X2RlY29kZVxzKlwoXHMqWyciXXswLDF9Lis/WyciXXswLDF9XHMqXCkiO2k6NjE7czo3MzoicHJlZ19yZXBsYWNlXHMqXCg/XHMqWyciXXswLDF9L1wuXCpcWy4rP1xdXD8vZVsnIl17MCwxfVxzKixccypzdHJfcmVwbGFjZSI7aTo2MjtzOjEwMToiXCRHTE9CQUxTXFtccypbJyJdezAsMX0uKz9bJyJdezAsMX1ccypcXVxbXHMqXGQrXHMqXF1cKFxzKlwkX1xkK1xzKixccypfXGQrXHMqXChccypcZCtccypcKVxzKlwpXHMqXCkiO2k6NjM7czoxMTU6IlwkYmVlY29kZVxzKj1AP2ZpbGVfZ2V0X2NvbnRlbnRzXHMqXCg/WyciXXswLDF9XHMqXCR1cmxwdXJzXHMqWyciXXswLDF9XCk/XHMqO1xzKmVjaG9ccytbJyJdezAsMX1cJGJlZWNvZGVbJyJdezAsMX0iO2k6NjQ7czo3OToiXCR4XGQrXHMqPVxzKlsnIl0uKz9bJyJdXHMqO1xzKlwkeFxkK1xzKj1ccypbJyJdLis/WyciXVxzKjtccypcJHhcZCtccyo9XHMqWyciXSI7aTo2NTtzOjQzOiI8XD9waHBccytcJF9GXHMqPVxzKl9fRklMRV9fXHMqO1xzKlwkX1hccyo9IjtpOjY2O3M6Njg6ImlmXHMrXCg/XHMqbWFpbFxzKlwoXHMqXCRyZWNwXHMqLFxzKlwkc3VialxzKixccypcJHN0dW50XHMqLFxzKlwkZnJtIjtpOjY3O3M6MTM5OiJpZlxzK1woXHMqc3RycG9zXHMqXChccypcJHVybFxzKixccypbJyJdanMvbW9vdG9vbHNcLmpzWyciXVxzKlwpXHMqPT09XHMqZmFsc2VccysmJlxzK3N0cnBvc1xzKlwoXHMqXCR1cmxccyosXHMqWyciXWpzL2NhcHRpb25cLmpzWyciXXswLDF9IjtpOjY4O3M6ODc6ImV2YWxccypcKD9ccypzdHJpcHNsYXNoZXNccypcKD9ccyphcnJheV9wb3BcKD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aTo2OTtzOjIzMzoiaWZccypcKD9ccyppc3NldFxzKlwoP1xzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXHMqXFtccypbJyJdezAsMX1cdytbJyJdezAsMX1ccypcXVxzKlwpP1xzKlwpXHMqe1xzKlwkXHcrXHMqPVxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXHMqXFtccypbJyJdezAsMX1cdytbJyJdezAsMX1ccypcXTtccypldmFsXHMqXCg/XHMqXCRcdytccypcKT8iO2k6NzA7czoxMjM6InByZWdfcmVwbGFjZVxzKlwoXHMqWyciXS9cXlwod3d3XHxmdHBcKVxcXC4vaVsnIl1ccyosXHMqWyciXVsnIl0sXHMqQFwkX1NFUlZFUlxzKlxbXHMqWyciXXswLDF9SFRUUF9IT1NUWyciXXswLDF9XHMqXF1ccypcKSI7aTo3MTtzOjEwMToiaWZccypcKCFmdW5jdGlvbl9leGlzdHNccypcKFxzKlsnIl1wb3NpeF9nZXRwd3VpZFsnIl1ccypcKVxzKiYmXHMqIWluX2FycmF5XHMqXChccypbJyJdcG9zaXhfZ2V0cHd1aWQiO2k6NzI7czo4ODoiPVxzKnByZWdfc3BsaXRccypcKFxzKlsnIl0vXFwsXChcXCBcK1wpXD8vWyciXSxccypAP2luaV9nZXRccypcKFxzKlsnIl1kaXNhYmxlX2Z1bmN0aW9ucyI7aTo3MztzOjQ3OiJcJGJccypcLlxzKlwkcFxzKlwuXHMqXCRoXHMqXC5ccypcJGtccypcLlxzKlwkdiI7aTo3NDtzOjIzOiJcKFxzKlsnIl1JTlNIRUxMWyciXVxzKiI7aTo3NTtzOjYwOiIoR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxzKlxbXHMqWyciXV9fX1snIl1ccyoiO2k6NzY7czoxMDA6ImFycmF5X3BvcFxzKlwoP1xzKlwkd29ya1JlcGxhY2VccyosXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylccyosXHMqXCRjb3VudEtleXNOZXciO2k6Nzc7czozNToiaWZccypcKD9ccypAP3ByZWdfbWF0Y2hccypcKD9ccypzdHIiO2k6Nzg7czo0MzoiQFwkX0NPT0tJRVxbWyciXXswLDF9c3RhdENvdW50ZXJbJyJdezAsMX1cXSI7aTo3OTtzOjEwNToiZm9wZW5ccypcKD9ccypbJyJdaHR0cDovL1snIl1ccypcLlxzKlwkY2hlY2tfZG9tYWluXHMqXC5ccypbJyJdOjgwWyciXVxzKlwuXHMqXCRjaGVja19kb2NccyosXHMqWyciXXJbJyJdIjtpOjgwO3M6NTU6IkA/Z3ppbmZsYXRlXHMqXChccypAP2Jhc2U2NF9kZWNvZGVccypcKFxzKkA/c3RyX3JlcGxhY2UiO2k6ODE7czoyODoiZmlsZV9wdXRfY29udGVudHpccypcKD9ccypcJCI7aTo4MjtzOjg3OiImJlxzKmZ1bmN0aW9uX2V4aXN0c1xzKlwoP1xzKlsnIl17MCwxfWdldG14cnJbJyJdezAsMX1cKVxzKlwpXHMqe1xzKkBnZXRteHJyXHMqXCg/XHMqXCQiO2k6ODM7czo0MToiXCRwb3N0UmVzdWx0XHMqPVxzKmN1cmxfZXhlY1xzKlwoP1xzKlwkY2giO2k6ODQ7czoyNToiZnVuY3Rpb25ccytzcWwyX3NhZmVccypcKCI7aTo4NTtzOjg1OiJleGl0XHMqXChccypbJyJdezAsMX08c2NyaXB0PlxzKnNldFRpbWVvdXRccypcKFxzKlxcWyciXXswLDF9ZG9jdW1lbnRcLmxvY2F0aW9uXC5ocmVmIjtpOjg2O3M6Mzg6ImV2YWxcKFxzKnN0cmlwc2xhc2hlc1woXHMqXFxcJF9SRVFVRVNUIjtpOjg3O3M6MzY6IiF0b3VjaFwoWyciXXswLDF9XC5cLi9cLlwuL2xhbmd1YWdlLyI7aTo4ODtzOjEwOiJEYzBSSGFbJyJdIjtpOjg5O3M6NjA6ImhlYWRlclxzKlwoWyciXUxvY2F0aW9uOlxzKlsnIl1ccypcLlxzKlwkdG9ccypcLlxzKnVybGRlY29kZSI7aTo5MDtzOjE1NjoiaWZccypcKFxzKnN0cmlwb3NccypcKFxzKlwkX1NFUlZFUlxbWyciXXswLDF9SFRUUF9VU0VSX0FHRU5UWyciXXswLDF9XF1ccyosXHMqWyciXXswLDF9QW5kcm9pZFsnIl17MCwxfVwpXHMqIT09ZmFsc2VccyomJlxzKiFcJF9DT09LSUVcW1snIl17MCwxfWRsZV91c2VyX2lkIjtpOjkxO3M6Mzg6ImVjaG9ccytAZmlsZV9nZXRfY29udGVudHNccypcKFxzKlwkZ2V0IjtpOjkyO3M6NDc6ImRlZmF1bHRfYWN0aW9uXHMqPVxzKlsnIl17MCwxfUZpbGVzTWFuWyciXXswLDF9IjtpOjkzO3M6MzM6ImRlZmluZVxzKlwoXHMqWyciXURFRkNBTExCQUNLTUFJTCI7aTo5NDtzOjE3OiJNeXN0ZXJpb3VzXHMrV2lyZSI7aTo5NTtzOjM0OiJwcmVnX3JlcGxhY2VccypcKD9ccypbJyJdL1wuXCsvZXNpIjtpOjk2O3M6NDU6ImRlZmluZVxzKlwoP1xzKlsnIl1TQkNJRF9SRVFVRVNUX0ZJTEVbJyJdXHMqLCI7aTo5NztzOjYwOiJcJHRsZFxzKj1ccyphcnJheVxzKlwoXHMqWyciXWNvbVsnIl0sWyciXW9yZ1snIl0sWyciXW5ldFsnIl0iO2k6OTg7czoxNzoiQnJhemlsXHMrSGFja1RlYW0iO2k6OTk7czoxNDU6ImlmXCghZW1wdHlcKFwkX0ZJTEVTXFtbJyJdezAsMX1tZXNzYWdlWyciXXswLDF9XF1cW1snIl17MCwxfW5hbWVbJyJdezAsMX1cXVwpXHMrQU5EXHMrXChtZDVcKFwkX1BPU1RcW1snIl17MCwxfW5pY2tbJyJdezAsMX1cXVwpXHMqPT1ccypbJyJdezAsMX0iO2k6MTAwO3M6NzU6InRpbWVcKFwpXHMqXCtccyoxMDAwMFxzKixccypbJyJdL1snIl1cKTtccyplY2hvXHMrXCRtX3p6O1xzKmV2YWxccypcKFwkbV96eiI7aToxMDE7czoxMDY6InJldHVyblxzKlwoXHMqc3Ryc3RyXHMqXChccypcJHNccyosXHMqJ2VjaG8nXHMqXClccyo9PVxzKmZhbHNlXHMqXD9ccypcKFxzKnN0cnN0clxzKlwoXHMqXCRzXHMqLFxzKidwcmludCciO2k6MTAyO3M6Njc6InNldF90aW1lX2xpbWl0XHMqXChccyowXHMqXCk7XHMqaWZccypcKCFTZWNyZXRQYWdlSGFuZGxlcjo6Y2hlY2tLZXkiO2k6MTAzO3M6NzM6IkBoZWFkZXJcKFsnIl1Mb2NhdGlvbjpccypbJyJdXC5bJyJdaFsnIl1cLlsnIl10WyciXVwuWyciXXRbJyJdXC5bJyJdcFsnIl0iO2k6MTA0O3M6OToiSXJTZWNUZWFtIjtpOjEwNTtzOjk3OiJcJHJCdWZmTGVuXHMqPVxzKm9yZFxzKlwoXHMqVkNfRGVjcnlwdFxzKlwoXHMqZnJlYWRccypcKFxzKlwkaW5wdXQsXHMqMVxzKlwpXHMqXClccypcKVxzKlwqXHMqMjU2IjtpOjEwNjtzOjc0OiJjbGVhcnN0YXRjYWNoZVwoXHMqXCk7XHMqaWZccypcKFxzKiFpc19kaXJccypcKFxzKlwkZmxkXHMqXClccypcKVxzKnJldHVybiI7aToxMDc7czo5NzoiY29udGVudD1bJyJdezAsMX1uby1jYWNoZVsnIl17MCwxfTtccypcJGNvbmZpZ1xbWyciXXswLDF9ZGVzY3JpcHRpb25bJyJdezAsMX1cXVxzKlwuPVxzKlsnIl17MCwxfSI7aToxMDg7czoxMjoidG1oYXBiemNlcmZmIjtpOjEwOTtzOjcwOiJmaWxlX2dldF9jb250ZW50c1xzKlwoP1xzKkFETUlOX1JFRElSX1VSTFxzKixccypmYWxzZVxzKixccypcJGN0eFxzKlwpIjtpOjExMDtzOjg3OiJpZlxzKlwoXHMqXCRpXHMqPFxzKlwoXHMqY291bnRccypcKFxzKlwkX1BPU1RcW1xzKlsnIl17MCwxfXFbJyJdezAsMX1ccypcXVxzKlwpXHMqLVxzKjEiO2k6MTExO3M6MjMyOiJpc3NldFxzKlwoXHMqXCRfRklMRVNcW1xzKlsnIl17MCwxfXhbJyJdezAsMX1ccypcXVxzKlwpXHMqXD9ccypcKFxzKmlzX3VwbG9hZGVkX2ZpbGVccypcKFxzKlwkX0ZJTEVTXFtccypbJyJdezAsMX14WyciXXswLDF9XHMqXF1cW1xzKlsnIl17MCwxfXRtcF9uYW1lWyciXXswLDF9XHMqXF1ccypcKVxzKlw/XHMqXChccypjb3B5XHMqXChccypcJF9GSUxFU1xbXHMqWyciXXswLDF9eFsnIl17MCwxfVxzKlxdIjtpOjExMjtzOjgyOiJcJFVSTFxzKj1ccypcJHVybHNcW1xzKnJhbmRcKFxzKjBccyosXHMqY291bnRccypcKFxzKlwkdXJsc1xzKlwpXHMqLVxzKjFccypcKVxzKlxdIjtpOjExMztzOjIxMzoiQD9tb3ZlX3VwbG9hZGVkX2ZpbGVccypcKFxzKlwkX0ZJTEVTXFtccypbJyJdezAsMX1tZXNzYWdlWyciXXswLDF9XHMqXF1cW1xzKlsnIl17MCwxfXRtcF9uYW1lWyciXXswLDF9XHMqXF1ccyosXHMqXCRzZWN1cml0eV9jb2RlXHMqXC5ccyoiLyJccypcLlxzKlwkX0ZJTEVTXFtbJyJdezAsMX1tZXNzYWdlWyciXXswLDF9XF1cW1snIl17MCwxfW5hbWVbJyJdezAsMX1cXVwpIjtpOjExNDtzOjM5OiJldmFsXHMqXCg/XHMqc3RycmV2XHMqXCg/XHMqc3RyX3JlcGxhY2UiO2k6MTE1O3M6ODE6IlwkcmVzPW15c3FsX3F1ZXJ5XChbJyJdezAsMX1TRUxFQ1RccytcKlxzK0ZST01ccytgd2F0Y2hkb2dfb2xkXzA1YFxzK1dIRVJFXHMrcGFnZSI7aToxMTY7czo3MjoiXF5kb3dubG9hZHMvXChcWzAtOVxdXCpcKS9cKFxbMC05XF1cKlwpL1wkXHMrZG93bmxvYWRzXC5waHBcP2M9XCQxJnA9XCQyIjtpOjExNztzOjkyOiJwcmVnX3JlcGxhY2VccypcKFxzKlwkZXhpZlxbXHMqXFxbJyJdTWFrZVxcWyciXVxzKlxdXHMqLFxzKlwkZXhpZlxbXHMqXFxbJyJdTW9kZWxcXFsnIl1ccypcXSI7aToxMTg7czozODoiZmNsb3NlXChcJGZcKTtccyplY2hvXHMqWyciXW9cLmtcLlsnIl0iO2k6MTE5O3M6NDE6ImZ1bmN0aW9uXHMraW5qZWN0XChcJGZpbGUsXHMqXCRpbmplY3Rpb249IjtpOjEyMDtzOjcxOiJleGVjbFwoWyciXS9iaW4vc2hbJyJdXHMqLFxzKlsnIl0vYmluL3NoWyciXVxzKixccypbJyJdLWlbJyJdXHMqLFxzKjBcKSI7aToxMjE7czo0MzoiZmluZFxzKy9ccystdHlwZVxzK2ZccystcGVybVxzKy0wNDAwMFxzKy1scyI7aToxMjI7czo0NDoiaWZccypcKFxzKmZ1bmN0aW9uX2V4aXN0c1xzKlwoXHMqJ3BjbnRsX2ZvcmsiO2k6MTIzO3M6NjU6InVybGVuY29kZVwocHJpbnRfclwoYXJyYXlcKFwpLDFcKVwpLDUsMVwpXC5jXCksXCRjXCk7fWV2YWxcKFwkZFwpIjtpOjEyNDtzOjg5OiJhcnJheV9rZXlfZXhpc3RzXHMqXChccypcJGZpbGVSYXNccyosXHMqXCRmaWxlVHlwZVwpXHMqXD9ccypcJGZpbGVUeXBlXFtccypcJGZpbGVSYXNccypcXSI7aToxMjU7czoxMDU6ImlmXHMqXChccypmd3JpdGVccypcKFxzKlwkaGFuZGxlXHMqLFxzKmZpbGVfZ2V0X2NvbnRlbnRzXHMqXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aToxMjY7czoxNzg6ImlmXHMqXChccypcJF9QT1NUXFtccypbJyJdezAsMX1wYXRoWyciXXswLDF9XHMqXF1ccyo9PVxzKlsnIl17MCwxfVsnIl17MCwxfVxzKlwpXHMqe1xzKlwkdXBsb2FkZmlsZVxzKj1ccypcJF9GSUxFU1xbXHMqWyciXXswLDF9ZmlsZVsnIl17MCwxfVxzKlxdXFtccypbJyJdezAsMX1uYW1lWyciXXswLDF9XHMqXF0iO2k6MTI3O3M6ODM6ImlmXHMqXChccypcJGRhdGFTaXplXHMqPFxzKkJPVENSWVBUX01BWF9TSVpFXHMqXClccypyYzRccypcKFxzKlwkZGF0YSxccypcJGNyeXB0a2V5IjtpOjEyODtzOjkwOiIsXHMqYXJyYXlccypcKCdcLicsJ1wuXC4nLCdUaHVtYnNcLmRiJ1wpXHMqXClccypcKVxzKntccypjb250aW51ZTtccyp9XHMqaWZccypcKFxzKmlzX2ZpbGUiO2k6MTI5O3M6NTE6IlwpXHMqXC5ccypzdWJzdHJccypcKFxzKm1kNVxzKlwoXHMqc3RycmV2XHMqXChccypcJCI7aToxMzA7czoyODoiYXNzZXJ0XHMqXChccypAP3N0cmlwc2xhc2hlcyI7aToxMzE7czoxNToiWyciXWUvXCpcLi9bJyJdIjtpOjEzMjtzOjUyOiJlY2hvWyciXXswLDF9PGNlbnRlcj48Yj5Eb25lXHMqPT0+XHMqXCR1c2VyZmlsZV9uYW1lIjtpOjEzMztzOjEzNDoiaWZccypcKFwka2V5XHMqIT1ccypbJyJdezAsMX1tYWlsX3RvWyciXXswLDF9XHMqJiZccypcJGtleVxzKiE9XHMqWyciXXswLDF9c210cF9zZXJ2ZXJbJyJdezAsMX1ccyomJlxzKlwka2V5XHMqIT1ccypbJyJdezAsMX1zbXRwX3BvcnQiO2k6MTM0O3M6NTk6InN0cnBvc1woXCR1YSxccypbJyJdezAsMX15YW5kZXhib3RbJyJdezAsMX1cKVxzKiE9PVxzKmZhbHNlIjtpOjEzNTtzOjQ1OiJpZlwoQ2hlY2tJUE9wZXJhdG9yXChcKVxzKiYmXHMqIWlzTW9kZW1cKFwpXCkiO2k6MTM2O3M6MzQ6InVybD08XD9waHBccyplY2hvXHMqXCRyYW5kX3VybDtcPz4iO2k6MTM3O3M6Mjc6ImVjaG9ccypbJyJdYW5zd2VyPWVycm9yWyciXSI7aToxMzg7czozMjoiXCRwb3N0XHMqPVxzKlsnIl1cXHg3N1xceDY3XFx4NjUiO2k6MTM5O3M6NDY6ImlmXHMqXChkZXRlY3RfbW9iaWxlX2RldmljZVwoXClcKVxzKntccypoZWFkZXIiO2k6MTQwO3M6OToiSXJJc1RcLklyIjtpOjE0MTtzOjg5OiJcJGxldHRlclxzKj1ccypzdHJfcmVwbGFjZVxzKlwoXHMqXCRBUlJBWVxbMFxdXFtcJGpcXVxzKixccypcJGFyclxbXCRpbmRcXVxzKixccypcJGxldHRlciI7aToxNDI7czo5MjoiY3JlYXRlX2Z1bmN0aW9uXHMqXChccypbJyJdXCRtWyciXVxzKixccypbJyJdaWZccypcKFxzKlwkbVxzKlxbXHMqMHgwMVxzKlxdXHMqPT1ccypbJyJdTFsnIl0iO2k6MTQzO3M6NzI6IlwkcFxzKj1ccypzdHJwb3NcKFwkdHhccyosXHMqWyciXXswLDF9e1wjWyciXXswLDF9XHMqLFxzKlwkcDJccypcK1xzKjJcKSI7aToxNDQ7czoxMTI6IlwkdXNlcl9hZ2VudFxzKj1ccypwcmVnX3JlcGxhY2VccypcKFxzKlsnIl1cfFVzZXJcXFwuQWdlbnRcXDpcW1xccyBcXVw/XHxpWyciXVxzKixccypbJyJdWyciXVxzKixccypcJHVzZXJfYWdlbnQiO2k6MTQ1O3M6MzE6InByaW50XCgiXCNccytpbmZvXHMrT0tcXG5cXG4iXCkiO2k6MTQ2O3M6NTE6IlxdXHMqfVxzKj1ccyp0cmltXHMqXChccyphcnJheV9wb3BccypcKFxzKlwke1xzKlwkeyI7aToxNDc7czo2NDoiXF09WyciXXswLDF9aXBbJyJdezAsMX1ccyo7XHMqaWZccypcKFxzKmlzc2V0XHMqXChccypcJF9TRVJWRVJcWyI7aToxNDg7czozNDoicHJpbnRccypcJHNvY2sgIlBSSVZNU0cgIlwuXCRvd25lciI7aToxNDk7czo2MzoiaWZcKC9cXlxcOlwkb3duZXIhXC5cKlxcQFwuXCpQUklWTVNHXC5cKjpcLm1zZ2Zsb29kXChcLlwqXCkvXCl7IjtpOjE1MDtzOjI2OiJcWy1cXVxzK0Nvbm5lY3Rpb25ccytmYWlsZCI7aToxNTE7czo1NDoiPCEtLVwjZXhlY1xzK2NtZD1bJyJdezAsMX1cJEhUVFBfQUNDRVBUWyciXXswLDF9XHMqLS0+IjtpOjE1MjtzOjE2NzoiWyciXXswLDF9RnJvbTpccypbJyJdezAsMX1cLlwkX1BPU1RcW1snIl17MCwxfXJlYWxuYW1lWyciXXswLDF9XF1cLlsnIl17MCwxfSBbJyJdezAsMX1cLlsnIl17MCwxfSA8WyciXXswLDF9XC5cJF9QT1NUXFtbJyJdezAsMX1mcm9tWyciXXswLDF9XF1cLlsnIl17MCwxfT5cXG5bJyJdezAsMX0iO2k6MTUzO3M6OTk6ImlmXHMqXChccyppc19kaXJccypcKFxzKlwkRnVsbFBhdGhccypcKVxzKlwpXHMqQWxsRGlyXHMqXChccypcJEZ1bGxQYXRoXHMqLFxzKlwkRmlsZXNccypcKTtccyp9XHMqfSI7aToxNTQ7czo3ODoiXCRwXHMqPVxzKnN0cnBvc1xzKlwoXHMqXCR0eFxzKixccypbJyJdezAsMX17XCNbJyJdezAsMX1ccyosXHMqXCRwMlxzKlwrXHMqMlwpIjtpOjE1NTtzOjEyMzoicHJlZ19tYXRjaF9hbGxcKFsnIl17MCwxfS88YSBocmVmPSJcXC91cmxcXFw/cT1cKFwuXCtcP1wpXFsmXHwiXF1cKy9pc1snIl17MCwxfSwgXCRwYWdlXFtbJyJdezAsMX1leGVbJyJdezAsMX1cXSwgXCRsaW5rc1wpIjtpOjE1NjtzOjgwOiJcJHVybFxzKj1ccypcJHVybFxzKlwuXHMqWyciXXswLDF9XD9bJyJdezAsMX1ccypcLlxzKmh0dHBfYnVpbGRfcXVlcnlcKFwkcXVlcnlcKSI7aToxNTc7czo4MzoicHJpbnRccytcJHNvY2tccytbJyJdezAsMX1OSUNLIFsnIl17MCwxfVxzK1wuXHMrXCRuaWNrXHMrXC5ccytbJyJdezAsMX1cXG5bJyJdezAsMX0iO2k6MTU4O3M6MzI6IlBSSVZNU0dcLlwqOlwub3duZXJcXHNcK1woXC5cKlwpIjtpOjE1OTtzOjc1OiJcJHJlc3VsdEZVTFxzKj1ccypzdHJpcGNzbGFzaGVzXHMqXChccypcJF9QT1NUXFtbJyJdezAsMX1yZXN1bHRGVUxbJyJdezAsMX0iO2k6MTYwO3M6MTYyOiJcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdXHMqXCkiO2k6MTYxO3M6NjY6ImlmXHMqXChccypAP21kNVxzKlwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcWyI7aToxNjI7czoxMDA6ImVjaG9ccytmaWxlX2dldF9jb250ZW50c1xzKlwoXHMqYmFzZTY0X3VybF9kZWNvZGVccypcKFxzKkA/XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MTYzO3M6OTA6ImZ3cml0ZVxzKlwoXHMqXCRmaFxzKixccypzdHJpcHNsYXNoZXNccypcKFxzKkA/XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcWyI7aToxNjQ7czo4MzoiaWZccypcKFxzKm1haWxccypcKFxzKlwkbWFpbHNcW1wkaVxdXHMqLFxzKlwkdGVtYVxzKixccypiYXNlNjRfZW5jb2RlXHMqXChccypcJHRleHQiO2k6MTY1O3M6NjI6IlwkZ3ppcFxzKj1ccypAP2d6aW5mbGF0ZVxzKlwoXHMqQD9zdWJzdHJccypcKFxzKlwkZ3plbmNvZGVfYXJnIjtpOjE2NjtzOjczOiJtb3ZlX3VwbG9hZGVkX2ZpbGVcKFwkX0ZJTEVTXFtbJyJdezAsMX1lbGlmWyciXXswLDF9XF1cW1snIl17MCwxfXRtcF9uYW1lIjtpOjE2NztzOjgwOiJoZWFkZXJcKFsnIl17MCwxfXM6XHMqWyciXXswLDF9XHMqXC5ccypwaHBfdW5hbWVccypcKFxzKlsnIl17MCwxfW5bJyJdezAsMX1ccypcKSI7aToxNjg7czoxMjoiQnlccytXZWJSb29UIjtpOjE2OTtzOjU3OiJcJE9PTzBPME8wMD1fX0ZJTEVfXztccypcJE9PMDBPMDAwMFxzKj1ccyoweDFiNTQwO1xzKmV2YWwiO2k6MTcwO3M6NTI6IlwkbWFpbGVyXHMqPVxzKlwkX1BPU1RcW1snIl17MCwxfXhfbWFpbGVyWyciXXswLDF9XF0iO2k6MTcxO3M6Nzc6InByZWdfbWF0Y2hcKFsnIl0vXCh5YW5kZXhcfGdvb2dsZVx8Ym90XCkvaVsnIl0sXHMqZ2V0ZW52XChbJyJdSFRUUF9VU0VSX0FHRU5UIjtpOjE3MjtzOjQ3OiJlY2hvXHMrXCRpZnVwbG9hZD1bJyJdezAsMX1ccypJdHNPa1xzKlsnIl17MCwxfSI7aToxNzM7czo0MjoiZnNvY2tvcGVuXHMqXChccypcJENvbm5lY3RBZGRyZXNzXHMqLFxzKjI1IjtpOjE3NDtzOjY0OiJcJF9TRVNTSU9OXFtbJyJdezAsMX1zZXNzaW9uX3BpblsnIl17MCwxfVxdXHMqPVxzKlsnIl17MCwxfVwkUElOIjtpOjE3NTtzOjYzOiJcJHVybFsnIl17MCwxfVxzKlwuXHMqXCRzZXNzaW9uX2lkXHMqXC5ccypbJyJdezAsMX0vbG9naW5cLmh0bWwiO2k6MTc2O3M6NDQ6ImZccyo9XHMqXCRxXHMqXC5ccypcJGFccypcLlxzKlwkYlxzKlwuXHMqXCR4IjtpOjE3NztzOjYxOiJpZlxzKlwobWQ1XCh0cmltXChcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIjtpOjE3ODtzOjMzOiJkaWVccypcKFxzKlBIUF9PU1xzKlwuXHMqY2hyXHMqXCgiO2k6MTc5O3M6MTkyOiJjcmVhdGVfZnVuY3Rpb25ccypcKFsnIl1bJyJdXHMqLFxzKlxiKGV2YWx8YmFzZTY0X2RlY29kZXxzdHJyZXZ8cHJlZ19yZXBsYWNlfHByZWdfcmVwbGFjZV9jYWxsYmFja3x1cmxkZWNvZGV8c3Ryc3RyfGd6aW5mbGF0ZXxzcHJpbnRmfGd6dW5jb21wcmVzc3xhc3NlcnR8c3RyX3JvdDEzfG1kNXxhcnJheV93YWxrfGFycmF5X2ZpbHRlcikiO2k6MTgwO3M6ODY6IlwkaGVhZGVyc1xzKj1ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9aGVhZGVyc1snIl17MCwxfVxdIjtpOjE4MTtzOjg2OiJmaWxlX3B1dF9jb250ZW50c1xzKlwoWyciXXswLDF9MVwudHh0WyciXXswLDF9XHMqLFxzKnByaW50X3JccypcKFxzKlwkX1BPU1RccyosXHMqdHJ1ZSI7aToxODI7czozNToiZndyaXRlXHMqXChccypcJGZsd1xzKixccypcJGZsXHMqXCkiO2k6MTgzO3M6Mzg6Ilwkc3lzX3BhcmFtc1xzKj1ccypAP2ZpbGVfZ2V0X2NvbnRlbnRzIjtpOjE4NDtzOjUxOiJcJGFsbGVtYWlsc1xzKj1ccypAc3BsaXRcKCJcXG4iXHMqLFxzKlwkZW1haWxsaXN0XCkiO2k6MTg1O3M6NTA6ImZpbGVfcHV0X2NvbnRlbnRzXChTVkNfU0VMRlxzKlwuXHMqWyciXS9cLmh0YWNjZXNzIjtpOjE4NjtzOjU3OiJjcmVhdGVfZnVuY3Rpb25cKFsnIl1bJyJdLFxzKlwkb3B0XFsxXF1ccypcLlxzKlwkb3B0XFs0XF0iO2k6MTg3O3M6OTU6IjxzY3JpcHRccyt0eXBlPVsnIl17MCwxfXRleHQvamF2YXNjcmlwdFsnIl17MCwxfVxzK3NyYz1bJyJdezAsMX1qcXVlcnktdVwuanNbJyJdezAsMX0+PC9zY3JpcHQ+IjtpOjE4ODtzOjI4OiJVUkw9PFw/ZWNob1xzK1wkaW5kZXg7XHMrXD8+IjtpOjE4OTtzOjIzOiJcI1xzKnNlY3VyaXR5c3BhY2VcLmNvbSI7aToxOTA7czoxODoiXCNccypzdGVhbHRoXHMqYm90IjtpOjE5MTtzOjIxOiJBcHBsZVxzK1NwQW1ccytSZVp1bFQiO2k6MTkyO3M6NTI6ImlzX3dyaXRhYmxlXChcJGRpclwuWyciXXdwLWluY2x1ZGVzL3ZlcnNpb25cLnBocFsnIl0iO2k6MTkzO3M6NDI6ImlmXChlbXB0eVwoXCRfQ09PS0lFXFtbJyJdeFsnIl1cXVwpXCl7ZWNobyI7aToxOTQ7czoyOToiXClcXTt9aWZcKGlzc2V0XChcJF9TRVJWRVJcW18iO2k6MTk1O3M6NjY6ImlmXChAXCR2YXJzXChnZXRfbWFnaWNfcXVvdGVzX2dwY1woXClccypcP1xzKnN0cmlwc2xhc2hlc1woXCR1cmlcKSI7aToxOTY7czozMzoiYmFzZVsnIl17MCwxfVwuXChcZCtccypcKlxzKlxkK1wpIjtpOjE5NztzOjc1OiJcJHBhcmFtXHMqPVxzKlwkcGFyYW1ccyp4XHMqXCRuXC5zdWJzdHJccypcKFwkcGFyYW1ccyosXHMqbGVuZ3RoXChcJHBhcmFtXCkiO2k6MTk4O3M6NTM6InJlZ2lzdGVyX3NodXRkb3duX2Z1bmN0aW9uXChccypbJyJdezAsMX1yZWFkX2Fuc19jb2RlIjtpOjE5OTtzOjM1OiJiYXNlNjRfZGVjb2RlXChcJF9QT1NUXFtbJyJdezAsMX1fLSI7aToyMDA7czo1NDoiaWZcKGlzc2V0XChcJF9QT1NUXFtbJyJdezAsMX1tc2dzdWJqZWN0WyciXXswLDF9XF1cKVwpIjtpOjIwMTtzOjEzMzoibWFpbFwoXCRhcnJcW1snIl17MCwxfXRvWyciXXswLDF9XF0sXCRhcnJcW1snIl17MCwxfXN1YmpbJyJdezAsMX1cXSxcJGFyclxbWyciXXswLDF9bXNnWyciXXswLDF9XF0sXCRhcnJcW1snIl17MCwxfWhlYWRbJyJdezAsMX1cXVwpOyI7aToyMDI7czozODoiZmlsZV9nZXRfY29udGVudHNcKHRyaW1cKFwkZlxbXCRfR0VUXFsiO2k6MjAzO3M6NjA6ImluaV9nZXRcKFsnIl17MCwxfWZpbHRlclwuZGVmYXVsdF9mbGFnc1snIl17MCwxfVwpXCl7Zm9yZWFjaCI7aToyMDQ7czo1MDoiY2h1bmtfc3BsaXRcKGJhc2U2NF9lbmNvZGVcKGZyZWFkXChcJHtcJHtbJyJdezAsMX0iO2k6MjA1O3M6NTI6Ilwkc3RyPVsnIl17MCwxfTxoMT40MDNccytGb3JiaWRkZW48L2gxPjwhLS1ccyp0b2tlbjoiO2k6MjA2O3M6MzM6IjxcP3BocFxzK3JlbmFtZVwoWyciXXdzb1wucGhwWyciXSI7aToyMDc7czo2NDoiXCRbYS16QS1aMC05X10rL1wqLnsxLDEwfVwqL1xzKlwuXHMqXCRbYS16QS1aMC05X10rL1wqLnsxLDEwfVwqLyI7aToyMDg7czo1MToiQD9tYWlsXChcJG1vc0NvbmZpZ19tYWlsZnJvbSwgXCRtb3NDb25maWdfbGl2ZV9zaXRlIjtpOjIwOTtzOjk1OiJcJHQ9XCRzO1xzKlwkb1xzKj1ccypbJyJdWyciXTtccypmb3JcKFwkaT0wO1wkaTxzdHJsZW5cKFwkdFwpO1wkaVwrXCtcKXtccypcJG9ccypcLj1ccypcJHR7XCRpfSI7aToyMTA7czo0NzoibW1jcnlwdFwoXCRkYXRhLCBcJGtleSwgXCRpdiwgXCRkZWNyeXB0ID0gRkFMU0UiO2k6MjExO3M6MTU6InRuZWdhX3Jlc3VfcHR0aCI7aToyMTI7czo5OiJ0c29oX3B0dGgiO2k6MjEzO3M6MTI6IlJFUkVGRVJfUFRUSCI7aToyMTQ7czozMToid2ViaVwucnUvd2ViaV9maWxlcy9waHBfbGlibWFpbCI7aToyMTU7czo0MDoic3Vic3RyX2NvdW50XChnZXRlbnZcKFxcWyciXUhUVFBfUkVGRVJFUiI7aToyMTY7czozNzoiZnVuY3Rpb24gcmVsb2FkXChcKXtoZWFkZXJcKCJMb2NhdGlvbiI7aToyMTc7czoyNToiaW1nIHNyYz1bJyJdb3BlcmEwMDBcLnBuZyI7aToyMTg7czo0NjoiZWNob1xzKm1kNVwoXCRfUE9TVFxbWyciXXswLDF9Y2hlY2tbJyJdezAsMX1cXSI7aToyMTk7czozMzoiZVZhTFwoXHMqdHJpbVwoXHMqYmFTZTY0X2RlQ29EZVwoIjtpOjIyMDtzOjQyOiJmc29ja29wZW5cKFwkbVxbMFxdLFwkbVxbMTBcXSxcJF8sXCRfXyxcJG0iO2k6MjIxO3M6MTk6IlsnIl09Plwke1wke1snIl1cXHgiO2k6MjIyO3M6Mzg6InByZWdfcmVwbGFjZVwoWyciXS5VVEZcXC04OlwoLlwqXCkuVXNlIjtpOjIyMztzOjMwOiI6OlsnIl1cLnBocHZlcnNpb25cKFwpXC5bJyJdOjoiO2k6MjI0O3M6NDA6IkBzdHJlYW1fc29ja2V0X2NsaWVudFwoWyciXXswLDF9dGNwOi8vXCQiO2k6MjI1O3M6MTg6Ij09MFwpe2pzb25RdWl0XChcJCI7aToyMjY7czo0NjoibG9jXHMqPVxzKlsnIl17MCwxfTxcP2VjaG9ccytcJHJlZGlyZWN0O1xzKlw/PiI7aToyMjc7czoyODoiYXJyYXlcKFwkZW4sXCRlcyxcJGVmLFwkZWxcKSI7aToyMjg7czozNzoiWyciXXswLDF9LmMuWyciXXswLDF9XC5zdWJzdHJcKFwkdmJnLCI7aToyMjk7czoxODoiZnVja1xzK3lvdXJccyttYW1hIjtpOjIzMDtzOjg0OiJjYWxsX3VzZXJfZnVuY1woXHMqWyciXWFjdGlvblsnIl1ccypcLlxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFsiO2k6MjMxO3M6NTk6InN0cl9yZXBsYWNlXChcJGZpbmRccyosXHMqXCRmaW5kXHMqXC5ccypcJGh0bWxccyosXHMqXCR0ZXh0IjtpOjIzMjtzOjMzOiJmaWxlX2V4aXN0c1xzKlwoP1xzKlsnIl0vdmFyL3RtcC8iO2k6MjMzO3M6NDE6IiYmXHMqIWVtcHR5XChccypcJF9DT09LSUVcW1snIl1maWxsWyciXVxdIjtpOjIzNDtzOjIxOiJmdW5jdGlvblxzK2luRGlhcGFzb24iO2k6MjM1O3M6MzU6Im1ha2VfZGlyX2FuZF9maWxlXChccypcJHBhdGhfam9vbWxhIjtpOjIzNjtzOjQxOiJsaXN0aW5nX3BhZ2VcKFxzKm5vdGljZVwoXHMqWyciXXN5bWxpbmtlZCI7aToyMzc7czo2MjoibGlzdFxzKlwoXHMqXCRob3N0XHMqLFxzKlwkcG9ydFxzKixccypcJHNpemVccyosXHMqXCRleGVjX3RpbWUiO2k6MjM4O3M6NTI6ImZpbGVtdGltZVwoXCRiYXNlcGF0aFxzKlwuXHMqWyciXS9jb25maWd1cmF0aW9uXC5waHAiO2k6MjM5O3M6NTg6ImZ1bmN0aW9uXHMrcmVhZF9waWNcKFxzKlwkQVxzKlwpXHMqe1xzKlwkYVxzKj1ccypcJF9TRVJWRVIiO2k6MjQwO3M6NjQ6ImNoclwoXHMqXCR0YWJsZVxbXHMqXCRzdHJpbmdcW1xzKlwkaVxzKlxdXHMqXCpccypwb3dcKDY0XHMqLFxzKjEiO2k6MjQxO3M6Mzk6IlxdXHMqXCl7ZXZhbFwoXHMqXCRbYS16QS1aMC05X10rXFtccypcJCI7aToyNDI7czo1NDoiTG9jYXRpb246OmlzRmlsZVdyaXRhYmxlXChccypFbmNvZGVFeHBsb3Jlcjo6Z2V0Q29uZmlnIjtpOjI0MztzOjEzOiJieVxzK1NodW5jZW5nIjtpOjI0NDtzOjE0OiJ7ZXZhbFwoXCR7XCRzMiI7aToyNDU7czoxODoiZXZhbFwoXCRzMjFcKFwke1wkIjtpOjI0NjtzOjIxOiJSYW1aa2lFXHMrLVxzK2V4cGxvaXQiO2k6MjQ3O3M6NDc6IlsnIl1yZW1vdmVfc2NyaXB0c1snIl1ccyo9PlxzKmFycmF5XChbJyJdUmVtb3ZlIjtpOjI0ODtzOjI4OiJcJGJhY2tfY29ubmVjdF9wbFxzKj1ccypbJyJdIjtpOjI0OTtzOjQwOiJcJHNpdGVfcm9vdFwuXCRmaWxldW5wX2RpclwuXCRmaWxldW5wX2ZuIjtpOjI1MDtzOjI0OiJAcHJlZ19yZXBsYWNlXChbJyJdL2FkL2UiO2k6MjUxO3M6MjY6IjxiPlwkdWlkXHMqXChcJHVuYW1lXCk8L2I+IjtpOjI1MjtzOjExOiJGeDI5R29vZ2xlciI7aToyNTM7czo4OiJlbnZpcjBubiI7aToyNTQ7czo0NjoiYXJyYXlcKFsnIl1cKi9bJyJdLFsnIl0vXCpbJyJdXCksYmFzZTY0X2RlY29kZSI7aToyNTU7czoyODoiPFw/PVxzKkBwaHBfdW5hbWVcKFwpO1xzKlw/PiI7aToyNTY7czoxMToic1V4Q3Jld1xzK1YiO2k6MjU3O3M6MTY6IldhckJvdFxzK3NVeENyZXciO2k6MjU4O3M6NDM6ImV4ZWNcKFsnIl1jZFxzKy90bXA7Y3VybFxzKy1PXHMrWyciXVwuXCR1cmwiO2k6MjU5O3M6MTU6IkJhdGF2aTRccytTaGVsbCI7aToyNjA7czozNjoiQGV4dHJhY3RcKFwkX1JFUVVFU1RcW1snIl1meDI5c2hjb29rIjtpOjI2MTtzOjEwOiJUdVhfU2hhZG93IjtpOjI2MjtzOjQwOiI9QGZvcGVuXHMqXChbJyJdcGhwXC5pbmlbJyJdXHMqLFxzKlsnIl13IjtpOjI2MztzOjk6IkxlYmF5Q3JldyI7aToyNjQ7czo4NToiXCRoZWFkZXJzXHMqXC49XHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1xzKlsnIl1lTWFpbEFkZFsnIl1ccypcXSI7aToyNjU7czoxOToiYm9nZWxccyotXHMqZXhwbG9pdCI7aToyNjY7czo1OToiXFt1bmFtZVxdWyciXVxzKlwuXHMqcGhwX3VuYW1lXChccypcKVxzKlwuXHMqWyciXVxbL3VuYW1lXF0iO2k6MjY3O3M6MzI6IlxdXChcJF8xLFwkXzFcKVwpO2Vsc2V7XCRHTE9CQUxTIjtpOjI2ODtzOjE0OiJmaWxlOmZpbGU6Ly8vLyI7aToyNjk7czozMjoiZnVuY3Rpb25ccytNQ0xvZ2luXChcKVxzKntccypkaWUiO2k6MjcwO3M6NTU6IntlY2hvIFsnIl15ZXNbJyJdOyBleGl0O31lbHNle2VjaG8gWyciXW5vWyciXTsgZXhpdDt9fX0iO2k6MjcxO3M6Mzk6IjtcPz48XD89XCR7WyciXV9bJyJdXC5cJF99XFtbJyJdX1snIl1cXSI7aToyNzI7czo0MToiXCRhXFsxXF09PVsnIl1ieXBhc3NpcFsnIl1cKTtcJGM9c2VsZjo6YzEiO2k6MjczO3M6NDI6IlwkZGlyXC5bJyJdL1snIl1cLlwkZlwuWyciXS93cC1jb25maWdcLnBocCI7aToyNzQ7czoyMzoiZXZhbFwoWyciXXJldHVyblxzK2V2YWwiO2k6Mjc1O3M6OTA6ImZ3cml0ZVwoXCRbYS16QS1aMC05X10rLCJcXHhFRlxceEJCXFx4QkYiXC5pY29udlwoWyciXWdia1snIl0sWyciXXV0Zi04Ly9JR05PUkVbJyJdLFwkYm9keSI7aToyNzY7czo3MjoiZWNob1xzK1snIl1fX3N1Y2Nlc3NfX1snIl1ccypcLlxzKlwkTm93U3ViRm9sZGVyc1xzKlwuXHMqWyciXV9fc3VjY2Vzc19fIjtpOjI3NztzOjc3OiJvYl9zdGFydFwoXCk7XHMqdmFyX2R1bXBcKFwkX1BPU1RccyosXHMqXCRfR0VUXHMqLFxzKlwkX0NPT0tJRVxzKixccypcJF9GSUxFUyI7aToyNzg7czozNDoiZ2V0ZW52XCgiSFRUUF9IT1NUIlwpXC4nIH4gU2hlbGwgSSI7aToyNzk7czo0MzoiZXZhbC9cKlwqL1woImV2YWxcKGd6aW5mbGF0ZVwoYmFzZTY0X2RlY29kZSI7aToyODA7czoyNToiYXNzZXJ0XChcJFthLXpBLVowLTlfXStcKCI7aToyODE7czoxODoiXCRkZWZhY2VyPSdSZVpLMkxMIjtpOjI4MjtzOjE5OiI8JVxzKmV2YWxccytyZXF1ZXN0IjtpOjI4MztzOjMxOiJuZXdfdGltZVwoXCRwYXRoMmZpbGUsXCRHTE9CQUxTIjtpOjI4NDtzOjUzOiJcJHN0cj1zdHJfcmVwbGFjZVwoIlxbdFxkK1xdIlxzKixccyoiPFw/IixccypcJHJlc1wpOyI7aToyODU7czo5NjoiXCRfX2E9IlthLXpBLVowLTlfXSsiO1xzKlwkX19hXHMqPVxzKnN0cl9yZXBsYWNlXCgiW2EtekEtWjAtOV9dKyIsXHMqIlthLXpBLVowLTlfXSsiLFxzKlwkX19hXCk7IjtpOjI4NjtzOjQ0OiI8IS0tXHd7MzJ9LS0+PFw/cGhwXHMqQG9iX3N0YXJ0XChcKTtAaW5pX3NldCI7aToyODc7czo0MjoiaWZcKGlzc2V0XChcJF9HRVRcW3BocFxdXClcKVxzKntcJGZ1bmN0aW9uIjtpOjI4ODtzOjI4OiJcJHNcKCJ+XFtkaXNjdXpcXX5lIixcJF9QT1NUIjtpOjI4OTtzOjQxOiJQbGdTeXN0ZW1YY2FsZW5kYXJIZWxwZXI6OmdldEluc3RhbmNlXChcKSI7aToyOTA7czo2MjoiaXNfZGlyX2VtcHR5XChcJF9QT1NUXFtbJyJdZGlyZWN0b3J5WyciXVxdXClcKVxzKntccyplY2hvXHMrMTsiO2k6MjkxO3M6MzI6ImlmXChpc3NldFwoXCRfUE9TVFxbWyciXV9fYnNjb2RlIjtpOjI5MjtzOjM1OiJiYXNlNjRfZW5jb2RlXChjbGVhbl91cmxcKFwkX1NFUlZFUiI7aToyOTM7czozMDoiXCRfR0VUXFtbJyJdbW9kWyciXVxdPT1bJyJdMFhYIjtpOjI5NDtzOjQ0OiJcJGZvbGRlclwuWyciXS9wbGVhc2VfcmVuYW1lX1VOWklQRklSU1RcLnppcCI7aToyOTU7czo0MzoiQFwkc3RyaW5nc1woc3RyX3JvdDEzXCgncmlueVwob25mcjY0X3FycGJxciI7aToyOTY7czo2NzoiXCR0aGlzLT5zZXJ2ZXJccyo9XHMqWyciXWh0dHA6Ly9bJyJdXC5cJHRoaXMtPnNlcnZlclwuWyciXS9pbWcvXD9xPSI7aToyOTc7czo0NzoiZWNob1xzKiI8Y2VudGVyPjxiPkRvbmVccyo9PT5ccypcJHVzZXJmaWxlX25hbWUiO2k6Mjk4O3M6OTQ6ImZpbGVfZ2V0X2NvbnRlbnRzXChcJFthLXpBLVowLTlfXStcKTtccypbYS16QS1aMC05X10rXChbJyJdaHR0cHM6Ly9kbFwuZHJvcGJveHVzZXJjb250ZW50XC5jb20iO2k6Mjk5O3M6NjA6ImlmXChmaWxlX2V4aXN0c1woXCRuZXdQYXRoXClcKVxzKntccyplY2hvXHMqInB1Ymxpc2ggc3VjY2VzcyI7aTozMDA7czo1MzoiZnVuY3Rpb25ccytLaWxsTWVcKFwpXHMqe1xzKnVubGlua1woXHMqTXlGaWxlTmFtZVwoXCkiO2k6MzAxO3M6NjE6IjxcP3BocFxzKmVycm9yX3JlcG9ydGluZ1woRV9FUlJPUlwpO1xzKlwkcmVtb3RlX3BhdGg9Imh0dHA6Ly8iO2k6MzAyO3M6NDQ6ImVjaG9ccytwaHBfdW5hbWVcKFwpO1xzKkB1bmxpbmtcKF9fRklMRV9fXCk7IjtpOjMwMztzOjUxOiI8dGl0bGU+PFw/cGhwXHMrZWNob1xzK1wkc2hlbGxfdGl0bGU7XHMrXD8+PC90aXRsZT4iO2k6MzA0O3M6NTY6ImNoclwob3JkXChcJHN0clxbXCRpXF1cKVxzKlxeXHMqXCRrZXlcKTtccypldmFsXChcJGV2XCk7IjtpOjMwNTtzOjMwOiJcJHdwX193cD1cJHdwX193cFwoc3RyX3JlcGxhY2UiO2k6MzA2O3M6MTg6IjxcP3BocFxzKlwkd3BfX3dwPSI7aTozMDc7czoyNDoiPFw/cGhwXHMqZXZhbFwoWyciXVxceDY1IjtpOjMwODtzOjgzOiJAcHJlZ19yZXBsYWNlXChbJyJdL1woXC5cKlwpL2VbJyJdXHMqLFxzKkBcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aTozMDk7czoyMjoiPHRpdGxlPlczTGNvbWU8L3RpdGxlPiI7aTozMTA7czo3NToiaWZcKHN0cmlzdHJcKFwkZmlsZXNcW1wkaVxdLFxzKlsnIl1waHBbJyJdXClcKVxzKntccypcJHRpbWVccyo9XHMqZmlsZW10aW1lIjtpOjMxMTtzOjc0OiJcKVwpe1xzKmluY2x1ZGVcKGdldGN3ZFwoXClcLlsnIl0vW2EtekEtWjAtOV9dK1wucGhwWyciXVwpO1xzKmV4aXQ7fVxzKlw/PiI7aTozMTI7czoyOToiPHRpdGxlPlsnIl1cLnVjZmlyc3RcKFwka2V5XCkiO2k6MzEzO3M6MTg6IjxcP3BocFxzKi9cKlxzKldTTyI7aTozMTQ7czozMDoiZnVuY3Rpb25fZXhpc3RzXCgiYzk5X3Nlc3NfcHV0IjtpOjMxNTtzOjIxOiIzeHAxcjNccypDeWJlclxzKkFybXkiO2k6MzE2O3M6Mzg6ImZpbGVfZ2V0X2NvbnRlbnRzXCh+XHMqYmFzZTY0X2RlY29kZVwoIjtpOjMxNztzOjQ3OiJoZXhkZWNcKHN1YnN0clwobWQ1XChcJF9TRVJWRVJcW1snIl1SRVFVRVNUX1VSSSI7aTozMTg7czo4Njoicm9vdF9wYXRoPXN1YnN0clwoXCRhYnNvbHV0ZXBhdGgsMCxzdHJwb3NcKFwkYWJzb2x1dGVwYXRoLFwkbG9jYWxwYXRoXClcKTtpbmNsdWRlX29uY2UiO2k6MzE5O3M6NTU6IlwkX1NFUlZFUlxbIlJFTU9URV9BRERSIlxdO2lmXChcKHByZWdfbWF0Y2hcKCIvNjlcLjQyXC4iO2k6MzIwO3M6NDE6IjxcP3BocFxzKmlmXChpc3NldFwoXCRfR0VUXFtwaHBcXVwpXClccyp7IjtpOjMyMTtzOjU5OiJcKVwpe2lmXChpc3NldFwoXCRfRklMRVNcW1snIl1pbVsnIl1cXVwpXCl7XCRkaW09Z2V0Y3dkXChcKSI7aTozMjI7czozMzoiY2xhc3NccytKU1lTT1BFUkFUSU9OX1NldFBhc3N3b3JkIjtpOjMyMztzOjQ3OiJcKTthcnJheV9maWx0ZXJcKFwkbWNkYXRhXHMqLFxzKmJhc2U2NF9kZWNvZGVcKCI7aTozMjQ7czo1OToiPFw/cGhwIGlmXChcJG1lc3NhZ2VcKSBlY2hvICI8cD5cJG1lc3NhZ2U8L3A+IjsgXD8+XHMqPGZvcm0iO2k6MzI1O3M6ODM6InRvdWNoXChkaXJuYW1lXChfX0ZJTEVfX1wpLFxzKlwkdGltZVwpO3RvdWNoXChcJF9TRVJWRVJcW1snIl1TQ1JJUFRfRklMRU5BTUVbJyJdXF0sIjtpOjMyNjtzOjE3OiI8dGl0bGU+RmFrZVNlbmRlciI7aTozMjc7czo5NDoiXCRDb25mXHMqPVxzKkA/ZmlsZV9nZXRfY29udGVudHNcKFwkcGdcLlsnIl0vXD9kPVsnIl1ccypcLlxzKlwkX1NFUlZFUlxbWyciXUhUVFBfSE9TVFsnIl1cXVwpOyI7aTozMjg7czo2MDoiPFw/XHMqaW5jbHVkZVwoWyciXVsnIl1cLlwkZHJvb3RcLlsnIl0vYml0cml4L2ltYWdlcy9pYmxvY2svIjtpOjMyOTtzOjY4OiJcJGZpbGVcW1wka2V5XF1ccyo9XHMqXCRleFxbMFxdXC5cJGxpbmtcLlsnIl08L2JvZHk+WyciXVwuXCRleFxbMVxdOyI7aTozMzA7czoxNTI6IlwkXHd7MSw0NX1cW1wkXHd7MSw0NX1cXT1jaHJcKG9yZFwoXCRcd3sxLDQ1fVxbXCRcd3sxLDQ1fVxdXClcXm9yZFwoXCRcd3sxLDQ1fVxbXCRcd3sxLDQ1fSVcJFx3ezEsNDV9XF1cKVwpO3JldHVyblxzKlwkXHd7MSw0NX07fXByaXZhdGUgc3RhdGljIGZ1bmN0aW9uIjtpOjMzMTtzOjY2OiJpZlxzKlwoXHMqbW92ZV91cGxvYWRlZF9maWxlXChccypcJG5hendhX3BsaWtccyosXHMqXCR1cGxvYWRmaWxlXCkiO2k6MzMyO3M6MzY6ImlmXChzdHJzdHJcKFwkdGVtcFN0cixbJyJdLy9maWxlIGVuZCI7aTozMzM7czo2ODoidHJpbVwoXHMqcmVtb3ZlQk9NXChccypfZmlsZV9nZXRfY29udGVudHNcKFxzKkFQSV9VUkxccypcKVxzKlwpXHMqXCkiO2k6MzM0O3M6NzY6IlwkXHd7MSw0NX1ccyo9XHMqc3RyX3JlcGxhY2VcKFxzKlsnIl1cW3QxXF1bJyJdXHMqLFxzKlsnIl08LnBocFsnIl1ccyosXHMqXCQiO2k6MzM1O3M6ODE6IlwqL1wkXHd7MSw0NX1ccyo9XHMqQFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtccypcd3sxLDQ1fVxzKlxdOyI7aTozMzY7czoxMjY6IlxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilccypcKD9ccypAP1wkX1BPU1RccypcW1xzKlsnIl0uKz9bJyJdXHMqXF1ccypcLlxzKiJccyoyXHMqPlxzKiYxXHMqWyciXSI7aTozMzc7czo4ODoiXGIoZnRwX2V4ZWN8c3lzdGVtfHNoZWxsX2V4ZWN8cGFzc3RocnV8cG9wZW58cHJvY19vcGVuKVxzKlwoP1xzKlsnIl11bmFtZVxzKy1hWyciXVxzKlwpPyI7aTozMzg7czo5NToiQD9hc3NlcnRccypcKD9ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxzKlxbXHMqWyciXXswLDF9Lis/WyciXXswLDF9XHMqXF1ccyoiO2k6MzM5O3M6Mjg6InBocFxzK1snIl1ccypcLlxzKlwkd3NvX3BhdGgiO2k6MzQwO3M6NTI6ImZpbmRccysvXHMrLW5hbWVccytcLnNzaFxzKz5ccytcJGRpci9zc2hrZXlzL3NzaGtleXMiO2k6MzQxO3M6NDU6InN5c3RlbVxzKlwoP1xzKlsnIl17MCwxfXdob2FtaVsnIl17MCwxfVxzKlwpPyI7aTozNDI7czo4ODoiY3VybF9zZXRvcHRccypcKFxzKlwkY2hccyosXHMqQ1VSTE9QVF9VUkxccyosXHMqWyciXXswLDF9aHR0cDovL1wkaG9zdDpcZCtbJyJdezAsMX1ccypcKSI7aTozNDM7czozNToiXGJldmFsXChccypcJFxzKntccypcJFthLXpBLVowLTlfXSsiO2k6MzQ0O3M6MTUzOiJpZlwoXHMqaW5fYXJyYXlcKFxzKlwkX1NFUlZFUlxbWyciXVJFTU9URV9BRERSWyciXVxdXHMqLFxzKlwkW2EtekEtWjAtOV9dK1wpXClccypcJFthLXpBLVowLTlfXSs9XCRbYS16QS1aMC05X10rXFtyYW5kXCgwLGNvdW50XChcJFthLXpBLVowLTlfXStcKS0xXClcXTsiO2k6MzQ1O3M6MjA6ImFycjJodG1sXChcJF9SRVFVRVNUIjtpOjM0NjtzOjYyOiJcJG1kZXRhaWxzXHMqXC49XHMqWyciXTwhLS1EZXJlZ2lzdGVyXChcKVxzKkRlZmF1bHRccypXaWRnZXRzOiI7aTozNDc7czo1NDoid3BfcmVtb3RlX3JldHJpZXZlX2JvZHlcKFxzKndwX3JlbW90ZV9nZXRcKFxzKlwkanF1ZXJ5IjtpOjM0ODtzOjExMjoiQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXVthLXpBLVowLTlfXStbJyJdXF1ccypcKVxzKlwpO1xzKlsnIl1ccypcKTtccypcJFx3ezEsNDV9XChccypcKSI7aTozNDk7czo0NjoianNfaW5mZWN0aW9uXHMqPVxzKmFycmF5X3JldmVyc2VcKFxzKnN0cl9zcGxpdCI7aTozNTA7czo0MjoiXCRob3N0XC5bJyJddWlbJyJdXC5bJyJdanF1ZXJ5XC5vcmcvanF1ZXJ5IjtpOjM1MTtzOjIwOiJoYW5kbGVfYm90X2NtZF9zaGVsbCI7aTozNTI7czozODoie2VjaG9ccypbJyJdMjAwWyciXVxzKjtccypleGl0XHMqO1xzKn0iO2k6MzUzO3M6NDI6ImV2YWxcKFxzKktleVJlZ2lzdHJhdGlvblwoXHMqS2V5R2VuZXJhdGlvbiI7aTozNTQ7czoxMToiZXZhbFwoXCR7XCQiO30="));
$gX_FlexDBShe = unserialize(base64_decode("YTozNjY6e2k6MDtzOjQ4OiJcJFx3ezEsNDV9XHMqPVxzKlwoXHMqLVxzKlwoXHMqLVxkK1wpXHMqXCtccypcZCsiO2k6MTtzOjgwOiJyZXR1cm5ccypcJFx3ezEsNDV9XHMqXF5ccypzdHJfcmVwZWF0XHMqXChccypcJFx3ezEsNDV9XHMqLFxzKmNlaWxccypcKFxzKnN0cmxlbiI7aToyO3M6NzE6Ij1bJyJdYmFzZTY0X2RlY29kZVsnIl1ccyo7XHMqcmV0dXJuXHMqXCRcd3sxLDQ1fVxzKlwoXHMqXCRcd3sxLDQ1fVxzKlwpIjtpOjM7czoxNjoiY2xhc3NccypXYXBDbGljayI7aTo0O3M6NjE6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlsnIl17MCwxfXppcDoiO2k6NTtzOjY2OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKFxzKlsnIl17MCwxfXppcDoiO2k6NjtzOjY4OiJmaWxlX2dldF9jb250ZW50c1woU1JWX05BTUVccypcLlxzKlsnIl1cP2FjdGlvbj1nZXRfc2l0ZXMmbm9kYV9uYW1lPSI7aTo3O3M6NDA6IkxvY2F0aW9uOlxzKlthLXpBLVowLTlfXStcLmRvY3VtZW50XC5leGUiO2k6ODtzOjQwOiJpZlwoIXByZWdfbWF0Y2hcKFsnIl0vSGFja2VkIGJ5L2lbJyJdLFwkIjtpOjk7czo5OiJCeVxzK0FtIXIiO2k6MTA7czoxOToiQ29udGVudC1UeXBlOlxzKlwkXyI7aToxMTtzOjQwOiJldmFsXHMqXCg/XHMqZ3ppbmZsYXRlXHMqXCg/XHMqc3RyX3JvdDEzIjtpOjEyO3M6MTA5OiJpZlxzKlwoXHMqaXNfY2FsbGFibGVccypcKD9ccypbJyJdezAsMX1cYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pWyciXXswLDF9XHMqXCk/IjtpOjEzO3M6Mjk6ImV2YWxccypcKD9ccypnZXRfb3B0aW9uXHMqXCg/IjtpOjE0O3M6OTU6ImFkZF9maWx0ZXJccypcKD9ccypbJyJdezAsMX10aGVfY29udGVudFsnIl17MCwxfVxzKixccypbJyJdezAsMX1fYmxvZ2luZm9bJyJdezAsMX1ccyosXHMqLis/XCk/IjtpOjE1O3M6MzI6ImlzX3dyaXRhYmxlXHMqXCg/XHMqWyciXS92YXIvdG1wIjtpOjE2O3M6MjY6InN5bWxpbmtccypcKD9ccypbJyJdL2hvbWUvIjtpOjE3O3M6MTAwOiJpc3NldFwoXHMqQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXVthLXpBLVowLTlfXStbJyJdXF1cKVxzKm9yXHMqZGllXCg/Lis/XCk/IjtpOjE4O3M6NDk6Imd6dW5jb21wcmVzc1xzKlwoP1xzKnN1YnN0clxzKlwoP1xzKmJhc2U2NF9kZWNvZGUiO2k6MTk7czo5OiJcJF9fX1xzKj0iO2k6MjA7czo0MDoiaWZccypcKFxzKnByZWdfbWF0Y2hccypcKFxzKlsnIl1cI3lhbmRleCI7aToyMTtzOjcxOiJAc2V0Y29va2llXChbJyJdbVsnIl0sXHMqWyciXVthLXpBLVowLTlfXStbJyJdLFxzKnRpbWVcKFwpXHMqXCtccyo4NjQwMCI7aToyMjtzOjI4OiJlY2hvXHMrWyciXW9cLmtcLlsnIl07XHMqXD8+IjtpOjIzO3M6MzM6InN5bWJpYW5cfG1pZHBcfHdhcFx8cGhvbmVcfHBvY2tldCI7aToyNDtzOjQ4OiJmdW5jdGlvblxzKmNobW9kX1JccypcKFxzKlwkcGF0aFxzKixccypcJHBlcm1ccyoiO2k6MjU7czozODoiZXZhbFxzKlwoXHMqZ3ppbmZsYXRlXHMqXChccypzdHJfcm90MTMiO2k6MjY7czoyMToiZXZhbFxzKlwoXHMqc3RyX3JvdDEzIjtpOjI3O3M6MzA6InByZWdfcmVwbGFjZVxzKlwoXHMqWyciXS9cLlwqLyI7aToyODtzOjU4OiJcJG1haWxlclxzKj1ccypcJF9QT1NUXFtccypbJyJdezAsMX14X21haWxlclsnIl17MCwxfVxzKlxdIjtpOjI5O3M6NjM6InByZWdfcmVwbGFjZVxzKlwoXHMqQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aTozMDtzOjM1OiJlY2hvXHMrWyciXXswLDF9aW5zdGFsbF9va1snIl17MCwxfSI7aTozMTtzOjE2OiJTcGFtXHMrY29tcGxldGVkIjtpOjMyO3M6NDQ6ImFycmF5XChccypbJyJdR29vZ2xlWyciXVxzKixccypbJyJdU2x1cnBbJyJdIjtpOjMzO3M6MzI6IjxoMT40MDMgRm9yYmlkZGVuPC9oMT48IS0tIHRva2VuIjtpOjM0O3M6MjA6Ii9lWyciXVxzKixccypbJyJdXFx4IjtpOjM1O3M6MzU6InBocF9bJyJdXC5cJGV4dFwuWyciXVwuZGxsWyciXXswLDF9IjtpOjM2O3M6MTc6Im14MlwuaG90bWFpbFwuY29tIjtpOjM3O3M6MzY6InByZWdfcmVwbGFjZVwoXHMqWyciXWVbJyJdLFsnIl17MCwxfSI7aTozODtzOjUzOiJmb3BlblwoWyciXXswLDF9XC5cLi9cLlwuL1wuXC4vWyciXXswLDF9XC5cJGZpbGVwYXRocyI7aTozOTtzOjUxOiJcJGRhdGFccyo9XHMqYXJyYXlcKFsnIl17MCwxfXRlcm1pbmFsWyciXXswLDF9XHMqPT4iO2k6NDA7czoyOToiXCRiXHMqPVxzKm1kNV9maWxlXChcJGZpbGViXCkiO2k6NDE7czozMzoicG9ydGxldHMvZnJhbWV3b3JrL3NlY3VyaXR5L2xvZ2luIjtpOjQyO3M6MzE6IlwkZmlsZWJccyo9XHMqZmlsZV9nZXRfY29udGVudHMiO2k6NDM7czoxMDQ6InNpdGVfZnJvbT1bJyJdezAsMX1cLlwkX1NFUlZFUlxbWyciXXswLDF9SFRUUF9IT1NUWyciXXswLDF9XF1cLlsnIl17MCwxfSZzaXRlX2ZvbGRlcj1bJyJdezAsMX1cLlwkZlxbMVxdIjtpOjQ0O3M6NTY6IndoaWxlXChjb3VudFwoXCRsaW5lc1wpPlwkY29sX3phcFwpIGFycmF5X3BvcFwoXCRsaW5lc1wpIjtpOjQ1O3M6ODU6Ilwkc3RyaW5nXHMqPVxzKlwkX1NFU1NJT05cW1snIl17MCwxfWRhdGFfYVsnIl17MCwxfVxdXFtbJyJdezAsMX1udXR6ZXJuYW1lWyciXXswLDF9XF0iO2k6NDY7czo0MToiaWYgXCghc3RycG9zXChcJHN0cnNcWzBcXSxbJyJdezAsMX08XD9waHAiO2k6NDc7czoyNToiXCRpc2V2YWxmdW5jdGlvbmF2YWlsYWJsZSI7aTo0ODtzOjE0OiJEYXZpZFxzK0JsYWluZSI7aTo0OTtzOjQ3OiJpZiBcKGRhdGVcKFsnIl17MCwxfWpbJyJdezAsMX1cKVxzKi1ccypcJG5ld3NpZCI7aTo1MDtzOjE1OiI8IS0tXHMranMtdG9vbHMiO2k6NTE7czozNDoiaWZcKEBwcmVnX21hdGNoXChzdHJ0clwoWyciXXswLDF9LyI7aTo1MjtzOjM3OiJfWyciXXswLDF9XF1cWzJcXVwoWyciXXswLDF9TG9jYXRpb246IjtpOjUzO3M6Mjg6IlwkX1BPU1RcW1snIl17MCwxfXNtdHBfbG9naW4iO2k6NTQ7czoyODoiaWZccypcKEBpc193cml0YWJsZVwoXCRpbmRleCI7aTo1NTtzOjg2OiJAaW5pX3NldFxzKlwoWyciXXswLDF9aW5jbHVkZV9wYXRoWyciXXswLDF9LFsnIl17MCwxfWluaV9nZXRccypcKFsnIl17MCwxfWluY2x1ZGVfcGF0aCI7aTo1NjtzOjM4OiJaZW5kXHMrT3B0aW1pemF0aW9uXHMrdmVyXHMrMVwuMFwuMFwuMSI7aTo1NztzOjYyOiJcJF9TRVNTSU9OXFtbJyJdezAsMX1kYXRhX2FbJyJdezAsMX1cXVxbXCRuYW1lXF1ccyo9XHMqXCR2YWx1ZSI7aTo1ODtzOjQyOiJpZlxzKlwoZnVuY3Rpb25fZXhpc3RzXChbJyJdc2Nhbl9kaXJlY3RvcnkiO2k6NTk7czo2NzoiYXJyYXlcKFxzKlsnIl1oWyciXVxzKixccypbJyJddFsnIl1ccyosXHMqWyciXXRbJyJdXHMqLFxzKlsnIl1wWyciXSI7aTo2MDtzOjM1OiJcJGNvdW50ZXJVcmxccyo9XHMqWyciXXswLDF9aHR0cDovLyI7aTo2MTtzOjEwNDoiZm9yXChcJFthLXpBLVowLTlfXSs9XGQrO1wkW2EtekEtWjAtOV9dKzxcZCs7XCRbYS16QS1aMC05X10rLT1cZCtcKXtpZlwoXCRbYS16QS1aMC05X10rIT1cZCtcKVxzKmJyZWFrO30iO2k6NjI7czozNjoiaWZcKEBmdW5jdGlvbl9leGlzdHNcKFsnIl17MCwxfWZyZWFkIjtpOjYzO3M6MzM6Ilwkb3B0XHMqPVxzKlwkZmlsZVwoQD9cJF9DT09LSUVcWyI7aTo2NDtzOjM4OiJwcmVnX3JlcGxhY2VcKFwpe3JldHVyblxzK19fRlVOQ1RJT05fXyI7aTo2NTtzOjM5OiJpZlxzKlwoY2hlY2tfYWNjXChcJGxvZ2luLFwkcGFzcyxcJHNlcnYiO2k6NjY7czozNjoicHJpbnRccytbJyJdezAsMX1kbGVfbnVsbGVkWyciXXswLDF9IjtpOjY3O3M6NjM6ImlmXChtYWlsXChcJGVtYWlsXFtcJGlcXSxccypcJHN1YmplY3QsXHMqXCRtZXNzYWdlLFxzKlwkaGVhZGVycyI7aTo2ODtzOjEyOiJUZWFNXHMrTW9zVGEiO2k6Njk7czoxNDoiWyciXXswLDF9RFplMXIiO2k6NzA7czoxNToicGFja1xzKyJTbkE0eDgiIjtpOjcxO3M6MzI6IlwkX1Bvc3RcW1snIl17MCwxfVNTTlsnIl17MCwxfVxdIjtpOjcyO3M6Mjc6IkV0aG5pY1xzK0FsYmFuaWFuXHMrSGFja2VycyI7aTo3MztzOjk6IkJ5XHMrRFoyNyI7aTo3NDtzOjc0OiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChbJyJdezAsMX1jbWRcLmV4ZSI7aTo3NTtzOjE1OiJBdXRvXHMqWHBsb2l0ZXIiO2k6NzY7czo5OiJieVxzK2cwMG4iO2k6Nzc7czoyODoiaWZcKFwkbzwxNlwpe1wkaFxbXCRlXFtcJG9cXSI7aTo3ODtzOjk0OiJpZlwoaXNfZGlyXChcJHBhdGhcLlsnIl17MCwxfS93cC1jb250ZW50WyciXXswLDF9XClccytBTkRccytpc19kaXJcKFwkcGF0aFwuWyciXXswLDF9L3dwLWFkbWluIjtpOjc5O3M6NjA6ImlmXHMqXChccypmaWxlX3B1dF9jb250ZW50c1xzKlwoXHMqXCRpbmRleF9wYXRoXHMqLFxzKlwkY29kZSI7aTo4MDtzOjUxOiJAYXJyYXlcKFxzKlwoc3RyaW5nXClccypzdHJpcHNsYXNoZXNcKFxzKlwkX1JFUVVFU1QiO2k6ODE7czo0MDoic3RyX3JlcGxhY2VccypcKFxzKlsnIl17MCwxfS9wdWJsaWNfaHRtbCI7aTo4MjtzOjQxOiJpZlwoXHMqaXNzZXRcKFxzKlwkX1JFUVVFU1RcW1snIl17MCwxfWNpZCI7aTo4MztzOjE1OiJjYXRhdGFuXHMrc2l0dXMiO2k6ODQ7czo4NToiL2luZGV4XC5waHBcP29wdGlvbj1jb21famNlJnRhc2s9cGx1Z2luJnBsdWdpbj1pbWdtYW5hZ2VyJmZpbGU9aW1nbWFuYWdlciZ2ZXJzaW9uPVxkKyI7aTo4NTtzOjM3OiJzZXRjb29raWVcKFxzKlwkelxbMFxdXHMqLFxzKlwkelxbMVxdIjtpOjg2O3M6MzI6IlwkU1xbXCRpXCtcK1xdXChcJFNcW1wkaVwrXCtcXVwoIjtpOjg3O3M6MzI6IlxbXCRvXF1cKTtcJG9cK1wrXCl7aWZcKFwkbzwxNlwpIjtpOjg4O3M6ODE6InR5cGVvZlxzKlwoZGxlX2FkbWluXClccyo9PVxzKlsnIl17MCwxfXVuZGVmaW5lZFsnIl17MCwxfVxzKlx8XHxccypkbGVfYWRtaW5ccyo9PSI7aTo4OTtzOjM2OiJjcmVhdGVfZnVuY3Rpb25cKHN1YnN0clwoMiwxXCksXCRzXCkiO2k6OTA7czo2MDoicGx1Z2lucy9zZWFyY2gvcXVlcnlcLnBocFw/X19fX3BnZmE9aHR0cCUzQSUyRiUyRnd3d1wuZ29vZ2xlIjtpOjkxO3M6MzY6InJldHVyblxzK2Jhc2U2NF9kZWNvZGVcKFwkYVxbXCRpXF1cKSI7aTo5MjtzOjUxOiJcJGZpbGVcKEA/XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6OTM7czoyNzoiY3VybF9pbml0XChccypiYXNlNjRfZGVjb2RlIjtpOjk0O3M6MzI6ImV2YWxcKFsnIl1cPz5bJyJdXC5iYXNlNjRfZGVjb2RlIjtpOjk1O3M6Mjk6IlsnIl1bJyJdXHMqXC5ccypCQXNlNjRfZGVDb0RlIjtpOjk2O3M6Mjg6IlsnIl1bJyJdXHMqXC5ccypnelVuY29NcHJlU3MiO2k6OTc7czoxOToiZ3JlcFxzKy12XHMrY3JvbnRhYiI7aTo5ODtzOjM0OiJjcmMzMlwoXHMqXCRfUE9TVFxbXHMqWyciXXswLDF9Y21kIjtpOjk5O3M6MTk6IlwkYmtleXdvcmRfYmV6PVsnIl0iO2k6MTAwO3M6NjA6ImZpbGVfZ2V0X2NvbnRlbnRzXChiYXNlbmFtZVwoXCRfU0VSVkVSXFtbJyJdezAsMX1TQ1JJUFRfTkFNRSI7aToxMDE7czo1NDoiXHMqWyciXXswLDF9cm9va2VlWyciXXswLDF9XHMqLFxzKlsnIl17MCwxfXdlYmVmZmVjdG9yIjtpOjEwMjtzOjQ4OiJccypbJyJdezAsMX1zbHVycFsnIl17MCwxfVxzKixccypbJyJdezAsMX1tc25ib3QiO2k6MTAzO3M6MjA6ImV2YWxccypcKFxzKlRQTF9GSUxFIjtpOjEwNDtzOjg4OiJAP2FycmF5X2RpZmZfdWtleVwoXHMqQD9hcnJheVwoXHMqXChzdHJpbmdcKVxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpIjtpOjEwNTtzOjEwNToiXCRwYXRoXHMqPVxzKlwkX1NFUlZFUlxbXHMqWyciXXswLDF9RE9DVU1FTlRfUk9PVFsnIl17MCwxfVxzKlxdXHMqXC5ccypbJyJdezAsMX0vaW1hZ2VzL3N0b3JpZXMvWyciXXswLDF9IjtpOjEwNjtzOjg5OiJcJHNhcGVfb3B0aW9uXFtccypbJyJdezAsMX1mZXRjaF9yZW1vdGVfdHlwZVsnIl17MCwxfVxzKlxdXHMqPVxzKlsnIl17MCwxfXNvY2tldFsnIl17MCwxfSI7aToxMDc7czo5NDoiZmlsZV9wdXRfY29udGVudHNcKFxzKlwkbmFtZVxzKixccypiYXNlNjRfZGVjb2RlXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aToxMDg7czo4MjoiZXJlZ19yZXBsYWNlXChbJyJdezAsMX0lNUMlMjJbJyJdezAsMX1ccyosXHMqWyciXXswLDF9JTIyWyciXXswLDF9XHMqLFxzKlwkbWVzc2FnZSI7aToxMDk7czo5MToiXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl17MCwxfXVyWyciXXswLDF9XF1cKVwpXHMqXCRtb2RlXHMqXHw9XHMqMDQwMCI7aToxMTA7czo0MToiL3BsdWdpbnMvc2VhcmNoL3F1ZXJ5XC5waHBcP19fX19wZ2ZhPWh0dHAiO2k6MTExO3M6NDk6IkA/ZmlsZV9wdXRfY29udGVudHNcKFxzKlwkdGhpcy0+ZmlsZVxzKixccypzdHJyZXYiO2k6MTEyO3M6NDg6InByZWdfbWF0Y2hfYWxsXChccypbJyJdXHxcKFwuXCpcKTxcXCEtLSBqcy10b29scyI7aToxMTM7czozMDoiaGVhZGVyXChbJyJdezAsMX1yOlxzKm5vXHMrY29tIjtpOjExNDtzOjc1OiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChbJyJdbHNccysvdmFyL21haWwiO2k6MTE1O3M6MjY6IlwkZG9yX2NvbnRlbnQ9cHJlZ19yZXBsYWNlIjtpOjExNjtzOjIzOiJfX3VybF9nZXRfY29udGVudHNcKFwkbCI7aToxMTc7czo1MzoiXCRHTE9CQUxTXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cKFxzKk5VTEwiO2k6MTE4O3M6NjI6InVuYW1lXF1bJyJdezAsMX1ccypcLlxzKnBocF91bmFtZVwoXClccypcLlxzKlsnIl17MCwxfVxbL3VuYW1lIjtpOjExOTtzOjMzOiJAXCRmdW5jXChcJGNmaWxlLCBcJGNkaXJcLlwkY25hbWUiO2k6MTIwO3M6Mzc6IlxiZXZhbFwoXHMqXCRbYS16QS1aMC05X10rXChccypcJDxhbWMiO2k6MTIxO3M6NzE6IlwkX1xbXHMqXGQrXHMqXF1cKFxzKlwkX1xbXHMqXGQrXHMqXF1cKFwkX1xbXHMqXGQrXHMqXF1cKFxzKlwkX1xbXHMqXGQrIjtpOjEyMjtzOjI5OiJlcmVnaVwoXHMqc3FsX3JlZ2Nhc2VcKFxzKlwkXyI7aToxMjM7czo0MDoiXCNVc2VbJyJdezAsMX1ccyosXHMqZmlsZV9nZXRfY29udGVudHNcKCI7aToxMjQ7czoyMDoibWtkaXJcKFxzKlsnIl0vaG9tZS8iO2k6MTI1O3M6MjA6ImZvcGVuXChccypbJyJdL2hvbWUvIjtpOjEyNjtzOjM2OiJcJHVzZXJfYWdlbnRfdG9fZmlsdGVyXHMqPVxzKmFycmF5XCgiO2k6MTI3O3M6NDQ6ImZpbGVfcHV0X2NvbnRlbnRzXChbJyJdezAsMX1cLi9saWJ3b3JrZXJcLnNvIjtpOjEyODtzOjY0OiJcIyEvYmluL3NobmNkXHMrWyciXXswLDF9WyciXXswLDF9XC5cJFNDUFwuWyciXXswLDF9WyciXXswLDF9bmlmIjtpOjEyOTtzOjgyOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChccypbJyJdezAsMX1hdFxzK25vd1xzKy1mIjtpOjEzMDtzOjMzOiJjcm9udGFiXHMrLWxcfGdyZXBccystdlxzK2Nyb250YWIiO2k6MTMxO3M6MTQ6IkRhdmlkXHMqQmxhaW5lIjtpOjEzMjtzOjIzOiJleHBsb2l0LWRiXC5jb20vc2VhcmNoLyI7aToxMzM7czozNjoiZmlsZV9wdXRfY29udGVudHNcKFxzKlsnIl17MCwxfS9ob21lIjtpOjEzNDtzOjYwOiJtYWlsXChccypcJE1haWxUb1xzKixccypcJE1lc3NhZ2VTdWJqZWN0XHMqLFxzKlwkTWVzc2FnZUJvZHkiO2k6MTM1O3M6MTE3OiJcJGNvbnRlbnRccyo9XHMqaHR0cF9yZXF1ZXN0XChbJyJdezAsMX1odHRwOi8vWyciXXswLDF9XHMqXC5ccypcJF9TRVJWRVJcW1snIl17MCwxfVNFUlZFUl9OQU1FWyciXXswLDF9XF1cLlsnIl17MCwxfS8iO2k6MTM2O3M6Nzg6IiFmaWxlX3B1dF9jb250ZW50c1woXHMqXCRkYm5hbWVccyosXHMqXCR0aGlzLT5nZXRJbWFnZUVuY29kZWRUZXh0XChccypcJGRibmFtZSI7aToxMzc7czo0NDoic2NyaXB0c1xbXHMqZ3p1bmNvbXByZXNzXChccypiYXNlNjRfZGVjb2RlXCgiO2k6MTM4O3M6NzI6InNlbmRfc210cFwoXHMqXCRlbWFpbFxbWyciXXswLDF9YWRyWyciXXswLDF9XF1ccyosXHMqXCRzdWJqXHMqLFxzKlwkdGV4dCI7aToxMzk7czo1MjoiPVwkZmlsZVwoQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aToxNDA7czo1MjoidG91Y2hcKFxzKlsnIl17MCwxfVwkYmFzZXBhdGgvY29tcG9uZW50cy9jb21fY29udGVudCI7aToxNDE7czoyNzoiXChbJyJdXCR0bXBkaXIvc2Vzc19mY1wubG9nIjtpOjE0MjtzOjM1OiJmaWxlX2V4aXN0c1woXHMqWyciXS90bXAvdG1wLXNlcnZlciI7aToxNDM7czo0OToibWFpbFwoXHMqXCRyZXRvcm5vXHMqLFxzKlwkYXN1bnRvXHMqLFxzKlwkbWVuc2FqZSI7aToxNDQ7czo4MjoiXCRVUkxccyo9XHMqXCR1cmxzXFtccypyYW5kXChccyowXHMqLFxzKmNvdW50XChccypcJHVybHNccypcKVxzKi1ccyoxXClccypcXVwucmFuZCI7aToxNDU7czo0MDoiX19maWxlX2dldF91cmxfY29udGVudHNcKFxzKlwkcmVtb3RlX3VybCI7aToxNDY7czoxMzoiPWJ5XHMrRFJBR09OPSI7aToxNDc7czo5ODoic3Vic3RyXChccypcJHN0cmluZzJccyosXHMqc3RybGVuXChccypcJHN0cmluZzJccypcKVxzKi1ccyo5XHMqLFxzKjlcKVxzKj09XHMqWyciXXswLDF9XFtsLHI9MzAyXF0iO2k6MTQ4O3M6MzM6IlxbXF1ccyo9XHMqWyciXVJld3JpdGVFbmdpbmVccytvbiI7aToxNDk7czo4MToiZndyaXRlXChccypcJGZccyosXHMqZ2V0X2Rvd25sb2FkXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIjtpOjE1MDtzOjQ3OiJ0YXJccystY3pmXHMrIlxzKlwuXHMqXCRGT1JNe3Rhcn1ccypcLlxzKiJcLnRhciI7aToxNTE7czoxMToic2NvcGJpblsnIl0iO2k6MTUyO3M6NjY6IjxkaXZccytpZD1bJyJdbGluazFbJyJdPjxidXR0b24gb25jbGljaz1bJyJdcHJvY2Vzc1RpbWVyXChcKTtbJyJdPiI7aToxNTM7czozNToiPGd1aWQ+PFw/cGhwXHMrZWNob1xzK1wkY3VycmVudF91cmwiO2k6MTU0O3M6NjI6ImludDMyXChcKFwoXCR6XHMqPj5ccyo1XHMqJlxzKjB4MDdmZmZmZmZcKVxzKlxeXHMqXCR5XHMqPDxccyoyIjtpOjE1NTtzOjQzOiJmb3BlblwoXHMqXCRyb290X2RpclxzKlwuXHMqWyciXS9cLmh0YWNjZXNzIjtpOjE1NjtzOjIzOiJcJGluX1Blcm1zXHMrJlxzKzB4NDAwMCI7aToxNTc7czozNDoiZmlsZV9nZXRfY29udGVudHNcKFxzKlsnIl0vdmFyL3RtcCI7aToxNTg7czo5OiIvcG10L3Jhdi8iO2k6MTU5O3M6NDk6ImZ3cml0ZVwoXCRmcFxzKixccypzdHJyZXZcKFxzKlwkY29udGV4dFxzKlwpXHMqXCkiO2k6MTYwO3M6MjA6Ik1hc3JpXHMrQ3liZXJccytUZWFtIjtpOjE2MTtzOjE4OiJVczNccytZMHVyXHMrYnI0MW4iO2k6MTYyO3M6MjA6Ik1hc3IxXHMrQ3liM3JccytUZTRtIjtpOjE2MztzOjIwOiJ0SEFOS3Nccyt0T1xzK1Nub3BweSI7aToxNjQ7czo2NjoiLFxzKlsnIl0vaW5kZXhcXFwuXChwaHBcfGh0bWxcKS9pWyciXVxzKixccypSZWN1cnNpdmVSZWdleEl0ZXJhdG9yIjtpOjE2NTtzOjQ3OiJmaWxlX3B1dF9jb250ZW50c1woXHMqXCRpbmRleF9wYXRoXHMqLFxzKlwkY29kZSI7aToxNjY7czo1NToiZ2V0cHJvdG9ieW5hbWVcKFxzKlsnIl10Y3BbJyJdXHMqXClccytcfFx8XHMrZGllXHMrc2hpdCI7aToxNjc7czo3ODoiXGIoZnRwX2V4ZWN8c3lzdGVtfHNoZWxsX2V4ZWN8cGFzc3RocnV8cG9wZW58cHJvY19vcGVuKVwoXHMqWyciXWNkXHMrL3RtcDt3Z2V0IjtpOjE2ODtzOjIyOiI8YVxzK2hyZWY9WyciXW9zaGlia2EtIjtpOjE2OTtzOjg1OiJpZlwoXHMqXCRfR0VUXFtccypbJyJdaWRbJyJdXHMqXF0hPVxzKlsnIl1bJyJdXHMqXClccypcJGlkPVwkX0dFVFxbXHMqWyciXWlkWyciXVxzKlxdIjtpOjE3MDtzOjgzOiJpZlwoWyciXXN1YnN0cl9jb3VudFwoWyciXVwkX1NFUlZFUlxbWyciXVJFUVVFU1RfVVJJWyciXVxdXHMqLFxzKlsnIl1xdWVyeVwucGhwWyciXSI7aToxNzE7czozODoiXCRmaWxsID0gXCRfQ09PS0lFXFtcXFsnIl1maWxsXFxbJyJdXF0iO2k6MTcyO3M6NjI6IlwkcmVzdWx0PXNtYXJ0Q29weVwoXHMqXCRzb3VyY2VccypcLlxzKlsnIl0vWyciXVxzKlwuXHMqXCRmaWxlIjtpOjE3MztzOjQwOiJcJGJhbm5lZElQXHMqPVxzKmFycmF5XChccypbJyJdXF42NlwuMTAyIjtpOjE3NDtzOjM1OiI8bG9jPjxcP3BocFxzK2VjaG9ccytcJGN1cnJlbnRfdXJsOyI7aToxNzU7czoyODoiXCRzZXRjb29rXCk7c2V0Y29va2llXChcJHNldCI7aToxNzY7czoyODoiXCk7ZnVuY3Rpb25ccytzdHJpbmdfY3B0XChcJCI7aToxNzc7czo1MDoiWyciXVwuWyciXVsnIl1cLlsnIl1bJyJdXC5bJyJdWyciXVwuWyciXVsnIl1cLlsnIl0iO2k6MTc4O3M6NTM6ImlmXChwcmVnX21hdGNoXChbJyJdXCN3b3JkcHJlc3NfbG9nZ2VkX2luXHxhZG1pblx8cHdkIjtpOjE3OTtzOjQxOiJnX2RlbGV0ZV9vbl9leGl0XHMqPVxzKm5ld1xzK0RlbGV0ZU9uRXhpdCI7aToxODA7czozMDoiU0VMRUNUXHMrXCpccytGUk9NXHMrZG9yX3BhZ2VzIjtpOjE4MTtzOjE4OiJBY2FkZW1pY29ccytSZXN1bHQiO2k6MTgyO3M6Nzc6InZhbHVlPVsnIl08XD9ccytcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChbJyJdIjtpOjE4MztzOjI3OiJcZCsmQHByZWdfbWF0Y2hcKFxzKnN0cnRyXCgiO2k6MTg0O3M6Mzg6ImNoclwoXHMqaGV4ZGVjXChccypzdWJzdHJcKFxzKlwkbWFrZXVwIjtpOjE4NTtzOjMwOiJyZWFkX2ZpbGVfbmV3XzJcKFwkcmVzdWx0X3BhdGgiO2k6MTg2O3M6MjM6IlwkaW5kZXhfcGF0aFxzKixccyowNDA0IjtpOjE4NztzOjY3OiJcJGZpbGVfZm9yX3RvdWNoXHMqPVxzKlwkX1NFUlZFUlxbWyciXXswLDF9RE9DVU1FTlRfUk9PVFsnIl17MCwxfVxdIjtpOjE4ODtzOjYxOiJcJF9TRVJWRVJcW1snIl17MCwxfVJFTU9URV9BRERSWyciXXswLDF9XF07aWZcKFwocHJlZ19tYXRjaFwoIjtpOjE4OTtzOjE5OiI9PVxzKlsnIl1jc2hlbGxbJyJdIjtpOjE5MDtzOjI5OiJmaWxlX2V4aXN0c1woXHMqXCRGaWxlQmF6YVRYVCI7aToxOTE7czoxODoicmVzdWx0c2lnbl93YXJuaW5nIjtpOjE5MjtzOjI0OiJmdW5jdGlvblxzK2dldGZpcnN0c2h0YWciO2k6MTkzO3M6OTA6ImZpbGVfZ2V0X2NvbnRlbnRzXChST09UX0RJUlwuWyciXS90ZW1wbGF0ZXMvWyciXVwuXCRjb25maWdcW1snIl1za2luWyciXVxdXC5bJyJdL21haW5cLnRwbCI7aToxOTQ7czoyNToibmV3XHMrY29uZWN0QmFzZVwoWyciXWFIUiI7aToxOTU7czo4MzoiXCRpZFxzKlwuXHMqWyciXVw/ZD1bJyJdXHMqXC5ccypiYXNlNjRfZW5jb2RlXChccypcJF9TRVJWRVJcW1xzKlsnIl1IVFRQX1VTRVJfQUdFTlQiO2k6MTk2O3M6Mjk6ImRvX3dvcmtcKFxzKlwkaW5kZXhfZmlsZVxzKlwpIjtpOjE5NztzOjIwOiJoZWFkZXJccypcKFxzKl9cZCtcKCI7aToxOTg7czoxMjoiQnlccytXZWJSb29UIjtpOjE5OTtzOjE2OiJDb2RlZFxzK2J5XHMrRVhFIjtpOjIwMDtzOjcxOiJ0cmltXChccypcJGhlYWRlcnNccypcKVxzKlwpXHMqYXNccypcJGhlYWRlclxzKlwpXHMqaGVhZGVyXChccypcJGhlYWRlciI7aToyMDE7czo1NjoiQFwkX1NFUlZFUlxbXHMqSFRUUF9IT1NUXHMqXF0+WyciXVxzKlwuXHMqWyciXVxcclxcblsnIl0iO2k6MjAyO3M6ODE6ImZpbGVfZ2V0X2NvbnRlbnRzXChccypcJF9TRVJWRVJcW1xzKlsnIl1ET0NVTUVOVF9ST09UWyciXVxzKlxdXHMqXC5ccypbJyJdL2VuZ2luZSI7aToyMDM7czo2OToidG91Y2hcKFxzKlwkX1NFUlZFUlxbXHMqWyciXURPQ1VNRU5UX1JPT1RbJyJdXHMqXF1ccypcLlxzKlsnIl0vZW5naW5lIjtpOjIwNDtzOjE2OiJQSFBTSEVMTF9WRVJTSU9OIjtpOjIwNTtzOjI1OiI8XD9ccyo9QGBcJFthLXpBLVowLTlfXStgIjtpOjIwNjtzOjIxOiImX1NFU1NJT05cW3BheWxvYWRcXT0iO2k6MjA3O3M6NDc6Imd6dW5jb21wcmVzc1woXHMqZmlsZV9nZXRfY29udGVudHNcKFxzKlsnIl1odHRwIjtpOjIwODtzOjg0OiJpZlwoXHMqIWVtcHR5XChccypcJF9QT1NUXFtccypbJyJdezAsMX10cDJbJyJdezAsMX1ccypcXVwpXHMqYW5kXHMqaXNzZXRcKFxzKlwkX1BPU1QiO2k6MjA5O3M6NDk6ImlmXChccyp0cnVlXHMqJlxzKkBwcmVnX21hdGNoXChccypzdHJ0clwoXHMqWyciXS8iO2k6MjEwO3M6Mzg6Ij09XHMqMFwpXHMqe1xzKmVjaG9ccypQSFBfT1NccypcLlxzKlwkIjtpOjIxMTtzOjEwNzoiaXNzZXRcKFxzKlwkX1NFUlZFUlxbXHMqX1xkK1woXHMqXGQrXHMqXClccypcXVxzKlwpXHMqXD9ccypcJF9TRVJWRVJcW1xzKl9cZCtcKFxkK1wpXHMqXF1ccyo6XHMqX1xkK1woXGQrXCkiO2k6MjEyO3M6OTk6IlwkaW5kZXhccyo9XHMqc3RyX3JlcGxhY2VcKFxzKlsnIl08XD9waHBccypvYl9lbmRfZmx1c2hcKFwpO1xzKlw/PlsnIl1ccyosXHMqWyciXVsnIl1ccyosXHMqXCRpbmRleCI7aToyMTM7czozMzoiXCRzdGF0dXNfbG9jX3NoXHMqPVxzKmZpbGVfZXhpc3RzIjtpOjIxNDtzOjQ4OiJcJFBPU1RfU1RSXHMqPVxzKmZpbGVfZ2V0X2NvbnRlbnRzXCgicGhwOi8vaW5wdXQiO2k6MjE1O3M6NDg6ImdlXHMqPVxzKnN0cmlwc2xhc2hlc1xzKlwoXHMqXCRfUE9TVFxzKlxbWyciXW1lcyI7aToyMTY7czo2NjoiXCR0YWJsZVxbXCRzdHJpbmdcW1wkaVxdXF1ccypcKlxzKnBvd1woNjRccyosXHMqMlwpXHMqXCtccypcJHRhYmxlIjtpOjIxNztzOjMzOiJpZlwoXHMqc3RyaXBvc1woXHMqWyciXVwqXCpcKlwkdWEiO2k6MjE4O3M6NDk6ImZsdXNoX2VuZF9maWxlXChccypcJGZpbGVuYW1lXHMqLFxzKlwkZmlsZWNvbnRlbnQiO2k6MjE5O3M6NTY6InByZWdfbWF0Y2hcKFxzKlsnIl17MCwxfX5Mb2NhdGlvbjpcKFwuXCpcP1wpXChcPzpcXG5cfFwkIjtpOjIyMDtzOjI4OiJ0b3VjaFwoXHMqXCR0aGlzLT5jb25mLT5yb290IjtpOjIyMTtzOjM4OiJcYmV2YWxcKFxzKlwke1xzKlwkW2EtekEtWjAtOV9dK1xzKn1cWyI7aToyMjI7czo0MzoiaWZccypcKFxzKkBmaWxldHlwZVwoXCRsZWFkb25ccypcLlxzKlwkZmlsZSI7aToyMjM7czo1OToiZmlsZV9wdXRfY29udGVudHNcKFxzKlwkZGlyXHMqXC5ccypcJGZpbGVccypcLlxzKlsnIl0vaW5kZXgiO2k6MjI0O3M6MjY6ImZpbGVzaXplXChccypcJHB1dF9rX2ZhaWx1IjtpOjIyNTtzOjYwOiJhZ2Vccyo9XHMqc3RyaXBzbGFzaGVzXHMqXChccypcJF9QT1NUXHMqXFtbJyJdezAsMX1tZXNbJyJdXF0iO2k6MjI2O3M6NDM6ImZ1bmN0aW9uXHMrZmluZEhlYWRlckxpbmVccypcKFxzKlwkdGVtcGxhdGUiO2k6MjI3O3M6NDM6Ilwkc3RhdHVzX2NyZWF0ZV9nbG9iX2ZpbGVccyo9XHMqY3JlYXRlX2ZpbGUiO2k6MjI4O3M6Mzg6ImVjaG9ccytzaG93X3F1ZXJ5X2Zvcm1cKFxzKlwkc3Fsc3RyaW5nIjtpOjIyOTtzOjM1OiI9PVxzKkZBTFNFXHMqXD9ccypcZCtccyo6XHMqaXAybG9uZyI7aToyMzA7czoyMjoiZnVuY3Rpb25ccyttYWlsZXJfc3BhbSI7aToyMzE7czozNDoiRWRpdEh0YWNjZXNzXChccypbJyJdUmV3cml0ZUVuZ2luZSI7aToyMzI7czoxMToiXCRwYXRoVG9Eb3IiO2k6MjMzO3M6NDA6IlwkY3VyX2NhdF9pZFxzKj1ccypcKFxzKmlzc2V0XChccypcJF9HRVQiO2k6MjM0O3M6OTc6IkBcJF9DT09LSUVcW1xzKlsnIl1bYS16QS1aMC05X10rWyciXVxzKlxdXChccypAXCRfQ09PS0lFXFtccypbJyJdW2EtekEtWjAtOV9dK1snIl1ccypcXVxzKlwpXHMqXCkiO2k6MjM1O3M6NDA6ImhlYWRlclwoWyciXUxvY2F0aW9uOlxzKmh0dHA6Ly9cJHBwXC5vcmciO2k6MjM2O3M6NDc6InJldHVyblxzK1snIl0vaG9tZS9bYS16QS1aMC05X10rL1thLXpBLVowLTlfXSsvIjtpOjIzNztzOjM5OiJbJyJdd3AtWyciXVxzKlwuXHMqZ2VuZXJhdGVSYW5kb21TdHJpbmciO2k6MjM4O3M6Njc6IlwkW2EtekEtWjAtOV9dKz09WyciXWZlYXR1cmVkWyciXVxzKlwpXHMqXCl7XHMqZWNob1xzK2Jhc2U2NF9kZWNvZGUiO2k6MjM5O3M6MTEyOiJcJFthLXpBLVowLTlfXStccyo9XHMqXCRqcVxzKlwoXHMqQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdIjtpOjI0MDtzOjIyOiJleHBsb2l0XHMqOjpcLjwvdGl0bGU+IjtpOjI0MTtzOjQwOiJcJFthLXpBLVowLTlfXSs9c3RyX3JlcGxhY2VcKFsnIl1cKmFcJFwqIjtpOjI0MjtzOjYwOiJjaHJcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwpO1xzKn1ccypldmFsXChccypcJFthLXpBLVowLTlfXSsiO2k6MjQzO3M6NDc6ImlmXChccyppc0luU3RyaW5nMT9cKFwkW2EtekEtWjAtOV9dKyxbJyJdZ29vZ2xlIjtpOjI0NDtzOjkzOiJcJHBwXHMqPVxzKlwkcFxbXGQrXF1ccypcLlxzKlwkcFxbXGQrXF1ccypcLlxzKlwkcFxbXGQrXF1ccypcLlxzKlwkcFxbXGQrXF1ccypcLlxzKlwkcFxbXGQrXF0iO2k6MjQ1O3M6NDk6ImZpbGVfcHV0X2NvbnRlbnRzXChESVJcLlsnIl0vWyciXVwuWyciXWluZGV4XC5waHAiO2k6MjQ2O3M6Mjk6IkBnZXRfaGVhZGVyc1woXHMqXCRmdWxscGF0aFwpIjtpOjI0NztzOjIxOiJAXCRfR0VUXFtbJyJdcHdbJyJdXF0iO2k6MjQ4O3M6MjU6Impzb25fZW5jb2RlXChhbGV4dXNNYWlsZXIiO2k6MjQ5O3M6MTk6Ij1bJyJdXClcKTtbJyJdXClcKTsiO2k6MjUwO3M6MTgwOiI9XHMqXCRbYS16QS1aMC05X10rXChcYihldmFsfGJhc2U2NF9kZWNvZGV8c3RycmV2fHByZWdfcmVwbGFjZXxwcmVnX3JlcGxhY2VfY2FsbGJhY2t8dXJsZGVjb2RlfHN0cnN0cnxnemluZmxhdGV8c3ByaW50ZnxnenVuY29tcHJlc3N8YXNzZXJ0fHN0cl9yb3QxM3xtZDV8YXJyYXlfd2Fsa3xhcnJheV9maWx0ZXIpXCgiO2k6MjUxO3M6NjE6IlxdXHMqfVxzKlwoXHMqe1xzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFsiO2k6MjUyO3M6Nzc6InJlcXVlc3RcLnNlcnZlcnZhcmlhYmxlc1woXHMqWyciXUhUVFBfVVNFUl9BR0VOVFsnIl1ccypcKVxzKixccypbJyJdR29vZ2xlYm90IjtpOjI1MztzOjQ4OiJldmFsXChbJyJdXD8+WyciXVxzKlwuXHMqam9pblwoWyciXVsnIl0sZmlsZVwoXCQiO2k6MjU0O3M6Njg6InNldG9wdFwoXCRjaFxzKixccypDVVJMT1BUX1BPU1RGSUVMRFNccyosXHMqaHR0cF9idWlsZF9xdWVyeVwoXCRkYXRhIjtpOjI1NTtzOjEyOToibXlzcWxfY29ubmVjdFwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl1bYS16QS1aMC05X10rWyciXVxdXHMqLFxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpIjtpOjI1NjtzOjY0OiJyZXF1ZXN0XC5zZXJ2ZXJ2YXJpYWJsZXNcKFsnIl1IVFRQX1VTRVJfQUdFTlRbJyJdXCksWyciXWFpZHVbJyJdIjtpOjI1NztzOjM2OiJcXVxzKlwpXHMqXClccyp7XHMqZXZhbFxzKlwoXHMqXCR7XCQiO2k6MjU4O3M6MTY6ImJ5XHMrRXJyb3Jccys3ckIiO2k6MjU5O3M6MzM6IkBpcmNzZXJ2ZXJzXFtyYW5kXHMrQGlyY3NlcnZlcnNcXSI7aToyNjA7czo2NToic2V0X3RpbWVfbGltaXRcKGludHZhbFwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MjYxO3M6MjQ6Im5pY2tccytbJyJdY2hhbnNlcnZbJyJdOyI7aToyNjI7czoyMzoiTWFnaWNccytJbmNsdWRlXHMrU2hlbGwiO2k6MjYzO3M6OTc6IlwkW2EtekEtWjAtOV9dK1wpO1wkW2EtekEtWjAtOV9dKz1jcmVhdGVfZnVuY3Rpb25cKFsnIl1bJyJdLFwkW2EtekEtWjAtOV9dK1wpO1wkW2EtekEtWjAtOV9dK1woXCkiO2k6MjY0O3M6Mzg6ImN1cmxPcGVuXChcJHJlbW90ZV9wYXRoXC5cJHBhcmFtX3ZhbHVlIjtpOjI2NTtzOjQ3OiJmd3JpdGVcKFwkZnAsWyciXVxceEVGXFx4QkJcXHhCRlsnIl1cLlwkYm9keVwpOyI7aToyNjY7czoxMzM6IlwkW2EtekEtWjAtOV9dK1wrXCtcKVxzKntccypcJFthLXpBLVowLTlfXStccyo9XHMqYXJyYXlfdW5pcXVlXChhcnJheV9tZXJnZVwoXCRbYS16QS1aMC05X10rXHMqLFxzKlthLXpBLVowLTlfXStcKFsnIl1cJFthLXpBLVowLTlfXSsiO2k6MjY3O3M6NDI6ImFuZFxzKlwoIVxzKnN0cnN0clwoXCR1YSxbJyJdcnY6MTFbJyJdXClcKSI7aToyNjg7czozNToiZWNob1xzK1wkb2tccytcP1xzK1snIl1TSEVMTF9PS1snIl0iO2k6MjY5O3M6Mjc6IjtldmFsXChcJHRvZG9jb250ZW50XFswXF1cKSI7aToyNzA7czo0MDoib3JccytzdHJ0b2xvd2VyXChAaW5pX2dldFwoWyciXXNhZmVfbW9kZSI7aToyNzE7czoyOToiaWZcKCFpc3NldFwoXCRfUkVRVUVTVFxbY2hyXCgiO2k6MjcyO3M6NDQ6IlwkcHJvY2Vzc29ccyo9XHMqXCRwc1xbcmFuZFxzK3NjYWxhclxzK0Bwc1xdIjtpOjI3MztzOjMyOiJlY2hvXHMrWyciXXVuYW1lXHMrLWE7XHMqXCR1bmFtZSI7aToyNzQ7czoyMToiXC50Y3BmbG9vZFxzKzx0YXJnZXQ+IjtpOjI3NTtzOjUwOiJcJGJvdFxbWyciXXNlcnZlclsnIl1cXT1cJHNlcnZiYW5cW3JhbmRcKDAsY291bnRcKCI7aToyNzY7czoxNjoiXC46XHMrdzMzZFxzKzpcLiI7aToyNzc7czoxNjoiQkxBQ0tVTklYXHMrQ1JFVyI7aToyNzg7czoxMTg6IjtcJFthLXpBLVowLTlfXStcW1wkW2EtekEtWjAtOV9dK1xbWyciXVthLXpBLVowLTlfXStbJyJdXF1cW1xkK1xdXC5cJFthLXpBLVowLTlfXStcW1snIl1bYS16QS1aMC05X10rWyciXVxdXFtcZCtcXVwuXCQiO2k6Mjc5O3M6MzA6ImNhc2VccypbJyJdY3JlYXRlX3N5bWxpbmtbJyJdOiI7aToyODA7czo5Njoic29ja2V0X2Nvbm5lY3RcKFwkW2EtekEtWjAtOV9dKyxccypbJyJdZ21haWwtc210cC1pblwubFwuZ29vZ2xlXC5jb21bJyJdXHMqLFxzKjI1XClccyo9PVxzKkZBTFNFIjtpOjI4MTtzOjQ2OiJjYWxsX3VzZXJfZnVuY1woQHVuaGV4XCgweFthLXpBLVowLTlfXStcKVwoXCRfIjtpOjI4MjtzOjYyOiJcJF89QFwkX1JFUVVFU1RcW1snIl1bYS16QS1aMC05X10rWyciXVxdXClcLkBcJF9cKFwkX1JFUVVFU1RcWyI7aToyODM7czo2NToiXCRHTE9CQUxTXFtcJEdMT0JBTFNcW1snIl1bYS16QS1aMC05X10rWyciXVxdXFtcZCtcXVwuXCRHTE9CQUxTXFsiO2k6Mjg0O3M6NjM6IlwuXCRbYS16QS1aMC05X10rXFtcJFthLXpBLVowLTlfXStcXVwuWyciXXtbJyJdXClcKTt9O3Vuc2V0XChcJCI7aToyODU7czo5MjoiaHR0cF9idWlsZF9xdWVyeVwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcKVwuWyciXSZpcD1bJyJdXHMqXC5ccypcJF9TRVJWRVIiO2k6Mjg2O3M6ODI6IlxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilcKFxzKlsnIl0vc2Jpbi9pZmNvbmZpZ1snIl0iO2k6Mjg3O3M6OTU6IjxcP3BocFxzK2lmXHMqXChpc3NldFxzKlwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl1pbWFnZXNbJyJdXF1cKVwpXHMqe1wkIjtpOjI4ODtzOjE3OiI8dGl0bGU+R09SRE9ccysyMCI7aToyODk7czoxNTA6ImNvcHlcKFxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXVxzKixccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXVthLXpBLVowLTlfXStbJyJdXF1cKSI7aToyOTA7czo2ODoic3ByaW50ZlwoW2EtekEtWjAtOV9dK1woXCRbYS16QS1aMC05X10rXHMqLFxzKlwkW2EtekEtWjAtOV9dK1wpXHMqXCkiO2k6MjkxO3M6Njg6Ij1ccypzdHJfcmVwbGFjZVwoXHMqWyciXVx8XHxcfFthLXpBLVowLTlfXStcfFx8XHxbJyJdXHMqLFxzKlsnIl1bJyJdIjtpOjI5MjtzOjEzMjoiXCRbYS16QS1aMC05X10rXFswXF09cGFja1woWyciXUhcKlsnIl0sWyciXVthLXpBLVowLTlfXStbJyJdXCk7YXJyYXlfZmlsdGVyXChcJFthLXpBLVowLTlfXSsscGFja1woWyciXUhcKlsnIl0sWyciXVthLXpBLVowLTlfXStbJyJdIjtpOjI5MztzOjYxOiJpZlxzKlwod2luZG93XC5wbHVzb1wpXHMqaWZccypcKHR5cGVvZlxzKndpbmRvd1wucGx1c29cLnN0YXJ0IjtpOjI5NDtzOjEzOiJldmFsXChcJHtcJHsiIjtpOjI5NTtzOjE4OiItLXZpc2l0b3JUcmFja2VyLS0iO2k6Mjk2O3M6MTM6IjwlLS1TdUV4cC0tJT4iO2k6Mjk3O3M6NzM6IlwkX19hXHMqPVxzKnN0cl9yZXBsYWNlXCgiLiIsXHMqIi4iLFxzKlwkX18uXCk7XHMqXCRfXy5ccyo9XHMqc3RyX3JlcGxhY2UiO2k6Mjk4O3M6MzA6ImVjaG9ccypleGVjXChbJyJdd2hvYW1pWyciXVwpOyI7aToyOTk7czoxMjc6ImZpbGVfcHV0X2NvbnRlbnRzXChbJyJdW2EtekEtWjAtOV9dK1wucGhwWyciXSxcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXVthLXpBLVowLTlfXStbJyJdXF0sRklMRV9BUFBFTkRcKTsiO2k6MzAwO3M6NTg6IlwkZGlycGF0aFwuWyciXS9bJyJdXC5cJHZhbHVlXC5bJyJdL3dwLWNvbmZpZ1wucGhwWyciXVwpXC4iO2k6MzAxO3M6NjA6ImFycmF5X2RpZmZfdWtleVwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcWyI7aTozMDI7czo2NzoiXCRbYS16QS1aMC05X10rPWZpbGVfZ2V0X2NvbnRlbnRzXChbJyJdaHR0cDovL3d3d1wuYXNrXC5jb20vd2ViXD9xPSI7aTozMDM7czoyNjU6ImlmXCghZW1wdHlcKFxzKlwkX0ZJTEVTXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXVwpXCl7Y29weVwoXCRfRklMRVNcW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdLFwkX0ZJTEVTXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXVwpO30iO2k6MzA0O3M6MjEzOiJyZWdpc3Rlcl9zaHV0ZG93bl9mdW5jdGlvblxzKlwoXHMqY3JlYXRlX2Z1bmN0aW9uXChccypAP1wke1xzKlsnIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1ccyp9XHMqLFxzKkA/XCR7XHMqWyciXV8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVsnIl1ccyp9e1xzKlsnIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1ccyp9XHMqXClccypcKTsiO2k6MzA1O3M6Mjg3OiJAP2ZpbHRlcl92YXJccypcKEA/XHMqXCR7WyciXV8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVsnIl19e1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX19XHMqLFxzKkZJTFRFUl9DQUxMQkFDS1xzKixccyphcnJheVxzKlwoXHMqWyciXVthLXpBLVowLTlfXStbJyJdXHMqPT5ccypcJHtccypbJyJdXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpWyciXVxzKn17XHMqWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxzKn1ccypcKVxzKlwpXHMqOyI7aTozMDY7czoyNTk6ImlmXChcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdIT1cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdXCl7ZXh0cmFjdFwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcKTtccyp1c29ydFwoXCRbYS16QS1aMC05X10rLFwkW2EtekEtWjAtOV9dK1wpO30iO2k6MzA3O3M6MjUwOiJkZWNsYXJlXChccyp0aWNrcz1cZCtccypcKVxzKjtAP3JlZ2lzdGVyX3RpY2tfZnVuY3Rpb25cKFxzKlwke1snIl1fKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylbJyJdfVxzKntbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9fVxzKixcJHtbJyJdezAsMX1fKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylbJyJdezAsMX19XHMqe1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX19XCk7IjtpOjMwODtzOjE0NzoiZGVmaW5lXChbYS16QS1aMC05X10rXHMqLFxzKlwkX1NFUlZFUlxbW2EtekEtWjAtOV9dK1xdXCk7XHMqQD9yZWdpc3Rlcl9zaHV0ZG93bl9mdW5jdGlvblwoXHMqY3JlYXRlX2Z1bmN0aW9uXChcJFthLXpBLVowLTlfXSssW2EtekEtWjAtOV9dK1wpXHMqXCk7IjtpOjMwOTtzOjMxNDoiXCR7WyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfX09QD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKTtAPyFcKEA/XCR7WyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfX1cW1xkK1xdJiZAP1wke0A/WyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfX1cW1xkK1xdXClcP1wke0A/WyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfX06QD9AP1wke1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX19XFtcZCtcXVwoQD9cJHtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9fVxbXGQrXF1cKTsiO2k6MzEwO3M6ODc6InNldGNvb2tpZVwoWyciXVthLXpBLVowLTlfXStbJyJdXHMqLFxzKnNlcmlhbGl6ZVwoQD9cJF88R1BDPlxbWyciXVthLXpBLVowLTlfXStbJyJdXF1cKSI7aTozMTE7czoyNToiPC9kaXY+XHMqPCUtLVBvcnRTY2FuLS0lPiI7aTozMTI7czoxNDoiR0lGODkuLi48XD9waHAiO2k6MzEzO3M6MTc4OiJcJFthLXpBLVowLTlfXStccyo9XHMqZm9wZW5cKFsnIl1bYS16QS1aMC05X10rXC5waHBbJyJdXHMqLFxzKlsnIl13XCs/WyciXVwpO1xzKmZwdXRzXChcJFthLXpBLVowLTlfXStccyosXHMqXCRbYS16QS1aMC05X10rXCk7XHMqZmNsb3NlXChcJFthLXpBLVowLTlfXStcKTtccyp1bmxpbmtcKF9fRklMRV9fXCk7IjtpOjMxNDtzOjM0OiJDcmVhdGVKb29tQ29kZVwoXCRbYS16QS1aMC05X10rXCk7IjtpOjMxNTtzOjIzOiJmdW5jdGlvblxzK0NyZWF0ZVdwQ29kZSI7aTozMTY7czozNzoiPGJyPlsnIl1cLnBocF91bmFtZVwoXClcLlsnIl08YnI+PC9iPiI7aTozMTc7czo3MzoiaWZcKFwkW2EtekEtWjAtOV9dK1xzKj1ccypAP3N0cnBvc1woXCRbYS16QS1aMC05X10rLCJjaGVja19tZXRhXChcKTsiXClcKSI7aTozMTg7czo5NDoiaWZcKGlzc2V0XChcJF9HRVRcW1snIl1pbnN0YWxsWyciXVxdXClcKXtccypcJGRiLT5leGVjXChbJyJdQ1JFQVRFIFRBQkxFIElGIE5PVCBFWElTVFMgYXJ0aWNsZSI7aTozMTk7czo3NDoiYXJyMmh0bWxcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdXHd7MSw0NX1bJyJdXF1cKTsiO2k6MzIwO3M6Mzk6ImZ1bmN0aW9uXHMrZ2V0X3RleHRfZnJfc2VydlwoXHMqXCR1cmxfcyI7aTozMjE7czo1MToiZnVuY3Rpb25ccytjdXJsX2dldF9mcm9tX3dlYnBhZ2Vfb25lX3RpbWVcKFxzKlwkdXJsIjtpOjMyMjtzOjQ3OiJcJGFydGljbGVcZCtccyo9XHMqZXhwbG9kZVwoIlwjXCNcIyIsXCRzdHJcZCtcKSI7aTozMjM7czoxMzY6ImVjaG9ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXVx3ezEsNDV9WyciXVxdXHMqLlxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdXHd7MSw0NX1bJyJdXF0iO2k6MzI0O3M6NjY6ImZpbGVfZ2V0X2NvbnRlbnRzXChbJyJdaHR0cDovL1xkK1wuXGQrXC5cZCtcLlxkKy9cd3sxLDQ1fVwucGhwXD9oPSI7aTozMjU7czoxNjoiY2xhc3NccypMTE1fYmFzZSI7aTozMjY7czozNDoiZm5fZG9sbHlfZ2V0X2ZpbGVuYW1lX2Zyb21faGVhZGVycyI7aTozMjc7czo1NjoiZXZhbFxzKlwoP1xzKkA/XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MzI4O3M6NTg6ImFzc2VydFxzKlwoP1xzKkA/XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MzI5O3M6MTEyOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXHMqXCg/XHMqQD9cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxzKlxbIjtpOjMzMDtzOjE3NzoiPGI+ZXZhbFxzKlwoXHMqXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVxzKlwoIjtpOjMzMTtzOjcyOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXHMqXCg/XHMqWyciXXdnZXQiO2k6MzMyO3M6MjE6Ij09WyciXVwpXCk7cmV0dXJuO1w/PiI7aTozMzM7czo3OiJ1Z2djOi8vIjtpOjMzNDtzOjEwMzoiXCRbYS16QS1aMC05X10rXFtcJFthLXpBLVowLTlfXStcXT1jaHJcKFwkW2EtekEtWjAtOV9dK1xbb3JkXChcJFthLXpBLVowLTlfXStcW1wkW2EtekEtWjAtOV9dK1xdXClcXVwpOyI7aTozMzU7czo0NjoiXCRbYS16QS1aMC05X10rXFtjaHJcKFxkK1wpXF1cKFthLXpBLVowLTlfXStcKCI7aTozMzY7czoxNDI6IlwkW2EtekEtWjAtOV9dK1xzKlwoXHMqXGQrXHMqXF5ccypcZCtccypcKVxzKlwuXHMqXCRbYS16QS1aMC05X10rXHMqXChccypcZCtccypcXlxzKlxkK1xzKlwpXHMqXC5ccypcJFthLXpBLVowLTlfXStccypcKFxzKlxkK1xzKlxeXHMqXGQrXHMqXCkiO2k6MzM3O3M6MTEwOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChbJyJdezAsMX1cJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIiI7aTozMzg7czo5NDoiXCRbYS16QS1aMC05X10rPWFycmF5XChbJyJdXCRbYS16QS1aMC05X10rXFtccypcXT1hcnJheV9wb3BcKFwkW2EtekEtWjAtOV9dK1wpO1wkW2EtekEtWjAtOV9dKyI7aTozMzk7czoxMDc6IlwkW2EtekEtWjAtOV9dKz1wYWNrXChbJyJdSFwqWyciXSxzdWJzdHJcKFwkW2EtekEtWjAtOV9dKyxccyotXGQrXClcKTtccypyZXR1cm5ccytcJFthLXpBLVowLTlfXStcKHN1YnN0clwoIjtpOjM0MDtzOjEzNjoiXCRbYS16QS1aMC05X10rXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXVwoXCRbYS16QS1aMC05X10rLFwkW2EtekEtWjAtOV9dKyxcJFthLXpBLVowLTlfXStcWyI7aTozNDE7czoxNDU6IjwvZm9ybT5bJyJdO2lmXChpc3NldFwoXCRfUE9TVFxbWyciXVx3ezEsNDV9WyciXVxdXClcKXtpZlwoaXNfdXBsb2FkZWRfZmlsZVwoXCRfRklMRVNcW1snIl1cd3sxLDQ1fVsnIl1cXVxbWyciXVx3ezEsNDV9WyciXVxdXClcKXtAY29weVwoXCRfRklMRVMiO2k6MzQyO3M6NDY6IlwuL2hvc3RccytlbmNyeXB0XHMrcHVibGlja2V5XC5wdWJccytbJyJdL2hvbWUiO2k6MzQzO3M6NDQ6IlwkcmVzX2VsXFtbJyJdbGlua1snIl1cXVwuWyciXS1cfFx8LWdvb2RbJyJdIjtpOjM0NDtzOjM1OiI9XHMqZXhwbG9kZVwoWyciXV9cfFx8X1snIl0sXCRfUE9TVCI7aTozNDU7czoxMDI6IlwkY3VycmVudFxzKj1ccypmaWxlX2dldF9jb250ZW50c1woWyciXWh0dHA6Ly8uKj9cJGlkWyciXVwpO1xzKmZpbGVfcHV0X2NvbnRlbnRzXChcJGlkLFxzKlwkY3VycmVudFwpOyI7aTozNDY7czo2NToiXCRkb21haW5ccyo9XHMqXCRkb21haW5zXFthcnJheV9yYW5kXChcJGRvbWFpbnMsXHMqMVwpXF07XHMqXCR1cmwiO2k6MzQ3O3M6MTE1OiJcJFx3ezEsNDV9XHMqPVxzKlwkXHd7MSw0NX1cLlsnIl0vYXBpXC5waHBcP2FjdGlvbj1mdWxsdHh0JmNvbmZpZz1bJyJdXC5cJFx3ezEsNDV9XC5bJyJdJlx3ezEsNDV9PVsnIl1cLlwkXHd7MSw0NX07IjtpOjM0ODtzOjM1OiJcJF9QT1NUXFtbJyJdXHd7MSw0NX1bJyJdXF1cKTtAZXZhbCI7aTozNDk7czo2MzoiZnVuY3Rpb25ccypyczJodG1sXCgmXCRycyxcJHp0YWJodG1sPWZhbHNlLFwkemhlYWRlcmFycmF5PWZhbHNlIjtpOjM1MDtzOjE1MDoiaWZcKGlzc2V0XChcJF9HRVRcW1snIl1wWyciXVxdXClcKVxzKntccypoZWFkZXJcKFsnIl1Db250ZW50LVR5cGU6XHMqdGV4dC9odG1sO2NoYXJzZXQ9d2luZG93cy0xMjUxWyciXVwpO1xzKlwkcGF0aD1cJF9TRVJWRVJcW1snIl1ET0NVTUVOVF9ST09UWyciXVxdIjtpOjM1MTtzOjU2OiJzZXNzaW9uX3dyaXRlX2Nsb3NlXChcKTtccypMb2NhbFJlZGlyZWN0XChcJGRvd25sb2FkX2RpciI7aTozNTI7czo0NjoiR2V0VGhpc0lwXChcKTtccyppZlwoXCRhY3Rpb25ccyo9PVxzKiJmaWxlaW5mbyI7aTozNTM7czoxMjc6ImlmXHMqXChccypzXC5pbmRleE9mXChbJyJdZ29vZ2xlWyciXVwpXHMqPlxzKjBccypcfFx8XHMqc1wuaW5kZXhPZlwoWyciXWJpbmdbJyJdXClccyo+XHMqMFxzKlx8XHxccypzXC5pbmRleE9mXChbJyJdeWFob29bJyJdXCkiO2k6MzU0O3M6Njk6IkBjaG1vZFwoWyciXVwuaHRhY2Nlc3NbJyJdLFxkK1wpO1xzKkBjaG1vZFwoWyciXWluZGV4XC5waHBbJyJdLFxkK1wpOyI7aTozNTU7czozNjoicGluZ1xzKy1jXHMrWyciXVxzKlwuXHMqXCRwaW5nX2NvdW50IjtpOjM1NjtzOjE3OiJBbnRpY2hhdFxzK1NvY2tzNSI7aTozNTc7czoyNzoiZnVuY3Rpb25ccytCcmlkZ2VFc3RhYmxpc2gyIjtpOjM1ODtzOjMxOiJmdW5jdGlvblxzKl9fb2JmdXNjYXRlX3JlZGlyZWN0IjtpOjM1OTtzOjQyOiJcJF9QT1NUXFtccypbJyJdcHdkWyciXVxdPVsnIl1XZWFrXHMrTGl2ZXIiO2k6MzYwO3M6NjE6IlxbQklUUklYXF1ccypcW1dvcmRQcmVzc1xdXHMqXFtvc0NvbW1lcmNlXF1ccypcW0pvb21sYVxdPC9oMz4iO2k6MzYxO3M6MTk6Ijx0aXRsZT5ccypLYXp1eWE0MDQiO2k6MzYyO3M6NzE6ImlmIFwoQFwkYXJyYXlcW1xkK1xdID09IFxkK1wpXHMqe1xzKmhlYWRlclxzKlwoXHMqWyciXUxvY2F0aW9uOlxzKlwkdXJsIjtpOjM2MztzOjcyOiJpZlxzKlwoXHMqaXNzZXRcKFxzKlwkX1BPU1RcW1snIl1wcm94eVsnIl1cXVxzKlwpXHMqXClccyp7XHMqY3VybF9zZXRvcHQiO2k6MzY0O3M6MTg5OiJcYihldmFsfGJhc2U2NF9kZWNvZGV8c3RycmV2fHByZWdfcmVwbGFjZXxwcmVnX3JlcGxhY2VfY2FsbGJhY2t8dXJsZGVjb2RlfHN0cnN0cnxnemluZmxhdGV8c3ByaW50ZnxnenVuY29tcHJlc3N8YXNzZXJ0fHN0cl9yb3QxM3xtZDV8YXJyYXlfd2Fsa3xhcnJheV9maWx0ZXIpXChmaWxlX2dldF9jb250ZW50c1woWyciXWh0dHA6Ly8iO2k6MzY1O3M6Mzg6In1ccyo9XHMqZmlsZV9nZXRfY29udGVudHNcKFsnIl1odHRwOi8vIjt9"));
$gXX_FlexDBShe = unserialize(base64_decode("YTo1NjQ6e2k6MDtzOjUyOiJtYWlsXHMqXChcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIjtpOjE7czoxNDoiQk9UTkVUXHMrUEFORUwiO2k6MjtzOjE4OiI9PVxzKlsnIl00NlwuMjI5XC4iO2k6MztzOjE4OiI9PVxzKlsnIl05MVwuMjQzXC4iO2k6NDtzOjU6IkpUZXJtIjtpOjU7czo1OiJPbmV0NyI7aTo2O3M6OToiXCRwYXNzX3VwIjtpOjc7czo1OiJ4Q2VkeiI7aTo4O3M6MTE2OiJpZlxzKlwoXHMqZnVuY3Rpb25fZXhpc3RzXHMqXChccypbJyJdezAsMX1cYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pWyciXXswLDF9XHMqXClccypcKSI7aTo5O3M6Mjc6IlwkT09PLis/PVxzKnVybGRlY29kZVxzKlwoPyI7aToxMDtzOjM4OiJzdHJlYW1fc29ja2V0X2NsaWVudFxzKlwoXHMqWyciXXRjcDovLyI7aToxMTtzOjE1OiJwY250bF9leGVjXHMqXCgiO2k6MTI7czozMToiPVxzKmFycmF5X21hcFxzKlwoP1xzKnN0cnJldlxzKiI7aToxMztzOjMyOiJzdHJfaXJlcGxhY2VccypcKD9ccypbJyJdPC9oZWFkPiI7aToxNDtzOjIzOiJjb3B5XHMqXChccypbJyJdaHR0cDovLyI7aToxNTtzOjE5MDoibW92ZV91cGxvYWRlZF9maWxlXHMqXCg/XHMqXCRfRklMRVNcW1xzKlsnIl17MCwxfWZpbGVuYW1lWyciXXswLDF9XHMqXF1cW1xzKlsnIl17MCwxfXRtcF9uYW1lWyciXXswLDF9XHMqXF1ccyosXHMqXCRfRklMRVNcW1xzKlsnIl17MCwxfWZpbGVuYW1lWyciXXswLDF9XHMqXF1cW1xzKlsnIl17MCwxfW5hbWVbJyJdezAsMX1ccypcXSI7aToxNjtzOjI4OiJlY2hvXHMqXCg/XHMqWyciXU5PIEZJTEVbJyJdIjtpOjE3O3M6MTU6IlsnIl0vXC5cKi9lWyciXSI7aToxODtzOjcwOiJlY2hvXHMrc3RyaXBzbGFzaGVzXHMqXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIjtpOjE5O3M6MTU6Ii91c3Ivc2Jpbi9odHRwZCI7aToyMDtzOjcwOiI9XHMqXCRHTE9CQUxTXFtccypbJyJdXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpWyciXVxzKlxdIjtpOjIxO3M6MTU6IlwkYXV0aF9wYXNzXHMqPSI7aToyMjtzOjI5OiJlY2hvXHMrWyciXXswLDF9Z29vZFsnIl17MCwxfSI7aToyMztzOjIyOiJldmFsXHMqXChccypnZXRfb3B0aW9uIjtpOjI0O3M6ODA6IldCU19ESVJccypcLlxzKlsnIl17MCwxfXRlbXAvWyciXXswLDF9XHMqXC5ccypcJGFjdGl2ZUZpbGVccypcLlxzKlsnIl17MCwxfVwudG1wIjtpOjI1O3M6ODM6Im1vdmVfdXBsb2FkZWRfZmlsZVxzKlwoXHMqXCRfRklMRVNcW1snIl1bYS16QS1aMC05X10rWyciXVxdXFtbJyJddG1wX25hbWVbJyJdXF1ccyosIjtpOjI2O3M6ODE6Im1haWxcKFwkX1BPU1RcW1snIl17MCwxfWVtYWlsWyciXXswLDF9XF0sXHMqXCRfUE9TVFxbWyciXXswLDF9c3ViamVjdFsnIl17MCwxfVxdLCI7aToyNztzOjc3OiJtYWlsXHMqXChcJGVtYWlsXHMqLFxzKlsnIl17MCwxfT1cP1VURi04XD9CXD9bJyJdezAsMX1cLmJhc2U2NF9lbmNvZGVcKFwkZnJvbSI7aToyODtzOjY5OiJcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxzKlxbXHMqW2EtekEtWjAtOV9dK1xzKlxdXCgiO2k6Mjk7czoxOToiWyciXS9cZCsvXFthLXpcXVwqZSI7aTozMDtzOjM4OiJKUmVzcG9uc2U6OnNldEJvZHlccypcKFxzKnByZWdfcmVwbGFjZSI7aTozMTtzOjYyOiJAP2ZpbGVfcHV0X2NvbnRlbnRzXChcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aTozMjtzOjkxOiJtYWlsXChccypzdHJpcHNsYXNoZXNcKFwkdG9cKVxzKixccypzdHJpcHNsYXNoZXNcKFwkc3ViamVjdFwpXHMqLFxzKnN0cmlwc2xhc2hlc1woXCRtZXNzYWdlIjtpOjMzO3M6NjM6IlwkW2EtekEtWjAtOV9dK1xzKlwoXHMqXCRbYS16QS1aMC05X10rXHMqXChccypcJFthLXpBLVowLTlfXStcKCI7aTozNDtzOjIzOiJpc193cml0YWJsZT1pc193cml0YWJsZSI7aTozNTtzOjM4OiJAbW92ZV91cGxvYWRlZF9maWxlXChccypcJHVzZXJmaWxlX3RtcCI7aTozNjtzOjI2OiJleGl0XChcKTpleGl0XChcKTpleGl0XChcKSI7aTozNztzOjY3OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypbJyJdL3Zhci90bXAvIjtpOjM4O3M6MTc6Ij1ccypbJyJdL3Zhci90bXAvIjtpOjM5O3M6NTk6IlwoXHMqXCRzZW5kXHMqLFxzKlwkc3ViamVjdFxzKixccypcJG1lc3NhZ2VccyosXHMqXCRoZWFkZXJzIjtpOjQwO3M6ODM6IlxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilcKFsnIl1sd3AtZG93bmxvYWRccytodHRwOi8vIjtpOjQxO3M6MTAxOiJzdHJfcmVwbGFjZVwoWyciXS5bJyJdXHMqLFxzKlsnIl0uWyciXVxzKixccypzdHJfcmVwbGFjZVwoWyciXS5bJyJdXHMqLFxzKlsnIl0uWyciXVxzKixccypzdHJfcmVwbGFjZSI7aTo0MjtzOjM2OiIvYWRtaW4vY29uZmlndXJhdGlvblwucGhwL2xvZ2luXC5waHAiO2k6NDM7czo3MToic2VsZWN0XHMqY29uZmlndXJhdGlvbl9pZCxccytjb25maWd1cmF0aW9uX3RpdGxlLFxzK2NvbmZpZ3VyYXRpb25fdmFsdWUiO2k6NDQ7czo1MDoidXBkYXRlXHMqY29uZmlndXJhdGlvblxzK3NldFxzK2NvbmZpZ3VyYXRpb25fdmFsdWUiO2k6NDU7czozNzoic2VsZWN0XHMqbGFuZ3VhZ2VzX2lkLFxzK25hbWUsXHMrY29kZSI7aTo0NjtzOjUyOiJjXC5sZW5ndGhcKTt9cmV0dXJuXHMqXFxbJyJdXFxbJyJdO31pZlwoIWdldENvb2tpZVwoIjtpOjQ3O3M6NDU6ImlmXChmaWxlX3B1dF9jb250ZW50c1woXCRpbmRleF9wYXRoLFxzKlwkY29kZSI7aTo0ODtzOjM2OiJleGVjXHMre1snIl0vYmluL3NoWyciXX1ccytbJyJdLWJhc2giO2k6NDk7czo1MDoiPGlmcmFtZVxzK3NyYz1bJyJdaHR0cHM6Ly9kb2NzXC5nb29nbGVcLmNvbS9mb3Jtcy8iO2k6NTA7czoyMjoiLFsnIl08XD9waHBcXG5bJyJdXC5cJCI7aTo1MTtzOjcyOiI8XD9waHBccytcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKFxzKlsnIl0vaG9tZS8iO2k6NTI7czoyMjoieHJ1bWVyX3NwYW1fbGlua3NcLnR4dCI7aTo1MztzOjMzOiJDb21maXJtXHMrVHJhbnNhY3Rpb25ccytQYXNzd29yZDoiO2k6NTQ7czo3NzoiYXJyYXlfbWVyZ2VcKFwkZXh0XHMqLFxzKmFycmF5XChbJyJdd2Vic3RhdFsnIl0sWyciXWF3c3RhdHNbJyJdLFsnIl10ZW1wb3JhcnkiO2k6NTU7czo5MjoiXGIoZnRwX2V4ZWN8c3lzdGVtfHNoZWxsX2V4ZWN8cGFzc3RocnV8cG9wZW58cHJvY19vcGVuKVwoWyciXW15c3FsZHVtcFxzKy1oXHMrbG9jYWxob3N0XHMrLXUiO2k6NTY7czoyODoiTW90aGVyWyciXXNccytNYWlkZW5ccytOYW1lOiI7aTo1NztzOjM5OiJsb2NhdGlvblwucmVwbGFjZVwoXFxbJyJdXCR1cmxfcmVkaXJlY3QiO2k6NTg7czozNjoiY2htb2RcKGRpcm5hbWVcKF9fRklMRV9fXCksXHMqMDUxMVwpIjtpOjU5O3M6ODU6IlxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilcKFsnIl17MCwxfWN1cmxccystT1xzK2h0dHA6Ly8iO2k6NjA7czoyOToiXClcKSxQSFBfVkVSU0lPTixtZDVfZmlsZVwoXCQiO2k6NjE7czozNDoiXCRxdWVyeVxzKyxccytbJyJdZnJvbSUyMGpvc191c2VycyI7aTo2MjtzOjE3OiJcYmV2YWxcKFsnIl1ccyovLyI7aTo2MztzOjE4OiJcYmV2YWxcKFsnIl1ccyovXCoiO2k6NjQ7czoxMDQ6IlwkW2EtekEtWjAtOV9dK1xzKj1cJFthLXpBLVowLTlfXStccypcKFwkW2EtekEtWjAtOV9dK1xzKixccypcJFthLXpBLVowLTlfXStccypcKFsnIl1ccyp7XCRbYS16QS1aMC05X10rIjtpOjY1O3M6MzE6IiFlcmVnXChbJyJdXF5cKHVuc2FmZV9yYXdcKVw/XCQiO2k6NjY7czozNToiXCRiYXNlX2RvbWFpblxzKj1ccypnZXRfYmFzZV9kb21haW4iO2k6Njc7czo5OiJzZXhzZXhzZXgiO2k6Njg7czoyMzoiXCt1bmlvblwrc2VsZWN0XCswLDAsMCwiO2k6Njk7czozNzoiY29uY2F0XCgweDIxN2UscGFzc3dvcmQsMHgzYSx1c2VybmFtZSI7aTo3MDtzOjM0OiJncm91cF9jb25jYXRcKDB4MjE3ZSxwYXNzd29yZCwweDNhIjtpOjcxO3M6NTc6IlwqL1xzKlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKi9cKiI7aTo3MjtzOjg6ImFiYWtvL0FPIjtpOjczO3M6NDg6ImlmXChccypzdHJwb3NcKFxzKlwkdmFsdWVccyosXHMqXCRtYXNrXHMqXClccypcKSI7aTo3NDtzOjEwNjoidW5saW5rXChccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfURPQ1VNRU5UX1JPT1RbJyJdezAsMX1cXVxzKlwuXHMqWyciXXswLDF9L2Fzc2V0cy9jYWNoZS90ZW1wL0ZpbGVTZXR0aW5ncyI7aTo3NTtzOjM4OiJzZXRUaW1lb3V0XChccypbJyJdbG9jYXRpb25cLnJlcGxhY2VcKCI7aTo3NjtzOjQzOiJzdHJwb3NcKFwkaW1ccyosXHMqWyciXTxcP1snIl1ccyosXHMqXCRpXCsxIjtpOjc3O3M6MjA6IlwkX1JFUVVFU1RcW1snIl1sYWxhIjtpOjc4O3M6MjM6IjBccypcKFxzKmd6dW5jb21wcmVzc1woIjtpOjc5O3M6MTU6Imd6aW5mbGF0ZVwoXChcKCI7aTo4MDtzOjQyOiJcJGtleVxzKj1ccypcJF9HRVRcW1snIl17MCwxfXFbJyJdezAsMX1cXTsiO2k6ODE7czoyNzoic3RybGVuXChccypcJHBhdGhUb0RvclxzKlwpIjtpOjgyO3M6NjQ6Imlzc2V0XChccypcJF9DT09LSUVcW1xzKm1kNVwoXHMqXCRfU0VSVkVSXFtccypbJyJdezAsMX1IVFRQX0hPU1QiO2k6ODM7czoyNzoiQGNoZGlyXChccypcJF9QT1NUXFtccypbJyJdIjtpOjg0O3M6ODQ6Ii9pbmRleFwucGhwXD9vcHRpb249Y29tX2NvbnRlbnQmdmlldz1hcnRpY2xlJmlkPVsnIl1cLlwkcG9zdFxbWyciXXswLDF9aWRbJyJdezAsMX1cXSI7aTo4NTtzOjU1OiJcJG91dFxzKlwuPVxzKlwkdGV4dHtccypcJGlccyp9XHMqXF5ccypcJGtleXtccypcJGpccyp9IjtpOjg2O3M6OToiTDNaaGNpOTNkIjtpOjg3O3M6NDc6InN0cnRvbG93ZXJcKFxzKnN1YnN0clwoXHMqXCR1c2VyX2FnZW50XHMqLFxzKjAsIjtpOjg4O3M6NDQ6ImNobW9kXChccypcJFtccyVcLkBcLVwrXChcKS9cd10rP1xzKixccyowNDA0IjtpOjg5O3M6NDQ6ImNobW9kXChccypcJFtccyVcLkBcLVwrXChcKS9cd10rP1xzKixccyowNzU1IjtpOjkwO3M6NDI6IkB1bWFza1woXHMqMDc3N1xzKiZccyp+XHMqXCRmaWxlcGVybWlzc2lvbiI7aTo5MTtzOjIzOiJbJyJdXHMqXHxccyovYmluL3NoWyciXSI7aTo5MjtzOjE2OiI7XHMqL2Jpbi9zaFxzKi1pIjtpOjkzO3M6NDE6ImlmXHMqXChmdW5jdGlvbl9leGlzdHNcKFxzKlsnIl1wY250bF9mb3JrIjtpOjk0O3M6MjY6Ij1ccypbJyJdc2VuZG1haWxccyotdFxzKi1mIjtpOjk1O3M6MTU6Ii90bXAvdG1wLXNlcnZlciI7aTo5NjtzOjE1OiIvdG1wL1wuSUNFLXVuaXgiO2k6OTc7czoyOToiZXhlY1woXHMqWyciXS9iaW4vc2hbJyJdXHMqXCkiO2k6OTg7czoyNzoiXC5cLi9cLlwuL1wuXC4vXC5cLi9tb2R1bGVzIjtpOjk5O3M6MzM6InRvdWNoXHMqXChccypkaXJuYW1lXChccypfX0ZJTEVfXyI7aToxMDA7czo0OToiQHRvdWNoXHMqXChccypcJGN1cmZpbGVccyosXHMqXCR0aW1lXHMqLFxzKlwkdGltZSI7aToxMDE7czoxODoiLVwqLVxzKmNvbmZccyotXCotIjtpOjEwMjtzOjQ0OiJvcGVuXHMqXChccypNWUZJTEVccyosXHMqWyciXVxzKj5ccyp0YXJcLnRtcCI7aToxMDM7czo3NDoiXCRyZXQgPSBcJHRoaXMtPl9kYi0+dXBkYXRlT2JqZWN0XCggXCR0aGlzLT5fdGJsLCBcJHRoaXMsIFwkdGhpcy0+X3RibF9rZXkiO2k6MTA0O3M6MTk6ImRpZVwoXHMqWyciXW5vIGN1cmwiO2k6MTA1O3M6NTQ6InN1YnN0clwoXHMqXCRyZXNwb25zZVxzKixccypcJGluZm9cW1xzKlsnIl1oZWFkZXJfc2l6ZSI7aToxMDY7czoxMDg6ImlmXChccyohc29ja2V0X3NlbmR0b1woXHMqXCRzb2NrZXRccyosXHMqXCRkYXRhXHMqLFxzKnN0cmxlblwoXHMqXCRkYXRhXHMqXClccyosXHMqMFxzKixccypcJGlwXHMqLFxzKlwkcG9ydCI7aToxMDc7czo1MDoiPGlucHV0XHMrdHlwZT1zdWJtaXRccyt2YWx1ZT1VcGxvYWRccyovPlxzKjwvZm9ybT4iO2k6MTA4O3M6NTg6InJvdW5kXHMqXChccypcKFxzKlwkcGFja2V0c1xzKlwqXHMqNjVcKVxzKi9ccyoxMDI0XHMqLFxzKjIiO2k6MTA5O3M6NTc6IkBlcnJvcl9yZXBvcnRpbmdcKFxzKjBccypcKTtccyppZlxzKlwoXHMqIWlzc2V0XHMqXChccypcJCI7aToxMTA7czozMDoiZWxzZVxzKntccyplY2hvXHMqWyciXWZhaWxbJyJdIjtpOjExMTtzOjUxOiJ0eXBlPVsnIl1zdWJtaXRbJyJdXHMqdmFsdWU9WyciXVVwbG9hZCBmaWxlWyciXVxzKj4iO2k6MTEyO3M6Mzc6ImhlYWRlclwoXHMqWyciXUxvY2F0aW9uOlxzKlwkbGlua1snIl0iO2k6MTEzO3M6MzE6ImVjaG9ccypbJyJdPGI+VXBsb2FkPHNzPlN1Y2Nlc3MiO2k6MTE0O3M6NDM6Im5hbWU9WyciXXVwbG9hZGVyWyciXVxzK2lkPVsnIl11cGxvYWRlclsnIl0iO2k6MTE1O3M6MjE6Ii1JL3Vzci9sb2NhbC9iYW5kbWFpbiI7aToxMTY7czoyNDoidW5saW5rXChccypfX0ZJTEVfX1xzKlwpIjtpOjExNztzOjU2OiJtYWlsXChccypcJGFyclxbWyciXXRvWyciXVxdXHMqLFxzKlwkYXJyXFtbJyJdc3VialsnIl1cXSI7aToxMTg7czoxMjc6ImlmXChpc3NldFwoXCRfUkVRVUVTVFxbWyciXVthLXpBLVowLTlfXStbJyJdXF1cKVwpXHMqe1xzKlwkW2EtekEtWjAtOV9dK1xzKj1ccypcJF9SRVFVRVNUXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXTtccypleGl0XChcKTsiO2k6MTE5O3M6MTM6Im51bGxfZXhwbG9pdHMiO2k6MTIwO3M6NDY6IjxcP1xzKlwkW2EtekEtWjAtOV9dK1woXHMqXCRbYS16QS1aMC05X10rXHMqXCkiO2k6MTIxO3M6OToidG12YXN5bmdyIjtpOjEyMjtzOjEyOiJ0bWhhcGJ6Y2VyZmYiO2k6MTIzO3M6MTM6Im9uZnI2NF9xcnBicXIiO2k6MTI0O3M6MTQ6IlsnIl1uZmZyZWdbJyJdIjtpOjEyNTtzOjk6ImZnZV9lYmcxMyI7aToxMjY7czo3OiJjdWN2YXNiIjtpOjEyNztzOjE0OiJbJyJdZmxmZ3J6WyciXSI7aToxMjg7czoxMjoiWyciXXJpbnlbJyJdIjtpOjEyOTtzOjk6ImV0YWxmbml6ZyI7aToxMzA7czoxMjoic3NlcnBtb2NudXpnIjtpOjEzMTtzOjEzOiJlZG9jZWRfNDZlc2FiIjtpOjEzMjtzOjE0OiJbJyJddHJlc3NhWyciXSI7aToxMzM7czoxNzoiWyciXTMxdG9yX3J0c1snIl0iO2k6MTM0O3M6MTU6IlsnIl1vZm5pcGhwWyciXSI7aToxMzU7czoxNDoiWyciXWZsZmdyelsnIl0iO2k6MTM2O3M6MTI6IlsnIl1yaW55WyciXSI7aToxMzc7czo0MjoiQFwkW2EtekEtWjAtOV9dK1woXHMqXCRbYS16QS1aMC05X10rXHMqXCk7IjtpOjEzODtzOjQ4OiJwYXJzZV9xdWVyeV9zdHJpbmdcKFxzKlwkRU5We1xzKlsnIl1RVUVSWV9TVFJJTkciO2k6MTM5O3M6MzE6ImV2YWxccypcKFxzKm1iX2NvbnZlcnRfZW5jb2RpbmciO2k6MTQwO3M6MjQ6IlwpXHMqe1xzKnBhc3N0aHJ1XChccypcJCI7aToxNDE7czoxNToiSFRUUF9BQ0NFUFRfQVNFIjtpOjE0MjtzOjIxOiJmdW5jdGlvblxzKkN1cmxBdHRhY2siO2k6MTQzO3M6MTg6IkBzeXN0ZW1cKFxzKlsnIl1cJCI7aToxNDQ7czoyMzoiZWNob1woXHMqaHRtbFwoXHMqYXJyYXkiO2k6MTQ1O3M6NTY6IlwkY29kZT1bJyJdJTFzY3JpcHRccyp0eXBlPVxcWyciXXRleHQvamF2YXNjcmlwdFxcWyciXSUzIjtpOjE0NjtzOjIyOiJhcnJheVwoXHMqWyciXSUxaHRtbCUzIjtpOjE0NztzOjE5OiJidWRha1xzKi1ccypleHBsb2l0IjtpOjE0ODtzOjkxOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXHMqXChccypbJyJdXCRbYS16QS1aMC05X10rWyciXVxzKlwpIjtpOjE0OTtzOjk6IkdBR0FMPC9iPiI7aToxNTA7czozODoiZXhpdFwoWyciXTxzY3JpcHQ+ZG9jdW1lbnRcLmxvY2F0aW9uXC4iO2k6MTUxO3M6Mzc6ImRpZVwoWyciXTxzY3JpcHQ+ZG9jdW1lbnRcLmxvY2F0aW9uXC4iO2k6MTUyO3M6MzY6InNldF90aW1lX2xpbWl0XChccyppbnR2YWxcKFxzKlwkYXJndiI7aToxNTM7czozMzoiZWNob1xzKlwkcHJld3VlXC5cJGxvZ1wuXCRwb3N0d3VlIjtpOjE1NDtzOjQyOiJjb25uXHMqPVxzKmh0dHBsaWJcLkhUVFBDb25uZWN0aW9uXChccyp1cmkiO2k6MTU1O3M6MzY6ImlmXHMqXChccypcJF9QT1NUXFtbJyJdezAsMX1jaG1vZDc3NyI7aToxNTY7czoyNjoiPFw/XHMqZWNob1xzKlwkY29udGVudDtcPz4iO2k6MTU3O3M6ODQ6IlwkdXJsXHMqXC49XHMqWyciXVw/W2EtekEtWjAtOV9dKz1bJyJdXHMqXC5ccypcJF9HRVRcW1xzKlsnIl1bYS16QS1aMC05X10rWyciXVxzKlxdOyI7aToxNTg7czoxMDg6ImNvcHlcKFxzKlwkX0ZJTEVTXFtccypbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XHMqXF1cW1xzKlsnIl17MCwxfXRtcF9uYW1lWyciXXswLDF9XHMqXF1ccyosXHMqXCRfUE9TVCI7aToxNTk7czoxMTU6Im1vdmVfdXBsb2FkZWRfZmlsZVwoXHMqXCRfRklMRVNcW1xzKlsnIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1ccypcXVxbWyciXXswLDF9dG1wX25hbWVbJyJdezAsMX1cXVxbXHMqXCRpXHMqXF0iO2k6MTYwO3M6MzI6ImRuc19nZXRfcmVjb3JkXChccypcJGRvbWFpblxzKlwuIjtpOjE2MTtzOjM0OiJmdW5jdGlvbl9leGlzdHNccypcKFxzKlsnIl1nZXRteHJyIjtpOjE2MjtzOjI0OiJuc2xvb2t1cFwuZXhlXHMqLXR5cGU9TVgiO2k6MTYzO3M6MTI6Im5ld1xzKk1DdXJsOyI7aToxNjQ7czo0NDoiXCRmaWxlX2RhdGFccyo9XHMqWyciXTxzY3JpcHRccypzcmM9WyciXWh0dHAiO2k6MTY1O3M6NDA6ImZwdXRzXChcJGZwLFxzKlsnIl1JUDpccypcJGlwXHMqLVxzKkRBVEUiO2k6MTY2O3M6Mjg6ImNobW9kXChccypfX0RJUl9fXHMqLFxzKjA0MDAiO2k6MTY3O3M6NDA6IkNvZGVNaXJyb3JcLmRlZmluZU1JTUVcKFxzKlsnIl10ZXh0L21pcmMiO2k6MTY4O3M6NDM6IlxdXHMqXClccypcLlxzKlsnIl1cXG5cPz5bJyJdXHMqXClccypcKVxzKnsiO2k6MTY5O3M6Njc6IlwkZ3pwXHMqPVxzKlwkYmd6RXhpc3RccypcP1xzKkBnem9wZW5cKFwkdG1wZmlsZSxccypbJyJdcmJbJyJdXHMqXCkiO2k6MTcwO3M6NzU6ImZ1bmN0aW9uPHNzPnNtdHBfbWFpbFwoXCR0b1xzKixccypcJHN1YmplY3RccyosXHMqXCRtZXNzYWdlXHMqLFxzKlwkaGVhZGVycyI7aToxNzE7czo2NDoiXCRfUE9TVFxbWyciXXswLDF9YWN0aW9uWyciXXswLDF9XF1ccyo9PVxzKlsnIl1nZXRfYWxsX2xpbmtzWyciXSI7aToxNzI7czozODoiPVxzKmd6aW5mbGF0ZVwoXHMqYmFzZTY0X2RlY29kZVwoXHMqXCQiO2k6MTczO3M6NDE6ImNobW9kXChcJGZpbGUtPmdldFBhdGhuYW1lXChcKVxzKixccyowNzc3IjtpOjE3NDtzOjYzOiJcJF9QT1NUXFtbJyJdezAsMX10cDJbJyJdezAsMX1cXVxzKlwpXHMqYW5kXHMqaXNzZXRcKFxzKlwkX1BPU1QiO2k6MTc1O3M6MTA5OiJoZWFkZXJcKFxzKlsnIl1Db250ZW50LVR5cGU6XHMqaW1hZ2UvanBlZ1snIl1ccypcKTtccypyZWFkZmlsZVwoXHMqWyciXVthLXpBLVowLTlfXStbJyJdXHMqXCk7XHMqZXhpdFwoXHMqXCk7IjtpOjE3NjtzOjMxOiI9PlxzKkBcJGYyXChfX0ZJTEVfX1xzKixccypcJGYxIjtpOjE3NztzOjgzOiJcYmV2YWxcKFxzKlthLXpBLVowLTlfXStcKFxzKlwkW2EtekEtWjAtOV9dK1xzKixccypcJFthLXpBLVowLTlfXStccypcKVxzKlwpO1xzKlw/PiI7aToxNzg7czozNzoiaWZccypcKFxzKmlzX2NyYXdsZXIxXChccypcKVxzKlwpXHMqeyI7aToxNzk7czo0ODoiXCRlY2hvXzFcLlwkZWNob18yXC5cJGVjaG9fM1wuXCRlY2hvXzRcLlwkZWNob181IjtpOjE4MDtzOjM1OiJmaWxlX2dldF9jb250ZW50c1woXHMqX19GSUxFX19ccypcKSI7aToxODE7czo4MzoiQFxiKGZ0cF9leGVjfHN5c3RlbXxzaGVsbF9leGVjfHBhc3N0aHJ1fHBvcGVufHByb2Nfb3BlbilcKFxzKkB1cmxlbmNvZGVcKFxzKlwkX1BPU1QiO2k6MTgyO3M6OTU6IlwkR0xPQkFMU1xbWyciXVthLXpBLVowLTlfXStbJyJdXF1cW1wkR0xPQkFMU1xbWyciXVthLXpBLVowLTlfXStbJyJdXF1cW1xkK1xdXChyb3VuZFwoXGQrXClcKVxdIjtpOjE4MztzOjI1OiJmdW5jdGlvblxzK2Vycm9yXzQwNFwoXCl7IjtpOjE4NDtzOjY4OiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXChccypbJyJdcGVybCI7aToxODU7czo3MDoiXGIoZnRwX2V4ZWN8c3lzdGVtfHNoZWxsX2V4ZWN8cGFzc3RocnV8cG9wZW58cHJvY19vcGVuKVwoXHMqWyciXXB5dGhvbiI7aToxODY7czo3MzoiaWZccypcKGlzc2V0XChcJF9HRVRcW1snIl1bYS16QS1aMC05X10rWyciXVxdXClcKVxzKntccyplY2hvXHMqWyciXW9rWyciXSI7aToxODc7czozOToicmVscGF0aHRvYWJzcGF0aFwoXHMqXCRfR0VUXFtccypbJyJdY3B5IjtpOjE4ODtzOjQ1OiJodHRwOi8vLis/Ly4rP1wucGhwXD9hPVxkKyZjPVthLXpBLVowLTlfXSsmcz0iO2k6MTg5O3M6MTY6ImZ1bmN0aW9uXHMrd3NvRXgiO2k6MTkwO3M6NTE6ImZvcmVhY2hcKFxzKlwkdG9zXHMqYXNccypcJHRvXClccyp7XHMqbWFpbFwoXHMqXCR0byI7aToxOTE7czoxMDI6ImhlYWRlclwoXHMqWyciXUNvbnRlbnQtVHlwZTpccyppbWFnZS9qcGVnWyciXVxzKlwpO1xzKnJlYWRmaWxlXChbJyJdaHR0cDovLy4rP1wuanBnWyciXVwpO1xzKmV4aXRcKFwpOyI7aToxOTI7czoxMjoiPFw/PVwkY2xhc3M7IjtpOjE5MztzOjUwOiI8aW5wdXRccyp0eXBlPSJmaWxlIlxzKnNpemU9IlxkKyJccypuYW1lPSJ1cGxvYWQiPiI7aToxOTQ7czoxMTA6IlwkbWVzc2FnZXNcW1xdXHMqPVxzKlwkX0ZJTEVTXFtccypbJyJdezAsMX11c2VyZmlsZVsnIl17MCwxfVxzKlxdXFtccypbJyJdezAsMX1uYW1lWyciXXswLDF9XHMqXF1cW1xzKlwkaVxzKlxdIjtpOjE5NTtzOjU1OiI8aW5wdXRccyp0eXBlPVsnIl1maWxlWyciXVxzKm5hbWU9WyciXXVzZXJmaWxlWyciXVxzKi8+IjtpOjE5NjtzOjEzOiJEZXZhcnRccytIVFRQIjtpOjE5NztzOjg3OiJAXCR7XHMqW2EtekEtWjAtOV9dK1xzKn1cKFxzKlsnIl1bJyJdXHMqLFxzKlwke1xzKlthLXpBLVowLTlfXStccyp9XChccypcJFthLXpBLVowLTlfXSsiO2k6MTk4O3M6OTI6IlwkR0xPQkFMU1xbXHMqWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxzKlxdXChccypcJFthLXpBLVowLTlfXStcW1xzKlwkW2EtekEtWjAtOV9dK1xdIjtpOjE5OTtzOjUzOiJlcnJvcl9yZXBvcnRpbmdcKFxzKjBccypcKTtccypcJHVybFxzKj1ccypbJyJdaHR0cDovLyI7aToyMDA7czoxMjA6IlwkW2EtekEtWjAtOV9dKz1bJyJdaHR0cDovLy4rP1snIl07XHMqXCRbYS16QS1aMC05X10rPWZvcGVuXChcJFthLXpBLVowLTlfXSssWyciXXJbJyJdXCk7XHMqcmVhZGZpbGVcKFwkW2EtekEtWjAtOV9dK1wpOyI7aToyMDE7czo3NToiYXJyYXlcKFxzKlsnIl08IS0tWyciXVxzKlwuXHMqbWQ1XChccypcJHJlcXVlc3RfdXJsXHMqXC5ccypyYW5kXChcZCssXHMqXGQrIjtpOjIwMjtzOjE0OiJ3c29IZWFkZXJccypcKCI7aToyMDM7czo2OToiZWNob1woWyciXTxmb3JtIG1ldGhvZD1bJyJdcG9zdFsnIl1ccyplbmN0eXBlPVsnIl1tdWx0aXBhcnQvZm9ybS1kYXRhIjtpOjIwNDtzOjQzOiJmaWxlX2dldF9jb250ZW50c1woXHMqYmFzZTY0X2RlY29kZVwoXHMqXCRfIjtpOjIwNTtzOjY0OiJyZWxwYXRodG9hYnNwYXRoXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbIjtpOjIwNjtzOjQwOiJtYWlsXChcJHRvXHMqLFxzKlsnIl0uKz9bJyJdXHMqLFxzKlwkdXJsIjtpOjIwNztzOjUxOiJpZlxzKlwoXHMqIWZ1bmN0aW9uX2V4aXN0c1woXHMqWyciXXN5c19nZXRfdGVtcF9kaXIiO2k6MjA4O3M6MTc6Ijx0aXRsZT5ccypWYVJWYVJhIjtpOjIwOTtzOjM4OiJlbHNlaWZcKFxzKlwkc3FsdHlwZVxzKj09XHMqWyciXXNxbGl0ZSI7aToyMTA7czoxOToiPVsnIl1cKVxzKlwpO1xzKlw/PiI7aToyMTE7czoyNDoiZWNob1xzK2Jhc2U2NF9kZWNvZGVcKFwkIjtpOjIxMjtzOjUwOiJcI1thLXpBLVowLTlfXStcIy4rPzwvc2NyaXB0Pi4rP1wjL1thLXpBLVowLTlfXStcIyI7aToyMTM7czozNDoiZnVuY3Rpb25ccytfX2ZpbGVfZ2V0X3VybF9jb250ZW50cyI7aToyMTQ7czo1NDoiXCRmXHMqPVxzKlwkZlxkK1woWyciXVsnIl1ccyosXHMqZXZhbFwoXCRbYS16QS1aMC05X10rIjtpOjIxNTtzOjMyOiJldmFsXChcJGNvbnRlbnRcKTtccyplY2hvXHMqWyciXSI7aToyMTY7czoyOToiQ1VSTE9QVF9VUkxccyosXHMqWyciXXNtdHA6Ly8iO2k6MjE3O3M6Nzc6IjxoZWFkPlxzKjxzY3JpcHQ+XHMqd2luZG93XC50b3BcLmxvY2F0aW9uXC5ocmVmPVsnIl0uKz9ccyo8L3NjcmlwdD5ccyo8L2hlYWQ+IjtpOjIxODtzOjcwOiJcJFthLXpBLVowLTlfXStccyo9XHMqZm9wZW5cKFxzKlsnIl1bYS16QS1aMC05X10rXC5waHBbJyJdXHMqLFxzKlsnIl13IjtpOjIxOTtzOjE2OiJAYXNzZXJ0XChccypbJyJdIjtpOjIyMDtzOjg4OiJcJFthLXpBLVowLTlfXSs9XCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1xzKlsnIl1kb1snIl1ccypcXTtccyppbmNsdWRlIjtpOjIyMTtzOjc3OiJlY2hvXHMrXCRbYS16QS1aMC05X10rO21rZGlyXChccypbJyJdW2EtekEtWjAtOV9dK1snIl1ccypcKTtmaWxlX3B1dF9jb250ZW50cyI7aToyMjI7czo2NzoiXCRmcm9tXHMqPVxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtccypbJyJdZnJvbSI7aToyMjM7czoxOToiPVxzKnhkaXJcKFxzKlwkcGF0aCI7aToyMjQ7czozMDoiXCRfW2EtekEtWjAtOV9dK1woXHMqXCk7XHMqXD8+IjtpOjIyNTtzOjEwOiJ0YXJccystemNDIjtpOjIyNjtzOjgzOiJlY2hvXHMrc3RyX3JlcGxhY2VcKFxzKlsnIl1cW1BIUF9TRUxGXF1bJyJdXHMqLFxzKmJhc2VuYW1lXChcJF9TRVJWRVJcW1snIl1QSFBfU0VMRiI7aToyMjc7czo0MDoiZnVuY3Rpb25fZXhpc3RzXChccypbJyJdZlwkW2EtekEtWjAtOV9dKyI7aToyMjg7czo0MDoiXCRjdXJfY2F0X2lkXHMqPVxzKlwoXHMqaXNzZXRcKFxzKlwkX0dFVCI7aToyMjk7czozNToiaHJlZj1bJyJdPFw/cGhwXHMrZWNob1xzK1wkY3VyX3BhdGgiO2k6MjMwO3M6MzM6Ij1ccyplc2NfdXJsXChccypzaXRlX3VybFwoXHMqWyciXSI7aToyMzE7czo4NToiXlxzKjxcP3BocFxzKmhlYWRlclwoXHMqWyciXUxvY2F0aW9uOlxzKlsnIl1ccypcLlxzKlsnIl1ccypodHRwOi8vLis/WyciXVxzKlwpO1xzKlw/PiI7aToyMzI7czoxNDoiPHRpdGxlPlxzKml2bnoiO2k6MjMzO3M6NjM6Il5ccyo8XD9waHBccypoZWFkZXJcKFsnIl1Mb2NhdGlvbjpccypodHRwOi8vLis/WyciXVxzKlwpO1xzKlw/PiI7aToyMzQ7czo2MToiZ2V0X3VzZXJzXChccyphcnJheVwoXHMqWyciXXJvbGVbJyJdXHMqPT5ccypbJyJdYWRtaW5pc3RyYXRvciI7aToyMzU7czo3MToiXCR0b1xzKj1ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbXHMqWyciXXRvX2FkZHJlc3MiO2k6MjM2O3M6MTk6ImltYXBfaGVhZGVyaW5mb1woXCQiO2k6MjM3O3M6MzQ6ImV2YWxcKFxzKlsnIl1cPz5bJyJdXHMqXC5ccypqb2luXCgiO2k6MjM4O3M6MzU6ImJlZ2luXHMrbW9kOlxzK1RoYW5rc1xzK2ZvclxzK3Bvc3RzIjtpOjIzOTtzOjMxOiJbJyJdXHMqXF5ccypcJFthLXpBLVowLTlfXStccyo7IjtpOjI0MDtzOjY1OiJcJFthLXpBLVowLTlfXStccypcLlxzKlwkW2EtekEtWjAtOV9dK1xzKlxeXHMqXCRbYS16QS1aMC05X10rXHMqOyI7aToyNDE7czoxMjA6ImlmXChpc3NldFwoXCRfUkVRVUVTVFxbWyciXVthLXpBLVowLTlfXStbJyJdXF1cKVxzKiYmXHMqbWQ1XChcJF9SRVFVRVNUXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cKVxzKj09XHMqWyciXSI7aToyNDI7czoxMjoiXC53d3cvLzpwdHRoIjtpOjI0MztzOjYzOiIlNjMlNzIlNjklNzAlNzQlMkUlNzMlNzIlNjMlM0QlMjclNjglNzQlNzQlNzAlM0ElMkYlMkYlNzMlNkYlNjEiO2k6MjQ0O3M6Mjc6IndwLW9wdGlvbnNcLnBocFxzKj5ccypFcnJvciI7aToyNDU7czo4OToic3RyX3JlcGxhY2VcKGFycmF5XChbJyJdZmlsdGVyU3RhcnRbJyJdLFsnIl1maWx0ZXJFbmRbJyJdXCksXHMqYXJyYXlcKFsnIl1cKi9bJyJdLFsnIl0vXCoiO2k6MjQ2O3M6Mzc6ImZpbGVfZ2V0X2NvbnRlbnRzXChfX0ZJTEVfX1wpLFwkbWF0Y2giO2k6MjQ3O3M6MzA6InRvdWNoXChccypkaXJuYW1lXChccypfX0ZJTEVfXyI7aToyNDg7czoyMToiXHxib3RcfHNwaWRlclx8d2dldC9pIjtpOjI0OTtzOjYyOiJzdHJfcmVwbGFjZVwoWyciXTwvYm9keT5bJyJdLFthLXpBLVowLTlfXStcLlsnIl08L2JvZHk+WyciXSxcJCI7aToyNTA7czozNDoiZXhwbG9kZVwoWyciXTt0ZXh0O1snIl0sXCRyb3dcWzBcXSI7aToyNTE7czo5MDoibWFpbFwoXHMqc3RyaXBzbGFzaGVzXChccypcJFthLXpBLVowLTlfXStccypcKVxzKixccypzdHJpcHNsYXNoZXNcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwpIjtpOjI1MjtzOjIwODoiPVxzKm1haWxcKFxzKnN0cmlwc2xhc2hlc1woXHMqXCRfUE9TVFxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdXClccyosXHMqc3RyaXBzbGFzaGVzXChccypcJF9QT1NUXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1cKVxzKixccypzdHJpcHNsYXNoZXNcKFxzKlwkX1BPU1RcW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXSI7aToyNTM7czoxNTM6Ij1ccyptYWlsXChccypcJF9QT1NUXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF1ccyosXHMqXCRfUE9TVFxbWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxdXHMqLFxzKlwkX1BPU1RcW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXSI7aToyNTQ7czoxNDoiTGliWG1sMklzQnVnZ3kiO2k6MjU1O3M6OToibWFhZlxzK3lhIjtpOjI1NjtzOjM0OiJlY2hvIFthLXpBLVowLTlfXStccypcKFsnIl1odHRwOi8vIjtpOjI1NztzOjU0OiJcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXWFzc3VudG8iO2k6MjU4O3M6MTI6ImBjaGVja3N1ZXhlYyI7aToyNTk7czoxODoid2hpY2hccytzdXBlcmZldGNoIjtpOjI2MDtzOjQ1OiJybWRpcnNcKFwkZGlyXHMqXC5ccypbJyJdL1snIl1ccypcLlxzKlwkY2hpbGQiO2k6MjYxO3M6NDI6ImV4cGxvZGVcKFxzKlxcWyciXTt0ZXh0O1xcWyciXVxzKixccypcJHJvdyI7aToyNjI7czozNzoiPVxzKlsnIl1waHBfdmFsdWVccythdXRvX3ByZXBlbmRfZmlsZSI7aToyNjM7czozNToiaWZccypcKFxzKmlzX3dyaXRhYmxlXChccypcJHd3d1BhdGgiO2k6MjY0O3M6NDY6ImZvcGVuXChccypcJFthLXpBLVowLTlfXStccypcLlxzKlsnIl0vd3AtYWRtaW4iO2k6MjY1O3M6MjI6InJldHVyblxzKlsnIl0vdmFyL3d3dy8iO2k6MjY2O3M6MjA6IlsnIl07XCRcd3sxLDQ1fTtbJyJdIjtpOjI2NztzOjE0MToiY2FsbF91c2VyX2Z1bmNfYXJyYXlcKFxzKlsnIl1bYS16QS1aMC05X10rWyciXVxzKixccyphcnJheVwoXHMqWyciXS4qP1snIl1ccyosXHMqZ2V0ZW52XChccypbJyJdLio/WyciXVxzKlwpXHMqLFxzKlsnIl0uKj9bJyJdXHMqXClccypcKTtccyp9IjtpOjI2ODtzOjExNDoiXCRbYS16QS1aMC05X10rXHMqPVxzKmNoclwoXGQrXHMqXF5ccypcZCtcKVwuY2hyXChcZCtccypcXlxzKlxkK1wpXC5jaHJcKFxkK1xzKlxeXHMqXGQrXClcLmNoclwoXGQrXHMqXF5ccypcZCtcKVwuIjtpOjI2OTtzOjM2OiI8c2NyaXB0XHMrc3JjPVsnIl0vXD9cJFNFUlZFUl9JUD1cZCsiO2k6MjcwO3M6NTg6IkA/XCR7XHMqWyciXXswLDF9XHd7MSw0NX1bJyJdezAsMX1ccyp9XChccypcJFx3ezEsNDV9XHMqXCkiO2k6MjcxO3M6NDI6IkA/XCR7XHMqXCRcd3sxLDQ1fVxzKn1cKFxzKlwkXHd7MSw0NX1ccypcKSI7aToyNzI7czoxNDE6IlxiKGZvcGVufGZpbGVfZ2V0X2NvbnRlbnRzfGZpbGVfcHV0X2NvbnRlbnRzfHN0YXR8Y2htb2R8ZmlsZXxzeW1saW5rKVxzKlwoXHMqWyciXWh0dHA6Ly9bJyJdXHMqXC5ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aToyNzM7czoxODY6IlwkW2EtekEtWjAtOV9dK1xzKntccypcZCtccyp9XC5cJFthLXpBLVowLTlfXStccyp7XHMqXGQrXHMqfVwuXCRbYS16QS1aMC05X10rXHMqe1xzKlxkK1xzKn1cLlwkW2EtekEtWjAtOV9dK1xzKntccypcZCtccyp9XC5cJFthLXpBLVowLTlfXStccyp7XHMqXGQrXHMqfVwuXCRbYS16QS1aMC05X10rXHMqe1xzKlxkK1xzKn1cLiI7aToyNzQ7czoxNjoidGFncy9cJDYvXCQ0L1wkNyI7aToyNzU7czozMDoic3RyX3JlcGxhY2VcKFxzKlsnIl1cLmh0YWNjZXNzIjtpOjI3NjtzOjQzOiJmdW5jdGlvblxzK19cZCtcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwpe1wkIjtpOjI3NztzOjIxOiJleHBsb2RlXChcXFsnIl07dGV4dDsiO2k6Mjc4O3M6MTIzOiJzdWJzdHJcKFxzKlwkW2EtekEtWjAtOV9dK1xzKixccypcZCtccyosXHMqXGQrXHMqXCk7XHMqXCRbYS16QS1aMC05X10rXHMqPVxzKnByZWdfcmVwbGFjZVwoXHMqXCRbYS16QS1aMC05X10rXHMqLFxzKnN0cnRyXCgiO2k6Mjc5O3M6NjY6ImFycmF5X2ZsaXBcKFxzKmFycmF5X21lcmdlXChccypyYW5nZVwoXHMqWyciXUFbJyJdXHMqLFxzKlsnIl1aWyciXSI7aToyODA7czo2MzoiXCRfU0VSVkVSXFtccypbJyJdRE9DVU1FTlRfUk9PVFsnIl1ccypcXVxzKlwuXHMqWyciXS9cLmh0YWNjZXNzIjtpOjI4MTtzOjMxOiJcJGluc2VydF9jb2RlXHMqPVxzKlsnIl08aWZyYW1lIjtpOjI4MjtzOjQxOiJhc3NlcnRfb3B0aW9uc1woXHMqQVNTRVJUX1dBUk5JTkdccyosXHMqMCI7aToyODM7czoxNToiTXVzdEBmQFxzK1NoZWxsIjtpOjI4NDtzOjY2OiJcYmV2YWxcKFxzKlwkW2EtekEtWjAtOV9dK1woXHMqXCRbYS16QS1aMC05X10rXChccypcJFthLXpBLVowLTlfXSsiO2k6Mjg1O3M6MzQ6ImZ1bmN0aW9uX2V4aXN0c1woXHMqWyciXXBjbnRsX2ZvcmsiO2k6Mjg2O3M6NDA6InN0cl9yZXBsYWNlXChbJyJdXC5odGFjY2Vzc1snIl1ccyosXHMqXCQiO2k6Mjg3O3M6MzM6Ij1ccypAP2d6aW5mbGF0ZVwoXHMqc3RycmV2XChccypcJCI7aToyODg7czoyMjoiZ1woXHMqWyciXUZpbGVzTWFuWyciXSI7aToyODk7czoyODoic3RyX3JlcGxhY2VcKFsnIl0vXD9hbmRyWyciXSI7aToyOTA7czoyMDQ6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypcJF9SRVFVRVNUXFtbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XF07XHMqXCRbYS16QS1aMC05X10rXHMqPVxzKmFycmF5XChccypcJF9SRVFVRVNUXFtccypbJyJdezAsMX1bYS16QS1aMC05X10rWyciXXswLDF9XHMqXF1ccypcKTtccypcJFthLXpBLVowLTlfXStccyo9XHMqYXJyYXlfZmlsdGVyXChccypcJCI7aToyOTE7czoxMjg6IlwkW2EtekEtWjAtOV9dK1xzKlwuPVxzKlwkW2EtekEtWjAtOV9dK3tcZCt9XHMqXC5ccypcJFthLXpBLVowLTlfXSt7XGQrfVxzKlwuXHMqXCRbYS16QS1aMC05X10re1xkK31ccypcLlxzKlwkW2EtekEtWjAtOV9dK3tcZCt9IjtpOjI5MjtzOjc0OiJzdHJwb3NcKFwkbCxbJyJdTG9jYXRpb25bJyJdXCkhPT1mYWxzZVx8XHxzdHJwb3NcKFwkbCxbJyJdU2V0LUNvb2tpZVsnIl1cKSI7aToyOTM7czo5NzoiYWRtaW4vWyciXSxbJyJdYWRtaW5pc3RyYXRvci9bJyJdLFsnIl1hZG1pbjEvWyciXSxbJyJdYWRtaW4yL1snIl0sWyciXWFkbWluMy9bJyJdLFsnIl1hZG1pbjQvWyciXSI7aToyOTQ7czoxNToiWyciXWNoZWNrc3VleGVjIjtpOjI5NTtzOjU1OiJpZlxzKlwoXHMqXCR0aGlzLT5pdGVtLT5oaXRzXHMqPj1bJyJdXGQrWyciXVwpXHMqe1xzKlwkIjtpOjI5NjtzOjQ3OiJleHBsb2RlXChbJyJdXFxuWyciXSxccypcJF9QT1NUXFtbJyJddXJsc1snIl1cXSI7aToyOTc7czoxMTQ6ImlmXChpbmlfZ2V0XChbJyJdYWxsb3dfdXJsX2ZvcGVuWyciXVwpXHMqPT1ccyoxXClccyp7XHMqXCRbYS16QS1aMC05X10rXHMqPVxzKmZpbGVfZ2V0X2NvbnRlbnRzXChcJFthLXpBLVowLTlfXStcKSI7aToyOTg7czoxMjI6ImlmXChccypcJGZwXHMqPVxzKmZzb2Nrb3BlblwoXCR1XFtbJyJdaG9zdFsnIl1cXSwhZW1wdHlcKFwkdVxbWyciXXBvcnRbJyJdXF1cKVxzKlw/XHMqXCR1XFtbJyJdcG9ydFsnIl1cXVxzKjpccyo4MFxzKlwpXCl7IjtpOjI5OTtzOjg5OiJpbmNsdWRlXChccypbJyJdZGF0YTp0ZXh0L3BsYWluO2Jhc2U2NFxzKixccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbOyI7aTozMDA7czoyMToiaW5jbHVkZVwoXHMqWyciXXpsaWI6IjtpOjMwMTtzOjIxOiJpbmNsdWRlXChccypbJyJdL3RtcC8iO2k6MzAyO3M6NzA6IlwkZG9jXHMqPVxzKkpGYWN0b3J5OjpnZXREb2N1bWVudFwoXCk7XHMqXCRkb2MtPmFkZFNjcmlwdFwoWyciXWh0dHA6Ly8iO2k6MzAzO3M6MzA6IlwkZGVmYXVsdF91c2VfYWpheFxzKj1ccyp0cnVlOyI7aTozMDQ7czoxMDoiZGVrY2FoWyciXSI7aTozMDU7czoyMzoic3Vic3RyXChtZDVcKHN0cnJldlwoXCQiO2k6MzA2O3M6MTM6Ij09WyciXVwpXHMqXC4iO2k6MzA3O3M6MTAzOiJpZlxzKlwoXHMqXChccypcJFthLXpBLVowLTlfXStccyo9XHMqc3RycnBvc1woXCRbYS16QS1aMC05X10rXHMqLFxzKlsnIl1cPz5bJyJdXHMqXClccypcKVxzKj09PVxzKmZhbHNlIjtpOjMwODtzOjE1MzoiXCRfU0VSVkVSXFtbJyJdRE9DVU1FTlRfUk9PVFsnIl1ccypcLlxzKlsnIl0vWyciXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXHMqXC5ccypbJyJdL1snIl1ccypcLlxzKlwkW2EtekEtWjAtOV9dK1xzKlwuXHMqWyciXS9bJyJdXHMqXC5ccypcJFthLXpBLVowLTlfXSssIjtpOjMwOTtzOjMwOiJmb3BlblxzKlwoXHMqWyciXWJhZF9saXN0XC50eHQiO2k6MzEwO3M6NDk6IkA/ZmlsZV9nZXRfY29udGVudHNcKEA/YmFzZTY0X2RlY29kZVwoQD91cmxkZWNvZGUiO2k6MzExO3M6MjU6Ilwke1thLXpBLVowLTlfXSt9XChccypcKTsiO2k6MzEyO3M6NjA6InN1YnN0clwoc3ByaW50ZlwoWyciXSVvWyciXSxccypmaWxlcGVybXNcKFwkZmlsZVwpXCksXHMqLTRcKSI7aTozMTM7czo1NToiXCRbYS16QS1aMC05X10rXChbJyJdWyciXVxzKixccypldmFsXChcJFthLXpBLVowLTlfXStcKSI7aTozMTQ7czoxNjoid3NvU2VjUGFyYW1ccypcKCI7aTozMTU7czoxODoid2hpY2hccytzdXBlcmZldGNoIjtpOjMxNjtzOjY3OiJjb3B5XChccypbJyJdaHR0cDovLy4rP1wudHh0WyciXVxzKixccypbJyJdW2EtekEtWjAtOV9dK1wucGhwWyciXVwpIjtpOjMxNztzOjI4OiJcJHNldGNvb2tccypcKTtzZXRjb29raWVcKFwkIjtpOjMxODtzOjQ5MjoiQD9cYihldmFsfGJhc2U2NF9kZWNvZGV8c3RycmV2fHByZWdfcmVwbGFjZXxwcmVnX3JlcGxhY2VfY2FsbGJhY2t8dXJsZGVjb2RlfHN0cnN0cnxnemluZmxhdGV8c3ByaW50ZnxnenVuY29tcHJlc3N8YXNzZXJ0fHN0cl9yb3QxM3xtZDV8YXJyYXlfd2Fsa3xhcnJheV9maWx0ZXIpXHMqXChAP1xiKGV2YWx8YmFzZTY0X2RlY29kZXxzdHJyZXZ8cHJlZ19yZXBsYWNlfHByZWdfcmVwbGFjZV9jYWxsYmFja3x1cmxkZWNvZGV8c3Ryc3RyfGd6aW5mbGF0ZXxzcHJpbnRmfGd6dW5jb21wcmVzc3xhc3NlcnR8c3RyX3JvdDEzfG1kNXxhcnJheV93YWxrfGFycmF5X2ZpbHRlcilccypcKEA/XGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVxzKlwoIjtpOjMxOTtzOjQxOiJcLlxzKmJhc2U2NF9kZWNvZGVcKFxzKlwkaW5qZWN0XHMqXClccypcLiI7aTozMjA7czozOToiKGNoclwoW1xzXHdcJFxeXCtcLVwqL10rXClccypcLlxzKil7NCx9IjtpOjMyMTtzOjQyOiI9XHMqQD9mc29ja29wZW5cKFxzKlwkYXJndlxbXGQrXF1ccyosXHMqODAiO2k6MzIyO3M6MzU6IlwuXC4vXC5cLi9lbmdpbmUvZGF0YS9kYmNvbmZpZ1wucGhwIjtpOjMyMztzOjg1OiJyZWN1cnNlX2NvcHlcKFxzKlwkc3JjXHMqLFxzKlwkZHN0XHMqXCk7XHMqaGVhZGVyXChccypbJyJdbG9jYXRpb246XHMqXCRkc3RbJyJdXHMqXCk7IjtpOjMyNDtzOjE3OiJHYW50ZW5nZXJzXHMrQ3JldyI7aTozMjU7czoxNTU6IlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdezAsMX1ccypbYS16QS1aMC05X10rXHMqWyciXXswLDF9XF1cKFxzKlsnIl17MCwxfVwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtccypbYS16QS1aMC05X10rIjtpOjMyNjtzOjQwOiJmd3JpdGVcKFwkW2EtekEtWjAtOV9dK1xzKixccypbJyJdPFw/cGhwIjtpOjMyNztzOjU2OiJAP2NyZWF0ZV9mdW5jdGlvblwoXHMqWyciXVsnIl1ccyosXHMqQD9maWxlX2dldF9jb250ZW50cyI7aTozMjg7czoxMDQ6IlxdXChbJyJdXCRfWyciXVxzKixccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbXHMqWyciXXswLDF9W2EtekEtWjAtOV9dK1snIl17MCwxfVxzKlxdIjtpOjMyOTtzOjM5OiJpZlxzKlwoXHMqaXNzZXRcKFxzKlwkX0dFVFxbXHMqWyciXXBpbmciO2k6MzMwO3M6MzA6InJlYWRfZmlsZVwoXHMqWyciXWRvbWFpbnNcLnR4dCI7aTozMzE7czozODoiXGJldmFsXChccypbJyJde1xzKlwkW2EtekEtWjAtOV9dK1xzKn0iO2k6MzMyO3M6MTA4OiJpZlxzKlwoXHMqZmlsZV9leGlzdHNcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwpXHMqXClccyp7XHMqY2htb2RcKFxzKlwkW2EtekEtWjAtOV9dK1xzKixccyowXGQrXCk7XHMqfVxzKmVjaG8iO2k6MzMzO3M6MTE6Ij09WyciXVwpXCk7IjtpOjMzNDtzOjU1OiJcJFthLXpBLVowLTlfXSs9dXJsZGVjb2RlXChbJyJdLis/WyciXVwpO2lmXChwcmVnX21hdGNoIjtpOjMzNTtzOjgwOiJcJFthLXpBLVowLTlfXStccyo9XHMqZGVjcnlwdF9TT1woXHMqXCRbYS16QS1aMC05X10rXHMqLFxzKlsnIl1bYS16QS1aMC05X10rWyciXSI7aTozMzY7czoxMDU6Ij1ccyptYWlsXChccypiYXNlNjRfZGVjb2RlXChccypcJFthLXpBLVowLTlfXStcW1xkK1xdXHMqXClccyosXHMqYmFzZTY0X2RlY29kZVwoXHMqXCRbYS16QS1aMC05X10rXFtcZCtcXSI7aTozMzc7czoyNjoiZXZhbFwoXHMqWyciXXJldHVyblxzK2V2YWwiO2k6MzM4O3M6MTAwOiI9XHMqYmFzZTY0X2VuY29kZVwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl1bYS16QS1aMC05X10rWyciXVxdXCk7XHMqaGVhZGVyIjtpOjMzOTtzOjEwNzoiQGluaV9zZXRcKFsnIl1lcnJvcl9sb2dbJyJdLE5VTExcKTtccypAaW5pX3NldFwoWyciXWxvZ19lcnJvcnNbJyJdLDBcKTtccypmdW5jdGlvblxzK3JlYWRfZmlsZVwoXCRmaWxlX25hbWUiO2k6MzQwO3M6Mzc6IlwkdGV4dFxzKj1ccypodHRwX2dldFwoXHMqWyciXWh0dHA6Ly8iO2k6MzQxO3M6MTQzOiJcJFthLXpBLVowLTlfXStccyo9XHMqc3RyX3JlcGxhY2VcKFsnIl08L2JvZHk+WyciXVxzKixccypbJyJdWyciXVxzKixccypcJFthLXpBLVowLTlfXStcKTtccypcJFthLXpBLVowLTlfXStccyo9XHMqc3RyX3JlcGxhY2VcKFsnIl08L2h0bWw+WyciXSI7aTozNDI7czoxNTg6IlwjW2EtekEtWjAtOV9dK1wjXHMqaWZcKGVtcHR5XChcJFthLXpBLVowLTlfXStcKVwpXHMqe1xzKlwkW2EtekEtWjAtOV9dK1xzKj1ccypbJyJdPHNjcmlwdC4rPzwvc2NyaXB0PlsnIl07XHMqZWNob1xzK1wkW2EtekEtWjAtOV9dKztccyp9XHMqXCMvW2EtekEtWjAtOV9dK1wjIjtpOjM0MztzOjY2OiJcLlwkX1JFUVVFU1RcW1xzKlsnIl1bYS16QS1aMC05X10rWyciXVxzKlxdXHMqLFxzKnRydWVccyosXHMqMzAyXCkiO2k6MzQ0O3M6MTA0OiI9XHMqY3JlYXRlX2Z1bmN0aW9uXHMqXChccypudWxsXHMqLFxzKlthLXpBLVowLTlfXStcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwpXHMqXCk7XHMqXCRbYS16QS1aMC05X10rXChcKSI7aTozNDU7czo1NDoiPVxzKmZpbGVfZ2V0X2NvbnRlbnRzXChbJyJdaHR0cHM/Oi8vXGQrXC5cZCtcLlxkK1wuXGQrIjtpOjM0NjtzOjU3OiJDb250ZW50LXR5cGU6XHMqYXBwbGljYXRpb24vdm5kXC5hbmRyb2lkXC5wYWNrYWdlLWFyY2hpdmUiO2k6MzQ3O3M6MjA6InNsdXJwXHxtc25ib3RcfHRlb21hIjtpOjM0ODtzOjI3OiJcJEdMT0JBTFNcW25leHRcXVxbWyciXW5leHQiO2k6MzQ5O3M6MTc5OiI7QD9cJFthLXpBLVowLTlfXStcKFxiKGV2YWx8YmFzZTY0X2RlY29kZXxzdHJyZXZ8cHJlZ19yZXBsYWNlfHByZWdfcmVwbGFjZV9jYWxsYmFja3x1cmxkZWNvZGV8c3Ryc3RyfGd6aW5mbGF0ZXxzcHJpbnRmfGd6dW5jb21wcmVzc3xhc3NlcnR8c3RyX3JvdDEzfG1kNXxhcnJheV93YWxrfGFycmF5X2ZpbHRlcilcKCI7aTozNTA7czoyOToiaGVhZGVyXChfW2EtekEtWjAtOV9dK1woXGQrXCkiO2k6MzUxO3M6MTk1OiJpZlxzKlwoaXNzZXRcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXVwpXHMqJiZccyptZDVcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXVwpXHMqPT09XHMqWyciXVthLXpBLVowLTlfXStbJyJdXCkiO2k6MzUyO3M6OTA6IlwuPVxzKmNoclwoXCRbYS16QS1aMC05X10rXHMqPj5ccypcKFxkK1xzKlwqXHMqXChcZCtccyotXHMqXCRbYS16QS1aMC05X10rXClcKVxzKiZccypcZCtcKSI7aTozNTM7czozMToiLT5wcmVwYXJlXChbJyJdU0hPV1xzK0RBVEFCQVNFUyI7aTozNTQ7czoyMzoic29ja3Nfc3lzcmVhZFwoXCRjbGllbnQiO2k6MzU1O3M6MjQ6IjwlZXZhbFwoXHMqUmVxdWVzdFwuSXRlbSI7aTozNTY7czo5OToiXCRfUE9TVFxbWyciXVthLXpBLVowLTlfXStbJyJdXF07XHMqXCRbYS16QS1aMC05X10rXHMqPVxzKmZvcGVuXChccypcJF9HRVRcW1snIl1bYS16QS1aMC05X10rWyciXVxdIjtpOjM1NztzOjQwOiJ1cmw9WyciXWh0dHA6Ly9zY2FuNHlvdVwubmV0L3JlbW90ZVwucGhwIjtpOjM1ODtzOjYwOiJjYWxsX3VzZXJfZnVuY1woXHMqXCRbYS16QS1aMC05X10rXHMqLFxzKlwkW2EtekEtWjAtOV9dK1wpO30iO2k6MzU5O3M6Nzk6InByZWdfcmVwbGFjZVwoXHMqWyciXS8uKz8vZVsnIl1ccyosXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MzYwO3M6MTA2OiI9XHMqZmlsZV9nZXRfY29udGVudHNcKFxzKlsnIl0uKz9bJyJdXCk7XHMqXCRbYS16QS1aMC05X10rXHMqPVxzKmZvcGVuXChccypcJFthLXpBLVowLTlfXStccyosXHMqWyciXXdbJyJdIjtpOjM2MTtzOjU5OiJpZlwoXHMqXCRbYS16QS1aMC05X10rXClccyp7XHMqZXZhbFwoXCRbYS16QS1aMC05X10rXCk7XHMqfSI7aTozNjI7czoxNzk6ImFycmF5X21hcFwoXHMqWyciXVxiKGV2YWx8YmFzZTY0X2RlY29kZXxzdHJyZXZ8cHJlZ19yZXBsYWNlfHByZWdfcmVwbGFjZV9jYWxsYmFja3x1cmxkZWNvZGV8c3Ryc3RyfGd6aW5mbGF0ZXxzcHJpbnRmfGd6dW5jb21wcmVzc3xhc3NlcnR8c3RyX3JvdDEzfG1kNXxhcnJheV93YWxrfGFycmF5X2ZpbHRlcilbJyJdIjtpOjM2MztzOjE4NzoiPVxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXTtccypcJFthLXpBLVowLTlfXStccyo9XHMqZmlsZV9wdXRfY29udGVudHNcKFxzKlwkW2EtekEtWjAtOV9dK1xzKixccypmaWxlX2dldF9jb250ZW50c1woXHMqXCRbYS16QS1aMC05X10rXHMqXClccypcKSI7aTozNjQ7czo2MToiPFw/XHMqXCRbYS16QS1aMC05X10rPVsnIl0uKz9bJyJdO1xzKmhlYWRlclxzKlwoWyciXUxvY2F0aW9uOiI7aTozNjU7czoyNToiPCEtLVwjZXhlY1xzK2NtZFxzKj1ccypcJCI7aTozNjY7czo4MToiaWZcKFxzKnN0cmlwb3NcKFxzKlwkW2EtekEtWjAtOV9dK1xzKixccypbJyJdYW5kcm9pZFsnIl1ccypcKVxzKiE9PVxzKmZhbHNlXClccyp7IjtpOjM2NztzOjkwOiJcLj1ccypbJyJdPGRpdlxzK3N0eWxlPVsnIl1kaXNwbGF5Om5vbmU7WyciXT5bJyJdXHMqXC5ccypcJFthLXpBLVowLTlfXStccypcLlxzKlsnIl08L2Rpdj4iO2k6MzY4O3M6MTE0OiI9ZmlsZV9leGlzdHNcKFwkW2EtekEtWjAtOV9dK1wpXD9AZmlsZW10aW1lXChcJFthLXpBLVowLTlfXStcKTpcJFthLXpBLVowLTlfXSs7QGZpbGVfcHV0X2NvbnRlbnRzXChcJFthLXpBLVowLTlfXSsiO2k6MzY5O3M6ODk6IlwkW2EtekEtWjAtOV9dK1xzKlxbXHMqW2EtekEtWjAtOV9dK1xzKlxdXChccypcJFthLXpBLVowLTlfXStcW1xzKlthLXpBLVowLTlfXStccypcXVxzKlwpIjtpOjM3MDtzOjk2OiJcJFthLXpBLVowLTlfXSssWyciXXNsdXJwWyciXVwpXHMqIT09XHMqZmFsc2VccypcfFx8XHMqc3RycG9zXChccypcJFthLXpBLVowLTlfXSssWyciXXNlYXJjaFsnIl0iO2k6MzcxO3M6NjM6IlwkW2EtekEtWjAtOV9dK1woXHMqXCRbYS16QS1aMC05X10rXChccypcJFthLXpBLVowLTlfXStcKVxzKlwpOyI7aTozNzI7czoxNzoiY2xhc3NccytNQ3VybFxzKnsiO2k6MzczO3M6NTY6IkBpbmlfc2V0XChbJyJdZGlzcGxheV9lcnJvcnNbJyJdLDBcKTtccypAZXJyb3JfcmVwb3J0aW5nIjtpOjM3NDtzOjY5OiJpZlwoXHMqZmlsZV9leGlzdHNcKFxzKlwkZmlsZXBhdGhccypcKVxzKlwpXHMqe1xzKmVjaG9ccytbJyJddXBsb2FkZWQiO2k6Mzc1O3M6MzA6InJldHVyblxzK1JDNDo6RW5jcnlwdFwoXCRhLFwkYiI7aTozNzY7czozMjoiZnVuY3Rpb25ccytnZXRIVFRQUGFnZVwoXHMqXCR1cmwiO2k6Mzc3O3M6MjE6Ij1ccypyZXF1ZXN0XChccypjaHJcKCI7aTozNzg7czo1NToiO1xzKmFycmF5X2ZpbHRlclwoXCRbYS16QS1aMC05X10rXHMqLFxzKmJhc2U2NF9kZWNvZGVcKCI7aTozNzk7czoyMzQ6ImNhbGxfdXNlcl9mdW5jXChccypbJyJdXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVsnIl1ccyosXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcWyI7aTozODA7czoyNDc6ImNhbGxfdXNlcl9mdW5jX2FycmF5XChccypbJyJdXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVsnIl1ccyosXHMqYXJyYXlcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFsiO2k6MzgxO3M6ODc6ImlmIFwoIT9cJF9TRVJWRVJcW1snIl1IVFRQX1VTRVJfQUdFTlRbJyJdXF1ccypPUlxzKlwoc3Vic3RyXChcJF9TRVJWRVJcW1snIl1SRU1PVEVfQUREUiI7aTozODI7czo1OToicmVscGF0aHRvYWJzcGF0aFwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6MzgzO3M6Njg6IlwkZGF0YVxbWyciXWNjX2V4cF9tb250aFsnIl1cXVxzKixccypzdWJzdHJcKFwkZGF0YVxbWyciXWNjX2V4cF95ZWFyIjtpOjM4NDtzOjQwOiJcJFthLXpBLVowLTlfXStccyooXFsuezEsNDB9XF0pezEsfVxzKlwoIjtpOjM4NTtzOjMzOiJjYWxsX3VzZXJfZnVuY1woXHMqQD91bmhleFwoXHMqMHgiO2k6Mzg2O3M6Mjk6IlwuXC46OlxbXHMqcGhwcm94eVxzKlxdOjpcLlwuIjtpOjM4NztzOjQ0OiJbJyJdXHMqXC5ccypjaHJcKFxzKlxkKy5cZCtccypcKVxzKlwuXHMqWyciXSI7aTozODg7czozMjoicHJlZ19yZXBsYWNlLio/L2VbJyJdXHMqLFxzKlsnIl0iO2k6Mzg5O3M6ODU6IlwkW2EtekEtWjAtOV9dK1woXCRbYS16QS1aMC05X10rXChcJFthLXpBLVowLTlfXStcKFwkW2EtekEtWjAtOV9dK1woXCRbYS16QS1aMC05X10rXCkiO2k6MzkwO3M6MjM6In1ldmFsXChiemRlY29tcHJlc3NcKFwkIjtpOjM5MTtzOjU4OiIvdXNyL2xvY2FsL3BzYS9hcGFjaGUvYmluL2h0dHBkXHMrLURGUk9OVFBBR0VccystREhBVkVfU1NMIjtpOjM5MjtzOjU3OiJpY29udlwoYmFzZTY0X2RlY29kZVwoWyciXS4rP1snIl1cKVxzKixccypiYXNlNjRfZGVjb2RlXCgiO2k6MzkzO3M6MzM6Ijxicj5bJyJdXC5waHBfdW5hbWVcKFwpXC5bJyJdPGJyPiI7aTozOTQ7czo2NjoiXCk7QFwkW2EtekEtWjAtOV9dK1xbY2hyXChcZCtcKVxdXChcJFthLXpBLVowLTlfXStcW2NoclwoXGQrXClcXVwoIjtpOjM5NTtzOjExNToiXGIoZm9wZW58ZmlsZV9nZXRfY29udGVudHN8ZmlsZV9wdXRfY29udGVudHN8c3RhdHxjaG1vZHxmaWxlfHN5bWxpbmspXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKSI7aTozOTY7czo5NToiXGIoZm9wZW58ZmlsZV9nZXRfY29udGVudHN8ZmlsZV9wdXRfY29udGVudHN8c3RhdHxjaG1vZHxmaWxlfHN5bWxpbmspXChbJyJdaHR0cDovL3Bhc3RlYmluXC5jb20iO2k6Mzk3O3M6MTA5OiI7XCRbYS16QS1aMC05X10rPVwkW2EtekEtWjAtOV9dK1woXCRbYS16QS1aMC05X10rLFwkW2EtekEtWjAtOV9dK1wpO1wkW2EtekEtWjAtOV9dK1woXCRbYS16QS1aMC05X10rXCk7XHMqXD8+IjtpOjM5ODtzOjgzOiJcJF9TRVJWRVJcW1snIl1SRVFVRVNUX1VSSVsnIl1cXVwpLFsnIl1bYS16QS1aMC05X10rWyciXVwpXCl7XHMqaW5jbHVkZVwoZ2V0Y3dkXChcKSI7aTozOTk7czo4NDoid3Bfc2V0X2F1dGhfY29va2llXChcJHVzZXJfaWRcKTtccypkb19hY3Rpb25cKFsnIl13cF9sb2dpblsnIl1ccyosXHMqXCR1c2VyX2xvZ2luXCk7IjtpOjQwMDtzOjU4OiJhcnJheV9kaWZmX3VrZXlcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpIjtpOjQwMTtzOjY2OiI9Zm9wZW5cKGJhc2U2NF9kZWNvZGVcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFsiO2k6NDAyO3M6MTk6Ii1leGVjIHRvdWNoIC1hY20gLXIiO2k6NDAzO3M6MjM0OiJcJFthLXpBLVowLTlfXStccypcLj1ccypzdWJzdHJcKFwkW2EtekEtWjAtOV9dKyxccypcJFthLXpBLVowLTlfXStccypcK1xzKlxkKyxccypcJEdMT0JBTFNcW1snIl1bYS16QS1aMC05X10rWyciXVxdXChcJFthLXpBLVowLTlfXStcW1wkW2EtekEtWjAtOV9dK1xdXClcKTtccypcJFthLXpBLVowLTlfXStccypcKz1ccypcJEdMT0JBTFNcW1snIl1bYS16QS1aMC05X10rWyciXVxdXChcJFthLXpBLVowLTlfXSsiO2k6NDA0O3M6ODA6Im1vdmVfdXBsb2FkZWRfZmlsZVwoXHMqXCRpbWFnZSxccypkaXJuYW1lXChfX0ZJTEVfX1wpXHMqXC5ccypbJyJdL1snIl1ccypcLlxzKlwkIjtpOjQwNTtzOjM5OiJcJHN0cj0iPGgxPjQwMyBGb3JiaWRkZW48L2gxPjwhLS0gdG9rZW4iO2k6NDA2O3M6Njk6IkA/aW5jbHVkZV9vbmNlXHMqXChccypkaXJuYW1lXChfX0ZJTEVfX1wpXHMqXC5ccyonLydccypcLlxzKnVybGRlY29kZSI7aTo0MDc7czoxMTM6IlwkbG9jYWxwYXRoPWdldGVudlwoIlNDUklQVF9OQU1FIlwpO1wkYWJzb2x1dGVwYXRoPWdldGVudlwoIlNDUklQVF9GSUxFTkFNRSJcKTtcJHJvb3RfcGF0aD1zdWJzdHJcKFwkYWJzb2x1dGVwYXRoIjtpOjQwODtzOjEyNToiXCR0cGxccyo9XHMqXCR0cGxfcGF0aFw/XHMqQGZpbGVfZ2V0X2NvbnRlbnRzXChcJHJvb3RfcGF0aFwuXCR0cGxfcGF0aFwpOlxzKlsnIl1bJyJdO1xzKmlmIFwoc3RycG9zXChcJHRwbCxccypbJyJdXFtDT05URU5UXF0iO2k6NDA5O3M6MTk6Ii8vOnB0dGhbJyJdezAsMX1cKTsiO2k6NDEwO3M6OTc6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypcJFthLXpBLVowLTlfXStcKFxzKlwkW2EtekEtWjAtOV9dK1xbXHMqXCRbYS16QS1aMC05X10rXFtccypcJFthLXpBLVowLTlfXSsiO2k6NDExO3M6MTQ6IlwkY2xvYWtub3JlZGlyIjtpOjQxMjtzOjQ0OiJPcHRpb25zXHMqXCtFeGVjQ0dJXHMqRm9yY2VUeXBlXHMqY2dpLXNjcmlwdCI7aTo0MTM7czo1NjoiXC5vcGVuXChbJyJdR0VUWyciXVxzKixccypbJyJdaHR0cDovL1xkK1wuXGQrXC5cZCtcLlxkKy8iO2k6NDE0O3M6MzA6InJlcXVpcmVccysiXCRkaXIvYmluL3Bzb2Nrc2QiOyI7aTo0MTU7czo0NjoiZXhwbG9kZVwoWyciXS4qP1snIl1ccyosXHMqZ3ppbmZsYXRlXChzdWJzdHJcKCI7aTo0MTY7czozNjoiZnB1dHNcKFxzKlwkXHd7MSw0NX1ccyosXHMqWyciXTwucGhwIjtpOjQxNztzOjY5OiJcJFx3ezEsNDV9XHMqPVxzKlwkXHd7MSw0NX1cKFsnIl1bJyJdXHMqLFxzKlwkXHd7MSw0NX1cKFxzKlwkXHd7MSw0NX0iO2k6NDE4O3M6NTY6IjtccypcJFx3ezEsNDV9PXN0cnJldlwoXHMqWyciXVx3ezEsNDV9WyciXVwpXHMqXC5ccypbJyJdIjtpOjQxOTtzOjQ2OiI8ZGl2PlwjY29udGVudFwjPC9kaXY+XHMqPGRpdj5cI2xpbmtzMlwjPC9kaXY+IjtpOjQyMDtzOjI4OiJuZXdccypDbHVlXFxQc29ja3NkXFxBcHBcKFwpIjtpOjQyMTtzOjE1OiJbJyJdL2V0Yy9wYXNzd2QiO2k6NDIyO3M6MTU6IlsnIl0vdmFyL2NwYW5lbCI7aTo0MjM7czoxNDoiWyciXS9ldGMvaHR0cGQiO2k6NDI0O3M6MjA6IlsnIl0vZXRjL25hbWVkXC5jb25mIjtpOjQyNTtzOjEzOiI4OVwuMjQ5XC4yMVwuIjtpOjQyNjtzOjE1OiIxMDlcLjIzOFwuMjQyXC4iO2k6NDI3O3M6NjU6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlwoP1xzKlsnIl1pbWFnZXMvIjtpOjQyODtzOjcxOiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXHMqXChAP3VybGVuY29kZSI7aTo0Mjk7czo3MToiXGIoZnRwX2V4ZWN8c3lzdGVtfHNoZWxsX2V4ZWN8cGFzc3RocnV8cG9wZW58cHJvY19vcGVuKVwoP1snIl1jZFxzKy90bXAiO2k6NDMwO3M6MjM6Ii92YXIvcW1haWwvYmluL3NlbmRtYWlsIjtpOjQzMTtzOjUxOiJcJFthLXpBLVowLTlfXSsgPSBcJFthLXpBLVowLTlfXStcKFsnIl17MCwxfWh0dHA6Ly8iO2k6NDMyO3M6MTU6IlsnIl1cKVwpXCk7IlwpOyI7aTo0MzM7czo5MjoiXCRbYS16QS1aMC05X10rPVsnIl1bYS16QS1aMC05L1wrXD1fXStbJyJdO1xzKmVjaG9ccytiYXNlNjRfZGVjb2RlXChcJFthLXpBLVowLTlfXStcKTtccypcPz4iO2k6NDM0O3M6NjI6IlwkW2EtekEtWjAtOV9dKy0+X3NjcmlwdHNcW1xzKmd6dW5jb21wcmVzc1woXHMqYmFzZTY0X2RlY29kZVwoIjtpOjQzNTtzOjM0OiJcJFthLXpBLVowLTlfXStccyo9XHMqWyciXWV2YWxbJyJdIjtpOjQzNjtzOjQzOiJcJFthLXpBLVowLTlfXStccyo9XHMqWyciXWJhc2U2NF9kZWNvZGVbJyJdIjtpOjQzNztzOjQ1OiJcJFthLXpBLVowLTlfXStccyo9XHMqWyciXWNyZWF0ZV9mdW5jdGlvblsnIl0iO2k6NDM4O3M6MzY6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypbJyJdYXNzZXJ0WyciXSI7aTo0Mzk7czo0MjoiXCRbYS16QS1aMC05X10rXHMqPVxzKlsnIl1wcmVnX3JlcGxhY2VbJyJdIjtpOjQ0MDtzOjIxNjoiXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqIjtpOjQ0MTtzOjE1MDoiXCRbYS16QS1aMC05X10rXFtccypcJFthLXpBLVowLTlfXStccypcXVxbXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwuIjtpOjQ0MjtzOjQzOiJcJFthLXpBLVowLTlfXStccypcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlxbIjtpOjQ0MztzOjYzOiJcJFthLXpBLVowLTlfXStccypcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwoXHMqXCRbYS16QS1aMC05X10rXFsiO2k6NDQ0O3M6NTA6IlwkW2EtekEtWjAtOV9dK1xzKlwoXHMqXCRbYS16QS1aMC05X10rXHMqXChccypbJyJdIjtpOjQ0NTtzOjcwOiJcJFthLXpBLVowLTlfXStccypcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwoXHMqXCRbYS16QS1aMC05X10rXHMqXClccyosIjtpOjQ0NjtzOjY5OiJcJFthLXpBLVowLTlfXStccypcKFxzKlsnIl1bJyJdXHMqLFxzKmV2YWxcKFwkW2EtekEtWjAtOV9dK1xzKlwpXHMqXCkiO2k6NDQ3O3M6MjM2OiJcJFthLXpBLVowLTlfXStccyo9XHMqXCRbYS16QS1aMC05X10rXChbJyJdWyciXVxzKixccypcJFthLXpBLVowLTlfXStcKFxzKlwkW2EtekEtWjAtOV9dK1woXHMqWyciXVthLXpBLVowLTlfXStbJyJdXHMqLFxzKlsnIl1bJyJdXHMqLFxzKlwkW2EtekEtWjAtOV9dK1xzKlwuXHMqXCRbYS16QS1aMC05X10rXHMqXC5ccypcJFthLXpBLVowLTlfXStccypcLlxzKlwkW2EtekEtWjAtOV9dK1xzKlwpXHMqXClccypcKSI7aTo0NDg7czoxNDM6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypbJyJdXCRbYS16QS1aMC05X10rPUBbYS16QS1aMC05X10rXChbJyJdLis/WyciXVwpO1thLXpBLVowLTlfXStcKCFcJFthLXpBLVowLTlfXStcKXtcJFthLXpBLVowLTlfXSs9QFthLXpBLVowLTlfXStcKFxzKlwpIjtpOjQ0OTtzOjExNDoiXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVwoXHMqWyciXVsnIl1ccyosXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVwoXHMqXCRbYS16QS1aMC05X10rXFtccypcZCtccypcXVxzKlwpIjtpOjQ1MDtzOjMyOiJcJFthLXpBLVowLTlfXStcKFxzKkBcJF9DT09LSUVcWyI7aTo0NTE7czoyOToiXCRbYS16QS1aMC05X10rXChbJyJdLi5lWyciXSwiO2k6NDUyO3M6NzA6IkBcJFthLXpBLVowLTlfXSsmJkBcJFthLXpBLVowLTlfXStcKFwkW2EtekEtWjAtOV9dKyxcJFthLXpBLVowLTlfXStcKTsiO2k6NDUzO3M6MjM0OiJcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbXHMqWyciXVthLXpBLVowLTlfXStbJyJdXHMqXF1cKFxzKlxiKGV2YWx8YmFzZTY0X2RlY29kZXxzdHJyZXZ8cHJlZ19yZXBsYWNlfHByZWdfcmVwbGFjZV9jYWxsYmFja3x1cmxkZWNvZGV8c3Ryc3RyfGd6aW5mbGF0ZXxzcHJpbnRmfGd6dW5jb21wcmVzc3xhc3NlcnR8c3RyX3JvdDEzfG1kNXxhcnJheV93YWxrfGFycmF5X2ZpbHRlcikiO2k6NDU0O3M6MTg2OiJcYihldmFsfGJhc2U2NF9kZWNvZGV8c3RycmV2fHByZWdfcmVwbGFjZXxwcmVnX3JlcGxhY2VfY2FsbGJhY2t8dXJsZGVjb2RlfHN0cnN0cnxnemluZmxhdGV8c3ByaW50ZnxnenVuY29tcHJlc3N8YXNzZXJ0fHN0cl9yb3QxM3xtZDV8YXJyYXlfd2Fsa3xhcnJheV9maWx0ZXIpXChccypcJFthLXpBLVowLTlfXStcKFxzKlsnIl0iO2k6NDU1O3M6MjMzOiJAP1wkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtbJyJdW2EtekEtWjAtOV9dK1snIl1cXVwoXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVwoWyciXSI7aTo0NTY7czoxODE6IlwkW2EtekEtWjAtOV9dKz1cJFthLXpBLVowLTlfXStcKFsnIl0uKz9bJyJdLFwkW2EtekEtWjAtOV9dKyxcJFthLXpBLVowLTlfXStcKTtcJFthLXpBLVowLTlfXSs9XCRbYS16QS1aMC05X10rXChcJFthLXpBLVowLTlfXSssXCRbYS16QS1aMC05X10rXCk7XCRbYS16QS1aMC05X10rXChcJFthLXpBLVowLTlfXStcKTsiO2k6NDU3O3M6MTMxOiJAP2FycmF5X21hcFwoQD9cJHtccypbJyJdXHd7MSw0NX1bJyJdXHMqfXtccypcd3sxLDQ1fVxzKn1ccyosXHMqYXJyYXlcKEA/XCRccyp7XHMqWyciXVx3ezEsNDV9WyciXVxzKn17XHMqWyciXVx3ezEsNDV9WyciXVxzKn1cKVwpOyI7aTo0NTg7czo2NToiQD9cJHtAP1snIl1cd3sxLDQ1fVsnIl19XFtcZCtcXVwoQD9cJHtbJyJdXHd7MSw0NX1bJyJdfVxbXGQrXF1cKToiO2k6NDU5O3M6ODU6Ilwke1snIl1cd3sxLDQ1fVsnIl19XFtbJyJdXHd7MSw0NX1bJyJdXF1cKFwke1snIl1cd3sxLDQ1fVsnIl19XFtbJyJdXHd7MSw0NX1bJyJdXF1cKTsiO2k6NDYwO3M6ODM6IkA/XCR7WyciXVx3ezEsNDV9WyciXX09QD9cJHtbJyJdXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpWyciXX07QD9cKFwoIjtpOjQ2MTtzOjg0OiJAP3JlZ2lzdGVyX3NodXRkb3duX2Z1bmN0aW9uXChAP1wke0A/WyciXV8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVsnIl0iO2k6NDYyO3M6NDc6IlwkXHd7MSw0NX17XGQrfVwoXCRcd3sxLDQ1fXtcZCt9XCk6QD9cJFx3ezEsNDV9IjtpOjQ2MztzOjQ4OiI7XCR7WyciXUdMT0JBTFNbJyJdfVxbWyciXVx3ezEsNDV9WyciXVxdXChcKTtcPz4iO2k6NDY0O3M6NzQ6ImlmXChpc3NldFwoXCRfQ09PS0lFXFtbJyJdXHd7MSw0NX1bJyJdXF1cKVwpe1xzKlwkY2g9Y3VybF9pbml0XChbJyJdaHR0cDovIjtpOjQ2NTtzOjEyMjoiaWZcKFwkcGFnZVxzKj1ccypcJF9HRVRcW1snIl1wWyciXVxdXClccyp7XHMqXCRleHRzXHMqPVxzKmFycmF5XCgiXC5odG1sIixccyoiXC5odG0iLFxzKiJcLnBocCJcKTtccyplcnJvcl9yZXBvcnRpbmdcKDBcKTsiO2k6NDY2O3M6OTQ6ImlmXHMqXChzdHJsZW5cKFwkbGlua1wpXHMqPlxzKlxkK1wpXHMqe1xzKlwkZnBccyo9XHMqQD9mb3BlblxzKlwoXHMqXCRjYWNoZVxzKixccypbJyJdd1snIl1cKTsiO2k6NDY3O3M6Njg6IlwkbGlua2VyXHMqPVxzKnN0cl9yZXBsYWNlXChbJyJdPHJlcGxhY2U+WyciXSxccypcJHBhcmFtLFxzKlwkbGlua2VyIjtpOjQ2ODtzOjg0OiJnZXRcKFsnIl1odHRwOi8vWyciXVwuXCRfQ09PS0lFXFtbJyJdXHd7MSw0NX1bJyJdXF1cLlsnIl0vXD9cd3sxLDQ1fT1bJyJdXC5cJF9DT09LSUUiO2k6NDY5O3M6NzU6ImlmXHMqXChjb3B5XChcJF9GSUxFU1xbWyciXVx3ezEsNDV9WyciXVxdXFtbJyJdXHd7MSw0NX1bJyJdXF0sXHMqXCRcd3sxLDQ1fSI7aTo0NzA7czo2OToiZXJyb3JfcmVwb3J0aW5nXChcZCtcKTtccypkZWZpbmVcKFsnIl1QQVNTV09SRFsnIl1ccyosXHMqWyciXVx3ezEsNDV9IjtpOjQ3MTtzOjc3OiJleHRyYWN0XChcJF9TRVJWRVJcKTtccyphcnJheV9maWx0ZXJcKFwoYXJyYXlcKVwkXHd7MSw0NX1ccyosXHMqXCRcd3sxLDQ1fVwpOyI7aTo0NzI7czo3MjoicmVnaXN0ZXJfc2h1dGRvd25fZnVuY3Rpb25ccypcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpIjtpOjQ3MztzOjc2OiJcJHtcd3sxLDQ1fVwoWyciXS4qP1snIl1cKX1ccyo9XHMqXCR7XHd7MSw0NX1cKFsnIl0uKj9bJyJdXCl9XChcJHtcd3sxLDQ1fVwoIjtpOjQ3NDtzOjUxOiJcJFx3ezEsNDV9XFtccypjaHJcKFxzKlxkK1xzKlwpXHMqXF1cKFxzKlx3ezEsNDV9XCgiO2k6NDc1O3M6MTE4OiJcJHBhdGg9XCRfU0VSVkVSXFtbJyJdRE9DVU1FTlRfUk9PVFsnIl1cXVwuWyciXVthLXpBLVowLTlfXStbJyJdO1xcc1wqaW5jbHVkZVwoXCRwYXRoXC5cJF9HRVRcW1snIl1wWyciXVxdXCk7XFxzXCpkaWU7IjtpOjQ3NjtzOjQyOiJmdW5jdGlvbiBDcmVhdGVMaW5rXChcJGRpcixcJHJlcGxhY2VzdHIxXCkiO2k6NDc3O3M6MTA0OiJcJGZwXHMqPVxzKmZzb2Nrb3BlblwoWyciXXVkcDovL1wkaG9zdFsnIl1ccyosXHMqXCRwb3J0LFxzKlwkZXJybm8sXHMqXCRlcnJzdHIsXHMqXGQrXHMqXCk7XHMqaWZcKFwkZnBcKSI7aTo0Nzg7czo1ODoiO1xzKmluY2x1ZGVfb25jZVwoXHMqc3lzX2dldF90ZW1wX2RpclwoXClccypcLlxzKlsnIl0vU0VTUyI7aTo0Nzk7czo3ODoiXCR1cmxccyo9XHMqWyciXXJlZmVyZXI6WyciXVwuc3RydG9sb3dlclwoXCRfU0VSVkVSXFtbJyJdSFRUUF9SRUZFUkVSWyciXVxdXCk7IjtpOjQ4MDtzOjk1OiJcJGlwXHMqPVxzKlwkX1NFUlZFUlxbWyciXVJFTU9URV9BRERSWyciXVxdO1xzKlwkdXJsXHMqPVxzKlwkX0dFVFxbWyciXWlkWyciXVxdO1xzKlwkdGFyZ2V0XHMqPSI7aTo0ODE7czo4MjoiUmV3cml0ZVJ1bGVccypcXlwoXFtBLVphLXowLTktXF1cK1wpXC5odG1sXCRccypcd3sxLDQ1fVwucGhwXD9cd3sxLDQ1fT1cJDFccypcW0xcXSI7aTo0ODI7czo4MToiZmlsZV9wdXRfY29udGVudHNcKFwkZGlyXC5bJyJdL1snIl1cLlwkZmlsZSxccypcJHRlbXBzdHJcKTtccyplY2hvXHMqWyciXWxpbmtieW1lIjtpOjQ4MztzOjU3OiJpZlwoc3RycG9zXChcJHF1ZXJ5U3RyLFsnIl1hY3Rpb249c2l0ZW1hcFsnIl1cKSE9PWZhbHNlXCkiO2k6NDg0O3M6Mzk6IkVuY29kZVxzK2J5XHMraHR0cDovL1d3d1wuUEhQSmlhTWlcLkNvbSI7aTo0ODU7czo4MToiXCRbYS16QS1aMC05X10rXChccypcJFthLXpBLVowLTlfXStccypcKFxzKlwkW2EtekEtWjAtOV9dK1xzKlwuXHMqXCRbYS16QS1aMC05X10rIjtpOjQ4NjtzOjU3OiJlY2hvXHMqWyciXWdvb2dsZS1zaXRlLXZlcmlmaWNhdGlvbjpccypnb29nbGVbJyJdXC5cJF9HRVQiO2k6NDg3O3M6Mjc6ImZpbHRlcl92YXJccypcKFxzKlwkX1NFUlZFUiI7aTo0ODg7czoyNDoiUGx1Z2luIE5hbWU6XHMqV1BDb3JlU3lzIjtpOjQ4OTtzOjgzOiItPkF1dGhvcml6ZVxzKlwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl1cd3sxLDQ1fVsnIl1cXVwpOyI7aTo0OTA7czo0NToiLT5BdXRob3JpemVccypcKFxzKlsnIl17MCwxfVxkK1snIl17MCwxfVxzKlwpIjtpOjQ5MTtzOjM3OiJcJFVTRVItPkF1dGhvcml6ZVwoXHMqXCRcd3sxLDQ1fVxzKlwpIjtpOjQ5MjtzOjI2OiJDcmVkaXRccypDYXJkXHMqPFx3ezEsNDV9QCI7aTo0OTM7czo4MjoiXCRcd3sxLDQ1fVxzKj1ccypcJFx3ezEsNDV9XChbJyJdWyciXVxzKixccypcJFx3ezEsNDV9XHMqXCk7XHMqQFwkXHd7MSw0NX1cKFxzKlwpOyI7aTo0OTQ7czo1NDoiY2FsbF91c2VyX2Z1bmNcKFxzKmNyZWF0ZV9mdW5jdGlvblwoXHMqbnVsbFxzKixccypwYWNrIjtpOjQ5NTtzOjUzOiJmaWxlX2dldF9jb250ZW50c1woXHMqcGFja1woXHMqWyciXUhcKlsnIl1ccyosXHMqam9pbiI7aTo0OTY7czo0NzoicGFja1woWyciXUgxMzBbJyJdLFwkaGVhZGVyXHMqXC5ccypbJyJdXHd7MSw0NX0iO2k6NDk3O3M6NzQ6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlwoXHMqWyciXXBocDovL2lucHV0WyciXVwpIjtpOjQ5ODtzOjc3OiJcJFx3ezEsNDV9XChccypcJFxzKntccypbJyJdXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpWyciXX1ccypcWyI7aTo0OTk7czoxMTQ6IlwkX1x3ezEsNDV9XHMqPVxzKlwkX1x3ezEsNDV9XChccypcZCtccypcKVxzKlwuXHMqXCRfXHd7MSw0NX1cKFxzKlxkK1xzKlwpXHMqXC5ccypcJF9cd3sxLDQ1fVwoXHMqXGQrXHMqXClccypcLlxzKiI7aTo1MDA7czoxNzoiPVsnIl1cKVwpO1snIl1cKTsiO2k6NTAxO3M6NDg6IkBhc3NlcnRfb3B0aW9uc1woXHMqQVNTRVJUX1FVSUVUX0VWQUxccyosXHMqMVwpOyI7aTo1MDI7czo2ODoiYXJyYXlfZmlsdGVyXChccyphcnJheVwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6NTAzO3M6MTA2OiJmaWx0ZXJfdmFyXChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKXtccypcd3sxLDQ1fVxzKn1ccyosXHMqRklMVEVSX0NBTExCQUNLXHMqLFxzKmFycmF5IjtpOjUwNDtzOjQ2OiJwcmVnX21hdGNoX2FsbFwoIi88SWZNb2R1bGVcXHNcK21vZF9yZXdyaXRlXC5jIjtpOjUwNTtzOjIzOiI8dGl0bGU+U2hlbGxccytVcGxvYWRlciI7aTo1MDY7czo3NDoiXD9vcHRpb249Y29tX2NvbmZpZyZ2aWV3PWNvbXBvbmVudCZjb21wb25lbnQ9Y29tX21lZGlhJnBhdGgmdG1wbD1jb21wb25lbnQiO2k6NTA3O3M6MzA6Ijx0aXRsZT53aG9pc3RvcnlcLmNvbVxzK3BhcnNlciI7aTo1MDg7czoyOToibXllY2hvXChccypbJyJdU2hlbGxccyt1cGxvYWQiO2k6NTA5O3M6Nzc6ImFycmF5X21hcFwoXHMqY3JlYXRlX2Z1bmN0aW9uXChccypbJyJdWyciXVxzKixcJFx3ezEsNDV9XCksYXJyYXlcKFxzKlsnIl1bJyJdIjtpOjUxMDtzOjQzOiJ0cmlnZ2VyX2Vycm9yXHMqXChcJDxzcD5ccyosXHMqRV9VU0VSX0VSUk9SIjtpOjUxMTtzOjg0OiJpdGVyYXRvcl9hcHBseVxzKlwoXHMqXCRcd3sxLDQ1fVxzKixccypcJFx3ezEsNDV9XHMqLFxzKmFycmF5XHMqXChccypcJFx3ezEsNDV9XHMqXCkiO2k6NTEyO3M6Nzk6ImFycmF5X21hcFxzKlwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1xzKlsnIl08d3BbJyJdXHMqXF0iO2k6NTEzO3M6OTM6ImNyZWF0ZV9mdW5jdGlvblwoWyciXVsnIl1ccyosXHMqXCRcd3sxLDQ1fXtcZCt9XHMqXC5ccypcJFx3ezEsNDV9e1xkK31ccypcLlxzKlwkXHd7MSw0NX17XGQrfSI7aTo1MTQ7czo1NDoiYWRkX2FjdGlvblwoWyciXXdwX2Zvb3RlclsnIl1ccyosXHMqWyciXXdwX2Z1bmNfanF1ZXJ5IjtpOjUxNTtzOjY5OiJmb3BlblwoXHMqYmFzZTY0X2RlY29kZVwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUykiO2k6NTE2O3M6OTI6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypcJFthLXpBLVowLTlfXStcKFxzKlsnIl1ccypbJyJdXHMqLFxzKlwkW2EtekEtWjAtOV9dK1wpO1xzKlwkbFwoXHMqXCk7IjtpOjUxNztzOjM2OiJyZXF1aXJlXHMqWyciXVwkZGlyL2Jpbi9wc29ja3NkWyciXTsiO2k6NTE4O3M6MTcxOiJcJFthLXpBLVowLTlfXStccyo9XHMqYXJyYXlcKFxzKlwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtccypbJyJdW2EtekEtWjAtOV9dK1snIl1ccypcXVxzKlwpO1xzKkA/YXJyYXlfZmlsdGVyXChcJFthLXpBLVowLTlfXStccyosXHMqXCRbYS16QS1aMC05X10rXHMqXCkiO2k6NTE5O3M6MTAyOiJpZlwoaXNfb2JqZWN0XChcJF9TRVNTSU9OXFsiX19kZWZhdWx0IlxdXFsidXNlciJcXVwpXHMqJiZccyohXChcJF9TRVNTSU9OXFsiX19kZWZhdWx0IlxdXFsidXNlciJcXS0+aWQiO2k6NTIwO3M6MTY6Ilwke1wke1snIl1cXHhcZCsiO2k6NTIxO3M6NTU6InJldHVyblxzKlwkXHd7MSw0NX1ccypcXlxzKnN0cl9yZXBlYXRccypcKFxzKlwkXHd7MSw0NX0iO2k6NTIyO3M6MTM2OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypcJF9TRVJWRVJcW1snIl17MCwxfURPQ1VNRU5UX1JPT1RbJyJdezAsMX1cXVxzKlwuXHMqWyciXVtccyVcLkBcLVwrXChcKS9cd10rP1wuanBnIjtpOjUyMztzOjEzNjoiXGIoaW5jbHVkZXxpbmNsdWRlX29uY2V8cmVxdWlyZXxyZXF1aXJlX29uY2UpXHMqXCg/XHMqXCRfU0VSVkVSXFtbJyJdezAsMX1ET0NVTUVOVF9ST09UWyciXXswLDF9XF1ccypcLlxzKlsnIl1bXHMlXC5AXC1cK1woXCkvXHddKz9cLmdpZiI7aTo1MjQ7czoxMzY6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlwoP1xzKlwkX1NFUlZFUlxbWyciXXswLDF9RE9DVU1FTlRfUk9PVFsnIl17MCwxfVxdXHMqXC5ccypbJyJdW1xzJVwuQFwtXCtcKFwpL1x3XSs/XC5wbmciO2k6NTI1O3M6MTA2OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypbJyJdW1xzJVwuQFwtXCtcKFwpL1x3XSs/L1tccyVcLkBcLVwrXChcKS9cd10rP1wucG5nIjtpOjUyNjtzOjEwNjoiXGIoaW5jbHVkZXxpbmNsdWRlX29uY2V8cmVxdWlyZXxyZXF1aXJlX29uY2UpXHMqXCg/XHMqWyciXVtccyVcLkBcLVwrXChcKS9cd10rPy9bXHMlXC5AXC1cK1woXCkvXHddKz9cLmpwZyI7aTo1Mjc7czoxMDY6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlwoP1xzKlsnIl1bXHMlXC5AXC1cK1woXCkvXHddKz8vW1xzJVwuQFwtXCtcKFwpL1x3XSs/XC5naWYiO2k6NTI4O3M6MTA2OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypbJyJdW1xzJVwuQFwtXCtcKFwpL1x3XSs/L1tccyVcLkBcLVwrXChcKS9cd10rP1wuaWNvIjtpOjUyOTtzOjEwODoiXGIoaW5jbHVkZXxpbmNsdWRlX29uY2V8cmVxdWlyZXxyZXF1aXJlX29uY2UpXHMqXChccypkaXJuYW1lXChccypfX0ZJTEVfX1xzKlwpXHMqXC5ccypbJyJdL3dwLWNvbnRlbnQvdXBsb2FkIjtpOjUzMDtzOjY3OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypbJyJdL3Zhci93d3cvIjtpOjUzMTtzOjY0OiJcYihpbmNsdWRlfGluY2x1ZGVfb25jZXxyZXF1aXJlfHJlcXVpcmVfb25jZSlccypcKD9ccypbJyJdL2hvbWUvIjtpOjUzMjtzOjIzNzoiXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKVwoXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl17MCwxfVthLXpBLVowLTlfXStbJyJdezAsMX1cXVwpIjtpOjUzMztzOjI1MDoiXGIoZXZhbHxhc3NlcnR8XCRcd3sxLDQwfShcW1teXV17MSwxMH1cXVxzKil7MCw0fXxcJFx3ezEsNDB9KFx7W159XXsxLDEwfVx9XHMqKXswLDR9KVxzKlwoXHMqXGIoZXZhbHxiYXNlNjRfZGVjb2RlfHN0cnJldnxwcmVnX3JlcGxhY2V8cHJlZ19yZXBsYWNlX2NhbGxiYWNrfHVybGRlY29kZXxzdHJzdHJ8Z3ppbmZsYXRlfHNwcmludGZ8Z3p1bmNvbXByZXNzfGFzc2VydHxzdHJfcm90MTN8bWQ1fGFycmF5X3dhbGt8YXJyYXlfZmlsdGVyKSI7aTo1MzQ7czo0MzoiPVxzKndwX2luc2VydF91c2VyXChccypcJFthLXpBLVowLTlfXStccypcKSI7aTo1MzU7czo1NDoiXCR3cF90ZW1wbGF0ZVxzKj1ccypAP3ByZWdfcmVwbGFjZVwoWyciXS8uKz8vXFx4NjVbJyJdIjtpOjUzNjtzOjY2OiJcKFwkXyhHRVR8UE9TVHxTRVJWRVJ8Q09PS0lFfFJFUVVFU1R8RklMRVMpXFtccypiYXNlNjRfZGVjb2RlXHMqXCgiO2k6NTM3O3M6NzE6Ilwkd3BhdXRvcFxzKj1ccypwcmVfdGVybV9uYW1lXChccypcJHdwX2tzZXNfZGF0YVxzKixccypcJHdwX25vbmNlXHMqXCk7IjtpOjUzODtzOjI1OiI8XD9ccyplY2hvXHMqXGQrXCtcZCs7XD8+IjtpOjUzOTtzOjgwOiJpZlwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcW1snIl1pZFsnIl1cXVxzKj09XHMqWyciXXRlbXBJZCI7aTo1NDA7czoyMzoiZWNob1xzKiJmaWxlIHRlc3Qgb2theSIiO2k6NTQxO3M6Mzg6IkBcJHtbYS16QS1aMC05X10rfVwoXCRbYS16QS1aMC05X10rXCk7IjtpOjU0MjtzOjI4OiJyZXF1aXJlICJcJGRpci9iaW4vcHNvY2tzZCI7IjtpOjU0MztzOjE2NToiXCRbYS16QS1aMC05X10rXHMqPVxzKmFycmF5XChccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbXHMqWyciXVthLXpBLVowLTlfXStbJyJdXHMqXF1cKTtccypAYXJyYXlfZmlsdGVyXChcJFthLXpBLVowLTlfXStccyosXHMqXCRbYS16QS1aMC05X10rXCk7IjtpOjU0NDtzOjM2OiJjaHJcKFxkKy5cZCtcKVxzKlwuXHMqY2hyXChcZCsuXGQrXCkiO2k6NTQ1O3M6NzA6Ilwke1thLXpBLVowLTlfXStcKFsnIl1bYS16QS1aMC05X10rWyciXVwpfVxzKj1ccypcJHtccypbYS16QS1aMC05X10rXCgiO2k6NTQ2O3M6NTk6IlxiKGluY2x1ZGV8aW5jbHVkZV9vbmNlfHJlcXVpcmV8cmVxdWlyZV9vbmNlKVxzKlwoQD9pbmlfZ2V0IjtpOjU0NztzOjE3OiJcJGRpci9iaW4vcHNvY2tzZCI7aTo1NDg7czoyMToiYXRccytub3dccystZlxzKzFcLnNoIjtpOjU0OTtzOjEwNzoiXCRcd3sxLDQ1fVxzKj1ccypcJFx3ezEsNDV9XChccypbJyJdXCRfXHd7MSw0NX1bJyJdXHMqLFxzKlwkX1x3ezEsNDV9XCk7XHMqXCRcd3sxLDQ1fVwoXHMqXCRfXHd7MSw0NX1ccypcKTsiO2k6NTUwO3M6NDA6IlsnIl1ccypcLlxzKnBocF91bmFtZVwoXHMqXClccypcLlxzKlsnIl0iO2k6NTUxO3M6Mzg6IjtccypmcHV0c1woXCRcd3sxLDQ1fVxzKixccypbJyJdPFw/cGhwIjtpOjU1MjtzOjc2OiJ3aGlsZVxzKlwoXHMqQG9iX2VuZF9jbGVhblwoXHMqXClccypcKTtccypoZWFkZXJcKFsnIl1jb250ZW50LXR5cGU6dGV4dC94bWw7IjtpOjU1MztzOjI3OiJnaXRcLm9zY2hpbmFcLm5ldC9tei9tenBocDIiO2k6NTU0O3M6MzQ6ImZpbGVfZ2V0X2NvbnRlbnRzXChbJyJddGVtcElkXC5waHAiO2k6NTU1O3M6NTU6IjtcJFx3ezEsNDV9XHMqPVxzKnBhY2tcKFxzKlsnIl1IXCpbJyJdLFsnIl1cd3sxLDQ1fVsnIl0iO2k6NTU2O3M6Mjc6ImluY2x1ZGVccypcJHtccypcJFx3ezEsNDV9fSI7aTo1NTc7czoyNjoiU21hcnR5Mzo6cmVkaXJlY3RcKFsnIl1cXHgiO2k6NTU4O3M6MTc6IjxcP3BocFxzK1wke1snIl1HIjtpOjU1OTtzOjE5OiI8XD9waHBccytcJHtbJyJdXFx4IjtpOjU2MDtzOjE5OiJNSUxETkVUXFxzXCtTQ0FOTkVSIjtpOjU2MTtzOjY3OiI9XFsiJ1xdXC57MSwxMH1cWyciXF1cXHNcKlxbXFxcXiZcfFxdXFsnIlxdXC57MSwxMH1cWyciXF1cWztcXFwuL1xdIjtpOjU2MjtzOjY3OiIvXFxcKlwoXFx3XCtcKVxcXCovXFxzXCpAaW5jbHVkZVxcc1wqIlxbXF4iXF1cKyI7XFxzXCovXFxcKlxcMVxcXCovIjtpOjU2MztzOjEwMjoiXFxcJFxcc1wqXFx7XFxzXCoiXFtcXiJcXVwqIlxcc1wqXFx9XFxzXCpcXFxbXFxzXCoiXFtcXiJcXVwqIlxcc1wqXFxcXVxcc1wqPSJcW1xeIlxdXCoiXFxzXCo7XFxzXCpcXFwkIjt9"));
$g_ExceptFlex = unserialize(base64_decode("YToxNDY6e2k6MDtzOjY2OiJyZXF1aXJlXHMqXCg/XHMqXCRfU0VSVkVSXFtccypbJyJdezAsMX1ET0NVTUVOVF9ST09UWyciXXswLDF9XHMqXF0iO2k6MTtzOjcxOiJyZXF1aXJlX29uY2VccypcKD9ccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfURPQ1VNRU5UX1JPT1RbJyJdezAsMX1ccypcXSI7aToyO3M6NjY6ImluY2x1ZGVccypcKD9ccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfURPQ1VNRU5UX1JPT1RbJyJdezAsMX1ccypcXSI7aTozO3M6NzE6ImluY2x1ZGVfb25jZVxzKlwoP1xzKlwkX1NFUlZFUlxbXHMqWyciXXswLDF9RE9DVU1FTlRfUk9PVFsnIl17MCwxfVxzKlxdIjtpOjQ7czo2MToiaW5jbHVkZV9vbmNlXChcJF9TRVJWRVJcWydET0NVTUVOVF9ST09UJ1xdXC4nL2JpdHJpeC9tb2R1bGVzLyI7aTo1O3M6NTk6InJlcXVpcmVcKFwkX1NFUlZFUlxbIkRPQ1VNRU5UX1JPT1QiXF1cLiIvYml0cml4L2hlYWRlclwucGhwIjtpOjY7czo1OToicmVxdWlyZVwoXCRfU0VSVkVSXFsiRE9DVU1FTlRfUk9PVCJcXVwuIi9iaXRyaXgvZm9vdGVyXC5waHAiO2k6NztzOjM3OiJlY2hvICI8c2NyaXB0PiBhbGVydFwoJyJcLlwkZGItPmdldEVyIjtpOjg7czo0MDoiZWNobyAiPHNjcmlwdD4gYWxlcnRcKCciXC5cJG1vZGVsLT5nZXRFciI7aTo5O3M6ODoic29ydFwoXCkiO2k6MTA7czoxMDoibXVzdC1yZXZhbCI7aToxMTtzOjY6InJpZXZhbCI7aToxMjtzOjk6ImRvdWJsZXZhbCI7aToxMztzOjE3OiJcJHNtYXJ0eS0+X2V2YWxcKCI7aToxNDtzOjMwOiJwcmVwXHMrcm1ccystcmZccysle2J1aWxkcm9vdH0iO2k6MTU7czoyMjoiVE9ETzpccytybVxzKy1yZlxzK3RoZSI7aToxNjtzOjI3OiJrcnNvcnRcKFwkd3BzbWlsaWVzdHJhbnNcKTsiO2k6MTc7czo2MzoiZG9jdW1lbnRcLndyaXRlXCh1bmVzY2FwZVwoIiUzQ3NjcmlwdCBzcmM9JyIgXCsgZ2FKc0hvc3QgXCsgImdvIjtpOjE4O3M6NjoiXC5leGVjIjtpOjE5O3M6ODoiZXhlY1woXCkiO2k6MjA7czoyMjoiXCR4MT1cJHRoaXMtPncgLSBcJHgxOyI7aToyMTtzOjMxOiJhc29ydFwoXCRDYWNoZURpck9sZEZpbGVzQWdlXCk7IjtpOjIyO3M6MTM6IlwoJ3I1N3NoZWxsJywiO2k6MjM7czoyMzoiZXZhbFwoImxpc3RlbmVyPSJcK2xpc3QiO2k6MjQ7czo4OiJldmFsXChcKSI7aToyNTtzOjMzOiJwcmVnX3JlcGxhY2VfY2FsbGJhY2tcKCcvXFx7XChpbWEiO2k6MjY7czoyMDoiZXZhbFwoX2N0TWVudUluaXRTdHIiO2k6Mjc7czoyOToiYmFzZTY0X2RlY29kZVwoXCRhY2NvdW50S2V5XCkiO2k6Mjg7czozODoiYmFzZTY0X2RlY29kZVwoXCRkYXRhXClcKTtcJGFwaS0+c2V0UmUiO2k6Mjk7czo0ODoicmVxdWlyZVwoXCRfU0VSVkVSXFtcXCJET0NVTUVOVF9ST09UXFwiXF1cLlxcIi9iIjtpOjMwO3M6NjQ6ImJhc2U2NF9kZWNvZGVcKFwkX1JFUVVFU1RcWydwYXJhbWV0ZXJzJ1xdXCk7aWZcKENoZWNrU2VyaWFsaXplZEQiO2k6MzE7czo2MToicGNudGxfZXhlYyc9PiBBcnJheVwoQXJyYXlcKDFcKSxcJGFyUmVzdWx0XFsnU0VDVVJJTkdfRlVOQ1RJTyI7aTozMjtzOjM5OiJlY2hvICI8c2NyaXB0PmFsZXJ0XCgnIlwuQ1V0aWw6OkpTRXNjYXAiO2k6MzM7czo2NjoiYmFzZTY0X2RlY29kZVwoXCRfUkVRVUVTVFxbJ3RpdGxlX2NoYW5nZXJfbGluaydcXVwpO2lmXChzdHJsZW5cKFwkIjtpOjM0O3M6NDQ6ImV2YWxcKCdcJGhleGR0aW1lPSInXC5cJGhleGR0aW1lXC4nIjsnXCk7XCRmIjtpOjM1O3M6NTI6ImVjaG8gIjxzY3JpcHQ+YWxlcnRcKCdcJHJvdy0+dGl0bGUgLSAiXC5fTU9EVUxFX0lTX0UiO2k6MzY7czozNzoiZWNobyAiPHNjcmlwdD5hbGVydFwoJ1wkY2lkcyAiXC5fQ0FOTiI7aTozNztzOjM3OiJpZlwoMVwpe1wkdl9ob3VyPVwoXCRwX2hlYWRlclxbJ210aW1lIjtpOjM4O3M6Njg6ImRvY3VtZW50XC53cml0ZVwodW5lc2NhcGVcKCIlM0NzY3JpcHQlMjBzcmM9JTIyaHR0cCIgXCtcKFwoImh0dHBzOiI9IjtpOjM5O3M6NTc6ImRvY3VtZW50XC53cml0ZVwodW5lc2NhcGVcKCIlM0NzY3JpcHQgc3JjPSciIFwrIHBrQmFzZVVSTCI7aTo0MDtzOjMyOiJlY2hvICI8c2NyaXB0PmFsZXJ0XCgnIlwuSlRleHQ6OiI7aTo0MTtzOjI0OiInZmlsZW5hbWUnXCksXCgncjU3c2hlbGwiO2k6NDI7czozOToiZWNobyAiPHNjcmlwdD5hbGVydFwoJyJcLlwkZXJyTXNnXC4iJ1wpIjtpOjQzO3M6NDI6ImVjaG8gIjxzY3JpcHQ+YWxlcnRcKFxcIkVycm9yIHdoZW4gbG9hZGluZyI7aTo0NDtzOjQzOiJlY2hvICI8c2NyaXB0PmFsZXJ0XCgnIlwuSlRleHQ6Ol9cKCdWQUxJRF9FIjtpOjQ1O3M6ODoiZXZhbFwoXCkiO2k6NDY7czo4OiInc3lzdGVtJyI7aTo0NztzOjY6IidldmFsJyI7aTo0ODtzOjY6IiJldmFsIiI7aTo0OTtzOjc6Il9zeXN0ZW0iO2k6NTA7czo5OiJzYXZlMmNvcHkiO2k6NTE7czoxMDoiZmlsZXN5c3RlbSI7aTo1MjtzOjg6InNlbmRtYWlsIjtpOjUzO3M6ODoiY2FuQ2htb2QiO2k6NTQ7czoxMzoiL2V0Yy9wYXNzd2RcKSI7aTo1NTtzOjI0OiJ1ZHA6Ly8nXC5zZWxmOjpcJF9jX2FkZHIiO2k6NTY7czozMzoiZWRvY2VkXzQ2ZXNhYlwoJydcfCJcKVxcXCknLCdyZWdlIjtpOjU3O3M6OToiZG91YmxldmFsIjtpOjU4O3M6MTY6Im9wZXJhdGluZyBzeXN0ZW0iO2k6NTk7czoxMDoiZ2xvYmFsZXZhbCI7aTo2MDtzOjE5OiJ3aXRoIDAvMC8wIGlmXCgxXCl7IjtpOjYxO3M6NDY6IlwkeDI9XCRwYXJhbVxbWyciXXswLDF9eFsnIl17MCwxfVxdIFwrIFwkd2lkdGgiO2k6NjI7czo5OiJzcGVjaWFsaXMiO2k6NjM7czo4OiJjb3B5XChcKSI7aTo2NDtzOjE5OiJ3cF9nZXRfY3VycmVudF91c2VyIjtpOjY1O3M6NzoiLT5jaG1vZCI7aTo2NjtzOjc6Il9tYWlsXCgiO2k6Njc7czo3OiJfY29weVwoIjtpOjY4O3M6NzoiJmNvcHlcKCI7aTo2OTtzOjQ1OiJzdHJwb3NcKFwkX1NFUlZFUlxbJ0hUVFBfVVNFUl9BR0VOVCdcXSwnRHJ1cGEiO2k6NzA7czoxNjoiZXZhbFwoY2xhc3NTdHJcKSI7aTo3MTtzOjMxOiJmdW5jdGlvbl9leGlzdHNcKCdiYXNlNjRfZGVjb2RlIjtpOjcyO3M6NDQ6ImVjaG8gIjxzY3JpcHQ+YWxlcnRcKCciXC5KVGV4dDo6X1woJ1ZBTElEX0VNIjtpOjczO3M6NDM6IlwkeDE9XCRtaW5feDtcJHgyPVwkbWF4X3g7XCR5MT1cJG1pbl95O1wkeTIiO2k6NzQ7czo0ODoiXCRjdG1cWydhJ1xdXClcKXtcJHg9XCR4IFwqIFwkdGhpcy0+aztcJHk9XChcJHRoIjtpOjc1O3M6NTk6IlsnIl17MCwxfWNyZWF0ZV9mdW5jdGlvblsnIl17MCwxfSxbJyJdezAsMX1nZXRfcmVzb3VyY2VfdHlwIjtpOjc2O3M6NDg6IlsnIl17MCwxfWNyZWF0ZV9mdW5jdGlvblsnIl17MCwxfSxbJyJdezAsMX1jcnlwdCI7aTo3NztzOjY4OiJzdHJwb3NcKFwkX1NFUlZFUlxbWyciXXswLDF9SFRUUF9VU0VSX0FHRU5UWyciXXswLDF9XF0sWyciXXswLDF9THlueCI7aTo3ODtzOjY3OiJzdHJzdHJcKFwkX1NFUlZFUlxbWyciXXswLDF9SFRUUF9VU0VSX0FHRU5UWyciXXswLDF9XF0sWyciXXswLDF9TVNJIjtpOjc5O3M6MjU6InNvcnRcKFwkRGlzdHJpYnV0aW9uXFtcJGsiO2k6ODA7czoyNToic29ydFwoZnVuY3Rpb25cKGEsYlwpe3JldCI7aTo4MTtzOjI1OiJodHRwOi8vd3d3XC5mYWNlYm9va1wuY29tIjtpOjgyO3M6MjU6Imh0dHA6Ly9tYXBzXC5nb29nbGVcLmNvbS8iO2k6ODM7czo0ODoidWRwOi8vJ1wuc2VsZjo6XCRjX2FkZHIsODAsXCRlcnJubyxcJGVycnN0ciwxNTAwIjtpOjg0O3M6MjA6IlwoXC5cKlwodmlld1wpXD9cLlwqIjtpOjg1O3M6NDQ6ImVjaG8gWyciXXswLDF9PHNjcmlwdD5hbGVydFwoWyciXXswLDF9XCR0ZXh0IjtpOjg2O3M6MTc6InNvcnRcKFwkdl9saXN0XCk7IjtpOjg3O3M6NzU6Im1vdmVfdXBsb2FkZWRfZmlsZVwoXCRfRklMRVNcWyd1cGxvYWRlZF9wYWNrYWdlJ1xdXFsndG1wX25hbWUnXF0sXCRtb3NDb25maSI7aTo4ODtzOjEyOiJmYWxzZVwpXCk7XCMiO2k6ODk7czo0Njoic3RycG9zXChcJF9TRVJWRVJcWydIVFRQX1VTRVJfQUdFTlQnXF0sJ01hYyBPUyI7aTo5MDtzOjUwOiJkb2N1bWVudFwud3JpdGVcKHVuZXNjYXBlXCgiJTNDc2NyaXB0IHNyYz0nL2JpdHJpeCI7aTo5MTtzOjI1OiJcJF9TRVJWRVIgXFsiUkVNT1RFX0FERFIiIjtpOjkyO3M6MTc6ImFIUjBjRG92TDJOeWJETXVaIjtpOjkzO3M6NTQ6IkpSZXNwb25zZTo6c2V0Qm9keVwocHJlZ19yZXBsYWNlXChcJHBhdHRlcm5zLFwkcmVwbGFjZSI7aTo5NDtzOjg6Ih+LCAAAAAAAIjtpOjk1O3M6ODoiUEsFBgAAAAAiO2k6OTY7czoxNDoiCQoLDA0gLz5cXVxbXF4iO2k6OTc7czo4OiKJUE5HDQoaCiI7aTo5ODtzOjEwOiJcKTtcI2knLCcmIjtpOjk5O3M6MTU6IlwpO1wjbWlzJywnJyxcJCI7aToxMDA7czoxOToiXCk7XCNpJyxcJGRhdGEsXCRtYSI7aToxMDE7czozNDoiXCRmdW5jXChcJHBhcmFtc1xbXCR0eXBlXF0tPnBhcmFtcyI7aToxMDI7czo4OiIfiwgAAAAAACI7aToxMDM7czo5OiIAAQIDBAUGBwgiO2k6MTA0O3M6MTI6IiFcI1wkJSYnXCpcKyI7aToxMDU7czo3OiKDi42bnp+hIjtpOjEwNjtzOjY6IgkKCwwNICI7aToxMDc7czozMzoiXC5cLi9cLlwuL1wuXC4vXC5cLi9tb2R1bGVzL21vZF9tIjtpOjEwODtzOjMwOiJcJGRlY29yYXRvclwoXCRtYXRjaGVzXFsxXF1cWzAiO2k6MTA5O3M6MjE6IlwkZGVjb2RlZnVuY1woXCRkXFtcJCI7aToxMTA7czoxNzoiX1wuXCtfYWJicmV2aWF0aW8iO2k6MTExO3M6NDU6InN0cmVhbV9zb2NrZXRfY2xpZW50XCgndGNwOi8vJ1wuXCRwcm94eS0+aG9zdCI7aToxMTI7czoyNToiZXZhbFwoZnVuY3Rpb25cKHAsYSxjLGssZSI7aToxMTM7czoyNToiJ3J1bmtpdF9mdW5jdGlvbl9yZW5hbWUnLCI7aToxMTQ7czo2OiKAgYKDhIUiO2k6MTE1O3M6NjoiAQIDBAUGIjtpOjExNjtzOjY6IgAAAAAAACI7aToxMTc7czoyMToiXCRtZXRob2RcKFwkYXJnc1xbMFxdIjtpOjExODtzOjIxOiJcJG1ldGhvZFwoXCRhcmdzXFswXF0iO2k6MTE5O3M6MjQ6IlwkbmFtZVwoXCRhcmd1bWVudHNcWzBcXSI7aToxMjA7czozMToic3Vic3RyXChtZDVcKHN1YnN0clwoXCR0b2tlbiwwLCI7aToxMjE7czoyNDoic3RycmV2XChzdWJzdHJcKHN0cnJldlwoIjtpOjEyMjtzOjM5OiJzdHJlYW1fc29ja2V0X2NsaWVudFwoJ3RjcDovLydcLlwkcHJveHkiO2k6MTIzO3M6MTg6IjwhLS0gUmVkSGVscGVyIC0tPiI7aToxMjQ7czozNjoiXCRlbGVtZW50XFtiXF1cKDBcKSx0aGlzXC50cmFuc2l0aW9uIjtpOjEyNTtzOjMxOiJcJG1ldGhvZFwoXCRyZWxhdGlvblxbJ2l0ZW1OYW1lIjtpOjEyNjtzOjM2OiJcJHZlcnNpb25cWzFcXVwpO31lbHNlaWZcKHByZWdfbWF0Y2giO2k6MTI3O3M6MzQ6IlwkY29tbWFuZFwoXCRjb21tYW5kc1xbXCRpZGVudGlmaWUiO2k6MTI4O3M6NDI6IlwkY2FsbGFibGVcKFwkcmF3XFsnY2FsbGJhY2snXF1cKFwkY1wpLFwkYyI7aToxMjk7czo0MjoiXCRlbFxbdmFsXF1cKFwpXCkgXCRlbFxbdmFsXF1cKGRhdGFcW3N0YXRlIjtpOjEzMDtzOjQ3OiJcJGVsZW1lbnRcW3RcXVwoMFwpLHRoaXNcLnRyYW5zaXRpb25cKCJhZGRDbGFzcyI7aToxMzE7czozMToiXCk7XCNtaXMnLCcgJyxcJGlucHV0XCk7XCRpbnB1dCI7aToxMzI7czozMToia2lsbCAtOSAnXC5cJHBpZFwpO1wkdGhpcy0+Y2xvcyI7aToxMzM7czozMjoiY2FsbF91c2VyX2Z1bmNcKFwkZmlsdGVyLFwkdmFsdWUiO2k6MTM0O3M6MzM6ImNhbGxfdXNlcl9mdW5jXChcJG9wdGlvbnMsXCRlcnJvciI7aToxMzU7czozNjoiY2FsbF91c2VyX2Z1bmNcKFwkbGlzdGVuZXIsXCRldmVudFwpIjtpOjEzNjtzOjY1OiJpZlwoc3RyaXBvc1woXCR1c2VyQWdlbnQsJ0FuZHJvaWQnXCkhPT1mYWxzZVwpe1wkdGhpcy0+bW9iaWxlPXRydSI7aToxMzc7czo1MzoiYmFzZTY0X2RlY29kZVwodXJsZGVjb2RlXChcJGZpbGVcKVwpPT0naW5kZXhcLnBocCdcKXsiO2k6MTM4O3M6NjA6InVybGRlY29kZVwoYmFzZTY0X2RlY29kZVwoXCRpbnB1dFwpXCk7XCRleHBsb2RlQXJyYXk9ZXhwbG9kZSI7aToxMzk7czozNzoiYmFzZTY0X2RlY29kZVwodXJsZGVjb2RlXChcJHJldHVyblVyaSI7aToxNDA7czo0NzoidXJsZGVjb2RlXCh1cmxkZWNvZGVcKHN0cmlwY3NsYXNoZXNcKFwkc2VnbWVudHMiO2k6MTQxO3M6NTM6Im1haWxcKFwkdG8sXCRzdWJqZWN0LFwkYm9keSxcJGhlYWRlclwpO31lbHNle1wkcmVzdWx0IjtpOjE0MjtzOjM4OiI9aW5pX2dldFwoJ2Rpc2FibGVfZnVuY3Rpb25zJ1wpO1wkdGhpcyI7aToxNDM7czo0MjoiPWluaV9nZXRcKCdkaXNhYmxlX2Z1bmN0aW9ucydcKTtpZlwoIWVtcHR5IjtpOjE0NDtzOjM5OiJldmFsXChcJHBocENvZGVcKTt9ZWxzZXtjbGFzc19hbGlhc1woXCQiO2k6MTQ1O3M6NDg6ImV2YWxcKFwkc3RyXCk7fXB1YmxpYyBmdW5jdGlvbiBjb3VudE1lbnVDaGlsZHJlbiI7fQ=="));
$g_AdwareSig = unserialize(base64_decode("YToxNjA6e2k6MDtzOjI1OiJzbGlua3NcLnN1L2dldF9saW5rc1wucGhwIjtpOjE7czoxMzoiTUxfbGNvZGVcLnBocCI7aToyO3M6MTM6Ik1MXyVjb2RlXC5waHAiO2k6MztzOjE5OiJjb2Rlc1wubWFpbmxpbmtcLnJ1IjtpOjQ7czoxOToiX19saW5rZmVlZF9yb2JvdHNfXyI7aTo1O3M6MTM6IkxJTktGRUVEX1VTRVIiO2k6NjtzOjE0OiJMaW5rZmVlZENsaWVudCI7aTo3O3M6MTg6Il9fc2FwZV9kZWxpbWl0ZXJfXyI7aTo4O3M6Mjk6ImRpc3BlbnNlclwuYXJ0aWNsZXNcLnNhcGVcLnJ1IjtpOjk7czoxMToiTEVOS19jbGllbnQiO2k6MTA7czoxMToiU0FQRV9jbGllbnQiO2k6MTE7czoxNjoiX19saW5rZmVlZF9lbmRfXyI7aToxMjtzOjE2OiJTTEFydGljbGVzQ2xpZW50IjtpOjEzO3M6MjA6Im5ld1xzK0xMTV9jbGllbnRcKFwpIjtpOjE0O3M6MTc6ImRiXC50cnVzdGxpbmtcLnJ1IjtpOjE1O3M6NjM6IlwkX1NFUlZFUlxbXHMqWyciXUhUVFBfUkVGRVJFUlsnIl1ccypcXVxzKixccypbJyJddHJ1c3RsaW5rXC5ydSI7aToxNjtzOjQyOiJcJFthLXpBLVowLTlfXStccyo9XHMqbmV3XHMqQlNcKFwpO1xzKmVjaG8iO2k6MTc7czozNzoiY2xhc3NccytDTV9jbGllbnRccytleHRlbmRzXHMqQ01fYmFzZSI7aToxODtzOjE5OiJuZXdccytDTV9jbGllbnRcKFwpIjtpOjE5O3M6MTY6InRsX2xpbmtzX2RiX2ZpbGUiO2k6MjA7czoyMDoiY2xhc3NccytsbXBfYmFzZVxzK3siO2k6MjE7czoxNToiVHJ1c3RsaW5rQ2xpZW50IjtpOjIyO3M6MTM6Ii0+XHMqU0xDbGllbnQiO2k6MjM7czoxNjY6Imlzc2V0XHMqXCg/XHMqXCRfU0VSVkVSXHMqXFtccypbJyJdezAsMX1IVFRQX1VTRVJfQUdFTlRbJyJdezAsMX1ccypcXVxzKlwpXHMqJiZccypcKD9ccypcJF9TRVJWRVJccypcW1xzKlsnIl17MCwxfUhUVFBfVVNFUl9BR0VOVFsnIl17MCwxfVxdXHMqPT1ccypbJyJdezAsMX1MTVBfUm9ib3QiO2k6MjQ7czo0MzoiXCRsaW5rcy0+XHMqcmV0dXJuX2xpbmtzXHMqXCg/XHMqXCRsaWJfcGF0aCI7aToyNTtzOjQ0OiJcJGxpbmtzX2NsYXNzXHMqPVxzKm5ld1xzK0dldF9saW5rc1xzKlwoP1xzKiI7aToyNjtzOjUyOiJbJyJdezAsMX1ccyosXHMqWyciXXswLDF9XC5bJyJdezAsMX1ccypcKT9ccyo7XHMqXD8+IjtpOjI3O3M6OToiXGJsZXZpdHJhIjtpOjI4O3M6MTI6IlxiZGFwb3hldGluZSI7aToyOTtzOjg6IlxidmlhZ3JhIjtpOjMwO3M6ODoiXGJjaWFsaXMiO2k6MzE7czoxMDoiXGJwcm92aWdpbCI7aTozMjtzOjE5OiJjbGFzc1xzK1RXZWZmQ2xpZW50IjtpOjMzO3M6MTg6Im5ld1xzK1NMQ2xpZW50XChcKSI7aTozNDtzOjI0OiJfX2xpbmtmZWVkX2JlZm9yZV90ZXh0X18iO2k6MzU7czoxNjoiX190ZXN0X3RsX2xpbmtfXyI7aTozNjtzOjE4OiJzOjExOiJsbXBfY2hhcnNldCIiO2k6Mzc7czoyMDoiPVxzK25ld1xzK01MQ2xpZW50XCgiO2k6Mzg7czo0NzoiZWxzZVxzK2lmXHMqXChccypcKFxzKnN0cnBvc1woXHMqXCRsaW5rc19pcFxzKiwiO2k6Mzk7czozMzoiZnVuY3Rpb25ccytwb3dlcl9saW5rc19ibG9ja192aWV3IjtpOjQwO3M6MjA6ImNsYXNzXHMrSU5HT1RTQ2xpZW50IjtpOjQxO3M6MTA6Il9fTElOS19fPGEiO2k6NDI7czoyMToiY2xhc3NccytMaW5rcGFkX3N0YXJ0IjtpOjQzO3M6MTM6ImNsYXNzXHMrVE5YX2wiO2k6NDQ7czoyMjoiY2xhc3NccytNRUdBSU5ERVhfYmFzZSI7aTo0NTtzOjE1OiJfX0xJTktfX19fRU5EX18iO2k6NDY7czoyMjoibmV3XHMrVFJVU1RMSU5LX2NsaWVudCI7aTo0NztzOjc0OiJyXC5waHBcP2lkPVthLXpBLVowLTlfXSsmcmVmZXJlcj0le0hUVFBfSE9TVH0vJXtSRVFVRVNUX1VSSX1ccytcW1I9MzAyLExcXSI7aTo0ODtzOjM5OiJVc2VyLWFnZW50OlxzKkdvb2dsZWJvdFxzKkRpc2FsbG93OlxzKi8iO2k6NDk7czoxODoibmV3XHMrTExNX2NsaWVudFwoIjtpOjUwO3M6MzY6IiZyZWZlcmVyPSV7SFRUUF9IT1NUfS8le1JFUVVFU1RfVVJJfSI7aTo1MTtzOjI5OiJcLnBocFw/aWQ9XCQxJiV7UVVFUllfU1RSSU5HfSI7aTo1MjtzOjMzOiJBZGRUeXBlXHMrYXBwbGljYXRpb24veC1odHRwZC1waHAiO2k6NTM7czoyMzoiQWRkSGFuZGxlclxzK3BocC1zY3JpcHQiO2k6NTQ7czoyMzoiQWRkSGFuZGxlclxzK2NnaS1zY3JpcHQiO2k6NTU7czo1MjoiUmV3cml0ZVJ1bGVccytcLlwqXHMraW5kZXhcLnBocFw/dXJsPVwkMFxzK1xbTCxRU0FcXSI7aTo1NjtzOjEyOiJwaHBpbmZvXChcKTsiO2k6NTc7czoxNToiXChtc2llXHxvcGVyYVwpIjtpOjU4O3M6MjI6IjxoMT5Mb2FkaW5nXC5cLlwuPC9oMT4iO2k6NTk7czoyOToiRXJyb3JEb2N1bWVudFxzKzUwMFxzK2h0dHA6Ly8iO2k6NjA7czoyOToiRXJyb3JEb2N1bWVudFxzKzQwMFxzK2h0dHA6Ly8iO2k6NjE7czoyOToiRXJyb3JEb2N1bWVudFxzKzQwNFxzK2h0dHA6Ly8iO2k6NjI7czo0OToiUmV3cml0ZUNvbmRccyole0hUVFBfVVNFUl9BR0VOVH1ccypcLlwqbmRyb2lkXC5cKiI7aTo2MztzOjEwMToiPHNjcmlwdFxzK2xhbmd1YWdlPVsnIl17MCwxfUphdmFTY3JpcHRbJyJdezAsMX0+XHMqcGFyZW50XC53aW5kb3dcLm9wZW5lclwubG9jYXRpb25ccyo9XHMqWyciXWh0dHA6Ly8iO2k6NjQ7czo5OToiY2hyXHMqXChccyoxMDFccypcKVxzKlwuXHMqY2hyXHMqXChccyoxMThccypcKVxzKlwuXHMqY2hyXHMqXChccyo5N1xzKlwpXHMqXC5ccypjaHJccypcKFxzKjEwOFxzKlwpIjtpOjY1O3M6MzA6ImN1cmxcLmhheHhcLnNlL3JmYy9jb29raWVfc3BlYyI7aTo2NjtzOjE4OiJKb29tbGFfYnJ1dGVfRm9yY2UiO2k6Njc7czozNDoiUmV3cml0ZUNvbmRccyole0hUVFA6eC13YXAtcHJvZmlsZSI7aTo2ODtzOjQyOiJSZXdyaXRlQ29uZFxzKiV7SFRUUDp4LW9wZXJhbWluaS1waG9uZS11YX0iO2k6Njk7czo2NjoiUmV3cml0ZUNvbmRccyole0hUVFA6QWNjZXB0LUxhbmd1YWdlfVxzKlwocnVcfHJ1LXJ1XHx1a1wpXHMqXFtOQ1xdIjtpOjcwO3M6MjY6InNsZXNoXCtzbGVzaFwrZG9tZW5cK3BvaW50IjtpOjcxO3M6MTc6InRlbGVmb25uYXlhLWJhemEtIjtpOjcyO3M6MTg6ImljcS1kbHlhLXRlbGVmb25hLSI7aTo3MztzOjI0OiJwYWdlX2ZpbGVzL3N0eWxlMDAwXC5jc3MiO2k6NzQ7czoyMDoic3ByYXZvY2huaWstbm9tZXJvdi0iO2k6NzU7czoxNzoiS2F6YW4vaW5kZXhcLmh0bWwiO2k6NzY7czo1MDoiR29vZ2xlYm90WyciXXswLDF9XHMqXClcKXtlY2hvXHMrZmlsZV9nZXRfY29udGVudHMiO2k6Nzc7czoyNjoiaW5kZXhcLnBocFw/aWQ9XCQxJiV7UVVFUlkiO2k6Nzg7czoyMDoiVm9sZ29ncmFkaW5kZXhcLmh0bWwiO2k6Nzk7czozODoiQWRkVHlwZVxzK2FwcGxpY2F0aW9uL3gtaHR0cGQtY2dpXHMrXC4iO2k6ODA7czoxOToiLWtseWNoLWstaWdyZVwuaHRtbCI7aTo4MTtzOjE5OiJsbXBfY2xpZW50XChzdHJjb2RlIjtpOjgyO3M6MTc6Ii9cP2RvPWthay11ZGFsaXQtIjtpOjgzO3M6MTQ6Ii9cP2RvPW9zaGlia2EtIjtpOjg0O3M6MTk6Ii9pbnN0cnVrdHNpeWEtZGx5YS0iO2k6ODU7czo0MzoiY29udGVudD0iXGQrO1VSTD1odHRwczovL2RvY3NcLmdvb2dsZVwuY29tLyI7aTo4NjtzOjU5OiIlPCEtLVxcc1wqXCRtYXJrZXJcXHNcKi0tPlwuXCtcPzwhLS1cXHNcKi9cJG1hcmtlclxcc1wqLS0+JSI7aTo4NztzOjc5OiJSZXdyaXRlUnVsZVxzK1xeXChcLlwqXCksXChcLlwqXClcJFxzK1wkMlwucGhwXD9yZXdyaXRlX3BhcmFtcz1cJDEmcGFnZV91cmw9XCQyIjtpOjg4O3M6NDI6IlJld3JpdGVSdWxlXHMqXChcLlwrXClccyppbmRleFwucGhwXD9zPVwkMCI7aTo4OTtzOjE4OiJSZWRpcmVjdFxzKmh0dHA6Ly8iO2k6OTA7czo0NToiUmV3cml0ZVJ1bGVccypcXlwoXC5cKlwpXHMqaW5kZXhcLnBocFw/aWQ9XCQxIjtpOjkxO3M6NDQ6IlJld3JpdGVSdWxlXHMqXF5cKFwuXCpcKVxzKmluZGV4XC5waHBcP209XCQxIjtpOjkyO3M6MTk4OiJcYihwZXJjb2NldHxhZGRlcmFsbHx2aWFncmF8Y2lhbGlzfGxldml0cmF8a2F1ZmVufGFtYmllbnxibHVlXHMrcGlsbHxjb2NhaW5lfG1hcmlqdWFuYXxsaXBpdG9yfHBoZW50ZXJtaW58cHJvW3N6XWFjfHNhbmR5YXVlcnx0cmFtYWRvbHx0cm95aGFtYnl1bHRyYW18dW5pY2F1Y2F8dmFsaXVtfHZpY29kaW58eGFuYXh8eXB4YWllbylccytvbmxpbmUiO2k6OTM7czo0OToiUmV3cml0ZVJ1bGVccypcLlwqL1wuXCpccypbYS16QS1aMC05X10rXC5waHBcP1wkMCI7aTo5NDtzOjM5OiJSZXdyaXRlQ29uZFxzKyV7UkVNT1RFX0FERFJ9XHMrXF44NVwuMjYiO2k6OTU7czo0MToiUmV3cml0ZUNvbmRccysle1JFTU9URV9BRERSfVxzK1xeMjE3XC4xMTgiO2k6OTY7czo1MjoiUmV3cml0ZUVuZ2luZVxzK09uXHMqUmV3cml0ZUJhc2VccysvXD9bYS16QS1aMC05X10rPSI7aTo5NztzOjMyOiJFcnJvckRvY3VtZW50XHMrNDA0XHMraHR0cDovL3RkcyI7aTo5ODtzOjUxOiJSZXdyaXRlUnVsZVxzK1xeXChcLlwqXClcJFxzK2h0dHA6Ly9cZCtcLlxkK1wuXGQrXC4iO2k6OTk7czo3MzoiPCEtLWNoZWNrOlsnIl1ccypcLlxzKm1kNVwoXHMqXCRfKEdFVHxQT1NUfFNFUlZFUnxDT09LSUV8UkVRVUVTVHxGSUxFUylcWyI7aToxMDA7czoxODoiUmV3cml0ZUJhc2Vccysvd3AtIjtpOjEwMTtzOjM2OiJTZXRIYW5kbGVyXHMrYXBwbGljYXRpb24veC1odHRwZC1waHAiO2k6MTAyO3M6NDI6IiV7SFRUUF9VU0VSX0FHRU5UfVxzKyF3aW5kb3dzLW1lZGlhLXBsYXllciI7aToxMDM7czo4MjoiXChccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfUhUVFBfVVNFUl9BR0VOVFsnIl17MCwxfVxzKlxdXHMqLFxzKlsnIl17MCwxfVlhbmRleEJvdCI7aToxMDQ7czo3NjoiXChccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfUhUVFBfUkVGRVJFUlsnIl17MCwxfVxzKlxdXHMqLFxzKlsnIl17MCwxfXlhbmRleCI7aToxMDU7czo3NjoiXChccypcJF9TRVJWRVJcW1xzKlsnIl17MCwxfUhUVFBfUkVGRVJFUlsnIl17MCwxfVxzKlxdXHMqLFxzKlsnIl17MCwxfWdvb2dsZSI7aToxMDY7czo4OiIva3J5YWtpLyI7aToxMDc7czoxMDoiXC5waHBcP1wkMCI7aToxMDg7czo3MToicmVxdWVzdFwuc2VydmVydmFyaWFibGVzXChbJyJdSFRUUF9VU0VSX0FHRU5UWyciXVwpXHMqLFxzKlsnIl1Hb29nbGVib3QiO2k6MTA5O3M6ODA6ImluZGV4XC5waHBcP21haW5fcGFnZT1wcm9kdWN0X2luZm8mcHJvZHVjdHNfaWQ9WyciXVxzKlwuXHMqc3RyX3JlcGxhY2VcKFsnIl1saXN0IjtpOjExMDtzOjMxOiJmc29ja29wZW5cKFxzKlsnIl1zaGFkeWtpdFwuY29tIjtpOjExMTtzOjEwOiJlb2ppZXVcLmNuIjtpOjExMjtzOjIyOiI+XHMqPC9pZnJhbWU+XHMqPFw/cGhwIjtpOjExMztzOjgxOiI8bWV0YVxzK2h0dHAtZXF1aXY9WyciXXswLDF9cmVmcmVzaFsnIl17MCwxfVxzK2NvbnRlbnQ9WyciXXswLDF9XGQrO1xzKnVybD08XD9waHAiO2k6MTE0O3M6ODI6IjxtZXRhXHMraHR0cC1lcXVpdj1bJyJdezAsMX1SZWZyZXNoWyciXXswLDF9XHMrY29udGVudD1bJyJdezAsMX1cZCs7XHMqVVJMPWh0dHA6Ly8iO2k6MTE1O3M6Njc6IlwkZmxccyo9XHMqIjxtZXRhIGh0dHAtZXF1aXY9XFwiUmVmcmVzaFxcIlxzK2NvbnRlbnQ9XFwiXGQrO1xzKlVSTD0iO2k6MTE2O3M6Mzg6IlJld3JpdGVDb25kXHMqJXtIVFRQX1JFRkVSRVJ9XHMqeWFuZGV4IjtpOjExNztzOjM4OiJSZXdyaXRlQ29uZFxzKiV7SFRUUF9SRUZFUkVSfVxzKmdvb2dsZSI7aToxMTg7czo1NzoiT3B0aW9uc1xzK0ZvbGxvd1N5bUxpbmtzXHMrTXVsdGlWaWV3c1xzK0luZGV4ZXNccytFeGVjQ0dJIjtpOjExOTtzOjI4OiJnb29nbGVcfHlhbmRleFx8Ym90XHxyYW1ibGVyIjtpOjEyMDtzOjQxOiJjb250ZW50PVsnIl17MCwxfTE7VVJMPWNnaS1iaW5cLmh0bWxcP2NtZCI7aToxMjE7czoxMjoiYW5kZXhcfG9vZ2xlIjtpOjEyMjtzOjQ0OiJoZWFkZXJcKFxzKlsnIl1SZWZyZXNoOlxzKlxkKztccypVUkw9aHR0cDovLyI7aToxMjM7czo0NToiTW96aWxsYS81XC4wXHMqXChjb21wYXRpYmxlO1xzKkdvb2dsZWJvdC8yXC4xIjtpOjEyNDtzOjUwOiJodHRwOi8vd3d3XC5iaW5nXC5jb20vc2VhcmNoXD9xPVwkcXVlcnkmcHE9XCRxdWVyeSI7aToxMjU7czo0MzoiaHR0cDovL2dvXC5tYWlsXC5ydS9zZWFyY2hcP3E9WyciXVwuXCRxdWVyeSI7aToxMjY7czo2MzoiaHR0cDovL3d3d1wuZ29vZ2xlXC5jb20vc2VhcmNoXD9xPVsnIl1cLlwkcXVlcnlcLlsnIl0maGw9XCRsYW5nIjtpOjEyNztzOjM2OiJTZXRIYW5kbGVyXHMrYXBwbGljYXRpb24veC1odHRwZC1waHAiO2k6MTI4O3M6NDk6ImlmXChzdHJpcG9zXChcJHVhLFsnIl1hbmRyb2lkWyciXVwpXHMqIT09XHMqZmFsc2UiO2k6MTI5O3M6MTUyOiIoc2V4eVxzK2xlc2JpYW5zfGN1bVxzK3ZpZGVvfHNleFxzK3ZpZGVvfEFuYWxccytGdWNrfHRlZW5ccytzZXh8ZnVja1xzK3ZpZGVvfEJlYWNoXHMrTnVkZXx3b21hblxzK3B1c3N5fHNleFxzK3Bob3RvfG5ha2VkXHMrdGVlbnx4eHhccyt2aWRlb3x0ZWVuXHMrcGljKSI7aToxMzA7czo1NjoiaHR0cC1lcXVpdj1bJyJdQ29udGVudC1MYW5ndWFnZVsnIl1ccytjb250ZW50PVsnIl1qYVsnIl0iO2k6MTMxO3M6NTY6Imh0dHAtZXF1aXY9WyciXUNvbnRlbnQtTGFuZ3VhZ2VbJyJdXHMrY29udGVudD1bJyJdY2hbJyJdIjtpOjEzMjtzOjExOiJLQVBQVVNUT0JPVCI7aToxMzM7czozODoiY2xhc3NccytsVHJhbnNtaXRlcntccyp2YXJccypcJHZlcnNpb24iO2k6MTM0O3M6Mzc6IlwkW2EtekEtWjAtOV9dK1xzKj1ccypbJyJdL3RtcC9zc2Vzc18iO2k6MTM1O3M6OTE6ImZpbGVfZ2V0X2NvbnRlbnRzXChiYXNlNjRfZGVjb2RlXChcJFthLXpBLVowLTlfXStcKVwuWyciXVw/WyciXVwuaHR0cF9idWlsZF9xdWVyeVwoXCRfR0VUXCkiO2k6MTM2O3M6NTA6ImluaV9zZXRcKFsnIl17MCwxfXVzZXJfYWdlbnRbJyJdXHMqLFxzKlsnIl1KU0xJTktTIjtpOjEzNztzOjYzOiJcJGRiLT5xdWVyeVwoWyciXVNFTEVDVCBcKiBGUk9NIGFydGljbGUgV0hFUkUgdXJsPVsnIl1cJHJlcXVlc3QiO2k6MTM4O3M6MjQ6IjxodG1sXHMrbGFuZz1bJyJdamFbJyJdPiI7aToxMzk7czozNzoieG1sOmxhbmc9WyciXWphWyciXVxzK2xhbmc9WyciXWphWyciXSI7aToxNDA7czoxNjoibGFuZz1bJyJdamFbJyJdPiI7aToxNDE7czozMzoic3RycG9zXChcJGltLFsnIl1cWy9VUERfQ09OVEVOVFxdIjtpOjE0MjtzOjU5OiI9PVxzKlsnIl1pbmRleFwucGhwWyciXVwpXHMqe1xzKnByaW50XHMrZmlsZV9nZXRfY29udGVudHNcKCI7aToxNDM7czoxNToiY2xhc3NccytGYXRsaW5rIjtpOjE0NDtzOjQwOiJcJGY9ZmlsZV9nZXRfY29udGVudHNcKCJrZXlzLyJcLlwka2V5ZlwpIjtpOjE0NTtzOjU2OiJSZXdyaXRlUnVsZVxzK1xeXChcLlwqXClcXFwuaHRtbFwkXHMraW5kZXhcLnBocFxzK1xbbmNcXSI7aToxNDY7czo0NToibWtkaXJcKFsnIl1wYWdlL1snIl1cLm1iX3N1YnN0clwobWQ1XChcJGtleVwpIjtpOjE0NztzOjQ3OiJlbHNlaWYgXChAXCRfR0VUXFtbJyJdcFsnIl1cXSA9PSBbJyJdaHRtbFsnIl1cKSI7aToxNDg7czo4ODoiUmV3cml0ZVJ1bGVccytcXlwoXC5cKlwpXFwvXCRccytpbmRleFwucGhwXHMrUmV3cml0ZVJ1bGVccytcXnJvYm90c1wudHh0XCRccytyb2JvdHNcLnBocCI7aToxNDk7czoxMTU6ImlmXChzdHJpcG9zXChcJF9TRVJWRVJcW1snIl1IVFRQX1VTRVJfQUdFTlRbJyJdXF0sXHMqWyciXUdvb2dsZWJvdFsnIl1cKVxzKiE9PVxzKmZhbHNlXCl7XHMqXCR1cmxccyo9XHMqWyciXWh0dHA6Ly8iO2k6MTUwO3M6MjE6IlwkcGF0aF90b19kb3Jccyo9XHMqIiI7aToxNTE7czozOToic3RycmV2XChzdHJ0b3VwcGVyXChbJyJddG5lZ2FfcmVzdV9wdHRoIjtpOjE1MjtzOjYyOiJmaWxlX3B1dF9jb250ZW50c1woWyciXWNvbmZcLnBocFsnIl1ccyosXHMqWyciXVxcblxcXCRzdG9wcGFnZSI7aToxNTM7czozMzoic2Vzc2lvbl9uYW1lXChbJyJddXNlcm9pbnRlcmZlaXNvIjtpOjE1NDtzOjgxOiJSZXdyaXRlUnVsZVxzKlxeXChcW0EtWmEtejAtOS1cXVwrXClcLmh0bWxcJFxzKlthLXpBLVowLTlfXStcLnBocFw/aGw9XCQxXHMqXFtMXF0iO2k6MTU1O3M6Nzk6IlwkaWRccyo9XHMqXCRfUkVRVUVTVFxbWyciXWlkWyciXVxdO1xzKlwkY2hccyo9XHMqY3VybF9pbml0XChcKTtccypcJHVybF9zdHJpbmciO2k6MTU2O3M6NjA6IlwkcGFnZXBhcnNlPWZpbGVfZ2V0X2NvbnRlbnRzXCgiaHR0cDovL3d3d1wuYXNrXC5jb20vd2ViXD9xPSI7aToxNTc7czo2NDoiPE1FVEFccypIVFRQLUVRVUlWPVsnIl1yZWZyZXNoWyciXVxzKkNPTlRFTlQ9WyciXTBcLlxkKztVUkw9aHR0cCI7aToxNTg7czo1MToiZnVuY3Rpb25ccytyZWRpclRpbWVyXChccypcKVxzKntccypzZWxmXC5zZXRUaW1lb3V0IjtpOjE1OTtzOjEzOiJ4bWw6bGFuZz0iamEiIjt9"));
$g_PhishingSig = unserialize(base64_decode("YToxMDI6e2k6MDtzOjExOiJDVlY6XHMqXCRjdiI7aToxO3M6MTM6IkludmFsaWRccytUVk4iO2k6MjtzOjExOiJJbnZhbGlkIFJWTiI7aTozO3M6NDA6ImRlZmF1bHRTdGF0dXNccyo9XHMqWyciXUludGVybmV0IEJhbmtpbmciO2k6NDtzOjI4OiI8dGl0bGU+XHMqQ2FwaXRlY1xzK0ludGVybmV0IjtpOjU7czoyNzoiPHRpdGxlPlxzKkludmVzdGVjXHMrT25saW5lIjtpOjY7czozOToiaW50ZXJuZXRccytQSU5ccytudW1iZXJccytpc1xzK3JlcXVpcmVkIjtpOjc7czoxMToiPHRpdGxlPlNhcnMiO2k6ODtzOjEzOiI8YnI+QVRNXHMrUElOIjtpOjk7czoxODoiQ29uZmlybWF0aW9uXHMrT1RQIjtpOjEwO3M6MjU6Ijx0aXRsZT5ccypBYnNhXHMrSW50ZXJuZXQiO2k6MTE7czoyMToiLVxzKlBheVBhbFxzKjwvdGl0bGU+IjtpOjEyO3M6MTk6Ijx0aXRsZT5ccypQYXlccypQYWwiO2k6MTM7czoyMjoiLVxzKlByaXZhdGlccyo8L3RpdGxlPiI7aToxNDtzOjE5OiI8dGl0bGU+XHMqVW5pQ3JlZGl0IjtpOjE1O3M6MTk6IkJhbmtccytvZlxzK0FtZXJpY2EiO2k6MTY7czoyNToiQWxpYmFiYSZuYnNwO01hbnVmYWN0dXJlciI7aToxNztzOjIxOiJIb25nXHMrTGVvbmdccytPbmxpbmUiO2k6MTg7czozMDoiWW91clxzK2FjY291bnRccytcfFxzK0xvZ1xzK2luIjtpOjE5O3M6MjQ6Ijx0aXRsZT5ccypPbmxpbmUgQmFua2luZyI7aToyMDtzOjI0OiI8dGl0bGU+XHMqT25saW5lLUJhbmtpbmciO2k6MjE7czoyMjoiU2lnblxzK2luXHMrdG9ccytZYWhvbyI7aToyMjtzOjE2OiJZYWhvb1xzKjwvdGl0bGU+IjtpOjIzO3M6MTE6IkJBTkNPTE9NQklBIjtpOjI0O3M6MTY6Ijx0aXRsZT5ccypBbWF6b24iO2k6MjU7czoxNToiPHRpdGxlPlxzKkFwcGxlIjtpOjI2O3M6MTU6Ijx0aXRsZT5ccypHbWFpbCI7aToyNztzOjI4OiJHb29nbGVccytBY2NvdW50c1xzKjwvdGl0bGU+IjtpOjI4O3M6MjU6Ijx0aXRsZT5ccypHb29nbGVccytTZWN1cmUiO2k6Mjk7czozMToiPHRpdGxlPlxzKk1lcmFrXHMrTWFpbFxzK1NlcnZlciI7aTozMDtzOjI2OiI8dGl0bGU+XHMqU29ja2V0XHMrV2VibWFpbCI7aTozMTtzOjIxOiI8dGl0bGU+XHMqXFtMX1FVRVJZXF0iO2k6MzI7czozNDoiPHRpdGxlPlxzKkFOWlxzK0ludGVybmV0XHMrQmFua2luZyI7aTozMztzOjMzOiJjb21cLndlYnN0ZXJiYW5rXC5zZXJ2bGV0c1wuTG9naW4iO2k6MzQ7czoxNToiPHRpdGxlPlxzKkdtYWlsIjtpOjM1O3M6MTg6Ijx0aXRsZT5ccypGYWNlYm9vayI7aTozNjtzOjM2OiJcZCs7VVJMPWh0dHBzOi8vd3d3XC53ZWxsc2ZhcmdvXC5jb20iO2k6Mzc7czoyMzoiPHRpdGxlPlxzKldlbGxzXHMqRmFyZ28iO2k6Mzg7czo0OToicHJvcGVydHk9Im9nOnNpdGVfbmFtZSJccypjb250ZW50PSJGYWNlYm9vayJccyovPiI7aTozOTtzOjIyOiJBZXNcLkN0clwuZGVjcnlwdFxzKlwoIjtpOjQwO3M6MTc6Ijx0aXRsZT5ccypBbGliYWJhIjtpOjQxO3M6MTk6IlJhYm9iYW5rXHMqPC90aXRsZT4iO2k6NDI7czozNToiXCRtZXNzYWdlXHMqXC49XHMqWyciXXswLDF9UGFzc3dvcmQiO2k6NDM7czo2OToiXCRDVlYyQ1xzKj1ccypcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbXHMqWyciXUNWVjJDIjtpOjQ0O3M6MTQ6IkNWVjI6XHMqXCRDVlYyIjtpOjQ1O3M6MTg6IlwuaHRtbFw/Y21kPWxvZ2luPSI7aTo0NjtzOjE4OiJXZWJtYWlsXHMqPC90aXRsZT4iO2k6NDc7czoyMzoiPHRpdGxlPlxzKlVQQ1xzK1dlYm1haWwiO2k6NDg7czoxNzoiXC5waHBcP2NtZD1sb2dpbj0iO2k6NDk7czoxNzoiXC5odG1cP2NtZD1sb2dpbj0iO2k6NTA7czoyMzoiXC5zd2VkYmFua1wuc2UvbWRwYXlhY3MiO2k6NTE7czoyNDoiXC5ccypcJF9QT1NUXFtccypbJyJdY3Z2IjtpOjUyO3M6MjA6Ijx0aXRsZT5ccypMQU5ERVNCQU5LIjtpOjUzO3M6MTA6IkJZLVNQMU4wWkEiO2k6NTQ7czo0NToiU2VjdXJpdHlccytxdWVzdGlvblxzKzpccytbJyJdXHMqXC5ccypcJF9QT1NUIjtpOjU1O3M6NDA6ImlmXChccypmaWxlX2V4aXN0c1woXHMqXCRzY2FtXHMqXC5ccypcJGkiO2k6NTY7czoyMDoiPHRpdGxlPlxzKkJlc3QudGlnZW4iO2k6NTc7czoyMDoiPHRpdGxlPlxzKkxBTkRFU0JBTksiO2k6NTg7czo1Mjoid2luZG93XC5sb2NhdGlvblxzKj1ccypbJyJdaW5kZXhcZCs/XC5waHBcP2NtZD1sb2dpbiI7aTo1OTtzOjU0OiJ3aW5kb3dcLmxvY2F0aW9uXHMqPVxzKlsnIl1pbmRleFxkKz9cLmh0bWw/XD9jbWQ9bG9naW4iO2k6NjA7czoyNToiPHRpdGxlPlxzKk1haWxccyo8L3RpdGxlPiI7aTo2MTtzOjI4OiJTaWVccytJaHJccytLb250b1xzKjwvdGl0bGU+IjtpOjYyO3M6Mjk6IlBheXBhbFxzK0tvbnRvXHMrdmVyaWZpemllcmVuIjtpOjYzO3M6MzA6IlwkX0dFVFxbXHMqWyciXWNjX2NvdW50cnlfY29kZSI7aTo2NDtzOjI5OiI8dGl0bGU+T3V0bG9va1xzK1dlYlxzK0FjY2VzcyI7aTo2NTtzOjk6Il9DQVJUQVNJXyI7aTo2NjtzOjc2OiI8bWV0YVxzK2h0dHAtZXF1aXY9WyciXXJlZnJlc2hbJyJdXHMqY29udGVudD0iXGQrO1xzKnVybD1kYXRhOnRleHQvaHRtbDtodHRwIjtpOjY3O3M6MzA6ImNhblxzKnNpZ25ccyppblxzKnRvXHMqZHJvcGJveCI7aTo2ODtzOjM1OiJcZCs7XHMqVVJMPWh0dHBzOi8vd3d3XC5nb29nbGVcLmNvbSI7aTo2OTtzOjI2OiJtYWlsXC5ydS9zZXR0aW5ncy9zZWN1cml0eSI7aTo3MDtzOjU5OiJMb2NhdGlvbjpccypodHRwczovL3NlY3VyaXR5XC5nb29nbGVcLmNvbS9zZXR0aW5ncy9zZWN1cml0eSI7aTo3MTtzOjY1OiJcJGlwXHMqPVxzKmdldGVudlwoXHMqWyciXVJFTU9URV9BRERSWyciXVxzKlwpO1xzKlwkbWVzc2FnZVxzKlwuPSI7aTo3MjtzOjE3OiJsb2dpblwuZWMyMVwuY29tLyI7aTo3MztzOjY2OiJcJF8oR0VUfFBPU1R8U0VSVkVSfENPT0tJRXxSRVFVRVNUfEZJTEVTKVxbWyciXXswLDF9Y3Z2WyciXXswLDF9XF0iO2k6NzQ7czozNDoiXCRhZGRkYXRlPWRhdGVcKCJEIE0gZCwgWSBnOmkgYSJcKSI7aTo3NTtzOjM2OiJcJGRhdGFtYXNpaT1kYXRlXCgiRCBNIGQsIFkgZzppIGEiXCkiO2k6NzY7czoyNzoiaHR0cHM6Ly9hcHBsZWlkXC5hcHBsZVwuY29tIjtpOjc3O3M6MTQ6Ii1BcHBsZV9SZXN1bHQtIjtpOjc4O3M6MTM6IkFPTFxzK0RldGFpbHMiO2k6Nzk7czo0MzoiXCRfUE9TVFxbXHMqWyciXXswLDF9ZU1haWxBZGRbJyJdezAsMX1ccypcXSI7aTo4MDtzOjQwOiJiYXNlXHMraHJlZj1bJyJdaHR0cHM6Ly9sb2dpblwubGl2ZVwuY29tIjtpOjgxO3M6MjQ6Ijx0aXRsZT5Ib3RtYWlsXHMrQWNjb3VudCI7aTo4MjtzOjQxOiI8IS0tXHMrc2F2ZWRccytmcm9tXHMrdXJsPVwoXGQrXClodHRwczovLyI7aTo4MztzOjIwOiJCYW5rXHMrb2ZccytNb250cmVhbCI7aTo4NDtzOjIxOiJzZWN1cmVcLnRhbmdlcmluZVwuY2EiO2k6ODU7czoyMjoiYm1vXC5jb20vb25saW5lYmFua2luZyI7aTo4NjtzOjQxOiJwbV9mcD12ZXJzaW9uJnN0YXRlPTEmc2F2ZUZCQz0mRkJDX051bWJlciI7aTo4NztzOjIxOiJjaWJjb25saW5lXC5jaWJjXC5jb20iO2k6ODg7czozMToiaHR0cHM6Ly93d3dcLnRkY2FuYWRhdHJ1c3RcLmNvbSI7aTo4OTtzOjI2OiJWaXNpdGVkIFREIEJBTks6XHMqIlwuXCRpcCI7aTo5MDtzOjYyOiJ3aW5kb3dcLmxvY2F0aW9uPSJpbmRleFwuaHRtbFw/Y21kPWxvZ2luPXVzbWFpbD1jaGVjaz12YWxpZGF0ZSI7aTo5MTtzOjIwOiI8VElUTEU+QU9MXHMqQmlsbGluZyI7aTo5MjtzOjMzOiI8dGl0bGU+RG93bmxvYWQgWW91ciBGaWxlPC90aXRsZT4iO2k6OTM7czoyMToiPHRpdGxlPkFjZXNzXHMrUGF5UGFsIjtpOjk0O3M6NDg6IndpbmRvd1wubG9jYXRpb25ccyo9XHMqJ2h0dHBzOi8vd3d3XC5wYXlwYWxcLmNvbSI7aTo5NTtzOjIwOiJzeXN0ZW0vc2VuZF9jY3ZcLnBocCI7aTo5NjtzOjI2OiJcLnBocFw/d2Vic3JjPSJccypcLlxzKm1kNSI7aTo5NztzOjMzOiI8dGl0bGU+TG9nZ2luZ1xzK2luXHMrLVxzKyZSaG87YXkiO2k6OTg7czo0MToiXCRibG9ja2VkX3dvcmRzXHMqPVxzKmFycmF5XChccypbJyJdZHJ3ZWIiO2k6OTk7czozMjoiQWxsXHMqZm9yXHMqeW91XHMqIVxzKkdvb2RccypCb3kiO2k6MTAwO3M6MzM6IllvdXIgc2VjdXJpdHkgaXMgb3VyIHRvcCBwcmlvcml0eSI7aToxMDE7czoxMzoidGFuZ2VyaW5lXC5jYSI7fQ=="));
$g_JSVirSig = unserialize(base64_decode("YTozMjE6e2k6MDtzOjk1OiI8c2NyaXB0PnZhciBcdz0nJztccypzZXRUaW1lb3V0XChcZCtcKTsuKz9kZWZhdWx0X2tleS4rP3NlX3JlLis/ZGVmYXVsdF9rZXkuKz9mX3VybC4rPzwvc2NyaXB0PiI7aToxO3M6MTE0OiI8c2NyaXB0W14+XSs+dmFyIGE9Lis/U3RyaW5nXC5mcm9tQ2hhckNvZGVcKGFcLmNoYXJDb2RlQXRcKGlcKVxeMlwpfWM9dW5lc2NhcGVcKGJcKTtkb2N1bWVudFwud3JpdGVcKGNcKTs8L3NjcmlwdD4iO2k6MjtzOjI1NToidmFyIFx3ezEsMjB9PVxbIlxkKyIsLis/IlxkKyJcXTtmdW5jdGlvbiBcdytcKFx3K1wpe3ZhciBcdys9ZG9jdW1lbnRcW1x3K1woXHcrXFtcZCtcXVwpXF1cKFx3K1woXHcrXFtcZCtcXVwpXCtcdytcKFx3K1xbXGQrXF1cKS4rP1N0cmluZ1wuZnJvbUNoYXJDb2RlXChcdytcLnNsaWNlXChcdyssLis/ZWxzZSBcdytcK1wrO31yZXR1cm5cKFx3K1wpO30oZnVuY3Rpb24gXHcrXChcdytcKXtyZXR1cm4gXHcrXChcdytcKFx3K1wpLCdcdysnXCk7fSk/IjtpOjM7czoyODQ6ImZ1bmN0aW9uXHNcdytcKFx3Kyxcc1x3K1wpXHN7dmFyXHNcdys9Jyc7dmFyXHNcdys9MDt2YXJcc1x3Kz0wO2ZvclwoXHcrPTA7XHcrPFx3K1wubGVuZ3RoO1x3K1wrXCtcKXt2YXJcc1x3Kz1cdytcLmNoYXJBdFwoXHcrXCk7dmFyXHNcdys9XHcrXC5jaGFyQ29kZUF0XCgwXClcXlx3K1wuY2hhckNvZGVBdFwoXHcrXCk7XHcrPVN0cmluZ1wuZnJvbUNoYXJDb2RlXChcdytcKTtcdytcKz1cdys7aWZcKFx3Kz09XHcrXC5sZW5ndGgtMVwpXHcrPTA7ZWxzZVxzXHcrXCtcKzt9cmV0dXJuXChcdytcKTt9IjtpOjQ7czoxMTg6Ii9cKlx3ezMyfVwqL1xzKnZhclxzK18weFx3Kz1cWy4rP11dPWZ1bmN0aW9uXChcKXtmdW5jdGlvbi4rP1wpfWVsc2Uge3JldHVybiBmYWxzZX07cmV0dXJuIF8weC4rP1wpO307fTtccyovXCpcd3szMn1cKi8iO2k6NTtzOjQ5OiIvXCpcd3szMn1cKi9ccyo7XHMqd2luZG93XFsiXHhcZHsyfS4qL1wqXHd7MzJ9XCovIjtpOjY7czo5MjoiL1wqXHd7MzJ9XCovO1woZnVuY3Rpb25cKFwpe3ZhclxzKlx3Kz0iIjt2YXJccypcdys9Ilx3KyI7Zm9yLis/XClcKTt9XClcKFwpOy9cKlx3ezMyfVwqL1xzKiQiO2k6NztzOjI3MzoiPHNjcmlwdD5mdW5jdGlvbiBcdytcKFx3K1wpe3ZhciBcdys9XGQrLFx3Kz1cZCs7dmFyIFx3Kz0nXGQrLVxkKyxcZCstXGQrLis/ZnVuY3Rpb24gXHcrXChcdytcKXtccyp3aW5kb3dcLmV2YWxcKFwpO1xzKn0uKz88c2NyaXB0PmZ1bmN0aW9uIFx3K1woXCl7aWYgXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFwuaW5kZXhPZlwoIk1TSUUuKz9mcm9tQ2hhckNvZGVcKFxzKlwoXHcrXC5jaGFyQ29kZUF0XChcdytcK1xkK1wpLVxkK1wpXHMqXF5ccypcdytcKVxzKlwpO319PC9zY3JpcHQ+IjtpOjg7czoyMzE6IlwoZnVuY3Rpb25cKFwpe3ZhclxzLj0iXChcdytcKFx3K1wpZlwuXHcrLD1cdytcL1wpXHcrXCknXHcrXC9cKVx3K1wpJy5cKVx3K1woJz1cKVx3K1wvXFs7XChcdytcP1whMT1cdysnXClcXVx3K1woXHcrPVx3Kyw9XHcrXC5cdysnPVx3K1woXHcrXChcdytcK1wpXHcrJy49XHcrXCtcdytcISdcdyshXHcrJlx3K1woXHcrXCktXHtcdytcKFx3K1woXHcrXC5cdytcLi4rP2V2YWxcKFx3K1wpO1x9XChcKVwpOyI7aTo5O3M6MTQ6InY9MDt2eD1bJyJdQ29kIjtpOjEwO3M6MjM6IkF0WyciXVxdXCh2XCtcK1wpLTFcKVwpIjtpOjExO3M6MzI6IkNsaWNrVW5kZXJjb29raWVccyo9XHMqR2V0Q29va2llIjtpOjEyO3M6NzA6InVzZXJBZ2VudFx8cHBcfGh0dHBcfGRhemFseXpbJyJdezAsMX1cLnNwbGl0XChbJyJdezAsMX1cfFsnIl17MCwxfVwpLDAiO2k6MTM7czoyMjoiXC5wcm90b3R5cGVcLmF9Y2F0Y2hcKCI7aToxNDtzOjM3OiJ0cnl7Qm9vbGVhblwoXClcLnByb3RvdHlwZVwucX1jYXRjaFwoIjtpOjE1O3M6ODY6ImluZGV4T2ZcfGlmXHxyY1x8bGVuZ3RoXHxtc25cfHlhaG9vXHxyZWZlcnJlclx8YWx0YXZpc3RhXHxvZ29cfGJpXHxocFx8dmFyXHxhb2xcfHF1ZXJ5IjtpOjE2O3M6NjA6IkFycmF5XC5wcm90b3R5cGVcLnNsaWNlXC5jYWxsXChhcmd1bWVudHNcKVwuam9pblwoWyciXVsnIl1cKSI7aToxNztzOjgyOiJxPWRvY3VtZW50XC5jcmVhdGVFbGVtZW50XCgiZCJcKyJpIlwrInYiXCk7cVwuYXBwZW5kQ2hpbGRcKHFcKyIiXCk7fWNhdGNoXChxd1wpe2g9IjtpOjE4O3M6Nzk6Ilwreno7c3M9XFtcXTtmPSdmcidcKydvbSdcKydDaCc7ZlwrPSdhckMnO2ZcKz0nb2RlJzt3PXRoaXM7ZT13XFtmXFsic3Vic3RyIlxdXCgiO2k6MTk7czoxMTU6InM1XChxNVwpe3JldHVybiBcK1wrcTU7fWZ1bmN0aW9uIHlmXChzZix3ZVwpe3JldHVybiBzZlwuc3Vic3RyXCh3ZSwxXCk7fWZ1bmN0aW9uIHkxXCh3Ylwpe2lmXCh3Yj09MTY4XCl3Yj0xMDI1O2Vsc2UiO2k6MjA7czo2NDoiaWZcKG5hdmlnYXRvclwudXNlckFnZW50XC5tYXRjaFwoL1woYW5kcm9pZFx8bWlkcFx8ajJtZVx8c3ltYmlhbiI7aToyMTtzOjEwNjoiZG9jdW1lbnRcLndyaXRlXCgnPHNjcmlwdCBsYW5ndWFnZT0iSmF2YVNjcmlwdCIgdHlwZT0idGV4dC9qYXZhc2NyaXB0IiBzcmM9IidcK2RvbWFpblwrJyI+PC9zY3InXCsnaXB0PidcKSI7aToyMjtzOjMwOiJodHRwOi8vXHcrXC5ydS9fL2dvXC5waHBcP3NpZD0iO2k6MjM7czo2NjoiPW5hdmlnYXRvclxbYXBwVmVyc2lvbl92YXJcXVwuaW5kZXhPZlwoIk1TSUUiXCkhPS0xXD8nPGlmcmFtZSBuYW1lIjtpOjI0O3M6MjI6IiJmciJcKyJvbUMiXCsiaGFyQ29kZSIiO2k6MjU7czoxMToiPSJldiJcKyJhbCIiO2k6MjY7czo3ODoiXFtcKFwoZVwpXD8icyI6IiJcKVwrInAiXCsibGl0IlxdXCgiYVwkIlxbXChcKGVcKVw/InN1IjoiIlwpXCsiYnN0ciJcXVwoMVwpXCk7IjtpOjI3O3M6Mzk6ImY9J2ZyJ1wrJ29tJ1wrJ0NoJztmXCs9J2FyQyc7ZlwrPSdvZGUnOyI7aToyODtzOjIwOiJmXCs9XChoXClcPydvZGUnOiIiOyI7aToyOTtzOjQxOiJmPSdmJ1wrJ3InXCsnbydcKydtJ1wrJ0NoJ1wrJ2FyQydcKydvZGUnOyI7aTozMDtzOjUwOiJmPSdmcm9tQ2gnO2ZcKz0nYXJDJztmXCs9J3Fnb2RlJ1xbInN1YnN0ciJcXVwoMlwpOyI7aTozMTtzOjE2OiJ2YXJccytkaXZfY29sb3JzIjtpOjMyO3M6MjA6IkNvcmVMaWJyYXJpZXNIYW5kbGVyIjtpOjMzO3M6Nzk6In1ccyplbHNlXHMqe1xzKmRvY3VtZW50XC53cml0ZVxzKlwoXHMqWyciXXswLDF9XC5bJyJdezAsMX1cKVxzKn1ccyp9XHMqUlwoXHMqXCkiO2k6MzQ7czoxODoiXC5iaXRjb2lucGx1c1wuY29tIjtpOjM1O3M6NDE6Ilwuc3BsaXRcKCImJiJcKTtoPTI7cz0iIjtpZlwobVwpZm9yXChpPTA7IjtpOjM2O3M6NDU6IjNCZm9yXHxmcm9tQ2hhckNvZGVcfDJDMjdcfDNEXHwyQzg4XHx1bmVzY2FwZSI7aTozNztzOjU4OiI7XHMqZG9jdW1lbnRcLndyaXRlXChbJyJdezAsMX08aWZyYW1lXHMqc3JjPSJodHRwOi8veWFcLnJ1IjtpOjM4O3M6MTEwOiJpZlwoIWdcKFwpJiZ3aW5kb3dcLm5hdmlnYXRvclwuY29va2llRW5hYmxlZFwpe2RvY3VtZW50XC5jb29raWU9IjE9MTtleHBpcmVzPSJcK2VcLnRvR01UU3RyaW5nXChcKVwrIjtwYXRoPS8iOyI7aTozOTtzOjcwOiJubl9wYXJhbV9wcmVsb2FkZXJfY29udGFpbmVyXHw1MDAxXHxoaWRkZW5cfGlubmVySFRNTFx8aW5qZWN0XHx2aXNpYmxlIjtpOjQwO3M6Mjk6IjwhLS1bYS16QS1aMC05X10rXHxcfHN0YXQgLS0+IjtpOjQxO3M6ODU6IiZwYXJhbWV0ZXI9XCRrZXl3b3JkJnNlPVwkc2UmdXI9MSZIVFRQX1JFRkVSRVI9J1wrZW5jb2RlVVJJQ29tcG9uZW50XChkb2N1bWVudFwuVVJMXCkiO2k6NDI7czo0ODoid2luZG93c1x8c2VyaWVzXHw2MFx8c3ltYm9zXHxjZVx8bW9iaWxlXHxzeW1iaWFuIjtpOjQzO3M6MzU6IlxbWyciXWV2YWxbJyJdXF1cKHNcKTt9fX19PC9zY3JpcHQ+IjtpOjQ0O3M6NTk6ImtDNzBGTWJseUprRldab2RDS2wxV1lPZFdZVWxuUXpSbmJsMVdac1ZFZGxkbUwwNVdadFYzWXZSR0k5IjtpOjQ1O3M6NTU6IntrPWk7cz1zXC5jb25jYXRcKHNzXChldmFsXChhc3FcKFwpXCktMVwpXCk7fXo9cztldmFsXCgiO2k6NDY7czoxMjM6ImRvY3VtZW50XC5jb29raWVcLm1hdGNoXChuZXdccytSZWdFeHBcKFxzKiJcKFw/OlxeXHw7IFwpIlxzKlwrXHMqbmFtZVwucmVwbGFjZVwoL1woXFtcXC5cJFw/XCpcfHt9XFwoXFwpXFxbXFxdXC9cXCtcXlxdXCkvZyI7aTo0NztzOjg2OiJzZXRDb29raWVccypcKCpccyoiYXJ4X3R0IlxzKixccyoxXHMqLFxzKmR0XC50b0dNVFN0cmluZ1woXClccyosXHMqWyciXXswLDF9L1snIl17MCwxfSI7aTo0ODtzOjEzNzoiZG9jdW1lbnRcLmNvb2tpZVwubWF0Y2hccypcKFxzKm5ld1xzK1JlZ0V4cFxzKlwoXHMqIlwoXD86XF5cfDtccypcKSJccypcK1xzKm5hbWVcLnJlcGxhY2VccypcKC9cKFxbXFwuXCRcP1wqXHx7fVxcKFxcKVxcW1xcXVwvXFwrXF5cXVwpL2ciO2k6NDk7czo5ODoidmFyXHMrZHRccys9XHMrbmV3XHMrRGF0ZVwoXCksXHMrZXhwaXJ5VGltZVxzKz1ccytkdFwuc2V0VGltZVwoXHMrZHRcLmdldFRpbWVcKFwpXHMrXCtccys5MDAwMDAwMDAiO2k6NTA7czoxMDU6ImlmXHMqXChccypudW1ccyo9PT1ccyowXHMqXClccyp7XHMqcmV0dXJuXHMqMTtccyp9XHMqZWxzZVxzKntccypyZXR1cm5ccytudW1ccypcKlxzKnJGYWN0XChccypudW1ccyotXHMqMSI7aTo1MTtzOjQxOiJcKz1TdHJpbmdcLmZyb21DaGFyQ29kZVwocGFyc2VJbnRcKDBcKyd4JyI7aTo1MjtzOjgzOiI8c2NyaXB0XHMrbGFuZ3VhZ2U9IkphdmFTY3JpcHQiPlxzKnBhcmVudFwud2luZG93XC5vcGVuZXJcLmxvY2F0aW9uPSJodHRwOi8vdmtcLmNvbSI7aTo1MztzOjQ0OiJsb2NhdGlvblwucmVwbGFjZVwoWyciXXswLDF9aHR0cDovL3Y1azQ1XC5ydSI7aTo1NDtzOjEyOToiO3RyeXtcK1wrZG9jdW1lbnRcLmJvZHl9Y2F0Y2hcKHFcKXthYT1mdW5jdGlvblwoZmZcKXtmb3JcKGk9MDtpPHpcLmxlbmd0aDtpXCtcK1wpe3phXCs9U3RyaW5nXFtmZlxdXChlXCh2XCtcKHpcW2lcXVwpXCktMTJcKTt9fTt9IjtpOjU1O3M6MTQyOiJkb2N1bWVudFwud3JpdGVccypcKFsnIl17MCwxfTxbJyJdezAsMX1ccypcK1xzKnhcWzBcXVxzKlwrXHMqWyciXXswLDF9IFsnIl17MCwxfVxzKlwrXHMqeFxbNFxdXHMqXCtccypbJyJdezAsMX0+XC5bJyJdezAsMX1ccypcK3hccypcWzJcXVxzKlwrIjtpOjU2O3M6NjA6ImlmXCh0XC5sZW5ndGg9PTJcKXt6XCs9U3RyaW5nXC5mcm9tQ2hhckNvZGVcKHBhcnNlSW50XCh0XClcKyI7aTo1NztzOjc0OiJ3aW5kb3dcLm9ubG9hZFxzKj1ccypmdW5jdGlvblwoXClccyp7XHMqaWZccypcKGRvY3VtZW50XC5jb29raWVcLmluZGV4T2ZcKCI7aTo1ODtzOjk3OiJcLnN0eWxlXC5oZWlnaHRccyo9XHMqWyciXXswLDF9MHB4WyciXXswLDF9O3dpbmRvd1wub25sb2FkXHMqPVxzKmZ1bmN0aW9uXChcKVxzKntkb2N1bWVudFwuY29va2llIjtpOjU5O3M6MTIyOiJcLnNyYz1cKFsnIl17MCwxfWh0cHM6WyciXXswLDF9PT1kb2N1bWVudFwubG9jYXRpb25cLnByb3RvY29sXD9bJyJdezAsMX1odHRwczovL3NzbFsnIl17MCwxfTpbJyJdezAsMX1odHRwOi8vWyciXXswLDF9XClcKyI7aTo2MDtzOjMwOiI0MDRcLnBocFsnIl17MCwxfT5ccyo8L3NjcmlwdD4iO2k6NjE7czo3NjoicHJlZ19tYXRjaFwoWyciXXswLDF9L3NhcGUvaVsnIl17MCwxfVxzKixccypcJF9TRVJWRVJcW1snIl17MCwxfUhUVFBfUkVGRVJFUiI7aTo2MjtzOjc0OiJkaXZcLmlubmVySFRNTFxzKlwrPVxzKlsnIl17MCwxfTxlbWJlZFxzK2lkPSJkdW1teTIiXHMrbmFtZT0iZHVtbXkyIlxzK3NyYyI7aTo2MztzOjczOiJzZXRUaW1lb3V0XChbJyJdezAsMX1hZGROZXdPYmplY3RcKFwpWyciXXswLDF9LFxkK1wpO319fTthZGROZXdPYmplY3RcKFwpIjtpOjY0O3M6NTE6IlwoYj1kb2N1bWVudFwpXC5oZWFkXC5hcHBlbmRDaGlsZFwoYlwuY3JlYXRlRWxlbWVudCI7aTo2NTtzOjE5OiJcJDpcKHt9XCsiIlwpXFtcJFxdIjtpOjY2O3M6NDk6IjwvaWZyYW1lPlsnIl1cKTtccyp2YXJccytqPW5ld1xzK0RhdGVcKG5ld1xzK0RhdGUiO2k6Njc7czo1Mzoie3Bvc2l0aW9uOmFic29sdXRlO3RvcDotOTk5OXB4O308L3N0eWxlPjxkaXZccytjbGFzcz0iO2k6Njg7czoxMjg6ImlmXHMqXChcKHVhXC5pbmRleE9mXChbJyJdezAsMX1jaHJvbWVbJyJdezAsMX1cKVxzKj09XHMqLTFccyomJlxzKnVhXC5pbmRleE9mXCgid2luIlwpXHMqIT1ccyotMVwpXHMqJiZccypuYXZpZ2F0b3JcLmphdmFFbmFibGVkIjtpOjY5O3M6NTg6InBhcmVudFwud2luZG93XC5vcGVuZXJcLmxvY2F0aW9uPVsnIl17MCwxfWh0dHA6Ly92a1wuY29tXC4iO2k6NzA7czo2ODoiamF2YXNjcmlwdFx8aGVhZFx8dG9Mb3dlckNhc2VcfGNocm9tZVx8d2luXHxqYXZhRW5hYmxlZFx8YXBwZW5kQ2hpbGQiO2k6NzE7czoyMToibG9hZFBOR0RhdGFcKHN0ckZpbGUsIjtpOjcyO3M6MjM6Ii8vXHMqU29tZVwuZGV2aWNlc1wuYXJlIjtpOjczO3M6MTA1OiJjaGVja191c2VyX2FnZW50PVxbXHMqWyciXXswLDF9THVuYXNjYXBlWyciXXswLDF9XHMqLFxzKlsnIl17MCwxfWlQaG9uZVsnIl17MCwxfVxzKixccypbJyJdezAsMX1NYWNpbnRvc2giO2k6NzQ7czoxNTM6ImRvY3VtZW50XC53cml0ZVwoWyciXXswLDF9PFsnIl17MCwxfVwrWyciXXswLDF9aVsnIl17MCwxfVwrWyciXXswLDF9ZlsnIl17MCwxfVwrWyciXXswLDF9clsnIl17MCwxfVwrWyciXXswLDF9YVsnIl17MCwxfVwrWyciXXswLDF9bVsnIl17MCwxfVwrWyciXXswLDF9ZSI7aTo3NTtzOjQ4OiJzdHJpcG9zXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFxzKixccypsaXN0X2RhdGFcW2kiO2k6NzY7czoyNjoiaWZccypcKCFzZWVfdXNlcl9hZ2VudFwoXCkiO2k6Nzc7czo3MDoiPHNjcmlwdFxzKnR5cGU9WyciXXswLDF9dGV4dC9qYXZhc2NyaXB0WyciXXswLDF9XHMqc3JjPVsnIl17MCwxfWZ0cDovLyI7aTo3ODtzOjQ4OiJpZlxzKlwoZG9jdW1lbnRcLmNvb2tpZVwuaW5kZXhPZlwoWyciXXswLDF9c2FicmkiO2k6Nzk7czoxMTQ6IlwpO1xzKmlmXChccypbYS16QS1aMC05X10rXC50ZXN0XChccypkb2N1bWVudFwucmVmZXJyZXJccypcKVxzKiYmXHMqW2EtekEtWjAtOV9dK1wpXHMqe1xzKmRvY3VtZW50XC5sb2NhdGlvblwuaHJlZiI7aTo4MDtzOjUyOiJpZlwoL0FuZHJvaWQvaVxbXzB4W2EtekEtWjAtOV9dK1xbXGQrXF1cXVwobmF2aWdhdG9yIjtpOjgxO3M6Njk6ImZ1bmN0aW9uXChhXCl7aWZcKGEmJlsnIl1kYXRhWyciXWluXGQrYSYmYVwuZGF0YVwuYVxkKyYmYVwuZGF0YVwuYVxkKyI7aTo4MjtzOjk4OiI8XGQrXHMrXGQrPVsnIl1cZCsvXGQrXFsnIl1cK1xbJyJdLlxbJyJdXCtcWyciXS5bJyJdXHMrLj1bJyJdLjovL1xkK1xbJyJdXCtcWyciXS5cLlxkK1xbJyJdXCtcWyciXSI7aTo4MztzOjk4OiI9ZG9jdW1lbnRcLnJlZmVycmVyO2lmXChSZWZcLmluZGV4T2ZcKFsnIl1cLmdvb2dsZVwuWyciXVwpIT0tMVx8XHxSZWZcLmluZGV4T2ZcKFsnIl1cLmJpbmdcLlsnIl1cKSI7aTo4NDtzOjIwOiJ2aXNpdG9yVHJhY2tlcl9pc01vYiI7aTo4NTtzOjQwOiIvXCpcd3szMn1cKi92YXJccytfMHhbYS16QS1aMC05X10rPVxbIlx4IjtpOjg2O3M6NzE6Ii9cKlx3ezMyfVwqLzt3aW5kb3dcW1snIl1kb2N1bWVudFsnIl1cXVxbWyciXVthLXpBLVowLTlfXStbJyJdXF09XFtbJyJdIjtpOjg3O3M6NDY6IlxdXF1cLmpvaW5cKFxbJyJdXFsnIl1cKTtbJyJdXClcKTsvXCpcd3szMn1cKi8iO2k6ODg7czoxMzQ6Ijt2YXJccytbYS16QS1aMC05X10rPVthLXpBLVowLTlfXStcLmNoYXJDb2RlQXRcKFxkK1wpXF5bYS16QS1aMC05X10rXC5jaGFyQ29kZUF0XChbYS16QS1aMC05X10rXCk7W2EtekEtWjAtOV9dKz1TdHJpbmdcLmZyb21DaGFyQ29kZVwoIjtpOjg5O3M6Mzg6ImV2YWxcKGV2YWxcKFsnIl1TdHJpbmdcLmZyb21DaGFyQ29kZVwoIjtpOjkwO3M6MTAwOiJjbGVuO2lcK1wrXCl7YlwrPVN0cmluZ1wuZnJvbUNoYXJDb2RlXChhXC5jaGFyQ29kZUF0XChpXClcXjJcKX1jPXVuZXNjYXBlXChiXCk7ZG9jdW1lbnRcLndyaXRlXChjXCk7IjtpOjkxO3M6Nzg6IjxzY3JpcHRccyp0eXBlPVsnIl17MCwxfXRleHQvamF2YXNjcmlwdFsnIl17MCwxfVxzKnNyYz1bJyJdezAsMX1odHRwOi8vZ29vXC5nbCI7aTo5MjtzOjM0OiJcLmluZGV4T2ZcKFxzKlsnIl1JQnJvd3NlWyciXVxzKlwpIjtpOjkzO3M6ODU6Ij1kb2N1bWVudFwucmVmZXJyZXI7XHMqW2EtekEtWjAtOV9dKz11bmVzY2FwZVwoXHMqW2EtekEtWjAtOV9dK1xzKlwpO1xzKnZhclxzK0V4cERhdGUiO2k6OTQ7czo3MToid2hpbGVcKFxzKmY8XGQrXHMqXClkb2N1bWVudFxbXHMqW2EtekEtWjAtOV9dK1wrWyciXXRlWyciXVxzKlxdXChTdHJpbmciO2k6OTU7czoyOToiXF1cKFxzKnZcK1wrXHMqXCktMVxzKlwpXHMqXCkiO2k6OTY7czo0OToiZG9jdW1lbnRcLndyaXRlXChccypTdHJpbmdcLmZyb21DaGFyQ29kZVwuYXBwbHlcKCI7aTo5NztzOjUwOiJbJyJdXF1cKFthLXpBLVowLTlfXStcK1wrXCktXGQrXCl9XChGdW5jdGlvblwoWyciXSI7aTo5ODtzOjY0OiI7d2hpbGVcKFthLXpBLVowLTlfXSs8XGQrXClkb2N1bWVudFxbLis/XF1cKFN0cmluZ1xbWyciXWZyb21DaGFyIjtpOjk5O3M6MTA4OiJpZlxzKlwoW2EtekEtWjAtOV9dK1wuaW5kZXhPZlwoZG9jdW1lbnRcLnJlZmVycmVyXC5zcGxpdFwoWyciXS9bJyJdXClcW1snIl0yWyciXVxdXClccyohPVxzKlsnIl0tMVsnIl1cKVxzKnsiO2k6MTAwO3M6Mzg6InByZWdfbWF0Y2hcKFsnIl1AXCh5YW5kZXhcfGdvb2dsZVx8Ym90IjtpOjEwMTtzOjY0OiJTdHJpbmdcLmZyb21DaGFyQ29kZVwoXHMqW2EtekEtWjAtOV9dK1wuY2hhckNvZGVBdFwoaVwpXHMqXF5ccyoyIjtpOjEwMjtzOjU3OiJcW1snIl1jaGFyWyciXVxzKlwrXHMqW2EtekEtWjAtOV9dK1xzKlwrXHMqWyciXUF0WyciXVxdXCgiO2k6MTAzO3M6NDk6InNyYz1bJyJdLy9bJyJdXHMqXCtccypTdHJpbmdcLmZyb21DaGFyQ29kZVwuYXBwbHkiO2k6MTA0O3M6NTU6IlN0cmluZ1xbXHMqWyciXWZyb21DaGFyWyciXVxzKlwrXHMqW2EtekEtWjAtOV9dK1xzKlxdXCgiO2k6MTA1O3M6Mjg6Ii49WyciXS46Ly8uXC4uXC4uXC4uLy5cLi5cLi4iO2k6MTA2O3M6Mzk6Ijwvc2NyaXB0PlsnIl1cKTtccyovXCovW2EtekEtWjAtOV9dK1wqLyI7aToxMDc7czo3MzoiZG9jdW1lbnRcW18weFxkK1xbXGQrXF1cXVwoXzB4XGQrXFtcZCtcXVwrXzB4XGQrXFtcZCtcXVwrXzB4XGQrXFtcZCtcXVwpOyI7aToxMDg7czo5OiImYWR1bHQ9MSYiO2k6MTA5O3M6OTc6ImRvY3VtZW50XC5yZWFkeVN0YXRlXHMrPT1ccytbJyJdY29tcGxldGVbJyJdXClccyp7XHMqY2xlYXJJbnRlcnZhbFwoW2EtekEtWjAtOV9dK1wpO1xzKnNcLnNyY1xzKj0iO2k6MTEwO3M6MTk6Ii46Ly8uXC4uXC4uLy5cLi5cPy8iO2k6MTExO3M6MjI6InNyYz0iZmlsZXNfc2l0ZS9qc1wuanMiO2k6MTEyO3M6OTQ6IndpbmRvd1wucG9zdE1lc3NhZ2VcKHtccyp6b3JzeXN0ZW06XHMqMSxccyp0eXBlOlxzKlsnIl11cGRhdGVbJyJdLFxzKnBhcmFtczpccyp7XHMqWyciXXVybFsnIl0iO2k6MTEzO3M6OTg6IlwuYXR0YWNoRXZlbnRcKFsnIl1vbmxvYWRbJyJdLGFcKTpbYS16QS1aMC05X10rXC5hZGRFdmVudExpc3RlbmVyXChbJyJdbG9hZFsnIl0sYSwhMVwpO2xvYWRNYXRjaGVyIjtpOjExNDtzOjc4OiJpZlwoXChhPWVcLmdldEVsZW1lbnRzQnlUYWdOYW1lXChbJyJdYVsnIl1cKVwpJiZhXFswXF0mJmFcWzBcXVwuaHJlZlwpZm9yXCh2YXIiO2k6MTE1O3M6ODE6IjtccyplbGVtZW50XC5pbm5lckhUTUxccyo9XHMqWyciXTxpZnJhbWVccytzcmM9WyciXVxzKlwrXHMqeGhyXC5yZXNwb25zZVRleHRccypcKyI7aToxMTY7czoxOToiWEhGRVIxXHMqPVxzKlhIRkVSMSI7aToxMTc7czo3ODoiZG9jdW1lbnRcLndyaXRlXHMqXChccypbJyJdezAsMX08c2NyaXB0XHMrc3JjPVsnIl17MCwxfWh0dHA6Ly88XD89XCRkb21haW5cPz4vIjtpOjExODtzOjU1OiJ3aW5kb3dcLmxvY2F0aW9uXHMqPVxzKlsnIl1odHRwOi8vXGQrXC5cZCtcLlxkK1wuXGQrL1w/IjtpOjExOTtzOjY2OiJzZXRUaW1lb3V0XChmdW5jdGlvblwoXCl7dmFyXHMrcGF0dGVyblxzKj1ccypuZXdccypSZWdFeHBcKC9nb29nbGUiO2k6MTIwO3M6NjY6IndvPVsnIl1cKyEhXChbJyJdb250b3VjaHN0YXJ0WyciXVxzK2luXHMrd2luZG93XClcK1snIl0mc3Q9MSZ0aXRsZSI7aToxMjE7czozNzoiaWZcKGEmJlsnIl1kYXRhWyciXWluXHMqYSYmYVwuZGF0YVwuYSI7aToxMjI7czo4NjoiZG9jdW1lbnRcW1thLXpBLVowLTlfXStcKFthLXpBLVowLTlfXStcW1xkK1xdXClcXVwoW2EtekEtWjAtOV9dK1woW2EtekEtWjAtOV9dK1xbXGQrXF0iO2k6MTIzO3M6NTM6Ii5cLi5cKFxbJyJdPC4gLj1bJyJdLi8uXFsnIl1cK1xbJyJdLlxbJyJdXCtcWyciXS5bJyJdIjtpOjEyNDtzOjI1OiJcKFwpfX0sXGQrXCk7L1wqXHd7MzJ9XCovIjtpOjEyNTtzOjQ5OiJldmFsWyciXVwuc3BsaXRcKFsnIl1cfFsnIl1cKSwwLHt9XClcKTtccyp9XChcKVwpIjtpOjEyNjtzOjc5OiJpZlwoaXNcdytcKVxzKntccyp3aW5kb3dcLmxvY2F0aW9uXHMqPVxzKidodHRwOi8vXGQrXC5cZCtcLlxkK1wuXGQrL1w/XHcrJztccyp9IjtpOjEyNztzOjQxOiJldmFsXChTdHJpbmdcLmZyb21DaGFyQ29kZVwoW1xkKyxcc10rXClcKSI7aToxMjg7czo3Nzoid2luZG93XC50blNldFBhcmFtc1woXGQrXHMqLFxzKnsoIlx3KyI6XGQrLCl7Myx9Lis/O3dpbmRvd1wudG5Mb2FkQmxvY2tzXChcKTsiO2k6MTI5O3M6ODU6Ii5cLmdldFNob3dlZFRlYXNlcnNDb3VudFwoLlwuc2l0ZUlkLFxkK1wpXCsxfSxiPWZ1bmN0aW9uXChcKXsuXC5zZXRTaG93ZWRUZWFzZXJzQ291bnQiO2k6MTMwO3M6MTE1OiI8Ym9keT5ccyo8ZGl2IGlkPSJ0YmxvY2siPlxzKjxjZW50ZXI+XHMqPC9jZW50ZXI+XHMqPC9kaXY+XHMqPHNjcmlwdD5zZXRUaW1lb3V0XCgiaW5pdFwoXCkiLDBcKTs8L3NjcmlwdD5ccyo8L2JvZHk+IjtpOjEzMTtzOjExMzoiPCFcW0NEQVRBXFtccyp3aW5kb3dcLmFcZCtccyo9XHMqXGQrOyFmdW5jdGlvblwoXCl7dmFyXHMrXHcrPUpTT05cLnBhcnNlXCgnXFsiXHcrIiwiXHcrIiwiXHcrIiwiXHcrIlxdJ1wpLHQ9IlxkKyIiO2k6MTMyO3M6MTEwOiIvaVxbXHcrXFtcZCtcXVxdXChcdytcW1x3K1xbXGQrXF1cXVwoXGQrLFxkK1wpXClcPyFcZCs6IVxkKzt9XHcrXChcKT09PSFcZCsmJlwoXHcrXFtcdytcW1xkK1xdXF09XHcrXFtcZCtcXVwpOyI7aToxMzM7czoyMTI6Ijp1bmRlZmluZWR9cmVzdWx0PWNoZWNrX29zXChcKTtjb29rPW51bGw7Y29vaz1nZXRDb29raWVcKF8weFx3K1xbXGQrXF1cKTtpZlwoY29vaz09bnVsbFx8XHxjb29rPT1fMHhcdytcW1xkK1xdXCl7aWZcKHJlc3VsdD09XzB4XHcrXFtcZCtcXVx8XHxyZXN1bHQ9PV8weFx3K1xbXGQrXF1cKXtpZlwocmVzdWx0PT1fMHhcdytcW1xkK1xdXCl7dmFyXHMrZGl2PWRvY3VtZW50IjtpOjEzNDtzOjM2OToiIWZ1bmN0aW9uXChcKXtmdW5jdGlvblxzK3RcKFwpe3JldHVybiEhbG9jYWxTdG9yYWdlXC5nZXRJdGVtXChhXCl9ZnVuY3Rpb25ccytlXChcKXtvXChcKSxccypwYXJlbnRcLnRvcFwud2luZG93XC5sb2NhdGlvblwuaHJlZj1jfWZ1bmN0aW9uXHMrb1woXCl7dmFyXHMrdD1yXCtpO2xvY2FsU3RvcmFnZVwuc2V0SXRlbVwoYSx0XCl9XHMqZnVuY3Rpb25ccytuXChcKXtpZlwodFwoXClcKXt2YXJccytvPWxvY2FsU3RvcmFnZVwuZ2V0SXRlbVwoYVwpO3I+byYmZVwoXCl9ZWxzZVxzK2VcKFwpfXZhclxzK2E9Ilx3KyIsXHMqcj1NYXRoXC5mbG9vclwoXChuZXcgRGF0ZVwpXC5nZXRUaW1lXChcKS9cdytcKSxjPSIuKz8iLGk9XGQrO25cKFwpfVwoXCk7IjtpOjEzNTtzOjUyOiI8c2NyaXB0XHMrc3JjPScvXD9cZCtfXGQrX1xkK19cZCs9MSdccyo+XHMqPC9zY3JpcHQ+IjtpOjEzNjtzOjEwNjoiaWZcKCFnZXRDb29raWVcKCIoXHhcdyspKyJcKVwpe3ZhclxzKlx3Kz0iKFx4XHcrKSsiO3ZhclxzKlx3K1xzKj1ccypuZXcgRGF0ZVwoXCksXHcrPVxzKm5ldyBEYXRlXChcKTtcdytcWyI7aToxMzc7czoxMjk6IndpbmRvd1wub25sb2FkPWZ1bmN0aW9uXChcKVxzKntccyp2YXJccytcdytccyo9XHMqbmV3XHMrUmVnRXhwXCgvW1wtXHcrXC5dK1x8Lis/L2lcKTtccyp2YXIgXHcrXHMqPVxzKlwobG9jYXRpb25cLmhyZWZcKVwucmVwbGFjZSI7aToxMzg7czoyNjI6ImZcKCF3aW5kb3dcLmFcdytcKXt2YXJccythXHcrPWZ1bmN0aW9uXChcJFwpe3JldHVyblxzK1wkXD9kb2N1bWVudFwuZ2V0RWxlbWVudEJ5SWRcKFwkXCk6ITF9LGFcdys9ZnVuY3Rpb25cKFwkXCl7cmV0dXJuXHMrXCRcP2RvY3VtZW50XC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lXChcJFwpOiExfSxhXHcrPWZ1bmN0aW9uXChcJFwpe3JldHVyblxzK1wkXD9kb2N1bWVudFwucXVlcnlTZWxlY3RvclwoXCRcKTohMX0sYVx3Kz1mdW5jdGlvblwoXCRcKXtyZXR1cm4iO2k6MTM5O3M6MTQ4OiJcKGZ1bmN0aW9uXChhLGJcKXtpZlwoLy4rPy9pXC50ZXN0XChhXC5zdWJzdHJcKDAsNFwpXClcKXdpbmRvd1wubG9jYXRpb249Yn1cKVwobmF2aWdhdG9yXC51c2VyQWdlbnRcfFx8bmF2aWdhdG9yXC52ZW5kb3JcfFx8d2luZG93XC5vcGVyYSwnW14nXSsnXCk7IjtpOjE0MDtzOjUzOiJpZlxzKlwoIVZpc2l0V2ViXC5DbGlja3VuZGVyXClccypWaXNpdFdlYlwuQ2xpY2t1bmRlciI7aToxNDE7czoxMTk6ImlmXCgvXChnb29nbGVcfHlhaG9vXHxiaW5nXHxhb2xcKS9pXC50ZXN0XChkb2N1bWVudFwucmVmZXJyZXJcKVwpe3dpbmRvd1wuc2V0VGltZW91dFwoZnVuY3Rpb25cKFwpe3RvcFwubG9jYXRpb25cLmhyZWY9IjtpOjE0MjtzOjUwOiJ0PXdpbmRvdyxuPSJ0ZWFzZXJuZXRfYmxvY2tpZCIsZT0idGVhc2VybmV0X3BhZGlkIiI7aToxNDM7czozMjk6InZhclxzK3NjcmlwdD1kb2N1bWVudFwuY3JlYXRlRWxlbWVudFwoJ3NjcmlwdCdcKTtzY3JpcHRcLnNyYz0nLis/JztiaW5kRXZlbnRcKGZ1bmN0aW9uXChcKXtpZlwoXChkb2N1bWVudFwuY29va2llXC5pbmRleE9mXCgnLis/J1wpPT0tMVwpJiZcKG5hdmlnYXRvclwudXNlckFnZW50XC5pbmRleE9mXCgnXHcrJ1wpIT0tMVwpJiZcKGRvY3VtZW50XC5sb2NhdGlvblwucGF0aG5hbWVcLmxlbmd0aD4xXCkmJlwoXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFwuaW5kZXhPZlwoJ1x3KydcKSE9LTFcKVx8XHxcKG5hdmlnYXRvclwudXNlckFnZW50XC5pbmRleE9mXCgnXHcrJ1wpIT0tMVwpIjtpOjE0NDtzOjgxOiJcd3sxLDIwfT1cZCs7ZG9jdW1lbnRcLndyaXRlXChcdytcWyJbZnJvbUNoYXJDb2RlXHgwLTlhLWZdKyJcXVwoXHcrXClcKTtjb250aW51ZTsiO2k6MTQ1O3M6OTg6ImlmXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFwubWF0Y2hcKC9cKChbXHcrXHNcLl0rXHw/KStcKS9pXCkhPT1udWxsXCl7d2luZG93XC5sb2NhdGlvblxzKj1ccyonLis/Jzt9IjtpOjE0NjtzOjE3MDoiXChmdW5jdGlvbi4rKCh0b1N0cmluZ3xuZXd8UmVnRXhwfFN0cmluZ3xldmFsfHZhcnxkb2N1bWVudHxzY3JpcHR8aW5zZXJ0QmVmb3JlfHNyY3xodHRwfGpzfGNyZWF0ZUVsZW1lbnR8Z2V0RWxlbWVudHNCeVRhZ05hbWV8cGFyZW50Tm9kZXxzcGxpdClcfCl7NSx9LitzcGxpdFwoJ1x8J1wpLDAse30iO2k6MTQ3O3M6ODA6IiJcXTtmdW5jdGlvbiBcdytcKFx3K1wpe3JldHVybiBcdytcKFx3K1woXHcrXCksLlx3Ky5cKTt9XHcrXChcdytcKFx3K1xbXGQrXVwpXCk7IjtpOjE0ODtzOjg1OiJuYXZpZ2F0b3JcW19cdytcW1xkK1xdXF1cfFx8bmF2aWdhdG9yXFtfXHcrXFtcZCtcXVxdXHxcfHdpbmRvd1xbX1x3K1xbXGQrXF1cXTtyZXR1cm4vIjtpOjE0OTtzOjE0MToicGFyZW50XC50b3BcLndpbmRvd1wubG9jYXRpb25cLmhyZWY9XHcrfWZ1bmN0aW9uIFx3K1woXCl7dmFyIFx3Kz1cdytcK1x3Kztsb2NhbFN0b3JhZ2VcLnNldEl0ZW1cKFx3KyxcdytcKX1ccypmdW5jdGlvbiBcdytcKFwpe2lmXChcdytcKFwpXCl7IjtpOjE1MDtzOjkyOiJuYXZpZ2F0b3JcW19cdytcW1xkK1xdXF1cfFx8bmF2aWdhdG9yXFtfXHcrXFtcZCtcXVxdXHxcfHdpbmRvd1xbX1x3K1xbXGQrXF1cXTtyZXR1cm4vYW5kcm9pZCI7aToxNTE7czozODoiL2pzL2pxdWVyeVwubWluXC5waHBcP2NfdXR0PVx3KyZjX3V0bT0iO2k6MTUyO3M6NTg6Ii9qcy9qcXVlcnlcLm1pblwucGhwXD9rZXk9XHcrJnV0bV9jYW1wYWlnbj1cdysmdXRtX3NvdXJjZT0iO2k6MTUzO3M6MTA2OiI8c2NyaXB0XHMrbGFuZ3VhZ2U9SmF2YVNjcmlwdFxzK2lkPXNjcmlwdERhdGFccyo+PC9zY3JpcHQ+XHMqPHNjcmlwdFxzK2xhbmd1YWdlPUphdmFTY3JpcHRccytzcmM9L21vZHVsZXMvIjtpOjE1NDtzOjE1NToiXC9cKlx3Lis/XC5qcy4rP1wqXC87XHMqXChccypmdW5jdGlvblwoXClccyp7dmFyXHNcd3s4fVw9Lis/Zm9yXHMqXChccyp2YXJccypcd3s4fVxzKj0uKz9TdHJpbmdcLmZyb21DaGFyQ29kZVwoJ1wrXHd7OH1cKydcKSdcKVwpXDtcfVwpXHMqXChcKVw7XC9cKi4rP1wqXC8iO2k6MTU1O3M6MTAwOiJlbFwuc3JjXHMqPVxzKiIvbWlzYy9qcXVlcnlcLmFuaW1hdGVcLmpzXD9fPS4rP3RyeVxzKntccypwXC5wYXJlbnROb2RlXC5pbnNlcnRCZWZvcmVcKGVsXHMqLFxzKnBcKTsgIjtpOjE1NjtzOjUxOiJDcmVhdGVPYmplY3RcKCJTaGVsbFwuQXBwbGljYXRpb24iXCkuKz9TaGVsbEV4ZWN1dGUiO2k6MTU3O3M6OTg6IjwhLS0oXHd7NSwzMn0pLS0+PHNjcmlwdFxzK3R5cGU9InRleHQvamF2YXNjcmlwdCJccytzcmM9Imh0dHBzPzovLy4rP1w/aWQ9XGQrIj48L3NjcmlwdD48IS0tL1wxLS0+IjtpOjE1ODtzOjE0NzoiKFx3ezEsMjB9KT13aW5kb3dcLmxvY2F0aW9uO2lmXChuZXcgUmVnRXhwXChbIiddKFthLXpBLVpffFwtMC05XSspPyhjaGVja291dHxvbmVzdGVwfG9uZXBhZ2UpKFthLXpBLVpffFwtMC05XSspP1snIl1cKVwudGVzdFwoXDFcKS4rP1hNTEh0dHBSZXF1ZXN0IjtpOjE1OTtzOjEzMDoiWE1MSHR0cFJlcXVlc3QuKz9SZWdFeHBcKFsiJ10oW2EtekEtWl98XC0wLTldKyk/KGNoZWNrb3V0fG9uZXN0ZXB8b25lcGFnZSkoW2EtekEtWl98XC0wLTldKyk/WyciXSwnZ2knXClcKVwudGVzdFwod2luZG93XC5sb2NhdGlvbiI7aToxNjA7czoxMzk6IjwhLS1cd3szMn0tLT48c3R5bGU+I1x3K1xzKntjb2xvcjpcdys7cGFkZGluZzpcZCtweDttYXJnaW46XGQrcHg7fVxzKlwuXHcrLFxzKlwuXHcrXHMqYVxzKnt0ZXh0LWRlY29yYXRpb246bm9uZS4rPzwvZGl2PjwvZGl2PjwhLS1cd3szMn0tLT4iO2k6MTYxO3M6MjU1OiIoKGZyYW1lYm9yZGVyPSJubyJ8c2Nyb2xsaW5nPSJubyJ8YWxsb3d0cmFuc3BhcmVuY3kpXHMrKXszLH1zdHlsZT0iKHBvc2l0aW9uOmZpeGVkO3x0b3A6MHB4O3xsZWZ0OjBweDt8Ym90dG9tOjBweDt8cmlnaHQ6MHB4O3x3aWR0aDoxMDAlO3xoZWlnaHQ6MTAwJTt8Ym9yZGVyOm5vbmU7fG1hcmdpbjowO3xwYWRkaW5nOjA7fGZpbHRlcjphbHBoYVwob3BhY2l0eT0wXCk7fG9wYWNpdHk6MDt8Y3Vyc29yOnBvaW50ZXI7fHotaW5kZXg6XGQrKXs1LH0iO2k6MTYyO3M6NjE6Il9wb3B3bmRccyo9XHMqd2luZG93XC5vcGVuXCgnaHR0cDovL3F1aWNrZG9tYWluZndkXC5jb20vXD9kbj0iO2k6MTYzO3M6MjQ6Ii90ZHMvZ29cLnBocD9zaWQ9XGQrJnRhZyI7aToxNjQ7czoxNDoidj0wO3Z4PVsnIl1Db2QiO2k6MTY1O3M6MjM6IkF0WyciXVxdXCh2XCtcK1wpLTFcKVwpIjtpOjE2NjtzOjMyOiJDbGlja1VuZGVyY29va2llXHMqPVxzKkdldENvb2tpZSI7aToxNjc7czo3MDoidXNlckFnZW50XHxwcFx8aHR0cFx8ZGF6YWx5elsnIl17MCwxfVwuc3BsaXRcKFsnIl17MCwxfVx8WyciXXswLDF9XCksMCI7aToxNjg7czoyMjoiXC5wcm90b3R5cGVcLmF9Y2F0Y2hcKCI7aToxNjk7czozNzoidHJ5e0Jvb2xlYW5cKFwpXC5wcm90b3R5cGVcLnF9Y2F0Y2hcKCI7aToxNzA7czozNDoiaWZcKFJlZlwuaW5kZXhPZlwoJ1wuZ29vZ2xlXC4nXCkhPSI7aToxNzE7czo4NjoiaW5kZXhPZlx8aWZcfHJjXHxsZW5ndGhcfG1zblx8eWFob29cfHJlZmVycmVyXHxhbHRhdmlzdGFcfG9nb1x8YmlcfGhwXHx2YXJcfGFvbFx8cXVlcnkiO2k6MTcyO3M6NjA6IkFycmF5XC5wcm90b3R5cGVcLnNsaWNlXC5jYWxsXChhcmd1bWVudHNcKVwuam9pblwoWyciXVsnIl1cKSI7aToxNzM7czo4MjoicT1kb2N1bWVudFwuY3JlYXRlRWxlbWVudFwoImQiXCsiaSJcKyJ2IlwpO3FcLmFwcGVuZENoaWxkXChxXCsiIlwpO31jYXRjaFwocXdcKXtoPSI7aToxNzQ7czo3OToiXCt6ejtzcz1cW1xdO2Y9J2ZyJ1wrJ29tJ1wrJ0NoJztmXCs9J2FyQyc7ZlwrPSdvZGUnO3c9dGhpcztlPXdcW2ZcWyJzdWJzdHIiXF1cKCI7aToxNzU7czoxMTU6InM1XChxNVwpe3JldHVybiBcK1wrcTU7fWZ1bmN0aW9uIHlmXChzZix3ZVwpe3JldHVybiBzZlwuc3Vic3RyXCh3ZSwxXCk7fWZ1bmN0aW9uIHkxXCh3Ylwpe2lmXCh3Yj09MTY4XCl3Yj0xMDI1O2Vsc2UiO2k6MTc2O3M6NjQ6ImlmXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFwubWF0Y2hcKC9cKGFuZHJvaWRcfG1pZHBcfGoybWVcfHN5bWJpYW4iO2k6MTc3O3M6MTA2OiJkb2N1bWVudFwud3JpdGVcKCc8c2NyaXB0IGxhbmd1YWdlPSJKYXZhU2NyaXB0IiB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIHNyYz0iJ1wrZG9tYWluXCsnIj48L3NjcidcKydpcHQ+J1wpIjtpOjE3ODtzOjMxOiJodHRwOi8vcGhzcFwucnUvXy9nb1wucGhwXD9zaWQ9IjtpOjE3OTtzOjY2OiI9bmF2aWdhdG9yXFthcHBWZXJzaW9uX3ZhclxdXC5pbmRleE9mXCgiTVNJRSJcKSE9LTFcPyc8aWZyYW1lIG5hbWUiO2k6MTgwO3M6NzoiXFx4NjVBdCI7aToxODE7czo5OiJcXHg2MXJDb2QiO2k6MTgyO3M6MjI6IiJmciJcKyJvbUMiXCsiaGFyQ29kZSIiO2k6MTgzO3M6MTE6Ij0iZXYiXCsiYWwiIjtpOjE4NDtzOjc4OiJcW1woXChlXClcPyJzIjoiIlwpXCsicCJcKyJsaXQiXF1cKCJhXCQiXFtcKFwoZVwpXD8ic3UiOiIiXClcKyJic3RyIlxdXCgxXClcKTsiO2k6MTg1O3M6Mzk6ImY9J2ZyJ1wrJ29tJ1wrJ0NoJztmXCs9J2FyQyc7ZlwrPSdvZGUnOyI7aToxODY7czoyMDoiZlwrPVwoaFwpXD8nb2RlJzoiIjsiO2k6MTg3O3M6NDE6ImY9J2YnXCsncidcKydvJ1wrJ20nXCsnQ2gnXCsnYXJDJ1wrJ29kZSc7IjtpOjE4ODtzOjUwOiJmPSdmcm9tQ2gnO2ZcKz0nYXJDJztmXCs9J3Fnb2RlJ1xbInN1YnN0ciJcXVwoMlwpOyI7aToxODk7czoxNjoidmFyXHMrZGl2X2NvbG9ycyI7aToxOTA7czo5OiJ2YXJccytfMHgiO2k6MTkxO3M6MjA6IkNvcmVMaWJyYXJpZXNIYW5kbGVyIjtpOjE5MjtzOjEwOiJrbTBhZTlncjZtIjtpOjE5MztzOjY6ImMzMjg0ZCI7aToxOTQ7czo4OiJcXHg2OGFyQyI7aToxOTU7czo4OiJcXHg2ZENoYSI7aToxOTY7czo3OiJcXHg2ZmRlIjtpOjE5NztzOjc6IlxceDZmZGUiO2k6MTk4O3M6ODoiXFx4NDNvZGUiO2k6MTk5O3M6NzoiXFx4NzJvbSI7aToyMDA7czo3OiJcXHg0M2hhIjtpOjIwMTtzOjc6IlxceDcyQ28iO2k6MjAyO3M6ODoiXFx4NDNvZGUiO2k6MjAzO3M6MTA6IlwuZHluZG5zXC4iO2k6MjA0O3M6OToiXC5keW5kbnMtIjtpOjIwNTtzOjc5OiJ9XHMqZWxzZVxzKntccypkb2N1bWVudFwud3JpdGVccypcKFxzKlsnIl17MCwxfVwuWyciXXswLDF9XClccyp9XHMqfVxzKlJcKFxzKlwpIjtpOjIwNjtzOjQ1OiJkb2N1bWVudFwud3JpdGVcKHVuZXNjYXBlXCgnJTNDZGl2JTIwaWQlM0QlMjIiO2k6MjA3O3M6MTg6IlwuYml0Y29pbnBsdXNcLmNvbSI7aToyMDg7czo0MToiXC5zcGxpdFwoIiYmIlwpO2g9MjtzPSIiO2lmXChtXClmb3JcKGk9MDsiO2k6MjA5O3M6NDE6IjxpZnJhbWVccytzcmM9Imh0dHA6Ly9kZWx1eGVzY2xpY2tzXC5wcm8vIjtpOjIxMDtzOjQ1OiIzQmZvclx8ZnJvbUNoYXJDb2RlXHwyQzI3XHwzRFx8MkM4OFx8dW5lc2NhcGUiO2k6MjExO3M6NTg6Ijtccypkb2N1bWVudFwud3JpdGVcKFsnIl17MCwxfTxpZnJhbWVccypzcmM9Imh0dHA6Ly95YVwucnUiO2k6MjEyO3M6MTEwOiJ3XC5kb2N1bWVudFwuYm9keVwuYXBwZW5kQ2hpbGRcKHNjcmlwdFwpO1xzKmNsZWFySW50ZXJ2YWxcKGlcKTtccyp9XHMqfVxzKixccypcZCtccypcKVxzKjtccyp9XHMqXClcKFxzKndpbmRvdyI7aToyMTM7czoxMTA6ImlmXCghZ1woXCkmJndpbmRvd1wubmF2aWdhdG9yXC5jb29raWVFbmFibGVkXCl7ZG9jdW1lbnRcLmNvb2tpZT0iMT0xO2V4cGlyZXM9IlwrZVwudG9HTVRTdHJpbmdcKFwpXCsiO3BhdGg9LyI7IjtpOjIxNDtzOjcwOiJubl9wYXJhbV9wcmVsb2FkZXJfY29udGFpbmVyXHw1MDAxXHxoaWRkZW5cfGlubmVySFRNTFx8aW5qZWN0XHx2aXNpYmxlIjtpOjIxNTtzOjI5OiI8IS0tW2EtekEtWjAtOV9dK1x8XHxzdGF0IC0tPiI7aToyMTY7czo4NToiJnBhcmFtZXRlcj1cJGtleXdvcmQmc2U9XCRzZSZ1cj0xJkhUVFBfUkVGRVJFUj0nXCtlbmNvZGVVUklDb21wb25lbnRcKGRvY3VtZW50XC5VUkxcKSI7aToyMTc7czo0ODoid2luZG93c1x8c2VyaWVzXHw2MFx8c3ltYm9zXHxjZVx8bW9iaWxlXHxzeW1iaWFuIjtpOjIxODtzOjM1OiJcW1snIl1ldmFsWyciXVxdXChzXCk7fX19fTwvc2NyaXB0PiI7aToyMTk7czo1OToia0M3MEZNYmx5SmtGV1pvZENLbDFXWU9kV1lVbG5RelJuYmwxV1pzVkVkbGRtTDA1V1p0VjNZdlJHSTkiO2k6MjIwO3M6NTU6IntrPWk7cz1zXC5jb25jYXRcKHNzXChldmFsXChhc3FcKFwpXCktMVwpXCk7fXo9cztldmFsXCgiO2k6MjIxO3M6MTMwOiJkb2N1bWVudFwuY29va2llXC5tYXRjaFwobmV3XHMrUmVnRXhwXChccyoiXChcPzpcXlx8OyBcKSJccypcK1xzKm5hbWVcLnJlcGxhY2VcKC9cKFxbXFxcLlwkXD9cKlx8e31cXFwoXFxcKVxcXFtcXFxdXFwvXFxcK1xeXF1cKS9nIjtpOjIyMjtzOjg2OiJzZXRDb29raWVccypcKD9ccyoiYXJ4X3R0IlxzKixccyoxXHMqLFxzKmR0XC50b0dNVFN0cmluZ1woXClccyosXHMqWyciXXswLDF9L1snIl17MCwxfSI7aToyMjM7czoxNDQ6ImRvY3VtZW50XC5jb29raWVcLm1hdGNoXHMqXChccypuZXdccytSZWdFeHBccypcKFxzKiJcKFw/OlxeXHw7XHMqXCkiXHMqXCtccypuYW1lXC5yZXBsYWNlXHMqXCgvXChcW1xcXC5cJFw/XCpcfHt9XFxcKFxcXClcXFxbXFxcXVxcL1xcXCtcXlxdXCkvZyI7aToyMjQ7czo5ODoidmFyXHMrZHRccys9XHMrbmV3XHMrRGF0ZVwoXCksXHMrZXhwaXJ5VGltZVxzKz1ccytkdFwuc2V0VGltZVwoXHMrZHRcLmdldFRpbWVcKFwpXHMrXCtccys5MDAwMDAwMDAiO2k6MjI1O3M6MTA1OiJpZlxzKlwoXHMqbnVtXHMqPT09XHMqMFxzKlwpXHMqe1xzKnJldHVyblxzKjE7XHMqfVxzKmVsc2Vccyp7XHMqcmV0dXJuXHMrbnVtXHMqXCpccypyRmFjdFwoXHMqbnVtXHMqLVxzKjEiO2k6MjI2O3M6NDE6IlwrPVN0cmluZ1wuZnJvbUNoYXJDb2RlXChwYXJzZUludFwoMFwrJ3gnIjtpOjIyNztzOjgzOiI8c2NyaXB0XHMrbGFuZ3VhZ2U9IkphdmFTY3JpcHQiPlxzKnBhcmVudFwud2luZG93XC5vcGVuZXJcLmxvY2F0aW9uPSJodHRwOi8vdmtcLmNvbSI7aToyMjg7czo0NDoibG9jYXRpb25cLnJlcGxhY2VcKFsnIl17MCwxfWh0dHA6Ly92NWs0NVwucnUiO2k6MjI5O3M6MTI5OiI7dHJ5e1wrXCtkb2N1bWVudFwuYm9keX1jYXRjaFwocVwpe2FhPWZ1bmN0aW9uXChmZlwpe2ZvclwoaT0wO2k8elwubGVuZ3RoO2lcK1wrXCl7emFcKz1TdHJpbmdcW2ZmXF1cKGVcKHZcK1woelxbaVxdXClcKS0xMlwpO319O30iO2k6MjMwO3M6MTQyOiJkb2N1bWVudFwud3JpdGVccypcKFsnIl17MCwxfTxbJyJdezAsMX1ccypcK1xzKnhcWzBcXVxzKlwrXHMqWyciXXswLDF9IFsnIl17MCwxfVxzKlwrXHMqeFxbNFxdXHMqXCtccypbJyJdezAsMX0+XC5bJyJdezAsMX1ccypcK3hccypcWzJcXVxzKlwrIjtpOjIzMTtzOjYwOiJpZlwodFwubGVuZ3RoPT0yXCl7elwrPVN0cmluZ1wuZnJvbUNoYXJDb2RlXChwYXJzZUludFwodFwpXCsiO2k6MjMyO3M6NzQ6IndpbmRvd1wub25sb2FkXHMqPVxzKmZ1bmN0aW9uXChcKVxzKntccyppZlxzKlwoZG9jdW1lbnRcLmNvb2tpZVwuaW5kZXhPZlwoIjtpOjIzMztzOjk3OiJcLnN0eWxlXC5oZWlnaHRccyo9XHMqWyciXXswLDF9MHB4WyciXXswLDF9O3dpbmRvd1wub25sb2FkXHMqPVxzKmZ1bmN0aW9uXChcKVxzKntkb2N1bWVudFwuY29va2llIjtpOjIzNDtzOjEyMjoiXC5zcmM9XChbJyJdezAsMX1odHBzOlsnIl17MCwxfT09ZG9jdW1lbnRcLmxvY2F0aW9uXC5wcm90b2NvbFw/WyciXXswLDF9aHR0cHM6Ly9zc2xbJyJdezAsMX06WyciXXswLDF9aHR0cDovL1snIl17MCwxfVwpXCsiO2k6MjM1O3M6MzA6IjQwNFwucGhwWyciXXswLDF9PlxzKjwvc2NyaXB0PiI7aToyMzY7czo3NjoicHJlZ19tYXRjaFwoWyciXXswLDF9L3NhcGUvaVsnIl17MCwxfVxzKixccypcJF9TRVJWRVJcW1snIl17MCwxfUhUVFBfUkVGRVJFUiI7aToyMzc7czo3NDoiZGl2XC5pbm5lckhUTUxccypcKz1ccypbJyJdezAsMX08ZW1iZWRccytpZD0iZHVtbXkyIlxzK25hbWU9ImR1bW15MiJccytzcmMiO2k6MjM4O3M6NzM6InNldFRpbWVvdXRcKFsnIl17MCwxfWFkZE5ld09iamVjdFwoXClbJyJdezAsMX0sXGQrXCk7fX19O2FkZE5ld09iamVjdFwoXCkiO2k6MjM5O3M6NTE6IlwoYj1kb2N1bWVudFwpXC5oZWFkXC5hcHBlbmRDaGlsZFwoYlwuY3JlYXRlRWxlbWVudCI7aToyNDA7czozMDoiQ2hyb21lXHxpUGFkXHxpUGhvbmVcfElFTW9iaWxlIjtpOjI0MTtzOjE5OiJcJDpcKHt9XCsiIlwpXFtcJFxdIjtpOjI0MjtzOjQ5OiI8L2lmcmFtZT5bJyJdXCk7XHMqdmFyXHMraj1uZXdccytEYXRlXChuZXdccytEYXRlIjtpOjI0MztzOjUzOiJ7cG9zaXRpb246YWJzb2x1dGU7dG9wOi05OTk5cHg7fTwvc3R5bGU+PGRpdlxzK2NsYXNzPSI7aToyNDQ7czoxMjg6ImlmXHMqXChcKHVhXC5pbmRleE9mXChbJyJdezAsMX1jaHJvbWVbJyJdezAsMX1cKVxzKj09XHMqLTFccyomJlxzKnVhXC5pbmRleE9mXCgid2luIlwpXHMqIT1ccyotMVwpXHMqJiZccypuYXZpZ2F0b3JcLmphdmFFbmFibGVkIjtpOjI0NTtzOjU4OiJwYXJlbnRcLndpbmRvd1wub3BlbmVyXC5sb2NhdGlvbj1bJyJdezAsMX1odHRwOi8vdmtcLmNvbVwuIjtpOjI0NjtzOjY4OiJqYXZhc2NyaXB0XHxoZWFkXHx0b0xvd2VyQ2FzZVx8Y2hyb21lXHx3aW5cfGphdmFFbmFibGVkXHxhcHBlbmRDaGlsZCI7aToyNDc7czoyMToibG9hZFBOR0RhdGFcKHN0ckZpbGUsIjtpOjI0ODtzOjIwOiJcKTtpZlwoIX5cKFsnIl17MCwxfSI7aToyNDk7czoyMzoiLy9ccypTb21lXC5kZXZpY2VzXC5hcmUiO2k6MjUwO3M6MzI6IndpbmRvd1wub25lcnJvclxzKj1ccypraWxsZXJyb3JzIjtpOjI1MTtzOjEwNToiY2hlY2tfdXNlcl9hZ2VudD1cW1xzKlsnIl17MCwxfUx1bmFzY2FwZVsnIl17MCwxfVxzKixccypbJyJdezAsMX1pUGhvbmVbJyJdezAsMX1ccyosXHMqWyciXXswLDF9TWFjaW50b3NoIjtpOjI1MjtzOjE1MzoiZG9jdW1lbnRcLndyaXRlXChbJyJdezAsMX08WyciXXswLDF9XCtbJyJdezAsMX1pWyciXXswLDF9XCtbJyJdezAsMX1mWyciXXswLDF9XCtbJyJdezAsMX1yWyciXXswLDF9XCtbJyJdezAsMX1hWyciXXswLDF9XCtbJyJdezAsMX1tWyciXXswLDF9XCtbJyJdezAsMX1lIjtpOjI1MztzOjQ4OiJzdHJpcG9zXChuYXZpZ2F0b3JcLnVzZXJBZ2VudFxzKixccypsaXN0X2RhdGFcW2kiO2k6MjU0O3M6MjY6ImlmXHMqXCghc2VlX3VzZXJfYWdlbnRcKFwpIjtpOjI1NTtzOjQ2OiJjXC5sZW5ndGhcKTt9cmV0dXJuXHMqWyciXVsnIl07fWlmXCghZ2V0Q29va2llIjtpOjI1NjtzOjcwOiI8c2NyaXB0XHMqdHlwZT1bJyJdezAsMX10ZXh0L2phdmFzY3JpcHRbJyJdezAsMX1ccypzcmM9WyciXXswLDF9ZnRwOi8vIjtpOjI1NztzOjQ4OiJpZlxzKlwoZG9jdW1lbnRcLmNvb2tpZVwuaW5kZXhPZlwoWyciXXswLDF9c2FicmkiO2k6MjU4O3M6MTIyOiJ3aW5kb3dcLmxvY2F0aW9uPWJ9XHMqXClcKFxzKm5hdmlnYXRvclwudXNlckFnZW50XHMqXHxcfFxzKm5hdmlnYXRvclwudmVuZG9yXHMqXHxcfFxzKndpbmRvd1wub3BlcmFccyosXHMqWyciXXswLDF9aHR0cDovLyI7aToyNTk7czoxMTQ6IlwpO1xzKmlmXChccypbYS16QS1aMC05X10rXC50ZXN0XChccypkb2N1bWVudFwucmVmZXJyZXJccypcKVxzKiYmXHMqW2EtekEtWjAtOV9dK1wpXHMqe1xzKmRvY3VtZW50XC5sb2NhdGlvblwuaHJlZiI7aToyNjA7czo1MjoiaWZcKC9BbmRyb2lkL2lcW18weFthLXpBLVowLTlfXStcW1xkK1xdXF1cKG5hdmlnYXRvciI7aToyNjE7czo2OToiZnVuY3Rpb25cKGFcKXtpZlwoYSYmWyciXWRhdGFbJyJdaW5cZCthJiZhXC5kYXRhXC5hXGQrJiZhXC5kYXRhXC5hXGQrIjtpOjI2MjtzOjU4OiJzXChvXCl9XCl9LGY9ZnVuY3Rpb25cKFwpe3ZhciB0LGk9SlNPTlwuc3RyaW5naWZ5XChlXCk7b1woIjtpOjI2MztzOjEwNjoiPFxkK1xzK1xkKz1bJyJdXGQrL1xkK1xcWyciXVwrXFxbJyJdLlxcWyciXVwrXFxbJyJdLlsnIl1ccysuPVsnIl0uOi8vXGQrXFxbJyJdXCtcXFsnIl0uXC5cZCtcXFsnIl1cK1xcWyciXSI7aToyNjQ7czoxMDc6InNldFRpbWVvdXRcKFxkK1wpO1xzKnZhclxzK2RlZmF1bHRfa2V5d29yZFxzKj1ccyplbmNvZGVVUklDb21wb25lbnRcKGRvY3VtZW50XC50aXRsZVwpO1xzKnZhclxzK3NlX3JlZmVycmVyIjtpOjI2NTtzOjk4OiI9ZG9jdW1lbnRcLnJlZmVycmVyO2lmXChSZWZcLmluZGV4T2ZcKFsnIl1cLmdvb2dsZVwuWyciXVwpIT0tMVx8XHxSZWZcLmluZGV4T2ZcKFsnIl1cLmJpbmdcLlsnIl1cKSI7aToyNjY7czoyMDoidmlzaXRvclRyYWNrZXJfaXNNb2IiO2k6MjY3O3M6NDE6Ii9cKlx3ezMyfVwqL3ZhclxzK18weFthLXpBLVowLTlfXSs9XFsiXFx4IjtpOjI2ODtzOjcxOiIvXCpcd3szMn1cKi87d2luZG93XFtbJyJdZG9jdW1lbnRbJyJdXF1cW1snIl1bYS16QS1aMC05X10rWyciXVxdPVxbWyciXSI7aToyNjk7czo0ODoiXF1cXVwuam9pblwoXFxbJyJdXFxbJyJdXCk7WyciXVwpXCk7L1wqXHd7MzJ9XCovIjtpOjI3MDtzOjEzNDoiO3ZhclxzK1thLXpBLVowLTlfXSs9W2EtekEtWjAtOV9dK1wuY2hhckNvZGVBdFwoXGQrXClcXlthLXpBLVowLTlfXStcLmNoYXJDb2RlQXRcKFthLXpBLVowLTlfXStcKTtbYS16QS1aMC05X10rPVN0cmluZ1wuZnJvbUNoYXJDb2RlXCgiO2k6MjcxO3M6Mzg6ImV2YWxcKGV2YWxcKFsnIl1TdHJpbmdcLmZyb21DaGFyQ29kZVwoIjtpOjI3MjtzOjEwMDoiY2xlbjtpXCtcK1wpe2JcKz1TdHJpbmdcLmZyb21DaGFyQ29kZVwoYVwuY2hhckNvZGVBdFwoaVwpXF4yXCl9Yz11bmVzY2FwZVwoYlwpO2RvY3VtZW50XC53cml0ZVwoY1wpOyI7aToyNzM7czo2ODoid2luZG93XC5sb2NhdGlvblxzKj1ccypbJyJdaHR0cDovL1xkK1wuXGQrXC5cZCtcLlxkKy9cP1thLXpBLVowLTlfXSsiO2k6Mjc0O3M6MzM6Ii4iIC49Ii46Ly8uXC4uXC4uLy4tLi8uLy4vLlwuLlwuLiI7aToyNzU7czozNjoiO2V2YWxcKFN0cmluZ1wuZnJvbUNoYXJDb2RlXChcZCtccyosIjtpOjI3NjtzOjk3OiJzZXRUaW1lb3V0XChcZCtcKTtpZlwoZG9jdW1lbnRcLnJlZmVycmVyXC5pbmRleE9mXChsb2NhdGlvblwucHJvdG9jb2xcKyIvLyJcK2xvY2F0aW9uXC5ob3N0XCkhPT0wIjtpOjI3NztzOjEyODoiL2lcW1x3ezEsNDV9XFtcZCtcXVxdXChpXFtcd3sxLDQ1fVxbXGQrXF1cXVwoMCxcZCtcKVwpXD8hMDohMTt9XHd7MSw0NX1cKFwpPT09ITAmJlwod2luZG93XFtcd3sxLDQ1fVxbXGQrXF1cXT1cd3sxLDQ1fVxbXGQrXF1cKTsiO2k6Mjc4O3M6MTMyOiI6dW5kZWZpbmVkfXJlc3VsdD1jaGVja19vc1woXCk7Y29vaz1udWxsO2Nvb2s9Z2V0Q29va2llXChfMHhbYS16QS1aMC05X10rXFtcZCtcXVwpO2lmXChjb29rPT1udWxsXHxcfGNvb2s9PV8weFthLXpBLVowLTlfXStcW1xkK1xdXCkiO2k6Mjc5O3M6MTE0OiJkb2N1bWVudFwud3JpdGVcKFsnIl08c2NyaXB0XHMrdHlwZT1bJyJddGV4dC9qYXZhc2NyaXB0WyciXVxzK3NyYz1bJyJdaHR0cDovL2dvb1wuZ2wvXHd7MSw0NX1bJyJdPjwvc2NyaXB0PlsnIl1cKTsiO2k6MjgwO3M6MTI0OiJcKGxvY2F0aW9uXC5ocmVmXClcLnJlcGxhY2VcKFsnIl1odHRwOi8vWyciXVwrbG9jYXRpb25cLmhvc3RcK1snIl0vWyciXSxbJyJdWyciXVwpO2lmXChwYXR0ZXJuXC50ZXN0XChkb2N1bWVudFwucmVmZXJyZXJcKVwpIjtpOjI4MTtzOjY2OiI8c2NyaXB0XHMqbGFuZ3VhZ2U9SmF2YVNjcmlwdFxzKnNyYz0vbWVkaWEvc3lzdGVtL2pzL1x3ezEsNDV9XC5waHAiO2k6MjgyO3M6MTM6InVpanF1ZXJ5XC5vcmciO2k6MjgzO3M6MTI6InBvcnRhbC1iXC5wdyI7aToyODQ7czoxNzoic2V4ZnJvbWluZGlhXC5jb20iO2k6Mjg1O3M6MTE6ImZpbGVreFwuY29tIjtpOjI4NjtzOjEzOiJzdHVtbWFublwubmV0IjtpOjI4NztzOjE0OiJ0b3BsYXlnYW1lXC5ydSI7aToyODg7czoxNDoiaHR0cDovL3h6eFwucG0iO2k6Mjg5O3M6MTg6IlwuaG9wdG9cLm1lL2pxdWVyeSI7aToyOTA7czoxMToibW9iaS1nb1wuaW4iO2k6MjkxO3M6MTY6Im15ZmlsZXN0b3JlXC5jb20iO2k6MjkyO3M6MTc6ImZpbGVzdG9yZTcyXC5pbmZvIjtpOjI5MztzOjE2OiJmaWxlMnN0b3JlXC5pbmZvIjtpOjI5NDtzOjE1OiJ1cmwyc2hvcnRcLmluZm8iO2k6Mjk1O3M6MTg6ImZpbGVzdG9yZTEyM1wuaW5mbyI7aToyOTY7czoxMjoidXJsMTIzXC5pbmZvIjtpOjI5NztzOjE0OiJkb2xsYXJhZGVcLmNvbSI7aToyOTg7czoxMToic2VjY2xpa1wucnUiO2k6Mjk5O3M6MTE6Im1vYnktYWFcLnJ1IjtpOjMwMDtzOjEyOiJzZXJ2bG9hZFwucnUiO2k6MzAxO3M6Nzoibm5uXC5wbSI7aTozMDI7czo3OiJubm1cLnBtIjtpOjMwMztzOjE2OiJtb2ItcmVkaXJlY3RcLnJ1IjtpOjMwNDtzOjE2OiJ3ZWItcmVkaXJlY3RcLnJ1IjtpOjMwNTtzOjE2OiJ0b3Atd2VicGlsbFwuY29tIjtpOjMwNjtzOjE5OiJnb29kcGlsbHNlcnZpY2VcLnJ1IjtpOjMwNztzOjE0OiJ5b3V0dWliZXNcLmNvbSI7aTozMDg7czoxNDoidW5zY3Jld2luZ1wucnUiO2k6MzA5O3M6MjY6ImxvYWRtZVwuY2hpY2tlbmtpbGxlclwuY29tIjtpOjMxMDtzOjMxOiJzaGFyZVwucGx1c29cLnJ1L3BsdXNvLWxpa2VcLmpzIjtpOjMxMTtzOjg6Ii8vdmtcLmNjIjtpOjMxMjtzOjI2OiJ3ZWJzaG9wLXRvb2wtbWFuYWdlclwuaW5mbyI7aTozMTM7czoxNzoicHJpdmF0ZWxhbmRzXC5iaXoiO2k6MzE0O3M6MzQ6IlsnIl11aVsnIl1cLlsnIl1qcXVlcnlcLm9yZy9qcXVlcnkiO2k6MzE1O3M6MjE6Imdvb2dsZS1hbmFseXppbmdcLmNvbSI7aTozMTY7czoxOToicG9ydGFsLVx3ezEsNDV9XC5wdyI7aTozMTc7czoxMjoiU0JFUkJBTktcLlJVIjtpOjMxODtzOjE5OiJTQkVSQkFOSy1PTkxJTkVcLlJVIjtpOjMxOTtzOjE2OiJib2tvdHJhZmZpY1wuY29tIjtpOjMyMDtzOjEyOiJvbmNsa2RzXC5jb20iO30="));
$gX_JSVirSig = unserialize(base64_decode("YTo4MDp7aTowO3M6ODQ6IjxzY3JpcHRccytsYW5ndWFnZT1KYXZhU2NyaXB0XHMrc3JjPVthLXpBLVowLTlfXSsvW2EtekEtWjAtOV9dK1xkK1wucGhwXHMqPjwvc2NyaXB0PiI7aToxO3M6NDg6ImRvY3VtZW50XC53cml0ZVxzKlwoXHMqdW5lc2NhcGVccypcKFsnIl17MCwxfSUzYyI7aToyO3M6Njk6ImRvY3VtZW50XC5nZXRFbGVtZW50c0J5VGFnTmFtZVwoWyciXWhlYWRbJyJdXClcWzBcXVwuYXBwZW5kQ2hpbGRcKGFcKSI7aTozO3M6Mjg6ImlwXChob25lXHxvZFwpXHxpcmlzXHxraW5kbGUiO2k6NDtzOjQ4OiJzbWFydHBob25lXHxibGFja2JlcnJ5XHxtdGtcfGJhZGFcfHdpbmRvd3MgcGhvbmUiO2k6NTtzOjMwOiJjb21wYWxcfGVsYWluZVx8ZmVubmVjXHxoaXB0b3AiO2k6NjtzOjIyOiJlbGFpbmVcfGZlbm5lY1x8aGlwdG9wIjtpOjc7czoyOToiXChmdW5jdGlvblwoYSxiXCl7aWZcKC9cKGFuZHIiO2k6ODtzOjQ5OiJpZnJhbWVcLnN0eWxlXC53aWR0aFxzKj1ccypbJyJdezAsMX0wcHhbJyJdezAsMX07IjtpOjk7czo1NToic3RyaXBvc1xzKlwoXHMqZl9oYXlzdGFja1xzKixccypmX25lZWRsZVxzKixccypmX29mZnNldCI7aToxMDtzOjEwMToiZG9jdW1lbnRcLmNhcHRpb249bnVsbDt3aW5kb3dcLmFkZEV2ZW50XChbJyJdezAsMX1sb2FkWyciXXswLDF9LGZ1bmN0aW9uXChcKXt2YXIgY2FwdGlvbj1uZXcgSkNhcHRpb24iO2k6MTE7czoxMjoiaHR0cDovL2Z0cFwuIjtpOjEyO3M6Nzg6IjxzY3JpcHRccyp0eXBlPVsnIl17MCwxfXRleHQvamF2YXNjcmlwdFsnIl17MCwxfVxzKnNyYz1bJyJdezAsMX1odHRwOi8vZ29vXC5nbCI7aToxMztzOjY3OiIiXHMqXCtccypuZXcgRGF0ZVwoXClcLmdldFRpbWVcKFwpO1xzKmRvY3VtZW50XC5ib2R5XC5hcHBlbmRDaGlsZFwoIjtpOjE0O3M6MzQ6IlwuaW5kZXhPZlwoXHMqWyciXUlCcm93c2VbJyJdXHMqXCkiO2k6MTU7czo4NToiPWRvY3VtZW50XC5yZWZlcnJlcjtccypbYS16QS1aMC05X10rPXVuZXNjYXBlXChccypbYS16QS1aMC05X10rXHMqXCk7XHMqdmFyXHMrRXhwRGF0ZSI7aToxNjtzOjcyOiI8IS0tXHMqW2EtekEtWjAtOV9dK1xzKi0tPjxzY3JpcHQuKz88L3NjcmlwdD48IS0tL1xzKlthLXpBLVowLTlfXStccyotLT4iO2k6MTc7czozNToiZXZhbFxzKlwoXHMqZGVjb2RlVVJJQ29tcG9uZW50XHMqXCgiO2k6MTg7czo3MToid2hpbGVcKFxzKmY8XGQrXHMqXClkb2N1bWVudFxbXHMqW2EtekEtWjAtOV9dK1wrWyciXXRlWyciXVxzKlxdXChTdHJpbmciO2k6MTk7czo3ODoic2V0Q29va2llXChccypfMHhbYS16QS1aMC05X10rXHMqLFxzKl8weFthLXpBLVowLTlfXStccyosXHMqXzB4W2EtekEtWjAtOV9dK1wpIjtpOjIwO3M6Mjk6IlxdXChccyp2XCtcK1xzKlwpLTFccypcKVxzKlwpIjtpOjIxO3M6NDM6ImRvY3VtZW50XFtccypfMHhbYS16QS1aMC05X10rXFtcZCtcXVxzKlxdXCgiO2k6MjI7czoyODoiL2csWyciXVsnIl1cKVwuc3BsaXRcKFsnIl1cXSI7aToyMztzOjQzOiJ3aW5kb3dcLmxvY2F0aW9uPWJ9XClcKG5hdmlnYXRvclwudXNlckFnZW50IjtpOjI0O3M6MjI6IlsnIl1yZXBsYWNlWyciXVxdXCgvXFsiO2k6MjU7czoxMjM6ImlcW18weFthLXpBLVowLTlfXStcW1xkK1xdXF1cKFthLXpBLVowLTlfXStcW18weFthLXpBLVowLTlfXStcW1xkK1xdXF1cKFxkKyxcZCtcKVwpXCl7d2luZG93XFtfMHhbYS16QS1aMC05X10rXFtcZCtcXVxdPWxvYyI7aToyNjtzOjQ5OiJkb2N1bWVudFwud3JpdGVcKFxzKlN0cmluZ1wuZnJvbUNoYXJDb2RlXC5hcHBseVwoIjtpOjI3O3M6NTA6IlsnIl1cXVwoW2EtekEtWjAtOV9dK1wrXCtcKS1cZCtcKX1cKEZ1bmN0aW9uXChbJyJdIjtpOjI4O3M6NjQ6Ijt3aGlsZVwoW2EtekEtWjAtOV9dKzxcZCtcKWRvY3VtZW50XFsuKz9cXVwoU3RyaW5nXFtbJyJdZnJvbUNoYXIiO2k6Mjk7czoxMDg6ImlmXHMqXChbYS16QS1aMC05X10rXC5pbmRleE9mXChkb2N1bWVudFwucmVmZXJyZXJcLnNwbGl0XChbJyJdL1snIl1cKVxbWyciXTJbJyJdXF1cKVxzKiE9XHMqWyciXS0xWyciXVwpXHMqeyI7aTozMDtzOjExNDoiZG9jdW1lbnRcLndyaXRlXChccypbJyJdPHNjcmlwdFxzK3R5cGU9WyciXXRleHQvamF2YXNjcmlwdFsnIl1ccypzcmM9WyciXS8vWyciXVxzKlwrXHMqU3RyaW5nXC5mcm9tQ2hhckNvZGVcLmFwcGx5IjtpOjMxO3M6Mzg6InByZWdfbWF0Y2hcKFsnIl1AXCh5YW5kZXhcfGdvb2dsZVx8Ym90IjtpOjMyO3M6MTMwOiJmYWxzZX07W2EtekEtWjAtOV9dKz1bYS16QS1aMC05X10rXChbJyJdW2EtekEtWjAtOV9dK1snIl1cKVx8W2EtekEtWjAtOV9dK1woWyciXVthLXpBLVowLTlfXStbJyJdXCk7W2EtekEtWjAtOV9dK1x8PVthLXpBLVowLTlfXSs7IjtpOjMzO3M6NjQ6IlN0cmluZ1wuZnJvbUNoYXJDb2RlXChccypbYS16QS1aMC05X10rXC5jaGFyQ29kZUF0XChpXClccypcXlxzKjIiO2k6MzQ7czoxNjoiLj1bJyJdLjovLy5cLi4vLiI7aTozNTtzOjU3OiJcW1snIl1jaGFyWyciXVxzKlwrXHMqW2EtekEtWjAtOV9dK1xzKlwrXHMqWyciXUF0WyciXVxdXCgiO2k6MzY7czo0OToic3JjPVsnIl0vL1snIl1ccypcK1xzKlN0cmluZ1wuZnJvbUNoYXJDb2RlXC5hcHBseSI7aTozNztzOjU1OiJTdHJpbmdcW1xzKlsnIl1mcm9tQ2hhclsnIl1ccypcK1xzKlthLXpBLVowLTlfXStccypcXVwoIjtpOjM4O3M6Mjg6Ii49WyciXS46Ly8uXC4uXC4uXC4uLy5cLi5cLi4iO2k6Mzk7czozOToiPC9zY3JpcHQ+WyciXVwpO1xzKi9cKi9bYS16QS1aMC05X10rXCovIjtpOjQwO3M6NzM6ImRvY3VtZW50XFtfMHhcZCtcW1xkK1xdXF1cKF8weFxkK1xbXGQrXF1cK18weFxkK1xbXGQrXF1cK18weFxkK1xbXGQrXF1cKTsiO2k6NDE7czo1MToiXChzZWxmPT09dG9wXD8wOjFcKVwrWyciXVwuanNbJyJdLGFcKGYsZnVuY3Rpb25cKFwpIjtpOjQyO3M6OToiJmFkdWx0PTEmIjtpOjQzO3M6OTc6ImRvY3VtZW50XC5yZWFkeVN0YXRlXHMrPT1ccytbJyJdY29tcGxldGVbJyJdXClccyp7XHMqY2xlYXJJbnRlcnZhbFwoW2EtekEtWjAtOV9dK1wpO1xzKnNcLnNyY1xzKj0iO2k6NDQ7czoxOToiLjovLy5cLi5cLi4vLlwuLlw/LyI7aTo0NTtzOjM5OiJcZCtccyo+XHMqXGQrXHMqXD9ccypbJyJdXFx4XGQrWyciXVxzKjoiO2k6NDY7czo0NToiWyciXVxbXHMqWyciXWNoYXJDb2RlQXRbJyJdXHMqXF1cKFxzKlxkK1xzKlwpIjtpOjQ3O3M6MTc6IjwvYm9keT5ccyo8c2NyaXB0IjtpOjQ4O3M6MTc6IjwvaHRtbD5ccyo8c2NyaXB0IjtpOjQ5O3M6MTc6IjwvaHRtbD5ccyo8aWZyYW1lIjtpOjUwO3M6NDI6ImRvY3VtZW50XC53cml0ZVwoXHMqU3RyaW5nXC5mcm9tQ2hhckNvZGVcKCI7aTo1MTtzOjIyOiJzcmM9ImZpbGVzX3NpdGUvanNcLmpzIjtpOjUyO3M6OTQ6IndpbmRvd1wucG9zdE1lc3NhZ2VcKHtccyp6b3JzeXN0ZW06XHMqMSxccyp0eXBlOlxzKlsnIl11cGRhdGVbJyJdLFxzKnBhcmFtczpccyp7XHMqWyciXXVybFsnIl0iO2k6NTM7czo5ODoiXC5hdHRhY2hFdmVudFwoWyciXW9ubG9hZFsnIl0sYVwpOlthLXpBLVowLTlfXStcLmFkZEV2ZW50TGlzdGVuZXJcKFsnIl1sb2FkWyciXSxhLCExXCk7bG9hZE1hdGNoZXIiO2k6NTQ7czo3ODoiaWZcKFwoYT1lXC5nZXRFbGVtZW50c0J5VGFnTmFtZVwoWyciXWFbJyJdXClcKSYmYVxbMFxdJiZhXFswXF1cLmhyZWZcKWZvclwodmFyIjtpOjU1O3M6ODE6IjtccyplbGVtZW50XC5pbm5lckhUTUxccyo9XHMqWyciXTxpZnJhbWVccytzcmM9WyciXVxzKlwrXHMqeGhyXC5yZXNwb25zZVRleHRccypcKyI7aTo1NjtzOjE5OiJYSEZFUjFccyo9XHMqWEhGRVIxIjtpOjU3O3M6NTE6ImRvY3VtZW50XC53cml0ZVxzKlwoXHMqdW5lc2NhcGVccypcKFxzKlsnIl17MCwxfSUzQyI7aTo1ODtzOjc4OiJkb2N1bWVudFwud3JpdGVccypcKFxzKlsnIl17MCwxfTxzY3JpcHRccytzcmM9WyciXXswLDF9aHR0cDovLzxcPz1cJGRvbWFpblw/Pi8iO2k6NTk7czo1NToid2luZG93XC5sb2NhdGlvblxzKj1ccypbJyJdaHR0cDovL1xkK1wuXGQrXC5cZCtcLlxkKy9cPyI7aTo2MDtzOjY2OiJzZXRUaW1lb3V0XChmdW5jdGlvblwoXCl7dmFyXHMrcGF0dGVyblxzKj1ccypuZXdccypSZWdFeHBcKC9nb29nbGUiO2k6NjE7czo2Njoid289WyciXVwrISFcKFsnIl1vbnRvdWNoc3RhcnRbJyJdXHMraW5ccyt3aW5kb3dcKVwrWyciXSZzdD0xJnRpdGxlIjtpOjYyO3M6NTY6InJlZmVycmVyXHMqIT09XHMqWyciXVsnIl1cKXtkb2N1bWVudFwud3JpdGVcKFsnIl08c2NyaXB0IjtpOjYzO3M6Mzc6ImlmXChhJiZbJyJdZGF0YVsnIl1pblxzKmEmJmFcLmRhdGFcLmEiO2k6NjQ7czoxNjoianF1ZXJ5XC5taW5cLnBocCI7aTo2NTtzOjg2OiJkb2N1bWVudFxbW2EtekEtWjAtOV9dK1woW2EtekEtWjAtOV9dK1xbXGQrXF1cKVxdXChbYS16QS1aMC05X10rXChbYS16QS1aMC05X10rXFtcZCtcXSI7aTo2NjtzOjU4OiJoXC5mXChcXFsnIl08MyA3PVsnIl04LzlcXFsnIl1cK1xcWyciXWFcXFsnIl1cK1xcWyciXWJbJyJdIjtpOjY3O3M6MjU6IlwoXCl9fSxcZCtcKTsvXCpcd3szMn1cKi8iO2k6Njg7czo0OToiZXZhbFsnIl1cLnNwbGl0XChbJyJdXHxbJyJdXCksMCx7fVwpXCk7XHMqfVwoXClcKSI7aTo2OTtzOjY1OiIuXC5jaGFyQXRcKGlcKVwrLlwuY2hhckF0XChpXClcKy5cLmNoYXJBdFwoaVwpO2V2YWxcKC5cKTt9XChcKVwpOyI7aTo3MDtzOjU3OiJkYXRhOnRleHQvamF2YXNjcmlwdDtccypjaGFyc2V0XHMqPVxzKnV0Zi04O1xzKmJhc2U2NFxzKiwiO2k6NzE7czozNDoiZGF0YTp0ZXh0L2phdmFzY3JpcHQ7XHMqYmFzZTY0XHMqLCI7aTo3MjtzOjYzOiJcKTtpZlwoZG9jdW1lbnRcLmdldEVsZW1lbnRCeUlkXChbJyJdXHd7MSw0NX1bJyJdXClcKXt9ZWxzZXt2YXIiO2k6NzM7czo4NzoicmV0dXJuXHMqcH1cKFsnIl0uXC4uXC4uXC4uPVsnIl08LlxzKi49XFxbJyJdLiVcXFsnIl0uLis/c3BsaXRcKFsnIl1cfFsnIl1cKSxcZCsse31cKVwpIjtpOjc0O3M6MTc4OiIsXHd7MSw0NX1cKFx3ezEsNDV9XC5cd3sxLDQ1fVwoXHd7MSw0NX17XHd7MSw0NX1cKVx3ezEsNDV9XChcd3sxLDQ1fSBcd3sxLDQ1fXtcd3sxLDQ1fVwpXHd7MSw0NX1cKFx3ezEsNDV9XChbJyJdLFx3ezEsNDV9PVsnIl1bJyJdO2ZvclwodmFyIFx3ezEsNDV9PVx3ezEsNDV9XC5sZW5ndGgtMTtcd3sxLDQ1fT4wIjtpOjc1O3M6NTY6InNyY1xzKj1ccypbJyJdZGF0YTp0ZXh0L2phdmFzY3JpcHQ7Y2hhcnNldD11dGYtODtiYXNlNjQsIjtpOjc2O3M6NjM6InNyY1xzKj1ccypbJyJdZGF0YTp0ZXh0L2phdmFzY3JpcHQ7Y2hhcnNldD13aW5kb3dzLTEyNTE7YmFzZTY0LCI7aTo3NztzOjg2OiI9XHMqU3RyaW5nXC5mcm9tQ2hhckNvZGVcKFxzKlxkK1xzKi1ccypcZCtccyosXHMqXGQrXHMqLVxzKlxkK1xzKixccypcZCtccyotXHMqXGQrXHMqLCI7aTo3ODtzOjE1OiJcLnRyeW15ZmluZ2VyXC4iO2k6Nzk7czoxOToiXC5vbmVzdGVwdG93aW5cLmNvbSI7fQ=="));
$g_SusDB = unserialize(base64_decode("YToxMzE6e2k6MDtzOjE0OiJAP2V4dHJhY3RccypcKCI7aToxO3M6MTQ6IkA/ZXh0cmFjdFxzKlwkIjtpOjI7czoxMjoiWyciXWV2YWxbJyJdIjtpOjM7czoyMToiWyciXWJhc2U2NF9kZWNvZGVbJyJdIjtpOjQ7czoyMzoiWyciXWNyZWF0ZV9mdW5jdGlvblsnIl0iO2k6NTtzOjE0OiJbJyJdYXNzZXJ0WyciXSI7aTo2O3M6NDM6ImZvcmVhY2hccypcKFxzKlwkZW1haWxzXHMrYXNccytcJGVtYWlsXHMqXCkiO2k6NztzOjc6IlNwYW1tZXIiO2k6ODtzOjE1OiJldmFsXHMqWyciXChcJF0iO2k6OTtzOjE3OiJhc3NlcnRccypbJyJcKFwkXSI7aToxMDtzOjI4OiJzcnBhdGg6Ly9cLlwuL1wuXC4vXC5cLi9cLlwuIjtpOjExO3M6MTI6InBocGluZm9ccypcKCI7aToxMjtzOjE2OiJTSE9XXHMrREFUQUJBU0VTIjtpOjEzO3M6MTI6IlxicG9wZW5ccypcKCI7aToxNDtzOjk6ImV4ZWNccypcKCI7aToxNTtzOjEzOiJcYnN5c3RlbVxzKlwoIjtpOjE2O3M6MTU6IlxicGFzc3RocnVccypcKCI7aToxNztzOjE2OiJcYnByb2Nfb3BlblxzKlwoIjtpOjE4O3M6MTU6InNoZWxsX2V4ZWNccypcKCI7aToxOTtzOjE2OiJpbmlfcmVzdG9yZVxzKlwoIjtpOjIwO3M6OToiXGJkbFxzKlwoIjtpOjIxO3M6MTQ6Ilxic3ltbGlua1xzKlwoIjtpOjIyO3M6MTI6IlxiY2hncnBccypcKCI7aToyMztzOjE0OiJcYmluaV9zZXRccypcKCI7aToyNDtzOjEzOiJcYnB1dGVudlxzKlwoIjtpOjI1O3M6MTM6ImdldG15dWlkXHMqXCgiO2k6MjY7czoxNDoiZnNvY2tvcGVuXHMqXCgiO2k6Mjc7czoxNzoicG9zaXhfc2V0dWlkXHMqXCgiO2k6Mjg7czoxNzoicG9zaXhfc2V0c2lkXHMqXCgiO2k6Mjk7czoxODoicG9zaXhfc2V0cGdpZFxzKlwoIjtpOjMwO3M6MTU6InBvc2l4X2tpbGxccypcKCI7aTozMTtzOjI3OiJhcGFjaGVfY2hpbGRfdGVybWluYXRlXHMqXCgiO2k6MzI7czoxMjoiXGJjaG1vZFxzKlwoIjtpOjMzO3M6MTI6IlxiY2hkaXJccypcKCI7aTozNDtzOjE1OiJwY250bF9leGVjXHMqXCgiO2k6MzU7czoxNDoiXGJ2aXJ0dWFsXHMqXCgiO2k6MzY7czoxNToicHJvY19jbG9zZVxzKlwoIjtpOjM3O3M6MjA6InByb2NfZ2V0X3N0YXR1c1xzKlwoIjtpOjM4O3M6MTk6InByb2NfdGVybWluYXRlXHMqXCgiO2k6Mzk7czoxNDoicHJvY19uaWNlXHMqXCgiO2k6NDA7czoxMzoiZ2V0bXlnaWRccypcKCI7aTo0MTtzOjE5OiJwcm9jX2dldHN0YXR1c1xzKlwoIjtpOjQyO3M6MTU6InByb2NfY2xvc2VccypcKCI7aTo0MztzOjE5OiJlc2NhcGVzaGVsbGNtZFxzKlwoIjtpOjQ0O3M6MTk6ImVzY2FwZXNoZWxsYXJnXHMqXCgiO2k6NDU7czoxNjoic2hvd19zb3VyY2VccypcKCI7aTo0NjtzOjEzOiJcYnBjbG9zZVxzKlwoIjtpOjQ3O3M6MTM6InNhZmVfZGlyXHMqXCgiO2k6NDg7czoxNjoiaW5pX3Jlc3RvcmVccypcKCI7aTo0OTtzOjEwOiJjaG93blxzKlwoIjtpOjUwO3M6MTA6ImNoZ3JwXHMqXCgiO2k6NTE7czoxNzoic2hvd25fc291cmNlXHMqXCgiO2k6NTI7czoxOToibXlzcWxfbGlzdF9kYnNccypcKCI7aTo1MztzOjIxOiJnZXRfY3VycmVudF91c2VyXHMqXCgiO2k6NTQ7czoxMjoiZ2V0bXlpZFxzKlwoIjtpOjU1O3M6MTE6IlxibGVha1xzKlwoIjtpOjU2O3M6MTU6InBmc29ja29wZW5ccypcKCI7aTo1NztzOjIxOiJnZXRfY3VycmVudF91c2VyXHMqXCgiO2k6NTg7czoxMToic3lzbG9nXHMqXCgiO2k6NTk7czoxODoiXCRkZWZhdWx0X3VzZV9hamF4IjtpOjYwO3M6MjE6ImV2YWxccypcKD9ccyp1bmVzY2FwZSI7aTo2MTtzOjc6IkZMb29kZVIiO2k6NjI7czozMToiZG9jdW1lbnRcLndyaXRlXHMqXChccyp1bmVzY2FwZSI7aTo2MztzOjExOiJcYmNvcHlccypcKCI7aTo2NDtzOjIzOiJtb3ZlX3VwbG9hZGVkX2ZpbGVccypcKCI7aTo2NTtzOjg6IlwuMzMzMzMzIjtpOjY2O3M6ODoiXC42NjY2NjYiO2k6Njc7czoyMToicm91bmRccypcKD9ccyowXHMqXCk/IjtpOjY4O3M6MjQ6Im1vdmVfdXBsb2FkZWRfZmlsZXNccypcKCI7aTo2OTtzOjUwOiJpbmlfZ2V0XHMqXChccypbJyJdezAsMX1kaXNhYmxlX2Z1bmN0aW9uc1snIl17MCwxfSI7aTo3MDtzOjM2OiJVTklPTlxzK1NFTEVDVFxzK1snIl17MCwxfTBbJyJdezAsMX0iO2k6NzE7czoxMDoiMlxzKj5ccyomMSI7aTo3MjtzOjU3OiJlY2hvXHMqXCg/XHMqXCRfU0VSVkVSXFtbJyJdezAsMX1ET0NVTUVOVF9ST09UWyciXXswLDF9XF0iO2k6NzM7czozNzoiPVxzKkFycmF5XHMqXCg/XHMqYmFzZTY0X2RlY29kZVxzKlwoPyI7aTo3NDtzOjE0OiJraWxsYWxsXHMrLVxkKyI7aTo3NTtzOjc6ImVyaXVxZXIiO2k6NzY7czoxMDoidG91Y2hccypcKCI7aTo3NztzOjc6InNzaGtleXMiO2k6Nzg7czo4OiJAaW5jbHVkZSI7aTo3OTtzOjg6IkByZXF1aXJlIjtpOjgwO3M6NjI6ImlmXHMqXChtYWlsXHMqXChccypcJHRvLFxzKlwkc3ViamVjdCxccypcJG1lc3NhZ2UsXHMqXCRoZWFkZXJzIjtpOjgxO3M6Mzg6IkBpbmlfc2V0XHMqXCg/WyciXXswLDF9YWxsb3dfdXJsX2ZvcGVuIjtpOjgyO3M6MTg6IkBmaWxlX2dldF9jb250ZW50cyI7aTo4MztzOjE3OiJmaWxlX3B1dF9jb250ZW50cyI7aTo4NDtzOjQ2OiJhbmRyb2lkXHMqXHxccyptaWRwXHMqXHxccypqMm1lXHMqXHxccypzeW1iaWFuIjtpOjg1O3M6Mjg6IkBzZXRjb29raWVccypcKD9bJyJdezAsMX1oaXQiO2k6ODY7czoxMDoiQGZpbGVvd25lciI7aTo4NztzOjY6IjxrdWt1PiI7aTo4ODtzOjU6InN5cGV4IjtpOjg5O3M6OToiXCRiZWVjb2RlIjtpOjkwO3M6MTQ6InJvb3RAbG9jYWxob3N0IjtpOjkxO3M6ODoiQmFja2Rvb3IiO2k6OTI7czoxNDoicGhwX3VuYW1lXHMqXCgiO2k6OTM7czo1NToibWFpbFxzKlwoP1xzKlwkdG9ccyosXHMqXCRzdWJqXHMqLFxzKlwkbXNnXHMqLFxzKlwkZnJvbSI7aTo5NDtzOjI5OiJlY2hvXHMqWyciXTxzY3JpcHQ+XHMqYWxlcnRcKCI7aTo5NTtzOjY3OiJtYWlsXHMqXCg/XHMqXCRzZW5kXHMqLFxzKlwkc3ViamVjdFxzKixccypcJGhlYWRlcnNccyosXHMqXCRtZXNzYWdlIjtpOjk2O3M6NjU6Im1haWxccypcKD9ccypcJHRvXHMqLFxzKlwkc3ViamVjdFxzKixccypcJG1lc3NhZ2VccyosXHMqXCRoZWFkZXJzIjtpOjk3O3M6MTIwOiJzdHJwb3NccypcKD9ccypcJG5hbWVccyosXHMqWyciXXswLDF9SFRUUF9bJyJdezAsMX1ccypcKT9ccyohPT1ccyowXHMqJiZccypzdHJwb3NccypcKD9ccypcJG5hbWVccyosXHMqWyciXXswLDF9UkVRVUVTVF8iO2k6OTg7czo1MzoiaXNfZnVuY3Rpb25fZW5hYmxlZFxzKlwoXHMqWyciXXswLDF9aWdub3JlX3VzZXJfYWJvcnQiO2k6OTk7czozMDoiZWNob1xzKlwoP1xzKmZpbGVfZ2V0X2NvbnRlbnRzIjtpOjEwMDtzOjI2OiJlY2hvXHMqXCg/WyciXXswLDF9PHNjcmlwdCI7aToxMDE7czozMToicHJpbnRccypcKD9ccypmaWxlX2dldF9jb250ZW50cyI7aToxMDI7czoyNzoicHJpbnRccypcKD9bJyJdezAsMX08c2NyaXB0IjtpOjEwMztzOjg1OiI8bWFycXVlZVxzK3N0eWxlXHMqPVxzKlsnIl17MCwxfXBvc2l0aW9uXHMqOlxzKmFic29sdXRlXHMqO1xzKndpZHRoXHMqOlxzKlxkK1xzKnB4XHMqIjtpOjEwNDtzOjQyOiI9XHMqWyciXXswLDF9XC5cLi9cLlwuL1wuXC4vd3AtY29uZmlnXC5waHAiO2k6MTA1O3M6NzoiZWdnZHJvcCI7aToxMDY7czo5OiJyd3hyd3hyd3giO2k6MTA3O3M6MTU6ImVycm9yX3JlcG9ydGluZyI7aToxMDg7czoxNzoiXGJjcmVhdGVfZnVuY3Rpb24iO2k6MTA5O3M6NDM6Intccypwb3NpdGlvblxzKjpccyphYnNvbHV0ZTtccypsZWZ0XHMqOlxzKi0iO2k6MTEwO3M6MTU6IjxzY3JpcHRccythc3luYyI7aToxMTE7czo2NjoiX1snIl17MCwxfVxzKlxdXHMqPVxzKkFycmF5XHMqXChccypiYXNlNjRfZGVjb2RlXHMqXCg/XHMqWyciXXswLDF9IjtpOjExMjtzOjMzOiJBZGRUeXBlXHMrYXBwbGljYXRpb24veC1odHRwZC1jZ2kiO2k6MTEzO3M6NDQ6ImdldGVudlxzKlwoP1xzKlsnIl17MCwxfUhUVFBfQ09PS0lFWyciXXswLDF9IjtpOjExNDtzOjQ1OiJpZ25vcmVfdXNlcl9hYm9ydFxzKlwoP1xzKlsnIl17MCwxfTFbJyJdezAsMX0iO2k6MTE1O3M6MjE6IlwkX1JFUVVFU1RccypcW1xzKiUyMiI7aToxMTY7czo1MToidXJsXHMqXChbJyJdezAsMX1kYXRhXHMqOlxzKmltYWdlL3BuZztccypiYXNlNjRccyosIjtpOjExNztzOjUxOiJ1cmxccypcKFsnIl17MCwxfWRhdGFccyo6XHMqaW1hZ2UvZ2lmO1xzKmJhc2U2NFxzKiwiO2k6MTE4O3M6MzA6Ijpccyp1cmxccypcKFxzKlsnIl17MCwxfTxcP3BocCI7aToxMTk7czoxNzoiPC9odG1sPi4rPzxzY3JpcHQiO2k6MTIwO3M6MTc6IjwvaHRtbD4uKz88aWZyYW1lIjtpOjEyMTtzOjY2OiJcYihmdHBfZXhlY3xzeXN0ZW18c2hlbGxfZXhlY3xwYXNzdGhydXxwb3Blbnxwcm9jX29wZW4pXHMqWyciXChcJF0iO2k6MTIyO3M6MTE6IlxibWFpbFxzKlwoIjtpOjEyMztzOjQ2OiJmaWxlX2dldF9jb250ZW50c1xzKlwoP1xzKlsnIl17MCwxfXBocDovL2lucHV0IjtpOjEyNDtzOjExODoiPG1ldGFccytodHRwLWVxdWl2PVsnIl17MCwxfUNvbnRlbnQtdHlwZVsnIl17MCwxfVxzK2NvbnRlbnQ9WyciXXswLDF9dGV4dC9odG1sO1xzKmNoYXJzZXQ9d2luZG93cy0xMjUxWyciXXswLDF9Pjxib2R5PiI7aToxMjU7czo2MjoiPVxzKmRvY3VtZW50XC5jcmVhdGVFbGVtZW50XChccypbJyJdezAsMX1zY3JpcHRbJyJdezAsMX1ccypcKTsiO2k6MTI2O3M6Njk6ImRvY3VtZW50XC5ib2R5XC5pbnNlcnRCZWZvcmVcKGRpdixccypkb2N1bWVudFwuYm9keVwuY2hpbGRyZW5cWzBcXVwpOyI7aToxMjc7czo3NjoiPHNjcmlwdFxzK3R5cGU9InRleHQvamF2YXNjcmlwdCJccytzcmM9Imh0dHA6Ly9bYS16QS1aMC05X10rXC5waHAiPjwvc2NyaXB0PiI7aToxMjg7czoyNzoiZWNob1xzK1snIl17MCwxfW9rWyciXXswLDF9IjtpOjEyOTtzOjE4OiIvdXNyL3NiaW4vc2VuZG1haWwiO2k6MTMwO3M6MjM6Ii92YXIvcW1haWwvYmluL3NlbmRtYWlsIjt9"));
$g_SusDBPrio = unserialize(base64_decode("YToxMjE6e2k6MDtpOjA7aToxO2k6MDtpOjI7aTowO2k6MztpOjA7aTo0O2k6MDtpOjU7aTowO2k6NjtpOjA7aTo3O2k6MDtpOjg7aToxO2k6OTtpOjE7aToxMDtpOjA7aToxMTtpOjA7aToxMjtpOjA7aToxMztpOjA7aToxNDtpOjA7aToxNTtpOjA7aToxNjtpOjA7aToxNztpOjA7aToxODtpOjA7aToxOTtpOjA7aToyMDtpOjA7aToyMTtpOjA7aToyMjtpOjA7aToyMztpOjA7aToyNDtpOjA7aToyNTtpOjA7aToyNjtpOjA7aToyNztpOjA7aToyODtpOjA7aToyOTtpOjE7aTozMDtpOjE7aTozMTtpOjA7aTozMjtpOjA7aTozMztpOjA7aTozNDtpOjA7aTozNTtpOjA7aTozNjtpOjA7aTozNztpOjA7aTozODtpOjA7aTozOTtpOjA7aTo0MDtpOjA7aTo0MTtpOjA7aTo0MjtpOjA7aTo0MztpOjA7aTo0NDtpOjA7aTo0NTtpOjA7aTo0NjtpOjA7aTo0NztpOjA7aTo0ODtpOjA7aTo0OTtpOjA7aTo1MDtpOjA7aTo1MTtpOjA7aTo1MjtpOjA7aTo1MztpOjA7aTo1NDtpOjA7aTo1NTtpOjA7aTo1NjtpOjE7aTo1NztpOjA7aTo1ODtpOjA7aTo1OTtpOjI7aTo2MDtpOjE7aTo2MTtpOjA7aTo2MjtpOjA7aTo2MztpOjA7aTo2NDtpOjI7aTo2NTtpOjA7aTo2NjtpOjA7aTo2NztpOjA7aTo2ODtpOjI7aTo2OTtpOjE7aTo3MDtpOjA7aTo3MTtpOjA7aTo3MjtpOjE7aTo3MztpOjA7aTo3NDtpOjE7aTo3NTtpOjE7aTo3NjtpOjI7aTo3NztpOjE7aTo3ODtpOjM7aTo3OTtpOjI7aTo4MDtpOjA7aTo4MTtpOjI7aTo4MjtpOjA7aTo4MztpOjA7aTo4NDtpOjI7aTo4NTtpOjA7aTo4NjtpOjA7aTo4NztpOjA7aTo4ODtpOjA7aTo4OTtpOjE7aTo5MDtpOjE7aTo5MTtpOjE7aTo5MjtpOjE7aTo5MztpOjA7aTo5NDtpOjI7aTo5NTtpOjI7aTo5NjtpOjI7aTo5NztpOjI7aTo5ODtpOjI7aTo5OTtpOjE7aToxMDA7aToxO2k6MTAxO2k6MztpOjEwMjtpOjM7aToxMDM7aToxO2k6MTA0O2k6MztpOjEwNTtpOjM7aToxMDY7aToyO2k6MTA3O2k6MDtpOjEwODtpOjM7aToxMDk7aToxO2k6MTEwO2k6MTtpOjExMTtpOjM7aToxMTI7aTozO2k6MTEzO2k6MztpOjExNDtpOjE7aToxMTU7aToxO2k6MTE2O2k6MTtpOjExNztpOjQ7aToxMTg7aToxO2k6MTE5O2k6MztpOjEyMDtpOjA7fQ=="));

//END_SIG
////////////////////////////////////////////////////////////////////////////
if (!isCli() && !isset($_SERVER['HTTP_USER_AGENT'])) {
  echo "#####################################################\n";
  echo "# Error: cannot run on php-cgi. Requires php as cli #\n";
  echo "#                                                   #\n";
  echo "# See FAQ: http://revisium.com/ai/faq.php           #\n";
  echo "#####################################################\n";
  exit;
}


if (version_compare(phpversion(), '5.3.1', '<')) {
  echo "#####################################################\n";
  echo "# Warning: PHP Version < 5.3.1                      #\n";
  echo "# Some function might not work properly             #\n";
  echo "# See FAQ: http://revisium.com/ai/faq.php           #\n";
  echo "#####################################################\n";
  exit;
}

if (!(function_exists("file_put_contents") && is_callable("file_put_contents"))) {
    echo "#####################################################\n";
	echo "file_put_contents() is disabled. Cannot proceed.\n";
    echo "#####################################################\n";	
    exit;
}
                              
define('AI_VERSION', '20170820');

////////////////////////////////////////////////////////////////////////////

$l_Res = '';

$g_Structure = array();
$g_Counter = 0;
$g_SpecificExt = false;

$g_UpdatedJsonLog = 0;
$g_NotRead = array();
$g_FileInfo = array();
$g_Iframer = array();
$g_PHPCodeInside = array();
$g_CriticalJS = array();
$g_Phishing = array();
$g_Base64 = array();
$g_HeuristicDetected = array();
$g_HeuristicType = array();
$g_UnixExec = array();
$g_SkippedFolders = array();
$g_UnsafeFilesFound = array();
$g_CMS = array();
$g_SymLinks = array();
$g_HiddenFiles = array();
$g_Vulnerable = array();

$g_TotalFolder = 0;
$g_TotalFiles = 0;

$g_FoundTotalDirs = 0;
$g_FoundTotalFiles = 0;

if (!isCli()) {
   $defaults['site_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/'; 
}

define('CRC32_LIMIT', pow(2, 31) - 1);
define('CRC32_DIFF', CRC32_LIMIT * 2 -2);

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
srand(time());

set_time_limit(0);
ini_set('max_execution_time', '900000');
ini_set('realpath_cache_size','16M');
ini_set('realpath_cache_ttl','1200');
ini_set('pcre.backtrack_limit','1000000');
ini_set('pcre.recursion_limit','200000');
ini_set('pcre.jit','1');

if (!function_exists('stripos')) {
	function stripos($par_Str, $par_Entry, $Offset = 0) {
		return strpos(strtolower($par_Str), strtolower($par_Entry), $Offset);
	}
}

define('CMS_BITRIX', 'Bitrix');
define('CMS_WORDPRESS', 'Wordpress');
define('CMS_JOOMLA', 'Joomla');
define('CMS_DLE', 'Data Life Engine');
define('CMS_IPB', 'Invision Power Board');
define('CMS_WEBASYST', 'WebAsyst');
define('CMS_OSCOMMERCE', 'OsCommerce');
define('CMS_DRUPAL', 'Drupal');
define('CMS_MODX', 'MODX');
define('CMS_INSTANTCMS', 'Instant CMS');
define('CMS_PHPBB', 'PhpBB');
define('CMS_VBULLETIN', 'vBulletin');
define('CMS_SHOPSCRIPT', 'PHP ShopScript Premium');

define('CMS_VERSION_UNDEFINED', '0.0');

class CmsVersionDetector {
    private $root_path;
    private $versions;
    private $types;

    public function __construct($root_path = '.') {
        $this->root_path = $root_path;
        $this->versions = array();
        $this->types = array();

        $version = '';

        $dir_list = $this->getDirList($root_path);
        $dir_list[] = $root_path;

        foreach ($dir_list as $dir) {
            if ($this->checkBitrix($dir, $version)) {
               $this->addCms(CMS_BITRIX, $version);
            }

            if ($this->checkWordpress($dir, $version)) {
               $this->addCms(CMS_WORDPRESS, $version);
            }

            if ($this->checkJoomla($dir, $version)) {
               $this->addCms(CMS_JOOMLA, $version);
            }

            if ($this->checkDle($dir, $version)) {
               $this->addCms(CMS_DLE, $version);
            }

            if ($this->checkIpb($dir, $version)) {
               $this->addCms(CMS_IPB, $version);
            }

            if ($this->checkWebAsyst($dir, $version)) {
               $this->addCms(CMS_WEBASYST, $version);
            }

            if ($this->checkOsCommerce($dir, $version)) {
               $this->addCms(CMS_OSCOMMERCE, $version);
            }

            if ($this->checkDrupal($dir, $version)) {
               $this->addCms(CMS_DRUPAL, $version);
            }

            if ($this->checkMODX($dir, $version)) {
               $this->addCms(CMS_MODX, $version);
            }

            if ($this->checkInstantCms($dir, $version)) {
               $this->addCms(CMS_INSTANTCMS, $version);
            }

            if ($this->checkPhpBb($dir, $version)) {
               $this->addCms(CMS_PHPBB, $version);
            }

            if ($this->checkVBulletin($dir, $version)) {
               $this->addCms(CMS_VBULLETIN, $version);
            }

            if ($this->checkPhpShopScript($dir, $version)) {
               $this->addCms(CMS_SHOPSCRIPT, $version);
            }

        }
    }

    function getDirList($target) {
       $remove = array('.', '..'); 
       $directories = array_diff(scandir($target), $remove);

       $res = array();
           
       foreach($directories as $value) 
       { 
          if(is_dir($target . '/' . $value)) 
          {
             $res[] = $target . '/' . $value; 
          } 
       }

       return $res;
    }

    function isCms($name, $version) {
		for ($i = 0; $i < count($this->types); $i++) {
			if ((strpos($this->types[$i], $name) !== false) 
				&& 
			    (strpos($this->versions[$i], $version) !== false)) {
				return true;
			}
		}
    	
		return false;
    }

    function getCmsList() {
      return $this->types;
    }

    function getCmsVersions() {
      return $this->versions;
    }

    function getCmsNumber() {
      return count($this->types);
    }

    function getCmsName($index = 0) {
      return $this->types[$index];
    }

    function getCmsVersion($index = 0) {
      return $this->versions[$index];
    }

    private function addCms($type, $version) {
       $this->types[] = $type;
       $this->versions[] = $version;
    }

    private function checkBitrix($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir .'/bitrix')) {
          $res = true;

          $tmp_content = @file_get_contents($this->root_path .'/bitrix/modules/main/classes/general/version.php');
          if (preg_match('|define\("SM_VERSION","(.+?)"\)|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkWordpress($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir .'/wp-admin')) {
          $res = true;

          $tmp_content = @file_get_contents($dir .'/wp-includes/version.php');
          if (preg_match('|\$wp_version\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }
       }

       return $res;
    }

    private function checkJoomla($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir .'/libraries/joomla')) {
          $res = true;

          // for 1.5.x
          $tmp_content = @file_get_contents($dir .'/libraries/joomla/version.php');
          if (preg_match('|var\s+\$RELEASE\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];

             if (preg_match('|var\s+\$DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
                $version .= '.' . $tmp_ver[1];
             }
          }

          // for 1.7.x
          $tmp_content = @file_get_contents($dir .'/includes/version.php');
          if (preg_match('|public\s+\$RELEASE\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];

             if (preg_match('|public\s+\$DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
                $version .= '.' . $tmp_ver[1];
             }
          }


	  // for 2.5.x and 3.x 
          $tmp_content = @file_get_contents($dir . '/libraries/cms/version/version.php');
   
          if (preg_match('|const\s+RELEASE\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
	      $version = $tmp_ver[1];
 
             if (preg_match('|const\s+DEV_LEVEL\s*=\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) { 
		$version .= '.' . $tmp_ver[1];
             }
          }

       }

       return $res;
    }

    private function checkDle($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir .'/engine/engine.php')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/engine/data/config.php');
          if (preg_match('|\'version_id\'\s*=>\s*"(.+?)"|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

          $tmp_content = @file_get_contents($dir . '/install.php');
          if (preg_match('|\'version_id\'\s*=>\s*"(.+?)"|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkIpb($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/ips_kernel')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/ips_kernel/class_xml.php');
          if (preg_match('|IP.Board\s+v([0-9\.]+)|si', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkWebAsyst($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/wbs/installer')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/license.txt');
          if (preg_match('|v([0-9\.]+)|si', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkOsCommerce($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/includes/version.php')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/includes/version.php');
          if (preg_match('|([0-9\.]+)|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkDrupal($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/sites/all')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/CHANGELOG.txt');
          if (preg_match('|Drupal\s+([0-9\.]+)|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkMODX($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/manager/assets')) {
          $res = true;

          // no way to pick up version
       }

       return $res;
    }

    private function checkInstantCms($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/plugins/p_usertab')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/index.php');
          if (preg_match('|InstantCMS\s+v([0-9\.]+)|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkPhpBb($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/includes/acp')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/config.php');
          if (preg_match('|phpBB\s+([0-9\.x]+)|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }

    private function checkVBulletin($dir, &$version) {
          $version = CMS_VERSION_UNDEFINED;
          $res = false;
          if (file_exists($dir . '/core/includes/md5_sums_vbulletin.php'))
          {
                $res = true;
                require_once($dir . '/core/includes/md5_sums_vbulletin.php');
                $version = $md5_sum_versions['vb5_connect'];
          }
          else if(file_exists($dir . '/includes/md5_sums_vbulletin.php'))
          {
                $res = true;
                require_once($dir . '/includes/md5_sums_vbulletin.php');
                $version = $md5_sum_versions['vbulletin'];
          }
          return $res;
       }

    private function checkPhpShopScript($dir, &$version) {
       $version = CMS_VERSION_UNDEFINED;
       $res = false;

       if (file_exists($dir . '/install/consts.php')) {
          $res = true;

          $tmp_content = @file_get_contents($dir . '/install/consts.php');
          if (preg_match('|STRING_VERSION\',\s*\'(.+?)\'|smi', $tmp_content, $tmp_ver)) {
             $version = $tmp_ver[1];
          }

       }

       return $res;
    }
}

/**
 * Print file
*/
function printFile() {
	$l_FileName = $_GET['fn'];
	$l_CRC = isset($_GET['c']) ? (int)$_GET['c'] : 0;
	$l_Content = file_get_contents($l_FileName);
	$l_FileCRC = realCRC($l_Content);
	if ($l_FileCRC != $l_CRC) {
		echo 'Доступ запрещен.';
		exit;
	}
	
	echo '<pre>' . htmlspecialchars($l_Content) . '</pre>';
}

/**
 *
 */
function realCRC($str_in, $full = false)
{
        $in = crc32( $full ? normal($str_in) : $str_in );
        return ($in > CRC32_LIMIT) ? ($in - CRC32_DIFF) : $in;
}


/**
 * Determine php script is called from the command line interface
 * @return bool
 */
function isCli()
{
	return php_sapi_name() == 'cli';
}

function myCheckSum($str) {
   return hash('crc32b', $str);
}

 function generatePassword ($length = 9)
  {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

  }

/**
 * Print to console
 * @param mixed $text
 * @param bool $add_lb Add line break
 * @return void
 */
function stdOut($text, $add_lb = true)
{
	if (!isCli())
		return;
		
	if (is_bool($text))
	{
		$text = $text ? 'true' : 'false';
	}
	else if (is_null($text))
	{
		$text = 'null';
	}
	if (!is_scalar($text))
	{
		$text = print_r($text, true);
	}

 	if (!BOOL_RESULT)
 	{
 		@fwrite(STDOUT, $text . ($add_lb ? "\n" : ''));
 	}
}

/**
 * Print progress
 * @param int $num Current file
 */
function printProgress($num, &$par_File)
{
	global $g_CriticalPHP, $g_Base64, $g_Phishing, $g_CriticalJS, $g_Iframer, $g_UpdatedJsonLog, 
               $g_AddPrefix, $g_NoPrefix;

	$total_files = $GLOBALS['g_FoundTotalFiles'];
	$elapsed_time = microtime(true) - START_TIME;
	$percent = number_format($total_files ? $num * 100 / $total_files : 0, 1);
	$stat = '';
	if ($elapsed_time >= 1)
	{
		$elapsed_seconds = round($elapsed_time, 0);
		$fs = floor($num / $elapsed_seconds);
		$left_files = $total_files - $num;
		if ($fs > 0) 
		{
		   $left_time = ($left_files / $fs); //ceil($left_files / $fs);
		   $stat = ' [Avg: ' . round($fs,2) . ' files/s' . ($left_time > 0  ? ' Left: ' . seconds2Human($left_time) : '') . '] [Mlw:' . (count($g_CriticalPHP) + count($g_Base64))  . '|' . (count($g_CriticalJS) + count($g_Iframer) + count($g_Phishing)) . ']';
        }
	}

        $l_FN = $g_AddPrefix . str_replace($g_NoPrefix, '', $par_File); 
	$l_FN = substr($par_File, -60);

	$text = "$percent% [$l_FN] $num of {$total_files}. " . $stat;
	$text = str_pad($text, 160, ' ', STR_PAD_RIGHT);
	stdOut(str_repeat(chr(8), 160) . $text, false);


      	$data = array('self' => __FILE__, 'started' => AIBOLIT_START_TIME, 'updated' => time(), 
                            'progress' => $percent, 'time_elapsed' => $elapsed_seconds, 
                            'time_left' => round($left_time), 'files_left' => $left_files, 
                            'files_total' => $total_files, 'current_file' => substr($g_AddPrefix . str_replace($g_NoPrefix, '', $par_File), -160));

        if (function_exists('aibolit_onProgressUpdate')) { aibolit_onProgressUpdate($data); }

	if (defined('PROGRESS_LOG_FILE') && 
           (time() - $g_UpdatedJsonLog > 1)) {
                if (function_exists('json_encode')) {
             	   file_put_contents(PROGRESS_LOG_FILE, json_encode($data));
                } else {
             	   file_put_contents(PROGRESS_LOG_FILE, serialize($data));
                }

		$g_UpdatedJsonLog = time();
        }
}

/**
 * Seconds to human readable
 * @param int $seconds
 * @return string
 */
function seconds2Human($seconds)
{
	$r = '';
	$_seconds = floor($seconds);
	$ms = $seconds - $_seconds;
	$seconds = $_seconds;
	if ($hours = floor($seconds / 3600))
	{
		$r .= $hours . (isCli() ? ' h ' : ' час ');
		$seconds = $seconds % 3600;
	}

	if ($minutes = floor($seconds / 60))
	{
		$r .= $minutes . (isCli() ? ' m ' : ' мин ');
		$seconds = $seconds % 60;
	}

	if ($minutes < 3) $r .= ' ' . $seconds + ($ms > 0 ? round($ms) : 0) . (isCli() ? ' s' : ' сек'); 

	return $r;
}

if (isCli())
{

	$cli_options = array(
		'm:' => 'memory:',
		's:' => 'size:',
		'a' => 'all',
		'd:' => 'delay:',
		'l:' => 'list:',
		'r:' => 'report:',
		'f' => 'fast',
		'j:' => 'file:',
		'p:' => 'path:',
		'q' => 'quite',
		'e:' => 'cms:',
		'x:' => 'mode:',
		'k:' => 'skip:',
		'i:' => 'idb:',
		'n' => 'sc',
		'o:' => 'json_report:',
		't:' => 'php_report:',
		'z:' => 'progress:',
		'g:' => 'handler:',
		'b' => 'smart',
		'h' => 'help',
	);

	$cli_longopts = array(
		'cmd:',
		'noprefix:',
		'addprefix:',
		'scan:',
		'one-pass',
		'smart',
		'quarantine',
		'with-2check',
		'skip-cache',
		'imake',
		'icheck'
	);
	
	$cli_longopts = array_merge($cli_longopts, array_values($cli_options));

	$options = getopt(implode('', array_keys($cli_options)), $cli_longopts);

	if (isset($options['h']) OR isset($options['help']))
	{
		$memory_limit = ini_get('memory_limit');
		echo <<<HELP
AI-Bolit - Professional Malware File Scanner.

Usage: php {$_SERVER['PHP_SELF']} [OPTIONS] [PATH]
Current default path is: {$defaults['path']}

  -j, --file=FILE      		Full path to single file to check
  -l, --list=FILE      		Full path to create plain text file with a list of found malware
  -o, --json_report=FILE	Full path to create json-file with a list of found malware
  -p, --path=PATH      		Directory path to scan, by default the file directory is used
                       		Current path: {$defaults['path']}
  -m, --memory=SIZE    		Maximum amount of memory a script may consume. Current value: $memory_limit
                       		Can take shorthand byte values (1M, 1G...)
  -s, --size=SIZE      		Scan files are smaller than SIZE. 0 - All files. Current value: {$defaults['max_size_to_scan']}
  -a, --all            		Scan all files (by default scan. js,. php,. html,. htaccess)
  -d, --delay=INT      		Delay in milliseconds when scanning files to reduce load on the file system (Default: 1)
  -x, --mode=INT       		Set scan mode. 0 - for basic, 1 - for expert and 2 for paranoic.
  -k, --skip=jpg,...   		Skip specific extensions. E.g. --skip=jpg,gif,png,xls,pdf
      --scan=php,...   		Scan only specific extensions. E.g. --scan=php,htaccess,js
  -r, --report=PATH/EMAILS
  -z, --progress=FILE  		Runtime progress of scanning, saved to the file, full path required. 
  -g, --hander=FILE    		External php handler for different events, full path to php file required.
      --cmd="command [args...]"
      --smart                   Enable smart mode (skip cache files and optimize scanning)
                       		Run command after scanning
      --one-pass       		Do not calculate remaining time
      --quarantine     		Archive all malware from report
      --with-2check    		Create or use AI-BOLIT-DOUBLECHECK.php file
      --imake
      --icheck
      --idb=file	   	Integrity Check database file

      --help           		Display this help and exit

* Mandatory arguments listed below are required for both full and short way of usage.

HELP;
		exit;
	}

	$l_FastCli = false;
	
	if (
		(isset($options['memory']) AND !empty($options['memory']) AND ($memory = $options['memory']))
		OR (isset($options['m']) AND !empty($options['m']) AND ($memory = $options['m']))
	)
	{
		$memory = getBytes($memory);
		if ($memory > 0)
		{
			$defaults['memory_limit'] = $memory;
			ini_set('memory_limit', $memory);
		}
	}

	if (
		(isset($options['file']) AND !empty($options['file']) AND ($file = $options['file']) !== false)
		OR (isset($options['j']) AND !empty($options['j']) AND ($file = $options['j']) !== false)
	)
	{
		define('SCAN_FILE', $file);
	}


	if (
		(isset($options['list']) AND !empty($options['list']) AND ($file = $options['list']) !== false)
		OR (isset($options['l']) AND !empty($options['l']) AND ($file = $options['l']) !== false)
	)
	{

		define('PLAIN_FILE', $file);
	}

	if (
		(isset($options['json_report']) AND !empty($options['json_report']) AND ($file = $options['json_report']) !== false)
		OR (isset($options['o']) AND !empty($options['o']) AND ($file = $options['o']) !== false)
	)
	{
		define('JSON_FILE', $file);
	}

	if (
		(isset($options['php_report']) AND !empty($options['php_report']) AND ($file = $options['php_report']) !== false)
		OR (isset($options['t']) AND !empty($options['t']) AND ($file = $options['t']) !== false)
	)
	{
		define('PHP_FILE', $file);
	}

	if (isset($options['smart']) OR isset($options['b']))
	{
		define('SMART_SCAN', 1);
	}

	if (
		(isset($options['handler']) AND !empty($options['handler']) AND ($file = $options['handler']) !== false)
		OR (isset($options['g']) AND !empty($options['g']) AND ($file = $options['g']) !== false)
	)
	{
	        if (file_exists($file)) {
		   define('AIBOLIT_EXTERNAL_HANDLER', $file);
                }
	}

	if (
		(isset($options['progress']) AND !empty($options['progress']) AND ($file = $options['progress']) !== false)
		OR (isset($options['z']) AND !empty($options['z']) AND ($file = $options['z']) !== false)
	)
	{
		define('PROGRESS_LOG_FILE', $file);
	}
	if (
		(isset($options['size']) AND !empty($options['size']) AND ($size = $options['size']) !== false)
		OR (isset($options['s']) AND !empty($options['s']) AND ($size = $options['s']) !== false)
	)
	{
		$size = getBytes($size);
		$defaults['max_size_to_scan'] = $size > 0 ? $size : 0;
	}

 	if (
 		(isset($options['file']) AND !empty($options['file']) AND ($file = $options['file']) !== false)
 		OR (isset($options['j']) AND !empty($options['j']) AND ($file = $options['j']) !== false)
 		AND (isset($options['q'])) 
 	
 	)
 	{
 		$BOOL_RESULT = true;
 	}
 
	if (isset($options['f'])) 
	{
	   $l_FastCli = true;
	}
		
	if (isset($options['q']) || isset($options['quite'])) 
	{
 	    $BOOL_RESULT = true;
	}

	define('BOOL_RESULT', $BOOL_RESULT);

	if (
		(isset($options['delay']) AND !empty($options['delay']) AND ($delay = $options['delay']) !== false)
		OR (isset($options['d']) AND !empty($options['d']) AND ($delay = $options['d']) !== false)
	)
	{
		$delay = (int) $delay;
		if (!($delay < 0))
		{
			$defaults['scan_delay'] = $delay;
		}
	}

	if (
		(isset($options['skip']) AND !empty($options['skip']) AND ($ext_list = $options['skip']) !== false)
		OR (isset($options['k']) AND !empty($options['k']) AND ($ext_list = $options['k']) !== false)
	)
	{
		$defaults['skip_ext'] = $ext_list;
	}

	if (isset($options['n']) OR isset($options['skip-cache']))
	{
		$defaults['skip_cache'] = true;
	}

	if (isset($options['all']) OR isset($options['a']))
	{
		$defaults['scan_all_files'] = 1;
	}

	if (isset($options['scan']))
	{
		$ext_list = strtolower(trim($options['scan'], " ,\t\n\r\0\x0B"));
		if ($ext_list != '')
		{
			$l_FastCli = true;
			$g_SensitiveFiles = explode(",", $ext_list);
			stdOut("Scan extensions: " . $ext_list);
			$g_SpecificExt = true;
		}
	}


    if (isset($options['cms'])) {
        define('CMS', $options['cms']);
    } else if (isset($options['e'])) {
        define('CMS', $options['e']);
    }

    if (isset($options['x'])) {
        define('AI_EXPERT', $options['x']);
    } else if (isset($options['mode'])) {
        define('AI_EXPERT', $options['mode']);
    } else {
		define('AI_EXPERT', AI_EXPERT_MODE); 
    }

    if (!defined('SMART_SCAN')) {
       define('SMART_SCAN', 1);
    }


	$l_SpecifiedPath = false;
	if (
		(isset($options['path']) AND !empty($options['path']) AND ($path = $options['path']) !== false)
		OR (isset($options['p']) AND !empty($options['p']) AND ($path = $options['p']) !== false)
	)
	{
		$defaults['path'] = $path;
		$l_SpecifiedPath = true;
	}

	if (
		isset($options['noprefix']) AND !empty($options['noprefix']) AND ($g_NoPrefix = $options['noprefix']) !== false)
		
	{
	} else {
		$g_NoPrefix = '';
	}

	if (
		isset($options['addprefix']) AND !empty($options['addprefix']) AND ($g_AddPrefix = $options['addprefix']) !== false)
		
	{
	} else {
		$g_AddPrefix = '';
	}



	$l_SuffixReport = str_replace('/var/www', '', $defaults['path']);
	$l_SuffixReport = str_replace('/home', '', $l_SuffixReport);
    $l_SuffixReport = preg_replace('#[/\\\.\s]#', '_', $l_SuffixReport);
	$l_SuffixReport .=  "-" . rand(1, 999999);
		
	if (
		(isset($options['report']) AND ($report = $options['report']) !== false)
		OR (isset($options['r']) AND ($report = $options['r']) !== false)
	)
	{
		$report = str_replace('@PATH@', $l_SuffixReport, $report);
		$report = str_replace('@RND@', rand(1, 999999), $report);
		$report = str_replace('@DATE@', date('d-m-Y-h-i'), $report);
		define('REPORT', $report);
		define('NEED_REPORT', true);
	}

	if (
		(isset($options['idb']) AND ($ireport = $options['idb']) !== false)
	)
	{
		$ireport = str_replace('@PATH@', $l_SuffixReport, $ireport);
		$ireport = str_replace('@RND@', rand(1, 999999), $ireport);
		$ireport = str_replace('@DATE@', date('d-m-Y-h-i'), $ireport);
		define('INTEGRITY_DB_FILE', $ireport);
	}

  
    $l_ReportDirName = dirname($report);
	define('QUEUE_FILENAME', ($l_ReportDirName != '' ? $l_ReportDirName . '/' : '') . 'AI-BOLIT-QUEUE-' . md5($defaults['path']) . '.txt');

	defined('REPORT') OR define('REPORT', 'AI-BOLIT-REPORT-' . $l_SuffixReport . '-' . date('d-m-Y_H-i') . '.html');
	
	defined('INTEGRITY_DB_FILE') OR define('INTEGRITY_DB_FILE', 'AINTEGRITY-' . $l_SuffixReport . '-' . date('d-m-Y_H-i'));

	$last_arg = max(1, sizeof($_SERVER['argv']) - 1);
	if (isset($_SERVER['argv'][$last_arg]))
	{
		$path = $_SERVER['argv'][$last_arg];
		if (
			substr($path, 0, 1) != '-'
			AND (substr($_SERVER['argv'][$last_arg - 1], 0, 1) != '-' OR array_key_exists(substr($_SERVER['argv'][$last_arg - 1], -1), $cli_options)))
		{
			$defaults['path'] = $path;
		}
	}	
	
	
	define('ONE_PASS', isset($options['one-pass']));

	define('IMAKE', isset($options['imake']));
	define('ICHECK', isset($options['icheck']));

	if (IMAKE && ICHECK) die('One of the following options must be used --imake or --icheck.');

} else {
   define('AI_EXPERT', AI_EXPERT_MODE); 
   define('ONE_PASS', true);
}

OptimizeSignatures();

$g_DBShe  = array_map('strtolower', $g_DBShe);
$gX_DBShe = array_map('strtolower', $gX_DBShe);

if (!defined('PLAIN_FILE')) { define('PLAIN_FILE', ''); }

// Init
define('MAX_ALLOWED_PHP_HTML_IN_DIR', 600);
define('BASE64_LENGTH', 69);
define('MAX_PREVIEW_LEN', 80);
define('MAX_EXT_LINKS', 1001);

if (defined('AIBOLIT_EXTERNAL_HANDLER')) {
   include_once(AIBOLIT_EXTERNAL_HANDLER);
   stdOut("\nLoaded external handler: " . AIBOLIT_EXTERNAL_HANDLER . "\n");
   if (function_exists("aibolit_onStart")) { aibolit_onStart(); }
}

// Perform full scan when running from command line
if (isCli() || isset($_GET['full'])) {
  $defaults['scan_all_files'] = 1;
}

if ($l_FastCli) {
  $defaults['scan_all_files'] = 0; 
}

if (!isCli()) {
  	define('ICHECK', isset($_GET['icheck']));
  	define('IMAKE', isset($_GET['imake']));
	
	define('INTEGRITY_DB_FILE', 'ai-integrity-db');
}

define('SCAN_ALL_FILES', (bool) $defaults['scan_all_files']);
define('SCAN_DELAY', (int) $defaults['scan_delay']);
define('MAX_SIZE_TO_SCAN', getBytes($defaults['max_size_to_scan']));

if ($defaults['memory_limit'] AND ($defaults['memory_limit'] = getBytes($defaults['memory_limit'])) > 0) {
	ini_set('memory_limit', $defaults['memory_limit']);
    stdOut("Changed memory limit to " . $defaults['memory_limit']);
}

define('START_TIME', microtime(true));

define('ROOT_PATH', realpath($defaults['path']));

if (!ROOT_PATH)
{
    if (isCli())  {
		die(stdOut("Directory '{$defaults['path']}' not found!"));
	}
}
elseif(!is_readable(ROOT_PATH))
{
        if (isCli())  {
		die2(stdOut("Cannot read directory '" . ROOT_PATH . "'!"));
	}
}

define('CURRENT_DIR', getcwd());
chdir(ROOT_PATH);

// Проверяем отчет
if (isCli() AND REPORT !== '' AND !getEmails(REPORT))
{
	$report = str_replace('\\', '/', REPORT);
	$abs = strpos($report, '/') === 0 ? DIR_SEPARATOR : '';
	$report = array_values(array_filter(explode('/', $report)));
	$report_file = array_pop($report);
	$report_path = realpath($abs . implode(DIR_SEPARATOR, $report));

	define('REPORT_FILE', $report_file);
	define('REPORT_PATH', $report_path);

	if (REPORT_FILE AND REPORT_PATH AND is_file(REPORT_PATH . DIR_SEPARATOR . REPORT_FILE))
	{
		@unlink(REPORT_PATH . DIR_SEPARATOR . REPORT_FILE);
	}
}


if (function_exists('phpinfo')) {
   ob_start();
   phpinfo();
   $l_PhpInfo = ob_get_contents();
   ob_end_clean();

   $l_PhpInfo = str_replace('border: 1px', '', $l_PhpInfo);
   preg_match('|<body>(.*)</body>|smi', $l_PhpInfo, $l_PhpInfoBody);
}

////////////////////////////////////////////////////////////////////////////
$l_Template = str_replace("@@MODE@@", AI_EXPERT . '/' . SMART_SCAN, $l_Template);

if (AI_EXPERT == 0) {
   $l_Result .= '<div class="rep">' . AI_STR_057 . '</div>'; 
} else {
}

$l_Template = str_replace('@@HEAD_TITLE@@', AI_STR_051 . $g_AddPrefix . str_replace($g_NoPrefix, '', ROOT_PATH), $l_Template);

define('QCR_INDEX_FILENAME', 'fn');
define('QCR_INDEX_TYPE', 'type');
define('QCR_INDEX_WRITABLE', 'wr');
define('QCR_SVALUE_FILE', '1');
define('QCR_SVALUE_FOLDER', '0');

/**
 * Extract emails from the string
 * @param string $email
 * @return array of strings with emails or false on error
 */
function getEmails($email)
{
	$email = preg_split('#[,\s;]#', $email, -1, PREG_SPLIT_NO_EMPTY);
	$r = array();
	for ($i = 0, $size = sizeof($email); $i < $size; $i++)
	{
	        if (function_exists('filter_var')) {
   		   if (filter_var($email[$i], FILTER_VALIDATE_EMAIL))
   		   {
   		   	$r[] = $email[$i];
    		   }
                } else {
                   // for PHP4
                   if (strpos($email[$i], '@') !== false) {
   		   	$r[] = $email[$i];
                   }
                }
	}
	return empty($r) ? false : $r;
}

/**
 * Get bytes from shorthand byte values (1M, 1G...)
 * @param int|string $val
 * @return int
 */
function getBytes($val)
{
	$val = trim($val);
	$last = strtolower($val{strlen($val) - 1});
	switch($last) {
		case 't':
			$val *= 1024;
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
	return intval($val);
}

/**
 * Format bytes to human readable
 * @param int $bites
 * @return string
 */
function bytes2Human($bites)
{
	if ($bites < 1024)
	{
		return $bites . ' b';
	}
	elseif (($kb = $bites / 1024) < 1024)
	{
		return number_format($kb, 2) . ' Kb';
	}
	elseif (($mb = $kb / 1024) < 1024)
	{
		return number_format($mb, 2) . ' Mb';
	}
	elseif (($gb = $mb / 1024) < 1024)
	{
		return number_format($gb, 2) . ' Gb';
	}
	else
	{
		return number_format($gb / 1024, 2) . 'Tb';
	}
}

///////////////////////////////////////////////////////////////////////////
function needIgnore($par_FN, $par_CRC) {
  global $g_IgnoreList;
  
  for ($i = 0; $i < count($g_IgnoreList); $i++) {
     if (strpos($par_FN, $g_IgnoreList[$i][0]) !== false) {
		if ($par_CRC == $g_IgnoreList[$i][1]) {
			return true;
		}
	 }
  }
  
  return false;
}

///////////////////////////////////////////////////////////////////////////
function makeSafeFn($par_Str, $replace_path = false) {
  global $g_AddPrefix, $g_NoPrefix;
  if ($replace_path) {
     $lines = explode("\n", $par_Str);
     array_walk($lines, function(&$n) {
          global $g_AddPrefix, $g_NoPrefix;
          $n = $g_AddPrefix . str_replace($g_NoPrefix, '', $n); 
     }); 

     $par_Str = implode("\n", $lines);
  }
 
  return htmlspecialchars($par_Str, ENT_SUBSTITUTE | ENT_QUOTES);
}

function replacePathArray($par_Arr) {
  global $g_AddPrefix, $g_NoPrefix;
     array_walk($par_Arr, function(&$n) {
          global $g_AddPrefix, $g_NoPrefix;
          $n = $g_AddPrefix . str_replace($g_NoPrefix, '', $n); 
     }); 

  return $par_Arr;
}

///////////////////////////////////////////////////////////////////////////
function getRawJsonVuln($par_List) {
  global $g_Structure, $g_NoPrefix, $g_AddPrefix;
   $results = array();
   $l_Src = array('&quot;', '&lt;', '&gt;', '&amp;', '&#039;', '<' . '?php.');
   $l_Dst = array('"',      '<',    '>',    '&', '\'',         '<' . '?php ');

   for ($i = 0; $i < count($par_List); $i++) {
      $l_Pos = $par_List[$i]['ndx'];
      $res['fn'] = $g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$l_Pos]);
      $res['sig'] = $par_List[$i]['id'];

      $res['ct'] = $g_Structure['c'][$l_Pos];
      $res['mt'] = $g_Structure['m'][$l_Pos];
      $res['sz'] = $g_Structure['s'][$l_Pos];
      $res['sigid'] = 'vuln_' . md5($g_Structure['n'][$l_Pos] . $par_List[$i]['id']);

      $results[] = $res; 
   }

   return $results;
}

///////////////////////////////////////////////////////////////////////////
function getRawJson($par_List, $par_Details = null, $par_SigId = null) {
  global $g_Structure, $g_NoPrefix, $g_AddPrefix;
   $results = array();
   $l_Src = array('&quot;', '&lt;', '&gt;', '&amp;', '&#039;', '<' . '?php.');
   $l_Dst = array('"',      '<',    '>',    '&', '\'',         '<' . '?php ');

   for ($i = 0; $i < count($par_List); $i++) {
       if ($par_SigId != null) {
          $l_SigId = 'id_' . $par_SigId[$i];
       } else {
          $l_SigId = 'id_n' . rand(1000000, 9000000);
       }
       


      $l_Pos = $par_List[$i];
      $res['fn'] = $g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$l_Pos]);
      if ($par_Details != null) {
         $res['sig'] = preg_replace('|(L\d+).+__AI_MARKER__|smi', '[$1]: ...', $par_Details[$i]);
         $res['sig'] = preg_replace('/[^\x20-\x7F]/', '.', $res['sig']);
         $res['sig'] = preg_replace('/__AI_LINE1__(\d+)__AI_LINE2__/', '[$1] ', $res['sig']);
         $res['sig'] = preg_replace('/__AI_MARKER__/', ' @!!!>', $res['sig']);
         $res['sig'] = str_replace($l_Src, $l_Dst, $res['sig']);
      }

      $res['ct'] = $g_Structure['c'][$l_Pos];
      $res['mt'] = $g_Structure['m'][$l_Pos];
      $res['sz'] = $g_Structure['s'][$l_Pos];
      $res['sigid'] = $l_SigId;

      $results[] = $res; 
   }

   return $results;
}

///////////////////////////////////////////////////////////////////////////
function printList($par_List, $par_Details = null, $par_NeedIgnore = false, $par_SigId = null, $par_TableName = null) {
  global $g_Structure, $g_NoPrefix, $g_AddPrefix;
  
  $i = 0;

  if ($par_TableName == null) {
     $par_TableName = 'table_' . rand(1000000,9000000);
  }

  $l_Result = '';
  $l_Result .= "<div class=\"flist\"><table cellspacing=1 cellpadding=4 border=0 id=\"" . $par_TableName . "\">";

  $l_Result .= "<thead><tr class=\"tbgh" . ( $i % 2 ). "\">";
  $l_Result .= "<th width=70%>" . AI_STR_004 . "</th>";
  $l_Result .= "<th>" . AI_STR_005 . "</th>";
  $l_Result .= "<th>" . AI_STR_006 . "</th>";
  $l_Result .= "<th width=90>" . AI_STR_007 . "</th>";
  $l_Result .= "<th width=0 class=\"hidd\">CRC32</th>";
  $l_Result .= "<th width=0 class=\"hidd\"></th>";
  $l_Result .= "<th width=0 class=\"hidd\"></th>";
  $l_Result .= "<th width=0 class=\"hidd\"></th>";
  
  $l_Result .= "</tr></thead><tbody>";

  for ($i = 0; $i < count($par_List); $i++) {
    if ($par_SigId != null) {
       $l_SigId = 'id_' . $par_SigId[$i];
    } else {
       $l_SigId = 'id_z' . rand(1000000,9000000);
    }
    
    $l_Pos = $par_List[$i];
        if ($par_NeedIgnore) {
         	if (needIgnore($g_Structure['n'][$par_List[$i]], $g_Structure['crc'][$l_Pos])) {
         		continue;
         	}
        }
  
     $l_Creat = $g_Structure['c'][$l_Pos] > 0 ? date("d/m/Y H:i:s", $g_Structure['c'][$l_Pos]) : '-';
     $l_Modif = $g_Structure['m'][$l_Pos] > 0 ? date("d/m/Y H:i:s", $g_Structure['m'][$l_Pos]) : '-';
     $l_Size = $g_Structure['s'][$l_Pos] > 0 ? bytes2Human($g_Structure['s'][$l_Pos]) : '-';

     if ($par_Details != null) {
        $l_WithMarker = preg_replace('|__AI_MARKER__|smi', '<span class="marker">&nbsp;</span>', $par_Details[$i]);
        $l_WithMarker = preg_replace('|__AI_LINE1__|smi', '<span class="line_no">', $l_WithMarker);
        $l_WithMarker = preg_replace('|__AI_LINE2__|smi', '</span>', $l_WithMarker);
		
        $l_Body = '<div class="details">';

        if ($par_SigId != null) {
           $l_Body .= '<a href="#" onclick="return hsig(\'' . $l_SigId . '\')">[x]</a> ';
        }

        $l_Body .= $l_WithMarker . '</div>';
     } else {
        $l_Body = '';
     }

     $l_Result .= '<tr class="tbg' . ( $i % 2 ). '" o="' . $l_SigId .'">';
	 
	 if (is_file($g_Structure['n'][$l_Pos])) {
//		$l_Result .= '<td><div class="it"><a class="it" target="_blank" href="'. $defaults['site_url'] . 'ai-bolit.php?fn=' .
//	              $g_Structure['n'][$l_Pos] . '&ph=' . realCRC(PASS) . '&c=' . $g_Structure['crc'][$l_Pos] . '">' . $g_Structure['n'][$l_Pos] . '</a></div>' . $l_Body . '</td>';
		$l_Result .= '<td><div class="it"><a class="it">' . makeSafeFn($g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$l_Pos])) . '</a></div>' . $l_Body . '</td>';
	 } else {
		$l_Result .= '<td><div class="it"><a class="it">' . makeSafeFn($g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$par_List[$i]])) . '</a></div></td>';
	 }
	 
     $l_Result .= '<td align=center><div class="ctd">' . $l_Creat . '</div></td>';
     $l_Result .= '<td align=center><div class="ctd">' . $l_Modif . '</div></td>';
     $l_Result .= '<td align=center><div class="ctd">' . $l_Size . '</div></td>';
     $l_Result .= '<td class="hidd"><div class="hidd">' . $g_Structure['crc'][$l_Pos] . '</div></td>';
     $l_Result .= '<td class="hidd"><div class="hidd">' . 'x' . '</div></td>';
     $l_Result .= '<td class="hidd"><div class="hidd">' . $g_Structure['m'][$l_Pos] . '</div></td>';
     $l_Result .= '<td class="hidd"><div class="hidd">' . $l_SigId . '</div></td>';
     $l_Result .= '</tr>';

  }

  $l_Result .= "</tbody></table></div><div class=clear style=\"margin: 20px 0 0 0\"></div>";

  return $l_Result;
}

///////////////////////////////////////////////////////////////////////////
function printPlainList($par_List, $par_Details = null, $par_NeedIgnore = false, $par_SigId = null, $par_TableName = null) {
  global $g_Structure, $g_NoPrefix, $g_AddPrefix;
  
  $l_Result = "";

  $l_Src = array('&quot;', '&lt;', '&gt;', '&amp;', '&#039;');
  $l_Dst = array('"',      '<',    '>',    '&', '\'');

  for ($i = 0; $i < count($par_List); $i++) {
    $l_Pos = $par_List[$i];
        if ($par_NeedIgnore) {
         	if (needIgnore($g_Structure['n'][$par_List[$i]], $g_Structure['crc'][$l_Pos])) {
         		continue;
         	}                      
        }
  

     if ($par_Details != null) {

        $l_Body = preg_replace('|(L\d+).+__AI_MARKER__|smi', '$1: ...', $par_Details[$i]);
        $l_Body = preg_replace('/[^\x20-\x7F]/', '.', $l_Body);
        $l_Body = str_replace($l_Src, $l_Dst, $l_Body);

     } else {
        $l_Body = '';
     }

	 if (is_file($g_Structure['n'][$l_Pos])) {		 
		$l_Result .= $g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$l_Pos]) . "\t\t\t" . $l_Body . "\n";
	 } else {
		$l_Result .= $g_AddPrefix . str_replace($g_NoPrefix, '', $g_Structure['n'][$par_List[$i]]) . "\n";
	 }
	 
  }

  return $l_Result;
}

///////////////////////////////////////////////////////////////////////////
function extractValue(&$par_Str, $par_Name) {
  if (preg_match('|<tr><td class="e">\s*'.$par_Name.'\s*</td><td class="v">(.+?)</td>|sm', $par_Str, $l_Result)) {
     return str_replace('no value', '', strip_tags($l_Result[1]));
  }
}

///////////////////////////////////////////////////////////////////////////
function QCR_ExtractInfo($par_Str) {
   $l_PhpInfoSystem = extractValue($par_Str, 'System');
   $l_PhpPHPAPI = extractValue($par_Str, 'Server API');
   $l_AllowUrlFOpen = extractValue($par_Str, 'allow_url_fopen');
   $l_AllowUrlInclude = extractValue($par_Str, 'allow_url_include');
   $l_DisabledFunction = extractValue($par_Str, 'disable_functions');
   $l_DisplayErrors = extractValue($par_Str, 'display_errors');
   $l_ErrorReporting = extractValue($par_Str, 'error_reporting');
   $l_ExposePHP = extractValue($par_Str, 'expose_php');
   $l_LogErrors = extractValue($par_Str, 'log_errors');
   $l_MQGPC = extractValue($par_Str, 'magic_quotes_gpc');
   $l_MQRT = extractValue($par_Str, 'magic_quotes_runtime');
   $l_OpenBaseDir = extractValue($par_Str, 'open_basedir');
   $l_RegisterGlobals = extractValue($par_Str, 'register_globals');
   $l_SafeMode = extractValue($par_Str, 'safe_mode');


   $l_DisabledFunction = ($l_DisabledFunction == '' ? '-?-' : $l_DisabledFunction);
   $l_OpenBaseDir = ($l_OpenBaseDir == '' ? '-?-' : $l_OpenBaseDir);

   $l_Result = '<div class="title">' . AI_STR_008 . ': ' . phpversion() . '</div>';
   $l_Result .= 'System Version: <span class="php_ok">' . $l_PhpInfoSystem . '</span><br/>';
   $l_Result .= 'PHP API: <span class="php_ok">' . $l_PhpPHPAPI. '</span><br/>';
   $l_Result .= 'allow_url_fopen: <span class="php_' . ($l_AllowUrlFOpen == 'On' ? 'bad' : 'ok') . '">' . $l_AllowUrlFOpen. '</span><br/>';
   $l_Result .= 'allow_url_include: <span class="php_' . ($l_AllowUrlInclude == 'On' ? 'bad' : 'ok') . '">' . $l_AllowUrlInclude. '</span><br/>';
   $l_Result .= 'disable_functions: <span class="php_' . ($l_DisabledFunction == '-?-' ? 'bad' : 'ok') . '">' . $l_DisabledFunction. '</span><br/>';
   $l_Result .= 'display_errors: <span class="php_' . ($l_DisplayErrors == 'On' ? 'ok' : 'bad') . '">' . $l_DisplayErrors. '</span><br/>';
   $l_Result .= 'error_reporting: <span class="php_ok">' . $l_ErrorReporting. '</span><br/>';
   $l_Result .= 'expose_php: <span class="php_' . ($l_ExposePHP == 'On' ? 'bad' : 'ok') . '">' . $l_ExposePHP. '</span><br/>';
   $l_Result .= 'log_errors: <span class="php_' . ($l_LogErrors == 'On' ? 'ok' : 'bad') . '">' . $l_LogErrors . '</span><br/>';
   $l_Result .= 'magic_quotes_gpc: <span class="php_' . ($l_MQGPC == 'On' ? 'ok' : 'bad') . '">' . $l_MQGPC. '</span><br/>';
   $l_Result .= 'magic_quotes_runtime: <span class="php_' . ($l_MQRT == 'On' ? 'bad' : 'ok') . '">' . $l_MQRT. '</span><br/>';
   $l_Result .= 'register_globals: <span class="php_' . ($l_RegisterGlobals == 'On' ? 'bad' : 'ok') . '">' . $l_RegisterGlobals . '</span><br/>';
   $l_Result .= 'open_basedir: <span class="php_' . ($l_OpenBaseDir == '-?-' ? 'bad' : 'ok') . '">' . $l_OpenBaseDir . '</span><br/>';
   
   if (phpversion() < '5.3.0') {
      $l_Result .= 'safe_mode (PHP < 5.3.0): <span class="php_' . ($l_SafeMode == 'On' ? 'ok' : 'bad') . '">' . $l_SafeMode. '</span><br/>';
   }

   return $l_Result . '<p>';
}

///////////////////////////////////////////////////////////////////////////
   function addSlash($dir) {
      return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
   }

///////////////////////////////////////////////////////////////////////////
function QCR_Debug($par_Str = "") {
  if (!DEBUG_MODE) {
     return;
  }

  $l_MemInfo = ' ';  
  if (function_exists('memory_get_usage')) {
     $l_MemInfo .= ' curmem=' .  bytes2Human(memory_get_usage());
  }

  if (function_exists('memory_get_peak_usage')) {
     $l_MemInfo .= ' maxmem=' .  bytes2Human(memory_get_peak_usage());
  }

  stdOut("\n" . date('H:i:s') . ': ' . $par_Str . $l_MemInfo . "\n");
}


///////////////////////////////////////////////////////////////////////////
function QCR_ScanDirectories($l_RootDir)
{
	global $g_Structure, $g_Counter, $g_Doorway, $g_FoundTotalFiles, $g_FoundTotalDirs, 
			$defaults, $g_SkippedFolders, $g_UrlIgnoreList, $g_DirIgnoreList, $g_UnsafeDirArray, 
                        $g_UnsafeFilesFound, $g_SymLinks, $g_HiddenFiles, $g_UnixExec, $g_IgnoredExt, $g_SensitiveFiles, 
						$g_SuspiciousFiles, $g_ShortListExt, $l_SkipSample;

	static $l_Buffer = '';

	$l_DirCounter = 0;
	$l_DoorwayFilesCounter = 0;
	$l_SourceDirIndex = $g_Counter - 1;

        $l_SkipSample = array();

	QCR_Debug('Scan ' . $l_RootDir);

        $l_QuotedSeparator = quotemeta(DIR_SEPARATOR); 
 	if ($l_DIRH = @opendir($l_RootDir))
	{
		while (($l_FileName = readdir($l_DIRH)) !== false)
		{
			if ($l_FileName == '.' || $l_FileName == '..') continue;

			$l_FileName = $l_RootDir . DIR_SEPARATOR . $l_FileName;

			$l_Type = filetype($l_FileName);
            if ($l_Type == "link") 
            {
                $g_SymLinks[] = $l_FileName;
                continue;
            } else			
			if ($l_Type != "file" && $l_Type != "dir" ) {
			        if (!in_array($l_FileName, $g_UnixExec)) {
				   $g_UnixExec[] = $l_FileName;
				}

				continue;
			}	
						
			$l_Ext = strtolower(pathinfo($l_FileName, PATHINFO_EXTENSION));
			$l_IsDir = is_dir($l_FileName);

			if (in_array($l_Ext, $g_SuspiciousFiles)) 
			{
			        if (!in_array($l_FileName, $g_UnixExec)) {
                		   $g_UnixExec[] = $l_FileName;
                                } 
            		}

			// which files should be scanned
			$l_NeedToScan = SCAN_ALL_FILES || (in_array($l_Ext, $g_SensitiveFiles));
			
			if (in_array(strtolower($l_Ext), $g_IgnoredExt)) {    
		           $l_NeedToScan = false;
                        }

      			// if folder in ignore list
      			$l_Skip = false;
      			for ($dr = 0; $dr < count($g_DirIgnoreList); $dr++) {
      				if (($g_DirIgnoreList[$dr] != '') &&
      				   preg_match('#' . $g_DirIgnoreList[$dr] . '#', $l_FileName, $l_Found)) {
      				   if (!in_array($g_DirIgnoreList[$dr], $l_SkipSample)) {
                                      $l_SkipSample[] = $g_DirIgnoreList[$dr];
                                   } else {
        		             $l_Skip = true;
                                     $l_NeedToScan = false;
                                   }
      				}
      			}


			if ($l_IsDir)
			{
				// skip on ignore
				if ($l_Skip) {
				   $g_SkippedFolders[] = $l_FileName;
				   continue;
				}
				
				$l_BaseName = basename($l_FileName);

				if ((strpos($l_BaseName, '.') === 0) && ($l_BaseName != '.htaccess')) {
	               $g_HiddenFiles[] = $l_FileName;
	            }

//				$g_Structure['d'][$g_Counter] = $l_IsDir;
//				$g_Structure['n'][$g_Counter] = $l_FileName;
				if (ONE_PASS) {
					$g_Structure['n'][$g_Counter] = $l_FileName . DIR_SEPARATOR;
				} else {
					$l_Buffer .= $l_FileName . DIR_SEPARATOR . "\n";
				}

				$l_DirCounter++;

				if ($l_DirCounter > MAX_ALLOWED_PHP_HTML_IN_DIR)
				{
					$g_Doorway[] = $l_SourceDirIndex;
					$l_DirCounter = -655360;
				}

				$g_Counter++;
				$g_FoundTotalDirs++;

				QCR_ScanDirectories($l_FileName);
			} else
			{
				if ($l_NeedToScan)
				{
					$g_FoundTotalFiles++;
					if (in_array($l_Ext, $g_ShortListExt)) 
					{
						$l_DoorwayFilesCounter++;
						
						if ($l_DoorwayFilesCounter > MAX_ALLOWED_PHP_HTML_IN_DIR)
						{
							$g_Doorway[] = $l_SourceDirIndex;
							$l_DoorwayFilesCounter = -655360;
						}
					}

					if (ONE_PASS) {
						QCR_ScanFile($l_FileName, $g_Counter++);
					} else {
						$l_Buffer .= $l_FileName."\n";
					}

					$g_Counter++;
				}
			}

			if (strlen($l_Buffer) > 32000)
			{ 
				file_put_contents(QUEUE_FILENAME, $l_Buffer, FILE_APPEND) or die2("Cannot write to file ".QUEUE_FILENAME);
				$l_Buffer = '';
			}

		}

		closedir($l_DIRH);
	}
	
	if (($l_RootDir == ROOT_PATH) && !empty($l_Buffer)) {
		file_put_contents(QUEUE_FILENAME, $l_Buffer, FILE_APPEND) or die2("Cannot write to file " . QUEUE_FILENAME);
		$l_Buffer = '';                                                                            
	}

}


///////////////////////////////////////////////////////////////////////////
function getFragment($par_Content, $par_Pos) {
  $l_MaxChars = MAX_PREVIEW_LEN;
  $l_MaxLen = strlen($par_Content);
  $l_RightPos = min($par_Pos + $l_MaxChars, $l_MaxLen); 
  $l_MinPos = max(0, $par_Pos - $l_MaxChars);

  $l_FoundStart = substr($par_Content, 0, $par_Pos);
  $l_FoundStart = str_replace("\r", '', $l_FoundStart);
  $l_LineNo = strlen($l_FoundStart) - strlen(str_replace("\n", '', $l_FoundStart)) + 1;

  $par_Content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '~', $par_Content);
  $par_Content = preg_replace('/\s+/', ' ', $par_Content);

  $l_Res = '__AI_LINE1__' . $l_LineNo . "__AI_LINE2__  " . ($l_MinPos > 0 ? '…' : '') . substr($par_Content, $l_MinPos, $par_Pos - $l_MinPos) . 
           '__AI_MARKER__' . 
           substr($par_Content, $par_Pos, $l_RightPos - $par_Pos - 1);

  $l_Res = makeSafeFn(UnwrapObfu($l_Res));
  $l_Res = str_replace('~', '·', $l_Res);
  $l_Res = str_replace('' . '?php', '' . '?php ', $l_Res);

  return $l_Res;
}

///////////////////////////////////////////////////////////////////////////
function escapedHexToHex($escaped)
{ $GLOBALS['g_EncObfu']++; return chr(hexdec($escaped[1])); }
function escapedOctDec($escaped)
{ $GLOBALS['g_EncObfu']++; return chr(octdec($escaped[1])); }
function escapedDec($escaped)
{ $GLOBALS['g_EncObfu']++; return chr($escaped[1]); }

///////////////////////////////////////////////////////////////////////////
if (!defined('T_ML_COMMENT')) {
   define('T_ML_COMMENT', T_COMMENT);
} else {
   define('T_DOC_COMMENT', T_ML_COMMENT);
}
          	
function UnwrapObfu($par_Content) {
  $GLOBALS['g_EncObfu'] = 0;
  
  $search  = array( ' ;', ' =', ' ,', ' .', ' (', ' )', ' {', ' }', '; ', '= ', ', ', '. ', '( ', '( ', '{ ', '} ', ' !', ' >', ' <', ' _', '_ ', '< ',  '> ', ' $', ' %',   '% ', '# ', ' #', '^ ', ' ^', ' &', '& ', ' ?', '? ');
  $replace = array(  ';',  '=',  ',',  '.',  '(',  ')',  '{',  '}', ';',  '=',  ',',  '.',  '(',   ')', '{',  '}',   '!',  '>',  '<',  '_', '_',  '<',   '>',   '$',  '%',   '%',  '#',   '#', '^',   '^',  '&', '&',   '?', '?');
  $par_Content = str_replace('@', '', $par_Content);
  $par_Content = preg_replace('~(\(\s*)+~', '(', $par_Content);
  $par_Content = preg_replace('~\s+~', ' ', $par_Content);
  $par_Content = str_replace($search, $replace, $par_Content);
  $par_Content = preg_replace_callback('~\bchr\(\s*([0-9a-fA-FxX]+)\s*\)~', function ($m) { return "'".chr(intval($m[1], 0))."'"; }, $par_Content );

  $par_Content = preg_replace_callback('/\\\\x([a-fA-F0-9]{1,2})/i','escapedHexToHex', $par_Content);
  $par_Content = preg_replace_callback('/\\\\([0-9]{1,3})/i','escapedOctDec', $par_Content);

  $par_Content = preg_replace('/[\'"]\s*?\.+\s*?[\'"]/smi', '', $par_Content);
  $par_Content = preg_replace('/[\'"]\s*?\++\s*?[\'"]/smi', '', $par_Content);

  return $par_Content;
}

///////////////////////////////////////////////////////////////////////////
// Unicode BOM is U+FEFF, but after encoded, it will look like this.
define ('UTF32_BIG_ENDIAN_BOM'   , chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF));
define ('UTF32_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00));
define ('UTF16_BIG_ENDIAN_BOM'   , chr(0xFE) . chr(0xFF));
define ('UTF16_LITTLE_ENDIAN_BOM', chr(0xFF) . chr(0xFE));
define ('UTF8_BOM'               , chr(0xEF) . chr(0xBB) . chr(0xBF));

function detect_utf_encoding($text) {
    $first2 = substr($text, 0, 2);
    $first3 = substr($text, 0, 3);
    $first4 = substr($text, 0, 3);
    
    if ($first3 == UTF8_BOM) return 'UTF-8';
    elseif ($first4 == UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
    elseif ($first4 == UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
    elseif ($first2 == UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
    elseif ($first2 == UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';

    return false;
}

///////////////////////////////////////////////////////////////////////////
function QCR_SearchPHP($src)
{
  if (preg_match("/(<\?php[\w\s]{5,})/smi", $src, $l_Found, PREG_OFFSET_CAPTURE)) {
	  return $l_Found[0][1];
  }

  if (preg_match("/(<script[^>]*language\s*=\s*)('|\"|)php('|\"|)([^>]*>)/i", $src, $l_Found, PREG_OFFSET_CAPTURE)) {
    return $l_Found[0][1];
  }

  return false;
}


///////////////////////////////////////////////////////////////////////////
function knowUrl($par_URL) {
  global $g_UrlIgnoreList;

  for ($jk = 0; $jk < count($g_UrlIgnoreList); $jk++) {
     if  (stripos($par_URL, $g_UrlIgnoreList[$jk]) !== false) {
     	return true;
     }
  }

  return false;
}

///////////////////////////////////////////////////////////////////////////

function makeSummary($par_Str, $par_Number, $par_Style) {
   return '<tr><td class="' . $par_Style . '" width=400>' . $par_Str . '</td><td class="' . $par_Style . '">' . $par_Number . '</td></tr>';
}

///////////////////////////////////////////////////////////////////////////

function CheckVulnerability($par_Filename, $par_Index, $par_Content) {
    global $g_Vulnerable, $g_CmsListDetector;
	
	$l_Vuln = array();

        $par_Filename = strtolower($par_Filename);


	if (
	    (strpos($par_Filename, 'libraries/joomla/session/session.php') !== false) &&
		(strpos($par_Content, '&& filter_var($_SERVER[\'HTTP_X_FORWARDED_FOR') === false)
		) 
	{		
			$l_Vuln['id'] = 'RCE : https://docs.joomla.org/Security_hotfixes_for_Joomla_EOL_versions';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
	}

	if (
	    (strpos($par_Filename, 'administrator/components/com_media/helpers/media.php') !== false) &&
		(strpos($par_Content, '$format == \'\' || $format == false ||') === false)
		) 
	{		
		if ($g_CmsListDetector->isCms(CMS_JOOMLA, '1.5')) {
			$l_Vuln['id'] = 'AFU : https://docs.joomla.org/Security_hotfixes_for_Joomla_EOL_versions';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (
	    (strpos($par_Filename, 'joomla/filesystem/file.php') !== false) &&
		(strpos($par_Content, '$file = rtrim($file, \'.\');') === false)
		) 
	{		
		if ($g_CmsListDetector->isCms(CMS_JOOMLA, '1.5')) {
			$l_Vuln['id'] = 'AFU : https://docs.joomla.org/Security_hotfixes_for_Joomla_EOL_versions';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if ((strpos($par_Filename, 'editor/filemanager/upload/test.html') !== false) ||
		(stripos($par_Filename, 'editor/filemanager/browser/default/connectors/php/') !== false) ||
		(stripos($par_Filename, 'editor/filemanager/connectors/uploadtest.html') !== false) ||
	   (strpos($par_Filename, 'editor/filemanager/browser/default/connectors/test.html') !== false)) {
		$l_Vuln['id'] = 'AFU : FCKEDITOR : http://www.exploit-db.com/exploits/17644/ & /exploit/249';
		$l_Vuln['ndx'] = $par_Index;
		$g_Vulnerable[] = $l_Vuln;
		return true;
	}

	if ((strpos($par_Filename, 'inc_php/image_view.class.php') !== false) ||
	    (strpos($par_Filename, '/inc_php/framework/image_view.class.php') !== false)) {
		if (strpos($par_Content, 'showImageByID') === false) {
			$l_Vuln['id'] = 'AFU : REVSLIDER : http://www.exploit-db.com/exploits/35385/';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if ((strpos($par_Filename, 'elfinder/php/connector.php') !== false) ||
	    (strpos($par_Filename, 'elfinder/elfinder.') !== false)) {
			$l_Vuln['id'] = 'AFU : elFinder';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
	}

	if (strpos($par_Filename, 'includes/database/database.inc') !== false) {
		if (strpos($par_Content, 'foreach ($data as $i => $value)') !== false) {
			$l_Vuln['id'] = 'SQLI : DRUPAL : CVE-2014-3704';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'engine/classes/min/index.php') !== false) {
		if (strpos($par_Content, 'tr_replace(chr(0)') === false) {
			$l_Vuln['id'] = 'AFD : MINIFY : CVE-2013-6619';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (( strpos($par_Filename, 'timthumb.php') !== false ) || 
	    ( strpos($par_Filename, 'thumb.php') !== false ) || 
	    ( strpos($par_Filename, 'cache.php') !== false ) || 
	    ( strpos($par_Filename, '_img.php') !== false )) {
		if (strpos($par_Content, 'code.google.com/p/timthumb') !== false && strpos($par_Content, '2.8.14') === false ) {
			$l_Vuln['id'] = 'RCE : TIMTHUMB : CVE-2011-4106,CVE-2014-4663';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'components/com_rsform/helpers/rsform.php') !== false) {
		if (strpos($par_Content, 'eval($form->ScriptDisplay);') !== false) {
			$l_Vuln['id'] = 'RCE : RSFORM : rsform.php, LINE 1605';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'fancybox-for-wordpress/fancybox.php') !== false) {
		if (strpos($par_Content, '\'reset\' == $_REQUEST[\'action\']') !== false) {
			$l_Vuln['id'] = 'CODE INJECTION : FANCYBOX';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}


	if (strpos($par_Filename, 'cherry-plugin/admin/import-export/upload.php') !== false) {
		if (strpos($par_Content, 'verify nonce') === false) {
			$l_Vuln['id'] = 'AFU : Cherry Plugin';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}
	
	
	if (strpos($par_Filename, 'tiny_mce/plugins/tinybrowser/tinybrowser.php') !== false) {	
		$l_Vuln['id'] = 'AFU : TINYMCE : http://www.exploit-db.com/exploits/9296/';
		$l_Vuln['ndx'] = $par_Index;
		$g_Vulnerable[] = $l_Vuln;
		
		return true;
	}

	if (strpos($par_Filename, '/bx_1c_import.php') !== false) {	
		if (strpos($par_Content, '$_GET[\'action\']=="getfiles"') !== false) {
   		   $l_Vuln['id'] = 'AFD : https://habrahabr.ru/company/dsec/blog/326166/';
   		   $l_Vuln['ndx'] = $par_Index;
   		   $g_Vulnerable[] = $l_Vuln;
   		
   		   return true;
                }
	}

	if (strpos($par_Filename, 'scripts/setup.php') !== false) {		
		if (strpos($par_Content, 'PMA_Config') !== false) {
			$l_Vuln['id'] = 'CODE INJECTION : PHPMYADMIN : http://1337day.com/exploit/5334';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, '/uploadify.php') !== false) {		
		if (strpos($par_Content, 'move_uploaded_file($tempFile,$targetFile') !== false) {
			$l_Vuln['id'] = 'AFU : UPLOADIFY : CVE: 2012-1153';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'com_adsmanager/controller.php') !== false) {		
		if (strpos($par_Content, 'move_uploaded_file($file[\'tmp_name\'], $tempPath.\'/\'.basename($file[') !== false) {
			$l_Vuln['id'] = 'AFU : https://revisium.com/ru/blog/adsmanager_afu.html';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'wp-content/plugins/wp-mobile-detector/resize.php') !== false) {		
		if (strpos($par_Content, 'file_put_contents($path, file_get_contents($_REQUEST[\'src\']));') !== false) {
			$l_Vuln['id'] = 'AFU : https://www.pluginvulnerabilities.com/2016/05/31/aribitrary-file-upload-vulnerability-in-wp-mobile-detector/';
			$l_Vuln['ndx'] = $par_Index;
			$g_Vulnerable[] = $l_Vuln;
			return true;
		}
		
		return false;
	}

	if (strpos($par_Filename, 'phpmailer.php') !== false) {		
		if (strpos($par_Content, 'PHPMailer') !== false) {
                        $l_Found = preg_match('~Version:\s*(\d+)\.(\d+)\.(\d+)~', $par_Content, $l_Match);

                        if ($l_Found) {
                           $l_Version = $l_Match[1] * 1000 + $l_Match[2] * 100 + $l_Match[3];

                           if ($l_Version < 2520) {
                              $l_Found = false;
                           }
                        }

                        if (!$l_Found) {

                           $l_Found = preg_match('~Version\s*=\s*\'(\d+)\.*(\d+)\.(\d+)~', $par_Content, $l_Match);
                           if ($l_Found) {
                              $l_Version = $l_Match[1] * 1000 + $l_Match[2] * 100 + $l_Match[3];
                              if ($l_Version < 5220) {
                                 $l_Found = false;
                              }
                           }
			}


		        if (!$l_Found) {
	   		   $l_Vuln['id'] = 'RCE : CVE-2016-10045, CVE-2016-10031';
			   $l_Vuln['ndx'] = $par_Index;
			   $g_Vulnerable[] = $l_Vuln;
			   return true;
                        }
		}
		
		return false;
	}




}

///////////////////////////////////////////////////////////////////////////
function QCR_GoScan($par_Offset)
{
	global $g_IframerFragment, $g_Iframer, $g_Redirect, $g_Doorway, $g_EmptyLink, $g_Structure, $g_Counter, 
		   $g_HeuristicType, $g_HeuristicDetected, $g_TotalFolder, $g_TotalFiles, $g_WarningPHP, $g_AdwareList,
		   $g_CriticalPHP, $g_Phishing, $g_CriticalJS, $g_UrlIgnoreList, $g_CriticalJSFragment, $g_PHPCodeInside, $g_PHPCodeInsideFragment, 
		   $g_NotRead, $g_WarningPHPFragment, $g_WarningPHPSig, $g_BigFiles, $g_RedirectPHPFragment, $g_EmptyLinkSrc, $g_CriticalPHPSig, $g_CriticalPHPFragment, 
           $g_Base64Fragment, $g_UnixExec, $g_PhishingSigFragment, $g_PhishingFragment, $g_PhishingSig, $g_CriticalJSSig, $g_IframerFragment, $g_CMS, $defaults, $g_AdwareListFragment, $g_KnownList,$g_Vulnerable;

    QCR_Debug('QCR_GoScan ' . $par_Offset);

	$i = 0;
	
	try {
		$s_file = new SplFileObject(QUEUE_FILENAME);
		$s_file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

		foreach ($s_file as $l_Filename) {
			QCR_ScanFile($l_Filename, $i++);
		}
		
		unset($s_file);	
	}
	catch (Exception $e) { QCR_Debug( $e->getMessage() ); }
}

///////////////////////////////////////////////////////////////////////////
function QCR_ScanFile($l_Filename, $i = 0)
{
	global $g_IframerFragment, $g_Iframer, $g_Redirect, $g_Doorway, $g_EmptyLink, $g_Structure, $g_Counter, 
		   $g_HeuristicType, $g_HeuristicDetected, $g_TotalFolder, $g_TotalFiles, $g_WarningPHP, $g_AdwareList,
		   $g_CriticalPHP, $g_Phishing, $g_CriticalJS, $g_UrlIgnoreList, $g_CriticalJSFragment, $g_PHPCodeInside, $g_PHPCodeInsideFragment, 
		   $g_NotRead, $g_WarningPHPFragment, $g_WarningPHPSig, $g_BigFiles, $g_RedirectPHPFragment, $g_EmptyLinkSrc, $g_CriticalPHPSig, $g_CriticalPHPFragment, 
           $g_Base64Fragment, $g_UnixExec, $g_PhishingSigFragment, $g_PhishingFragment, $g_PhishingSig, $g_CriticalJSSig, $g_IframerFragment, $g_CMS, $defaults, $g_AdwareListFragment, 
           $g_KnownList,$g_Vulnerable, $g_CriticalFiles;

	global $g_CRC;
	static $_files_and_ignored = 0;

			$l_CriticalDetected = false;
			$l_Stat = stat($l_Filename);

			if (substr($l_Filename, -1) == DIR_SEPARATOR) {
				// FOLDER
				$g_Structure['n'][$i] = $l_Filename;
				$g_TotalFolder++;
				printProgress($_files_and_ignored, $l_Filename);
				return;
			}

			QCR_Debug('Scan file ' . $l_Filename);
			printProgress(++$_files_and_ignored, $l_Filename);

			// FILE
			if ((MAX_SIZE_TO_SCAN > 0 AND $l_Stat['size'] > MAX_SIZE_TO_SCAN) || ($l_Stat['size'] < 0))
			{
				$g_BigFiles[] = $i;

                                if (function_exists('aibolit_onBigFile')) { aibolit_onBigFile($l_Filename); }

				AddResult($l_Filename, $i);

		                $l_Ext = strtolower(pathinfo($l_Filename, PATHINFO_EXTENSION));
                                if ((!AI_HOSTER) && in_array($l_Ext, $g_CriticalFiles)) {
				    $g_CriticalPHP[] = $i;
				    $g_CriticalPHPFragment[] = "BIG FILE. SKIPPED.";
				    $g_CriticalPHPSig[] = "big_1";
                                }
			}
			else
			{
				$g_TotalFiles++;

			$l_TSStartScan = microtime(true);

		$l_Ext = strtolower(pathinfo($l_Filename, PATHINFO_EXTENSION));
		if (filetype($l_Filename) == 'file') {
                   $l_Content = @file_get_contents($l_Filename);
		   if (SHORT_PHP_TAG) {
//                      $l_Content = preg_replace('|<\?\s|smiS', '<?php ', $l_Content); 
                   }

                   $l_Unwrapped = @php_strip_whitespace($l_Filename);
                }

		
                if ((($l_Content == '') || ($l_Unwrapped == '')) && ($l_Stat['size'] > 0)) {
                   $g_NotRead[] = $i;
                   if (function_exists('aibolit_onReadError')) { aibolit_onReadError($l_Filename, 'io'); }
                   AddResult('[io] ' . $l_Filename, $i);
                   return;
                }

				// ignore itself
				if (strpos($l_Content, '264215fae8356a91afdd3514567f7f50') !== false) {
					return;
				}

				// unix executables
				if (strpos($l_Content, chr(127) . 'ELF') !== false) 
				{
			        	if (!in_array($l_Filename, $g_UnixExec)) {
                    				$g_UnixExec[] = $l_Filename;
					}

				        return;
                		}

				$g_CRC = _hash_($l_Unwrapped);

				$l_UnicodeContent = detect_utf_encoding($l_Content);
				//$l_Unwrapped = $l_Content;

				// check vulnerability in files
				$l_CriticalDetected = CheckVulnerability($l_Filename, $i, $l_Content);				

				if ($l_UnicodeContent !== false) {
       				   if (function_exists('iconv')) {
				      $l_Unwrapped = iconv($l_UnicodeContent, "CP1251//IGNORE", $l_Unwrapped);
//       			   if (function_exists('mb_convert_encoding')) {
//                                    $l_Unwrapped = mb_convert_encoding($l_Unwrapped, $l_UnicodeContent, "CP1251");
                                   } else {
                                      $g_NotRead[] = $i;
                                      if (function_exists('aibolit_onReadError')) { aibolit_onReadError($l_Filename, 'ec'); }
                                      AddResult('[ec] ' . $l_Filename, $i);
				   }
                                }

				$l_Unwrapped = UnwrapObfu($l_Unwrapped);
				
				// critical
				$g_SkipNextCheck = false;

				if (CriticalPHP($l_Filename, $i, $l_Unwrapped, $l_Pos, $l_SigId))
				{
				        if ($l_Ext == 'js') {
 					   $g_CriticalJS[] = $i;
 					   $g_CriticalJSFragment[] = getFragment($l_Unwrapped, $l_Pos);
 					   $g_CriticalJSSig[] = $l_SigId;
                                        } else {
       					   $g_CriticalPHP[] = $i;
       					   $g_CriticalPHPFragment[] = getFragment($l_Unwrapped, $l_Pos);
      					   $g_CriticalPHPSig[] = $l_SigId;
                                        }

					$g_SkipNextCheck = true;
				} else {
         				if (CriticalPHP($l_Filename, $i, $l_Content, $l_Pos, $l_SigId))
         				{
					        if ($l_Ext == 'js') {
         					   $g_CriticalJS[] = $i;
         					   $g_CriticalJSFragment[] = getFragment($l_Content, $l_Pos);
         					   $g_CriticalJSSig[] = $l_SigId;
                                                } else {
               					   $g_CriticalPHP[] = $i;
               					   $g_CriticalPHPFragment[] = getFragment($l_Content, $l_Pos);
      						   $g_CriticalPHPSig[] = $l_SigId;
                                                }

         					$g_SkipNextCheck = true;
         				}
				}

				$l_TypeDe = 0;
			    if ((!$g_SkipNextCheck) && HeuristicChecker($l_Content, $l_TypeDe, $l_Filename)) {
					$g_HeuristicDetected[] = $i;
					$g_HeuristicType[] = $l_TypeDe;
					$l_CriticalDetected = true;
				}

				// critical JS
				if (!$g_SkipNextCheck) {
					$l_Pos = CriticalJS($l_Filename, $i, $l_Unwrapped, $l_SigId);
					if ($l_Pos !== false)
					{
					        if ($l_Ext == 'js') {
         					   $g_CriticalJS[] = $i;
         					   $g_CriticalJSFragment[] = getFragment($l_Unwrapped, $l_Pos);
         					   $g_CriticalJSSig[] = $l_SigId;
                                                } else {
               					   $g_CriticalPHP[] = $i;
               					   $g_CriticalPHPFragment[] = getFragment($l_Unwrapped, $l_Pos);
      						   $g_CriticalPHPSig[] = $l_SigId;
                                                }

						$g_SkipNextCheck = true;
					}
			    }

				// phishing
				if (!$g_SkipNextCheck) {
					$l_Pos = Phishing($l_Filename, $i, $l_Unwrapped, $l_SigId);
					if ($l_Pos === false) {
                                            $l_Pos = Phishing($l_Filename, $i, $l_Content, $l_SigId);
                                        }

					if ($l_Pos !== false)
					{
						$g_Phishing[] = $i;
						$g_PhishingFragment[] = getFragment($l_Unwrapped, $l_Pos);
						$g_PhishingSigFragment[] = $l_SigId;
						$g_SkipNextCheck = true;
					}
				}

			
			if (!$g_SkipNextCheck) {
				if (SCAN_ALL_FILES || stripos($l_Filename, 'index.'))
				{
					// check iframes
					if (preg_match_all('|<iframe[^>]+src.+?>|smi', $l_Unwrapped, $l_Found, PREG_SET_ORDER)) 
					{
						for ($kk = 0; $kk < count($l_Found); $kk++) {
						    $l_Pos = stripos($l_Found[$kk][0], 'http://');
						    $l_Pos = $l_Pos || stripos($l_Found[$kk][0], 'https://');
						    $l_Pos = $l_Pos || stripos($l_Found[$kk][0], 'ftp://');
							if  (($l_Pos !== false ) && (!knowUrl($l_Found[$kk][0]))) {
         						$g_Iframer[] = $i;
         						$g_IframerFragment[] = getFragment($l_Found[$kk][0], $l_Pos);
         						$l_CriticalDetected = true;
							}
						}
					}

					// check empty links
					if ((($defaults['report_mask'] & REPORT_MASK_SPAMLINKS) == REPORT_MASK_SPAMLINKS) &&
					   (preg_match_all('|<a[^>]+href([^>]+?)>(.*?)</a>|smi', $l_Unwrapped, $l_Found, PREG_SET_ORDER)))
					{
						for ($kk = 0; $kk < count($l_Found); $kk++) {
							if  ((stripos($l_Found[$kk][1], 'http://') !== false) &&
                                                            (trim(strip_tags($l_Found[$kk][2])) == '')) {

								$l_NeedToAdd = true;

							    if  ((stripos($l_Found[$kk][1], $defaults['site_url']) !== false)
                                                                 || knowUrl($l_Found[$kk][1])) {
										$l_NeedToAdd = false;
								}
								
								if ($l_NeedToAdd && (count($g_EmptyLink) < MAX_EXT_LINKS)) {
									$g_EmptyLink[] = $i;
									$g_EmptyLinkSrc[$i][] = substr($l_Found[$kk][0], 0, MAX_PREVIEW_LEN);
									$l_CriticalDetected = true;
								}
							}
						}
					}
				}

				// check for PHP code inside any type of file
				if (stripos($l_Ext, 'ph') === false)
				{
					$l_Pos = QCR_SearchPHP($l_Content);
					if ($l_Pos !== false)
					{
						$g_PHPCodeInside[] = $i;
						$g_PHPCodeInsideFragment[] = getFragment($l_Unwrapped, $l_Pos);
						$l_CriticalDetected = true;
					}
				}

				// htaccess
				if (stripos($l_Filename, '.htaccess'))
				{
				
					if (stripos($l_Content, 'index.php?name=$1') !== false ||
						stripos($l_Content, 'index.php?m=1') !== false
					)
					{
						$g_SuspDir[] = $i;
					}

					$l_HTAContent = preg_replace('|^\s*#.+$|m', '', $l_Content);

					$l_Pos = stripos($l_Content, 'auto_prepend_file');
					if ($l_Pos !== false) {
						$g_Redirect[] = $i;
						$g_RedirectPHPFragment[] = getFragment($l_Content, $l_Pos);
						$l_CriticalDetected = true;
					}
					
					$l_Pos = stripos($l_Content, 'auto_append_file');
					if ($l_Pos !== false) {
						$g_Redirect[] = $i;
						$g_RedirectPHPFragment[] = getFragment($l_Content, $l_Pos);
						$l_CriticalDetected = true;
					}

					$l_Pos = stripos($l_Content, '^(%2d|-)[^=]+$');
					if ($l_Pos !== false)
					{
						$g_Redirect[] = $i;
                        			$g_RedirectPHPFragment[] = getFragment($l_Content, $l_Pos);
						$l_CriticalDetected = true;
					}

					if (!$l_CriticalDetected) {
						$l_Pos = stripos($l_Content, '%{HTTP_USER_AGENT}');
						if ($l_Pos !== false)
						{
							$g_Redirect[] = $i;
							$g_RedirectPHPFragment[] = getFragment($l_Content, $l_Pos);
							$l_CriticalDetected = true;
						}
					}

					if (!$l_CriticalDetected) {
						if (
							preg_match_all("|RewriteRule\s+.+?\s+http://(.+?)/.+\s+\[.*R=\d+.*\]|smi", $l_HTAContent, $l_Found, PREG_SET_ORDER)
						)
						{
							$l_Host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
							for ($j = 0; $j < sizeof($l_Found); $j++)
							{
								$l_Found[$j][1] = str_replace('www.', '', $l_Found[$j][1]);
								if ($l_Found[$j][1] != $l_Host)
								{
									$g_Redirect[] = $i;
									$l_CriticalDetected = true;
									break;
								}
							}
						}
					}

					unset($l_HTAContent);
			    }
			

			    // warnings
				$l_Pos = '';
				
			    if (WarningPHP($l_Filename, $l_Unwrapped, $l_Pos, $l_SigId))
				{       
					$l_Prio = 1;
					if (strpos($l_Filename, '.ph') !== false) {
					   $l_Prio = 0;
					}
					
					$g_WarningPHP[$l_Prio][] = $i;
					$g_WarningPHPFragment[$l_Prio][] = getFragment($l_Unwrapped, $l_Pos);
					$g_WarningPHPSig[] = $l_SigId;

					$l_CriticalDetected = true;
				}
				

				// adware
				if (Adware($l_Filename, $l_Unwrapped, $l_Pos))
				{
					$g_AdwareList[] = $i;
					$g_AdwareListFragment[] = getFragment($l_Unwrapped, $l_Pos);
					$l_CriticalDetected = true;
				}

				// articles
				if (stripos($l_Filename, 'article_index'))
				{
					$g_AdwareList[] = $i;
					$l_CriticalDetected = true;
				}
			}
		} // end of if (!$g_SkipNextCheck) {
			
			unset($l_Unwrapped);
			unset($l_Content);
			
			//printProgress(++$_files_and_ignored, $l_Filename);

			$l_TSEndScan = microtime(true);
                        if ($l_TSEndScan - $l_TSStartScan >= 0.5) {
			   			   usleep(SCAN_DELAY * 1000);
                        }

			if ($g_SkipNextCheck || $l_CriticalDetected) {
				AddResult($l_Filename, $i);
			}
}

function AddResult($l_Filename, $i)
{
	global $g_Structure, $g_CRC;
	
	$l_Stat = stat($l_Filename);
	$g_Structure['n'][$i] = $l_Filename;
	$g_Structure['s'][$i] = $l_Stat['size'];
	$g_Structure['c'][$i] = $l_Stat['ctime'];
	$g_Structure['m'][$i] = $l_Stat['mtime'];
	$g_Structure['crc'][$i] = $g_CRC;
}

///////////////////////////////////////////////////////////////////////////
function WarningPHP($l_FN, $l_Content, &$l_Pos, &$l_SigId)
{
	   global $g_SusDB,$g_ExceptFlex, $gXX_FlexDBShe, $gX_FlexDBShe, $g_FlexDBShe, $gX_DBShe, $g_DBShe, $g_Base64, $g_Base64Fragment;

  $l_Res = false;

  if (AI_EXTRA_WARN) {
  	foreach ($g_SusDB as $l_Item) {
    	if (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
       	 	if (!CheckException($l_Content, $l_Found)) {
           	 	$l_Pos = $l_Found[0][1];
           	 	//$l_SigId = myCheckSum($l_Item);
           	 	$l_SigId = getSigId($l_Found);
           	 	return true;
       	 	}
    	}
  	}
  }

  if (AI_EXPERT < 2) {
    	foreach ($gXX_FlexDBShe as $l_Item) {
      		if (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
             	$l_Pos = $l_Found[0][1];
           	    //$l_SigId = myCheckSum($l_Item);
           	    $l_SigId = getSigId($l_Found);
        	    return true;
	  		}
    	}

	}

    if (AI_EXPERT < 1) {
    	foreach ($gX_FlexDBShe as $l_Item) {
      		if (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
             	$l_Pos = $l_Found[0][1];
           	 	//$l_SigId = myCheckSum($l_Item);
           	 	$l_SigId = getSigId($l_Found);
        	    return true;
	  		}
    	}

	    $l_Content_lo = strtolower($l_Content);

	    foreach ($gX_DBShe as $l_Item) {
	      $l_Pos = strpos($l_Content_lo, $l_Item);
	      if ($l_Pos !== false) {
	         $l_SigId = myCheckSum($l_Item);
	         return true;
	      }
		}
	}

}

///////////////////////////////////////////////////////////////////////////
function Adware($l_FN, $l_Content, &$l_Pos)
{
  global $g_AdwareSig;

  $l_Res = false;

foreach ($g_AdwareSig as $l_Item) {
    $offset = 0;
    while (preg_match('#' . $l_Item . '#smi', $l_Content, $l_Found, PREG_OFFSET_CAPTURE, $offset)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
           return true;
       }

       $offset = $l_Found[0][1] + 1;
    }
  }

  return $l_Res;
}

///////////////////////////////////////////////////////////////////////////
function CheckException(&$l_Content, &$l_Found) {
  global $g_ExceptFlex, $gX_FlexDBShe, $gXX_FlexDBShe, $g_FlexDBShe, $gX_DBShe, $g_DBShe, $g_Base64, $g_Base64Fragment;
   $l_FoundStrPlus = substr($l_Content, max($l_Found[0][1] - 10, 0), 70);

   foreach ($g_ExceptFlex as $l_ExceptItem) {
      if (@preg_match('#' . $l_ExceptItem . '#smi', $l_FoundStrPlus, $l_Detected)) {
//         print("\n\nEXCEPTION FOUND\n[" . $l_ExceptItem .  "]\n" . $l_Content . "\n\n----------\n\n");
         return true;
      }
   }

   return false;
}

///////////////////////////////////////////////////////////////////////////
function Phishing($l_FN, $l_Index, $l_Content, &$l_SigId)
{
  global $g_PhishingSig, $g_PhishFiles, $g_PhishEntries;

  $l_Res = false;

  // need check file (by extension) ?
  $l_SkipCheck = SMART_SCAN;

if ($l_SkipCheck) {
  	foreach($g_PhishFiles as $l_Ext) {
  		  if (strpos($l_FN, $l_Ext) !== false) {
		  			$l_SkipCheck = false;
		  		  	break;
  	  	  }
  	  }
  }

  // need check file (by signatures) ?
  if ($l_SkipCheck && preg_match('~' . $g_PhishEntries . '~smiS', $l_Content, $l_Found)) {
	  $l_SkipCheck = false;
  }

  if ($l_SkipCheck && SMART_SCAN) {
      if (DEBUG_MODE) {
         echo "Skipped phs file, not critical.\n";
      }

	  return false;
  }


  foreach ($g_PhishingSig as $l_Item) {
    $offset = 0;
    while (preg_match('#' . $l_Item . '#smi', $l_Content, $l_Found, PREG_OFFSET_CAPTURE, $offset)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
//           $l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "Phis: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return $l_Pos;
       }
       $offset = $l_Found[0][1] + 1;

    }
  }

  return $l_Res;
}

///////////////////////////////////////////////////////////////////////////
function CriticalJS($l_FN, $l_Index, $l_Content, &$l_SigId)
{
  global $g_JSVirSig, $gX_JSVirSig, $g_VirusFiles, $g_VirusEntries;

  $l_Res = false;
  
    // need check file (by extension) ?
    $l_SkipCheck = SMART_SCAN;
	
	if ($l_SkipCheck) {
    	foreach($g_VirusFiles as $l_Ext) {
    		  if (strpos($l_FN, $l_Ext) !== false) {
  		  			$l_SkipCheck = false;
  		  		  	break;
    	  	  }
    	  }
	  }
  
    // need check file (by signatures) ?
    if ($l_SkipCheck && preg_match('~' . $g_VirusEntries . '~smiS', $l_Content, $l_Found)) {
  	  $l_SkipCheck = false;
    }
  
    if ($l_SkipCheck && SMART_SCAN) {
        if (DEBUG_MODE) {
           echo "Skipped js file, not critical.\n";
        }

  	  return false;
    }
  

  foreach ($g_JSVirSig as $l_Item) {
    $offset = 0;
    while (preg_match('#' . $l_Item . '#smi', $l_Content, $l_Found, PREG_OFFSET_CAPTURE, $offset)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
//           $l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "JS: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return $l_Pos;
       }

       $offset = $l_Found[0][1] + 1;

    }

//   if (pcre_error($l_FN, $l_Index)) {  }

  }

if (AI_EXPERT > 1) {
  foreach ($gX_JSVirSig as $l_Item) {
    if (preg_match('#' . $l_Item . '#smi', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
           //$l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "JS PARA: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return $l_Pos;
       }
    }

//   if (pcre_error($l_FN, $l_Index)) {  }

  }
}

  return $l_Res;
}

////////////////////////////////////////////////////////////////////////////
function pcre_error($par_FN, $par_Index) {
   global $g_NotRead, $g_Structure;

   $err = preg_last_error();
   if (($err == PREG_BACKTRACK_LIMIT_ERROR) || ($err == PREG_RECURSION_LIMIT_ERROR)) {
      if (!in_array($par_Index, $g_NotRead)) {
         if (function_exists('aibolit_onReadError')) { aibolit_onReadError($l_Filename, 're'); }
         $g_NotRead[] = $par_Index;
         AddResult('[re] ' . $par_FN, $par_Index);
      }
 
      return true;
   }

   return false;
}



////////////////////////////////////////////////////////////////////////////
define('SUSP_MTIME', 1); // suspicious mtime (greater than ctime)
define('SUSP_PERM', 2); // suspicious permissions 
define('SUSP_PHP_IN_UPLOAD', 3); // suspicious .php file in upload or image folder 

  function get_descr_heur($type) {
     switch ($type) {
	     case SUSP_MTIME: return AI_STR_077; 
	     case SUSP_PERM: return AI_STR_078;  
	     case SUSP_PHP_IN_UPLOAD: return AI_STR_079; 
	 }
	 
	 return "---";
  }

  ///////////////////////////////////////////////////////////////////////////
  function HeuristicChecker($l_Content, &$l_Type, $l_Filename) {
     $res = false;
	 
	 $l_Stat = stat($l_Filename);
	 // most likely changed by touch
	 if ($l_Stat['ctime'] < $l_Stat['mtime']) {
	     $l_Type = SUSP_MTIME;
		 return true;
	 }

	 	 
	 $l_Perm = fileperms($l_Filename) & 0777;
	 if (($l_Perm & 0400 != 0400) || // not readable by owner
		($l_Perm == 0000) ||
		($l_Perm == 0404) ||
		($l_Perm == 0505))
	 {
		 $l_Type = SUSP_PERM;
		 return true;
	 }

	 
     if ((strpos($l_Filename, '.ph')) && (
	     strpos($l_Filename, '/images/stories/') ||
	     //strpos($l_Filename, '/img/') ||
		 //strpos($l_Filename, '/images/') ||
	     //strpos($l_Filename, '/uploads/') ||
		 strpos($l_Filename, '/wp-content/upload/') 
	    )	    
	 ) {
		$l_Type = SUSP_PHP_IN_UPLOAD;
	 	return true;
	 }

     return false;
  }

///////////////////////////////////////////////////////////////////////////
function CriticalPHP($l_FN, $l_Index, $l_Content, &$l_Pos, &$l_SigId)
{
  global $g_ExceptFlex, $gXX_FlexDBShe, $gX_FlexDBShe, $g_FlexDBShe, $gX_DBShe, $g_DBShe, $g_Base64, $g_Base64Fragment,
  $g_CriticalFiles, $g_CriticalEntries;

  // 264215fae8356a91afdd3514567f7f50 H24LKHGHCGHFHGKJHGKJHGGGHJ

  // need check file (by extension) ?
  $l_SkipCheck = SMART_SCAN;

  if ($l_SkipCheck) {
	  foreach($g_CriticalFiles as $l_Ext) {
  	  	if ((strpos($l_FN, $l_Ext) !== false) && (strpos($l_FN, '.js') === false)) {
		   $l_SkipCheck = false;
		   break;
  	  	}
  	  }
  }
  
  // need check file (by signatures) ?
  if ($l_SkipCheck && preg_match('~' . $g_CriticalEntries . '~smiS', $l_Content, $l_Found)) {
     $l_SkipCheck = false;
  }
  
  
  // if not critical - skip it 
  if ($l_SkipCheck && SMART_SCAN) {
      if (DEBUG_MODE) {
         echo "Skipped file, not critical.\n";
      }

	  return false;
  }

  foreach ($g_FlexDBShe as $l_Item) {
    $offset = 0;
    while (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE, $offset)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
           //$l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "CRIT 1: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return true;
       }

       $offset = $l_Found[0][1] + 1;

    }

//   if (pcre_error($l_FN, $l_Index)) {  }

  }

if (AI_EXPERT > 0) {
  foreach ($gX_FlexDBShe as $l_Item) {
    if (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
           //$l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "CRIT 3: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return true;
       }
    }

//   if (pcre_error($l_FN, $l_Index)) {  }
  }
}

if (AI_EXPERT > 1) {
  foreach ($gXX_FlexDBShe as $l_Item) {
    if (preg_match('#' . $l_Item . '#smiS', $l_Content, $l_Found, PREG_OFFSET_CAPTURE)) {
       if (!CheckException($l_Content, $l_Found)) {
           $l_Pos = $l_Found[0][1];
           //$l_SigId = myCheckSum($l_Item);
           $l_SigId = getSigId($l_Found);

           if (DEBUG_MODE) {
              echo "CRIT 2: $l_FN matched [$l_Item] in $l_Pos\n";
           }

           return true;
       }
    }

//   if (pcre_error($l_FN, $l_Index)) {  }
  }
}

  $l_Content_lo = strtolower($l_Content);

  foreach ($g_DBShe as $l_Item) {
    $l_Pos = strpos($l_Content_lo, $l_Item);
    if ($l_Pos !== false) {
       $l_SigId = myCheckSum($l_Item);

       if (DEBUG_MODE) {
          echo "CRIT 4: $l_FN matched [$l_Item] in $l_Pos\n";
       }

       return true;
    }
  }

if (AI_EXPERT > 0) {
  foreach ($gX_DBShe as $l_Item) {
    $l_Pos = strpos($l_Content_lo, $l_Item);
    if ($l_Pos !== false) {
       $l_SigId = myCheckSum($l_Item);

       if (DEBUG_MODE) {
          echo "CRIT 5: $l_FN matched [$l_Item] in $l_Pos\n";
       }

       return true;
    }
  }
}

if (AI_HOSTER) return false;

if (AI_EXPERT > 0) {
  if ((strpos($l_Content, 'GIF89') === 0) && (strpos($l_FN, '.php') !== false )) {
     $l_Pos = 0;

     if (DEBUG_MODE) {
          echo "CRIT 6: $l_FN matched [$l_Item] in $l_Pos\n";
     }

     return true;
  }
}

  // detect uploaders / droppers
if (AI_EXPERT > 1) {
  $l_Found = null;
  if (
     (filesize($l_FN) < 1024) &&
     (strpos($l_FN, '.ph') !== false) &&
     (
       (($l_Pos = strpos($l_Content, 'multipart/form-data')) > 0) || 
       (($l_Pos = strpos($l_Content, '$_FILE[') > 0)) ||
       (($l_Pos = strpos($l_Content, 'move_uploaded_file')) > 0) ||
       (preg_match('|\bcopy\s*\(|smi', $l_Content, $l_Found, PREG_OFFSET_CAPTURE))
     )
     ) {
       if ($l_Found != null) {
          $l_Pos = $l_Found[0][1];
       } 
     if (DEBUG_MODE) {
          echo "CRIT 7: $l_FN matched [$l_Item] in $l_Pos\n";
     }

     return true;
  }
}

  return false;
}

///////////////////////////////////////////////////////////////////////////
if (!isCli()) {
   header('Content-type: text/html; charset=utf-8');
}

if (!isCli()) {

  $l_PassOK = false;
  if (strlen(PASS) > 8) {
     $l_PassOK = true;   
  } 

  if ($l_PassOK && preg_match('|[0-9]|', PASS, $l_Found) && preg_match('|[A-Z]|', PASS, $l_Found) && preg_match('|[a-z]|', PASS, $l_Found) ) {
     $l_PassOK = true;   
  }
  
  if (!$l_PassOK) {  
    echo sprintf(AI_STR_009, generatePassword());
    exit;
  }

  if (isset($_GET['fn']) && ($_GET['ph'] == crc32(PASS))) {
     printFile();
     exit;
  }

  if ($_GET['p'] != PASS) {
    $generated_pass = generatePassword(); 
    echo sprintf(AI_STR_010, $generated_pass, $generated_pass);
    exit;
  }
}

if (!is_readable(ROOT_PATH)) {
  echo AI_STR_011;
  exit;
}

if (isCli()) {
	if (defined('REPORT_PATH') AND REPORT_PATH)
	{
		if (!is_writable(REPORT_PATH))
		{
			die2("\nCannot write report. Report dir " . REPORT_PATH . " is not writable.");
		}

		else if (!REPORT_FILE)
		{
			die2("\nCannot write report. Report filename is empty.");
		}

		else if (($file = REPORT_PATH . DIR_SEPARATOR . REPORT_FILE) AND is_file($file) AND !is_writable($file))
		{
			die2("\nCannot write report. Report file '$file' exists but is not writable.");
		}
	}
}


// detect version CMS
$g_KnownCMS = array();
$tmp_cms = array();
$g_CmsListDetector = new CmsVersionDetector(ROOT_PATH);
$l_CmsDetectedNum = $g_CmsListDetector->getCmsNumber();
for ($tt = 0; $tt < $l_CmsDetectedNum; $tt++) {
    $g_CMS[] = $g_CmsListDetector->getCmsName($tt) . ' v' . makeSafeFn($g_CmsListDetector->getCmsVersion($tt));
    $tmp_cms[strtolower($g_CmsListDetector->getCmsName($tt))] = 1;
}

if (count($tmp_cms) > 0) {
   $g_KnownCMS = array_keys($tmp_cms);
   $len = count($g_KnownCMS);
   for ($i = 0; $i < $len; $i++) {
      if ($g_KnownCMS[$i] == strtolower(CMS_WORDPRESS)) $g_KnownCMS[] = 'wp';
      if ($g_KnownCMS[$i] == strtolower(CMS_WEBASYST)) $g_KnownCMS[] = 'shopscript';
      if ($g_KnownCMS[$i] == strtolower(CMS_IPB)) $g_KnownCMS[] = 'ipb';
      if ($g_KnownCMS[$i] == strtolower(CMS_DLE)) $g_KnownCMS[] = 'dle';
      if ($g_KnownCMS[$i] == strtolower(CMS_INSTANTCMS)) $g_KnownCMS[] = 'instantcms';
      if ($g_KnownCMS[$i] == strtolower(CMS_SHOPSCRIPT)) $g_KnownCMS[] = 'shopscript';
   }
}


$g_DirIgnoreList = array();
$g_IgnoreList = array();
$g_UrlIgnoreList = array();
$g_KnownList = array();

$l_IgnoreFilename = $g_AiBolitAbsolutePath . '/.aignore';
$l_DirIgnoreFilename = $g_AiBolitAbsolutePath . '/.adirignore';
$l_UrlIgnoreFilename = $g_AiBolitAbsolutePath . '/.aurlignore';
$l_KnownFilename = '.aknown';

if (file_exists($l_IgnoreFilename)) {
    $l_IgnoreListRaw = file($l_IgnoreFilename);
    for ($i = 0; $i < count($l_IgnoreListRaw); $i++) 
    {
    	$g_IgnoreList[] = explode("\t", trim($l_IgnoreListRaw[$i]));
    }
    unset($l_IgnoreListRaw);
}

if (file_exists($l_DirIgnoreFilename)) {
    $g_DirIgnoreList = file($l_DirIgnoreFilename);
	
	for ($i = 0; $i < count($g_DirIgnoreList); $i++) {
		$g_DirIgnoreList[$i] = trim($g_DirIgnoreList[$i]);
	}
}

if (file_exists($l_UrlIgnoreFilename)) {
    $g_UrlIgnoreList = file($l_UrlIgnoreFilename);
	
	for ($i = 0; $i < count($g_UrlIgnoreList); $i++) {
		$g_UrlIgnoreList[$i] = trim($g_UrlIgnoreList[$i]);
	}
}


$l_SkipMask = array(
            '/template_\w{32}.css',
            '/cache/templates/.{1,150}\.tpl\.php',
	    '/system/cache/templates_c/\w{1,40}\.php',
	    '/assets/cache/rss/\w{1,60}',
            '/cache/minify/minify_\w{32}',
            '/cache/page/\w{32}\.php',
            '/cache/object/\w{1,10}/\w{1,10}/\w{1,10}/\w{32}\.php',
            '/cache/wp-cache-\d{32}\.php',
            '/cache/page/\w{32}\.php_expire',
	    '/cache/page/\w{32}-cache-page-\w{32}\.php',
	    '\w{32}-cache-com_content-\w{32}\.php',
	    '\w{32}-cache-mod_custom-\w{32}\.php',
	    '\w{32}-cache-mod_templates-\w{32}\.php',
            '\w{32}-cache-_system-\w{32}\.php',
            '/cache/twig/\w{1,32}/\d+/\w{1,100}\.php', 
            '/autoptimize/js/autoptimize_\w{32}\.js',
            '/bitrix/cache/\w{32}\.php',
            '/bitrix/cache/.+/\w{32}\.php',
            '/bitrix/cache/iblock_find/',
            '/bitrix/managed_cache/MYSQL/user_option/[^/]+/',
            '/bitrix/cache/s1/bitrix/catalog\.section/',
            '/bitrix/cache/s1/bitrix/catalog\.element/',
            '/bitrix/cache/s1/bitrix/menu/',
            '/catalog.element/[^/]+/[^/]+/\w{32}\.php',
            '/bitrix/managed\_cache/.*/\.\w{32}\.php',
            '/core/cache/mgr/smarty/default/.{1,100}\.tpl\.php',
            '/core/cache/resource/web/resources/[0-9]{1,50}\.cache\.php',
            '/smarty/compiled/SC/.*/%%.*\.php',
            '/smarty/.{1,150}\.tpl\.php',
            '/smarty/compile/.{1,150}\.tpl\.cache\.php',
            '/files/templates_c/.{1,150}\.html\.php',
            '/uploads/javascript_global/.{1,150}\.js',
            '/assets/cache/rss/\w{32}',
	    '/assets/cache/docid_\d+_\w{32}\.pageCache\.php',
            '/t3-assets/dev/t3/.*-cache-\w{1,20}-.{1,150}\.php',
	    '/t3-assets/js/js-\w{1,30}\.js',
            '/temp/cache/SC/.*/\.cache\..*\.php',
            '/tmp/sess\_\w{32}$',
            '/assets/cache/docid\_.*\.pageCache\.php',
            '/stat/usage\_\w+\.html',
            '/stat/site\_\w+\.html',
            '/gallery/item/list/\w+\.cache\.php',
            '/core/cache/registry/.*/ext-.*\.php',
            '/core/cache/resource/shk\_/\w+\.cache\.php',
            '/webstat/awstats.*\.txt',
            '/awstats/awstats.*\.txt',
            '/awstats/.{1,80}\.pl',
            '/awstats/.{1,80}\.html',
            '/inc/min/styles_\w+\.min\.css',
            '/inc/min/styles_\w+\.min\.js',
            '/logs/error\_log\..*',
            '/logs/xferlog\..*',
            '/logs/access_log\..*',
            '/logs/cron\..*',
            '/logs/exceptions/.+\.log$',
            '/hyper-cache/[^/]+/[^/]+/[^/]+/index\.html',
            '/mail/new/[^,]+,S=[^,]+,W=.+',
            '/mail/new/[^,]=,S=.+',
            '/application/logs/\d+/\d+/\d+\.php',
            '/sites/default/files/js/js_\w{32}\.js',
            '/yt-assets/\w{32}\.css',
);

$l_SkipSample = array();

if (SMART_SCAN) {
   $g_DirIgnoreList = array_merge($g_DirIgnoreList, $l_SkipMask);
}

QCR_Debug();

// Load custom signatures

try {
	$s_file = new SplFileObject($g_AiBolitAbsolutePath."/ai-bolit.sig");
	$s_file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
	foreach ($s_file as $line) {
		$g_FlexDBShe[] = preg_replace('~\G(?:[^#\\\\]+|\\\\.)*+\K#~', '\\#', $line); // escaping #
	}
	stdOut("Loaded " . $s_file->key() . " signatures from ai-bolit.sig");
	$s_file = null; // file handler is closed
} catch (Exception $e) { QCR_Debug( "Import ai-bolit.sig " . $e->getMessage() ); }

QCR_Debug();

	$defaults['skip_ext'] = strtolower(trim($defaults['skip_ext']));
         if ($defaults['skip_ext'] != '') {
	    $g_IgnoredExt = explode(',', $defaults['skip_ext']);
	    for ($i = 0; $i < count($g_IgnoredExt); $i++) {
                $g_IgnoredExt[$i] = trim($g_IgnoredExt[$i]);
             }

	    QCR_Debug('Skip files with extensions: ' . implode(',', $g_IgnoredExt));
	    stdOut('Skip extensions: ' . implode(',', $g_IgnoredExt));
         } 

// scan single file
if (defined('SCAN_FILE')) {
   if (file_exists(SCAN_FILE) && is_file(SCAN_FILE) && is_readable(SCAN_FILE)) {
       stdOut("Start scanning file '" . SCAN_FILE . "'.");
       QCR_ScanFile(SCAN_FILE); 
   } else { 
       stdOut("Error:" . SCAN_FILE . " either is not a file or readable");
   }
} else {
	if (isset($_GET['2check'])) {
		$options['with-2check'] = 1;
	}
   
   // scan list of files from file
   if (!(ICHECK || IMAKE) && isset($options['with-2check']) && file_exists(DOUBLECHECK_FILE)) {
      stdOut("Start scanning the list from '" . DOUBLECHECK_FILE . "'.\n");
      $lines = file(DOUBLECHECK_FILE);
      for ($i = 0, $size = count($lines); $i < $size; $i++) {
         $lines[$i] = trim($lines[$i]);
         if (empty($lines[$i])) unset($lines[$i]);
      }
      /* skip first line with <?php die("Forbidden"); ?> */
      unset($lines[0]);
      $g_FoundTotalFiles = count($lines);
      $i = 1;
      foreach ($lines as $l_FN) {
         is_dir($l_FN) && $g_TotalFolder++;
         printProgress( $i++, $l_FN);
         $BOOL_RESULT = true; // display disable
         is_file($l_FN) && QCR_ScanFile($l_FN, $i);
         $BOOL_RESULT = false; // display enable
      }

      $g_FoundTotalDirs = $g_TotalFolder;
      $g_FoundTotalFiles = $g_TotalFiles;

   } else {
      // scan whole file system
      stdOut("Start scanning '" . ROOT_PATH . "'.\n");
      
      file_exists(QUEUE_FILENAME) && unlink(QUEUE_FILENAME);
      if (ICHECK || IMAKE) {
      // INTEGRITY CHECK
        IMAKE and unlink(INTEGRITY_DB_FILE);
        ICHECK and load_integrity_db();
        QCR_IntegrityCheck(ROOT_PATH);
        stdOut("Found $g_FoundTotalFiles files in $g_FoundTotalDirs directories.");
        if (IMAKE) exit(0);
        if (ICHECK) {
            $i = $g_Counter;
            $g_CRC = 0;
            $changes = array();
            $ref =& $g_IntegrityDB;
            foreach ($g_IntegrityDB as $l_FileName => $type) {
                unset($g_IntegrityDB[$l_FileName]);
                $l_Ext2 = substr(strstr(basename($l_FileName), '.'), 1);
                if (in_array(strtolower($l_Ext2), $g_IgnoredExt)) {
                    continue;
                }
                for ($dr = 0; $dr < count($g_DirIgnoreList); $dr++) {
                    if (($g_DirIgnoreList[$dr] != '') && preg_match('#' . $g_DirIgnoreList[$dr] . '#', $l_FileName, $l_Found)) {
                        continue 2;
                    }
                }
                $type = in_array($type, array('added', 'modified')) ? $type : 'deleted';
                $type .= substr($l_FileName, -1) == '/' ? 'Dirs' : 'Files';
                $changes[$type][] = ++$i;
                AddResult($l_FileName, $i);
            }
            $g_FoundTotalFiles = count($changes['addedFiles']) + count($changes['modifiedFiles']);
            stdOut("Found changes " . count($changes['modifiedFiles']) . " files and added " . count($changes['addedFiles']) . " files.");
        }
        
      } else {
      QCR_ScanDirectories(ROOT_PATH);
      stdOut("Found $g_FoundTotalFiles files in $g_FoundTotalDirs directories.");
      }

      QCR_Debug();
      stdOut(str_repeat(' ', 160),false);
      QCR_GoScan(0);
      unlink(QUEUE_FILENAME);
      if (defined('PROGRESS_LOG_FILE') && file_exists(PROGRESS_LOG_FILE)) @unlink(PROGRESS_LOG_FILE);
   }
}

QCR_Debug();

if (true) {
   $g_HeuristicDetected = array();
   $g_Iframer = array();
   $g_Base64 = array();
}


// whitelist

$snum = 0;
$list = check_whitelist($g_Structure['crc'], $snum);

foreach (array('g_CriticalPHP', 'g_CriticalJS', 'g_Iframer', 'g_Base64', 'g_Phishing', 'g_AdwareList', 'g_Redirect') as $p) {
	if (empty($$p)) continue;
	
	$p_Fragment = $p . "Fragment";
	$p_Sig = $p . "Sig";
	if ($p == 'g_Redirect') $p_Fragment = $p . "PHPFragment";
	if ($p == 'g_Phishing') $p_Sig = $p . "SigFragment";

	$count = count($$p);
	for ($i = 0; $i < $count; $i++) {
		$id = "{${$p}[$i]}";
		if (in_array($g_Structure['crc'][$id], $list)) {
			unset($GLOBALS[$p][$i]);
			unset($GLOBALS[$p_Sig][$i]);
			unset($GLOBALS[$p_Fragment][$i]);
		}
	}

	$$p = array_values($$p);
	$$p_Fragment = array_values($$p_Fragment);
	if (!empty($$p_Sig)) $$p_Sig = array_values($$p_Sig);
}


////////////////////////////////////////////////////////////////////////////
if (AI_HOSTER) {
   $g_IframerFragment = array();
   $g_Iframer = array();
   $g_Redirect = array();
   $g_Doorway = array();
   $g_EmptyLink = array();
   $g_HeuristicType = array();
   $g_HeuristicDetected = array();
   $g_WarningPHP = array();
   $g_AdwareList = array();
   $g_Phishing = array(); 
   $g_PHPCodeInside = array();
   $g_PHPCodeInsideFragment = array();
   $g_NotRead = array();
   $g_WarningPHPFragment = array();
   $g_WarningPHPSig = array();
   $g_BigFiles = array();
   $g_RedirectPHPFragment = array();
   $g_EmptyLinkSrc = array();
   $g_Base64Fragment = array();
   $g_UnixExec = array();
   $g_PhishingSigFragment = array();
   $g_PhishingFragment = array();
   $g_PhishingSig = array();
   $g_IframerFragment = array();
   $g_CMS = array();
   $g_AdwareListFragment = array(); 
   $g_Vulnerable = array();
}


 if (BOOL_RESULT && (!defined('NEED_REPORT'))) {
  if ((count($g_CriticalPHP) > 0) OR (count($g_CriticalJS) > 0) OR (count($g_Base64) > 0) OR  (count($g_Iframer) > 0) OR  (count($g_UnixExec) > 0))
  {
  echo "1\n";
  exit(0);
  }
 }
////////////////////////////////////////////////////////////////////////////
$l_Template = str_replace("@@SERVICE_INFO@@", htmlspecialchars("[" . $int_enc . "][" . $snum . "]"), $l_Template);

$l_Template = str_replace("@@PATH_URL@@", (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $g_AddPrefix . str_replace($g_NoPrefix, '', addSlash(ROOT_PATH))), $l_Template);

$time_taken = seconds2Human(microtime(true) - START_TIME);

$l_Template = str_replace("@@SCANNED@@", sprintf(AI_STR_013, $g_TotalFolder, $g_TotalFiles), $l_Template);

$l_ShowOffer = false;

stdOut("\nBuilding report [ mode = " . AI_EXPERT . " ]\n");

stdOut("\nLoaded signatures: " . count($g_FlexDBShe) . " / " . count($g_JSVirSig) . "\n");

////////////////////////////////////////////////////////////////////////////
// save 
if (!(ICHECK || IMAKE))
if (isset($options['with-2check']) || isset($options['quarantine']))
if ((count($g_CriticalPHP) > 0) OR (count($g_CriticalJS) > 0) OR (count($g_Base64) > 0) OR 
   (count($g_Iframer) > 0) OR  (count($g_UnixExec))) 
{
  if (!file_exists(DOUBLECHECK_FILE)) {	  
      if ($l_FH = fopen(DOUBLECHECK_FILE, 'w')) {
         fputs($l_FH, '<?php die("Forbidden"); ?>' . "\n");

         $l_CurrPath = dirname(__FILE__);
		 
		 if (!isset($g_CriticalPHP)) { $g_CriticalPHP = array(); }
		 if (!isset($g_CriticalJS)) { $g_CriticalJS = array(); }
		 if (!isset($g_Iframer)) { $g_Iframer = array(); }
		 if (!isset($g_Base64)) { $g_Base64 = array(); }
		 if (!isset($g_Phishing)) { $g_Phishing = array(); }
		 if (!isset($g_AdwareList)) { $g_AdwareList = array(); }
		 if (!isset($g_Redirect)) { $g_Redirect = array(); }
		 
         $tmpIndex = array_merge($g_CriticalPHP, $g_CriticalJS, $g_Phishing, $g_Base64, $g_Iframer, $g_AdwareList, $g_Redirect);
         $tmpIndex = array_values(array_unique($tmpIndex));

         for ($i = 0; $i < count($tmpIndex); $i++) {
             $tmpIndex[$i] = str_replace($l_CurrPath, '.', $g_Structure['n'][$tmpIndex[$i]]);
         }

         for ($i = 0; $i < count($g_UnixExec); $i++) {
             $tmpIndex[] = str_replace($l_CurrPath, '.', $g_UnixExec[$i]);
         }

         $tmpIndex = array_values(array_unique($tmpIndex));

         for ($i = 0; $i < count($tmpIndex); $i++) {
             fputs($l_FH, $tmpIndex[$i] . "\n");
         }

         fclose($l_FH);
      } else {
         stdOut("Error! Cannot create " . DOUBLECHECK_FILE);
      }      
  } else {
      stdOut(DOUBLECHECK_FILE . ' already exists.');
      if (AI_STR_044 != '') $l_Result .= '<div class="rep">' . AI_STR_044 . '</div>';
  }
 
}

////////////////////////////////////////////////////////////////////////////

$l_Summary = '<div class="title">' . AI_STR_074 . '</div>';
$l_Summary .= '<table cellspacing=0 border=0>';

if (count($g_Redirect) > 0) {
   $l_Summary .= makeSummary(AI_STR_059, count($g_Redirect), "crit");
}

if (count($g_CriticalPHP) > 0) {
   $l_Summary .= makeSummary(AI_STR_060, count($g_CriticalPHP), "crit");
}

if (count($g_CriticalJS) > 0) {
   $l_Summary .= makeSummary(AI_STR_061, count($g_CriticalJS), "crit");
}

if (count($g_Phishing) > 0) {
   $l_Summary .= makeSummary(AI_STR_062, count($g_Phishing), "crit");
}

if (count($g_UnixExec) > 0) {
   $l_Summary .= makeSummary(AI_STR_063, count($g_UnixExec), (AI_EXPERT > 1 ? 'crit' : 'warn'));
}

if (count($g_Iframer) > 0) {
   $l_Summary .= makeSummary(AI_STR_064, count($g_Iframer), "crit");
}

if (count($g_NotRead) > 0) {
   $l_Summary .= makeSummary(AI_STR_066, count($g_NotRead), "crit");
}

if (count($g_Base64) > 0) {
   $l_Summary .= makeSummary(AI_STR_067, count($g_Base64), (AI_EXPERT > 1 ? 'crit' : 'warn'));
}

if (count($g_BigFiles) > 0) {
   $l_Summary .= makeSummary(AI_STR_065, count($g_BigFiles), "warn");
}

if (count($g_HeuristicDetected) > 0) {
   $l_Summary .= makeSummary(AI_STR_068, count($g_HeuristicDetected), "warn");
}

if (count($g_SymLinks) > 0) {
   $l_Summary .= makeSummary(AI_STR_069, count($g_SymLinks), "warn");
}

if (count($g_HiddenFiles) > 0) {
   $l_Summary .= makeSummary(AI_STR_070, count($g_HiddenFiles), "warn");
}

if (count($g_AdwareList) > 0) {
   $l_Summary .= makeSummary(AI_STR_072, count($g_AdwareList), "warn");
}

if (count($g_EmptyLink) > 0) {
   $l_Summary .= makeSummary(AI_STR_073, count($g_EmptyLink), "warn");
}

 $l_Summary .= "</table>";

$l_ArraySummary = array();
$l_ArraySummary["redirect"] = count($g_Redirect);
$l_ArraySummary["critical_php"] = count($g_CriticalPHP);
$l_ArraySummary["critical_js"] = count($g_CriticalJS);
$l_ArraySummary["phishing"] = count($g_Phishing);
$l_ArraySummary["unix_exec"] = count($g_UnixExec);
$l_ArraySummary["iframes"] = count($g_Iframer);
$l_ArraySummary["not_read"] = count($g_NotRead);
$l_ArraySummary["base64"] = count($g_Base64);
$l_ArraySummary["heuristics"] = count($g_HeuristicDetected);
$l_ArraySummary["symlinks"] = count($g_SymLinks);
$l_ArraySummary["big_files_skipped"] = count($g_BigFiles);

 if (function_exists('json_encode')) { $l_Summary .= "<!--[json]" . json_encode($l_ArraySummary) . "[/json]-->"; }

 $l_Summary .= "<div class=details style=\"margin: 20px 20px 20px 0\">" . AI_STR_080 . "</div>\n";

 $l_Template = str_replace("@@SUMMARY@@", $l_Summary, $l_Template);


 $l_Result .= AI_STR_015;
 
 $l_Template = str_replace("@@VERSION@@", AI_VERSION, $l_Template);
 
////////////////////////////////////////////////////////////////////////////



if (function_exists("gethostname") && is_callable("gethostname")) {
  $l_HostName = gethostname();
} else {
  $l_HostName = '???';
}

$l_PlainResult = "# Malware list detected by AI-Bolit (https://revisium.com/ai/) on " . date("d/m/Y H:i:s", time()) . " " . $l_HostName .  "\n\n";

$l_RawReport = array();

if (!AI_HOSTER) {
   stdOut("Building list of vulnerable scripts " . count($g_Vulnerable));

   if (count($g_Vulnerable) > 0) {
       $l_Result .= '<div class="note_vir">' . AI_STR_081 . ' (' . count($g_Vulnerable) . ')</div><div class="crit">';
    	foreach ($g_Vulnerable as $l_Item) {
   	    $l_Result .= '<li>' . makeSafeFn($g_Structure['n'][$l_Item['ndx']], true) . ' - ' . $l_Item['id'] . '</li>';
               $l_PlainResult .= '[VULNERABILITY] ' . replacePathArray($g_Structure['n'][$l_Item['ndx']]) . ' - ' . $l_Item['id'] . "\n";
    	}
   	
     $l_Result .= '</div><p>' . PHP_EOL;
     $l_PlainResult .= "\n";
   }
}


stdOut("Building list of shells " . count($g_CriticalPHP));

$l_RawReport['vulners'] = getRawJsonVuln($g_Vulnerable);

if (count($g_CriticalPHP) > 0) {
  $g_CriticalPHP = array_slice($g_CriticalPHP, 0, 15000);
  $l_RawReport['php_malware'] = getRawJson($g_CriticalPHP, $g_CriticalPHPFragment, $g_CriticalPHPSig);
  $l_Result .= '<div class="note_vir">' . AI_STR_016 . ' (' . count($g_CriticalPHP) . ')</div><div class="crit">';
  $l_Result .= printList($g_CriticalPHP, $g_CriticalPHPFragment, true, $g_CriticalPHPSig, 'table_crit');
  $l_PlainResult .= '[SERVER MALWARE]' . "\n" . printPlainList($g_CriticalPHP, $g_CriticalPHPFragment, true, $g_CriticalPHPSig, 'table_crit') . "\n";
  $l_Result .= '</div>' . PHP_EOL;

  $l_ShowOffer = true;
} else {
  $l_Result .= '<div class="ok"><b>' . AI_STR_017. '</b></div>';
}

stdOut("Building list of js " . count($g_CriticalJS));

if (count($g_CriticalJS) > 0) {
  $g_CriticalJS = array_slice($g_CriticalJS, 0, 15000);
  $l_RawReport['js_malware'] = getRawJson($g_CriticalJS, $g_CriticalJSFragment, $g_CriticalJSSig);
  $l_Result .= '<div class="note_vir">' . AI_STR_018 . ' (' . count($g_CriticalJS) . ')</div><div class="crit">';
  $l_Result .= printList($g_CriticalJS, $g_CriticalJSFragment, true, $g_CriticalJSSig, 'table_vir');
  $l_PlainResult .= '[CLIENT MALWARE / JS]'  . "\n" . printPlainList($g_CriticalJS, $g_CriticalJSFragment, true, $g_CriticalJSSig, 'table_vir') . "\n";
  $l_Result .= "</div>" . PHP_EOL;

  $l_ShowOffer = true;
}

if (!AI_HOSTER) {
   stdOut("Building phishing pages " . count($g_Phishing));

   if (count($g_Phishing) > 0) {
     $l_RawReport['phishing'] = getRawJson($g_Phishing, $g_PhishingFragment, $g_PhishingSigFragment);
     $l_Result .= '<div class="note_vir">' . AI_STR_058 . ' (' . count($g_Phishing) . ')</div><div class="crit">';
     $l_Result .= printList($g_Phishing, $g_PhishingFragment, true, $g_PhishingSigFragment, 'table_vir');
     $l_PlainResult .= '[PHISHING]'  . "\n" . printPlainList($g_Phishing, $g_PhishingFragment, true, $g_PhishingSigFragment, 'table_vir') . "\n";
     $l_Result .= "</div>". PHP_EOL;

     $l_ShowOffer = true;
   }

   stdOut("Building list of iframes " . count($g_Iframer));

   if (count($g_Iframer) > 0) {
     $l_RawReport['iframer'] = getRawJson($g_Iframer, $g_IframerFragment);
     $l_ShowOffer = true;
     $l_Result .= '<div class="note_vir">' . AI_STR_021 . ' (' . count($g_Iframer) . ')</div><div class="crit">';
     $l_Result .= printList($g_Iframer, $g_IframerFragment, true);
     $l_Result .= "</div>" . PHP_EOL;

   }

   stdOut("Building list of base64s " . count($g_Base64));

   if (count($g_Base64) > 0) {
     $l_RawReport['warn_enc'] = getRawJson($g_Base64, $g_Base64Fragment);
     if (AI_EXPERT > 1) $l_ShowOffer = true;
     
     $l_Result .= '<div class="note_' . (AI_EXPERT > 1 ? 'vir' : 'warn') . '">' . AI_STR_020 . ' (' . count($g_Base64) . ')</div><div class="' . (AI_EXPERT > 1 ? 'crit' : 'warn') . '">';
     $l_Result .= printList($g_Base64, $g_Base64Fragment, true);
     $l_PlainResult .= '[ENCODED / SUSP_EXT]' . "\n" . printPlainList($g_Base64, $g_Base64Fragment, true) . "\n";
     $l_Result .= "</div>" . PHP_EOL;

   }

   stdOut("Building list of redirects " . count($g_Redirect));
   if (count($g_Redirect) > 0) {
     $l_RawReport['redirect'] = getRawJson($g_Redirect, $g_RedirectPHPFragment);
     $l_ShowOffer = true;
     $l_Result .= '<div class="note_vir">' . AI_STR_027 . ' (' . count($g_Redirect) . ')</div><div class="crit">';
     $l_Result .= printList($g_Redirect, $g_RedirectPHPFragment, true);
     $l_Result .= "</div>" . PHP_EOL;
   }


   stdOut("Building list of unread files " . count($g_NotRead));

   if (count($g_NotRead) > 0) {
     $g_NotRead = array_slice($g_NotRead, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['not_read'] = $g_NotRead;
     $l_Result .= '<div class="note_vir">' . AI_STR_030 . ' (' . count($g_NotRead) . ')</div><div class="crit">';
     $l_Result .= printList($g_NotRead);
     $l_Result .= "</div><div class=\"spacer\"></div>" . PHP_EOL;
     $l_PlainResult .= '[SCAN ERROR / SKIPPED]' . "\n" . printPlainList($g_NotRead) . "\n\n";
   }

   stdOut("Building list of symlinks " . count($g_SymLinks));

   if (count($g_SymLinks) > 0) {
     $g_SymLinks = array_slice($g_SymLinks, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['sym_links'] = $g_SymLinks;
     $l_Result .= '<div class="note_vir">' . AI_STR_022 . ' (' . count($g_SymLinks) . ')</div><div class="crit">';
     $l_Result .= nl2br(makeSafeFn(implode("\n", $g_SymLinks), true));
     $l_Result .= "</div><div class=\"spacer\"></div>";
   }

   stdOut("Building list of unix executables and odd scripts " . count($g_UnixExec));

   if (count($g_UnixExec) > 0) {
     $g_UnixExec = array_slice($g_UnixExec, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['unix_exec'] = $g_UnixExec;
     $l_Result .= '<div class="note_' . (AI_EXPERT > 1 ? 'vir' : 'warn') . '">' . AI_STR_019 . ' (' . count($g_UnixExec) . ')</div><div class="' . (AI_EXPERT > 1 ? 'crit' : 'warn') . '">';
     $l_Result .= nl2br(makeSafeFn(implode("\n", $g_UnixExec), true));
     $l_PlainResult .= '[UNIX EXEC]' . "\n" . implode("\n", replacePathArray($g_UnixExec)) . "\n\n";
     $l_Result .= "</div>" . PHP_EOL;

     if (AI_EXPERT > 1) $l_ShowOffer = true;
   }
}
////////////////////////////////////
if (!AI_HOSTER) {
   $l_WarningsNum = count($g_HeuristicDetected) + count($g_HiddenFiles) + count($g_BigFiles) + count($g_PHPCodeInside) + count($g_AdwareList) + count($g_EmptyLink) + count($g_Doorway) + (count($g_WarningPHP[0]) + count($g_WarningPHP[1]) + count($g_SkippedFolders));

   if ($l_WarningsNum > 0) {
   	$l_Result .= "<div style=\"margin-top: 20px\" class=\"title\">" . AI_STR_026 . "</div>";
   }

   stdOut("Building list of links/adware " . count($g_AdwareList));

   if (count($g_AdwareList) > 0) {
     $l_RawReport['adware'] = getRawJson($g_AdwareList, $g_AdwareListFragment);
     $l_Result .= '<div class="note_warn">' . AI_STR_029 . '</div><div class="warn">';
     $l_Result .= printList($g_AdwareList, $g_AdwareListFragment, true);
     $l_PlainResult .= '[ADWARE]' . "\n" . printPlainList($g_AdwareList, $g_AdwareListFragment, true) . "\n";
     $l_Result .= "</div>" . PHP_EOL;

   }

   stdOut("Building list of heuristics " . count($g_HeuristicDetected));

   if (count($g_HeuristicDetected) > 0) {
     $l_RawReport['heuristic'] = $g_HeuristicDetected;
     $l_Result .= '<div class="note_warn">' . AI_STR_052 . ' (' . count($g_HeuristicDetected) . ')</div><div class="warn">';
     for ($i = 0; $i < count($g_HeuristicDetected); $i++) {
   	   $l_Result .= '<li>' . makeSafeFn($g_Structure['n'][$g_HeuristicDetected[$i]], true) . ' (' . get_descr_heur($g_HeuristicType[$i]) . ')</li>';
     }
     
     $l_Result .= '</ul></div><div class=\"spacer\"></div>' . PHP_EOL;
   }

   stdOut("Building list of hidden files " . count($g_HiddenFiles));
   if (count($g_HiddenFiles) > 0) {
     $g_HiddenFiles = array_slice($g_HiddenFiles, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['hidden'] = $g_HiddenFiles;
     $l_Result .= '<div class="note_warn">' . AI_STR_023 . ' (' . count($g_HiddenFiles) . ')</div><div class="warn">';
     $l_Result .= nl2br(makeSafeFn(implode("\n", $g_HiddenFiles), true));
     $l_Result .= "</div><div class=\"spacer\"></div>" . PHP_EOL;
     $l_PlainResult .= '[HIDDEN]' . "\n" . implode("\n", replacePathArray($g_HiddenFiles)) . "\n\n";
   }

   stdOut("Building list of bigfiles " . count($g_BigFiles));
   $max_size_to_scan = getBytes(MAX_SIZE_TO_SCAN);
   $max_size_to_scan = $max_size_to_scan > 0 ? $max_size_to_scan : getBytes('1m');

   if (count($g_BigFiles) > 0) {
     $g_BigFiles = array_slice($g_BigFiles, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['big_files'] = getRawJson($g_BigFiles);
     $l_Result .= "<div class=\"note_warn\">" . sprintf(AI_STR_038, bytes2Human($max_size_to_scan)) . '</div><div class="warn">';
     $l_Result .= printList($g_BigFiles);
     $l_Result .= "</div>";
     $l_PlainResult .= '[BIG FILES / SKIPPED]' . "\n" . printPlainList($g_BigFiles) . "\n\n";
   } 

   stdOut("Building list of php inj " . count($g_PHPCodeInside));

   if ((count($g_PHPCodeInside) > 0) && (($defaults['report_mask'] & REPORT_MASK_PHPSIGN) == REPORT_MASK_PHPSIGN)) {
     $l_Result .= '<div class="note_warn">' . AI_STR_028 . '</div><div class="warn">';
     $l_Result .= printList($g_PHPCodeInside, $g_PHPCodeInsideFragment, true);
     $l_Result .= "</div>" . PHP_EOL;

   }

   stdOut("Building list of empty links " . count($g_EmptyLink));
   if (count($g_EmptyLink) > 0) {
     $g_EmptyLink = array_slice($g_EmptyLink, 0, AIBOLIT_MAX_NUMBER);
     $l_Result .= '<div class="note_warn">' . AI_STR_031 . '</div><div class="warn">';
     $l_Result .= printList($g_EmptyLink, '', true);

     $l_Result .= AI_STR_032 . '<br/>';
     
     if (count($g_EmptyLink) == MAX_EXT_LINKS) {
         $l_Result .= '(' . AI_STR_033 . MAX_EXT_LINKS . ')<br/>';
       }
      
     for ($i = 0; $i < count($g_EmptyLink); $i++) {
   	$l_Idx = $g_EmptyLink[$i];
       for ($j = 0; $j < count($g_EmptyLinkSrc[$l_Idx]); $j++) {
         $l_Result .= '<span class="details">' . makeSafeFn($g_Structure['n'][$g_EmptyLink[$i]], true) . ' &rarr; ' . htmlspecialchars($g_EmptyLinkSrc[$l_Idx][$j]) . '</span><br/>';
   	}
     }

     $l_Result .= "</div>";

   }

   stdOut("Building list of doorways " . count($g_Doorway));

   if ((count($g_Doorway) > 0) && (($defaults['report_mask'] & REPORT_MASK_DOORWAYS) == REPORT_MASK_DOORWAYS)) {
     $g_Doorway = array_slice($g_Doorway, 0, AIBOLIT_MAX_NUMBER);
     $l_RawReport['doorway'] = getRawJson($g_Doorway);
     $l_Result .= '<div class="note_warn">' . AI_STR_034 . '</div><div class="warn">';
     $l_Result .= printList($g_Doorway);
     $l_Result .= "</div>" . PHP_EOL;

   }

   stdOut("Building list of php warnings " . (count($g_WarningPHP[0]) + count($g_WarningPHP[1])));

   if (($defaults['report_mask'] & REPORT_MASK_SUSP) == REPORT_MASK_SUSP) {
      if ((count($g_WarningPHP[0]) + count($g_WarningPHP[1])) > 0) {
        $g_WarningPHP[0] = array_slice($g_WarningPHP[0], 0, AIBOLIT_MAX_NUMBER);
        $g_WarningPHP[1] = array_slice($g_WarningPHP[1], 0, AIBOLIT_MAX_NUMBER);
        $l_Result .= '<div class="note_warn">' . AI_STR_035 . '</div><div class="warn">';

        for ($i = 0; $i < count($g_WarningPHP); $i++) {
            if (count($g_WarningPHP[$i]) > 0) 
               $l_Result .= printList($g_WarningPHP[$i], $g_WarningPHPFragment[$i], true, $g_WarningPHPSig, 'table_warn' . $i);
        }                                                                                                                    
        $l_Result .= "</div>" . PHP_EOL;

      } 
   }

   stdOut("Building list of skipped dirs " . count($g_SkippedFolders));
   if (count($g_SkippedFolders) > 0) {
        $l_Result .= '<div class="note_warn">' . AI_STR_036 . '</div><div class="warn">';
        $l_Result .= nl2br(makeSafeFn(implode("\n", $g_SkippedFolders), true));   
        $l_Result .= "</div>" . PHP_EOL;
    }

    if (count($g_CMS) > 0) {
         $l_RawReport['cms'] = $g_CMS;
         $l_Result .= "<div class=\"note_warn\">" . AI_STR_037 . "<br/>";
         $l_Result .= nl2br(makeSafeFn(implode("\n", $g_CMS)));
         $l_Result .= "</div>";
    }
}

if (ICHECK) {
	$l_Result .= "<div style=\"margin-top: 20px\" class=\"title\">" . AI_STR_087 . "</div>";
	
    stdOut("Building list of added files " . count($changes['addedFiles']));
    if (count($changes['addedFiles']) > 0) {
      $l_Result .= '<div class="note_int">' . AI_STR_082 . ' (' . count($changes['addedFiles']) . ')</div><div class="intitem">';
      $l_Result .= printList($changes['addedFiles']);
      $l_Result .= "</div>" . PHP_EOL;
    }

    stdOut("Building list of modified files " . count($changes['modifiedFiles']));
    if (count($changes['modifiedFiles']) > 0) {
      $l_Result .= '<div class="note_int">' . AI_STR_083 . ' (' . count($changes['modifiedFiles']) . ')</div><div class="intitem">';
      $l_Result .= printList($changes['modifiedFiles']);
      $l_Result .= "</div>" . PHP_EOL;
    }

    stdOut("Building list of deleted files " . count($changes['deletedFiles']));
    if (count($changes['deletedFiles']) > 0) {
      $l_Result .= '<div class="note_int">' . AI_STR_084 . ' (' . count($changes['deletedFiles']) . ')</div><div class="intitem">';
      $l_Result .= printList($changes['deletedFiles']);
      $l_Result .= "</div>" . PHP_EOL;
    }

    stdOut("Building list of added dirs " . count($changes['addedDirs']));
    if (count($changes['addedDirs']) > 0) {
      $l_Result .= '<div class="note_int">' . AI_STR_085 . ' (' . count($changes['addedDirs']) . ')</div><div class="intitem">';
      $l_Result .= printList($changes['addedDirs']);
      $l_Result .= "</div>" . PHP_EOL;
    }

    stdOut("Building list of deleted dirs " . count($changes['deletedDirs']));
    if (count($changes['deletedDirs']) > 0) {
      $l_Result .= '<div class="note_int">' . AI_STR_086 . ' (' . count($changes['deletedDirs']) . ')</div><div class="intitem">';
      $l_Result .= printList($changes['deletedDirs']);
      $l_Result .= "</div>" . PHP_EOL;
    }
}

if (!isCli()) {
   $l_Result .= QCR_ExtractInfo($l_PhpInfoBody[1]);
}


if (function_exists('memory_get_peak_usage')) {
  $l_Template = str_replace("@@MEMORY@@", AI_STR_043 . bytes2Human(memory_get_peak_usage()), $l_Template);
}

$l_Template = str_replace('@@WARN_QUICK@@', ((SCAN_ALL_FILES || $g_SpecificExt) ? '' : AI_STR_045), $l_Template);

if ($l_ShowOffer) {
	$l_Template = str_replace('@@OFFER@@', $l_Offer, $l_Template);
} else {
	$l_Template = str_replace('@@OFFER@@', AI_STR_002, $l_Template);
}

$l_Template = str_replace('@@CAUTION@@', AI_STR_003, $l_Template);

$l_Template = str_replace('@@CREDITS@@', AI_STR_075, $l_Template);

$l_Template = str_replace('@@FOOTER@@', AI_STR_076, $l_Template);

$l_Template = str_replace('@@STAT@@', sprintf(AI_STR_012, $time_taken, date('d-m-Y в H:i:s', floor(START_TIME)) , date('d-m-Y в H:i:s')), $l_Template);

////////////////////////////////////////////////////////////////////////////
$l_Template = str_replace("@@MAIN_CONTENT@@", $l_Result, $l_Template);

if (!isCli())
{
    echo $l_Template;
    exit;
}

if (!defined('REPORT') OR REPORT === '')
{
	die2('Report not written.');
}
 
// write plain text result
if (PLAIN_FILE != '') {
	
    $l_PlainResult = preg_replace('|__AI_LINE1__|smi', '[', $l_PlainResult);
    $l_PlainResult = preg_replace('|__AI_LINE2__|smi', '] ', $l_PlainResult);
    $l_PlainResult = preg_replace('|__AI_MARKER__|smi', ' %> ', $l_PlainResult);

   if ($l_FH = fopen(PLAIN_FILE, "w")) {
      fputs($l_FH, $l_PlainResult);
      fclose($l_FH);
   }
}

// write json result
if (defined('JSON_FILE')) {	
   if ($l_FH = fopen(JSON_FILE, "w")) {
      fputs($l_FH, json_encode($l_RawReport));
      fclose($l_FH);
   }
}

// write serialized result
if (defined('PHP_FILE')) {	
   if ($l_FH = fopen(PHP_FILE, "w")) {
      fputs($l_FH, serialize($l_RawReport));
      fclose($l_FH);
   }
}

$emails = getEmails(REPORT);

if (!$emails) {
	if ($l_FH = fopen($file, "w")) {
	   fputs($l_FH, $l_Template);
	   fclose($l_FH);
	   stdOut("\nReport written to '$file'.");
	} else {
		stdOut("\nCannot create '$file'.");
	}
}	else	{
		$headers = array(
			'MIME-Version: 1.0',
			'Content-type: text/html; charset=UTF-8',
			'From: ' . ($defaults['email_from'] ? $defaults['email_from'] : 'AI-Bolit@myhost')
		);

		for ($i = 0, $size = sizeof($emails); $i < $size; $i++)
		{
			mail($emails[$i], 'AI-Bolit Report ' . date("d/m/Y H:i", time()), $l_Result, implode("\r\n", $headers));
		}

		stdOut("\nReport sended to " . implode(', ', $emails));
}


$time_taken = microtime(true) - START_TIME;
$time_taken = number_format($time_taken, 5);

stdOut("Scanning complete! Time taken: " . seconds2Human($time_taken));

stdOut("\n\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
stdOut("Attention! DO NOT LEAVE either ai-bolit.php or AI-BOLIT-REPORT-<xxxx>-<yy>.html \nfile on server. COPY it locally then REMOVE from server. ");
stdOut("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");

if (isset($options['quarantine'])) {
	Quarantine();
}

if (isset($options['cmd'])) {
	stdOut("Run \"{$options['cmd']}\" ");
	system($options['cmd']);
}

QCR_Debug();

# exit with code

$l_EC1 = count($g_CriticalPHP);
$l_EC2 = count($g_CriticalJS) + count($g_Phishing) + count($g_WarningPHP[0]) + count($g_WarningPHP[1]);
$code = 0;

if ($l_EC1 > 0) {
	$code = 2;
} else {
	if ($l_EC2 > 0) {
		$code = 1;
	}
}

$stat = array('php_malware' => count($g_CriticalPHP), 'js_malware' => count($g_CriticalJS), 'phishing' => count($g_Phishing));

if (function_exists('aibolit_onComplete')) { aibolit_onComplete($code, $stat); }

stdOut('Exit code ' . $code);
exit($code);

############################################# END ###############################################

function Quarantine()
{
	if (!file_exists(DOUBLECHECK_FILE)) {
		return;
	}
	
	$g_QuarantinePass = 'aibolit';
	
	$archive = "AI-QUARANTINE-" .rand(100000, 999999) . ".zip";
	$infoFile = substr($archive, 0, -3) . "txt";
	$report = REPORT_PATH . DIR_SEPARATOR . REPORT_FILE;
	

	foreach (file(DOUBLECHECK_FILE) as $file) {
		$file = trim($file);
		if (!is_file($file)) continue;
	
		$lStat = stat($file);
		
		// skip files over 300KB
		if ($lStat['size'] > 300*1024) continue;

		// http://www.askapache.com/security/chmod-stat.html
		$p = $lStat['mode'];
		$perm ='-';
		$perm.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
		$perm.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
		$perm.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
		$perm.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
		$perm.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
		$perm.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));
		
		$owner = (function_exists('posix_getpwuid'))? @posix_getpwuid($lStat['uid']) : array('name' => $lStat['uid']);
		$group = (function_exists('posix_getgrgid'))? @posix_getgrgid($lStat['gid']) : array('name' => $lStat['uid']);

		$inf['permission'][] = $perm;
		$inf['owner'][] = $owner['name'];
		$inf['group'][] = $group['name'];
		$inf['size'][] = $lStat['size'] > 0 ? bytes2Human($lStat['size']) : '-';
		$inf['ctime'][] = $lStat['ctime'] > 0 ? date("d/m/Y H:i:s", $lStat['ctime']) : '-';
		$inf['mtime'][] = $lStat['mtime'] > 0 ? date("d/m/Y H:i:s", $lStat['mtime']) : '-';
		$files[] = strpos($file, './') === 0 ? substr($file, 2) : $file;
	}
	
	// get config files for cleaning
	$configFilesRegex = 'config(uration|\.in[ic])?\.php$|dbconn\.php$';
	$configFiles = preg_grep("~$configFilesRegex~", $files);

	// get columns width
	$width = array();
	foreach (array_keys($inf) as $k) {
		$width[$k] = strlen($k);
		for ($i = 0; $i < count($inf[$k]); ++$i) {
			$len = strlen($inf[$k][$i]);
			if ($len > $width[$k])
				$width[$k] = $len;
		}
	}

	// headings of columns
	$info = '';
	foreach (array_keys($inf) as $k) {
		$info .= str_pad($k, $width[$k], ' ', STR_PAD_LEFT). ' ';
	}
	$info .= "name\n";
	
	for ($i = 0; $i < count($files); ++$i) {
		foreach (array_keys($inf) as $k) {
			$info .= str_pad($inf[$k][$i], $width[$k], ' ', STR_PAD_LEFT). ' ';
		}
		$info .= $files[$i]."\n";
	}
	unset($inf, $width);

	exec("zip -v 2>&1", $output,$code);

	if ($code == 0) {
		$filter = '';
		if ($configFiles && exec("grep -V 2>&1", $output, $code) && $code == 0) {
			$filter = "|grep -v -E '$configFilesRegex'";
		}

		exec("cat AI-BOLIT-DOUBLECHECK.php $filter |zip -@ --password $g_QuarantinePass $archive", $output, $code);
		if ($code == 0) {
			file_put_contents($infoFile, $info);
			$m = array();
			if (!empty($filter)) {
				foreach ($configFiles as $file) {
					$tmp = file_get_contents($file);
					// remove  passwords
					$tmp = preg_replace('~^.*?pass.*~im', '', $tmp);
					// new file name
					$file = preg_replace('~.*/~', '', $file) . '-' . rand(100000, 999999);
					file_put_contents($file, $tmp);
					$m[] = $file;
				}
			}

			exec("zip -j --password $g_QuarantinePass $archive $infoFile $report " . DOUBLECHECK_FILE . ' ' . implode(' ', $m));
			stdOut("\nCreate archive '" . realpath($archive) . "'");
			stdOut("This archive have password '$g_QuarantinePass'");
			foreach ($m as $file) unlink($file);
			unlink($infoFile);
			return;
		}
	}
	
	$zip = new ZipArchive;
	
	if ($zip->open($archive, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) === false) {
		stdOut("Cannot create '$archive'.");
		return;
	}

	foreach ($files as $file) {
		if (in_array($file, $configFiles)) {
			$tmp = file_get_contents($file);
			// remove  passwords
			$tmp = preg_replace('~^.*?pass.*~im', '', $tmp);
			$zip->addFromString($file, $tmp);
		} else {
			$zip->addFile($file);
		}
	}
	$zip->addFile(DOUBLECHECK_FILE, DOUBLECHECK_FILE);
	$zip->addFile($report, REPORT_FILE);
	$zip->addFromString($infoFile, $info);
	$zip->close();

	stdOut("\nCreate archive '" . realpath($archive) . "'.");
	stdOut("This archive has no password!");
}



///////////////////////////////////////////////////////////////////////////
function QCR_IntegrityCheck($l_RootDir)
{
	global $g_Structure, $g_Counter, $g_Doorway, $g_FoundTotalFiles, $g_FoundTotalDirs, 
			$defaults, $g_SkippedFolders, $g_UrlIgnoreList, $g_DirIgnoreList, $g_UnsafeDirArray, 
                        $g_UnsafeFilesFound, $g_SymLinks, $g_HiddenFiles, $g_UnixExec, $g_IgnoredExt, $g_SuspiciousFiles, $l_SkipSample;
	global $g_IntegrityDB, $g_ICheck;
	static $l_Buffer = '';
	
	$l_DirCounter = 0;
	$l_DoorwayFilesCounter = 0;
	$l_SourceDirIndex = $g_Counter - 1;
	
	QCR_Debug('Check ' . $l_RootDir);

 	if ($l_DIRH = @opendir($l_RootDir))
	{
		while (($l_FileName = readdir($l_DIRH)) !== false)
		{
			if ($l_FileName == '.' || $l_FileName == '..') continue;

			$l_FileName = $l_RootDir . DIR_SEPARATOR . $l_FileName;

			$l_Type = filetype($l_FileName);
			$l_IsDir = ($l_Type == "dir");
            if ($l_Type == "link") 
            {
				$g_SymLinks[] = $l_FileName;
                continue;
            } else 
			if ($l_Type != "file" && (!$l_IsDir)) {
				$g_UnixExec[] = $l_FileName;
				continue;
			}	
						
			$l_Ext = substr($l_FileName, strrpos($l_FileName, '.') + 1);

			$l_NeedToScan = true;
			$l_Ext2 = substr(strstr(basename($l_FileName), '.'), 1);
			if (in_array(strtolower($l_Ext2), $g_IgnoredExt)) {
                           $l_NeedToScan = false;
            		}

      			// if folder in ignore list
      			$l_Skip = false;
      			for ($dr = 0; $dr < count($g_DirIgnoreList); $dr++) {
      				if (($g_DirIgnoreList[$dr] != '') &&
      				   preg_match('#' . $g_DirIgnoreList[$dr] . '#', $l_FileName, $l_Found)) {
      				   if (!in_array($g_DirIgnoreList[$dr], $l_SkipSample)) {
                                      $l_SkipSample[] = $g_DirIgnoreList[$dr];
                                   } else {
        		             $l_Skip = true;
                                     $l_NeedToScan = false;
                                   }
      				}
      			}
      					
			if (getRelativePath($l_FileName) == "./" . INTEGRITY_DB_FILE) $l_NeedToScan = false;

			if ($l_IsDir)
			{
				// skip on ignore
				if ($l_Skip) {
				   $g_SkippedFolders[] = $l_FileName;
				   continue;
				}
				
				$l_BaseName = basename($l_FileName);

				$l_DirCounter++;

				$g_Counter++;
				$g_FoundTotalDirs++;

				QCR_IntegrityCheck($l_FileName);

			} else
			{
				if ($l_NeedToScan)
				{
					$g_FoundTotalFiles++;
					$g_Counter++;
				}
			}
			
			if (!$l_NeedToScan) continue;

			if (IMAKE) {
				write_integrity_db_file($l_FileName);
				continue;
			}

			// ICHECK
			// skip if known and not modified.
			if (icheck($l_FileName)) continue;
			
			$l_Buffer .= getRelativePath($l_FileName);
			$l_Buffer .= $l_IsDir ? DIR_SEPARATOR . "\n" : "\n";

			if (strlen($l_Buffer) > 32000)
			{
				file_put_contents(QUEUE_FILENAME, $l_Buffer, FILE_APPEND) or die2("Cannot write to file " . QUEUE_FILENAME);
				$l_Buffer = '';
			}

		}

		closedir($l_DIRH);
	}
	
	if (($l_RootDir == ROOT_PATH) && !empty($l_Buffer)) {
		file_put_contents(QUEUE_FILENAME, $l_Buffer, FILE_APPEND) or die2("Cannot write to file ".QUEUE_FILENAME);
		$l_Buffer = '';
	}

	if (($l_RootDir == ROOT_PATH)) {
		write_integrity_db_file();
	}

}


function getRelativePath($l_FileName) {
	return "./" . substr($l_FileName, strlen(ROOT_PATH) + 1) . (is_dir($l_FileName) ? DIR_SEPARATOR : '');
}
/**
 *
 * @return true if known and not modified
 */
function icheck($l_FileName) {
	global $g_IntegrityDB, $g_ICheck;
	static $l_Buffer = '';
	static $l_status = array( 'modified' => 'modified', 'added' => 'added' );
    
	$l_RelativePath = getRelativePath($l_FileName);
	$l_known = isset($g_IntegrityDB[$l_RelativePath]);

	if (is_dir($l_FileName)) {
		if ( $l_known ) {
			unset($g_IntegrityDB[$l_RelativePath]);
		} else {
			$g_IntegrityDB[$l_RelativePath] =& $l_status['added'];
		}
		return $l_known;
	}

	if ($l_known == false) {
		$g_IntegrityDB[$l_RelativePath] =& $l_status['added'];
		return false;
	}

	$hash = is_file($l_FileName) ? hash_file('sha1', $l_FileName) : '';
	
	if ($g_IntegrityDB[$l_RelativePath] != $hash) {
		$g_IntegrityDB[$l_RelativePath] =& $l_status['modified'];
		return false;
	}

	unset($g_IntegrityDB[$l_RelativePath]);
	return true;
}

function write_integrity_db_file($l_FileName = '') {
	static $l_Buffer = '';

	if (empty($l_FileName)) {
		empty($l_Buffer) or file_put_contents('compress.zlib://' . INTEGRITY_DB_FILE, $l_Buffer, FILE_APPEND) or die2("Cannot write to file " . INTEGRITY_DB_FILE);
		$l_Buffer = '';
		return;
	}

	$l_RelativePath = getRelativePath($l_FileName);
		
	$hash = is_file($l_FileName) ? hash_file('sha1', $l_FileName) : '';

	$l_Buffer .= "$l_RelativePath|$hash\n";
	
	if (strlen($l_Buffer) > 32000)
	{
		file_put_contents('compress.zlib://' . INTEGRITY_DB_FILE, $l_Buffer, FILE_APPEND) or die2("Cannot write to file " . INTEGRITY_DB_FILE);
		$l_Buffer = '';
	}
}

function load_integrity_db() {
	global $g_IntegrityDB;
	file_exists(INTEGRITY_DB_FILE) or die2('Not found ' . INTEGRITY_DB_FILE);

	$s_file = new SplFileObject('compress.zlib://'.INTEGRITY_DB_FILE);
	$s_file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

	foreach ($s_file as $line) {
		$i = strrpos($line, '|');
		if (!$i) continue;
		$g_IntegrityDB[substr($line, 0, $i)] = substr($line, $i+1);
	}

	$s_file = null;
}


function OptimizeSignatures()
{
	global $g_DBShe, $g_FlexDBShe, $gX_FlexDBShe, $gXX_FlexDBShe;
	global $g_JSVirSig, $gX_JSVirSig;
	global $g_AdwareSig;
	global $g_PhishingSig;
	global $g_ExceptFlex, $g_SusDBPrio, $g_SusDB;

	(AI_EXPERT == 2) && ($g_FlexDBShe = array_merge($g_FlexDBShe, $gX_FlexDBShe, $gXX_FlexDBShe));
	(AI_EXPERT == 1) && ($g_FlexDBShe = array_merge($g_FlexDBShe, $gX_FlexDBShe));
	$gX_FlexDBShe = $gXX_FlexDBShe = array();

	(AI_EXPERT == 2) && ($g_JSVirSig = array_merge($g_JSVirSig, $gX_JSVirSig));
	$gX_JSVirSig = array();

	$count = count($g_FlexDBShe);

	for ($i = 0; $i < $count; $i++) {
		if ($g_FlexDBShe[$i] == '[a-zA-Z0-9_]+?\(\s*[a-zA-Z0-9_]+?=\s*\)') $g_FlexDBShe[$i] = '\((?<=[a-zA-Z0-9_].)\s*[a-zA-Z0-9_]++=\s*\)';
		if ($g_FlexDBShe[$i] == '([^\?\s])\({0,1}\.[\+\*]\){0,1}\2[a-z]*e') $g_FlexDBShe[$i] = '(?J)\.[+*](?<=(?<d>[^\?\s])\(..|(?<d>[^\?\s])..)\)?\g{d}[a-z]*e';
		if ($g_FlexDBShe[$i] == '$[a-zA-Z0-9_]\{\d+\}\s*\.$[a-zA-Z0-9_]\{\d+\}\s*\.$[a-zA-Z0-9_]\{\d+\}\s*\.') $g_FlexDBShe[$i] = '\$[a-zA-Z0-9_]\{\d+\}\s*\.\$[a-zA-Z0-9_]\{\d+\}\s*\.\$[a-zA-Z0-9_]\{\d+\}\s*\.';

		$g_FlexDBShe[$i] = str_replace('http://.+?/.+?\.php\?a', 'http://[^?\s]++(?<=\.php)\?a', $g_FlexDBShe[$i]);
		$g_FlexDBShe[$i] = preg_replace('~\[a-zA-Z0-9_\]\+\K\?~', '+', $g_FlexDBShe[$i]);
		$g_FlexDBShe[$i] = preg_replace('~^\\\\[d]\+&@~', '&@(?<=\d..)', $g_FlexDBShe[$i]);
		$g_FlexDBShe[$i] = str_replace('\s*[\'"]{0,1}.+?[\'"]{0,1}\s*', '.+?', $g_FlexDBShe[$i]);
		$g_FlexDBShe[$i] = str_replace('[\'"]{0,1}.+?[\'"]{0,1}', '.+?', $g_FlexDBShe[$i]);

		$g_FlexDBShe[$i] = preg_replace('~^\[\'"\]\{0,1\}\.?|^@\*|^\\\\s\*~', '', $g_FlexDBShe[$i]);
		$g_FlexDBShe[$i] = preg_replace('~^\[\'"\]\{0,1\}\.?|^@\*|^\\\\s\*~', '', $g_FlexDBShe[$i]);
	}

	optSig($g_FlexDBShe);

	optSig($g_JSVirSig);
	optSig($g_AdwareSig);
	optSig($g_PhishingSig);
        optSig($g_SusDB);
        //optSig($g_SusDBPrio);
        //optSig($g_ExceptFlex);

        // convert exception rules
        $cnt = count($g_ExceptFlex);
        for ($i = 0; $i < $cnt; $i++) {                		
            $g_ExceptFlex[$i] = trim(UnwrapObfu($g_ExceptFlex[$i]));
            if (!strlen($g_ExceptFlex[$i])) unset($g_ExceptFlex[$i]);
        }

        $g_ExceptFlex = array_values($g_ExceptFlex);
}

function optSig(&$sigs)
{
	$sigs = array_unique($sigs);

	// Add SigId
	foreach ($sigs as &$s) {
		$s .= '(?<X' . myCheckSum($s) . '>)';
	}
	unset($s);
	
	$fix = array(
		'([^\?\s])\({0,1}\.[\+\*]\){0,1}\2[a-z]*e' => '(?J)\.[+*](?<=(?<d>[^\?\s])\(..|(?<d>[^\?\s])..)\)?\g{d}[a-z]*e',
		'http://.+?/.+?\.php\?a' => 'http://[^?\s]++(?<=\.php)\?a',
		'\s*[\'"]{0,1}.+?[\'"]{0,1}\s*' => '.+?',
		'[\'"]{0,1}.+?[\'"]{0,1}' => '.+?'
	);

	$sigs = str_replace(array_keys($fix), array_values($fix), $sigs);
	
	$fix = array(
		'~^\\\\[d]\+&@~' => '&@(?<=\d..)',
		'~^((\[\'"\]|\\\\s|@)(\{0,1\}\.?|[?*]))+~' => ''
	);

	$sigs = preg_replace(array_keys($fix), array_values($fix), $sigs);

	optSigCheck($sigs);

	$tmp = array();
	foreach ($sigs as $i => $s) {
		if (!preg_match('#^(?>(?!\.[*+]|\\\\\d)(?:\\\\.|\[.+?\]|.))+$#', $s)) {
			unset($sigs[$i]);
			$tmp[] = $s;
		}
	}
	
	usort($sigs, 'strcasecmp');
	$txt = implode("\n", $sigs);

	for ($i = 24; $i >= 1; ($i > 4 ) ? $i -= 4 : --$i) {
	    $txt = preg_replace_callback('#^((?>(?:\\\\.|\\[.+?\\]|[^(\n]|\((?:\\\\.|[^)(\n])++\))(?:[*?+]\+?|\{\d+(?:,\d*)?\}[+?]?|)){' . $i . ',})[^\n]*+(?:\\n\\1(?![{?*+]).+)+#im', 'optMergePrefixes', $txt);
	}

	$sigs = array_merge(explode("\n", $txt), $tmp);
	
	optSigCheck($sigs);
}

function optMergePrefixes($m)
{
	$prefix = $m[1];
	$prefix_len = strlen($prefix);

	$len = $prefix_len;
	$r = array();

	$suffixes = array();
	foreach (explode("\n", $m[0]) as $line) {
	  $s = substr($line, $prefix_len);
	  $len += strlen($s);
	  if ($len > 8000) {
	    $r[] = $prefix . '(?:' . implode('|', $suffixes) . ')';
	    $suffixes = array();
	    $len = $prefix_len + strlen($s);
	  }
	  $suffixes[] = $s;
	}

	if (!empty($suffixes)) {
	  $r[] = $prefix . '(?:' . implode('|', $suffixes) . ')';
	}
	
	return implode("\n", $r);
}

function optMergePrefixes_Old($m)
{
	$prefix = $m[1];
	$prefix_len = strlen($prefix);

	$suffixes = array();
	foreach (explode("\n", $m[0]) as $line) {
	  $suffixes[] = substr($line, $prefix_len);
	}

	return $prefix . '(?:' . implode('|', $suffixes) . ')';
}

/*
 * Checking errors in pattern
 */
function optSigCheck(&$sigs)
{
	$result = true;

	foreach ($sigs as $k => $sig) {
                if (trim($sig) == "") {
                   if (DEBUG_MODE) {
                      echo("************>>>>> EMPTY\n     pattern: " . $sig . "\n");
                   }
	           unset($sigs[$k]);
		   $result = false;
                }

		if (@preg_match('#' . $sig . '#smiS', '') === false) {
			$error = error_get_last();
                        if (DEBUG_MODE) {
			   echo("************>>>>> " . $error['message'] . "\n     pattern: " . $sig . "\n");
                        }
			unset($sigs[$k]);
			$result = false;
		}
	}
	
	return $result;
}

function _hash_($text)
{
	static $r;
	
	if (empty($r)) {
		for ($i = 0; $i < 256; $i++) {
			if ($i < 33 OR $i > 127 ) $r[chr($i)] = '';
		}
	}

	return sha1(strtr($text, $r));
}

function check_whitelist($list, &$snum) 
{
	if (empty($list)) return array();
	
	$file = dirname(__FILE__) . '/AIBOLIT-WHITELIST.db';

	$snum = max(0, @filesize($file) - 1024) / 20;
	stdOut("\nLoaded " . ceil($snum) . " known files\n");
	
	sort($list);

	$hash = reset($list);
	
	$fp = @fopen($file, 'rb');
	
	if (false === $fp) return array();
	
	$header = unpack('V256', fread($fp, 1024));
	
	$result = array();
	
	foreach ($header as $chunk_id => $chunk_size) {
		if ($chunk_size > 0) {
			$str = fread($fp, $chunk_size);
			
			do {
				$raw = pack("H*", $hash);
				$id = ord($raw[0]) + 1;
				
				if ($chunk_id == $id AND binarySearch($str, $raw)) {
					$result[] = $hash;
				}
				
			} while ($chunk_id >= $id AND $hash = next($list));
			
			if ($hash === false) break;
		}
	}
	
	fclose($fp);

	return $result;
}


function binarySearch($str, $item)
{
	$item_size = strlen($item);
	
	if ( $item_size == 0 ) return false;
	
	$first = 0;

	$last = floor(strlen($str) / $item_size);
	
	while ($first < $last) {
		$mid = $first + (($last - $first) >> 1);
		$b = substr($str, $mid * $item_size, $item_size);
		if (strcmp($item, $b) <= 0)
			$last = $mid;
		else
			$first = $mid + 1;
	}

	$b = substr($str, $last * $item_size, $item_size);
	if ($b == $item) {
		return true;
	} else {
		return false;
	}
}

function getSigId($l_Found)
{
	foreach ($l_Found as $key => &$v) {
		if (is_string($key) AND $v[1] != -1 AND strlen($key) == 9) {
			return substr($key, 1);
		}
	}
	
	return null;
}

function die2($str) {
  if (function_exists('aibolit_onFatalError')) { aibolit_onFatalError($str); }
  die($str);
}