document.addEventListener('DOMContentLoaded', function() {
    const scrollElements = document.querySelectorAll(".js-scroll");

    window.addEventListener('scroll', function() {
        scrollElements.forEach((el) => {
            const elementTop = el.getBoundingClientRect().top;
            const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

            if (elementTop <= viewportHeight * 0.8) { // Ajusta el porcentaje según prefieras
                el.classList.add("scrolled");
            }
        });
    });
});
