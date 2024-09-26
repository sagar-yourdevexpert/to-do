import React, { useState, useEffect } from 'react';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.headers.post['Content-Type'] = 'application/json';

function App() {
    const [tasks, setTasks] = useState([]);
    const [newTask, setNewTask] = useState('');

    useEffect(() => {
        axios.get('/api/tasks').then((response) => {
            setTasks(response.data);
        });
    }, []);

    const addTask = () => {
        axios.post('/api/tasks', { title: newTask }).then((response) => {
            setTasks([...tasks, response.data]);
            setNewTask('');
        });
    };

    const toggleTask = (task) => {
        axios.put(`/api/tasks/${task.id}`, {
            ...task, completed: !task.completed
        }).then((response) => {
            setTasks(tasks.map(t => t.id === task.id ? response.data : t));
        });
    };

    const deleteTask = (task) => {
        axios.delete(`/api/tasks/${task.id}`).then(() => {
            setTasks(tasks.filter(t => t.id !== task.id));
        });
    };

    return (
        <div>
            <h1>Todo List</h1>
            <input
                type="text"
                value={newTask}
                onChange={(e) => setNewTask(e.target.value)}
                placeholder="New task"
            />
            <button onClick={addTask}>Add Task</button>

            <ul>
                {tasks.map((task) => (
                    <li key={task.id}>
                        <span
                            style={{
                                textDecoration: task.completed ? 'line-through' : 'none'
                            }}
                            onClick={() => toggleTask(task)}
                        >
                            {task.title}
                        </span>
                        <button onClick={() => deleteTask(task)}>Delete</button>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default App;
