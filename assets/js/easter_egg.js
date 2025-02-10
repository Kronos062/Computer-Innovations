document.addEventListener('DOMContentLoaded', () => {
    // Seleccionamos las montañas
    const mountains = document.querySelectorAll('.mountain');
    let clickSequence = [];
    const correctSequence = ['mountain4', 'mountain1', 'mountain3', 'mountain2'];

    // Función para manejar clics en las montañas
    function handleMountainClick(event) {
        const mountainId = event.currentTarget.id;

        // Agregamos a la secuencia actual
        clickSequence.push(mountainId);

        // Mantenemos solo los últimos 4 clics
        if (clickSequence.length > 4) {
            clickSequence.shift();
        }

        // Comprobamos si la secuencia es correcta
        if (arraysEqual(clickSequence, correctSequence)) {
            showFullscreenVideo(); // Cambiado a la nueva función de video
            clickSequence = []; // Reiniciamos la secuencia
        }
    }

    // Asignamos el evento a cada montaña
    mountains.forEach(mountain => {
        mountain.addEventListener('click', handleMountainClick); // Cambiado a "click" en lugar de "mousedown"
    });

    // Función para comparar dos arrays
    function arraysEqual(arr1, arr2) {
        return arr1.length === arr2.length && arr1.every((value, index) => value === arr2[index]);
    }

    // Función para mostrar el video en pantalla completa
    function showFullscreenVideo() {
        // Crear un contenedor para el video
        const videoContainer = document.createElement('div');
        videoContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: black;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        `;
    
        // Crear el elemento de video
        const video = document.createElement('video');
        video.src = 'assets/img/enemigo.mp4'; // Ruta del archivo .mp4
        video.style.cssText = `
            max-width: 100%;
            max-height: 100%;
        `;
        video.autoplay = true; // Reproducir automáticamente
        video.controls = false; // Ocultar controles
        video.loop = false; // No repetir el video
    
        // Añadir el video al contenedor
        videoContainer.appendChild(video);
        document.body.appendChild(videoContainer);
    
        // Eliminar el contenedor cuando el video termine
        video.addEventListener('ended', () => {
            document.body.removeChild(videoContainer);
        });
    }
});