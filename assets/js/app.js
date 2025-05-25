try {
    var dropdownMenus = document.querySelectorAll(".dropdown-menu.stop");
    dropdownMenus.forEach(function (e) {
        e.addEventListener("click", function (e) {
            e.stopPropagation()
        })
    })
} catch (e) {
}
try {
    lucide.createIcons()
} catch (e) {
}
try {
    var themeColorToggle = document.getElementById("light-dark-mode");
    themeColorToggle && themeColorToggle.addEventListener("click", function (e) {
        "light" === document.documentElement.getAttribute("data-bs-theme") ? document.documentElement.setAttribute("data-bs-theme", "dark") : document.documentElement.setAttribute("data-bs-theme", "light")
    })
} catch (e) {
}
try {
    const k = document.querySelectorAll('[data-bs-toggle="tooltip"]'), l = [...k].map(e => new bootstrap.Tooltip(e));
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')),
        popoverList = popoverTriggerList.map(function (e) {
            return new bootstrap.Popover(e)
        })
} catch (e) {
}
