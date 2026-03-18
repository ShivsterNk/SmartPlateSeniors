<<<<<<< HEAD
const leftButton = document.querySelector(".leftButton");
const rightButton = document.querySelector(".rightButton");
const slider = document.querySelector("#slider");


leftButton.addEventListener("click", () => {
    slider.scrollLeft -= 300;
});


rightButton.addEventListener("click", () => {
    slider.scrollLeft += 300;
});


=======
const leftButton = document.querySelector(".leftButton"); 
const rightButton = document.querySelector(".rightButton"); 
const slider = document.querySelector("#slider"); 

rightButton.addEventListener("click", () => {
    slider.scrollLeft += 300; 
});

leftButton.addEventListener("click", () => {
    slider.scrollLeft -= 300; 
});
>>>>>>> 4d0e039 (Final)
