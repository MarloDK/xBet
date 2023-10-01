/* Profile picture script */
var loadFile = function(event) {
    // IMPORT IMAGE
    filePath = URL.createObjectURL(event.target.files[0]);
	document.getElementById('output').src = filePath;
}
function checkSize(img) {
    if (img.width >= 256 && img.height >= 256) {
        img.style.display = "block";
    } else {
        img.style.display = "none";

    }
}
function uploadFile() {
    const canvas = document.getElementById("canvas");
    const img = document.getElementById("output");
    const ctx = canvas.getContext("2d");
    ctx.drawImage(img, x, y, 256, 256, 0, 0, 128, 128);
    const dataURL = document.getElementById("canvas").toDataURL();
    /* console.log(dataURL); */
    hiddenField = document.getElementById("upload-value");
    hiddenField.value = dataURL;
    document.getElementById("pfp-form").submit();
}
//Find coordinates of scroll
function scrollCoords() {
    const element = document.getElementById("img-container");
    window.x = element.scrollLeft;
    window.y = element.scrollTop;
  }