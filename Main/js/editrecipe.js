//Globals
var MainId = 0;
var SupportId = 0;
var SpicesId = 0;
var GarnishId = 0;
var Steps;
var OptionList;
var UnselectedOptions;

//Class
class Ingredient {
    constructor(Quantity,Unit,Name1,Name2,Name3) {
      this.Quantity = Quantity;
      this.Unit = Unit;
      this.Name1 = Name1;
      this.Name2 = Name2;
      this.Name3 = Name3;
    }
}
class PantryObj {
    constructor(ItemId,Name1,Name2,Name3) {
      this.ItemId = ItemId;
      this.Name1 = Name1;
      this.Name2 = Name2;
      this.Name3 = Name3;
      this.Item_Id = null;
    }
}


//Fill global variables
document.addEventListener("DOMContentLoaded", GetIdCounts);
document.addEventListener("DOMContentLoaded", BuildIngredientOptions);
document.addEventListener("DOMContentLoaded", GetName1);


//function to remove an ingredient row
function RemoveElement(str){
    document.getElementById(str).remove();
}
//Function to  add an ingredient row
function AddIngredient(str) {
    //Create a row id
    var RowId='';
    if(str == 'Main'){
        MainId++;
        RowId = 'Main'+MainId;
    }else if(str == 'Support'){
        SupportId++;
        RowId = 'Support'+SupportId;
    }else if(str == 'Spices'){
        SpicesId++;
        RowId = 'Spices'+SpicesId;
    }else{
        GarnishId++;
        RowId = 'Garnish'+GarnishId;
    }
    //Get the table
    var table = document.getElementById(str);
    //Create a new row
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);
    var cell6 = row.insertCell(5);
    //Add items to cells
    cell1.innerHTML = '<input type="text" id="'+RowId+'Quantity">';
    cell2.innerHTML = '<input type="text" id="'+RowId+'Unit" list="Unit"/>';
    cell3.innerHTML = '<input onchange="GetName2(\''+RowId+'\')" type="text" id="'+RowId+'Name1" list="Name1All"/>';
    cell4.innerHTML = '<input onchange="GetName3(\''+RowId+'\')" type="text" id="'+RowId+'Name2" list="Name2'+RowId+'"/>';
    cell5.innerHTML = '<input type="text" id="'+RowId+'Name3" list="Name3'+RowId+'"/>';
    cell6.innerHTML = '<i onclick="RemoveElement(\''+RowId+'\')" style="color: #F2CC8F; font-size: 5vw" class="far fa-minus-square"></i>';
    //Give row an id
    row.id = RowId;
    //Style
    cell1.style.width = '10%';
    cell2.style.width = '15%';
}
//Function to get the name 1 options
function GetName1(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //Get the list section
            var temp = document.getElementById("Lists").innerHTML;
            //Get list of options
            var NewList = this.responseText;
            //Create a new list
            NewList = '<datalist id="Name1All">'+NewList+'</datalist>';
            //Add to lists
            document.getElementById("Lists").innerHTML = temp + NewList;
        }
    };
    xmlhttp.open("GET","php/name1.php",true);
    xmlhttp.send();
}
//Function to create a Name2 data list
function GetName2(str){
    //Get the Name1 value
    var Name1 = document.getElementById(str+'Name1').value;

    //Run the request to get Name2
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //Get the list section
            var temp = document.getElementById("Lists").innerHTML;
            //Get list of options
            var NewList = this.responseText;
            //Create a new list
            NewList = '<datalist id="Name2'+str+'">'+NewList+'</datalist>';
            //Replace or add
            var OldList = document.getElementById('Name2'+str)
            if(OldList == null){
                //Add the new list to the lists section
                document.getElementById("Lists").innerHTML = temp + NewList;
            }else{
                //Replace the current list
                OldList.innerHTML = NewList;
            }
        }
    };
    xmlhttp.open("GET","php/name2.php?name1="+Name1,true);
    xmlhttp.send();
}
//Function to create a Name2 data list
function GetName3(str){
    //Get the Name1 value
    var Name1 = document.getElementById(str+'Name1').value;
    var Name2 = document.getElementById(str+'Name2').value;

    //Run the request to get Name3
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //Get the list section
            var temp = document.getElementById("Lists").innerHTML;
            //Get list of options
            var NewList = this.responseText;
            //Create a new list
            NewList = '<datalist id="Name3'+str+'">'+NewList+'</datalist>';
            //Replace or add
            var OldList = document.getElementById('Name3'+str)
            if(OldList == null){
                //Add the new list to the lists section
                document.getElementById("Lists").innerHTML = temp + NewList;
            }else{
                //Replace the current list
                OldList.innerHTML = NewList;
            }
        }
    };
    xmlhttp.open("GET","php/name3.php?name1="+Name1+"&name2="+Name2,true);
    xmlhttp.send();
}
function BuildIngredientOptions(){
    var TempOptionList=[];
    UnselectedOptions = '<option value=""></option>';
    for (var Id = 1; Id <= MainId; Id++) {
        var RowId = "Main"+Id;
        try {
            var Quantity = document.getElementById("Main"+Id+"Quantity").value;
            var Unit = document.getElementById("Main"+Id+"Unit").value;
            var Name1 = document.getElementById("Main"+Id+"Name1").value;
            var Name2 = document.getElementById("Main"+Id+"Name2").value;
            var Name3 = document.getElementById("Main"+Id+"Name3").value;
            var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
            var Row = [RowId,RowName];
            TempOptionList.push(Row);
            UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
          }
          catch(err) {}
    }
    for (var Id = 1; Id <= SupportId; Id++) {
        var RowId = "Support"+Id;
        try {
            var Quantity = document.getElementById("Support"+Id+"Quantity").value;
            var Unit = document.getElementById("Support"+Id+"Unit").value;
            var Name1 = document.getElementById("Support"+Id+"Name1").value;
            var Name2 = document.getElementById("Support"+Id+"Name2").value;
            var Name3 = document.getElementById("Support"+Id+"Name3").value;
            var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
            var Row = [RowId,RowName];
            TempOptionList.push(Row);
            UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
        }
        catch(err) {}
    }
    for (var Id = 1; Id <= SpicesId; Id++) {
        var RowId = "Spices"+Id;
        try {
            var Quantity = document.getElementById("Spices"+Id+"Quantity").value;
            var Unit = document.getElementById("Spices"+Id+"Unit").value;
            var Name1 = document.getElementById("Spices"+Id+"Name1").value;
            var Name2 = document.getElementById("Spices"+Id+"Name2").value;
            var Name3 = document.getElementById("Spices"+Id+"Name3").value;
            var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
            var Row = [RowId,RowName];
            TempOptionList.push(Row);
            UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
        }
        catch(err) {}
    }
    for (var Id = 1; Id <= GarnishId; Id++) {
        var RowId = "Garnish"+Id;
        try {
            var Quantity = document.getElementById("Garnish"+Id+"Quantity").value;
            var Unit = document.getElementById("Garnish"+Id+"Unit").value;
            var Name1 = document.getElementById("Garnish"+Id+"Name1").value;
            var Name2 = document.getElementById("Garnish"+Id+"Name2").value;
            var Name3 = document.getElementById("Garnish"+Id+"Name3").value;
            var RowName = Quantity+" "+Unit+" "+Name1+" "+Name2+" "+Name3;
            var Row = [RowId,RowName];
            TempOptionList.push(Row);
            UnselectedOptions += '<option value="'+RowId+'">'+RowName+'</option>';
        }
        catch(err) {}
    }
    OptionList = TempOptionList;
}
function GetIdCounts(){
    MainId = document.getElementById("MainId").value;
    SupportId = document.getElementById("SupportId").value;
    SpicesId = document.getElementById("SpicesId").value;
    GarnishId = document.getElementById("GarnishId").value;
    var temp = document.getElementById("StepIds").value;
    Steps = JSON.parse(temp);
    
    //PrepId = document.getElementById("PrepId").value;
}
//Function to add an ingredient line
function AddIngredientLine(id){
    //Create a unique id
    Steps.Ids[id]++;
    var RowId='Step'+id+'Item'+ Steps.Ids[id];
    //Get the table
    var table = document.getElementById('Step'+id);
    //Create a new row
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    //Add items to cells
    cell1.innerHTML = '<select id="Select'+RowId+'" onchange="UpdateSelectOption('+RowId+')">'+UnselectedOptions+'</select>';
    cell2.innerHTML = '<i onclick="RemoveElement(\''+RowId+'\')" style="color: #F2CC8F; font-size: 5vw;" class="far fa-minus-square"></i>';
    //Give row an id
    row.id = RowId;
    var hello = Steps.Ids[id];
}
//Function to add a step
function AddStep(){
    //Create a new id count for the new step
    Steps.Ids.push(0);
    //Get the step id
    var StepId = Steps.Ids.length-1;
    //Create the html for the new step
    var NewStep = '<div id="DivStep'+StepId+'">';
    NewStep += '<table style="text-align: left; width: 75%;" id="Step'+StepId+'">'
    NewStep += '<tr>';
    NewStep += '<th>Step '+StepId+' <i onclick="AddIngredientLine('+StepId+')" style="color: #F2CC8F;" class="far fa-plus-square"></i></th>';
    NewStep += '</tr>'; 
    //NewStep += PreSelectedDropDown($AllIngredinets,$Id,$OptionList);
    NewStep += '</table>';
    NewStep += '<textarea style="width:98%;" rows="10" id="Step'+StepId+'Box" onchange="UpdateStepText('+StepId+')"></textarea>';
    NewStep += '</div>';
    NewStep += '<div class="break" id="BreakStep'+StepId+'"></div>'

    //Add new html to old html 
    var OldSteps = document.getElementById("Steps").innerHTML;
    document.getElementById("Steps").innerHTML = OldSteps + NewStep;
}
//Function to remove a step
function RemoveStep(){
    //Get the number of steps
    if(Steps.Ids.length > 1){
        //Get confirmation
        var temp = Steps.Ids.length-1;
        temp = "Are you sure you want to remove Step "+temp;
        if (confirm(temp) == true) {
            //Remove the last step from the Steps Id array
            Steps.Ids.pop();
            var StepId = Steps.Ids.length;
            //Remove the DivStep and the break element
            RemoveElement('DivStep'+StepId);
            RemoveElement('BreakStep'+StepId);
        }  
    }
}
//Function to transfer the textarea value to it's innerHTML
function UpdateStepText(Step){
    document.getElementById("Step"+Step+"Box").innerHTML = document.getElementById("Step"+Step+"Box").value;
}
//Function to update the selected option
function UpdateSelectOption(Id){
    //Get selection box
    var SelectionBox = document.getElementById(Id);
    //Get the value
    var value = SelectionBox.value;
    var hello = document.getElementById(Id);

    var SelectionBox = document.getElementById(Id);
    var Options = SelectionBox.options;
    for (var idk, j=0; idk=Options[j]; j++){
        if(idk.value == SelectionBox.value){
            SelectionBox.selectedIndex = j;
            break;
        }
    }

}
//Function to submit the data
function Submit(){
    try{
        //Get the name, link, and image source
        var Name = document.getElementById("Name").value;
        var Link = document.getElementById("Link").value;
        var Image = document.getElementById("Image").value;
        //Get stats stuff
        var Rating = document.getElementById("Rating").value;
        var ActiveTime = document.getElementById("ActiveTime").value;
        var PassiveTime = document.getElementById("PassiveTime").value;
        var People = document.getElementById("People").value;

        //Get the data for the Pantry table
        var PantryTable = [];
        //Get the main ingredients
        for (var Id = 1; Id <= MainId; Id++) {
            var ItemId = "Main"+Id;
            try {
                var Name1 = document.getElementById("Main"+Id+"Name1").value;
                var Name2 = document.getElementById("Main"+Id+"Name2").value;
                var Name3 = document.getElementById("Main"+Id+"Name3").value;                
                PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
              }
              catch(err) {}
        }
        //Get the support ingredients
        for (var Id = 1; Id <= SupportId; Id++) {
            var ItemId = "Support"+Id;
            try {
                var Name1 = document.getElementById("Support"+Id+"Name1").value;
                var Name2 = document.getElementById("Support"+Id+"Name2").value;
                var Name3 = document.getElementById("Support"+Id+"Name3").value;
                PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
            }
            catch(err) {}
        }
        //Get the spices ingredients
        for (var Id = 1; Id <= SpicesId; Id++) {
            var ItemId = "Spices"+Id;
            try {
                var Name1 = document.getElementById("Spices"+Id+"Name1").value;
                var Name2 = document.getElementById("Spices"+Id+"Name2").value;
                var Name3 = document.getElementById("Spices"+Id+"Name3").value;
                PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
            }
            catch(err) {}
        }
        //Get the garnish ingredients
        for (var Id = 1; Id <= GarnishId; Id++) {
            var ItemId = "Garnish"+Id;
            try {
                var Name1 = document.getElementById("Garnish"+Id+"Name1").value;
                var Name2 = document.getElementById("Garnish"+Id+"Name2").value;
                var Name3 = document.getElementById("Garnish"+Id+"Name3").value;
                PantryTable.push(new PantryObj(ItemId,Name1,Name2,Name3));
            }
            catch(err) {}
        }

        //Get the data for the Ingredient table
        var IngredientTable = [];
        //Get the ingredient information in each step
        for (var Step = 1; Step<Steps.Ids.length; Step++){
            //For each ingredient in a step
            for (var Id = 1; Id <= Steps.Ids[Step]; Id++) {
                //Parse the ingredient
                var StepItemId = "SelectStep"+Step+"Item"+Id;
                try {
                    //Variables
                    var ItemId, Category, Quantity, Unit;
                    //Get the ItemId information
                    ItemId = document.getElementById(StepItemId).value; //Suport1

                    if(ItemId.substring(0, 4) === 'Main'){
                        Category = 1;
                    } else if(ItemId.substring(0, 6) === 'Spices'){
                        Category = 3;
                    } else if(ItemId.substring(0, 7) === 'Support'){
                        Category = 2;
                    } else {
                        Category = 4;
                    }
                    Quantity = document.getElementById(ItemId+"Quantity").value;
                    Unit = document.getElementById(ItemId+"Unit").value;

                    var ParsedIngredient = new Object;
                    ParsedIngredient.ItemId = ItemId;
                    ParsedIngredient.Category = Category;
                    ParsedIngredient.Quantity = Quantity;
                    ParsedIngredient.Unit = Unit;
                    ParsedIngredient.Step = Step;
                    ParsedIngredient.Prep = 0;
                    IngredientTable.push(ParsedIngredient);
                }
                catch(err) {}
            }
        }
        //Mark the prep ingredients
        for (var Id = 1; Id <= Steps.Ids[0]; Id++) {
            var StepItemId = "SelectStep0Item"+Id;
            var ItemId = document.getElementById(StepItemId).value;
            for (var row = 0; row<IngredientTable.length; row++) {
                if(IngredientTable[row].ItemId === ItemId){
                    IngredientTable[row].Prep = 1;
                    break;
                }
            }
        }
        
        //Get the data for the JSON file
        var Notes = document.getElementById("NotesBox").value;
        var StepDirections = [];
        for (var Step = 0; Step<Steps.Ids.length; Step++){
            try{
                StepDirections.push(document.getElementById("Step"+Step+"Box").value);
            }
            catch(err){}
        }

        //Get the id if there is one
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        var Id = urlParams.get('id');

        //Input validation
        if(!(Rating >= 0 && Rating <= 5)) throw "Submit Validation Failed: Rating is not a number between 0-5";
        if(!(ActiveTime >= 0 && ActiveTime <= 240)) throw "Submit Validation Failed: ActiveTime is not a number between 0-240";
        if(!(PassiveTime >= 0 && PassiveTime <= 10080)) throw "Submit Validation Failed: PassiveTime is not a number between 0-10080";
        if(!(People >= 0 && People <= 10)) throw "Submit Validation Failed: People is not a number between 0-10";

        //Parse the data into a JSON so it may be passed to PHP
        var EditResults = new Object();
        EditResults.Id = Id;
        EditResults.Name = Name;
        EditResults.Link = Link;
        EditResults.Image = Image
        EditResults.Rating = Rating;
        EditResults.ActiveTime = ActiveTime;
        EditResults.PassiveTime = PassiveTime;
        EditResults.People = People;
        EditResults.PantryTable = PantryTable;
        EditResults.IngredientTable = IngredientTable;
        EditResults.Notes = Notes;
        EditResults.StepDirections = StepDirections;

        var jsonEditResults= JSON.stringify(EditResults);

        //Submit the data to server for processing
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
                //var Hello = this.responseText;
                //console.log(Hello);
                //Error handling
            }
        };
        xmlhttp.open("POST","php/submitEdit.php",true);
        xmlhttp.send(jsonEditResults);


		


    }catch(err){
        console.log(err);
        //alert("Hello! I am an alert box!");
    }

    //console.log(product);
}