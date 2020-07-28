<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Europe/Kiev");
define('RDD', dirname (__FILE__));
require_once (RDD."/lib/DbSingleton.php");
require_once (RDD."/lib/menu_class.php");
require_once (RDD."/lib/catalogue_class.php");
$db=DbSingleton::getTokoDb(); $cat=new Catalogue;

$client_name=$cat->getUrlString($_POST['newname']);
$phone=$cat->getUrlString($_POST['phone']);
$amount=$cat->getUrlNumber($_POST['amount']);
$city=$cat->getUrlString($_POST['newcity']);
$department=$cat->getUrlString($_POST['newpost']);
$del_type=$cat->getUrlNumber($_POST['del_type']);

if ($client_name=="" || $phone=="" || $amount==0 || $city=="" || $department=="" || $del_type==0) {

    $print="Ви ввели не вірні дані!";
    if ($_COOKIE["lang_id"]=="ru") { $print="Вы ввели не верные данные!"; }

} else {

    $date=date("Y-m-d H:i:s"); $client_ip=$_SERVER['REMOTE_ADDR'];

    $print="Дякую за замовлення. Ми зв'яжемося з Вами найближчим часом!";
    if ($_COOKIE["lang_id"]=="ru") { $print="Спасибо за заказ. Мы свяжемся с Вами в ближайшее время!"; }

    require_once "index.php"; require_once "class.phpmailer.php";
    $del_cap="";
//$del_cap = $menu->getSmartPaymentCaption($del_type);

    $list="
    <p>NAME: $client_name</p>
    <p>PHONE: $phone</p>
    <p>AMOUNT: $amount</p>
    <p>DATE: $date</p>
    <p>CITY: $city</p>
    <p>NOVA POSHTA: $department</p>
    <p>DELIVERY TYPE: $del_cap</p>";

    $client_name=iconv("UTF-8","windows-1251",$client_name);
    $city=iconv("UTF-8","windows-1251",$city);
    $department=iconv("UTF-8","windows-1251",$department);
    $del_type=iconv("UTF-8","windows-1251",$del_type);

    $db->query("INSERT INTO `SMART_MAIL` (`client_name`,`phone`,`amount`,`city`,`department`,`del_type`,`client_ip`) VALUES ('$client_name','$phone','$amount','$city','$department','$del_type','$client_ip');");

    $order_id = 0;
    $order_id = $cat->finishOrder($client_name, $phone, $city, $del_type, $department, $amount);

    $list.="<p>ORDER: $order_id</p>";

    if ($client_name!="" && $phone!="" && $amount!="" && $city!="" && $department!="" && $del_type!="") {
        $mail = new PHPMailer();
        try {
            $mail->isMail();
            $mail->addReplyTo('noreply@toko.ua', 'TOKO GROUP');
            $mail->addAddress("smartkidbelt@outlook.com");
            $subject="smartkidbelt - $date";
            $mail->Subject =$subject;
            $mail->msgHTML($list);
            $mail->send();
        }  catch (Exception $e) { }
    }

}

$print=iconv("windows-1251","utf-8",$print);

$form="<div class='send_mail'>
    <div class='container'>
        <div class='row'>
            $print
        </div>
    </div>
</div>";

print $form;