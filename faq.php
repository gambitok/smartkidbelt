<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);@ini_set('display_errors', true);
date_default_timezone_set("Europe/Kiev");
$content=null;
define('RDD', dirname (__FILE__));

require_once (RDD."/lib/DbSingleton.php");
require_once (RDD."/lib/menu_class.php");
require_once (RDD."/lib/catalogue_class.php");
$db=DbSingleton::getTokoDb(); $dbm=DbSingleton::getDbm(); $menu=new MenuClass; $cat=new Catalogue;

$theme_htm=RDD."/theme/theme_min.html";if (file_exists("$theme_htm")){ $content = file_get_contents($theme_htm);}

$form_htm=RDD."/tpl/faq.html";if (file_exists("$form_htm")){ $form = file_get_contents($form_htm);}

$content=str_replace("{smart_nav}", $menu->showNav(), $content);
$content=str_replace("{smart_nav_mob}", $menu->showNav(1), $content);
$content=str_replace("{smart_payment}", $menu->getSmartPayment(), $content);

$form=str_replace("{faq_title}", $menu->showFaqTitle(), $form);
$form=str_replace("{faq_range}", $menu->showFaqList(), $form);
$form = $menu->translateContent($form);

//$form = iconv("windows-1251", "UTF-8", $form);
$content = str_replace("{main_window}", $form, $content);
$content = str_replace("{true_price}", $cat->getArticlePrice(100053510), $content);
$content = $menu->translateContent($content);

echo $content;