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
    //Get the new value
    var val = document.getElementById(id).checked ? 1 : 0;

    //Convert the id to a number
    id = id.match(/\d+/)[0];

    //Get the cart status
    var cart = document.getElementById("Cart"+id).checked ? 1 : 0;

    //If going from cart to have -> don't need to recalculate the buildability  
    var calc = 1;
    if(val && cart){
        calc = 0;
        //Change the cart value also
        document.getElementById("Cart"+id).checked = false;
    }
    //If setting status 0 but item is in cart -> remove from cart
    if(!val && cart){
        document.getElementById("Cart"+id).checked = false;
    }

    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "php/changeStatus.php?id=" + id + "&val=" + val + "&calc=" + calc, true);
    xmlhttp.send();
}
//Function to change cart
function changeCart(id) {
    //Get the new value
    var val = document.getElementById(id).checked ? 1 : 0;

    //Convert the id to a number
    id = id.match(/\d+/)[0];

    //Get the cart status
    var Status = document.getElementById("Status"+id).checked ? 1 : 0;

    //If adding to cart but already have -> don't need to recalculate buildability
    var calc = 1;
    if(Status){
        calc = 0;
    }

    //Submit the changes
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("error").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "php/changeCart.php?id=" + id + "&val=" + val + "&calc=" + calc, true);
    xmlhttp.send();
}
