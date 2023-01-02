<?php

    include 'Segment.php';
    use App\PruebaTecnica\Entity\Segment;

?>
    <html>
        <body>
            <form action='request.php' method='POST'>
                <input type='hidden' name='sent' value='1' />
                <input type='submit' value='Enviar peticion'>                
            </form>
     


<?php

if(isset($_REQUEST["sent"]) and $_REQUEST["sent"] == '1'){

    
    $url = 'https://testapi.lleego.com/prueba-tecnica/availability-price';


    $options = array(
        'uri' => 'http://schemas.xmlsoap.org/soap/envelope/',
        'location' => $url,
        'style' => SOAP_RPC,
        'use' => SOAP_ENCODED,
        'soap_version' => SOAP_1_1,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'connection_timeout' => 15,
        'trace' => 1,
        'encoding' => 'UTF-8',
        'exceptions' => true,
    );



    try {
        
        $client = new SoapClient(null,$options);
        $client->__setOptions($options);
        $header = new SoapHeader($url, 'Authenticate', array('username' => '', 'password' => ''));
        $client->__setSoapHeaders($header);
        $result = $client->__soapCall('GetData', array());
        $response = $client->__getLastResponse();

    } catch (Exception $e) {
        throw new Exception("Error Processing Request", 1);
        
    }


    $xml = simplexml_load_string($response);

    $data = $xml->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://www.iata.org/IATA/EDIST/2017.2');

    $flights = $data->AirShoppingRS->DataLists->FlightSegmentList->FlightSegment;

    $flightList = array();

    foreach($flights as $flight){

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

    print "<pre>";
    print_r($flightList);
    print "</pre>";



}

?>

</body>
</html>

