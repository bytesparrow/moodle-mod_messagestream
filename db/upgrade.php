<?php

// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin upgrade steps are defined here.
 * @package    mod_messagestream
 * @category    upgrade
 * @copyright  2025 Bernhard Strehl <moodle@bytesparrow.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Execute mod_messagestream upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_messagestream_upgrade($oldversion) {
  global $DB;

  $dbman = $DB->get_manager();



  if ($oldversion < 2025062402) {
    $table = new xmldb_table('messagestream');
    // Now add a new database field.
    $field = new xmldb_field('enableai', XMLDB_TYPE_INTEGER, '1', null, true, null, 0, 'introformat');

    // Conditionally launch add field qformat.
    if (!$dbman->field_exists($table, $field)) {
      $dbman->add_field($table, $field);
    }
    // Genai savepoint reached.
    upgrade_plugin_savepoint(true, 2025062402, 'mod', 'messagestream');
  }


  if ($oldversion < 2025062409) {
    $table = new xmldb_table('messagestream');
    // Now add a new database field.
    $field = new xmldb_field('aidefaulton', XMLDB_TYPE_INTEGER, '1', null, true, null, 0, 'enableai');

    // Conditionally launch add field qformat.
    if (!$dbman->field_exists($table, $field)) {
      $dbman->add_field($table, $field);
    }
    // Aidefaulton savepoint reached.
    upgrade_plugin_savepoint(true, 2025062409, 'mod', 'messagestream');
  }
  if ($oldversion < 2025062416) {
    $table = new xmldb_table('messagestream');
    // Now add a new database field.
    $field = new xmldb_field('privacyactive', XMLDB_TYPE_INTEGER, '1', null, true, null, 0, 'introformat');

    // Conditionally launch add field qformat.
    if (!$dbman->field_exists($table, $field)) {
      $dbman->add_field($table, $field);
    }
    // Privacyactive savepoint reached.
    upgrade_plugin_savepoint(true, 2025062416, 'mod', 'messagestream');
  }



  return true;
}
