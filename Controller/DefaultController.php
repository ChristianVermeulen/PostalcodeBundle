<?php

namespace ChristianVermeulen\PostalcodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function lookupAction(Request $request)
    {
        // Get the vars for looking up the postalcode
        $postalcode = $request->request->get('postalcode');
        $number = $request->request->get('number');
        $addition = $request->request->get('addition');

        // Get the api wrapper
        $api = $this->get("christian_vermeulen_postalcode");

        // Try to find an address
        try
        {
            $address = $api->lookupAddress($postalcode, $number, $addition);
        }
        catch (PostcodeNl_Api_RestClient_AddressNotFoundException $e)
        {
            die('There is no address on this postcode/housenumber combination: '. $e);
        }
        catch (PostcodeNl_Api_RestClient_InputInvalidException $e)
        {
            die('We have input which can never return a valid address: '. $e);
        }
        catch (PostcodeNl_Api_RestClient_ClientException $e)
        {
            die('We have a problem setting up our client connection: '. $e);
        }
        catch (PostcodeNl_Api_RestClient_AuthenticationException $e)
        {
            die('The Postcode.nl API service does not know who we are: '. $e);
        }
        catch (PostcodeNl_Api_RestClient_ServiceException $e)
        {
            die('The Postcode.nl API service reported an error: '. $e);
        }

        // Return the address to the front
        return new JsonResponse($address);
    }
}
