<?php
  /**
   * This is the base class for all Entities. Essentially, an Entity is any
   * interactive object that can be fought in battle (currently, only Users,
   * NPCs, and Mobs).
   */
  class Entity {
    public function __construct($info = array()) {
      $this->level = $info['level'];
      $this->name  = $info['name'];

      $this->buildStats($info);
    }

    public function grabData() {
      return array(
        'maxHP' => $this->maxHP,
        'maxMP' => $this->maxMP,
        'hp' => $this->HP,
        'mp' => $this->MP,
        'strength' => $this->str,
        'defense'  => $this->def,
        'speed'    => $this->spd,
        'intelligence' => $this->int,
        'level' => $this->level,
        'name'  => $this->name
      );
    }

    public function fistFight($enemy) {
      $dmg = $enemy->def - $this->str + rand(0,ceil(sqrt($this->str)));
      if($dmg > 0)
        return $dmg;
      else
        return 1;
    }

    public function removeHP($hp) {
      $this->hp -= $hp;
    }

    public function canDodge($enemy) {
      // No time to get this right!!! @TODO: FIX.
      // $rate = ceil(75 * ($this->spd / $enemy->spd)) + 10;
      // echo $rate;
      // if($rate > 90)
      //   $rate = 90;

      if(rand(0,100) <= 75)
        return true;
      else
        return false;
    }

    private function buildStats($info) {
      if(!isset($info['maxHP'])) {
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

        $this->str = floor((1 + $diff + $str_modifier)) * $this->level + rand(0,1);
        $this->def = floor((1 + $diff + $def_modifier)) * $this->level + rand(0,1);
        $this->spd = floor((1 + $diff + $spd_modifier)) * $this->level + rand(0,1);
        $this->int = floor((1 + $diff + $int_modifier)) * $this->level + rand(0,1);

        $this->maxHP = floor((.5 * $this->str + .75 * $this->def) * 2) + 5;
        $this->maxMP = floor($this->int * 2) + 3;

        $this->HP = $this->maxHP;
        $this->MP = $this->maxMP;
      } else {
        $this->maxHP = $info['maxHP'];
        $this->maxMP = $info['maxMP'];
        $this->str = $info['strength'];
        $this->int = $info['intelligence'];
        $this->spd = $info['speed'];
        $this->def = $info['defense'];
        $this->HP  = $info['hp'];
        $this->MP  = $info['mp'];
      }
    }
  }
?>
