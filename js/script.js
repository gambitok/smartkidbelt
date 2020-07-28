$(document).ready(function() {

    $(function() {
        $('.lazy').lazy();
    });

    lazyframe('.lazyframe',{
        vendor: undefined,
        id: undefined,
        src: undefined,
        thumbnail: undefined,
        title: undefined,
        apikey: undefined,
        debounce: 250,
        lazyload: true,
        initinview: false

    });

    $("#phone").mask("+38 (999) 999-99-99");

    AOS.init();

    //
    // $(".owl-carousel").owlCarousel({
    //     margin:10,
    //     nav:true,
    //     navText: ["<i class='fa fa-angle-left'></i> LEFT", "RIGHT <i class='fa fa-angle-right'></i>"]
    // });

    var owl = $(".owl-carousel");

    if (detectmob()) {
        owl.owlCarousel({
            loop:true,
            nav:true,
            dots:false,
            margin:15,
            autoplay:true,
            autoplayTimeout:12000,
            autoplayHoverPause:true,
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:5,
                    nav:true,
                    loop:false
                }
            }
        });
    } else {
        owl.owlCarousel({
            loop:true,
            nav:true,
            dots:false,
            margin:15,
            autoplay:true,
            autoplayTimeout:12000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:4,
                    nav:true,
                    loop:true
                }
            }
        });
    }

    $(".owl-carousel2").owlCarousel({
        margin:10,
        nav:true
    });

    $("#amount").inputSpinner();

    $('.dropdown-toggle').dropdown();

    // fixed top search
    var $win = $(window), $fixed = $(".fixed-nav"), limit1 = 240;
    function tgl (state) { $fixed.toggleClass("hidden", state); }
    $win.on("scroll", function () {
        var top = $win.scrollTop();
        if (top < limit1) { tgl(true); } else { tgl(false); }
    });

});

function detectmob() {
    if (navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)) {
        return true;
    } else {
        return false;
    }
}



$(document).on("click", 'a[href^="#"]', function (event) {
    event.preventDefault();
    $("html, body").animate({
        scrollTop: $($.attr(this, "href")).offset().top
    }, 500);
});

function validateField() {
    let name=$("#newname").val();
    let phone=$("#phone").val();
    let valid_phone = phone.charAt(5);
    let amount=parseInt($("#amount").val()); if (isNaN(amount)) amount = 0;
    let city=$("#newcity").val();
    let post=$("#newpost").val();
    let del=$("#del_type option:selected").val();
    let message=$("#message").val();
    let message2=$("#message2").val();

    if (name==="") { $("#newname").addClass("is-invalid"); } else { $("#newname").removeClass("is-invalid"); }
    if (phone.length<10) { $("#phone").addClass("is-invalid"); } else { $("#phone").removeClass("is-invalid"); }
    if (valid_phone!=="0") { $("#phone").addClass("is-invalid"); } else { $("#phone").removeClass("is-invalid"); }
    if (amount===0) { $("#amount").addClass("is-invalid"); } else { $("#amount").removeClass("is-invalid"); }
    if (city==="") { $("#newcity").addClass("is-invalid"); } else { $("#newcity").removeClass("is-invalid"); }
    if (post==="") { $("#newpost").addClass("is-invalid"); } else { $("#newpost").removeClass("is-invalid"); }
    if (del==="0") { $("#del_type").addClass("is-invalid"); } else { $("#del_type").removeClass("is-invalid"); }

    if (valid_phone!=="0") {
        alert(message2);
        return false;
    }

    if (name==="" || phone.length<10 || amount===0 || city==="" || post==="" || del==="0") {
        alert(message);
        return false;
    }
}

function isValidFields() {
    $("#newname").removeClass("is-invalid");
    $("#phone").removeClass("is-invalid");
    $("#amount").removeClass("is-invalid");
    $("#newcity").removeClass("is-invalid");
    $("#newpost").removeClass("is-invalid");
    $("#del_type").removeClass("is-invalid");
}

function getSumm() {
    let amount = parseInt($("#amount").val());
    let default_summ = $("#default_summ").val();
    let summ = default_summ * amount;
    let cur = $("#currency").val();
    $("#summ").val(summ+" "+cur);

    let del_sum1 = summ*0.02+20;

    let del_sum2 = 0;
    let start_del =  44;

    for (let i = 1; i <= amount; i++) {
        if (amount===1) {del_sum2=start_del;break;}
        if (i===1) {del_sum2=start_del;}
        if (i>1) {
            if (i%2===0) {del_sum2+=3;}
            else {del_sum2+=9;}
        }
    }

    $("#del_summ1").text(del_sum1);
    $("#del_summ2").text(del_sum2);

    return true;
}

function getDelivery() {
    let del=$("#del_type option:selected").val();
    if (del==="0") { $('#del_info2').addClass('d-none');$('#del_summ').addClass('d-none'); }
    if (del==="34") { $('#del_info2').removeClass('d-none');$('#del_summ').addClass('d-none'); }
    if (del==="35") { $('#del_info2').addClass('d-none');$('#del_summ').removeClass('d-none'); }
    return del;
}

function setCookie(name, value, props) {

    props = { path: '/' };

    props = props || {};
    var exp = props.expires;

    if (typeof exp == "number" && exp) {
        var d = new Date();
        d.setTime(d.getTime() + exp*1000);
        exp = props.expires = d
    }

    if(exp && exp.toUTCString) { props.expires = exp.toUTCString() }
    value = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;

    for(var propName in props){
        updatedCookie += "; " + propName;
        var propValue = props[propName];
        if(propValue !== true){ updatedCookie += "=" + propValue }
    }

    document.cookie = updatedCookie
}


