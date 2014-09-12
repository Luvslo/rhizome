<?php
  class LootHandler {
    public function __construct($gameData = array(), $string = '') {
      $this->gameData     = $gameData;
    }

    public function makeLoot() {
      $json = new Loader("words");
      $words = $json->fetchAll();
      $copy = array();
      $copy['adjective'] = $this->randArray($words['adjectives']);
      $copy['noun'] = $this->randArray($words['nouns']);
      $copy['pronoun'] = $this->randArray($words['pronouns']);
      $copy['adverb'] = $this->randArray($words['adverbs']);
      $copy['verb'] = $this->randArray($words['verbs']);
      $copy['wisdom'] = $this->randArray($words['wisdom']);
      
      $this->gameData['prompt'] = "You notice a {$copy['adjective']} {$copy['noun']}.  Once {$copy['pronoun']} see you, {$copy['pronoun']} {$copy['adverb']} {$copy['verb']}, but not before saying &#39;{$copy['wisdom']}&#39;";
      $this->gameData['choices'] = array();
      array_push($this->gameData['choices'], "To continue adventuring");
      return $this->gameData;
    }
    
    private function randArray($array){
      return $array[rand(0, (count($array)-1))];
    }
  }
?>
