rhizome
=======

A text based adventure game

## API
The primary endpoint for the API is at `/api/action.php`. This _currently_
accepts a url parameter called `string` that should contain the URL encoded
action sent from the client. Note that this will eventually be a POST action.

API requests return a JSON string.

All requests, regardless of game state, will return a `prompt` property that
should be printed to the screen. Often, an array of `choices` will also be
sent.

## Battle Stuff
Just some notes to remember for later.

Basic stats: Strength, Defense, Intelligence, Agility

Entities have "types" for initialization, each type correlates to a basic stat,
and stats are built with modifiers based on type.

HP = `(.5STR + .75DEF) * 2 + 5`

MP = `INT * 2 + 3`

Stats are built based on: `(1 + (DIFFICULTY / 20) + modifier) * LEVEL + RN(-1,1)`

Attack Rate:
`75 * (AGILITY / ENEMY_AGILITY) + 10`
