document.addEventListener("DOMContentLoaded", () => {
 
    // On récupère la balise qui servira à charger le CSS du mode clair.
    let baliseSombre = document.getElementById("style-sombre");
    if (!baliseSombre) {
        // Si la page n'a pas cette balise, on la crée automatiquement.
        baliseSombre = document.createElement("link");
        baliseSombre.rel = "stylesheet";
        baliseSombre.id = "style-sombre";
        document.head.appendChild(baliseSombre);
    }
 
    // On retrouve le chemin du dossier JS pour construire le chemin vers le CSS.
    const BASE = (() => {
        const scripts = document.querySelectorAll("script[src]");
        for (const s of scripts) {
            const m = s.src.match(/^(.*\/Folder[_ ]?JS\/)/i);
            if (m) return m[1];
        }
        return "../Folder JS/";
    })();
    const CSS_CLAIR = BASE.replace(/Folder[_ ]?JS\//i, "Folder CSS/") + "mode-sombre.css";
 
    // Lit le thème sauvegardé dans les cookies.
    const lireCookie = () => {
        const c = document.cookie.split("; ").find(r => r.startsWith("theme="));
        if (c) {
            return c.split("=")[1];
        } else {
            return "sombre";
        }
    };
 
    // Sauvegarde le thème choisi pendant 30 jours.
    const sauvegarder = (mode) => {
        document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}; SameSite=Lax`;
    };
 
    // Applique réellement le mode clair ou sombre à la page.
    const appliquerMode = (mode, anime = false) => {
        const html = document.documentElement;
 
        if (mode === "clair") {
            // En mode clair, on active le CSS supplémentaire.
            baliseSombre.setAttribute("href", CSS_CLAIR);
            html.setAttribute("data-theme", "sombre");
        } else {
            // En mode sombre, on retire le CSS clair.
            baliseSombre.setAttribute("href", "");
            html.removeAttribute("data-theme");
        }
 
        // Animation légère uniquement quand l'utilisateur clique sur le bouton.
        if (anime) {
            document.body.style.transition = "background-color 0.4s ease, color 0.3s ease";
            setTimeout(() => { document.body.style.transition = ""; }, 500);
        }
 
        // Met à jour le texte du bouton selon le mode actuel.
        document.querySelectorAll(".bouton-theme").forEach(btn => {
            if (mode === "clair") {
                btn.textContent = "🌙 Mode Sombre";
                btn.setAttribute("aria-pressed", "true");
            } else {
                btn.textContent = "☀️ Mode Clair";
                btn.setAttribute("aria-pressed", "false");
            }
        });
    };
 
    // Vérifie qu'un bouton de thème existe sur la page.
    const injecterBouton = () => {
        const ancien = document.getElementById("bouton-theme");
        if (ancien) {
            ancien.classList.add("bouton-theme");
            return;
        }
        const btn = document.createElement("button");
        btn.className = "bouton-theme bouton-theme-flottant";
        // Le bouton créé ici est caché, il sert surtout de secours si une page n'a pas de bouton.
        btn.style.display = "none";
        document.body.appendChild(btn);
    };
 
    // Au chargement de la page, on applique le thème sauvegardé.
    injecterBouton();
    const modeActuel = lireCookie();
    appliquerMode(modeActuel, false);
 
    // Quand l'utilisateur clique sur le bouton, on inverse le thème et on sauvegarde le choix.
    document.addEventListener("click", (e) => {
        if (!e.target.classList.contains("bouton-theme")) return;
        const actuel = lireCookie();
        let nouveau;
        if (actuel === "sombre") {
            nouveau = "clair";
        } else {
            nouveau = "sombre";
        }
        appliquerMode(nouveau, true);
        sauvegarder(nouveau);
    });
});
 
