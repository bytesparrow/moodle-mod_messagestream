# 📡 Messagestream Activity (mod_messagestream) for Moodle

**Messagestream Activity** ist ein benutzerdefiniertes Moodle-Aktivitätsmodul, das in enger Zusammenarbeit mit dem Plugin [`local_nm_stream`](https://github.com/dein-repo/local_nm_stream) arbeitet. Es stellt eine einfache Aktivität bereit, bei der Teilnehmer*innen durch einen Buttonklick Punkte erhalten können. Das Plugin eignet sich ideal zur Anbindung an automatische Feedback- oder Prompt-Refinement-Prozesse.

---

## 🔧 Features

- Zwei konfigurierbare Felder für Lehrende:
  - **Beschreibung** (wird angezeigt)
  - **Prompt Refinement** (versteckt, für spätere Auswertung)
- Punktezuteilung an Teilnehmer per Buttonklick
- Automatische Integration ins Gradebook
- Unterstützung für:
  - Internationalisierung (i18n)
  - Moodle Backup/Restore-System
  - Bewertungs-API
- Templating mit Mustache + Moodle Renderer API

---

## ⚠️ Wichtig: Abhängigkeit

**Dieses Plugin setzt zwingend das Plugin [`local_nm_stream`](https://github.com/n-multimedia/local_nmstream) voraus.**

Ohne `local_nm_stream` ist die Funktionalität stark eingeschränkt und macht **keinen Sinn**, da Anzeige und Verarbeitung über dieses lokale Plugin abgewickelt werden.

---

## 🧪 Anwendungsbeispiel

1. Lehrkraft erstellt eine neue *Messagestream Activity* im Kurs.
2. Gibt eine Beschreibung ein und optional einen Prompt-Refinement-Text.
3. Konfiguriert die Punktevergabe (z. B. 10 Punkte).
4. Erstellen Lernende einen Stream-Eintrag, erhalten sie diese Punkte gutgeschrieben
5. Punkte werden direkt im Gradebook verzeichnet.

---

## 🛠️ Installation

1. Lege den Ordner `mod_messagestream` im Verzeichnis `moodle/mod/` an.
2. Kopiere alle Dateien in diesen Ordner.
3. Rufe die Moodle-Administrationsseite auf, um die Installation abzuschließen.
4. Stelle sicher, dass auch `local_nm_stream` installiert und konfiguriert ist.

---

## 📁 Dateien

- `mod_form.php` – Formular für die Kurserstellung
- `lib.php` – Zentrale Logik (add, update, delete, grade)
- `view.php` – Anzeige der Aktivität mit Punkteschaltfläche
- `renderer.php` + `templates/` – Darstellung mit Mustache
- `backup/` – Unterstützung für Moodle-Backup und Restore

---

## 👤 Entwickler

Dieses Plugin wurde entwickelt für spezialisierte Workflows mit Prompt-Interaktionen und automatischer Punktevergabe im Rahmen von KI-gestützten Lernsystemen.

---

## 📝 Lizenz

GNU General Public License v3.0  
2025 Bernhard Strehl <moodle@bytesparrow.de>

