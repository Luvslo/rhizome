<?php
  session_start();

  /**
   * This is the base endpoint where game action commands should be sent and
   * processed.
   */

  // Just initializing some high-level variables. They can't be globals because
  // we need to be able to alter them.
  $GAME_DATA = array();
  $RETURN    = array();

  if(isset($_SESSION['game-data'])) {
    // We store the game data in a properly named GAME_DATA variable.
    $GAME_DATA = json_decode($_SESSION['game-data']);
  } else {
    // This should be where the game data is first compiled and stored (as JSON)
  }

  // This is some return data we need on every request, so I just tack it on
  // at the end.
  $RETURN['stats']        = $GAME_DATA['user-stats'];
  $RETURN['location']     = $GAME_DATA['location'];
  $RETURN['currentEvent'] = $GAME_DATA['current-event'];
  $RETURN['prompt']       = $GAME_DATA['prompt'];
  $RETURN['choices']      = $GAME_DATA['choices'];
  $RETURN['inventory']    = $GAME_DATA['inventory'];

  header('Content-Type: application/json');
  echo json_encode($RETURN);
?>
