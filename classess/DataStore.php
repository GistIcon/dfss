<?php

	class DataStore extends PDO {
		
		public function __construct( $connection_settings, $username, $password, $driver_options = array( PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) ) {
			parent::__construct( $connection_settings, $username, $password, $driver_options );
		}
		
		public function limitQuery( $query, $record_count, $start_record = null ) {
			return $query . ' LIMIT ' . ( ( $start_record ) ? $start_record . ', ' : '' ) . $record_count;
		}
		
		// returns PDOStatement
		public function queryPrepared( $query, $variables = array() ) {
			if ( is_array( $variables ) === false ) $variables = ( array )$variables; // allows for $variables to be an array or an object
			$stmt = $this->prepare( $query );
			try {
				$stmt->execute( $variables );
			} catch ( Exception $exception ) {
				if ($this->inTransaction() === true) $this->rollBack();
				$variables = array_map( function( $key, $value ) {
					return $key . ' => "' . $value . '"';
				}, array_keys( $variables ), $variables );
				throw new PDOException( $exception->getMessage() . ".\nQuery: \"" . $query . "\"\nVariables: " . implode( ', ', $variables ), 0, $exception );
			}
			return $stmt;
		}
		
		public function queryRowPrepared( $query, $variables = array() ) {
			$stmt = $this->queryPrepared( $this->limitQuery( $query, 1 ), $variables );
			return $stmt->fetch();
		}
		
		public function queryColumnPrepared( $query, $variables = array(), $column = 0 ) {
			$stmt = $this->queryPrepared( $this->limitQuery( $query, 1 ), $variables );
			return $stmt->fetchColumn( $column );
		}
	}
?>
