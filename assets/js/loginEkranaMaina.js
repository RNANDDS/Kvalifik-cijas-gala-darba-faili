form = 0

function paslegtFormu() {
    if (form == 0) {
        form = 1
        document.getElementById("login").style.display = "none"
        document.getElementById("register").style.display = "block"
        document.getElementById("togglePoga").innerHTML = "Vēlies ielogoties?"
    } else {
        form = 0
        document.getElementById("login").style.display = "block"
        document.getElementById("register").style.display = "none"
        document.getElementById("togglePoga").innerHTML = "Vēlies reģistrēties?"
    }
}
