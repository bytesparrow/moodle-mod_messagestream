<?php

/*
 * Form beim Anlegen/editieren dieser Activity
 */

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(__DIR__ . '/locallib.php');

class mod_messagestream_mod_form extends moodleform_mod {

  public function definition() {
    global $COURSE;
    $courseid = $COURSE->id;
    $mform = $this->_form;

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

      $mform->addElement('textarea', 'promptrefinement', get_string('promptrefinement', 'mod_messagestream'), array('rows' => 10, 'cols' => 60));
      $mform->setType('promptrefinement', PARAM_TEXT);
      $mform->hideif('promptrefinement', 'enableai');
    }
    else {
      $mform->addElement('hidden', 'enableai');
      $mform->setDefault('enableai', 0); // "no"
      $mform->addElement('hidden', 'aidefaulton');
      $mform->setDefault('aidefaulton', "");
      $mform->addElement('hidden', 'promptrefinement');
      $mform->setDefault('promptrefinement', "");
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

}
