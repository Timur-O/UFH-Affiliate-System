/**
 * Declare "Missing Variables"
 *
 * @var $ JQuery
*/

// Initialize Materialize Scripts
$(document).ready(function(){
  $('.sidenav').sidenav();
});

// Function called if AdBlock is not detected
function adBlockNotDetected() {
  const banners = document.getElementsByClassName("banner-funding");
  for (let i = 0; i < banners.length; i += 1) {
    banners[i].style.display = 'none';
  }
  const adContainers = document.getElementsByClassName("leaderad_container");
  for (let i = 0; i < adContainers.length; i += 1) {
    adContainers[i].style.display = 'block';
  }
}
// Function called if AdBlock is detected
function adBlockDetected() {
  const banners = document.getElementsByClassName("banner-funding");
  for (let i = 0; i < banners.length; i += 1) {
    banners[i].style.display = 'block';
  }
  const adContainers = document.getElementsByClassName("leaderad_container");
  for (let i = 0; i < adContainers.length; i += 1) {
    adContainers[i].style.display = 'none';
  }
}
// Recommended audit because AdBlock lock the file 'blockadblock.js' 
// If the file is not called, the variable does not exist 'blockAdBlock'
// This means that AdBlock is present
if(typeof blockAdBlock === 'undefined') {
  adBlockDetected();
} else {
  blockAdBlock.onDetected(adBlockDetected);
  blockAdBlock.onNotDetected(adBlockNotDetected);
  // and|or
  blockAdBlock.on(true, adBlockDetected);
  blockAdBlock.on(false, adBlockNotDetected);
  // and|or
  blockAdBlock.on(true, adBlockDetected).onNotDetected(adBlockNotDetected);
}