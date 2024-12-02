
# Installation d'un Serveur Linux pour un Projet PHP avec Docker

Ce guide décrit comment configurer un serveur Docker pour héberger un projet PHP. Docker permet d'exécuter facilement les services nécessaires dans des conteneurs isolés.

---

## Pré-requis

1. **Serveur Linux** (physique ou virtuel).
2. **Docker** et **Docker Compose** installés.
3. Accès à Internet.

### Installer Docker
Pour installer Docker et Docker Compose, utilisez les commandes suivantes :
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install docker.io docker-compose -y
sudo systemctl start docker
sudo systemctl enable docker
```

Vérifiez que Docker est bien installé :
```bash
docker --version
docker-compose --version
```

---

## Étapes d'installation

### 1. Préparer le fichier `docker-compose.yml`

Créez un répertoire pour votre projet Docker et un fichier `docker-compose.yml` :
```bash
mkdir mon-projet-php
cd mon-projet-php
nano docker-compose.yml
```

Ajoutez le contenu suivant :

```yaml
version: '3.8'

services:
  web:
    image: php:8.2-apache
    container_name: php-web
    ports:
      - "8080:80"
    volumes:
      - ./www:/var/www/html
      - ./php.ini:/usr/local/etc/php/php.ini
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: mysql-db
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: projet
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - db_data:/var/lib/mysql
    ports:
      - "3306:3306"

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: phpmyadmin
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: rootpassword
    ports:
      - "8081:80"

volumes:
  db_data:
```

---

### 2. Préparer les fichiers du projet

1. **Créer un répertoire pour votre code PHP :**
   ```bash
   mkdir www
   ```
2. **Créer un fichier `php.ini` personnalisé (facultatif) :**
   ```bash
   nano php.ini
   ```
   Exemple de configuration :
   ```ini
   display_errors = On
   memory_limit = 256M
   upload_max_filesize = 50M
   post_max_size = 50M
   ```
3. Placez votre projet PHP dans le répertoire `www`.

---

### 3. Lancer Docker Compose

Démarrez les conteneurs avec Docker Compose :
```bash
docker-compose up -d
```

Vérifiez que les conteneurs fonctionnent :
```bash
docker ps
```

---

### 4. Accéder aux services

- **Projet PHP :**  
  Accédez à votre projet via [http://localhost:8080](http://localhost:8080).

- **PhpMyAdmin :**  
  Gérez la base de données via [http://localhost:8081](http://localhost:8081).  
  Utilisez les informations suivantes :
  - **Serveur :** `db`
  - **Utilisateur :** `root`
  - **Mot de passe :** `rootpassword`

---

### 5. Gérer les conteneurs

- **Arrêter les conteneurs :**
  ```bash
  docker-compose down
  ```

- **Redémarrer les conteneurs :**
  ```bash
  docker-compose up -d
  ```

- **Visualiser les logs :**
  ```bash
  docker-compose logs -f
  ```

- **Supprimer les conteneurs et les volumes associés :**
  ```bash
  docker-compose down --volumes
  ```

---

## Avantages de Docker
- **Isolation :** Chaque service fonctionne dans son propre conteneur.
- **Portabilité :** Le projet peut être facilement déployé sur d'autres machines.
- **Simplicité :** Toutes les dépendances sont gérées via des conteneurs.

---

Votre serveur Docker est maintenant prêt à héberger votre projet PHP. 🎉
