<?php

class Catalogue {

    public $art_id=100053510;

    function getUrlString($str) {
        $str = str_replace("'","",$str);
        $str = str_replace("`","",$str);
        $str = str_replace('"',"",$str);
        $str = str_replace("%20"," ",$str);
        $str = str_replace("%60","",$str);
        $str = str_replace("&nbsp;","",$str);
        $str = str_replace("&rsquo;","",$str);
        return $str;
    }

    function getUrlNumber($number) {
        if (!is_numeric($number)) $number=0;
        return $number;
    }

    function getArticleInfo() { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT t2a.*, t2b.BRAND_NAME FROM `T2_ARTICLES` t2a 
            LEFT JOIN `T2_BRANDS` t2b ON t2b.BRAND_ID = t2a.BRAND_ID 
        WHERE t2a.`ART_ID`='$this->art_id' LIMIT 1;");
        $article_nr_displ = $db->result($r,0,"ARTICLE_NR_DISPL");
        $brand_name = $db->result($r,0,"BRAND_NAME");
        $price = $this->getArticlePrice($this->art_id);
        return array($article_nr_displ, $brand_name, $price);
    }

    function getArticlePrice($art){ $dbt = DbSingleton::getTokoDb(); $cash_id=2;
        $price=0; $client_id=10;
        list($price_lvl,$margin_price_lvl,,,)=$this->getDpClientPriceLevels($client_id);
        $query="SELECT t2apr.price_$price_lvl, t2apr.minMarkup, t2apr.cash_id, t2aps.OPER_PRICE, t2si.price_usd as suppl_price_usd
        FROM `T2_ARTICLES` t2a 
            LEFT OUTER JOIN `T2_ARTICLES_PRICE_RATING` t2apr ON (t2apr.art_id=t2a.ART_ID)
            LEFT OUTER JOIN `T2_ARTICLES_PRICE_STOCK` t2aps ON (t2aps.ART_ID=t2a.ART_ID)
            LEFT OUTER JOIN `T2_SUPPL_IMPORT` t2si ON (t2si.art_id=t2a.ART_ID)
        WHERE t2a.ART_ID='$art' AND t2apr.in_use='1' LIMIT 1;";
        $r=$dbt->query($query); $n=$dbt->num_rows($r);
        if ($n==1) {
            $price=$dbt->result($r,0,"price_".$price_lvl);
            $minMarkup=$dbt->result($r,0,"minMarkup");
            $cash_id=$dbt->result($r,0,"cash_id");
            $oper_price=$dbt->result($r,0,"OPER_PRICE");
            $float_price=floatval($price);
            if ($margin_price_lvl>0){
                $price=$float_price+round($price*$margin_price_lvl/100,2);
            }
            if ($margin_price_lvl<0){
                $price_minus=$price+($price*$margin_price_lvl/100);
                $oper_limit=$oper_price+($oper_price*$minMarkup/100);
                if ($price_minus>=$oper_limit) $price=$price_minus;
                else if ($oper_limit>=$price) true; else $price=$oper_limit;
            }
        }

        $cur_usd=$this->getKoursUSD();
        $cur_eur=$this->getKoursEUR();

        //UAH
        if ($cash_id==1) $price=$price*1;

        //USD
        if ($cash_id==2) $price=$price*$cur_usd;

        //EUR
        if ($cash_id==3) $price=$price*$cur_eur;

        $price = $this->getClientPriceRounding($client_id,$price);

        return $price;
    }

