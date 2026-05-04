document.addEventListener("DOMContentLoaded", () => {
    const boutonTheme = document.getElementById("bouton-theme");
    const baliseSombre = document.getElementById("style-sombre");

    const appliquerMode = (mode) => {
        if (mode === "sombre") {
            baliseSombre.setAttribute("href", "../Folder CSS/mode-sombre.css");
        } else {
            baliseSombre.setAttribute("href", "");
        }
    };

    // Vérifier le cookie au chargement
    const modeEnregistre = document.cookie.split('; ').find(row => row.startsWith('theme='));
    if (modeEnregistre && modeEnregistre.split('=')[1] === 'sombre') {
        appliquerMode("sombre");
    }

    if (boutonTheme) {
        boutonTheme.addEventListener("click", () => {
            let mode = baliseSombre.getAttribute("href") === "" ? "sombre" : "clair";
            appliquerMode(mode);
            document.cookie = `theme=${mode}; path=/; max-age=${30*24*60*60}`;
        });
    }
});