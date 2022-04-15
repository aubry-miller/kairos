function fullscreen() {
    var x = document.getElementById("plan");
    if (x.style.position = "absolute") {
      x.style.position = "fixed";
      x.style.width = "100%";
      x.style.height = "100%";
      x.style.top = "0";
      x.style.left = "0";
      x.style.backgroundColor = "white";
      x.style.marginTop = "0";
      x.style.zIndex = "9999";
    } else {
        x.style.position = "absolute";
        x.style.width = "auto";
    }

}
