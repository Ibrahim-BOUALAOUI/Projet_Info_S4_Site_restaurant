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
    const CSS_CLAIR = BASE.replace(/Folder[_ ]?JS\//i, "Folder CSS/") + "mode-sombre.css";
 
    /* ── Lecture du thème (Cookie) ── */
    const lireCookie = () => {
        const c = document.cookie.split("; ").find(r => r.startsWith("theme="));
        if (c) {
            return c.split("=")[1];
        } else {
            return "sombre";
        }
    };
 
    /* ── Sauvegarde ── */
    const sauvegarder = (mode) => {
        document.cookie = `theme=${mode}; path=/; max-age=${30 * 24 * 60 * 60}; SameSite=Lax`;
    };
 
    /* ── Application du thème ── */
    const appliquerMode = (mode, anime = false) => {
        const html = document.documentElement;
 
        if (mode === "clair") {
            baliseSombre.setAttribute("href", CSS_CLAIR);
            html.setAttribute("data-theme", "sombre");
        } else {
            baliseSombre.setAttribute("href", "");
            html.removeAttribute("data-theme");
        }
 
        /* Animation flash uniquement lors d'un clic volontaire */
        if (anime) {
            document.body.style.transition = "background-color 0.4s ease, color 0.3s ease";
            setTimeout(() => { document.body.style.transition = ""; }, 500);
        }
 
        /* Mise à jour du texte du bouton */
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
 
    /* ── Injection du bouton ── */
    const injecterBouton = () => {
        const ancien = document.getElementById("bouton-theme");
        if (ancien) {
            ancien.classList.add("bouton-theme");
            return;
        }
        const btn = document.createElement("button");
        btn.className = "bouton-theme bouton-theme-flottant";
        btn.style.display = "none";
        document.body.appendChild(btn);
    };
 
    /* ── Initialisation ── */
    injecterBouton();
    const modeActuel = lireCookie();
    appliquerMode(modeActuel, false);
 
    /* ── Écoute des clics ── */
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
 
