const fs = require('fs'); //For writting to file

//Data from input
var id = 1;
var name = "Butternut Squash Carbonara";
var url = "//www.veganricha.com/vegan-butternut-squash-carbonara/#wprm-recipe-container-33880";
var image = "https://www.veganricha.com/wp-content/uploads/2020/11/butternut-squash-carbonara-10-150x150.jpg" //Probably local storage
var people = 2;
var active = 1.15;
var passive = 0;
var rating = 3;
var notes = "";
var steps = [
    "Group 1<br> * Finely chop onions<br> * Mince garlic<br>Group 2<br> * cube butternut squash<br>Group 3<br> * Cube the tofu",
    "Add oil to a skillet over medium heat, add in the onion and garlic and cook until translucent.",
    "Add in the sage and pepper flakes and mix in. Add in the butternut squash and cook until the butternut squash starts to get golden on some edges. This may not happen if you're using frozen butternut squash.",
    "Add in the rest of the ingredients under sauce, and cover and mix well. Then cover and cook for 10-12 minutes or until the butternut squash is tender to preference.<br>Take off heat and let the mixture cool a bit before adding to a blender and blending until smooth.",
    "Meanwhile make your fettuccine according to instructions on the package and cook until al dente, then combine the sauce to the fettuccine in a bowl, or you can also add it to a skillet to reheat, and cook it a little bit more. This helps thicken the sauce and also melds the flavors together",
    "Meanwhile, make the smoky tofu by combining the cubed tofu with the oil, soy sauce, smoked paprika, garlic, and maple syrup in a bowl to coat really well.",
    "Spread out the tofu on a parchment lined baking sheet. Spread it out so that most of the cubes are not touching each other. Preheat the oven to 425 F (218 c ) then add the baking sheet to the oven when it's heated up. Bake for 10 minutes, then move it around, and then bake for another few minutes, and then keep an eye on the tofu. Take it out once the pieces start to get dry-ish and crunchy. The tofu can burn easily, so keep an eye after 14-15 minute mark. Serve the tofu on top of the butternut squash fettuccine."
];

//Create object with inputs
var Recipe = {
    Recipe_ID: id,
    Name: name,
    Link: url,
    Image: image,
    People: people,
    ActiveTime: active,
    PassiveTime: passive,
    Rating: rating,
    Notes: notes,
    Steps: steps
}

//Convert object to JSON string
var JsonText = JSON.stringify(Recipe);

//Write JSON string to a file
fs.writeFile( './RecipeFiles/' + Recipe.Recipe_ID + '.json', JsonText, (err) => {
    if (err) {
        throw err;
    }
    console.log("Recipe saved");
});

//Read the JSON file
fs.readFile('./RecipeFiles/1.json', (err, JsonInput) => {
    if (err) {
        console.log("Error reading file from disk:", err)
        return
    }
    try {

        const ReadRecipe = JSON.parse(JsonInput);
        console.log(ReadRecipe.Name);
    } catch(err) {
        console.log('Error parsing JSON string:', err)
    }
})



