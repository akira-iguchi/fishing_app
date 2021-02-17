(() => {
    let lest = null,
        max = 300,
        input_area = document.getElementById("textArea"),
        output_lest = document.getElementById("textLest"),
        attention = document.getElementById("textAttention");

    input_area.onkeyup = function(){
        let length = input_area.value.replace(/\n/g, '++').length;
        lest =  max - length;
        output_lest.innerText = lest;
        attention.style.display = ( length > max ) ? "block" : "none";
    }
})();