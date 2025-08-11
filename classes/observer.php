<?php

namespace mod_messagestream;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/messagestream/lib.php');

class observer {

  public static function handle_nmstream_entry_written(\local_nmstream\event\comment_created $event) {
    $userid = $event->userid;
    $messagestreamid = $event->objectid;

    $contextid = $event->contextinstanceid;
    $courseid = $event->courseid;

    $timecreated = $event->timecreated;
    \messagestream_set_points_for_user($contextid, $userid);
    // Do something – for example, log or update something
    //debugging("local_nmstream received stream_entry_written event for user $userid and stream $messagestreamid in Context (cmid) $contextid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
  }

  public static function handle_nmstream_entry_deleted(\local_nmstream\event\comment_deleted $event) {
    $userid = $event->userid;
    $messagestreamid = $event->objectid;

    #$contextid = $event->context;
    $contextid = $event->other["rootid"];
    $courseid = $event->courseid;

    $timecreated = $event->timecreated;

    return messagestream_set_points_for_user($contextid, $userid, true);
    #  return;
    #  \messagestream_award_points_for_user($contextid, $userid);
    // Do something – for example, log or update something
    #  var_dump("local_nmstream received comment_deleted event for user $userid and stream $messagestreamid in Context (cmid) $contextid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
    # exit;
  }

}
