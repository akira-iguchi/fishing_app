// スライド
(() => {
    const swiper = new Swiper('.swiper-container', {
        autoplay: {
            delay: 4000,
            reverseDirection: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
        },
    });
})();