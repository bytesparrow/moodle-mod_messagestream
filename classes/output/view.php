<?php

namespace mod_messagestream\output;

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
    $data = new stdClass();
    $data->name = format_string($this->messagestream->name);
    $data->description = format_text($this->messagestream->intro, $this->messagestream->introformat, ['context' => $this->context]);
    $data->points = $this->messagestream->points;
    $privacyactive = (bool) $this->messagestream->privacyactive;
    $enableai = (bool) $this->messagestream->enableai;
    $aidefaulton = (bool) $this->messagestream->aidefaulton;
    // Use StreamService to get context and render the stream
    $service = new \local_nmstream\StreamService();
    $currenctcontext = $service->getStreamRootContext();
    if ($currenctcontext === null) {
      return "";
    }

    $streamoptions = array(
      'enableprivacy' => $privacyactive,
      'enableai' => $enableai,
      'default_ai' => $aidefaulton && $enableai
    );
    $streamoptions["promptOverride"] = "{{ DefaultSystemPrompt }}" . self::$refinement_intro . (str_replace('\'', '"', htmlspecialchars_decode($this->messagestream->promptrefinement)));
    $data->messagestreamhtml = $service->renderStream($currenctcontext, $streamoptions);


    $data->getpointslabel = get_string('getpoints', 'mod_messagestream');
    return $data;
  }

}
