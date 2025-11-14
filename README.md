# BikeStore Management System 🚴

Un système complet de gestion pour magasins de vélos développé en PHP avec Symfony/Doctrine ORM, utilisant une approche code-first pour la base de données.

## 📋 À propos du projet

Ce projet a été réalisé dans le cadre de la SAE 401 en 2ème année de BUT MMI. Il s'agit d'une application web complète permettant la gestion d'une chaîne de magasins de vélos, avec différents niveaux d'accès selon les rôles des utilisateurs.

### Objectifs pédagogiques
- Initiation au framework Symfony et à Doctrine ORM
- Développement d'une base de données en approche code-first
- Création d'une API RESTful
- Gestion des rôles et permissions utilisateurs
- Intégration de services externes (géolocalisation, cartographie)

## ✨ Fonctionnalités principales

### Pour tous les utilisateurs
- 🗺️ Carte interactive des magasins (Leaflet)
- 🔍 Recherche et filtrage de produits
- 📍 Géolocalisation automatique de l'utilisateur

### Clients
- Consultation du catalogue produits
- Filtrage par marque, catégorie, année et prix
- Localisation des magasins les plus proches

### Employés
- Gestion des stocks de leur magasin
- Modification des informations produits
- Gestion des catégories et marques
- Mise à jour du profil personnel

### Chefs de magasin
- Toutes les fonctionnalités employés
- Ajout/suppression d'employés
- Gestion complète du personnel du magasin

### Administrateurs IT
- Accès complet à tous les magasins
- Gestion globale des employés
- Administration de toutes les données

## 🛠️ Technologies utilisées

### Backend
- **PHP 7.4+**
- **Symfony Components** (Cache, Console, etc.)
- **Doctrine ORM 2.11** - Mapping objet-relationnel
- **MySQL/MariaDB** - Base de données

### Frontend
- **HTML5/CSS3**
- **JavaScript (Vanilla & jQuery)**
- **Leaflet.js** - Cartographie interactive
- **AJAX** - Requêtes asynchrones

### API & Services
- **RESTful API** personnalisée
- **Nominatim** - Géocodage d'adresses
- **BigDataCloud & APIBundle** - Géolocalisation IP
- **Documentation Swagger/OpenAPI**

## 📁 Structure du projet

```
SAE_401/
├── Chief/              # Interface chef de magasin
├── Client/             # Interface client
├── Employee/           # Interface employé
├── IT/                 # Interface administrateur
├── src/
│   └── Entity/        # Entités Doctrine
│       ├── Brands.php
│       ├── Categories.php
│       ├── Employees.php
│       ├── Products.php
│       ├── Stocks.php
│       └── Stores.php
├── www/               # Headers et footers communs
├── api.php           # Point d'entrée API REST
├── bootstrap.php     # Configuration Doctrine
├── composer.json     # Dépendances PHP
└── Documentation/    # Documentation Swagger
```

## 🚀 Installation

### Prérequis
- PHP 7.4 ou supérieur
- MySQL 5.7+ ou MariaDB 10.3+
- Composer
- Serveur web (Apache/Nginx)

### Étapes d'installation

1. **Cloner le dépôt**
```bash
git clone https://github.com/votre-username/bikestore-management.git
cd bikestore-management
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**
Modifier le fichier `bootstrap.php` avec vos identifiants :
```php
$connection = DriverManager::getConnection([
    'dbname' => 'votre_base',
    'user' => 'votre_user',
    'password' => 'votre_password',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
], $config);
```

4. **Créer le schéma de base de données**
```bash
php bin/doctrine orm:schema-tool:create
```

5. **Configurer le serveur web**
Pointer le DocumentRoot vers le dossier du projet.

## 📚 API Documentation

L'API RESTful est documentée avec Swagger/OpenAPI. Accédez à la documentation interactive via :
```
Documentation/Swagger/index.html
```

### Endpoints principaux

#### Ressources
- `GET /api.php?action=stores` - Liste des magasins
- `GET /api.php?action=products` - Liste des produits
- `GET /api.php?action=employees` - Liste des employés
- `GET /api.php?action=brands` - Liste des marques
- `GET /api.php?action=categories` - Liste des catégories

#### Opérations CRUD
- `POST /api.php?action=add{Resource}` - Créer
- `GET /api.php?action={resource}&id={id}` - Lire
- `PUT /api.php?action=update{Resource}&id={id}` - Mettre à jour
- `DELETE /api.php?action=delete{Resource}&id={id}` - Supprimer

## 🎨 Interface utilisateur

### Connexion
L'interface de connexion (`index.html`) différencie automatiquement les utilisateurs selon leur rôle :
- **Employee** → Interface employé
- **Chief** → Interface chef de magasin
- **IT** → Interface administrateur

### Navigation
Chaque rôle dispose d'une navigation adaptée avec accès aux fonctionnalités correspondantes.

## 🔐 Sécurité

- Authentification par email/mot de passe
- Gestion des sessions PHP
- Validation des entrées utilisateur
- Séparation des privilèges par rôle
- Protection contre les injections SQL (via Doctrine)

## 🗃️ Modèle de données

### Entités principales

#### Stores
Représente les magasins physiques avec leur localisation complète.

#### Products
Catalogue de produits avec marque, catégorie, année et prix.

#### Employees
Utilisateurs du système avec rôles et affectation magasin.

#### Stocks
Gestion des quantités par produit et par magasin.

#### Brands & Categories
Classification des produits.

## 🌟 Fonctionnalités avancées

- **Géolocalisation** : Détection automatique de la position utilisateur
- **Cartographie** : Affichage interactif des magasins sur carte
- **Géocodage** : Conversion adresses → coordonnées GPS
- **Filtres dynamiques** : Recherche multi-critères en temps réel
- **API REST complète** : CRUD sur toutes les ressources

## 📱 Responsive Design

L'interface s'adapte aux différentes tailles d'écran pour une utilisation optimale sur desktop, tablette et mobile.

## 🤝 Contribution

Ce projet est un travail académique. Les contributions ne sont pas acceptées, mais n'hésitez pas à le forker pour vos propres expérimentations !

## 📝 Licence

Projet académique - Tous droits réservés

## 👥 Auteurs

- **Lélio Buton** - Développement complet
- **IUT de Caen - Département MMI** - Encadrement pédagogique

## 📧 Contact

Pour toute question : lelio.buton@etu.unicaen.fr

---

⭐ N'hésitez pas à mettre une étoile si ce projet vous a été utile !