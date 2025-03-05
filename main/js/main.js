const sun = document.querySelector('.sun');
const mountains = document.querySelectorAll('.mountain');
const ground = document.querySelector('.ground');
const sections = document.querySelectorAll(".section");
document.querySelectorAll('.content-div, .content-div2, .content-div3, .content-div4, .content-div5')
.forEach(div => {
    div.addEventListener('click', (event) => {
        event.stopPropagation();
    });
});
const initialPositions = Array.from(mountains).map((mountain) => {
    const transform = getComputedStyle(mountain).transform;
    if (transform !== 'none') {
        const matrix = new DOMMatrix(transform);
        return { x: matrix.m41, y: matrix.m42 };
    }
    return { x: 0, y: 0 };
});

let lastX = 0, lastY = 0;
let ticking = false;
let scrollDepth = -500;
let activeIndex = 0;
let lastScrollDepth = -500;

function updatePositions(x, y) {
    sun.style.transform = `translate3d(${x * 30}px, ${y * 30}px, 0)`;

    mountains.forEach((mountain, index) => {
        let intensity = [2, 3, 4, 4][index] || 3;
        const initialX = initialPositions[index].x;
        const initialY = initialPositions[index].y;
        mountain.style.transform = `translate3d(${initialX + x * intensity}px, ${initialY + y * intensity}px, 0)`;
    });

    ground.style.transform = `translate3d(${x * 10}px, ${y * 5}px, 0)`;
}

window.addEventListener('mousemove', (event) => {
    lastX = (event.clientX / window.innerWidth) - 0.5;
    lastY = (event.clientY / window.innerHeight) - 0.5;

    if (!ticking) {
        window.requestAnimationFrame(() => {
            updatePositions(lastX, lastY);
            ticking = false;
        });
        ticking = true;
    }
});

window.addEventListener("wheel", (event) => {
    event.preventDefault();
    scrollDepth += event.deltaY * 1.5;
    scrollDepth = Math.min(Math.max(scrollDepth, -500), 100);

    let scrollDirection = scrollDepth > lastScrollDepth ? 'down' : 'up';

    sections.forEach((section, index) => {
        if (index === activeIndex) {
            let scaleValue = Math.min(2, 0.8 + (scrollDepth + 500) / 600 * 1.2);
            let zOffset = -900 + scrollDepth;
            
            section.style.opacity = 1;
            
            if (scrollDepth >= 0) {
                scaleValue = 2;
                section.style.opacity = 1 - scrollDepth / 100;
                if (scrollDepth >= 50 && scrollDirection === 'down') {
                    activeIndex = (activeIndex + 1) % sections.length;
                    scrollDepth = -500;
                }
            } else if (scrollDepth <= -450 && scrollDirection === 'up' && activeIndex > 0) {
                activeIndex--;
                scrollDepth = 100;
            }
            
            section.style.transform = `translate(-50%, -50%) translateZ(${zOffset}px) scale(${scaleValue})`;
        } else {
            section.style.transform = 'translate(-50%, -50%) translateZ(-900px) scale(0.8)';
            section.style.opacity = 0;
        }
    });

    lastScrollDepth = scrollDepth;
}, { passive: false });

let touchStartY = 0;
window.addEventListener("touchstart", (event) => {
    touchStartY = event.touches[0].clientY;
}, { passive: true });

window.addEventListener("touchmove", (event) => {
    event.preventDefault();
    
    const touchEndY = event.touches[0].clientY;
    const deltaY = touchStartY - touchEndY;
    scrollDepth += deltaY * 2;
    scrollDepth = Math.min(Math.max(scrollDepth, -500), 100);

    let scrollDirection = scrollDepth > lastScrollDepth ? 'down' : 'up';

    sections.forEach((section, index) => {
        if (index === activeIndex) {
            let scaleValue = Math.min(2, 0.8 + (scrollDepth + 500) / 600 * 1.2);
            let zOffset = -900 + scrollDepth;
            
            section.style.opacity = 1;
            
            if (scrollDepth >= 0) {
                scaleValue = 2;
                section.style.opacity = 1 - scrollDepth / 100;
                if (scrollDepth >= 50 && scrollDirection === 'down') {
                    activeIndex = (activeIndex + 1) % sections.length;
                    scrollDepth = -500;
                }
            } else if (scrollDepth <= -450 && scrollDirection === 'up' && activeIndex > 0) {
                activeIndex--;
                scrollDepth = 100;
            }
            
            section.style.transform = `translate(-50%, -50%) translateZ(${zOffset}px) scale(${scaleValue})`;
        } else {
            section.style.transform = 'translate(-50%, -50%) translateZ(-900px) scale(0.8)';
            section.style.opacity = 0;
        }
    });

    lastScrollDepth = scrollDepth;
    touchStartY = touchEndY;
}, { passive: false });