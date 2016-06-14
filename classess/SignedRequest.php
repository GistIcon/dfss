<?php

	class SignedRequest {
		
		protected $isValid;
		
		public function	__construct ( $signature, $payload ) {
			$signature = $this->base64URLDecode( $signature );
			$this->isValid = $this->signatureMatchesPayload( $signature, $payload );
		}
		
		protected function signatureMatchesPayload( $signature, $payload ) {
			$expected_signature = hash_hmac( 'sha256', $payload, APP_SECRET, true );
  			return ( $signature === $expected_signature );
		}
		
		protected function base64URLDecode( $input ) {
			return base64_decode( strtr( $input, '-_', '+/' ) );
		}
		
		public function isValid() {
			return $this->isValid;
		}
	}
?>
