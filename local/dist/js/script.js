// jQuery(document).ready(function ($) {
//     setTimeout(function() {
//         $.magnificPopup.open({
//             items: {
//                 src: '#popupHello'
//             },
//             mainClass: 'mfp-fade'
//         });
//     }, 1000);
// });


  String.prototype.publicFloat = function() {
        var val = parseFloat(this);
        val = parseInt((val * 100)) / 100;
        return val;
  };

    Number.prototype.publicFloat = String.prototype.publicFloat;


  // Navgoco acordion
  $(".catalog__acordion").navgoco({accordion: true});
  $(".acord-main").navgoco({accordion: true});
  // $(".acord__item").click(function(event) {
  //   $('.acord__item').addClass('open')
  //   $(this).addClass('open')
  // });
  $('.acord__link').click(function(event) {
    $('.acord__item-2').removeClass('jsAcordActive')
    if ($('.acord__item').hasClass('open')) {
      $('.b-mac').addClass('fadeOut')
    }
    else {
      $('.b-mac').removeClass('fadeOut')
    }
  });
  $('.acord__item-2').click(function(event) {
    $('.acord__item-2').removeClass('jsAcordActive')
    $(this).addClass('jsAcordActive')
  });
  // jQuery price slider
  $(function() {
    $( "#slider-range" ).slider({
      range: "min",
      value: 50,
      min: 1,
      max: 100,
      slide: function( event, ui ) {
        $( "#amount" ).val( ui.value );
      }
    });
    $( "#amount" ).val( $( "#slider-range" ).slider( "value" ) );
  });

  // Reset price slider
  function resetSlider() {
    $("#slider-range").slider('value', 50);
    $( "#amount" ).val(50);
  } 
  
  // Filter
  $(".filter__checkbox:checkbox").click(function(){
    $(this).closest('.b-wave').find('.filter__result__products').addClass('animated fadeInUp js_resultActive')
  });

  var count = 0
  $(function() { 
       updateCount(); 
       $('#js_filter1 input[type=checkbox]').change(function() { 
             updateCount(this.checked ? 1 : -1); 
       });
  }); 

  function updateCount(a) {
       count = a ? count + a : $('#js_filter1 input[type=checkbox]:checked').length;
       $('.products__cont').text(count);
  }

  $('.reset__filetr').click(function() {
    $(this).closest('.b-wave').find('input:checkbox').removeAttr('checked');
    $(this).closest('.b-wave').find('.filter__result__products').removeClass('js_resultActive')
    count = 0;
    $(this).closest('.b-wave').find('.products__cont').text(count);
    return false;
  });

  // Reset filter
  function resetAll() {
    $("#slider-range").slider('value', 50);
    $( "#amount" ).val(50);
    $('.b-wave').find('input:checkbox').removeAttr('checked');
    $('.b-wave').find('.filter__result__products').removeClass('js_resultActive')
    count = 0;
    $('.products__cont').text(count);
  } 

  // Ashan slider 
  var productSlider = $('.js-products-slider-2'),
      productSliderOptions = {
          dots: false,
          arrows: true,
          slide: '.js-products-slider-item',
          slidesToShow: 4,
          slidesToScroll: 1
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }
  var productSlider = $('.js-products-slider-3'),
      productSliderOptions = {
          dots: true,
          arrows: true,
          slide: '.js-products-slider-item',
          slidesToShow: 1,
          slidesToScroll: 1
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }
    var productSlider = $('.js-products-slider-4'),
      productSliderOptions = {
          dots: true,
          arrows: false,
          slide: '.js-products-slider-item',
          slidesToShow: 3,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 5000
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }

    var productSlider = $('.js-products-slider-5'),
      productSliderOptions = {
          dots: true,
          arrows: true,
          slide: '.js-products-slider-item',
          slidesToShow: 4,
          slidesToScroll: 1,
          autoplaySpeed: 5000,
          autoplay: true
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }
  var productSlider = $('.js-products-slider-6'),
      productSliderOptions = {
          dots: true,
          arrows: true,
          slide: '.js-products-slider-item',
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplaySpeed: 5000
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }
  var productSlider = $('.js-products-slider-m-1, .js-products-slider-m-2, .js-products-slider-m-3, .js-products-slider-m-4'),
      productSliderOptions = {
          dots: false,
          arrows: true,
          slide: '.js-products-slider-item',
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplaySpeed: 5000
      };
  if (productSlider.length) {
      productSlider.slick(productSliderOptions);
  }

// Height detect funciton

  function heightDetect(){
    $('.lk__col1').css( 
      'height', $('.b-lk__content').height()
    );
  };
  function widthDetect(){
    $('.js-products-slider-5 .slick-list').css( 
      'width', $('.b-popup__slider').width()
    );
  };
  $('button').click(function(event) {
   widthDetect();
  });
  widthDetect();
  heightDetect();


$(function () {
   /* $('.index_products_basket').each(function () {
        var $plus = $(this).find('.b-button_plus');
        var $minus = $(this).find('.b-button_minus');
        var $basketBtn = $(this).find('.add_basket_btn');
        var $quantity = $(this).find('.b-product-preview__input');

        $plus.on('click', function () {
            var quantity = $quantity.val();
            quantity++;
            $quantity.val(quantity);
        });
        $minus.on('click', function () {
            var quantity = $quantity.val();
            quantity--;
            if(quantity <= 0){
                quantity = 1;
            }
            $quantity.val(quantity);
        });

        $basketBtn.on('click', function () {
            var id = $(this).data('id');
            var url = '/local/ajax/basket.php?ID='+ id +'&quantity='+ $quantity.val()+ '&sessid='+ BX.bitrix_sessid();
            $.get(url, function (result) {
                if(result.status == 1){
                    $('#add_cart_check').show(0, function () {
                        $.magnificPopup.open({
                            items: {
                                src: '#add_cart_check',
                                type: 'inline'
                            }
                        });
                    });

                    $.get('/local/ajax/basket.php?getBasket=Y', function (res) {
                        $('#basket_update').html(res);
                    });
                } else {
                    // todo сообщение об ошибке
                }
            }, 'json')
        });
    });*/
});