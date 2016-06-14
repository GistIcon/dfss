<?php

	class SampleDataGenerator {
		
		protected $dataStore;
		
		public function __construct( $data_store ) {
			$this->dataStore = $data_store;
		}
			
		public function generateOneMillionRecords() {
			$query = 'INSERT INTO users ( facebookID, lastPlayed ) VALUES ( ?, ? )';
			$userCreationStatement = $this->dataStore->prepare( $query );
			for ( $i = 0; $i < 1000; $i++ ) {
				// create random user
				$random_id = mt_rand();
				$random_last_play_time = date( 'Y-m-d', strtotime( '-' . mt_rand( 0, 30 ) . ' days' ) );
				$userCreationStatement->execute( array( $random_id, $random_last_play_time ) );
				
				$query = 'INSERT INTO userScores ( userFacebookID, date, score ) VALUES ( ?, ?, ? )';
				$scoreCreationStatement = $this->dataStore->prepare( $query );
				for ( $e = 0; $e < 999; $e++ ) {
					// create random score
					$random_score_date = date( 'Y-m-d', strtotime( '-' . mt_rand( 0, 30 ) . ' days' ) );
					$random_score = mt_rand( 0, 1000000 );
					$scoreCreationStatement->execute( array( $random_id, $random_score_date, $random_score ) );
				}
			}
		}
		
		public function clearAllData() {
			$query = 'TRUNCATE TABLE users';
			$this->dataStore->queryPrepared( $query );
			$query = 'TRUNCATE TABLE userScores';
			$this->dataStore->queryPrepared( $query );
		}
	}
?>
