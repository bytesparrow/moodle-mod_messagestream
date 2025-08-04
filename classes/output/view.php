<?php

namespace mod_messagestream\output;

use renderable;
use templatable;
use renderer_base;
use stdClass;

class view implements renderable, templatable {

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
    $data->formaction = new \moodle_url('/mod/messagestream/view.php', ['id' => $this->messagestream->coursemodule]);
    $data->coursemodule = $this->messagestream->coursemodule;
    $data->sesskey = sesskey();
    
    $enableai = (bool)$this->messagestream->enableai;

    // Use StreamService to get context and render the stream
       $service = new \local_nmstream\StreamService();
       $currenctcontext = $service->getStreamRootContext();
       if ($currenctcontext === null) {
           return "";
       }
    $data->messagestreamhtml =  $service->renderStream($currenctcontext, $enableai);


    $data->getpointslabel = get_string('getpoints', 'mod_messagestream');
    return $data;
  }

}
