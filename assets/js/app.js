require('../scss/app.scss');
require('../../public/css/fa-svg-with-js.css');
require('bootstrap-sass');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    $('.datumUit').change(function(event){
        datumUit = new Date(event.target.value);
        datumTerug = new Date($('.datumTerug').val());
        timeDiff = Math.abs(datumTerug - datumUit);
        daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        price = parseFloat($('.price').text());
        actualPrice = price * daysDiff;
        priceString = '<i class="fas fa-euro-sign"></i> ' + actualPrice.toFixed(2);
        kortingsMultiplier = $('.totalprice').data('multiplier');
        korting = (actualPrice * kortingsMultiplier).toFixed(2);
        kortingString = '<i class="fas fa-euro-sign"></i> ' + korting;
        $('.actual-price').html(priceString);
        $('.totalprice').html(kortingString);
    })
    $('.datumTerug').change(function(event){
        datumUit = new Date($('.datumUit').val());
        datumTerug = new Date(event.target.value);
        timeDiff = Math.abs(datumTerug - datumUit);
        daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        price = parseFloat($('.price').text());
        actualPrice = price * daysDiff;
        priceString = '<i class="fas fa-euro-sign"></i> ' + actualPrice.toFixed(2);
        kortingsMultiplier = $('.totalprice').data('multiplier');
        korting = (actualPrice * kortingsMultiplier).toFixed(2);
        kortingString = '<i class="fas fa-euro-sign"></i> ' + korting;
        $('.actual-price').html(priceString);
        $('.totalprice').html(kortingString);
    })
});

