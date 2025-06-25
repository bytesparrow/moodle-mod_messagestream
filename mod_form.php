<?php

/* 
 * Form beim Anlegen/editieren dieser Activity
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_messagestream_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('messagestreamname', 'mod_messagestream'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements();


        $mform->addElement('advcheckbox', 'enableai', get_string('enableai', 'mod_messagestream'));
        $mform->setDefault('enableai', 1); // Default of "no"
        $mform->setType('enableai', PARAM_BOOL);
        $mform->addHelpButton('enableai', 'adminenableai', 'mod_messagestream');


        $mform->addElement('textarea', 'promptrefinement', get_string('promptrefinement', 'mod_messagestream'), array('rows' => 10, 'cols' => 60));
        $mform->setType('promptrefinement', PARAM_TEXT);
        $mform->hideif('promptrefinement', 'enableai');

        $mform->addElement('text', 'points', get_string('points', 'mod_messagestream'));
        $mform->setType('points', PARAM_INT);
        $mform->addRule('points', null, 'required', null, 'client');
        $mform->setDefault('points',  0);
        $mform->addHelpButton('points', 'adminpoints', 'mod_messagestream');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
    public function completion_rule_enabled($data) {
    // Wenn es keine eigenen Completion-Kriterien gibt, immer true zurückgeben.
    return !empty($data['completionusegrade']);
}
}
