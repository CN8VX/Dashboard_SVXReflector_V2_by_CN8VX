# Dashboard SVXReflector V2 by CN8VX

![Version](https://img.shields.io/badge/version-2.0-blue.svg)
![License](https://img.shields.io/badge/license-GPL-green.svg)
![Platform](https://img.shields.io/badge/platform-Debian%2011%2F12+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)

## 📋 Description

Dashboard SVXReflector V2 est la version améliorée du [Dashboard SvxReflector by CN8VX](https://github.com/CN8VX/Dashboard_SvxReflector_by_CN8VX). Cette deuxième version n'est pas une simple mise à jour, mais une refonte avec de nombreuses améliorations et nouvelles fonctionnalités.

### ✨ Nouvelles fonctionnalités du Dashboard SVXReflector V2 by CN8VX

- **Interface graphique améliorée** avec navbar intégrée
- **CSS fusionnés** pour de meilleures performances
- **Filtres avancés** pour afficher uniquement les messages importants dans "Reflector Events"
- **SVXReflector Log Viewer** - nouvelle page dédiée aux logs avec authentification
- **Interface moderne** avec coloration des messages selon les types d'actions
- **Thème clair/sombre** avec switch animé
- **Design responsive** compatible PC, tablette et smartphone

## 🔍 Dashboard SVXReflector V2

Ajout de 📅 Date et heure en 🕒 locale et en 🌍 UTC, ajout des symboles avec leurs légende pour améliorer la lecture des évènements, ajout de légende pour chaque Langue sélectionner, affichage des 📻 Indications DTMF.

### Legend Reflector Events :
- 🟩 : Talker start (TX).
- 🟥 : Talker stop.
- 🔵 : Login OK, connection successful.
- 🟠 : Disconnected.
- 🔴 : Login failed.
- 🟨 : TG number select.
- 🟦 : Monitor TG.
- 🟡 : Timeout.
- ❌ : Error.
- ⚠️ : Warning.
- ⚪ : Default (other events).

## 🔍 SVXReflector Log Viewer

Page spécialisée développée par [CN8VX](https://www.qrz.com/db/CN8VX) accessible uniquement au sysop et aux utilisateurs autorisés avec mot de passe.

### Types d'actions reconnues
- 🔵 **Login OK** (bleu)
- 🔴🟢 **Talker start/stop** (rouge/vert)
- 🔵🟠 **Connected/Disconnected** (bleu clair/orange)
- 🟡🔵 **Select/Monitor TG** (jaune/bleu pâle)

### Fonctionnalités avancées
- ⚡ **Auto-refresh** toutes les 5s (configurable)
- ⏸️ **Pause automatique** du refresh quand l'onglet n'est pas visible
- 🔍 **Recherche en temps réel** avec debouncing
- 📱 **Interface responsive** pour tous les appareils

## 🖥️ Compatibilité

- **OS** : Debian 11, 12 et versions ultérieures
- **Serveurs web** : Apache, Nginx
- **PHP** : 7.4+ avec support des fuseaux horaires
- **Appareils** : PC, tablettes, smartphones

## 📋 Prérequis

- SVXLink installé et configuré ([Guide DMR-Maroc](https://www.dmr-maroc.com/repeaters_simplex_svxlink.php))
- Serveur web configuré
- PHP avec extensions standard
- Accès en lecture aux fichiers de logs SVXLink

## 🚀 Installation

### Étape 1 : Configuration SVXReflector V2

1. **Éditez le fichier de configuration** :
   ```bash
   sudo nano /etc/svxlink/svxreflector.conf
   ```

2. **Modifier dans la section `[GLOBAL]`** :
   ```conf
   TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
   ```
   
3. **Enregistrez les modifications puis fermez le fichier `svxreflector.conf`.**

4. **Redémarrez le service** :
   ```bash
   sudo systemctl restart svxreflector
   ```

### Étape 2 : Installation des fichiers

#### A. Première installation
```bash
cd /var/www/html
```
```bash
sudo git clone https://github.com/CN8VX/Dashboard_SVXReflector_V2_by_CN8VX.git .
```

#### B. Mise à jour depuis V1
⚠️ **Attention** : Toutes les données personnelles et modifications seront supprimées. Veuillez faire une sauvegarde de vos fichiers de configuration.
```bash
cd /var/www/
sudo rm -rf /var/www/html/*
sudo rm -rf /var/www/html/.*
cd /var/www/html
sudo git clone https://github.com/CN8VX/Dashboard_SVXReflector_V2_by_CN8VX.git .
```

### Étape 3 : Permissions
```bash
sudo chmod 777 -R /var/www/html
```

## ⚙️ Configuration du fichier `config.php`

Éditez le fichier de configuration :
```bash
sudo nano /var/www/html/include/config.php
```

### 🌍 Configuration générale
#### Fuseau Horaire :
```php
// Définissez le fuseau horaire de votre pays/région. 
// Vous trouverez les fuseaux horaires sur ce site : https://www.php.net/manual/en/timezones.php
date_default_timezone_set('Africa/Casablanca');
```

### 🎨 Interface

#### Titres des pages :
```php
// Titre de page pour Dashboard SVXReflector
$page_title = "SVXReflector Dashboard V2 by CN8VX";

// Titre de page pour SVXReflector Log Viewer
$page_title_SLV = "SVXReflector Log Viewer by CN8VX";
```

#### Chemins du logo et des favicons :
```php
// Chemin du logo principal
define("LOGO_PATH", "/path/to/logo.png");

// Favicon pour Dashboard SVXReflector V2
$favicon_path = "/path/to/favicon.ico";

// Favicon pour SVXReflector Log Viewer
$favicon_path_SLV = "/path/to/favicon_slv.ico";
```

### 📁 Configuration des Fichiers de Logs

```php
// Chemin du log pour Dashboard SVXReflector V2
$LOGFILES = array('/var/log/svxreflector');

// Chemin du log pour SVXReflector Log Viewer avec rotation de 10 fichiers
$LOGFILES_SLV = array(
    '/var/log/svxreflector',
    '/var/log/svxreflector.1',
    '/var/log/svxreflector.2',
    // ... jusqu'à .9
);
```

### 🌐 Langue d'affichage pour Dashboard SVXReflector V2

```php
// Options disponibles
$LANGUAGE = "FR";    // "FR", "FR-I", "EN", "EN-I", ""

// Nombre de lignes dans "Reflector Events"
define("LOGLINECOUNT", "30");
```

## 📖 Langues disponibles

| Code | Description |
|------|-------------|
| `FR` | Français |
| `FR-I` | Français avec indications |
| `EN` | English |
| `EN-I` | English with indications |
| `""` | Pas de légende |

### 🔄 Log Viewer

```php
// Intervalle de rafraîchissement (secondes)
$refresh_interval = 5;

// Lignes par page (20-120)
$lines_per_page = 20;
```

### 🔐 Configuration d'Authentification pour accéder a SVXReflector Log Viewer

```php
// Exemple de configuration des identifiants utilisateurs
'users' => [
    "admin" => "admin",
    "user" => "123456",
    "user1" => "user123"
]
```

### 👤 Informations SYSOP

Ces informations apparaissent dans la partie FOOTER
```php
$SYSOP = "CN8VX";
$SYSOPNAME = "Youness";
```

### 📘 Info bulle Client
Quand tu poses le curseur de la sourie sur un client il te montre l’info bulle.

Éditez le fichier `userdb.php` pour ajouter les informations sur les clients connecter :
```bash
sudo nano /var/www/html/include/userdb.php
```
Puis modifier 'Callsing' et 'descriptif'
```php
// Exemple d’infos pour clients connecter.
    'CN8VX-SV'		=> 'Répéteur Simplex VHF de Mohemadia 145.250Mhz',
    'CN8EAA-L'		=> 'Répéteur Duplex VHF de Témara 145.73750Mhz',
    //'Callsing'		=> 'descriptif',
    //'Callsing'		=> 'descriptif',
    //'Callsing'		=> 'descriptif',
```
### 🛈 Nom d'alias pour TG
Éditez le fichier `tgdb.php` pour nommer vos Talk Group :
```bash
sudo nano /var/www/html/include/tgdb.php
```
```php
// Exemple d'alias pour Talk Group.
	'#0'     => 'IDLE',
	'#6041'    => 'Cross Mode DMR = ANALOGIQUE',
	'#604' => 'Morocco - National',
	'#604112' => 'EmComm Morocco',
	//'#talkgroup_number' => 'Alias Name',
	//'#talkgroup_number' => 'Alias Name',
	//'#talkgroup_number' => 'Alias Name',
```

## 🎛️ Personnalisation des Navbar

### Navbar pour Dashboard SVXReflector V2
```bash
sudo nano /var/www/html/include/navbar.php
```

### Navbar pour SVXReflector Log Viewer
```bash
sudo nano /var/www/html/logviewer/include/navbar.php
```

## 🔒 Sécurité

### ⚠️ Recommandations importantes

1. **Changez TOUS les mots de passe par défaut**
2. **Utilisez des mots de passe complexes** (8+ caractères, chiffres, symboles)
3. **Limitez l'accès au fichier `config.php`**
4. **Surveillez régulièrement les logs d'accès**
5. **Maintenez PHP et le serveur web à jour**

### 🛡️ Durcissement recommandé

```bash
# Restriction d'accès au fichier de configuration
sudo chmod 640 /var/www/html/include/config.php
```

## 🐛 Dépannage

### Problèmes courants

1. **Page blanche** : Vérifiez les logs PHP et les permissions
2. **Logs non affichés** : Contrôlez le chemin des fichiers de logs
3. **Authentification échouée** : Vérifiez les identifiants dans `config.php`
4. **Pas de rafraîchissement** : Vérifiez les permissions JavaScript du navigateur

### Logs utiles
```bash
# Logs Apache/Nginx
sudo tail -f /var/log/apache2/error.log
sudo tail -f /var/log/nginx/error.log

# Logs SVXReflector
sudo tail -f /var/log/svxreflector
```

## 🤝 Support et Suggestions

Toutes questions, problèmes ou suggestions sont les bienvenus ! N'hésitez pas à :
- Signaler des bugs
- Proposer des améliorations
- Partager vos suggestions

📧 **Email** : [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com)

### Avant de contacter le support

1. Vérifiez les permissions des fichiers
2. Contrôlez la syntaxe dans `config.php`
3. Consultez les logs SVXLink
4. Testez sur différents navigateurs

## 📄 Licence

Ce projet est développé par [CN8VX](https://www.qrz.com/db/CN8VX) sous licence GPL.

## 🏆 Crédits

**Développeur** : CN8VX - SYSOP du SERVEUR DMR-MAROC  
**Version** : 2.0

---

**73 de CN8VX**









