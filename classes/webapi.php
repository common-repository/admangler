<?php
    class WebAPI
    {
        function __construct( $username = null, $apikey = null, $url = 'http://webapi.webternals.com/api' )
        {
            $this->url = $url;
            $this->requestXML = $webapi = new SimpleXMLElement('<webapi></webapi>');
            $verify = $webapi->addchild('verify');
            $verify->addChild('username', $username);
            $verify->addChild('apikey', $apikey);
        } // __construct

        function add_request( SimpleXMLElement $newRequest )
        {
            $webapi = $this->requestXML;
            //$request = $webapi->addChild('request');
            $this->append_tree($webapi, $newRequest);
        }

        function append_tree( $root, $child )
        {
            // Create new DOMElements from the two SimpleXMLElements
            $domroot = dom_import_simplexml($root);
            $domchild  = dom_import_simplexml($child);

            // Import the <cat> into the dictionary document
            $domchild  = $domroot->ownerDocument->importNode($domchild, TRUE);

            // Append the <cat> to <c> in the dictionary
            $domroot->appendChild($domchild);
        }

        function request()
        {
            $xml = $this->requestXML->saveXML();
            if ( function_exists('curl_version') )
            {
                //open connection
//                $ch = curl_init();
//
//                //set the url, number of POST vars, POST data
//                curl_setopt($ch,CURLOPT_URL, $this->url);
//                curl_setopt($ch,CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
//                curl_setopt($ch,CURLOPT_POSTFIELDS, $xml);
//
//                //execute post
//                $xml = curl_exec($ch);
//                $this->responseXML = new SimpleXMLElement($xml);
//
//                //close connection
//                curl_close($ch);
                //file_put_contents(dirname(__FILE__)."/../log.txt", $ch. "\n", FILE_APPEND);
            }
            else
            {
                $fp = fsockopen("webapi.webternals.com", 80, $errno, $errstr, 5);

                if (!$fp)
                {
                    $_return = ' error: ' . $errno . ' ' . $errstr;
                    throw new Exception ("Error: Faild to make a proper connection! - Error Number:" . $errno . " Error String:".$errstr);
                }
                else
                {
                    $http  = "POST /api HTTP/1.1\r\n";
                    $http .= "Host: webapi.webternals.com\r\n";
                    $http .= "User-Agent: fsockopen\r\n";
                    $http .= "Content-Type: text/xml\r\n";
                    $http .= "Content-length: " . strlen($xml) . "\r\n";
                    $http .= "Connection: close\r\n\r\n";
                    $http .= $xml . "\r\n\r\n";

                    fwrite($fp, $http);

                    $content = '';
                    $capture = false;
                    while (!feof($fp))
                    {
                        $buffer = fgets($fp);
                        if ( 0 == strcmp( substr($buffer, 0, 8), "<webapi>") )
                            $content .= $buffer;
                    }
                    fclose($fp);

                    $this->responseXML = new SimpleXMLElement($content);
                }
            }
        } // request

        function requestXML()
        {
            return $this->requestXML;
        } // requestXML

        function responseXML()
        {
            return $this->responseXML;
        } // responseXML

        function __destruct()
        {

        }// __destruct
    } // WebAPI
?>