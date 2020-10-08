function ChangeClassMenu() {
    var scrollY;
    if (document.all) {
        if (!document.documentElement.scrollTop)
            scrollY = document.body.scrollTop;
        else
            scrollY = document.documentElement.scrollTop;
    }
    else
        scrollY = window.pageYOffset;
    if (scrollY > 100)
        document.getElementById("menu_principal").className = "menu_principal2";
    else
        document.getElementById("menu_principal").className = "menu_principal1";
}
window.onscroll = ChangeClassMenu;