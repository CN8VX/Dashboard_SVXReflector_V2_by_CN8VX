# Dashboard SVXReflector V2 by CN8VX

![Version](https://img.shields.io/badge/version-2.0-blue.svg)
![License](https://img.shields.io/badge/license-GPL-green.svg)
![Platform](https://img.shields.io/badge/platform-Debian%2011%2F12+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)

## 📋 Description

Dashboard SVXReflector V2 is the improved version of "Dashboard SvxReflector by CN8VX". This second version is not a simple update, but a redesign with many improvements and new features.

### ✨ New Features of Dashboard SVXReflector V2 by CN8VX

- **Enhanced graphical interface** with integrated navbar
- **Merged CSS** for better performance
- **Advanced filters** to display only important messages in "Reflector Events"
- **SVXReflector Log Viewer** - new dedicated log page with authentication
- **Modern interface** with message coloring based on action types
- **Light/dark theme** with animated switch
- **Responsive design** compatible with PC, tablet, and smartphone

## 🔍 Dashboard SVXReflector V2

Added 📅 Date and time in 🕒 local and 🌍 UTC, added symbols with their legend to improve event readability, added legend for each selected language, display of 📻 DTMF indications.

### Legend Reflector Events:
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

Specialized page developed by [CN8VX](https://www.qrz.com/db/CN8VX) accessible only to sysop and authorized users with password.

### Recognized action types
- 🔵 **Login OK** (blue)
- 🔴🟢 **Talker start/stop** (red/green)
- 🔵🟠 **Connected/Disconnected** (light blue/orange)
- 🟡🔵 **Select/Monitor TG** (yellow/pale blue)

### Advanced features
- ⚡ **Auto-refresh** every 5s (configurable)
- ⏸️ **Automatic pause** of refresh when tab is not visible
- 🔍 **Real-time search** with debouncing
- 📱 **Responsive interface** for all devices

## 🖥️ Compatibility

- **OS**: Debian 11, 12 and later versions
- **Web servers**: Apache, Nginx
- **PHP**: 7.4+ with timezone support
- **Devices**: PC, tablets, smartphones

## 📋 Prerequisites

- SVXLink installed and configured ([DMR-Morocco Guide](https://www.dmr-maroc.com/repeaters_simplex_svxlink.php))
- Configured web server
- PHP with standard extensions
- Read access to SVXLink log files

## 🚀 Installation

### Step 1: SVXReflector V2 Configuration

1. **Edit the configuration file**:
   ```bash
   sudo nano /etc/svxlink/svxreflector.conf
   ```

2. **Modify in the `[GLOBAL]` section**:
   ```conf
   TIMESTAMP_FORMAT="%d.%m.%Y %H:%M:%S"
   ```
   
3. **Save the changes and close the `svxreflector.conf` file.**

4. **Restart the service**:
   ```bash
   sudo systemctl restart svxreflector
   ```

### Step 2: File Installation

#### A. First installation
```bash
cd /var/www/html
sudo git clone https://github.com/CN8VX/Dashboard_SvxReflector_V2.git .
```

#### B. Upgrade from V1
⚠️ **Warning**: All personal data and modifications will be deleted. Please backup your configuration files.
```bash
cd /var/www/
sudo rm -rf /var/www/html/*
sudo rm -rf /var/www/html/.*
cd /var/www/html
sudo git clone https://github.com/CN8VX/Dashboard_SvxReflector_V2.git .
```

### Step 3: Permissions
```bash
sudo chmod 777 -R /var/www/html
```

## ⚙️ Configuration of `config.php` file

Edit the configuration file:
```bash
sudo nano /var/www/html/include/config.php
```

### 🌍 General Configuration
#### Timezone:
```php
// Set the timezone for your country/region. 
// You can find timezones on this site: https://www.php.net/manual/en/timezones.php
date_default_timezone_set('Africa/Casablanca');
```

### 🎨 Interface

#### Page titles:
```php
// Page title for Dashboard SVXReflector
$page_title = "SVXReflector Dashboard V2 by CN8VX";

// Page title for SVXReflector Log Viewer
$page_title_SLV = "SVXReflector Log Viewer by CN8VX";
```

#### Logo and favicon paths:
```php
// Main logo path
define("LOGO_PATH", "/path/to/logo.png");

// Favicon for Dashboard SVXReflector V2
$favicon_path = "/path/to/favicon.ico";

// Favicon for SVXReflector Log Viewer
$favicon_path_SLV = "/path/to/favicon_slv.ico";
```

### 📁 Log Files Configuration

```php
// Log path for Dashboard SVXReflector V2
$LOGFILES = array('/var/log/svxreflector');

// Log path for SVXReflector Log Viewer with rotation of 10 files
$LOGFILES_SLV = array(
    '/var/log/svxreflector',
    '/var/log/svxreflector.1',
    '/var/log/svxreflector.2',
    // ... up to .9
);
```

### 🌐 Display Language for Dashboard SVXReflector V2

```php
// Available options
$LANGUAGE = "EN";    // "FR", "FR-I", "EN", "EN-I", ""

// Number of lines in "Reflector Events"
define("LOGLINECOUNT", "30");
```

## 📖 Available Languages

| Code | Description |
|------|-------------|
| `FR` | French |
| `FR-I` | French with indications |
| `EN` | English |
| `EN-I` | English with indications |
| `""` | No legend |

### 🔄 SVXReflector Log Viewer

```php
// Refresh interval (seconds)
$refresh_interval = 5;

// Lines per page (20-120)
$lines_per_page = 20;
```
### 📘 Info bulle Client
When you hover your mouse cursor over a customer, it shows you the tooltip.

Edit the `userdb.php` file to add information about the connecting clients :
```bash
sudo nano /var/www/html/include/userdb.php
```
Then modify 'Callsing' and 'descriptif'
```php
// Example of information for connecting customers
$userdb_array = [
    'CN8VX-SV'		=> 'Répéteur Simplex VHF de Mohemadia 145.250Mhz',
    'CN8EAA-L'		=> 'Répéteur Duplex VHF de Témara 145.73750Mhz',
    /*'Callsing'		=> 'descriptif',*/
    /*'Callsing'		=> 'descriptif',*/
    /*'Callsing'		=> 'descriptif',*/
];
```

### 🔐 Authentication Configuration to access SVXReflector Log Viewer

```php
// Example user credential configuration
'users' => [
    "admin" => "admin",
    "user" => "123456",
    "user1" => "user123"
]
```

### 👤 SYSOP Information

This information appears in the FOOTER section
```php
$SYSOP = "CN8VX";
$SYSOPNAME = "Youness";
```

## 🎛️ Navbar Customization

### Navbar for Dashboard SVXReflector V2
```bash
sudo nano /var/www/html/include/navbar.php
```

### Navbar for SVXReflector Log Viewer
```bash
sudo nano /var/www/html/logviewer/include/navbar.php
```

## 🔒 Security

### ⚠️ Important Recommendations

1. **Change ALL default passwords**
2. **Use complex passwords** (8+ characters, numbers, symbols)
3. **Limit access to the `config.php` file**
4. **Regularly monitor access logs**
5. **Keep PHP and web server updated**

### 🛡️ Recommended Hardening

```bash
# Restrict access to configuration file
sudo chmod 640 /var/www/html/include/config.php
```

## 🐛 Troubleshooting

### Common Issues

1. **Blank page**: Check PHP logs and permissions
2. **Logs not displayed**: Check log file paths
3. **Authentication failed**: Verify credentials in `config.php`
4. **No refresh**: Check browser JavaScript permissions

### Useful Logs
```bash
# Apache/Nginx logs
sudo tail -f /var/log/apache2/error.log
sudo tail -f /var/log/nginx/error.log

# SVXReflector logs
sudo tail -f /var/log/svxreflector
```

## 🤝 Support and Suggestions

All questions, issues, or suggestions are welcome! Feel free to:
- Report bugs
- Propose improvements
- Share your suggestions

📧 **Email**: [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com) or via [Form](https://www.dmr-maroc.com/formulaires.php)

### Before contacting support

1. Check file permissions
2. Verify syntax in `config.php`
3. Check SVXLink logs
4. Test on different browsers

## 📄 License

This project is developed by [CN8VX](https://www.qrz.com/db/CN8VX) under GPL license.

## 🏆 Credits

**Developer**: CN8VX - SYSOP of DMR-MOROCCO SERVER  
**Version**: 2.0

---

**73 from CN8VX**

