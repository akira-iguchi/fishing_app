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