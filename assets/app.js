
import './bootstrap.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', function () {
  // Language Toggle
  const langToggle = document.getElementById('language-toggle');
  const themeToggle = document.getElementById('theme-toggle');

  if (langToggle) {
      const currentLang = document.cookie.match(/locale=([^;]+)/)?.[1] || 'en';
      const circle = langToggle.querySelector('.bg-white');

      if (currentLang === 'fr') {
          circle.style.transform = 'translateX(20px)';
      }
      langToggle.setAttribute('data-lang', currentLang);

      langToggle.addEventListener('click', function () {
          const newLang = this.getAttribute('data-lang') === 'en' ? 'fr' : 'en';

          circle.style.transform = newLang === 'fr' ? 'translateX(20px)' : 'translateX(0)';
          this.setAttribute('data-lang', newLang);
          document.cookie = `locale=${newLang};path=/;max-age=${30 * 24 * 60 * 60}`;
          location.reload(); // Refresh the page to apply the new language
      });
  }

  // Theme Toggle
  if (themeToggle) {
      const currentTheme = localStorage.getItem('theme') || 'light';
      const circle = themeToggle.querySelector('.bg-white');

      if (currentTheme === 'dark') {
          circle.style.transform = 'translateX(20px)';
          document.documentElement.classList.add('dark');
      }
      themeToggle.setAttribute('data-theme', currentTheme);

      themeToggle.addEventListener('click', function () {
          const newTheme = this.getAttribute('data-theme') === 'light' ? 'dark' : 'light';

          circle.style.transform = newTheme === 'dark' ? 'translateX(20px)' : 'translateX(0)';
          this.setAttribute('data-theme', newTheme);
          localStorage.setItem('theme', newTheme);
          document.documentElement.classList.toggle('dark');
      });
  }
});


// document.addEventListener('DOMContentLoaded', () => {
//     const themeToggle = document.querySelector('.theme-toggle');
//     const htmlElement = document.documentElement;
    
//     if (themeToggle) {
//         // Check for saved theme preference
//         const savedTheme = localStorage.getItem('theme');
//         if (savedTheme) {
//             htmlElement.classList.add(savedTheme);
//         }
        
//         themeToggle.addEventListener('click', () => {
//             htmlElement.classList.toggle('dark');
//             const isDark = htmlElement.classList.contains('dark');
//             localStorage.setItem('theme', isDark ? 'dark' : 'light');
//         });
//     }
// });


// document.addEventListener('DOMContentLoaded', function() {
//     const menuButton = document.getElementById('menuButton');
//     const closeMenu = document.getElementById('closeMenu');
//     const mobileMenu = document.querySelector('.mobile-menu');
//     const langToggle = document.getElementById('langToggle');
//     const sliderButton = document.querySelector('.slider-button');
//     let currentLang = 'en';

//     menuButton.addEventListener('click', () => {
//         mobileMenu.classList.add('active');
//     });

//     closeMenu.addEventListener('click', () => {
//         mobileMenu.classList.remove('active');
//     });

//     langToggle.addEventListener('click', () => {
//         currentLang = currentLang === 'en' ? 'fr' : 'en';
//         sliderButton.classList.toggle('fr');
        
//         // Assuming you're using Symfony's translation
//         // fetch(`/change-locale/${currentLang}`, {
//         //     method: 'POST',
//         //     headers: {
//         //         'X-Requested-With': 'XMLHttpRequest'
//         //     }
//         // }).then(() => {
//         //     window.location.reload();
//         // });
//     });
// });

// document.addEventListener('DOMContentLoaded', () => {
//     const languageToggle = document.getElementById('languageToggle');
//     const languageSlider = document.getElementById('languageSlider');
//     const themeToggle = document.getElementById('themeToggle');
//     const themeSlider = document.getElementById('themeSlider');

//     // Language toggle
//     languageToggle.addEventListener('click', () => {
//         const currentLang = languageSlider.dataset.currentLang;
//         const newLang = currentLang === 'en' ? 'fr' : 'en';
        
//         languageSlider.style.transform = newLang === 'fr' ? 'translateX(48px)' : 'translateX(0)';
        
