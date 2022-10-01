// Initialize Materialize Scripts
$(document).ready(function(){
  $('.sidenav').sidenav();
});

// Function called if AdBlock is not detected
function adBlockNotDetected() {
  var banners = document.getElementsByClassName("banner-funding");
  for (var i=0;i<banners.length;i+=1){
    banners[i].style.display = 'none';
  }
  var adContainers = document.getElementsByClassName("leaderad_container");
  for (var i=0;i<adContainers.length;i+=1){
    adContainers[i].style.display = 'block';
  }
}
// Function called if AdBlock is detected
function adBlockDetected() {
  var banners = document.getElementsByClassName("banner-funding");
  for (var i=0;i<banners.length;i+=1){
    banners[i].style.display = 'block';
  }
  var adContainers = document.getElementsByClassName("leaderad_container");
  for (var i=0;i<adContainers.length;i+=1){
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