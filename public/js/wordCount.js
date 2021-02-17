(() => {
    $(function (){
        const count = $(".js-text").text().replace(/\n/g, "改行").length;
        const now_count = 300 - count;

        if (count > 300) {
            $(".js-text-count").css("color","red");
        }
        $(".js-text-count").text( "残り" + now_count + "文字");

        $(".js-text").on("keyup", function() {
            const count = $(this).val().replace(/\n/g, "改行").length;
            const now_count = 300 - count;

            if (count > 300) {
                $(".js-text-count").css("color","red");
            } else {
                $(".js-text-count").css("color","black");
            }
            $(".js-text-count").text( "残り" + now_count + "文字");
        });
    });
})();