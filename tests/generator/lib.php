<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

defined('MOODLE_INTERNAL') || die();

/**
 * Messagestream module data generator.
 *
 * @package    mod_messagestream
 * @category   test
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_messagestream_generator extends testing_module_generator {

    /**
     * @param stdClass|array|null $record
     * @param array|null $options
     * @return stdClass
     */
    public function create_instance($record = null, ?array $options = null): stdClass {
        $record = (object) (array) $record;

        if (!isset($record->name)) {
            $record->name = 'Test messagestream';
        }
        if (!isset($record->intro)) {
            $record->intro = 'Test intro';
        }
        if (!isset($record->introformat)) {
            $record->introformat = FORMAT_HTML;
        }
        if (!isset($record->privacyactive)) {
            $record->privacyactive = 0;
        }
        if (!isset($record->enableai)) {
            $record->enableai = 1;
        }
        if (!isset($record->aidefaulton)) {
            $record->aidefaulton = 1;
        }
        if (!isset($record->points)) {
            $record->points = 0;
        }

        return parent::create_instance($record, (array) $options);
    }
}
