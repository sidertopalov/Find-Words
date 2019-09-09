<?php
set_time_limit( 0 );

$bgDictionary = file_get_contents('dictionaries/bg_BG_no_duplicates.json');

$dummyMatrix = [
    ['т', 'д', 'в', 'а'],
    ['ш', 'к', 'а', 'л'],
    ['е', 'б', 'и', 'ф'],
    ['к', 'р', 'и', 'ц']
];
$manage = json_decode($bgWords, true);
var_dump(json_encode($manage, JSON_PRETTY_PRINT)); die;

echo '<script>';
echo 'var bg_dictionary = ' . $bgDictionary . ';';
// echo 'var bg_dic_len = ' . (count())
echo 'var dummy_matrix = ' . json_encode($dummyMatrix) . ';';
echo '</script>';

?>

<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .h-88 {
            height: 88px;
        }

        .padding-bot-20 {
            padding-bottom: 20px;
        }

        .space-bot-top-30 {
            margin: auto; 
            margin-bottom: 30px; 
            margin-top: 30px;

            padding-bottom: 30px; 
            padding-top: 30px; 
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 0px;
        }

        .grid {
            margin: 10px;
            font-size: 24px;
            padding-top: 40px;
        }
        .grid button#closeBtn {
            margin-left: 5px;
        }
        .grid .row:not(:last-child) {
            border-width: 1px;
            border-bottom-style: solid;
        }
        .grid .row .col:not(:last-child) {
            border-width: 1px;
            border-right-style: solid;
        }

        .info {
            background: #e5e1e1;
            margin-left: 10px;
        }
        
        .info .info-title {
            font-size: 21px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .info div#letters {
            font-size: 18px;
            padding-bottom: 5px;
        }
        .info div#cell {
            font-size: 16px;
            white-space: pre-wrap;
        }
        
    </style>

    <title>Find Words!</title>
  </head>
  <body>

    <h2 class="text-center" style="padding-bottom: 30px;">Find all words</h2>
    <div class="container">
        <form id="solveForm">
            <div class="row padding-bot-20">
                <div class="col col-9">
                    <div class="form-group">
                        <!-- <label for="enterMatrix">Example label</label> -->
                        <input type="text" class="form-control form-control-lg" id="enterMatrix" placeholder="Enter letters for matrix">
                    </div>
                </div>

                <div class="col col-3">
                    <button id="submitBtn" type="submit" onclick="solveMatrix(this);" class="btn btn-lg btn-primary">Solve</button>
                </div>
                <hr />
            </div>
        </form>

        <div class="text-center space-bot-top-30" id="matrix" style="background-color: #03a9f430; width: 350px;">
            <div class="row">
                <div class="col col-3" id="cell_00">Т</div>
                <div class="col col-3" id="cell_01">Д</div>
                <div class="col col-3" id="cell_02">В</div>
                <div class="col col-3" id="cell_03">А</div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_10">Ш</div>
                <div class="col col-3" id="cell_11">К</div>
                <div class="col col-3" id="cell_12">А</div>
                <div class="col col-3" id="cell_13">Л</div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_20">Е</div>
                <div class="col col-3" id="cell_21">Б</div>
                <div class="col col-3" id="cell_22">И</div>
                <div class="col col-3" id="cell_23">Ф</div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_30">К</div>
                <div class="col col-3" id="cell_31">Р</div>
                <div class="col col-3" id="cell_32">И</div>
                <div class="col col-3" id="cell_33">Ц</div>
            </div>
        </div>

    </div>

    <div class="text-center title" id="totalWord"> 
        
    </div>

    <div class="container" id="container-grid-result" style="margin-top: 50px; margin-bottom: 50px; position: relative; padding: 0px;">
        
    </div>


    <!-- html templates -->

    <div class="row grid" id="grid_copy" style="display: none;">
        <div class="col-4 text-center" style="border-style: solid;">
            <div class="row">
                <div class="col col-3" id="cell_00"></div>
                <div class="col col-3" id="cell_01"></div>
                <div class="col col-3" id="cell_02"></div>
                <div class="col col-3" id="cell_03"></div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_10"></div>
                <div class="col col-3" id="cell_11"></div>
                <div class="col col-3" id="cell_12"></div>
                <div class="col col-3" id="cell_13"></div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_20"></div>
                <div class="col col-3" id="cell_21"></div>
                <div class="col col-3" id="cell_22"></div>
                <div class="col col-3" id="cell_23"></div>
            </div>
            <div class="row">
                <div class="col col-3" id="cell_30"></div>
                <div class="col col-3" id="cell_31"></div>
                <div class="col col-3" id="cell_32"></div>
                <div class="col col-3" id="cell_33"></div>
            </div>
        </div>

        <div class="col info"> 
            <div class="info-title" id="fullWord"></div>
            <div id="letters"></div>
            <div id="cell"></div>

            <!-- <div>
                <button type="button" class="btn btn-primary">Primary</button>
            </div> -->
        </div>

        <button type="button" id="closeBtn" class="btn btn-primary">DONE</button>
    </div>

    <!-- html templates -->


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- <script type="text/javascript" src="app/index.js"></script> -->
    <script type="text/javascript" src="app/trie/trie.js"></script>
    <script type="text/javascript" src="app/trie/node.js"></script>
    <script type="text/javascript" src="app/trie/board.js"></script>


    <script type="text/javascript" src="app/index.js"></script>

  </body>
</html>