<?php
namespace mod_stream;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/stream/lib.php');

class observer {
    public static function handle_stream_entry_written(\local_nmstream\event\comment_created $event) {
        global $DB;
        $userid = $event->userid;
        $streamid = $event->objectid;
       
        $contextid = $event->contextinstanceid;
        $courseid = $event->courseid;

        $timecreated = $event->timecreated;
        \stream_set_points_for_user($contextid, $userid);
        // Do something – for example, log or update something
       //debugging("local_nmstream received stream_entry_written event for user $userid and stream $streamid in Context (cmid) $contextid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
    }
    public static function handle_stream_entry_deleted(\local_nmstream\event\comment_deleted $event) {
        global $DB;
        $userid = $event->userid;
        $streamid = $event->objectid;
       
        #$contextid = $event->context;
        $contextid = $event->other["rootid"]  ;
        $courseid = $event->courseid;

        $timecreated = $event->timecreated;
        
        return stream_set_points_for_user($contextid, $userid, true);
      #  return;
      #  \stream_award_points_for_user($contextid, $userid);
        // Do something – for example, log or update something
     #  var_dump("local_nmstream received comment_deleted event for user $userid and stream $streamid in Context (cmid) $contextid in course $courseid at  $timecreated", DEBUG_DEVELOPER);
      # exit;
    }
}
