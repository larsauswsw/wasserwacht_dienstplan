# Wasserwacht Dienstplan

Eine webbasierte Anwendung zur Verwaltung der Dienstpläne für Wasserrettungsdienste an den Standorten **Halbendorf** und **Reichenbach**.

## Funktionen

- Dienstplanerstellung und -verwaltung für mehrere Standorte und Positionen
- Zwei Zugriffsmodi: **Ansicht** (schreibgeschützt) und **Bearbeitung**
- Rollenbasierte Zugriffssteuerung (Administrator und normaler Nutzer)
- Automatische E-Mail-Benachrichtigungen bei Änderungen
- Vollständiges Änderungsprotokoll (Audit-Log)
- Hervorhebung von Wochenenden und Feiertagen

## Positionen

| Position | Standort | Qualifikation |
|---|---|---|
| Textil 1 | Halbendorf | Silber-Abzeichen, mind. 18 Jahre |
| Textil 2 | Halbendorf | Bronze-Abzeichen + Erste Hilfe |
| FKK 1 | Halbendorf | Silber-Abzeichen, mind. 18 Jahre |
| FKK 2 | Halbendorf | Bronze-Abzeichen + Erste Hilfe |
| Schwimmbad | Reichenbach | Bronze-Abzeichen + Erste Hilfe |

## Technologien

- **PHP** – Backend-Logik
- **MySQL** – Datenbankspeicherung
- **Bootstrap 2.3.2** – Responsives Frontend
- **jQuery 1.12.3** – DOM-Manipulation
- **Apache HTTP Basic Auth** – Authentifizierung

## Voraussetzungen

- PHP (mit MySQLi-Erweiterung)
- MySQL/MariaDB
- Apache-Webserver mit aktiviertem `mod_auth_basic`

## Installation

### 1. Dateien bereitstellen

Dateien in das Webserver-Verzeichnis kopieren (z. B. `/var/www/html/dienstplan/`).

### 2. Konfiguration anlegen

Die Datei `settings.template.ini` kopieren und anpassen:

```bash
cp settings.template.ini settings.ini
```

```ini
[db]
servername="localhost"
username="datenbankbenutzer"
password="passwort"
dbname="datenbankname"

[email]
from="absender@example.com"
to="empfaenger@example.com"
subject="Dienstplan Änderung"
```

> **Hinweis:** Die Datei `settings.ini` enthält sensible Zugangsdaten und darf nicht öffentlich zugänglich sein. Sie ist in `.gitignore` eingetragen.

### 3. Datenbank einrichten

Folgende Tabellen in der MySQL-Datenbank anlegen:

```sql
CREATE TABLE tage (
    tag         DATE NOT NULL PRIMARY KEY,
    textil1     VARCHAR(255),
    textil2     VARCHAR(255),
    fkk1        VARCHAR(255),
    fkk2        VARCHAR(255),
    reichenbach VARCHAR(255),
    archiv      TINYINT(1) DEFAULT 0
);

CREATE TABLE auditlog (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    tag         DATE NOT NULL,
    ort         VARCHAR(100),
    alter_wert  VARCHAR(255),
    neuer_wert  VARCHAR(255),
    zeitpunkt   DATETIME NOT NULL
);
```

### 4. Apache-Authentifizierung einrichten

Zugangsdaten mit `htpasswd` anlegen und Apache entsprechend konfigurieren:

```bash
htpasswd -c /etc/apache2/.htpasswd benutzername
```

Benutzer mit Administratorrechten werden in `index.php` in der Variable `$admins` definiert.

## Nutzung

### Ansichtsmodus

Dienstplan schreibgeschützt anzeigen:

```
https://example.com/dienstplan/?mode=view
```

### Bearbeitungsmodus

Standardmäßig ist der Bearbeitungsmodus aktiv. Angemeldete Benutzer können freie Positionen ausfüllen. Administratoren können zusätzlich bereits eingetragene Dienste überschreiben.

### Audit-Log

Das Änderungsprotokoll ist unter `auditlog.php` erreichbar.

## Benutzerrollen

| Rolle | Berechtigung |
|---|---|
| `wawa` | Freie Positionen eintragen |
| `admin` | Alle Positionen bearbeiten und überschreiben |

## Dateistruktur

```
wasserwacht_dienstplan/
├── index.php               # Hauptanwendung / Dienstplanansicht
├── update.php              # Verarbeitung von Formularänderungen
├── auditlog.php            # Ansicht des Änderungsprotokolls
├── settings.template.ini   # Konfigurationsvorlage
├── style.css               # Benutzerdefiniertes Stylesheet
├── css/                    # Bootstrap CSS
├── js/                     # jQuery und Bootstrap JavaScript
└── img/                    # Grafiken (Glyphicons)
```

## Sicherheit

- HTTP Basic Authentication schützt den gesamten Zugriff
- Zweistufiges Berechtigungsmodell (Admin / normaler Nutzer)
- Alle Änderungen werden im Audit-Log protokolliert
- E-Mail-Benachrichtigungen bei jeder Änderung
- `settings.ini` ist über `.gitignore` vor Versionskontrolle geschützt
