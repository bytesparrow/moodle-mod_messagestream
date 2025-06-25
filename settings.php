<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Nur Site-Admins können das sehen.

  $modmessagestream = new admin_category('modmessagestream', new lang_string('pluginname', 'mod_messagestream'), $module->is_enabled() === false);
  $ADMIN->add('modsettings', $modmessagestream);

  $settings->add(new admin_setting_configtext(
      'mod_messagestream/aienabledcourses',
      get_string('aienabledcourses', 'mod_messagestream'),
      get_string('aienabledcourses_desc', 'mod_messagestream'),
      '',
      PARAM_TEXT
  ));
}
