# tictactoe
A simple tic-tac-toe model and AI written in PHP. This was initially made as a school project in 2013.

## Basic usage
To create a game instance, use:
```php
<?php
require_once('class.php'); // Assuming class.php is in the include paths
$game = new tictactoe(); // Create a new game
```

Now, you have created a new game with a empty board. You can export the board by using the `exportBoard()` method:
```php
print_r( $game->exportBoard() );
```

Place tiles with:
```php
$game->playTile( TICTACTOE_PLAYER_A, 1, 2 );
$game->playTile( TICTACTOE_PLAYER_B, 0, 1 );
```
Now the board is like:
```
 | | 
-----
B| |
-----
 |A|
```

To check if the game has ended, use the `isEnded()` method:
```php
if ( $game->isEnded() ) {
	$winner = $game->getWinner();
	if ( $winner == TICTACTOE_PLAYER_A ) {
		echo "Player A wins!";
	} elseif ( $winner == TICTACTOE_PLAYER_B ) {
		echo "Player B wins!";
	} else {
		echo "It's a tie.";
	}
}
```

## AI
To get the best move of a player, use the `getBestMove()` method:
```php
if ( $bestmove = getBestMove( TICTACTOE_PLAYER_A ) ) { // best move available
	$game->playTile( TICTACTOE_PLAYER_A, $bestmove[0], $bestmove[1] );
}
```
Please check out `getBestMove()` and `rateMove()` methods in the source code to see how it works.
The AI is flawed, and it's possible for it to lose a game.

## Licensing
This program is licensed under BSD 2-Clause License. See `LICENSE` for details.
