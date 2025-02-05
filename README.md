# Mercato - Système de Gestion des Ventes

Mercato est une application web de gestion des ventes développée avec Laravel et TailwindCSS. Elle permet de gérer facilement les ventes, les produits et de suivre les statistiques commerciales en temps réel.

## Fonctionnalités

- **Tableau de bord** : Visualisation des statistiques de ventes (journalières et mensuelles)
- **Gestion des ventes** : 
  - Création rapide de nouvelles ventes
  - Calcul automatique des totaux et de la TVA
  - Gestion du stock en temps réel
- **Gestion des produits** :
  - Suivi des stocks
  - Prix et informations détaillées
- **Paiements** :
  - Support de multiples méthodes de paiement
  - Calcul automatique de la monnaie à rendre
- **Rapports** :
  - Statistiques journalières et mensuelles
  - Suivi du chiffre d'affaires

## Prérequis

- PHP >= 8.1
- Composer
- Node.js et NPM
- MySQL ou PostgreSQL
- Git

## Installation

1. Cloner le projet
```bash
git clone https://github.com/votre-username/mercato.git
cd mercato
```

2. Installer les dépendances PHP
```bash
composer install
```

3. Installer les dépendances JavaScript
```bash
npm install
```

4. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

5. Configurer la base de données dans le fichier `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mercato
DB_USERNAME=votre_username
DB_PASSWORD=votre_password
```

6. Migrer la base de données
```bash
php artisan migrate --seed
```

## Lancement

1. Démarrer le serveur Laravel
```bash
php artisan serve
```

2. Compiler les assets (dans un autre terminal)
```bash
npm run dev
```

L'application sera accessible à l'adresse : http://localhost:8000

## Structure du Projet

- `app/` - Code source PHP
  - `Http/Controllers/` - Contrôleurs de l'application
  - `Models/` - Modèles Eloquent
- `resources/`
  - `views/` - Templates Blade
  - `js/` - Code JavaScript
  - `css/` - Styles CSS/Tailwind
- `database/`
  - `migrations/` - Migrations de base de données
  - `seeders/` - Données de test

## Développement

Pour contribuer au projet :

1. Créer une branche pour votre fonctionnalité
```bash
git checkout -b feature/ma-fonctionnalite
```

2. Commiter vos changements
```bash
git commit -m "Description de vos changements"
```

3. Pousser sur le dépôt
```bash
git push origin feature/ma-fonctionnalite
```

## License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
