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

//if ($_COOKIE["lang_id"]=="ru") {
//    $theme_htm=RDD."/index_ru.html";if (file_exists("$theme_htm")){ $content = file_get_contents($theme_htm);}
//}

$theme_htm=RDD."/theme/theme.html";if (file_exists("$theme_htm")){ $content = file_get_contents($theme_htm);}

$form_htm=RDD."/tpl/index.html";if (file_exists("$form_htm")){ $form = file_get_contents($form_htm);}

$content=str_replace("{smart_nav}", $menu->showNav(), $content);
$content=str_replace("{smart_nav_mob}", $menu->showNav(1), $content);
$content=str_replace("{smart_payment}", $menu->getSmartPayment(), $content);

$form=str_replace("{official_items}", $menu->showBrands(), $form);
$form=str_replace("{smart_partners}", $menu->showPartners(), $form);
$form = iconv("windows-1251", "UTF-8", $form);

$content = str_replace("{main_window}", $form, $content);
$content = str_replace("{true_price}", $cat->getArticlePrice(100053510), $content);
$content = $menu->translateContent($content);

echo $content;

//$content=str_replace("{smart_nav}", $menu->showNav(), $content);
//$content=str_replace("{smart_payment}", $menu->getSmartPayment(), $content);
//$content=str_replace("{smart_nav_mob}", $menu->showNav(1), $content);
//$content=str_replace("{smart_partners}", $menu->showPartners(), $content);
//$content=str_replace("{official_items}", $menu->showBrands(), $content);
//
//function getPath() {
//    $url=findUrl();
//    $path=findPath();
//    if ($path=="") $path=$url;
//    return $path;
//}
//
//function findUrl() {
//    $link="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
//    $link=parse_url($link);
//    $url=$link["path"];
//    return $url;
//}
//
//function findPath() {
//    $link="https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
//    if (substr($link, -1)!="/") $link.="/";
//    $link=parse_url($link);
//    $url=substr($link["path"],1);
//    $pos=strpos($url,"/");
//    if ($pos) {
//        $path=substr($url,0,$pos+1);
//        $cur_path=substr($path, 0, -1);
//        if ($cur_path=="ua" || $cur_path=="ru" || $cur_path=="en") {
//            if ($cur_path=="ru") $_SESSION["lang"]=1;
//            if ($cur_path=="ua") $_SESSION["lang"]=2;
//            if ($cur_path=="en") $_SESSION["lang"]=3;
//            $url=str_replace_first($path,"",$url);
//            $pos=strpos($url,"/");
//            $path=substr($url,0,$pos);
//        } else {
//            $path=substr($url,0,$pos);
//            $path!=null ? $res=$path : $res=$url;
//        }
//        $path!=null ? $res=$path : $res=$url;
//    } else $res="";
//    return $res;
//}
//
//function str_replace_first($from, $to, $content) {
//    $from="/".preg_quote($from, "/")."/";
//    return preg_replace($from, $to, $content, 1);
//}
//
//$path = getPath();
//
//if ($path=="" || $path=="/") include_once RDD . "/event/main.php";
//elseif (file_exists(RDD . "/event/$path.php")) {
//    include_once RDD . "/event/$path.php";
//} else {
//    include RDD . "/event/404.php";
//}

