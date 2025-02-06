const sun = document.querySelector('.sun');
const mountains = document.querySelectorAll('.mountain');
const ground = document.querySelector('.ground');
const sections = document.querySelectorAll(".section");

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
let scrollDepth = -7600;
let activeIndex = 0;

function updatePositions(x, y) {
    sun.style.transform = `translate3d(${x * 30}px, ${y * 30}px, 0)`;

    mountains.forEach((mountain, index) => {
        let intensity;
        switch(index) {
            case 0:
                intensity = 2;
                break;
            case 1:
                intensity = 3;
                break;
            case 2:
                intensity = 4;
                break;
            case 3:
                intensity = 4;
                break;
            default:
                intensity = 3;
        }
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
    scrollDepth += event.deltaY * 3;
    scrollDepth = Math.min(Math.max(scrollDepth, -7600), 0); // Ajustado el límite

    sections.forEach((section, index) => {
        if (index === activeIndex) {
            let scaleValue = Math.min(1, 0.2 + (scrollDepth + 7600) / 7600);
            let zOffset = -7600 + scrollDepth;
            
            if (scaleValue >= 1) {
                section.style.opacity = 1 - (scrollDepth + 7600) / 200;
                if (scrollDepth > -7600) {
                    activeIndex = (activeIndex + 1) % sections.length;
                    scrollDepth = -7600;
                }
            } else {
                section.style.opacity = 1;
            }
            
            section.style.transform = `translate(-50%, -50%) translateZ(${zOffset}px) scale(${scaleValue})`;
        } else {
            section.style.transform = 'translate(-50%, -50%) translateZ(-7600px) scale(0.2)';
            section.style.opacity = 0;
        }
    });
}, { passive: false });