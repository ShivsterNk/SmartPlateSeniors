const leftButton = document.querySelector(".leftButton"); 
const rightButton = document.querySelector(".rightButton"); 
const slider = document.querySelector(".slider"); 

let position = 0; 
mealPhotos.src = Image[index]; 

leftButton.addEventListener("click", moveLeft); 
rightButton.addEventListener("click", moveRight);  

function moveRight() {
    position += 900; 
    slider.style.transform = "translateX(-" + position + "px)"; 
}

function moveLeft() {
    position -= 900;
    slider.style.transform = "translateX(-" + position + "px)";
}