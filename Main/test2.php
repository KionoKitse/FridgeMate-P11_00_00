<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .center {
            margin: auto;
            border: 3px solid #73AD21;
            padding: 10px;
        }

        .break {
            background: #3E87BC;
            height: 2px;
            margin: 5px 0 10px 0;
            width: 100%;
        }

        p {
            font-size: 3vw;
            font-weight: normal;
        }
        th{
            font-size: 3vw;
            font-weight: normal;
            padding: 0.5vw;
        }
        li{
            font-size: 3vw;
            font-weight: normal;
        }
        html * {
            max-height: 999999px !important;
            color: #3E87BC;
        }


    </style>
    <script type="text/javascript" src="data.json"></script>
</head>

<body>

    <div class="center">
        <div id="Title">
            <p style="text-align: center; font-size: 5vw;">
                <a href="https://www.allrecipes.com/recipe/23891/grilled-cheese-sandwich/">
                Grilled banana
                </a>
            </p>
            
        </div>

        <img id="Image" src="./Img/cat.png" style="width:100%">

        <table id="Stats" style="width:100%">
            <tr>
                <th>4/5 <i class="fa fa-star"></i></th>
                <th>10% <i class="fas fa-clipboard-check"></i></th>
                <th>20min <i class="far fa-clock"></i></i></th>
                <th>1h 20min <i class="fa fa-clock"></i></th>
                <th>3 <i class="fas fa-user-astronaut"></i></th>
            </tr>
        </table>
        <br>

        <table id="Main" style="float: left; text-align: left;">
            <tr>
                <th colspan="4">Main</th>
            </tr>
            <tr>
                <th>2</th>
                <th>P</th>
                <th>Bread</th>
            </tr>
            <tr>
                <th>3</th>
                <th>oz</th>
                <th>Cheese</th>
            </tr>
        </table>

        <table id="Support" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="4">Support</th>
            </tr>
            <tr>
                <th>1</th>
                <th>oz</th>
                <th>Spinach</th>
            </tr>
            <tr>
                <th>2</th>
                <th>Tb</th>
                <th>Butter</th>
            </tr>
        </table>

        <div style="clear: both;"><br></div>

        <table id="Spices" style="float: left; text-align: left;">
            <tr>
                <th colspan="4">Spices</th>
            </tr>
            <tr>
                <th>0.25</th>
                <th>Tsp</th>
                <th>Garlic powder</th>
            </tr>
            <tr>
                <th>0.5</th>
                <th>Tsp</th>
                <th>Oregano</th>
            </tr>
        </table>

        <table id="Garnish" style="float: right; width: 50%; text-align: left;">
            <tr>
                <th colspan="3">Garnish</th>
            </tr>
            <tr>
                <th>*</th>
                <th>*</th>
                <th>Fresh parsley</th>
            </tr>

        </table>

        <div class="break" style="clear: both;"></div>

        <div id="Notes">
            <p>Sandwich is good but could use some more cheese.</p>
        </div>

        <div class="break"></div>

        <div id="Prep">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Prep</th>
                </tr>
                <tr>
                    <th>*</th>
                    <th>*</th>
                    <th>Fresh parsley</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>oz</th>
                    <th>Spinach</th>
                </tr>
            </table>
            <p>
                Group: 1<br>
                * Chop fresh parsley<br>
                <br>
                Group: 2<br>
                * Add galic<br>
                * Add oregano<br>
                <br>
                Group: 3<br>
                * Wash and cut spinach
            </p>
        </div>
        <div class="break" style="clear: both;"></div>

        <div id="Step1">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Step 1</th>
                </tr>
                <tr>
                    <th>2</th>
                    <th>P</th>
                    <th>Bread</th>
                </tr>
                <tr>
                    <th>2</th>
                    <th>Tb</th>
                    <th>Butter</th>
                </tr>
            </table>
            <p>
                Heat skillet to medium high.
                Add butter and sliced bread.
                Toast on one side.
            </p>
        </div>
        <div class="break" style="clear: both;"></div>

        <div id="Step2">
            <table style="text-align: left;">
                <tr>
                    <th colspan="3">Step 2</th>
                </tr>
                <tr>
                    <th>3</th>
                    <th>oz</th>
                    <th>Cheese</th>
                </tr>
                <tr>
                    <th>1</th>
                    <th>oz</th>
                    <th>Spinach</th>
                </tr>
                <tr>
                    <th>0.25</th>
                    <th>Tsp</th>
                    <th>Garlic powder</th>
                </tr>
                <tr>
                    <th>0.5</th>
                    <th>Tsp</th>
                    <th>Oregano</th>
                </tr>
                <tr>
                    <th>*</th>
                    <th>*</th>
                    <th>Fresh parsley</th>
                </tr>
            </table>
            <p>
                Once the bread is toasted.
                Remove from skillet and add a little more butter then put untoasted side down.
                Put cheese ans skices attop the toasted bread.
                Close sandwich and grill until finished.
            </p>
        </div>
    </div>
    <div id="txtHint"><b>Person info will be listed here...</b></div>
</body>

<!--Load content into page-->
<script>
    //Update information on page
    UpdateTitleContent()
    UpdateStats()
    showUser('4');

    //Update the title, link and image
    function UpdateTitleContent() {
        var TitleString = '<p style="text-align: center; font-size: 5vw;"><a href="' + data.Link + '">' + data.Name + '</a></p>'
        document.getElementById('Title').innerHTML = TitleString
        document.getElementById("Image").src = data.Image
    }
    //Update basic stats content and notes
    function UpdateStats() {
        var StatsString =
            '<tr>' +
            '<th>' + data.Rating + '/5 <i class="fa fa-star"></i></th>' +
            '<th>100% <i class="fas fa-clipboard-check"></i></th>' +
            '<th>' + Min2Time(data.ActiveTime) + ' <i class="far fa-clock"></i></i></th>' +
            '<th>' + Min2Time(data.PassiveTime) + ' <i class="fa fa-clock"></i></th>' +
            '<th>' + data.People + ' <i class="fas fa-user-astronaut"></i></th>' +
            '</tr>'
        document.getElementById('Stats').innerHTML = StatsString
        document.getElementById('Notes').innerHTML = '<p>' + data.Notes + '</p>'
    }
    //Function to convert min to h/min string
    function Min2Time(Mins) {
        if (Mins < 60) {
            return Mins.toString()
        }
        else {
            var Hrs = Math.floor(Mins / 60)
            var min = Mins - 60 * Hrs
            return Hrs + 'h' + min + 'min'
        }
    }

    function showUser(str) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("txtHint").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET","getuser.php?q="+str,true);
      xmlhttp.send();
    }
    
    
</script>

</html>
<!--
    Credits
    Loading JSON data: https://www.quora.com/How-can-I-load-data-from-a-JSON-file-into-a-variable-in-JavaScript-without-using-Ajax
    Issues with font size being wrong on android: https://stackoverflow.com/questions/11289166/chrome-on-android-resizes-font
    Getting mySQL stuff from php using JS: https://www.w3schools.com/php/php_ajax_database.asp

-->



