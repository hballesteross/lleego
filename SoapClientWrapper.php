<?php



    class SoapClientWrapper
    {
        private $options;
        private $client;

        public function __construct(array $options)
        {
            $this->options = $options;
            $this->client = new SoapClient(null, $this->options);
            $this->client->__setOptions($this->options);
        }

        public function authenticate(string $username, string $password)
        {
            $header = new SoapHeader($this->options['location'], 'Authenticate', array('username' => $username, 'password' => $password));
            $this->client->__setSoapHeaders($header);
        }

        public function getData()
        {
            try {
                $result = $this->client->__soapCall('GetData', array());
                return $this->client->__getLastResponse();
            } catch (Exception $e) {
                throw new Exception("Error Processing Request", 1);
            }
        }
    }