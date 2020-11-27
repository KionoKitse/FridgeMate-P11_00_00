import React from "react"
import TodosList from "./TodosList";
import Header from "./Header"
import InputTodo from "./InputTodo"

class TodoContainer extends React.Component {
    //Initial data to start with
    state = {
        todos: [
            {
                id: 1,
                title: "Setup development environment",
                completed: true
            },
            {
                id: 2,
                title: "Develop website and add content",
                completed: false
            },
            {
                id: 3,
                title: "Deploy to live server",
                completed: false
            }
        ]
    };

    //Changing the check box status by using a copy of the previous state and id
    handleChange = (id) => {
        this.setState(prevState => ({
            todos: prevState.todos.map((todo) => {
                if (todo.id === id) {
                    todo.completed = !todo.completed;
                }
                return todo;
            }),
        }));
    };

    //Removing all the items from the list where the id = id
    delTodo = id =>{
        this.setState({    
            todos: [      
                ...this.state.todos.filter(todo => {        
                    return todo.id !== id;      
                })    
            ]  
        });
    };

    /*
     * Render the header
     * Render the TodoList
     */
    render() {
        return (
            <div>
                <Header />
                <InputTodo />
                <TodosList
                    todos={this.state.todos}
                    handleChangeProps={this.handleChange}
                    delTodoProps = {this.delTodo}
                />
            </div>
        )
    }
}
export default TodoContainer