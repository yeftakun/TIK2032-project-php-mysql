document.addEventListener('DOMContentLoaded', function() {
    var spanText = document.querySelector('.multiple-text');
    var textArray = ['Backend Enthusiast', 'Tech Enthusiasts'];
    var currentTextIndex = 0;

    function type() {
        var currentText = textArray[currentTextIndex];
        var currentIndex = 0;
        var typingInterval = setInterval(function() {
            if (currentIndex < currentText.length) {
                spanText.textContent += currentText.charAt(currentIndex);
                currentIndex++;
            } else {
                clearInterval(typingInterval);
                setTimeout(erase, 2000);
            }
        }, 100);
    }

    function erase() {
        var currentText = textArray[currentTextIndex];
        var currentIndex = currentText.length - 1;
        var erasingInterval = setInterval(function() {
            if (currentIndex >= 0) {
                spanText.textContent = currentText.substring(0, currentIndex);
                currentIndex--;
            } else {
                clearInterval(erasingInterval);
                currentTextIndex = (currentTextIndex + 1) % textArray.length;
                setTimeout(type, 1000);
            }
        }, 50);
    }

    type();
});
