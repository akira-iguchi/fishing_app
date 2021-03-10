(() => {
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];

        // ファイルのブラウザ上でのURLを取得する
        const blobUrl = window.URL.createObjectURL(file);

        // img要素に表示
        const img = document.getElementById('file-preview');
        const errorFile = document.getElementById('file_hidden');

        if (file.type.match("image.*")) {
            img.src = blobUrl;
        } else {
            errorFile.removeAttribute("id");
        }
    });
})();