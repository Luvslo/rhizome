<?php
  class Loader {
    public function __construct($type) {
      $this->file = getcwd().'/../content/Base/'.$type.'.json';
    }

    public function fetchOne($id) {
      $this->decode();
      if(isset($this->data[$id]))
        return $this->data[$id];
      else
        return false;
    }

    public function fetchAll() {
      $this->decode();
      return $this->data;
    }

    private function decode() {
      $json  = file_get_contents($this->file);
      $this->data = json_decode($json, true);
    }
  }
?>
