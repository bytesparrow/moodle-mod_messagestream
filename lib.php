<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/lib/gradelib.php');
//Hier übrigens ne doc fürs Verwenden der Gradelib:
//https://moodle.org/mod/forum/discuss.php?d=449152
  

/**
 * Adds a new instance of the messagestream module.
 *
 * @param object $data
 * @param object $mform
 * @return int new instance id
 */
function messagestream_add_instance($data, $mform) {
  global $DB;

  $data->timecreated = time();
  $data->timemodified = $data->timecreated;

  // Intro wird automatisch durch standardformelement() behandelt
  if (isset($data->introeditor)) {
    $data->intro = $data->introeditor['text'];
    $data->introformat = $data->introeditor['format'];
  }

  return $DB->insert_record('messagestream', $data);
}

/**
 * Updates an existing stream instance.
 *
 * @param object $data
 * @param object $mform
 * @return bool
 */
function messagestream_update_instance($data, $mform) {
  global $DB;

  $data->timemodified = time();
  $data->id = $data->instance;

  if (isset($data->introeditor)) {
    $data->intro = $data->introeditor['text'];
    $data->introformat = $data->introeditor['format'];
  }
  //normal nicht nötigt, $data->promptrefinement ist bereits der Text
  if (!empty($data->promptrefinement) && is_array($data->promptrefinement)) {
    $data->promptrefinement = $data->promptrefinement['text'];
  }
  
 $succ = $DB->update_record('messagestream', $data);

  // Ergänze coursemodule ID (wird z. B. für context benötigt).
    if (!isset($data->coursemodule)) {
        $cm = get_coursemodule_from_instance('messagestream', $data->id, $data->course, false, MUST_EXIST);
        $data->coursemodule = $cm->id;
    }

    // Alle bisherigen Bewertungen aktualisieren
    messagestream_update_grades($data);
    return $succ;
}

/**
 * Deletes a messagestream instance.
 *
 * @param int $id
 * @return bool
 */
function messagestream_delete_instance($id) {
  global $DB;

  if (!$messagestream = $DB->get_record('messagestream', ['id' => $id])) {
    return false;
  }

  return $DB->delete_records('messagestream', ['id' => $id]);
}

/**
 * Returns the list of features supported by the messagestream module.
 *
 * @param string $feature
 * @return mixed
 */
function messagestream_supports($feature) {
  switch ($feature) {
    case FEATURE_GRADE_HAS_GRADE: return true;
    case FEATURE_GRADE_OUTCOMES: return false;
    case FEATURE_BACKUP_MOODLE2: return true;
    // weitere optional:
    case FEATURE_MOD_PURPOSE: return MOD_PURPOSE_COMMUNICATION;
    case FEATURE_MOD_ARCHETYPE: return MOD_ARCHETYPE_OTHER;
    case FEATURE_SHOW_DESCRIPTION: return true;
    default: return null;
  }
}

function messagestream_grade_item_update($messagestream, $userid = null) {


  $item = [
    'itemname' => clean_param($messagestream->name, PARAM_NOTAGS),
    'gradetype' => GRADE_TYPE_VALUE,
    'grademax' => (float) $messagestream->points,
    'grademin' => 0
  ];

  if ($userid !== null) {
    $userid = (int) $userid; // <<< FIX HIER
    $grades = [
      $userid => [
        'userid' => $userid,
        'rawgrade' => (float) $messagestream->points
      ]
    ];
    grade_update('mod/messagestream', $messagestream->course, 'mod', 'messagestream', $messagestream->id, 0, $grades, $item);
  }
  else {
    grade_update('mod/messagestream', $messagestream->course, 'mod', 'messagestream', $messagestream->id, 0, null, $item);
  }
}
/**
 * Schreibe alle ($userid=0) oder eine einzelne Bewertung ($userid=X) neu. Punkte werden aus der Activity-Config genommen.
 * @global type $DB
 * @param stdClass $messagestream
 * @param type $userid
 * @param type $nullifnone
 * @return type
 */
