document.addEventListener('DOMContentLoaded', () => {
    const mountains = document.querySelectorAll('.mountain');
    let clickSequence = [];
    const correctSequence = ['mountain4', 'mountain1', 'mountain3', 'mountain2'];

    function handleMountainClick(event) {
        const mountainId = event.currentTarget.id;

        clickSequence.push(mountainId);

        if (clickSequence.length > 4) {
            clickSequence.shift();
        }

        if (arraysEqual(clickSequence, correctSequence)) {
            showFullscreenVideo();
            clickSequence = [];
        }
    }

    mountains.forEach(mountain => {
        mountain.addEventListener('click', handleMountainClick);
    });

    function arraysEqual(arr1, arr2) {
        return arr1.length === arr2.length && arr1.every((value, index) => value === arr2[index]);
    }

    function showFullscreenVideo() {
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
    
        const video = document.createElement('video');
        video.src = 'assets/img/enemigo.mp4';
        video.style.cssText = `
            max-width: 100%;
            max-height: 100%;
        `;
        video.autoplay = true;
        video.controls = false;
        video.loop = false;

        videoContainer.appendChild(video);
        document.body.appendChild(videoContainer);

        video.addEventListener('ended', () => {
            document.body.removeChild(videoContainer);
        });
    }
});