<?php

	class User {
		
		protected $id;
		protected $dataStore;
		
		public function __construct( $id, $data_store ) {
			$this->id = $id;
			$this->dataStore = $data_store;
		}
		
		public function addScore( $score ) {
			$this->saveLastPlayed();
			$query = 'INSERT INTO userScores ( userFacebookID, date, score) VALUES ( ?, ?, ? )';
			$this->dataStore->queryPrepared( $query, array(
				$this->id,
				date( 'Y-m-d' ),
				$score
			) );
		}
		
		public function saveLastPlayed() {
			$query = 'UPDATE users SET lastPlayed = ? WHERE facebookID = ? LIMIT 1';
			$this->dataStore->queryPrepared( $query, array(
				date( 'Y-m-d' ),
				$this->id
			) );
		}
	}
?>
