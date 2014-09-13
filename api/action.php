<?php
  session_start();
  ini_set('display_errors',1);
  error_reporting(E_ALL);

  /**
   * This is the base endpoint where game action commands should be sent and
   * processed.
   */

  // Just initializing some high-level variables. They can't be globals because
  // we need to be able to alter them.
  $GAME_DATA = array();
  $RETURN    = array();

  // Base action handler. Grants access to ActionHandler class.
  require_once(getcwd().'/classes/action-handler.php');
  require_once(getcwd().'/classes/loader.php');

  if(isset($_SESSION['game-data'])) {
    error_log("session is already set");
    // We store the game data in a properly named GAME_DATA variable.
    $GAME_DATA = json_decode($_SESSION['game-data'], true);
    error_log("getting game data from session: " . $GAME_DATA['current-event']);


    // Handles, as creatively described, the action.
    $actionHandler = new ActionHandler($GAME_DATA, $_GET['string']);
    $GAME_DATA = $actionHandler->handleAction();
    error_log("getting game data from action handler: " . $GAME_DATA['current-event']);
  } else {
    error_log("session is getting set");
    // This should be where the game data is first compiled and stored (as JSON)
    $GAME_DATA = array(
      'session' => 'XXX',
      'user-stats' => array(
        'level' => 2,
        'name'  => 'Mike'
      ),
      'enemy-data' => array(
        'level' => 2,
        'name'  => 'Grimlock the Super Mage'
      ),
      'location' => 'Forest of Grimdor',
      'current-event' => 'battle',
      'prompt' => 'You seem to have gone nowhere so far. You should probably
      decide where to go. Where to, buck-o?',
      'choices' => array(
        'HappyTime FunTown',
        'Graveyard of Ultra Death',
        'The Plains of Never-Ending Pain Rain'
      ),
      'inventory' => array(),
      'turn-count' => 0
    );

    // Handles, as creatively described, the action.
    $actionHandler = new ActionHandler($GAME_DATA, $_GET['string']);
    $GAME_DATA = $actionHandler->handleAction();
  }
  $_SESSION['game-data'] = json_encode($GAME_DATA);
  // This is some return data we need on every request, so I just tack it on
  // at the end.
  $RETURN['session']      = $GAME_DATA['session'];
  $RETURN['stats']        = $GAME_DATA['user-stats'];
  $RETURN['location']     = $GAME_DATA['location'];
  $RETURN['currentEvent'] = $GAME_DATA['current-event'];
  $RETURN['prompt']       = $GAME_DATA['prompt'];
  $RETURN['choices']      = $GAME_DATA['choices'];
  $RETURN['inventory']    = $GAME_DATA['inventory'];
  $RETURN['turnCount']    = $GAME_DATA['turn-count']++;

  header('Content-Type: application/json');
  error_log("returning event: " . $GAME_DATA['current-event']);
  echo json_encode($RETURN);
?>
