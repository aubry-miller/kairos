function hidde() {
    var x = document.getElementById("masque");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }

    var y = document.getElementById("masque_fleche_bas");
    if (y.style.display === "none") {
      y.style.display = "inline-block";
    } else {
      y.style.display = "none";
    }

    var z = document.getElementById("masque_fleche_droite");
    if (z.style.display === "inline-block") {
      z.style.display = "none";
    } else {
      z.style.display = "inline-block";
    }

}
