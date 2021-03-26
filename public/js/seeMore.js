// もっと見る
(() => {
    function appeared() {
        const appearedSpots = document.querySelectorAll('.spot-hidden');

        for(let i = 0; i < 20; i++){
            appearedSpots[i].classList.remove('spot-hidden');
        }
    };
    appeared();

    document.querySelector('.seeMore').addEventListener('click', e => {
        // .hidden が指定されている .spotをさがす
        const hiddenSpots = document.querySelectorAll('.spot-hidden');

        // .spot-hiddenが1つ以上ある場合、1つ目を表示する
        if(hiddenSpots.length > 0) {
            hiddenSpots[0].classList.remove('spot-hidden');
        }

        // .spot-hiddenが2つ以上ある場合、2つ目を表示する
        if(hiddenSpots.length > 1) {
            hiddenSpots[1].classList.remove('spot-hidden');
        }

        // .spot-hiddenが2つ以上ある場合、2つ目を表示する
        if(hiddenSpots.length > 2) {
            hiddenSpots[2].classList.remove('spot-hidden');
        }

        // .spot-hiddenが3つ以上ある場合 (= 2つを表示してもまだ非表示のものがある場合)
        // ボタン非表示を実行せず中断する
        if(hiddenSpots.length > 3) return;

        // ボタンを非表示にする
        e.currentTarget.classList.add('spot-hidden');
    });
})();