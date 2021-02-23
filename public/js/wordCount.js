// 文字数
(() => {
    let max = 300;
        input_area = document.getElementById("textArea"),
        output_lest = document.getElementById("textLest"),
        attention = document.getElementById("textAttention");

    const wordCount = function () {
        let length = input_area.value.replace(/\n/g, '++').length;
        lest =  max - length;
        output_lest.innerText = lest;
        attention.style.display = ( length > max ) ? "block" : "none";
    };

    if (document.getElementById("textArea") != null) {
        input_area.addEventListener('keyup', wordCount);
    } else {
        max = 150;
        input_area = document.getElementById("textAreaComment"),
        output_lest = document.getElementById("textLestComment"),
        attention = document.getElementById("textAttentionComment");

        input_area.addEventListener('keyup', wordCount);
    }
})();