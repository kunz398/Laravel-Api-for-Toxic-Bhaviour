@extends('layouts.app')


@section('content')
       <div class="container">
        <div id="container">
            <div class="row">
                <div class="col-10">
                    <input id="word" type="text" class="form-control" placeholder="Place Your Text Here" autofocus>
                </div>
                <div class="col-2">
                    <button class="btn btn-outline-primary" id="classify">Submit</button>
                </div>
            </div>


        </div>
        <hr />
        <div class="card">
            <div class="card">
                <div class="card-body">
                    <small> Output:</small>
                    <div id="show">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    {{--    <script src="{{ asset('js/tensorflow.js') }}" ></script>--}}
    <script>
        $(document).ready(function () {
            $("#classify").click(function (e) {
                const threshold = 0.100;

// Load the model. Users optionally pass in a threshold and an array of
// labels to include.
                $('html, body').css('cursor', 'not-allowed');
                $("#classify").css("disabled", true); //btn
                $("#classify").html("Proccessing.."); //btn
                let words = $("#word").val();

                toxicity.load(threshold).then(model => {
                    const sentences = [words];
                    model.classify(sentences).then(predictions => {
                        /*   `predictions` is an array of objects, one for each prediction head,
                           that contains the raw probabilities for each input along with the
                           final prediction in `match` (either `true` or `false`).
                           If neither prediction exceeds the threshold, `match` is `null`.*/

                        // console.log(predictions);
                        $("#show").html(JSON.stringify(predictions));
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        /* Ajaxs*/
                        PredictData = predictions;
                        $.ajax({
                            url: "{{route('process.machinelearning')}}",
                            method: 'POST',
                            data: {PredictData: PredictData},
                            success: function (data) {
                                $("#show").html(JSON.stringify(data));
                                $('html, body').css('cursor', 'unset');
                                $("#classify").attr("disabled", false); //btn
                                $("#classify").html("Submit"); //btn
                            }
                        });

                    });
                });
            });

        });
    </script>
@endsection
{{--<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/toxicity"></script>--}}
