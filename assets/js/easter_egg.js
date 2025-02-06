document.addEventListener('DOMContentLoaded', () => {
    const mountain1 = document.getElementById('mountain1');
    const mountain2 = document.getElementById('mountain2');
    const mountain3 = document.getElementById('mountain3');
    const mountain4 = document.getElementById('mountain4');
    let clickSequence = [];
    const correctSequence = ['mountain4', 'mountain1', 'mountain3', 'mountain2'];

    function handleMountainClick(mountainId) {
        clickSequence.push(mountainId);
        if (clickSequence.length > 4) {
            clickSequence.shift(); // Elimina el primer elemento si hay más de 4
        }
        console.log('Secuencia actual:', clickSequence); // Para depuración

        if (arraysEqual(clickSequence, correctSequence)) {
            showFullscreenGif();
            clickSequence = []; // Reinicia la secuencia
        }
    }

    mountain1.addEventListener('click', () => handleMountainClick('mountain1'));
    mountain3.addEventListener('click', () => handleMountainClick('mountain3'));
    mountain2.addEventListener('click', () => handleMountainClick('mountain2'));
    mountain4.addEventListener('click', () => handleMountainClick('mountain4'));

    function arraysEqual(arr1, arr2) {
        return arr1.length === arr2.length && arr1.every((value, index) => value === arr2[index]);
    }

    function showFullscreenGif() {
        console.log('Mostrando GIF');
        const gifContainer = document.createElement('div');
        gifContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('assets/img/gato.gif') no-repeat center center;
            background-size: cover;
            z-index: 9999;
        `;
        document.body.appendChild(gifContainer);
    
        setTimeout(() => {
            document.body.removeChild(gifContainer);
        }, 5000);
    }    
});
