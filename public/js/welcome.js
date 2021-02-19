// ローディング
(() => {
    window.onload = function() {
        const spinner = document.getElementById('js-loading');
        spinner.classList.add('js-loaded');

        const targetElement = document.querySelectorAll('.top-intro');
        document.addEventListener("scroll", function() {
            for(let i = 0; i < targetElement.length; i++) {
                const getElementDistance = targetElement[i].getBoundingClientRect().top
                + targetElement[i].clientHeight * .6
                if (window.innerHeight > getElementDistance) {
                    targetElement[i].classList.add("show");
                }
            }
        })

    }
})();

// スライド
(() => {
    const swiper = new Swiper('.swiper-container', {
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