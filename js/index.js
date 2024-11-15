const header = document.getElementById('header');

const images = ['img/image1.jpg', 'img/image2.jpg', 'img/image3.jpg', 'img/image4.jpg', 'img/image5.jpg']; // Add your image URLs here

const dotsContainer = document.getElementById('dots-container');

let currentImageIndex = 0;

let interval;



// Create image elements and dots

function createCarousel() {

    const carouselWrapper = document.createElement('div');

    carouselWrapper.classList.add('carousel-images');

    

    images.forEach((image, index) => {

        const imgDiv = document.createElement('div');

        imgDiv.classList.add('carousel-image');

        imgDiv.style.backgroundImage = `url(${image})`;

        carouselWrapper.appendChild(imgDiv);

        

        // Create a dot for each image

        const dot = document.createElement('span');

        dot.classList.add('carousel-dot');

        dot.addEventListener('click', () => {

            clearInterval(interval);  // Stop auto-slide

            showImage(index);

            resetAutoSlide();  // Restart auto-slide after a click

        });

        dotsContainer.appendChild(dot);

    });

    

    header.appendChild(carouselWrapper);

    updateActiveDot();

}



// Function to show the image based on index

function showImage(index) {

    currentImageIndex = index;

    const carouselWrapper = document.querySelector('.carousel-images');

    carouselWrapper.style.transform = `translateX(-${currentImageIndex * 100}%)`;

    updateActiveDot();

}



// Function to update the active dot

function updateActiveDot() {

    const dots = dotsContainer.children;

    for (let i = 0; i < dots.length; i++) {

        dots[i].classList.toggle('active', i === currentImageIndex);

    }

}



// Auto slide function

function autoSlide() {

    currentImageIndex = (currentImageIndex + 1) % images.length;

    showImage(currentImageIndex);

}



// Reset the interval when switching manually

function resetAutoSlide() {

    clearInterval(interval);

    interval = setInterval(autoSlide, 2500); // Slide every 5 seconds

}



// Initialize carousel

createCarousel();

interval = setInterval(autoSlide, 2500); // Start auto-slide

          const menuIcon = document.getElementById("menu-icon");

          const navMenu = document.getElementById("nav-menu");

        

          menuIcon.addEventListener("click", () => {

            menuIcon.classList.toggle("open");

            navMenu.classList.toggle("open");

          });

        

          // Bird movement based on scroll position

          window.addEventListener('scroll', function() {

            let scrollPosition = window.scrollY;

            let bird = document.getElementById('bird');

        

            let birdX = scrollPosition * 0.5; // Horizontal movement (right)

            let birdY = scrollPosition * -0.5; // Vertical movement (up)

        

            bird.style.transform = `translate(${birdX}px, ${birdY}px)`;

          });

        

          // Bird movement based on scroll position bird2

          window.addEventListener('scroll', function() {

            let scrollPosition = window.scrollY;

            let bird = document.getElementById('bird2');

        

            let birdX = scrollPosition * 0.5; // Horizontal movement (right)

            let birdY = scrollPosition * -0.5; // Vertical movement (up)

        

            bird.style.transform = `translate(${birdX}px, ${birdY}px)`;

          });

        

          // Select the toggle button

          const toggleButton = document.getElementById('toggle-button');

          const body = document.body;

        

          // Function to set a cookie

          function setCookie(name, value, days) {

            const date = new Date();

            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

            document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;

          }

        

          // Function to get a cookie by name

          function getCookie(name) {

            const cookies = document.cookie.split(';');

            for (let i = 0; i < cookies.length; i++) {

              const cookie = cookies[i].trim();

              if (cookie.startsWith(name + '=')) {

                return cookie.substring(name.length + 1);

              }

            }

            return null;

          }

        

          // Function to apply the correct theme based on the saved preference

          function applyTheme(theme) {

            const isDarkMode = theme === 'dark';

            body.classList.toggle('dark', isDarkMode); // Apply dark mode class if necessary

            toggleButton.classList.toggle('active', isDarkMode); // Set the toggle state accordingly

          }

        

          // Toggle between light and dark mode

          function toggleMode() {

            const isDarkMode = body.classList.toggle('dark'); // Toggle class

            const mode = isDarkMode ? 'dark' : 'light';

            setCookie('theme', mode, 7); // Save the chosen mode in the cookie

            toggleButton.classList.toggle('active', isDarkMode); // Reflect the switch state

          }

        

          // On page load, check the saved preference and apply it

          window.onload = function() {

            const savedMode = getCookie('theme') || 'light'; // Default to light mode if no cookie

            applyTheme(savedMode); // Apply the theme based on the saved cookie

          };

        

          // Add event listener to toggle the mode when the button is clicked

          toggleButton.addEventListener('click', toggleMode);

          

          navMenu.style.padding = '10px 20px'; // Apply padding directly
