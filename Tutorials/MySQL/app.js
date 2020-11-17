//Get the hidden connection information
require('dotenv').config();
const mysql = require('mysql');

//Create the connection
var connection = mysql.createConnection({
    host: process.env.host,
    user: process.env.username,
    password: process.env.password,
    database: process.env.database
});

//Establish the connection
connection.connect((err) => {
    if (err) {
        console.log('Error connecting to Db');
        return;
    }
    console.log('Connection established');
});

//Get data
connection.query('SELECT * FROM RECIPE', (err, rows) => {
    if (err) throw err;

    console.log('Data received from Db:');
    console.log(rows);

    //Print out the results
    rows.forEach((row) => {
        console.log(`RECIPE_ID: ${row.RECIPE_ID} is ${row.NAME}`);
    });
});

//Add data
const NewRecipe = {
    //RECIPE_ID: 5,
    NAME: 'Exmouth',
    PEOPLE: 1,
    ACTIVE: 1,
    PASIVE: 1,
    RATING: 1,
    PERCENT: 1,
    INGREDIANT: 1
};
var RemoveId = 0;
connection.query('INSERT INTO RECIPE SET ?', NewRecipe, (err, res) => {
    if (err) throw err;

    console.log('Last insert ID:', res.insertId);
    RemoveId = res.insertId;
});
console.log('RemoveId:', RemoveId);
//Update data
connection.query(
    'UPDATE RECIPE SET NAME = ? Where RECIPE_ID = ?',
    ['Leipzig', RemoveId],
    (err, result) => {
      if (err) throw err;
  
      console.log(`Changed ${result.changedRows} row(s)`);
    }
  );

//Remove data
connection.query(
  'DELETE FROM RECIPE WHERE RECIPE_ID = ?', [RemoveId], (err, result) => {
    if (err) throw err;

    console.log(`Deleted ${result.affectedRows} row(s)`);
  }
);


//Exit in case of error
connection.end((err) => {
    // The connection is terminated gracefully
    // Ensures all remaining queries are executed
    // Then sends a quit packet to the MySQL server.
});

