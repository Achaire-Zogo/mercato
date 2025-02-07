# Document de Déploiement - Mercato

## 1. Prérequis

### 1.1 Infrastructure
- Serveur Linux (Ubuntu 20.04 LTS recommandé)
- Minimum 8GB RAM
- 50GB espace disque
- Connexion Internet stable

### 1.2 Logiciels Requis
- Docker v20.10+
- Docker Compose v2.0+
- Nginx v1.18+
- PostgreSQL v13+

## 2. Configuration des Systèmes de Paiement

### 2.1 Cartes Bancaires
- Installation du module de paiement
- Configuration des clés API
- Mise en place des certificats SSL
- Tests de validation

### 2.2 Mobile Money
#### MTN Money
- Configuration des credentials API
- Setup des webhooks
- Configuration des timeouts
- Tests d'intégration

#### Orange Money
- Setup de l'environnement
- Configuration des API keys
- Tests de connexion
- Validation des transactions

### 2.3 Paiement en Espèces
- Configuration du système de suivi
- Setup des notifications
- Configuration des rapports

## 3. Déploiement

### 3.1 Base de Données
```bash
# Instructions de déploiement
docker-compose up -d postgres
```

### 3.2 Backend
```bash
# Déploiement des services
docker-compose up -d api
```

### 3.3 Frontend
```bash
# Build et déploiement
docker-compose up -d web
```

## 4. Tests Post-Déploiement

### 4.1 Tests Fonctionnels
- Validation des routes API
- Test des interfaces utilisateur
- Vérification des paiements

### 4.2 Tests de Performance
- Tests de charge
- Monitoring des ressources
- Validation des temps de réponse

## 5. Mise en Production

### 5.1 Procédure de Mise en Production
- Backup des données
- Déploiement progressif
- Validation finale

### 5.2 Surveillance
- Setup des alertes
- Configuration du monitoring
- Documentation des métriques