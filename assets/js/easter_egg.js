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
            openWebsite();
            clickSequence = [];
        }
    }

    mountains.forEach(mountain => {
        mountain.addEventListener('click', handleMountainClick);
    });

    function arraysEqual(arr1, arr2) {
        return arr1.length === arr2.length && arr1.every((value, index) => value === arr2[index]);
    }

    function openWebsite() {
        const websiteUrl = 'https://diekmann.github.io/wasm-fizzbuzz/doom/';
        
        window.open(websiteUrl, '_blank');
    }
});
