function ouvrirFenetre(url){
    var win = window.open(url, "popup", "toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, copyhistory=0, width=610px, height=842px,screenX=100,screenY=100");
    win.focus();
}

function ouvrirFenetreEcran(url){
    var win = window.open(url, "popup", "toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, copyhistory=0, width=800px, height=400px,screenX=100,screenY=100");
    win.focus();
}