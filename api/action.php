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

  if(isset($_SESSION['game-data'])) {
    // We store the game data in a properly named GAME_DATA variable.
    $GAME_DATA = json_decode($_SESSION['game-data'], true);

    // Handles, as creatively described, the action.
    $actionHandler = new ActionHandler($GAME_DATA, $_GET['string']);
    $GAME_DATA = $actionHandler->handleAction();
  } else {
    // This should be where the game data is first compiled and stored (as JSON)
    $GAME_DATA = array(
      'user-stats' => array(
        'hp' => 420,
        'mp' => 69
      ),
      'location' => 'Forest of Grimdor',
      'current-event' => 'none',
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

    $_SESSION['game-data'] = json_encode($GAME_DATA);
  }

  // This is some return data we need on every request, so I just tack it on
  // at the end.
  $RETURN['session']      = $GAME_DATA['session'];
  $RETURN['stats']        = $GAME_DATA['user-stats'];
  $RETURN['location']     = $GAME_DATA['location'];
  $RETURN['currentEvent'] = $GAME_DATA['current-event'];
  $RETURN['prompt']       = $GAME_DATA['prompt'];
  $RETURN['choices']      = $GAME_DATA['choices'];
  $RETURN['inventory']    = $GAME_DATA['inventory'];
  $RETURN['turnCount']    = $GAME_DATA['turn-count'];

  header('Content-Type: application/json');
  echo json_encode($RETURN);
?>
