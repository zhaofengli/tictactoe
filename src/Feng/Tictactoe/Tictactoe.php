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

namespace Feng\Tictactoe;

class Tictactoe {
	const PLAYER_A = 1;
	const PLAYER_B = 2;

	private $board = array(
		array( 0, 0, 0 ),
		array( 0, 0, 0 ),
		array( 0, 0, 0 ),
	); //the board
	
	private static $rows = array(
		array( array( 0, 0 ), array( 1, 0 ), array( 2, 0 ) ),
		array( array( 0, 1 ), array( 1, 1 ), array( 2, 1 ) ),
		array( array( 0, 2 ), array( 1, 2 ), array( 2, 2 ) ),
		
		array( array( 0, 0 ), array( 0, 1 ), array( 0, 2 ) ),
		array( array( 1, 0 ), array( 1, 1 ), array( 1, 2 ) ),
		array( array( 2, 0 ), array( 2, 1 ), array( 2, 2 ) ),
		
		array( array( 0, 0 ), array( 1, 1 ), array( 2, 2 ) ),
		array( array( 2, 0 ), array( 1, 1 ), array( 0, 2 ) ),
		);
		
	function __construct( $saved = null ) {
		if ( $saved ) { //attempt to restore a game
			if ( !$this->importBoard( $saved ) ) throw new Exception('Unable to restore the game.');
		}
	}
	
	public function __toString() {
		return serialize( $this->board );
	}
	
	/**
	 * Check if a board is valid
	 * @param array $board The board array
	 * @return bool true if valid, and vice versa
	 * 
	 */
	public function checkBoard( $board = null ) {
		if ( !$board ) $board = $this->board;
		if ( count( $board ) != 3) return false;
		foreach ( $board as $col ) {
			if ( count( $col ) != 3 ) return false;
			foreach ($col as $tile) {
				if ( !in_array( $tile, array( 0, 1, 2 ) ) ) return false;
			}
		}
		return true;
	}
	
	/**
	 * Check if a tile is valid
	 * @param int $posx The X axis of the tile
	 * @param int $posy The Y axis of the tile
	 * @return bool true if valid, and vice versa
	 * 
	 */
	public function checkTile( $posx, $posy ) {
		if ( !in_array( $posx, array( 0, 1, 2 ) ) || !in_array( $posy, array( 0, 1, 2 ) ) ) return false;
		else return true;
	}
	
	/**
	 * Check if a player is valid
	 * @param int $player The integer representing a player
	 * @return bool true if valid, and vice versa
	 * 
	 */
	public function checkPlayer( $player ) {
		if ( in_array( $player, array( self::PLAYER_A, self::PLAYER_B ) ) ) return true;
		else return false;
	}
	
