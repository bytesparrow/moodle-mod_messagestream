<?php

/*
 * Form beim Anlegen/editieren dieser Activity
 */

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(__DIR__ . '/locallib.php');

class mod_messagestream_mod_form extends moodleform_mod {

  public function definition() {
    global $COURSE, $DB;
    $courseid = $COURSE->id;
    $mform = $this->_form;
    if (!empty($this->_cm) && !empty($this->_cm->id)) {
      $cmid = $this->_cm->id;
    }
    else {
      $cmid = 0; // The activity hasn't been created/saved yet
    }

    $mform->addElement('text', 'name', get_string('messagestreamname', 'mod_messagestream'), ['size' => '64']);
    $mform->setType('name', PARAM_TEXT);
    $mform->addRule('name', null, 'required', null, 'client');

    $this->standard_intro_elements();


      $mform->addElement('advcheckbox', 'privacyactive', get_string('adminprivacyactive', 'mod_messagestream'));
      $mform->setDefault('privacyactive', 1); // Default of "yes"
      $mform->setType('privacyactive', PARAM_BOOL);
      $mform->addHelpButton('privacyactive', 'adminprivacyactive', 'mod_messagestream');
      
    $aicourses = get_messagestream_ai_activated_in_courses();
    if (in_array($courseid, $aicourses)) {
           
      
      $mform->addElement('advcheckbox', 'enableai', get_string('enableai', 'mod_messagestream'));
      $mform->setDefault('enableai', 1); // Default of "yes"
      $mform->setType('enableai', PARAM_BOOL);
      $mform->addHelpButton('enableai', 'adminenableai', 'mod_messagestream');

      $mform->addElement('advcheckbox', 'aidefaulton', get_string('adminaidefaulton', 'mod_messagestream'));
      $mform->setDefault('aidefaulton', 1); // Default of "yes"
      $mform->setType('aidefaulton', PARAM_BOOL);
      $mform->addHelpButton('aidefaulton', 'adminaidefaulton', 'mod_messagestream');
      $mform->hideif('aidefaulton', 'enableai');
      

      $personaoptions = [0 => get_string('persona_none', 'mod_messagestream')];
      if ($DB->get_manager()->table_exists('local_nmstream_personas')) {
        $personas = $DB->get_records_menu('local_nmstream_personas', null, 'name ASC', 'id, name');
        if ($personas) {
          foreach ($personas as $pid => $pname) {
            $personaoptions[(int) $pid] = format_string($pname);
          }
        }
      }
      $mform->addElement('select', 'persona_id', get_string('persona_id', 'mod_messagestream'), $personaoptions);
      $mform->setType('persona_id', PARAM_INT);
      $mform->addHelpButton('persona_id', 'persona_id', 'mod_messagestream');
      $mform->hideif('persona_id', 'enableai');
      
      
      if ($cmid) {
        $url = new moodle_url('/mod/messagestream/view.php', [
          'id' => $cmid,
          'view' => 'coach',
        ]);

        $html = html_writer::link(
            $url,
            get_string('coachsettings:configure_persona', 'mod_messagestream')
        );

        #$mform->addElement('html', $html);
        $mform->addElement(
          'static',
          'configurepersonalink',
          '',
          $html
        );
        $mform->hideif('configurepersonalink', 'enableai');
      }

      
      $mform->addElement('textarea', 'promptrefinement', get_string('promptrefinement', 'mod_messagestream'), array('rows' => 10, 'cols' => 60));
      $mform->setType('promptrefinement', PARAM_TEXT);
      $mform->hideif('promptrefinement', 'enableai');


      // Overrides are edited on the activity “Persona” tab; keep stored JSON on save.
      $mform->addElement('hidden', 'persona_overrides_json');
      $mform->setType('persona_overrides_json', PARAM_RAW);
      $mform->hideif('persona_overrides_json', 'enableai');
    }
    else {
      $mform->addElement('hidden', 'enableai');
      $mform->setType('enableai', PARAM_BOOL);
      $mform->setDefault('enableai', 0); // "no"
      $mform->addElement('hidden', 'aidefaulton');
      $mform->setType('aidefaulton', PARAM_BOOL);
      $mform->setDefault('aidefaulton', "");
      $mform->addElement('hidden', 'promptrefinement');
      $mform->setType('promptrefinement', PARAM_TEXT);
      $mform->setDefault('promptrefinement', "");
      $mform->addElement('hidden', 'persona_id');
      $mform->setType('persona_id', PARAM_INT);
      $mform->setDefault('persona_id', 0);
      $mform->addElement('hidden', 'persona_overrides_json');
      $mform->setType('persona_overrides_json', PARAM_RAW);
      $mform->setDefault('persona_overrides_json', '');
    }


    $mform->addElement('text', 'points', get_string('points', 'mod_messagestream'));
    $mform->setType('points', PARAM_INT);
    $mform->addRule('points', null, 'required', null, 'client');
    $mform->setDefault('points', 0);
    $mform->addHelpButton('points', 'adminpoints', 'mod_messagestream');

    $this->standard_coursemodule_elements();
    $this->add_action_buttons();
  }

  public function completion_rule_enabled($data) {
    // Wenn es keine eigenen Completion-Kriterien gibt, immer true zurückgeben.
    return !empty($data['completionusegrade']);
  }

  public function validation($data, $files) {
    return parent::validation($data, $files);
  }

}
