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
