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
