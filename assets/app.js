import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import "bootstrap/dist/css/bootstrap.min.css";
import 'bootstrap';
import './js/nouislider.min.js';
import './styles/nouislider.min.css';
import './js/filter.js';
import { Carousel } from "bootstrap";


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// Range Slider
function initSlider(sliderElement, minInput, maxInput, minValue, maxValue, step) {
  if (!sliderElement) return;

  const range = noUiSlider.create(sliderElement, {
      start: [minInput.value || minValue, maxInput.value || maxValue],
      connect: true,
      step: step,
      range: {
          'min': minValue,
          'max': maxValue
      }
  });

  range.on('slide', function (values, handle) {
      if (handle === 0) {
          minInput.value = Math.round(values[0]);
      } else if (handle === 1) {
          maxInput.value = Math.round(values[1]);
      }
  });

  range.on('end', function (values, handle) {
      if (handle === 0) {
          minInput.dispatchEvent(new Event('change'));
      } else {
          maxInput.dispatchEvent(new Event('change'));
      }
  });
}

const priceSlider = document.getElementById('price-slider');
const kmsSlider = document.getElementById('kms-slider');
const dateSlider = document.getElementById('date-slider');

initSlider(priceSlider, document.getElementById('minPrice'), document.getElementById('maxPrice'), 0, 100, 10);
initSlider(kmsSlider, document.getElementById('minKms'), document.getElementById('maxKms'), 0, 100000, 1000);
initSlider(dateSlider, document.getElementById('minCirculationAt'), document.getElementById('maxCirculationAt'), 2013, 2023, 1);


// Close alert message after 5 secondes
const alert = document.querySelector('.alert')
if (alert) {
    setTimeout(function () {
        alert.style.transition = "opacity 1s ease";
        alert.style.opacity = '0';

        setTimeout(function () {
            alert.style.display = 'none';
        }, 500); // After the fade-out animation (0.5 second)
    }, 5000); // After 5 seconds
}

//Homepage marques
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('brandSelect').addEventListener('change', function() {
        const selectedValue = this.value;
        if (selectedValue) {  // Vérifie si une marque a été choisie
            window.location.href = selectedValue;  // Redirection vers le lien
        }
    });
});

// Star rating
const ratingInput = document.querySelector('#comment_form_rating');
if (ratingInput) {
    const starRating = document.querySelector('.star-rating');
    starRating.addEventListener('click', function (event) {
        if (event.target.matches('i')) {
            const ratingValue = event.target.getAttribute('data-rating');
            ratingInput.value = ratingValue;
            // Remove 'far' class and add 'fas' class for selected stars
            starRating.querySelectorAll('i').forEach(function (star) {
                const starRatingValue = star.getAttribute('data-rating')
                if (starRatingValue <= ratingValue) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            })
        }
    })
}