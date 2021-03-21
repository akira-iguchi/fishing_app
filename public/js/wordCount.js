// 文字数
(() => {
    let max = 300,
        input_area = document.getElementById("textAreaExplanation"),
        output_lest = document.getElementById("textLestExplanation"),
        attention = document.getElementById("textAttentionExplanation");

    const wordCount = function() {
        let length = input_area.value.replace(/\n/g, '++').length,
            lest =  max - length;
        output_lest.innerText = lest;
        attention.style.display = ( length > max ) ? "block" : "none";
    };

    if (input_area != null) {
        input_area.addEventListener('keyup', wordCount);
    } else {
        max = 100,
        input_area = document.getElementById("textArea"),
        output_lest = document.getElementById("textLest"),
        attention = document.getElementById("textAttention");

        input_area.addEventListener('keyup', wordCount);
    }
})();