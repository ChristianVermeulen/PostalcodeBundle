What is PostalcodeBundle?
=============================

This bundle provides a wrapper for using [https://api.postcode.nl](https://api.postcode.nl) inside Symfony2.

* [Official postcode.nl API documentation](https://api.postcode.nl/documentation/rest-json-endpoint)
* [Postcode.nl API RestClient Source Code](https://github.com/postcode-nl/PostcodeNl_Api_RestClient)
* Author of this bundle: [Christian Vermeulen](http://www.christianvermeulen.net)

# Installation
When using composer add the following to your composer.json

```js
// composer.json
{
    //...

    "require": {
        //...
        "christianvermeulen/postalcode-bundle" : "dev-master"
    }

    //...
}
```

and run `php composer.phar update christianvermeulen/postalcode-bundle`.

Next add the following to your appkernel:

```php
    // in AppKernel::registerBundles()
    $bundles = array(
        // Dependencies
        new ChristianVermeulen/PostalcodeBundle/ChristianVermeulenPostalcodeBundle();
    );
```
# Configuration
Add the following to your `app/config/config.yml`

```yml
    christian_vermeulen_postalcode:
        key: [your_key]
        secret: [your_secret]
```
You can obtain these by registering your webshop at [https://api.postcode.nl/](https://api.postcode.nl/).

Add routing if you want support for ajax requests:
`app/config/routing.yml`

```yml
    christian_vermeulen_postalcode:
        resource: "@ChristianVermeulenPostalcodeBundle/Resources/config/routing.yml"
        prefix: /postal
```

# Usage

Whenever you need to lookup an address you have 2 choices.
* Through the service
* Through a post request

## Through the service

Inside any controller you can use the following code:

```php
    // get the api service
    $api = $this->get('christianvermeulen_postalcode');

    // Look up the address for Dutch postcode 2012ES, housenumber 30,
    // with no housenumber addition.
    try
    {
        $address = $client->lookupAddress('2012ES', '30', '');
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

    // Print the address data ($address is an array)
    echo var_export($address, true);
```

## Through post request
If you have included the routing of this bundle you can perform a post request to the route named `christianvermeulen_postalcode_bundle`.
You need to pass the following parameters:
* postalcode
* number
* addition

You will than get a JsonResponse with the correct details.
A sample javascript in your form might be something like this:

```javascript
    // Get the data to post through ajax
    var postalcode = $("#postalcode").val();
    var housenumber = $("#housenumber").val();
    var addition = $("#addition").val();

    // Perform ajax to get address
    $.ajax({
      url: "{{ path('christian_vermeulen_postalcode') }}",
      data: { postalcode: postalcode, number: housenumber, addition: addition },
      type: "post",
      dataType: "json"
    })

    // What should happen when we get the adress
    .done(function(response){
        // set the data in the fields
        $("#smaakwereld_sitebundle_addresstype_street").val(response.street);
        $("#smaakwereld_sitebundle_addresstype_city").val(response.city);
    })

    // What should happen if we fail?
    .fail(function(){
      console.log("We've encountered a problem!");
    });
```

## Collaboration
Feel free to fork this repo and add / improve features.
Any pull requests and / or issues are very welcome!

## The MIT License (MIT)
Copyright (c) 2013 Christian Vermeulen

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
