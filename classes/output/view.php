<?php

namespace mod_stream\output;

use renderable;
use templatable;
use renderer_base;
use stdClass;

class view implements renderable, templatable {

  /** @var \stdClass The stream instance */
  protected $stream;

  /** @var \context_module */
  protected $context;

  public function __construct(\stdClass $stream, \context_module $context) {
    $this->stream = $stream;
    $this->context = $context;
  }

  public function export_for_template(renderer_base $output): stdClass {
    $data = new stdClass();
    $data->name = format_string($this->stream->name);
    $data->description = format_text($this->stream->intro, $this->stream->introformat, ['context' => $this->context]);
    $data->points = $this->stream->points;
    $data->formaction = new \moodle_url('/mod/stream/view.php', ['id' => $this->stream->coursemodule]);
    $data->coursemodule = $this->stream->coursemodule;
    $data->sesskey = sesskey();
    
    // Use StreamService to get context and render the stream
       $service = new \local_nmstream\StreamService();
       $currenctcontext = $service->getStreamRootContext();
       if ($currenctcontext === null) {
           return "";
       }
    $data->streamhtml =  $service->renderStream($currenctcontext);


    $data->getpointslabel = get_string('getpoints', 'mod_stream');
    return $data;
  }

}
