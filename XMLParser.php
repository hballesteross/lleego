<?php

    use App\PruebaTecnica\Entity\Segment;

    class Parser
    {
        public function parseFlights(string $xmlString): array
        {
            $xml = simplexml_load_string($xmlString);
            $data = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://www.iata.org/IATA/EDIST/2017.2');
            $flights = $data->AirShoppingRS->DataLists->FlightSegmentList->FlightSegment;

            $flightList = array();

            foreach ($flights as $flight) {

                $segment = new Segment();

                $segment->setOriginCode($flight->Departure->AirportCode);
                $segment->setOriginName($flight->Departure->AirportName);
                $segment->setDestinationCode($flight->Arrival->AirportCode);
                $segment->setDestinationName($flight->Arrival->AirportName);
                $segment->setStart(new DateTime($flight->Departure->Date . $flight->Departure->Time));
                $segment->setEnd(new DateTime($flight->Arrival->Date . $flight->Arrival->Time));
                $segment->setTransportNumber($flight->MarketingCarrier->FlightNumber);
                $segment->setCompanyCode($flight->MarketingCarrier->AirlineID);
                $segment->setCompanyName($flight->MarketingCarrier->Name);

                $flightList[] = $segment;
            }

            return $flightList;
        }
    }