    function getDpClientPriceLevels($client_id){ $db = DbSingleton::getDbm();
        $price_lvl=$margin_price_lvl=$price_suppl_lvl=$margin_price_suppl_lvl=$client_vat=0;
        $r=$db->query("SELECT * FROM `A_CLIENTS_CONDITIONS` WHERE `client_id`='$client_id' LIMIT 1;"); $n=$db->num_rows($r);
        if ($n==1){
            $price_lvl=$db->result($r,0,"price_lvl"); $price_lvl++;
            $margin_price_lvl=$db->result($r,0,"margin_price_lvl");
            $price_suppl_lvl=$db->result($r,0,"price_suppl_lvl"); $price_suppl_lvl++;
            $margin_price_suppl_lvl=$db->result($r,0,"margin_price_suppl_lvl");
            $client_vat=$db->result($r,0,"client_vat");
        }
        return array($price_lvl,$margin_price_lvl,$price_suppl_lvl,$margin_price_suppl_lvl,$client_vat);
    }

    function getClientPriceRounding($client_id,$price) { $db=DbSingleton::getDbm();
        if ($client_id>0) {
            $r=$db->query("SELECT * FROM `A_CLIENTS` WHERE `id`='$client_id';"); $n=$db->num_rows($r);
            if ($n>0) {
                $rounding_price = $db->result($r,0,"rounding_price");
                if ($rounding_price==1) $price=round($price*100,-1)/100;
                if ($rounding_price==2) $price=round($price);
            }
        }
        return $price;
    }

    function getKoursUSD() { $db = DbSingleton::getDbm();
        $r=$db->query("SELECT `kours_value` FROM `J_KOURS` WHERE `cash_id`=2 AND `in_use`=1 LIMIT 1;");
        $cur_usd = number_format($db->result($r,0,"kours_value"), 2, '.', '');
        return $cur_usd;
    }

    function getKoursEUR() { $db = DbSingleton::getDbm();
        $r=$db->query("SELECT `kours_value` FROM `J_KOURS` WHERE `cash_id`=3 AND `in_use`=1 LIMIT 1;");
        $cur_eur = number_format($db->result($r,0,"kours_value"), 2, '.', '');
        return $cur_eur;
    }

    function finishOrder($name, $phone, $city, $delivery, $payment, $amount) { $db = DbSingleton::getDbm();
        $cookie=""; $cash_id=1; $client_id=1055; $user_id=905; $tpoint_id=1; $email=""; $carrier_id=148; $region=0; $del=60;

        $brand_id=$this->getBrandArt($this->art_id);
        $suppl_id=0;
        $storage_id=3;
        $price=floatval($this->getArticlePrice($this->art_id));
        $summ=$amount * $price;

        $address=$city;
        $menu=new MenuClass;
        $pay_info = $menu->getSmartPaymentCaption($delivery);
        $del_info=$payment;

        $r=$db->query("SELECT MAX(`id`) as max_order FROM `orders_new`;"); $order_id=intval($db->result($r,0,"max_order"))+1;
        $db->query("INSERT INTO `orders_new` (`id`,`client_id`,`client_user_id`,`cookie_id`,`tpoint_id`,`cash_id`,`name`,`email`,`phone`,`region`,`delivery`,`carrier_id`,`delivery_info`,`payment`,`payment_info`,`price_summ`,`status`) 
        VALUES ($order_id,$client_id,$user_id,'$cookie',$tpoint_id,$cash_id,'$name','$email','$phone',$region,$del,$carrier_id,'m. $address, NP, vid. $del_info',$delivery,'$pay_info',$summ,1);");

        $rmax=$db->query("SELECT MAX(`id`) AS max_order_str FROM `orders_str_new`;"); $max=intval($db->result($rmax,0,"max_order_str"))+1;
        $db->query("INSERT INTO `orders_str_new` (`id`, `order_id`, `suppl_id`, `storage_id`, `art_id`, `brand_id`, `amount`, `price`, `summ`, `status_action`) 
        VALUES ($max, $order_id, '$suppl_id', '$storage_id', '$this->art_id', $brand_id, $amount, $price, $summ, '0');");

        return $max;
    }

    function getBrandArt($art) { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT * FROM `T2_ARTICLES` WHERE `ART_ID`='$art' LIMIT 1;");
        $brand_id=$db->result($r,0,"BRAND_ID");
        return $brand_id;
    }

}