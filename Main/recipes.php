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

<body>
    <span id="error"></span>
    <!-- Lists used for the datallists -->
    <div id="Lists"></div>
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
                    <button class="bttnOrange" onclick="Search('New')"><i class="fas fa-search"></i></button>
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

        switch (Type) {
            case 'Stars':
                var list = `
                    <datalist id="SearchList">
                        <option value="5"> 5 stars</option>
                        <option value="4"> 4 stars</option>
                        <option value="3"> 3 stars</option>
                        <option value="2"> 2 stars</option>
                        <option value="1"> 1 stars</option>
                    </datalist>`;
                document.getElementById("Lists").innerHTML = list;
               break;
            case 'Time':
                break;
            case 'Tags':
                break;
            case 'Ingredient':
                break;
            case 'Name':
                break;
            case 'New':
                sbreak;
        }
    }
    function SetButtons(){

    }
</script>
