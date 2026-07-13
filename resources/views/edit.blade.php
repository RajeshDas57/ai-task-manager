<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>

    <style>
        body{
            font-family: Arial;
            background:#f2f2f2;
            padding:30px;
        }

        .box{
            max-width:600px;
            margin:auto;
            background:white;
            padding:25px;
            border-radius:10px;
        }

        input, textarea, select{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border-radius:5px;
            border:1px solid #ccc;
        }

        button{
            background:#2563eb;
            color:white;
            padding:10px 20px;
            border:none;
            border-radius:5px;
            cursor:pointer;
        }

        a{
            text-decoration:none;
            color:#555;
        }
    </style>
</head>

<body>

<div class="box">

<h2>Edit Task</h2>

<form action="/task/{{ $task->id }}" method="POST">

    @csrf
    @method('PUT')


    <label>Title</label>
    <input 
        type="text" 
        name="title" 
        value="{{ $task->title }}"
    >


    <label>Description</label>
    <textarea name="description">{{ $task->description }}</textarea>


    <label>Priority</label>

    <select name="priority">

        <option value="High" 
        {{ $task->priority=="High" ? 'selected':'' }}>
        High
        </option>


        <option value="Medium" 
        {{ $task->priority=="Medium" ? 'selected':'' }}>
        Medium
        </option>


        <option value="Low" 
        {{ $task->priority=="Low" ? 'selected':'' }}>
        Low
        </option>

    </select>


    <label>Due Date</label>

    <input 
        type="date" 
        name="due_date"
        value="{{ $task->due_date }}"
    >


    <button type="submit">
        Update Task
    </button>

</form>

<br>

<a href="/">← Back</a>

</div>

</body>
</html>