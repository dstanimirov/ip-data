# IP Data

Plugin allows lookup the location of any IP address

website

https://ipdata.co/

link to documentation

https://docs.ipdata.co/


**How to use**


*  As a shared service from json file
 
1. Create file in json format and save in desired folder

```
{
        "sharedServices": {
            "ipdata": {
                "className": "\\Openapi\\Phalcon\\Plugins\\IpData",
                "properties": [
                    {
                        "name": "apiKey",
                        "value": {
                            "type": "parameter",
                            "value": "your-ip-data-api-key"
                        }
                    },
                    {
                        "name": "baseUrl",
                        "value": {
                            "type": "parameter",
                            "value": "https://api.ipdata.co"
                        }
                    }
                ]
            }
        }
}
```


2. In your application read the file with Phalcon Json adatper an Load as a shared service


```
$sharedServices = new Phalcon\Config\Adapter\Json('path-to-json-file-with-shared-services');

foreach ($sharedServices->path('sharedServices', []) as $shareName => $options) {

    $di->setShared($shareName, $options->toArray());
    
};
```



3. Call anywere in your application ( controllers or plugins )

`$ipdata = $this->ipdata->locate('ip-address-to-locate');`



*  As a instance of class


```
$ipdata = new Openapi\Phalcon\Plugins\IpData();

$ipdata->setApiKey('your-api-key');

$data = $ipdata->locate('ip-address-to-locate');
```




