	/**
	 * Import an existing board
	 * @param array|string $board The board array or serialized string
	 * @return bool true if succeed, and vice versa
	 * 
	 */
	public function importBoard( $board ) {
		if ( is_string( $board ) ) $board = unserialize( $board );
		if ( !is_array( $board ) ) return false;
		if ( $this->checkBoard( $board ) ) {
			$this->board = $board;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Export the current board
	 * @return array
	 * 
	 */
	public function exportBoard() {
		return $this->board;
	}
	
	/**
	 * Check if a tile is avaliable
	 * @param int $posx The X axis of the tile
	 * @param int $posy The Y axis of the tile
	 * @return bool true if valid, and vice versa
	 * 
	 */
	public function isAvailable( $posx, $posy ) {
		$posx = (int)$posx;
		$posy = (int)$posy;
		if ( !$this->checkTile( $posx, $posy ) ) return false;
		else {
			if ( !$this->board[$posx][$posy] ) return true;
			else return false;
		}
	}
	
	/**
	 * Get the winner of the game, if there is one
	 * @return int|bool self::PLAYER_A or self::PLAYER_B if found, false if there's no winner
	 * 
	 */
	public function getWinner() {
		foreach ( $this::$rows as $row ) { //loop through the rows
			$cpa = 0; $cpb = 0;
			foreach ( $row as $tileaxis ) {
				$tile = $this->board[$tileaxis[0]][$tileaxis[1]];
				if ( $tile == self::PLAYER_A ) $cpa++;
				elseif ( $tile == self::PLAYER_B ) $cpb++;
			}
			if ( $cpa == 3 ) return self::PLAYER_A;
			elseif ( $cpb == 3 ) return self::PLAYER_B;
		}
		return false;
	}

	/**
	 * Get who is occupying a tile
	 * @param int $posx
	 * @param int $posy
	 * @return int|bool self::PLAYER_A, self::PLAYER_B or false if vacant
	 *
	 */
	public function getTile( $posx, $posy ) {
		if ( !$this->board[$posx][$posy] ) {
			return false;
		} else {
			return $this->board[$posx][$posy];
		}
	}
	
	/**
	 * Check if the game has ended
	 * @return bool true if ended, and vice versa
	 * 
	 */
	public function isEnded() {
		if ( $this->getWinner() ) return true; //there's a winner
		$ctaken = 0;
		foreach ( $this->board as $col ) {
			foreach ( $col as $tile ) {
				if ( $tile ) $ctaken++;
			}
		}
		if ( $ctaken == 9 ) return true;
		else return false;
	}
	
	/**
	 * Get the rating of a move
	 * @param int $player The player
	 * @param int $posx The X axis of the tile
	 * @param int $posy The Y axis of the tile
	 * @return int The overall rating (the higher the better)
	 * 
	 */
	public function rateMove( $player, $posx, $posy ) {
		if ( !$this->isAvailable( $posx, $posy ) ) return -1; // invalid move
		elseif ( !$this->checkPlayer( $player ) ) return -1; // no such player
		$total = 0; // overall rating
		foreach ( $this::$rows as $row ) { // loop through the rows
			if ( !in_array( array( $posx, $posy ), $row ) ) continue; // skip unrelated rows
			$cop = 0; $cme = 0; // count of my / opponent's tiles in the row
			foreach ( $row as $tileaxis ) {
				$tilex = $tileaxis[0]; $tiley = $tileaxis[1];
				$tile = $this->board[$tilex][$tiley];
				if ( $tile == $player ) $cme++;
				elseif ( $tile && $tile != $player ) $cop++;
			}
			if ( $cme == 2 && !$cop ) $total += 10; // immediate win (+10)
			elseif ( $cme == 1 && !$cop ) $total += 2; // makes 2 in a row (+2)
			elseif ( !$cme && !$cop ) $total += 1; // can start a new row (+1)
			// echo "->tile $posx $posy has a rating of $total now" . PHP_EOL;
		}
		return $total;
	}
	
	/**
	 * Get the opponent of a player
	 * @param int $player The player
	 * @return int|bool The integer representing the opponent if found
	 * 
	 */
	public function getOpponent( $player ) {
		if ( !$this->checkPlayer( $player ) ) {
			return false;
		} else {
			if ( $player == self::PLAYER_A ) return self::PLAYER_B;
			else return self::PLAYER_A;
		}
	}
	
	/**
	 * Get the best move of a player
	 * @param int $player The player
	 * @param bool $stop_opponent Whether to try to stop the opponent from winning
	 * @param int $bestrating
	 * @return array|bool The position of the best tile
	 * 
	 */
	public function getBestMove( $player, $stop_opponent = true, &$bestrating = 0 ) {
		if ( !$this->checkPlayer( $player ) ) return false; // invalid player
		elseif ( $this->isEnded() ) return false; // the game has already ended
		
		if ( $stop_opponent ) { // try to stop the opponent from winning
			$orating = 0;
			$obestpos = $this->getBestMove( $this->getOpponent( $player ), false, $orating ); // get the opponent's best move
		}
		
		$arrbest = array(); // the best tile(s)
		$bestrating = 0; // the rating of the best tile(s)
		foreach ( $this->board as $posx => $col ) {
			foreach ( $col as $posy => $tile ) {
				$rating = $this->rateMove( $player, $posx, $posy );
				if ( $rating != -1 ) { // tile avaliable
					// echo "tile $posx $posy has a rating of $rating" . PHP_EOL;
					if ( $rating > $bestrating ) { // we have a higher one!
						$arrbest = array(
							array( $posx, $posy ),
						);
						$bestrating = $rating;
					} elseif ( $rating == $bestrating ) { // not bad
						$arrbest[] = array( $posx, $posy );
					}
				}
			}
		}
		if ( $stop_opponent && $orating >= 10 && $bestrating < 10 ) { // stop the opponent from winning ;D
			return $obestpos;
		} else {
			if ( count( $arrbest ) >= 0 ) {
				$choice = array_rand( $arrbest );
				return $arrbest[$choice];
			} else return false;
		}
	}
	
	/**
	 * Make a move
	 * @param int $player The player
	 * @param int $posx The X axis of the tile
	 * @param int $posy The Y axis of the tile
	 * @return bool true if succeed, and vice versa
	 * 
	 */
	public function playTile( $player, $posx, $posy ) {
		$posx = (int)$posx; $posy = (int)$posy;
		if ( !$this->checkPlayer( $player ) ) return false; // bad player
		elseif ( !$this->isAvailable( $posx, $posy ) ) return false; // bad tile
		elseif ( $this->isEnded() ) return false; //the game is already over
		
		$this->board[$posx][$posy] = $player;
		return true;
	}
}
