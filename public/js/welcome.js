// ローディング
(() => {
    window.onload = function() {
        const spinner = document.getElementById('js-loading');
        spinner.classList.add('js-loaded');

        const targetElement = document.querySelectorAll('.top-slider');
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

// もっと見る
(() => {
    function appeared() {
        const appearedSpots = document.querySelectorAll('.spot-hidden');

        for(let i = 0; i < 1; i++){
            appearedSpots[i].classList.remove('spot-hidden');
        }
    };
    appeared();

    document.querySelector('.seeMore').addEventListener('click', e => {
        // .hidden が指定されている .item をさがす
        const hiddenSpots = document.querySelectorAll('.spot-hidden');

        // .hidden.item が1つ以上ある場合、1つ目を表示する
        if(hiddenSpots.length > 0) {
            hiddenSpots[0].classList.remove('spot-hidden');
        }

        // .hidden.item が2つ以上ある場合、2つ目を表示する
        if(hiddenSpots.length > 1) {
            hiddenSpots[1].classList.remove('spot-hidden');
        }

        // .hidden.item が2つ以上ある場合、2つ目を表示する
        if(hiddenSpots.length > 2) {
            hiddenSpots[2].classList.remove('spot-hidden');
        }

        // .hidden.item が3つ以上ある場合 (= 2つを表示してもまだ非表示のものがある場合)
        // ボタン非表示を実行せず中断する
        if(hiddenSpots.length > 3) return;

        // ボタンを非表示にする
        e.currentTarget.classList.add('spot-hidden');
    });
})();