**HOW TO USE**
1) Firstly you would need to install Tensorflowjs toxic Modal
```
    npm install @tensorflow/tfjs @tensorflow-models/toxicity
   ```
2) Import the Modal to : ***resources /js/bootstrap.js***
```
    window.toxicity = require('@tensorflow-models/toxicity');
```
3) The ***ValidationController.php*** contains the Code for making the prediction on the Toxicity Modal

4) The ***Demo.php*** contains a front end view as shown beloww
![enter image description here](https://github.com/kunz398/Laravel_ML-TensorFlow-Api-for-Toxic-Text/blob/master/Screenshot_8.png)
5) ***api.php*** contains the api route

6) how to make the API Call: 
```
       require_once get_template_directory() . "/framework/Curl.php";  
      
      $url = 'http://bati.local/api/invokeML';  
      
      $params = array(  
      'from'=>get_option('siteurl'),  
      'word' => ' You Suck',  
      );  
      
      $output = Curl::httpPost($url, "POST", $params);  
      
    var_dump($output);

```
![enter image description here](https://github.com/kunz398/Laravel_ML-TensorFlow-Api-for-Toxic-Text/blob/master/Screenshot_8.png)

In-order to learn how to make API calls you can look at my ApiCall Repo::
[Api Call PHP](https://github.com/kunz398/API-custom-Function-for-php-)
