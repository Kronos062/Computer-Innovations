// Selecciona los elementos
const sun = document.querySelector('.sun');
const mountains = document.querySelectorAll('.mountain');
const ground = document.querySelector('.ground');

// Guarda las posiciones iniciales de las montañas
const initialPositions = Array.from(mountains).map((mountain) => {
    const transform = getComputedStyle(mountain).transform;

    // Extrae la posición inicial de "translateX" y "translateY" desde la matriz de transformación
    if (transform !== 'none') {
        const matrix = transform.match(/matrix.*\((.+)\)/)[1].split(', ');
        return {
            x: parseFloat(matrix[4]) || 0,
            y: parseFloat(matrix[5]) || 0,
        };
    }

    return { x: 0, y: 0 }; // Si no hay transformación previa
});

// Evento para capturar el movimiento del mouse
window.addEventListener('mousemove', (event) => {
    // Calcula el porcentaje de la posición del mouse en la ventana
    const x = (event.clientX / window.innerWidth) - 0.5; // De -0.5 a 0.5
    const y = (event.clientY / window.innerHeight) - 0.5; // De -0.5 a 0.5

    // Mueve el sol con base en la posición del cursor
    sun.style.transform = `translate(${x * 30}px, ${y * 30}px)`; // Movimiento más sutil

    // Mueve cada montaña con diferentes intensidades para crear profundidad
    mountains.forEach((mountain, index) => {
        const intensity = (index + 1) * 5; // Montañas más lejanas se mueven menos

        // Calcula la nueva posición sumando la inicial al desplazamiento dinámico
        const initialX = initialPositions[index].x;
        const initialY = initialPositions[index].y;

        //mountain.style.transform = `translate(${initialX + x * intensity}px, ${initialY + y * intensity}px)`;
    });

    // Mueve el suelo ligeramente con el cursor para mantener consistencia
    ground.style.transform = `translate(${x * 10}px, ${y * 5}px)`; // Movimiento muy sutil para el suelo
});
