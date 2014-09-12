<?php
  include 'entities/entity.php';

  class BattleHandler {
    public function __construct($gameData = array(), $string = '') {
      $this->gameData     = $gameData;
      $this->actionString = $string;
    }

    public function battle() {
      $this->createBattleEntities();
      $this->rollNextRound();

      $this->endRound();

      return $this->gameData;
    }

    private function endRound() {
      if($this->user->HP > 0 && $this->enemy->HP > 0) {
        $this->gameData['choices'] = array(
          "Hit ".$this->enemy->name." in a body part with your space fists.",
          "Kick ".$this->enemy->name." like you're in a sponchy action movie. (2 MP)",
          "Run away from this slippery pickle."
        );
      } else {
        if($this->user->HP <= 0) {
          $this->gameData['currentEvent'] = 'dead';
          $this->gameData['prompt'] .= " And then you lost. You're dead now. In space.";
        } else {
          $this->gameData['currentEvent'] = 'none';
          $this->gameData['prompt'] .= " And then you won. Whaddup wit it?";
        }
      }

      $this->gameData['user-stats']  = $this->user->grabData();
      $this->gameData['enemy-data'] = $this->enemy->grabData();
    }

    public function makeBattle($id) {
      $this->gameData['currentEvent'] = 'battle';
      $this->gameData['enemy-data']   = new Loader('mobs');
      $this->gameData['enemy-data']   = $this->gameData['enemy-data']->fetchOne();

      $this->createBattleEntities();

      $this->gameData['prompt']  = "You've entered fierce combat with a " . $this->gameData['enemy-data']->name . "!";

      $this->rollFirstRound();
    }

    public function createBattleEntities() {
      $this->user  = new Entity($this->gameData['user-stats']);
      $this->enemy = new Entity($this->gameData['enemy-data']);
    }

    private function rollFirstRound() {
      if(!$this->user->canDodge($this->enemy)) {
        $this->gameData['prompt'] += $this->enemy->fistFight($this->user);
      }
    }

    private function rollNextRound() {
      $this->gameData['prompt'] = "You're in battle with a " . $this->enemy->name. '!';

      if($this->actionString == '1') {
        if($this->enemy->canDodge($this->user)) {
          $this->gameData['prompt'] .= ' You try to punch '.$this->enemy->name.', but they are much too quick for your sloth hands.';
        } else {
          $damage = $this->user->fistFight($this->enemy);
          $this->enemy->removeHP($damage);

          $this->gameData['prompt'] .= ' You punch '.$this->enemy->name.' in the general knee area, and cause '.$damage.' damage.';
        }
      }
    }
  }
?>