function messagestream_update_grades(stdClass $messagestream, $userid = 0, $revoke_points = false) {
    global $DB;

    // Fallback: Sicherstellen, dass coursemodule vorhanden ist.
    if (!isset($messagestream->coursemodule)) {
        $cm = get_coursemodule_from_instance('messagestream', $messagestream->id, $messagestream->course);
        if (!$cm) {
            debugging("messagestream_update_grades: coursemodule not found for messagestream id {$messagestream->id}", DEBUG_DEVELOPER);
            return null;
        }
        $messagestream->coursemodule = $cm->id;
    }

    $grades = [];

    if ($userid) {
        // Einzelner Nutzer – setze Bewertung auf volle Punktzahl
        $grades[$userid] = (object)[
            'userid' => $userid,
            'rawgrade' => $revoke_points? NULL: (float)($messagestream->points)
        ];
    } else {
        
        // Alle Nutzer, die Zugriff auf die Aktivität haben
        $context = context_module::instance($messagestream->coursemodule);
        $users = get_enrolled_users($context, 'mod/messagestream:view');

        foreach ($users as $user) {
            $grades[$user->id] = (object)[
                'userid' => $user->id,
                'rawgrade' => $revoke_points? NULL: (float)($messagestream->points)  
            ];
    }
    }
  // ✳️ Gradebook-Item aktualisieren (z. B. Name oder Maximalpunktzahl geändert)
    messagestream_grade_item_update($messagestream);
    
    return grade_update('mod/messagestream', $messagestream->course, 'mod', 'messagestream',
                        $messagestream->id, 0, $grades);
}

function messagestream_revoke_grade($course_id, $activityid, $user_id)
{
  if ($user_id) {
        // Einzelner Nutzer – setze Bewertung auf volle Punktzahl
        $grades[$user_id] = (object)[
            'userid' => $user_id,
            'rawgrade' => 0
        ];
    }
    return grade_update('mod/messagestream', $course_id, 'mod', 'messagestream',
                        $activityid, 0, $grades);
}
    

/**
 * Awards the (set) points for a user with a given $cmid (COURSE MODULE ID).
 * Points are defined in the messagestream-activity. Can not override the num of points.
 * For Sergej.
 * 
 * @global type $DB
 * @global type $CFG
 * @param int $cmid course-module-id
 * @param int $userid id of the user to give points to
 * @param bool $revoke_points if set to true, the users' points will be zero.
 * @return bool
 */
function messagestream_set_points_for_user(int $cmid, int $userid, $revoke_points=false): bool {
  global $DB, $CFG;

  // 1. Kursmodul prüfen und laden
  if (!$cm = get_coursemodule_from_id('messagestream', $cmid)) {
    #debugging("Modul-ID $cmid konnte nicht gefunden werden (mod_messagestream)", DEBUG_DEVELOPER);
    return false;
  }

  // 2. Stream-Aktivität laden
  if (!$messagestream = $DB->get_record('messagestream', ['id' => $cm->instance])) {
    debugging("Messagestream-Instanz mit ID {$cm->instance} nicht gefunden", DEBUG_DEVELOPER);
    return false;
  }

  // 3. Nutzer-ID prüfen (optional)
  if (!$DB->record_exists('user', ['id' => $userid])) {
    debugging("Benutzer mit ID $userid existiert nicht", DEBUG_DEVELOPER);
    return false;
  }
    
  // 6. Bewertung schreiben
  $result = messagestream_update_grades($messagestream, $userid, $revoke_points);

  // 7. Erfolg prüfen
// Akzeptiere TRUE, 0 (kein Update notwendig), oder ein Array ohne Fehler
  if ($result === false || (is_array($result) && !empty($result['error']))) {
    debugging("Fehler beim Schreiben der Bewertung: " . print_r($result, true), DEBUG_DEVELOPER);
    return false;
  }
  return true;
}


/**
  Wenn du das Icon auch bei „Aktivität oder Material anlegen“ schöner darstellen willst, ergänze in deiner lib.php diese Funktion:
 */
function mod_messagestream_get_shortcut_icon() {
  return ['mod_messagestream', 'icon'];
}

/**
 * Whether the activity is branded.
 * This information is used, for instance, to decide if a filter should be applied to the icon or not.
 *
 * @return bool True if the activity is branded, false otherwise.
 */
function messagestream_is_branded(): bool {
  return true;
}
