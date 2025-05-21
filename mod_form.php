<?php

/* 
 * Form beim Anlegen/editieren dieser Activity
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_stream_mod_form extends moodleform_mod {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('streamname', 'mod_stream'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $this->standard_intro_elements();

        $mform->addElement('editor', 'promptrefinement', get_string('promptrefinement', 'mod_stream'));
        $mform->setType('promptrefinement', PARAM_RAW);

        $mform->addElement('text', 'points', get_string('points', 'mod_stream'));
        $mform->setType('points', PARAM_INT);
        $mform->addRule('points', null, 'required', null, 'client');
        $mform->setDefault('points',  0);
        $mform->addHelpButton('points', 'adminpoints', 'mod_stream');

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
    public function completion_rule_enabled($data) {
    // Wenn es keine eigenen Completion-Kriterien gibt, immer true zurückgeben.
    return !empty($data['completionusegrade']);
}
}
