<!DOCTYPE html>
<html>
<head>
    <link href="css/mainstyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="js/mainstyle.js"></script>
</head>

<!-- Style for this page -->
<style>
  .bttnGreen {
    background-color: #81B29A;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #9BCCB4; 
    color: #3D405B;
  }
  .bttnYellow {
    background-color: #F2CC8F;
    width:100%;
    border-radius: 5px; 
    border: 2px solid #FFE6A9; 
    color: #3D405B;
  }
  .bttnOrange {
        background-color: #E07A5F;
        width:100%;
        border-radius: 5px; 
        border: 2px solid #FA9479; 
        color: #3D405B;
    }
</style>

<!-- Get the required information to render the page -->
<?php
    require_once 'dbconnect.php';

    //Get all tags
    $Query1 = "SELECT DISTINCT tag FROM tags ORDER BY tag";
    $ResultSet1 = $connection->query($Query1);
    
    //Build list
    $TagsList = '<datalist id="SearchList">';
    while ($row = $ResultSet1->fetch_assoc()) {
        $TagsList .= '<option value="'.$row["tag"].'"></option>';
    }
    $TagsList .= '</datalist>';

?>

<body>
    <span id="error"></span>
    <!-- Lists used for the datallists -->
    <div id="Lists">
        <datalist id="StarsList">
            <option value="5 Stars"></option>
            <option value="4 Stars"></option>
            <option value="3 Stars"></option>
            <option value="2 Stars"></option>
            <option value="1 Stars"></option>
        </datalist>
        <datalist id="TimeList">
            <option value="0-15"></option>
            <option value="15-30"></option>
            <option value="30-45"></option>
            <option value="45-60"></option>
            <option value="60-90"></option>
            <option value="90-120"></option>
        </datalist>
        <?php echo $TagsList; ?>

    </div>
    <div class="center">
        <p style="text-align: center; font-size: 5vw; color: #81B29A; font-weight: bold;">Recipes</p>
        <table style="width: 100%; border-collapse:collapse; border-spacing:0;">
            <tr>
                <td>
                    <button id="Stars" class="bttnGreen" onclick="SearchType('Stars')"><i class="fa fa-star"></i></button>
                </td>
                <td>
                    <button id="Time" class="bttnGreen" onclick="SearchType('Time')"><i class="far fa-clock"></i></button>
                </td>
                <td>
                    <button id="Tags" class="bttnGreen" onclick="SearchType('Tags')"><i class="fas fa-tags"></i></button>
                </td>
                <td>
                    <button id="Ingredient" class="bttnGreen" onclick="SearchType('Ingredient')"><i class="fas fa-carrot"></i></button>
                </td>
                <td>
                    <button id="Name" class="bttnGreen" onclick="SearchType('Name')"><i class="fas fa-id-card"></i></button>
                </td>
                <td>
                    <button id="New" class="bttnGreen" onclick="SearchType('New')"><i class="fas fa-plus"></i></button>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <input style="width: 95%" type="text" id="Search" list="SearchList">
                </td>
                <td>
                    <button class="bttnOrange" onclick="SubmitSearch()"><i class="fas fa-search"></i></button>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
<script>
    //Default search type
    var LastType = 'Stars';
    SearchType(LastType);

    function SearchType(Type){
        //Remove class from LastType
        document.getElementById(LastType).classList.remove('bttnYellow');
        document.getElementById(LastType).classList.add('bttnGreen');
        LastType = Type;

        //Change button class
        document.getElementById(Type).classList.remove('bttnGreen');
        document.getElementById(Type).classList.add('bttnYellow');

        var list;
        switch (Type) {
            case 'Stars':
                list = `
                    <datalist id="SearchList">
                        <option value="5 Stars"></option>
                        <option value="4 Stars"></option>
                        <option value="3 Stars"></option>
                        <option value="2 Stars"></option>
                        <option value="1 Stars"></option>
                    </datalist>`;
               break;
            case 'Time':
                list = `
                    <datalist id="SearchList">
                        <option value="0-15"></option>
                        <option value="15-30"></option>
                        <option value="30-45"></option>
                        <option value="45-60"></option>
                        <option value="60-90"></option>
                        <option value="90-120"></option>
                    </datalist>`;
                break;
            case 'Tags':
                list = '<?php echo $TagsList; ?>';
                break;
            case 'Ingredient':
                list = `
                    <datalist id="SearchList">
                        <option value="Enter an ingredient"></option>
                    </datalist>`;
                break;
            case 'Name':
                list = `
                    <datalist id="SearchList">
                        <option value="Enter a name"></option>
                    </datalist>`;
                break;
            case 'New':
                list = `
                    <datalist id="SearchList">
                        <option value="Search for new"></option>
                    </datalist>`;
                break;
        }
        //Apply the new list
        document.getElementById("Lists").innerHTML = list;
    }
    function SubmitSearch(){
        //Get the input from the search bar
        var SearchTerm = document.getElementById("Search").value;

        //Format input for search
        var ErrCode = false;
        switch (LastType) {
            case 'Stars':
                //Convert SearchTerm to integer
                try {
                    SearchTerm = parseInt(SearchTerm);
                    
                    //Validation
                    if(SearchTerm<0 || SearchTerm>5){
                        ErrCode = "Submit Search Failed: Input is outside expected range (1-5)";
                    }
                } catch (error) {
                    ErrCode = "Submit Search Failed: Could not convert input";
                }
               break;
            case 'Time':
                list = `
                    <datalist id="SearchList">
                        <option value="0-15"></option>
                        <option value="15-30"></option>
                        <option value="30-45"></option>
                        <option value="45-60"></option>
                        <option value="60-90"></option>
                        <option value="90-120"></option>
                    </datalist>`;
                break;
            case 'Tags':
                list = '<?php echo $TagsList; ?>';
                break;
            case 'Ingredient':
                list = `
                    <datalist id="SearchList">
                        <option value="Enter an ingredient"></option>
                    </datalist>`;
                break;
            case 'Name':
                list = `
                    <datalist id="SearchList">
                        <option value="Enter a name"></option>
                    </datalist>`;
                break;
            case 'New':
                list = `
                    <datalist id="SearchList">
                        <option value="Search for new"></option>
                    </datalist>`;
                break;
        }

    }
</script>
