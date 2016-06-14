<?php

	class FacebookSignedRequest {
		
		protected $isValid;
		protected $data;
		
		public function	__construct ( $signed_request ) {
			$this->parseSignedRequest( $signed_request );
		}
		
		protected function parseSignedRequest( $signed_request ) {
			list( $signature, $payload ) = explode( '.', $signed_request, 2 );
			$signature = $this->base64URLDecode( $signature );
			if ( $this->signatureMatchesPayload( $signature, $payload ) ) {
				$this->isValid = true;
			} else {
				$this->isValid = false;
			}
			$payload = $this->base64URLDecode( $payload );
			$this->data = json_decode( $payload, true );
		}
		
		protected function signatureMatchesPayload( $signature, $payload ) {
			$expected_signature = hash_hmac( 'sha256', $payload, FB_APP_SECRET, true );
  			return ( $signature === $expected_signature );
		}
		
		protected function base64URLDecode( $input ) {
			return base64_decode( strtr( $input, '-_', '+/' ) );
		}
		
		public function isValid() {
			return $this->isValid;
		}
		
		public function isSignedIn() {
			return ( isset( $this->data['user_id'] ) && isset( $this->data['oauth_token'] ) );
		}
		
		public function userID() {
			return $this->data['user_id'];
		}
	}
?>
