<?php

class MenuClass {

    function showNav($ds=0) { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $r=$db->query("SELECT * FROM `SMART_NAV` WHERE `STATUS`=1 ORDER BY `POSITION` ASC;"); $n=$db->num_rows($r); $list="";
        for($i=1;$i<=$n;$i++) {
            $text=$db->result($r,$i-1,"TEXT");
            $link=$db->result($r,$i-1,"LINK");
            if ($lang_id=="ru") $text=$db->result($r,$i-1,"TEXT_RU");
            if ($i==$n) $item_end="item-end"; else $item_end="";
            if (!$ds) $list.="<li class=\"list-inline-item $item_end\"><a href=\"https://smartkidbelt.com.ua/$link\">$text</a></li>";
            if ($ds) $list.="<li><a href=\"$link\"><i class='fa fa-bookmark'></i> $text</a></li>";
        }
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function showFaqTitle() { $lang_id=$_COOKIE["lang_id"];
        if ($lang_id=="ru") $title="Часто задаваемые вопросы (FAQ)"; else $title="Поширені запитання (FAQ)";
        $title = iconv("windows-1251", "UTF-8", $title);
        return $title;
    }

    function showFaqList() { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $r=$db->query("SELECT * FROM `SMART_FAQ` WHERE `STATUS`=1 ORDER BY `POSITION`;"); $n=$db->num_rows($r); $list="<ul class='list-inline'>";
        for ($i=1;$i<=$n;$i++) {
            if ($lang_id=="ru") $prefix="_RU"; else $prefix="";
            $question=$db->result($r,$i-1,"QUESTION$prefix");
            $answer=$db->result($r,$i-1,"ANSWER$prefix");
            $id=$db->result($r,$i-1,"ID");
            $list.="
            <div class=\"card\">
                <button id=\"heading$id\" class=\"btn card-header collapsed\" data-toggle=\"collapse\" data-target=\"#collapse$id\" aria-expanded=\"false\" aria-controls=\"collapse$id\">
                    <i class='fa fa-angle-down'></i> $question
                </button>
                <div id=\"collapse$id\" class=\"collapse\" aria-labelledby=\"heading$id\" data-parent=\"#accordion\">
                    <div class=\"card-body\">
                        $answer
                    </div>
                </div>
            </div>
            ";
        }
        $list.="</ul>";
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function showBrands() { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT * FROM `SMART_BRANDS` WHERE `STATUS`=1 ORDER BY `POSITION` ASC;"); $n=$db->num_rows($r); $list="";
        for ($i=1;$i<=$n;$i++) {
            $brand_name=$db->result($r,$i-1,"BRAND_NAME");
            $brand_image=$db->result($r,$i-1,"IMAGE");
            $list.="<div class='brand_card'>
                <div class='brand_card-img'>
                    <img src='https://smartkidbelt.com.ua/images/smart_brands/$brand_image' alt='$brand_name'>
                </div>
            </div>";
        }
        return $list;
    }

    function showBrandsItems() { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT * FROM `SMART_BRANDS` WHERE `STATUS`=1 ORDER BY `POSITION`;"); $n=$db->num_rows($r); $list="";
        for ($i=1;$i<=$n;$i++) {
            $brand_name=$db->result($r,$i-1,"BRAND_NAME");
            $brand_image=$db->result($r,$i-1,"IMAGE");
            $list.="<div class=\"item\">
                <div class='brand_card'>
                    <div class='brand_card-img'>
                        <img src='https://smartkidbelt.com.ua/images/smart_brands/$brand_image' alt='$brand_name'>
                    </div>
                </div>
            </div>";
        }
        return $list;
    }

    function getSmartPayment() { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $r=$db->query("SELECT * FROM `SMART_PAYMENT` WHERE `STATUS`=1;"); $n=$db->num_rows($r); $list="";
        for ($i=1;$i<=$n;$i++) {
            $pay_id=$db->result($r,$i-1,"PAYMENT_ID");
            $caption=$db->result($r,$i-1,"CAPTION");
            if ($lang_id=="ru") $caption=$db->result($r,$i-1,"CAPTION_RU");
            $list.="<option value='$pay_id'>$caption</option>";
        }
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function getSmartPaymentCaption($pay_id) { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT * FROM `SMART_PAYMENT` WHERE `PAYMENT_ID`='$pay_id';");
        $caption=$db->result($r,0,"CAPTION_EN");
        $caption = iconv("windows-1251", "UTF-8", $caption);
        return $caption;
    }

    function getStoreList($brand_id) { $db=DbSingleton::getTokoDb();
        $r=$db->query("SELECT * FROM `SMART_STORES` WHERE `BRAND_ID`='$brand_id' AND `STATUS`=1 ORDER BY `POSITION`;"); $n=$db->num_rows($r);
        $list="<br><a href='#' onclick='$(\"#brand-$brand_id\").toggle();'>смотреть все</a>";
        $list.="<div id='brand-$brand_id' class='brand_card_store'>";
        for ($i=1;$i<=$n;$i++) {
            $address = $db->result($r, $i - 1, "ADDRESS");
            $list.="<a href='#'><i class='fa fa-location-arrow'></i> $address</a><br>";
        }
        $list.="</div>";
        if ($n==0) $list="";
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function showPartners() {
        $images = array_diff(scandir("img/partners"), array('..', '.'));
        $list="<ul class='partners'>";
        foreach ($images as $image) {
            $list.="<li><img src='img/partners/$image' alt='partner'></li>";
        }
        $list.="</ul>";
        return $list;
    }

    function showNews() { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $r=$db->query("SELECT * FROM `SMART_NEWS` WHERE `STATUS`=1 ORDER BY `POSITION`;"); $n=$db->num_rows($r); $list="<ul class='list-inline'>";
        for ($i=1;$i<=$n;$i++) {
            if ($lang_id=="ru") $prefix="_RU"; else $prefix="";
            $news_id=$db->result($r,$i-1,"ID");
            $title=$db->result($r,$i-1,"TITLE$prefix");
            $text=$db->result($r,$i-1,"TEXT$prefix");
            $image=$db->result($r,$i-1,"IMAGE");
            $type_link=$db->result($r,$i-1,"TYPE_LINK");

            $first = strstr($text, '<p>');

            $second = strstr($first, '</p>');

            $text = str_replace($second, "", $first);

            if ($type_link) {
                $image_link="<iframe width=\"100%\" height=\"auto\"
                    src=\"$image\" allowfullscreen>
                    </iframe>";
            } else {
                $image_link="<img src='/img/smart_news/$image' alt='image'>";
            }

            $list.="<div class='row news-card'>
                <div class='col-4'>
                    $image_link
                </div>
                <div class='col-8 text-right'>
                    <h2><b>$title</b></h2>
                    $text
                    <br><a href='?page=$news_id' class='btn btn-primary'>{read_more}</a>
                </div>
            </div>";
        }
        $list.="</ul>";
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function showNewsCard($news_id) { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $r=$db->query("SELECT * FROM `SMART_NEWS` WHERE `ID`='$news_id' LIMIT 1;"); $list="";
        if ($lang_id=="ru") $prefix="_RU"; else $prefix="";
        $text=$db->result($r,0,"TEXT$prefix");
        $image=$db->result($r,0,"IMAGE");
        $type_link=$db->result($r,0,"TYPE_LINK");

        if ($type_link) {
            $image_link = "<iframe width=\"100%\" height='500px'
                    src=\"$image\" allowfullscreen>
                    </iframe>";
        } else $image_link="";

        $list.="<div class='row news-card'>
            <div class='col-12'>
                <p>$text</p>
                $image_link
            </div>
        </div>";
        $list = iconv("windows-1251", "UTF-8", $list);
        return $list;
    }

    function getNewsTitle($news_id) { $db=DbSingleton::getTokoDb(); $lang_id=$_COOKIE["lang_id"];
        $title="";
        if ($news_id=="") {
            if ($lang_id=="ru") $title="СМИ о нас - отзывы о Smart Kid Belt";
            else $title="Засоби масової інформації про нас - відгуки про Smart Kid Belt";
        }
        if ($news_id>0) {
            if ($lang_id=="ru") $prefix="_RU"; else $prefix="";
            $r=$db->query("SELECT * FROM `SMART_NEWS` WHERE `ID`='$news_id' LIMIT 1;");
            $title=$db->result($r,0,"TITLE$prefix");
        }
        $title = iconv("windows-1251", "UTF-8", $title);
        return $title;
    }

    function translateContent($content) { $lang_id=$_COOKIE["lang_id"];
        if ($lang_id=="ru") $lang=1; else $lang=0;
        $variable = [
            "{smart_title}"=>   [0=>"Smart Kid Belt - безпечна альтернатива автокріслу та бустеру.",    1=>"Smart Kid Belt - безопасная альтернатива автокреслу и бустеру."],
            "{smart_h1}"=>      [0=>"безпечна альтернатива автокріслу та бустеру.",                     1=>"безопасная альтернатива автокреслу и бустеру."],
            "{buy_cap}"=>       [0=>"Купити",                                                           1=>"Купить"],
            "{smartbelt_cap}"=> [0=>"Смарт Кід Белт",                                                   1=>"Смарт Кид Белт"],
            "{menu_cap}"=>      [0=>"Меню",                                                             1=>"Меню"],
            "{connect_cap}"=>   [0=>"Зв'язатися з нами",                                                1=>"Связаться с нами"],
            "{subscribe_cap}"=> [0=>"Підписуйтесь",                                                     1=>"Подписывайтесь"],
            "{whats_cap}"=>     [0=>"Що таке Smart Kid Belt?",                                          1=>"Что такое Smart Kid Belt?"],
            "{effect_cap}"=>    [0=>"Ефективний та безпечний",                                          1=>"Эффективный и безопасный"],
            "{found_cap}"=>     [0=>"Наші партнери в Україні",                                          1=>"Наши партнеры в Украине"],
            "{benefits_cap}"=>  [0=>"Багато переваг",                                                   1=>"Много преимуществ"],
            "{hands_cap}"=>     [0=>"Завжди під рукою",                                                 1=>"Всегда под рукой"],
            "{transport_cap}"=> [0=>"Для кожного транспортного засобу",                                 1=>"Для каждого транспортного средства"],
            "{test_cap}"=>      [0=>"Тести",                                                            1=>"Тесты"],
            "{partners_cap}"=>  [0=>"Наші партнери в Європейському Союзі",                              1=>"Наши партнеры в Европейском Союзе"],
            "{besmart_cap}"=>   [0=>"для рідних і близьких - обирайте",                                 1=>"для родных и близких - выбирайте"],
            "{read_more}"=>     [0=>"Читати далі",                                                      1=>"Читать далее"],
            "{lang_sel_cap}"=>  [0=>"UA",                                                               1=>"RU"],
            "{more_cap}"=>      [0=>"Докладніше",                                                       1=>"Детальнее"],
            "{wanna_smart}"=>   [0=>"Хочу бути SMART",                                                  1=>"Хочу быть SMART"],
            "{smart_trust}"=>   [0=>"нам довіряють своїх дітей в усьому світі.",                        1=>"нам доверяют своих детей во всем мире."],
            "{crash_cap}"=>     [0=>"Результат краш-тесту",                                             1=>"Результат краш-тесту"],
            "{order_cap}"=>     [0=>"Замовити",                                                         1=>"Заказать"]
        ];
        foreach ($variable as $key=>$value) {
            $content=str_replace("$key",iconv("windows-1251", "UTF-8", $value[$lang]),$content);
        }
        return $content;
    }

}