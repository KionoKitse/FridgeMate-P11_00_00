//Get the hidden connection information
require('dotenv').config();         //Loading settings
const mysql = require('mysql');     //For MySQL
const util = require('util');       //For promises

//Connection configuration
config = {
    host: process.env.host,
    user: process.env.username,
    password: process.env.password,
    database: process.env.database
}

//Function to connect to the database
function makeDb(config) {
    const connection = mysql.createConnection(config);
    return {
        query(sql, args) {
            return util.promisify(connection.query)
                .call(connection, sql, args);
        },
        close() {
            return util.promisify(connection.end).call(connection);
        }
    };
}

//Function to calculate the percentage of a given component
async function ComponentPercent(RecipeId,Category){
    const db = makeDb(config)
    var RecipeTotal = 0;
    var Query1 = ''
    var Query2 = ''
    var Query3 = ''
    var Total = 0
    var CategoryWeight = [75, 20, 3, 2]
    try {
        //Get the INGREDIENT information for the for RecipeId
        Query1 = 'select ITEM_ID from fridgemate_db.INGREDIENT where RECIPE_ID = ' + RecipeId + ' and CATEGORY = ' + (Category+1)
        Query2 = 'select ITEM_ID, STATUS, RECIPE_ID from fridgemate_db.PANTRY where ITEM_ID in ( ' + Query1 + ')'
        var items = await db.query(Query2)
        //console.log(items)

        //Calculate the percentage 
        Total = 0 
        for (const index of items) { //Force process array in sequence
            //Have ingredient
            if(index.STATUS){
                Total++
            }

            //Don't have ingredient
            else{
                //Check if the ingredient is buildable
                var Buildable = false
                if(index.RECIPE_ID != 0){
                    Query1 = 'select ITEM_ID from fridgemate_db.INGREDIENT where RECIPE_ID = ' + index.RECIPE_ID
                    Query2 = 'select ITEM_ID, STATUS, RECIPE_ID from fridgemate_db.PANTRY where ITEM_ID in ( ' + Query1 + ')'
                    var BuildList = await db.query(Query2)
                    //Count the number of ingredients availible
                    var BuildCount = 0
                    for (const index2 of BuildList) {
                        if(index2.STATUS){
                            BuildCount++
                        }
                    }

                    if(BuildCount = BuildList.length){
                        Buildable = true
                        Total++
                    }
                }

                //If ingredient cannot be built check if there is a substitute
                if(!Buildable)
                {
                    //Get all the ITEM_IDs from the groups that contain missing index.RECIPE_ID
                    Query1 = 'select GROUP_ID from fridgemate_db.GROUP where ITEM_ID = ' + index.ITEM_ID
                    Query2 = 'select ITEM_ID from fridgemate_db.GROUP where GROUP_ID in ( ' + Query1 + ')'
                    Query3 = 'select ITEM_ID from fridgemate_db.PANTRY where ITEM_ID in ( ' + Query2 + ') and STATUS = 1'
                    var Subs = await db.query(Query3)
                    //console.log('Subs:')
                    if (Subs.length > 0){
                        Total = Total + 0.5
                    }
                }
            }
        }

        //Calculate the result
        console.log('Ingredient ' + CategoryWeight[Category] + '%: '+Total+'/'+items.length )
        if(items.length>0){
            RecipeTotal = CategoryWeight[Category]*Total/items.length
        }else{
            RecipeTotal =  CategoryWeight[Category]
        }
        return RecipeTotal
		
    } catch (err) {
        console.log(err)
    } finally {
        await db.close();
    }
}

//Function to tabulate the recipe total and update the database
async function UpdatePercent(RecipeId){
    var FullTotal = 0
    //Get the Main, Support, Spices and garnish percentages
    FullTotal += await ComponentPercent(RecipeId,0)
    FullTotal += await ComponentPercent(RecipeId,1)
    FullTotal += await ComponentPercent(RecipeId,2)
    FullTotal += await ComponentPercent(RecipeId,3)
    console.log('FullTotal: '+ FullTotal)

    //Update the recipe percentage
    const db = makeDb(config)
    try{
        var Query1 = 'update fridgemate_db.RECIPE set PERCENT = ' + FullTotal + ' where (RECIPE_ID = ' + RecipeId +')'
        await db.query(Query1)
    } catch (err) {
        console.log(err)
    } finally {
        await db.close();
    }
}

//Function to update all the recipes in the database
async function UpdateAllRecipes(){
    //Get all the recipes
    const db = makeDb(config)
    try{
        var Query1 = 'select RECIPE_ID FROM fridgemate_db.RECIPE '
        var Recipes = await db.query(Query1)
    } catch (err) {
        console.log(err)
    } finally {
        db.close();
    }
    //Update the percentage on each recipe
    for (const item of Recipes) {
        await UpdatePercent(item.RECIPE_ID)
    }
}

UpdateAllRecipes()

/*
***** ROLL THE CREDITS *****
>> MySQL <<
* MySQL running queries synchronously       https://codeburst.io/node-js-mysql-and-async-await-6fb25b01b628
* Running synchronous for loops             https://lavrton.com/javascript-loops-how-to-handle-async-await-6252dd3c795/

***** Thanks everyone! ***** 
 */
