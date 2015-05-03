# tictactoe
A simple tic-tac-toe model and AI written in PHP. This was initially made as a school project in 2013.

## Installing
You can install the library via Composer, or by downloading it directly on GitHub.

### Composer
Set up a Composer project, then run the following command:
```
php composer.phar require zhaofengli/tictactoe
```

### ZIP download
Download [this file](https://github.com/zhaofengli/tictactoe/archive/master.zip) and extract it into your project directory.

## Demo
An interactive CLI program is included in the `examples` folder.

## Basic usage
To create a game instance, use:
```php
<?php
require_once __DIR__ . '/vendor/autoload.php'; // Composer
// OR
require_once __DIR__ . '/tictactoe/src/Feng/Tictactoe/Tictactoe.php'; // ZIP download

use Feng\Tictactoe\Tictactoe;

$game = new Tictactoe(); // Create a new game
```

Now, you have created a new game with a empty board. You can export the board by using the `exportBoard()` method:
```php
print_r( $game->exportBoard() );
```

Place tiles with:
```php
$game->playTile( $game::PLAYER_A, 1, 2 );
$game->playTile( $game::PLAYER_B, 0, 1 );
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
	if ( $winner == $game::PLAYER_A ) {
		echo "Player A wins!";
	} elseif ( $winner == $game::PLAYER_B ) {
		echo "Player B wins!";
	} else {
		echo "It's a tie.";
	}
}
```

## AI
To get the best move of a player, use the `getBestMove()` method:
```php
if ( $bestmove = getBestMove( $game::PLAYER_A ) ) { // best move available
	$game->playTile( $game::PLAYER_A, $bestmove[0], $bestmove[1] );
}
```
Please check out `getBestMove()` and `rateMove()` methods in the source code to see how it works.
The AI is flawed, and it's possible for it to lose a game.

## Licensing
This program is licensed under the MIT License. See `LICENSE` for details.
