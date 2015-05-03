<?php
/*
	Copyright (c) 2015 Zhaofeng Li
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	SOFTWARE.
*/

// Let's not use Composer's autoloader for now...
require_once __DIR__ . "/../src/Feng/Tictactoe/Tictactoe.php";

use Feng\Tictactoe\Tictactoe;

function gets() {
	return trim( fgets( STDIN ) );
}

$t = new Tictactoe();
echo "Tic-tac-toe by Zhaofeng Li\nYou are X, and the bot is O.\nEnter a zero-based coordinate to make a move. For example, \"0,1\" (without the quotes).\n";

while ( !$t->isEnded() ) { // Let's loop 'til the game is over
	echo "=============================\n";
	echo "The current board:\n";
	echo " X >  0  |  1  |  2  \n";
	echo "---------------------\n";
	for ( $y = 0; $y < 3; $y++ ) {
		echo " $y ";
		for ( $x = 0; $x < 3; $x++ ) {
			switch ( $t->getTile( $x, $y ) ) {
				case $t::PLAYER_A: // human (X)
					$symbol = "X";
					break;
				case $t::PLAYER_B: // AI (O)
					$symbol = "O";
					break;
				default: // unoccupied
					$symbol = " ";
					break;
			}
			echo "|  $symbol  ";
		}
		echo "\n---------------------\n";
	}
	$x = -1; $y = -1;
	while ( !$t->isAvailable( $x, $y ) ) {
		echo "Enter a coordinate: ";
		$in = explode( ',', gets() );
		$x = (int)$in[0];
		$y = (int)$in[1];
	}
	echo "Okay, you chose ($x,$y).\n";
	$t->playTile( $t::PLAYER_A, $x, $y );

	echo "I'm thinking...\n";
	$ai = $t->getBestMove( $t::PLAYER_B, true );
	if ( $ai === false ) {
		echo "Um, that's a tricky one... I'll pass. :/\n";
	} else {
		$x = $ai[0];
		$y = $ai[1];
		echo "I'll go with ($x,$y).\n";
		$t->playTile( $t::PLAYER_B, $x, $y );
	}
}

echo "Game over! ";
switch ( $t->getWinner() ) {
	case $t::PLAYER_A:
		echo "You won!\n";
		break;
	case $t::PLAYER_B:
		echo "I won!\n";
		break;
	default:
		echo "It's a tie!\n";
		break;
}
