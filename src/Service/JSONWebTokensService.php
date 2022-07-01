<?php 

    namespace App\Service;

    use Exception;
    use DateTimeImmutable;

    class JSONWebTokensService
    {        
        /**
         * This method is used to generate the 'JSON Web Tokens'
         *
         * @param  array $header
         * @param  array $playload
         * @param  string $secret
         * @param  int $validity
         * @return string
         */

        public function generate(array $header, array $payload, string $secret, int $validity = 10800): array
        {
            if($validity > 0){
                $now = new DateTimeImmutable();
                $exp = $now->getTimestamp() + $validity;
        
                $payload['iat'] = $now->getTimestamp();
                $payload['exp'] = $exp;
            }
    
            $base64Header = base64_encode(json_encode($header));
            $base64Payload = base64_encode(json_encode($payload));
    
            $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
            $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);
    
            $secret = base64_encode($secret);
            $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
            $base64Signature = base64_encode($signature);
            $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);
    
            return [
                'base64Header' => $base64Header,
                'base64Payload' => $base64Payload,
                'base64Signature' => $base64Signature
            ];
        }
        
        /**
         * We check that the token is valid
         *
         * @param  string $token
         * @return bool
         */
        public function isValid(string $token): bool
        {
            return preg_match(
                '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
                $token
            ) === 1;
        }
        
        /**
         * Here we recover the payload
         *
         * @param  string $token
         * @return array
         */
        public function getPayload(string $token): array
        {
            $array = explode('.', $token);
            $payload = json_decode(base64_decode($array[1]), true);

            return $payload;
        }
        
        /**
         * This method retrieves the header
         *
         * @param  mixed $token
         * @return array
         */
        public function getHeader(string $token): array
        {
            $array = explode('.', $token);
            $header = json_decode(base64_decode($array[0]), true);
            return $header;
        }
        
        /**
         * This method ensures that the token has not expired
         *
         * @param  string $token
         * @return bool
         */
        public function isExpired(string $token): bool
        {
            $payload = $this->getPayload($token);
            $now = new DateTimeImmutable();

            return $payload['exp'] < $now->getTimestamp();
        }
        
        /**
         * This method verifies the signature of the token
         *
         * @param  string $token
         * @param  string $secret
         * @return bool
         */
        public function check(string $token, string $secret) : bool
        {
            $header = $this->getHeader($token);
            $payload = $this->getPayload($token);
            $verifToken = $this->generate($header, $payload, $secret, 0);
            $verifToken = implode('.', $verifToken);

            return $token === $verifToken;
        }
        
    }