//         fetch(`/switch-language/${newLang}`, {
//             method: 'GET',
//             headers: {'X-Requested-With': 'XMLHttpRequest'}
//         }).then(() => window.location.reload());
//     });

//     // Theme toggle
//     themeToggle.addEventListener('click', () => {
//         const currentTheme = themeSlider.dataset.currentTheme;
//         const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
//         themeSlider.style.transform = newTheme === 'dark' ? 'translateX(48px)' : 'translateX(0)';
//         document.documentElement.classList.toggle('dark');
        
//         fetch(`/switch-theme/${newTheme}`, {
//             method: 'GET',
//             headers: {'X-Requested-With': 'XMLHttpRequest'}
//         });
//     });

//     // Initialize positions
//     if (languageSlider.dataset.currentLang === 'fr') {
//         languageSlider.style.transform = 'translateX(48px)';
//     }
//     if (themeSlider.dataset.currentTheme === 'dark') {
//         themeSlider.style.transform = 'translateX(48px)';
//     }
// });


// Seperated unto 


// document.getElementById("language-toggle").addEventListener("click", () => {
//     // Toggle between 'en' and 'fr'
//     let currentLang = document.getElementById("language-toggle").getAttribute("data-lang");
//     let newLang = currentLang === "en" ? "fr" : "en";
//     document.getElementById("language-toggle").setAttribute("data-lang", newLang);
    
//     // Set the locale cookie for Symfony (expires in 30 days)
//     document.cookie = `locale=${newLang};path=/;max-age=${30*24*60*60}`;
    
//     // Reload the page so Symfony loads the new locale
//     location.reload();
//   });
  

  // document.addEventListener("DOMContentLoaded", () => {
  //   const themeToggle = document.getElementById("theme-toggle");
  //   const themeSlider = document.getElementById("theme-slider");
  //   const sunIcon = document.getElementById("sun-icon");
  //   const moonIcon = document.getElementById("moon-icon");
  
  //   // Optionally, read the saved theme from localStorage
  //   const savedTheme = localStorage.getItem("theme");
  //   if (savedTheme === "dark") {
  //     document.documentElement.classList.add("dark");
  //     themeSlider.classList.add("translate-x-3.5");
  //     sunIcon.parentElement.classList.add("opacity-0");
  //     moonIcon.parentElement.classList.remove("opacity-0");
  //   }
  
  //   themeToggle.addEventListener("click", () => {
  //     // Toggle dark mode class on the root element
  //     document.documentElement.classList.toggle("dark");
  //     let isDark = document.documentElement.classList.contains("dark");
  
  //     // Animate the slider (assumes translate-x-0 for light and translate-x-3.5 for dark)
  //     if (isDark) {
  //       themeSlider.classList.remove("translate-x-0");
  //       themeSlider.classList.add("translate-x-3.5");
  //       // Toggle icon visibility
  //       sunIcon.parentElement.classList.add("opacity-0");
  //       moonIcon.parentElement.classList.remove("opacity-0");
  //       localStorage.setItem("theme", "dark");
  //     } else {
  //       themeSlider.classList.remove("translate-x-3.5");
  //       themeSlider.classList.add("translate-x-0");
  //       // Toggle icon visibility
  //       sunIcon.parentElement.classList.remove("opacity-0");
  //       moonIcon.parentElement.classList.add("opacity-0");
  //       localStorage.setItem("theme", "light");
  //     }
  //   });
  // });
  

  // Slider JS
//   document.addEventListener("DOMContentLoaded", () => {
//     const toggleSwitch = document.querySelector("[role='switch']");
//     const toggleCircle = toggleSwitch.querySelector("span[aria-hidden='true']");
    
//     toggleSwitch.addEventListener("click", () => {
//         const isChecked = toggleSwitch.getAttribute("aria-checked") === "true";
        
//         // Toggle aria-checked
//         toggleSwitch.setAttribute("aria-checked", isChecked ? "false" : "true");

//         // Toggle Tailwind classes
//         toggleCircle.classList.toggle("translate-x-0", isChecked);
//         toggleCircle.classList.toggle("translate-x-3.5", !isChecked);

//         // Optional: Do something when toggled
//         console.log(`Switch is now: ${isChecked ? "Off" : "On"}`);
//     });
// });
