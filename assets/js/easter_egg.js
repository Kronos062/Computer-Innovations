document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM fully loaded and parsed');

    const mountains = document.querySelectorAll('.mountain');
    let clickSequence = [];
    const correctSequence = ['mountain4', 'mountain1', 'mountain3', 'mountain2'];

    function handleMountainClick(event) {
        event.preventDefault();
        event.stopPropagation();
        const mountainId = event.currentTarget.id;
        console.log('Clicked mountain:', mountainId);
        clickSequence.push(mountainId);
        if (clickSequence.length > 4) {
            clickSequence.shift();
        }
        console.log('Current sequence:', clickSequence);

        if (arraysEqual(clickSequence, correctSequence)) {
            showFullscreenGif();
            clickSequence = [];
        }
    }

    mountains.forEach(mountain => {
        console.log('Adding listener to:', mountain.id);
        mountain.addEventListener('mousedown', handleMountainClick, true);
    });

    function arraysEqual(arr1, arr2) {
        return arr1.length === arr2.length && arr1.every((value, index) => value === arr2[index]);
    }

    function showFullscreenGif() {
        console.log('Showing GIF');
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
