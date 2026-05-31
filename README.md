# 🥖 Le 129 — Application Web de commande de sandwichs

Application web PHP de commande de sandwichs en ligne avec gestion des rôles, des commandes, des avis et d'un système d'avoir.

---

## 📋 Prérequis

- **PHP** 8.0 ou supérieur
- **Apache** ou **Nginx** avec mod_rewrite activé
- **XAMPP / WAMP / MAMP** (recommandé pour un environnement local)
- Aucune base de données requise — les données sont stockées en fichiers JSON

---

## 🚀 Installation & Lancement

1. Cloner ou déposer le projet dans le dossier racine de votre serveur local :
   - XAMPP : `C:/xampp/htdocs/le129/`
   - WAMP : `C:/wamp64/www/le129/`

2. Démarrer Apache depuis le panneau de contrôle XAMPP/WAMP.

3. Ouvrir un navigateur et accéder à :
   ```
   http://localhost/Folder_PHP/index.php
   ```

4. Vérifier que les fichiers JSON dans `Folder_Data/` sont accessibles en lecture/écriture par le serveur.

---

## 🗂️ Structure du projet

```
le129/
├── Folder_PHP/          # Pages PHP (index, admin, profil, commandes…)
├── Folder CSS/          # Feuilles de style
├── Folder_JS/           # Scripts JavaScript
├── Folder img/          # Images et assets
├── Folder_Data/         # Données JSON (utilisateurs, commandes, avis)
└── include/             # Fichiers inclus (Start.php, Permission.php…)
```

---

## 👥 Comptes disponibles

Tous les comptes ont le mot de passe : **`1`**

| Rôle | Email | Accès |
|------|-------|-------|
| **Admin** | `admin@gmail.com` | Panel d'administration complet |
| **Préparateur** | `Cuisinier@gmail.com` | Gestion des commandes à préparer |
| **Livreur** | `livreur@gmail.com` | Gestion des livraisons |
| **Client** | `client1@gmail.com` | Commande, avis, profil |
| **Client** | `client2@gmail.com` | Commande, avis, profil |
| **Client** | `antho@gmail.com` | Commande, avis, profil |

---

## ⚙️ Rôles & Permissions

| Rôle | Fonctionnalités |
|------|----------------|
| **Admin** | Accès à tout : gestion des utilisateurs, modification des rôles, blocage de comptes, suivi et mise à jour des statuts de commandes, statistiques globales (CA, commandes, utilisateurs en cours) |
| **Préparateur** | Visualisation et mise à jour des commandes avec le statut *À préparer* |
| **Livreur** | Visualisation et prise en charge des commandes *En cours de livraison* |
| **Client** | Navigation dans le catalogue, ajout au panier, passage de commande, dépôt d'avis sur les commandes livrées, gestion du profil et consultation de l'avoir |

---

## 🧭 Pages principales

| Page | Description |
|------|-------------|
| `index.php` | Page d'accueil — catalogue et navigation |
| `connexion.php` | Authentification |
| `Profil.php` | Espace personnel |
| `Produits.php` | Tous les produits |
| `Menus.php` | Menus disponibles |
| `Extras.php` | Suppléments |
| `Sandwich_Seul.php` | Sandwichs seuls |
| `Boissons.php` | Boissons |
| `panier.php` | Panier de commande |
| `Admin.php` | Panel administrateur *(admin uniquement)* |
| `commandes.php` | Suivi des commandes *(préparateur)* |
| `livreur.php` | Gestion des livraisons *(livreur)* |
| `Avis.php` | Formulaire d'avis *(commande livrée requise)* |

---

## 💾 Données JSON

Les données sont persistées dans `Folder_Data/` :

- **Utilisateurs** — profils, rôles, mots de passe hashés (bcrypt), avoir, statut bloqué
- **Commandes** — articles, client, adresse, statut, date
- **Avis** — note plat, note livraison, commentaire, commande associée

> ⚠️ Les noms des fichiers JSON sont volontairement obfusqués pour limiter l'accès direct.

---

## 🔒 Sécurité

- Mots de passe hashés avec **bcrypt** (`password_hash` / `password_verify`)
- Vérification des permissions à chaque page sensible via `Permission.php`
- Redirection automatique si rôle insuffisant
- Protection contre les comptes bloqués à la connexion

---

## 📝 Notes de développement

- Le mode sombre/clair est géré côté client via `affichage.js` et un cookie
- L'avoir client est incrémenté automatiquement selon les commandes
- Un avis ne peut être déposé qu'une seule fois par commande livrée