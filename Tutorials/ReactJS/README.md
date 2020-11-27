### ReactJS tutorial
Tutorial: [React Tutorial From Scratch: A Step-by-Step Guide](https://ibaslogic.com/react-tutorial-for-beginners/)
### Notes
 * installing React: npx create-react-app simple-todo-app
 * The index.html in the public folder is similar to the one we created earlier. It also has a div container element where your entire application will appear.
 * The src folder contains the working files. One of them is the index.js which will serve as the entry point to our application. 
 * package.json contains information about your app.
 * npm start command will lunch the development server on port 3000 and a new browser window displaying your application will appear automatically.
 * The files that describe what you are seeing in the frontend live in the src folder
 * A module is just a file that usually contains a class or library of functions
 * First, to create a component in React, you either write it as a class or functional-based. A class component is created using the ES6 class syntax while the functional component is created by writing function.
 * we have the render() method where we return the JSX that is rendered on the screen.
 * It’s a good convention to use UpperCamelCase for the Component file name (i.e TodoContainer.js).
 * props allow a child component to receive data from its parent
 * When this happens, the data that is received in the child component becomes read-only and cannot be changed by the child component. This is because the data is owned by the parent component and can only be changed by the same parent component
 * if a user inputs data directly to the component? That is why we have the state.
 * Think of the state as the data you can store to a specific component. This data is owned and can only be updated by the component holding it. 
 * for every component that will be accessing the state data, you will need to declare the state object in the file of their closest common parent.
 * To add a state in a class component, we simply create a state object with key-value pair. The value can be of any data type.
 * you can use a valid JavaScript expression inside the JSX through curly braces, {}
 * In React, we make use of the map() method which is a higher-order function to do this iteration.
 * Always remember, with props, we can access state data at different levels of the component hierarchy. This is called prop drilling. And it has to do with manually getting data from component A down to component B through the props. Where component A is the parent of B
 * Warning: Each child in a list should have a unique "key" prop. -> <li key={todo.id}>{todo.title}</li>
 * "const Header = () => {" is the same as "function Header() {"
 * To make the input field controllable, the input data (in this case, toggling of the checkbox) has to be handled by the component state and not the browser DOM.

### References