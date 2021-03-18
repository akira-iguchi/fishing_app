(() => {
    let nextImage = document.getElementById('image2_hidden'),
        img = document.getElementById('file1-preview'),
        errorFile1 = document.getElementById('file1_hidden');

    document.getElementById('image1').addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (nextImage != null) {
            nextImage.removeAttribute("id");
        }

        // ファイルのブラウザ上でのURLを取得する
        const blobUrl = window.URL.createObjectURL(file);

        if (file.type.match("image.*")) {
            img.src = blobUrl;
        } else {
            errorFile1.removeAttribute("id");
        }
    });

    document.getElementById('image2').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const blobUrl = window.URL.createObjectURL(file);

        nextImage = document.getElementById('image3_hidden');
        img = document.getElementById('file2-preview');
        errorFile2 = document.getElementById('file2_hidden');

        if (nextImage != null) {
            nextImage.removeAttribute("id");
        }

        if (file.type.match("image.*")) {
            img.src = blobUrl;
        } else {
            errorFile2.removeAttribute("id");
        }
    });

    document.getElementById('image3').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const blobUrl = window.URL.createObjectURL(file);

        img = document.getElementById('file3-preview');
        errorFile3 = document.getElementById('file3_hidden');

        if (file.type.match("image.*")) {
            img.src = blobUrl;
        } else {
            errorFile3.removeAttribute("id");
        }
    });
})();