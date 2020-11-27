import React from "react"
import TodoItem from "./TodoItem";

class TodosList extends React.Component {
    /*
     * For each item in todos give the props to TodoItem
     * Passing the handleChangeProps for the checkbox
     * Passing the delTodoProps for the delete button
     */
    render() {
        return (
            <div>
                {this.props.todos.map(todo => (
                    <TodoItem
                        key={todo.id}
                        todo={todo}
                        handleChangeProps={this.props.handleChangeProps}
                        delTodoProps={this.props.delTodoProps}
                    />
                ))}
            </div>
        )
    }
}

export default TodosList