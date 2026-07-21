<?php

namespace mod_messagestream\output;

use local_nmstream\local\persona\PersonaDisplayResolver;
use renderable;
use templatable;
use renderer_base;
use stdClass;

class view implements renderable, templatable {

  static $refinement_intro = "\n\n### SONSTIGE FOKUSSIERUNG / VERFEINERUNG \n\n";

  /** @var \stdClass The stream instance */
  protected $messagestream;

  /** @var \context_module */
  protected $context;

  public function __construct(\stdClass $messagestream, \context_module $context) {
    $this->messagestream = $messagestream;
    $this->context = $context;
  }

  public function export_for_template(renderer_base $output): stdClass {

    $viewmode = optional_param('view', 'stream', PARAM_ALPHA);

    $data = new stdClass();
    $data->name = format_string($this->messagestream->name);
    //fixing error "Before calling format_text(), the content must be processed with file_rewrite_pluginfile_urls() "
    $description = file_rewrite_pluginfile_urls($this->messagestream->intro, 'pluginfile.php',  $this->context->id, 'mod_messagestream', 'activity', $this->messagestream->id);
    $data->description = format_text($description, $this->messagestream->introformat, ['context' => $this->context]);
    $data->points = $this->messagestream->points;
    $privacyactive = (bool) $this->messagestream->privacyactive;
    $enableai = (bool) $this->messagestream->enableai;
    $aidefaulton = (bool) $this->messagestream->aidefaulton;
    // Use StreamService to get context and render the stream
    $service = new \local_nmstream\StreamService();
    $currenctcontext = $service->getStreamRootContext();
    $moodlecontext =  \context_module::instance($this->messagestream->coursemodule);
    if ($currenctcontext === null) {
      return "";
    }

    $streamoptions = array(
      'enableprivacy' => $privacyactive,
      'enableai' => $enableai,
      'default_ai' => $aidefaulton && $enableai
    );
    $streamoptions["promptOverride"] = (!empty($this->messagestream->promptrefinement)
      ? (self::$refinement_intro . (str_replace('\'', '"', htmlspecialchars_decode($this->messagestream->promptrefinement))))
      : '');
    $coach = PersonaDisplayResolver::resolve_for_messagestream_instance((int) $this->messagestream->id);
    $streamoptions['aiCoach'] = [
        'displayName' => $coach['display_name'],
        'avatarUrl' => $coach['avatar_url'],
        'avatarEmoji' => $coach['avatar_emoji'],
    ];
    
    

    //security
    $caneditcoach = has_capability('moodle/course:manageactivities', $moodlecontext);
    
    if ($viewmode === 'coach') {
      if (!$caneditcoach) {

        $data->mainhtml = \core\notification::error(get_string('nopermissions', 'error'));
      }
      else {
        //todo this is ugly AF
        ob_start();
        $cmid = $this->messagestream->coursemodule;
        $messagestream = $this->messagestream;
        require dirname(dirname(__DIR__)) . '/views/coach_settings.php';
        $html = ob_get_clean();
        $data->mainhtml = $html;
      }
    }
    else {
      $data->mainhtml = $service->renderStream($currenctcontext, $streamoptions);
    }

    $controller = new \local_nmstream\StreamController();
    $counts = $controller->getTotalCounts($this->context->instanceid, 'messagestream', true);

    if ($enableai) {
      $numcomments = $counts['comments'] - $counts['ai_comments'];
      $data->string_stats = get_string('statinfo_with_ai', 'mod_messagestream', ['count_posts' => $counts['posts'], 'count_comments' => $numcomments,  'count_ai_comments' => $counts['ai_comments']]); 
    }
    else {
         $data->string_stats = get_string('statinfo', 'mod_messagestream', ['count_comments' =>  $counts['comments'], 'count_posts' => $counts['posts']]);
    }


    $data->getpointslabel = get_string('getpoints', 'mod_messagestream');
    return $data;
  }

}
