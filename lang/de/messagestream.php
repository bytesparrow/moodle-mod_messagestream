<?php
/**
 * @package    mod_messagestream
 * @copyright  2025 Bernhard Strehl <moodle@bytesparrow.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['modulename'] = 'Nachrichten-Stream-Aktivität';
$string['modulenameplural'] = 'Nachrichten-Stream-Aktivitäten';
$string['modulename_help'] = 'Die Nachrichten-Stream-Aktivität ermöglicht verschachtelte Diskussionen zwischen Studierenden und einer KI. Studierende können Beiträge posten und dafür Punkte erhalten. Die KI kann dabei Fragen beantworten oder Stellungnahmen zu Aussagen abgeben. Diese Aktivität ist für die Verwendung mit local_nmstream vorgesehen und unterstützt interaktive, KI-gestützte Lernformate.';
$string['messagestreamname'] = 'Aktivitätsname';
$string['adminprivacyactive'] = 'Private Beiträge und Kommentare erlauben';
$string['adminprivacyactive_help'] = 'Legt fest, man eigene Einträge/Kommentare als privat markieren kann.';
$string['enableai'] = 'KI aktivieren';
$string['adminenableai'] = 'KI aktivieren';
$string['adminenableai_help'] = 'Wenn aktiviert, kann eine KI auf Beiträge antworten';
$string['adminaidefaulton'] = '"KI verwenden" voreingestellt';
$string['adminaidefaulton_help'] = 'Steuert, ob beim Verfassen eines Beitrags "KI verwenden" aktiviert oder deaktiviert ist.';
$string['promptrefinement'] = 'Prompt-Ergänzung für die KI. Es sollten aber alle Einstellungen über die Persona getätigt werden!';
$string['points'] = 'Vergebene Punkte (für Bewertungsübersicht)';
$string['pluginname'] = 'Nachrichten-Stream-Aktivität';
$string['clicktogetpoints'] = 'Klicke, um deine Punkte zu erhalten!';
$string['youreceivedpoints'] = 'Du hast {$a} Punkte erhalten!';
$string['pluginadministration'] = 'Plugin-Verwaltung';
$string['getpoints'] = 'Punkte erhalten';
$string['pointsawarded'] = 'Punkte wurden gutgeschrieben!';
$string['adminpoints'] = 'Vergebene Punkte';
$string['adminpoints_help'] = 'Wenn ein Lernender einen Beitrag postet, kann die Person hierfür (einmalig) Punkte erhalten. Wieviele, geben Sie hier an.';
$string['aienabledcourses'] = 'KI in Kursen aktiviert';
$string['aienabledcourses_desc'] = 'Geben Sie eine kommaseparierte Liste von Kurs-IDs an, in denen die KI des Message Stream Plugin aktiviert sein soll. Beispiel: 2,5,8';
$string['statinfo'] = 'Bisher {$a->count_posts} Beiträge, {$a->count_comments} Kommentare & {$a->count_ai_comments} KI-Antworten';
$string['statinfo_with_ai'] = 'Bisher {$a->count_posts} Beiträge, {$a->count_comments} Kommentare & {$a->count_ai_comments} KI-Antworten';
$string['persona_id'] = 'NMStream-Persona';
$string['persona_id_help'] = 'Optional. Die kompilierte Persona wird als zusätzliche Systemanweisung vor dem Standard-Kurs-Agent und der Aktivitäts-Verfeinerung verwendet.';
$string['persona_none'] = '— Keine —';
$string['persona_overrides_json'] = 'Persona-Overrides (JSON, fortgeschritten)';
$string['persona_overrides_json_help'] = 'Optionales JSON-Objekt mit wenigen Feldern, die die gewählte Persona überschreiben (gleiche Schlüssel wie NMStream-Persona, außer verschlüsselten Hinweisen). Beispiel: {"task":"Fokus auf Reflexion."}';
$string['persona_overrides_json_invalid'] = 'Ungültiges JSON.';
$string['viewtab_stream'] = 'Aktivität';
$string['coachsettings:tab'] = 'Persona-Einstellungen';
$string['coachsettings:persona'] = 'NMStream-Persona';
$string['coachsettings:configure_persona'] = 'Persona-Details der Aktivität konfigurieren';
$string['coachsettings:overrides_heading'] = 'Feld-Overrides';
$string['coachsettings:override_hint'] = 'Aktivieren Sie die Checkbox, um dieses Feld nur für diese Aktivität zu überschreiben. Die Werte werden als sparsames JSON in der Instanz gespeichert.';
$string['coachsettings:override_activate'] = 'Dieses Feld überschreiben';
$string['coachsettings:field'] = 'Feld';
$string['coachsettings:value'] = 'Wert';
$string['coachsettings:persona_default'] = 'Persona-Standard';
$string['coachsettings:save'] = 'Persona-Einstellungen speichern';
$string['coachsettings:saved'] = 'Persona-Einstellungen gespeichert.';
$string['coachsettings:edit_persona'] = 'Persona bearbeiten';
$string['coachsettings:new_persona'] = 'Neue Persona';
$string['coachsettings:avatar_hint'] = 'Emoji, Bild-URL oder Bild hochladen (als Data-URL gespeichert).';
$string['coachsettings:avatar_placeholder'] = 'Emoji oder Bild-URL';
$string['coachsettings:error_save'] = 'Einstellungen konnten nicht gespeichert werden.';
$string['coachsettings:error_request'] = 'Anfrage fehlgeschlagen.';