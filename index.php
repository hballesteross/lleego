<?php

    include 'Segment.php';
    include 'SoapClientWrapper.php';
    include 'XMLParser.php';
    include 'RequestHandler.php';


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


    $soapClient = new SoapClientWrapper($options);
    $xmlParser = new Parser();
    $requestHandler = new RequestHandler($soapClient, $xmlParser);

    ?>


    <html>
        <body>
            <form action='index.php' method='POST'>
                <input type='hidden' name='sent' value='1' />
                <input type='submit' value='Enviar petición'>
            </form>

            <?php

            
            if (isset($_REQUEST["sent"]) and $_REQUEST["sent"] == '1') {
               
                $flightList = $requestHandler->handleRequest('','');

                print "<pre>";
                print_r($flightList);
                print "</pre>";
            }
            ?>
            
        </body>

    </html>
