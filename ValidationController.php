<?php 
namespace App\Http\Controllers;

use App\validation as Validation;
use Illuminate\Http\Request;
class ValidationController extends Controller
{
  public function InvokeJS(Request $request)
      {
          $words = $request->word;
          $routez = route('kunz.ml.proccess');

          ?>
          <meta name="csrf-token" content="{{ csrf_token() }}">

          <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
          <script src="<?php echo asset('js/app.js'); ?>"></script>



          <script>
              $(document).ready(function () {
                  const threshold = 0.9;
                  toxicity.load(threshold).then(model => {
                      const sentences = ['$words'];
                      model.classify(sentences).then(predictions => {
                          // $('#result').html(JSON.stringify(predictions));

                          var sizeOfRequestPredictData = predictions.length;
                          var label =[];
                          var probability_0 =[];
                          var probability_1 =[];
                          var match =[];

                          for (let i = 0; i < sizeOfRequestPredictData; i++) //7
                          {


                              label.push([predictions[i]['label']]);
                              // probability_0.push((predictions[i]['results'][0]['probabilities'][0]));
                              // probability_1.push(predictions[i]['results'][0]['probabilities'][1]);
                              probability_0.push(Math.round(((predictions[i]['results'][0]['probabilities'][0]) * 100) * 1000)/1000);
                              probability_1.push(Math.round(((predictions[i]['results'][0]['probabilities'][1]) * 100) * 1000)/1000);
                              match.push(predictions[i]['results'][0]['match']);
                          }
                          for (var x in label) {

                              $('#result').append(label[x] +": " +
                                  // probability_0[x] + ", "+
                                  probability_1[x] +
                                  // " = "+ match[x] +
                                  "<br>");
                          };
                      });
                  });
              });

          </script>
          <div id='result'>
          </div>

          <?php
      }

      public function process(Request $request)
      {

          $RequestPredictData = $request->PredictData;

          $sizeOfRequestPredictData = sizeof($RequestPredictData);
          $probability = [];
          // loop starts
          for ($i = 0; $i < sizeof($RequestPredictData); $i++) //7
          {
              for ($j = 0; $j < sizeof($RequestPredictData[$i]['results']); $j++) //1
              {
                  $match['match'] = $RequestPredictData[$i]['results'][$j]['match'];

                  for ($k = 0; $k < sizeof($RequestPredictData[$i]['results'][$j]); $k++) //2
                  {
                      $probability['probability_' . $k] = array(
                          round($RequestPredictData[$i]['results'][$j]['probabilities'][$k] * 100, 2)
                      );
                  }
              }
              $labels[$RequestPredictData[$i]['label']] = array(
                  $probability,
                  $match
              );
          }

          for ($i = 0; $i < $sizeOfRequestPredictData; $i++) {
              $resp[] = $this->printData($labels, $RequestPredictData[$i]['label']);

          }
          return json_encode($resp);
      }

      public function printData($array, $label_name)
      {
          $final = $label_name . ': ';
          $final .= json_encode($array[$label_name][0]['probability_0'][0]);
          $final .= '%, ';
          $final .= json_encode($array[$label_name][0]['probability_1'][0]);
          $final .= '% <br>';

          return $final;
      }
}
