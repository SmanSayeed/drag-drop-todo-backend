# Drag and Drop Todo Application

## Overview
A responsive task management application with a modern UI, featuring drag-and-drop functionality, inline editing, and optimistic updates. Built with React.js and Laravel backend.

### 🔗 GitHub Links
- **Frontend:** [todo-frontend](https://github.com/SmanSayeed/todo-frontend)
- **Backend:** [drag-drop-todo-backend](https://github.com/SmanSayeed/drag-drop-todo-backend)

### 🚀 Live Demo
[Try it here](https://todo-frontend-ioho.vercel.app/)

## 🛠 Technologies

### Frontend
- **React JS** (v19.0.0)
- **Redux Toolkit** for state management
- **Tailwind CSS** for styling
- **Axios** for API communication
- **React Router** for navigation

### Backend
- **Laravel** (v10.10)
- **MySQL** database
- **JWT authentication**
- **RESTful API architecture**

## ✨ Features

### User Management
- User registration and authentication
- Secure API access with token-based authentication

### Task Management
- Create, read, update, and delete tasks
- Inline editing for task details (name, description, due date)
- Task filtering and sorting (by status, due date, creation date)
- Debounced search functionality

### Kanban Board
- Drag-and-drop interface for task status management
- Visual organization of tasks into "To Do", "In Progress", and "Done" columns
- Automatic status updates when tasks are moved between columns
- Task count per status

## ⚡ Technical Highlights

### Performance Optimizations
- Optimistic UI updates (interface updates before API responses)
- Component memoization with `React.memo`
- Preventing unnecessary re-renders with `useCallback`
- Efficient state management with Redux Toolkit

### Code Quality
- Reusable UI components architecture
- Custom hooks for shared functionality
- Utility functions and constants for maintainability
- Organized project structure

### Responsive Design
- Mobile-friendly interface
- Tailwind CSS for adaptive layouts

## 🏗 Installation and Setup

### Frontend Setup
```bash
# Clone the repository
git clone https://github.com/SmanSayeed/todo-frontend.git

# Navigate to the project directory
cd todo-frontend

# Install dependencies
npm install

# Start the development server
npm run dev
```

### Backend Setup
```bash
# Clone the repository
git clone https://github.com/SmanSayeed/drag-drop-todo-backend.git

# Navigate to the project directory
cd drag-drop-todo-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set up your database credentials in the .env file

# Run migrations
php artisan migrate

# Start the server
php artisan serve
```

## 📡 API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - User login
- `GET /api/user` - Get authenticated user profile

### Tasks
- `GET /api/tasks` - Get all tasks (with filtering, sorting, and pagination)
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - Get a specific task
- `PUT /api/tasks/{id}` - Update a task
- `DELETE /api/tasks/{id}` - Delete a task

## 🚀 Future Improvements
- Dark mode toggle
- Task categories/labels
- Task comments and attachments
- User collaboration features
- PWA support for offline functionality
- Real-time updates with WebSockets

## 👨‍💻 Contributors
- **Saadman Sayeed: 786saadman@gmail.com - https://sman.dev**

## 📜 License
This project is licensed under the [MIT License](LICENSE).
