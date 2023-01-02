<?php

    class RequestHandler
    {
        private $soapClient;
        private $xmlParser;

        public function __construct(SoapClientWrapper $soapClient, Parser $xmlParser)
        {
            $this->soapClient = $soapClient;
            $this->xmlParser = $xmlParser;
        }

        public function handleRequest(string $username, string $password): array
        {
            $this->soapClient->authenticate($username, $password);
            $response = $this->soapClient->getData();
            return $this->xmlParser->parseFlights($response);
        }
    }
