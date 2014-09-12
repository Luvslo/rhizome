<?php
  /**
   * This is the base class for all Entities. Essentially, an Entity is any
   * interactive object that can be fought in battle (currently, only Users,
   * NPCs, and Mobs).
   */
  class Entity {
    public function __construct($info = array()) {
      $this->level = $info['level'];

      $this->buildStats();
    }

    private function buildStats() {
      if($info['stats'] === null) {
        $difficulty   = 0;

        // This is the modifier.
        $modifier = .25;

        // Stats modifiers.
        $str_modifier = 0;
        $def_modifier = 0;
        $spd_modifier = 0;
        $int_modifier = 0;

        if(isset($info['difficulty']))
          $difficulty = $info['difficulty'];

        if(isset($info['type'])) {
          switch($info['type']) {
            case 'strong':
              $str_modifier = $modifier;
              break;
            case 'smart':
              $int_modifier = $modifier;
              break;
            case 'fast':
              $spd_modifier = $modifier;
              break;
          }
        }

        $diff = $difficulty / 20;

        $this->str = floor((1 + $diff + $str_modifier)) * $level + rand(0,1);
        $this->def = floor((1 + $diff + $def_modifier)) * $level + rand(0,1);
        $this->spd = floor((1 + $diff + $spd_modifier)) * $level + rand(0,1);
        $this->int = floor((1 + $diff + $int_modifier)) * $level + rand(0,1);

        $this->maxHP = ((.5 * $this->str + .75 * $this->def) * 2) + 5;
        $this->maxMP = ($this->int * 2) + 3;

        $this->HP = $this->maxHP;
        $this->MP = $this->maxMP;
      }
    }
  }
?>
