let currentSlide = 0;

function showSlide(index) {
    const slides = document.querySelectorAll(".slide");
    
    // Ensure there are slides
    if (slides.length === 0) {
        console.error("No slides found!");
        return;
    }

    // Update currentSlide to wrap around if index is out of bounds
    if (index >= slides.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = index;
    }

    // Hide all slides and display the current slide
    slides.forEach((slide) => (slide.style.display = "none"));
    slides[currentSlide].style.display = "block";
}

function changeSlide(step) {
    showSlide(currentSlide + step);
}

// Initialize the first slide
showSlide(currentSlide);

// Read More Toggle
document.querySelectorAll(".read-more").forEach((button) => {
    button.addEventListener("click", function () {
        const fullContent = this.nextElementSibling;
        if (fullContent.style.display === "none" || !fullContent.style.display) {
            fullContent.style.display = "block";
            this.textContent = "Read Less";
        } else {
            fullContent.style.display = "none";
            this.textContent = "Read More";
        }
    });
});

// Dropdown Menu Toggle
function toggleMenu() {
    const dropdown = document.getElementById("menuDropdown");
    dropdown.classList.toggle("show");
}
