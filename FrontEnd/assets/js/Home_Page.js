let slideIndex = 0;
let slideTimer;

function showSlides(n) {
  const slides = document.getElementsByClassName("mySlides");
  const dots = document.getElementsByClassName("dot");

  // If called with a number (manual control), update index
  if (typeof n === "number") {
    slideIndex = n;
  }

  if (slideIndex >= slides.length) slideIndex = 0;
  if (slideIndex < 0) slideIndex = slides.length - 1;

  // Hide all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  // Remove active class from dots
  for (let i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // Show current slide
  slides[slideIndex].style.display = "block";
  if (dots[slideIndex]) {
    dots[slideIndex].className += " active";
  }

  // Autoplay: advance slide and set timer
  clearTimeout(slideTimer);
  slideTimer = setTimeout(() => {
    slideIndex++;
    showSlides();
  }, 2500);
}

// Manual controls
function plusSlides(n) {
  showSlides(slideIndex + n);
}

function currentSlide(n) {
  showSlides(n - 1);
}

// Start autoplay on page load
window.addEventListener("load", () => {
  showSlides(); // start autoplay
});