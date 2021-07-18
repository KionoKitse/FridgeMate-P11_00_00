//Function to get the name 1 options
function GetName1() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //Get the list section
            var temp = document.getElementById("Lists").innerHTML;
            //Get list of options
            var NewList = this.responseText;
            //Create a new list
            NewList = '<datalist id="Name1All">' + NewList + '</datalist>';
            //Add to lists
            document.getElementById("Lists").innerHTML = temp + NewList;
        }
    };
    xmlhttp.open("GET", "php/name1.php", true);
    xmlhttp.send();
}
//Function to create a Name2 data list
function GetName2(str) {
    //Get the Name1 value
    var Name1 = document.getElementById(str + 'Name1').value;

    //Run the request to get Name2
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //Get list of options
            var NewList = this.responseText;
            NewList = '<datalist id="Name2' + str + '">' + NewList + '</datalist>';

            //Replace or add
            var OldList = document.getElementById('Name2' + str)
            if (OldList == null) {
                //Add the new list to the list of existing lists
                var temp = document.getElementById("Lists").innerHTML;
                document.getElementById("Lists").innerHTML = temp + NewList;
            } else {
                //Replace the current list
                OldList = NewList;
            }

            //Create the new datalist
            var NewList = this.responseText;
            NewList = '<datalist id="Name2' + str + '">' + NewList + '</datalist>';

            //Remove the old datalist
            var OldList = document.getElementById('Name2' + str)
            if (OldList != null) OldList.remove();

            //Add the new datalist to the lists section
            var temp = document.getElementById("Lists").innerHTML;
            document.getElementById("Lists").innerHTML = temp + NewList;
        }
    };
    xmlhttp.open("GET", "php/name2.php?name1=" + Name1, true);
    xmlhttp.send();
}
//Function to create a Name2 data list
function GetName3(str) {
    //Get the Name1 value
    var Name1 = document.getElementById(str + 'Name1').value;
    var Name2 = document.getElementById(str + 'Name2').value;

    //Run the request to get Name3
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //Create the new datalist
            var NewList = this.responseText;
            NewList = '<datalist id="Name3' + str + '">' + NewList + '</datalist>';

            //Remove the old datalist
            var OldList = document.getElementById('Name3' + str)
            if (OldList != null) OldList.remove();

            //Add the new datalist to the lists section
            var temp = document.getElementById("Lists").innerHTML;
            document.getElementById("Lists").innerHTML = temp + NewList;
        }
    };
    xmlhttp.open("GET", "php/name3.php?name1=" + Name1 + "&name2=" + Name2, true);
    xmlhttp.send();
}
//Function to update the selected option
function UpdateSelectOption(Id) {
    //Get selection box
    var SelectionBox = document.getElementById(Id);
    var SelectedId = SelectionBox.value;
    var SelectionText = SelectionBox.innerHTML;

    //Remove the previous selection if needed
    var KeyText = 'selected="selected" ';
    var SelectionText = SelectionText.replace(KeyText, '');

    //Find the SelectedId inside the SelectionText and add 
    var n = SelectionText.search(SelectedId) - 7;
    var NewText = SelectionText.slice(0, n) + KeyText + SelectionText.slice(n);

    //Update dropdown
    SelectionBox.innerHTML = NewText;
}
//Function to change status
function changeStatus(id) {
    //Get the values for the Status and the Cart
    var valA = document.getElementById(id).checked ? 1 : 0;
    id = id.match(/\d+/)[0];
    var valB = document.getElementById("Cart"+id).checked ? 1 : 0;

    //Determine if need to change sliders
    if(valA && valB){
        //Going from cart to status
        
        document.getElementById("Cart"+id).checked = false;
    }
    if(!valA && valB){
        //Removing from status and cart
        console.log("hello");
        document.getElementById("Cart"+id).checked = false;
    }

    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "php/changeStatus.php?id=" + id + "&valA=" + valA + "&valB=" + valB, true);
    xmlhttp.send();
}
//Function to change cart
function changeCart(id) {
    //Get the values for the Status and the Cart
    var val = document.getElementById(id).checked ? 1 : 0;

    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "php/changeCart.php?id=" + id + "&val=" + val, true);
    xmlhttp.send();
}
//Function to display tiles
function ShowMoreLessTiles(TableId){
    //Get the table and the number of entries displayed
    var Table = document.getElementById(TableId);
    var DispCt = Number(document.getElementById(TableId+'Disp').value);

    //Variables
    var i, NextCt;

    //Display more tiles
    if(DispCt<Table.rows.length-1){
        //Decide the next count to go to
        if(DispCt+2 > Table.rows.length-2){
            NextCt = Table.rows.length-1;
        } else {
            NextCt = DispCt+2;
        }

        //Set the visability
        for (i = DispCt; i < NextCt; i++) {
            Table.rows[i].style.display = 'table-row';
        }

        //Change button if no more to display
        if(i == Table.rows.length-1){
            var Bttn = document.getElementById(TableId+'Show');
            Bttn.style.backgroundColor = '#E07A5F';
            Bttn.style.borderColor = '#FA9479';
        }
    }
    //Display less tiles
    else{
        //Set the visability
        for (i = 2; i < Table.rows.length-1; i++) {
            Table.rows[i].style.display = 'none';
        }

        //Change button if no more to display
        var Bttn = document.getElementById(TableId+'Show');
        Bttn.style.backgroundColor = '#81B29A';
        Bttn.style.borderColor = '#9BCCB4';
        i=2;
    } 

    //Save displayed count
    document.getElementById(TableId+'Disp').value = i;
}
//Function to remove a tile
function RemoveTile(TileId){
    var Tile = document.getElementById(TileId);
    Tile.remove();
}
