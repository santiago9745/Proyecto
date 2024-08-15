const images = document.querySelectorAll('.image-slider img');
        let currentIndex = 0;

        function showImage(index) {
        images.forEach(image => {
            image.style.opacity = 0;
        });
        images[index].style.opacity = 1;
        }

        function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
        }

        // Mostrar la primera imagen al cargar la página
        showImage(currentIndex);

        // Cambiar de imagen cada 3 segundos
        setInterval(nextImage, 4500);