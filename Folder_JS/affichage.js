document.addEventListener("DOMContentLoaded", () => {

    /* ── Injection de la balise <link> si absente ── */
    let baliseSombre = document.getElementById("style-sombre");
    if (!baliseSombre) {
        baliseSombre = document.createElement("link");
        baliseSombre.rel = "stylesheet";
        baliseSombre.id = "style-sombre";
        document.head.appendChild(baliseSombre);
    }

    /* ── Chemin vers le CSS (Mode Clair) ── */
    const BASE = (() => {
        const scripts = document.querySelectorAll("script[src]");
        for (const s of scripts) {
            const m = s.src.match(/^(.*\/Folder[_ ]?JS\/)/i);
            if (m) return m[1];
        }
        return "../Folder JS/";
    })();
    // Le fichier s'appelle toujours mode-sombre.css dans ton dossier, 
    // mais il contient maintenant tes styles "Clairs" (blancs).
    const CSS_CLAIR = BASE.replace(/Folder[_ ]?JS\//i, "Folder CSS/") + "mode-sombre.css";

    /* ── Application du thème ── */
    const appliquerMode = (mode, anime = false) => {
        const html = document.documentElement;

        if (mode === "clair") {
            // On applique le fichier CSS blanc
            baliseSombre.setAttribute("href", CSS_CLAIR);
            html.setAttribute("data-theme", "sombre"); // On garde l'attribut pour le CSS
        } else {
            // On retire le CSS pour revenir au violet par défaut
            baliseSombre.setAttribute("href", "");
            html.removeAttribute("data-theme");
        }

        /* Animation flash */
        if (anime) {
            document.body.style.transition = "background-color 0.4s ease, color 0.3s ease";
            setTimeout(() => { document.body.style.transition = ""; }, 500);
        }

        /* Mise à jour du texte du bouton (Logique inversée) */
        document.querySelectorAll(".bouton-theme").forEach(btn => {
            // Si on est en CLAIR (blanc) -> on propose de revenir au SOMBRE (violet)
            // Si on est en SOMBRE (violet) -> on propose de passer au CLAIR (blanc)
            btn.textContent = mode === "clair" ? "🌙 Mode Sombre" : "☀️ Mode Clair";
            btn.setAttribute("aria-pressed", mode === "clair" ? "true" : "false");
        });
    };

    /* ── Lecture du thème (Cookie) ── */
    const lireCookie = () => {
        const c = document.cookie.split("; ").find(r => r.startsWith("theme="));
        // PAR DÉFAUT : On est en "sombre" (ton design violet)
        return c ? c.split("=")[1] : "sombre";
    };

    /* ── Sauvegarde ── */
    const sauvegarder = (mode) => {
        document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}; SameSite=Lax`;
    };

    /* ── Injection du bouton ── */
    const injecterBouton = () => {
        const ancien = document.getElementById("bouton-theme");
        if (ancien) {
            ancien.classList.add("bouton-theme");
            return;
        }
        // Bouton flottant (caché ou affiché selon tes réglages précédents)
        const btn = document.createElement("button");
        btn.className = "bouton-theme bouton-theme-flottant";
        btn.style.display = "none"; // On le cache comme demandé
        document.body.appendChild(btn);
    };

    /* ── Initialisation ── */
    injecterBouton();
    const modeActuel = lireCookie();
    appliquerMode(modeActuel, false);

    /* ── Écoute des clics ── */
    document.addEventListener("click", (e) => {
        if (!e.target.classList.contains("bouton-theme")) return;
        
        // Bascule : si on est en sombre, on passe en clair, et inversement
        const actuel = lireCookie();
        const nouveau = actuel === "sombre" ? "clair" : "sombre";
        
        appliquerMode(nouveau, true);
        sauvegarder(nouveau);
    });
});