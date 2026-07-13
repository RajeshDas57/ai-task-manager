<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Task Manager</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto py-10"
     x-data="taskManager()"
     x-init="loadTasks()">

<script>

function taskManager(){

    return{

        tasks:[],

        search:'',
        
        filter:'All',

        title:'',

        description:'',

        priority:'Medium',

        due_date:'',

        suggestion:'',

        editId:null,

        loadTasks(){

            fetch('/tasks')

            .then(res=>res.json())

            .then(data=>{

                this.tasks=data;

            });

        },

        resetForm(){

            this.editId=null;

            this.title='';

            this.description='';

            this.priority='Medium';

            this.due_date='';

        },

        addTask(){

            if(this.editId){

                this.updateTask();

                return;

            }

            fetch('/tasks',{

                method:'POST',

                headers:{

                    'Content-Type':'application/json',

                    'Accept':'application/json',

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                },

                body:JSON.stringify({

                    title:this.title,

                    description:this.description,

                    priority:this.priority,

                    due_date:this.due_date

                })

            })

            .then(res=>res.json())

            .then(data=>{

                this.tasks.unshift(data);

                this.resetForm();

            });

        },

        updateTask(){

            fetch('/tasks/'+this.editId,{

                method:'PUT',

                headers:{

                    'Content-Type':'application/json',

                    'Accept':'application/json',

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                },

                body:JSON.stringify({

                    title:this.title,

                    description:this.description,

                    priority:this.priority,

                    due_date:this.due_date

                })

            })

            .then(res=>res.json())

            .then(data=>{

                let index=this.tasks.findIndex(t=>t.id==this.editId);

                this.tasks[index]=data;

                this.resetForm();

            });

        },
                deleteTask(id){

            fetch('/tasks/' + id,{

                method:'DELETE',

                headers:{

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                }

            })

            .then(()=>{

                this.tasks=this.tasks.filter(t=>t.id!=id);

            });

        },

        toggleTask(id){

            fetch('/tasks/' + id + '/toggle',{

                method:'PATCH',

                headers:{

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                }

            })

            .then(res=>res.json())

            .then(data=>{

                let index=this.tasks.findIndex(t=>t.id==id);

                this.tasks[index]=data;

            });

        },

        editTask(task){

            this.editId=task.id;

            this.title=task.title;

            this.description=task.description;

            this.priority=task.priority;

            this.due_date=task.due_date
                ? task.due_date.substring(0,10)
                : '';

            window.scrollTo({

                top:0,

                behavior:'smooth'

            });

        },

        aiSuggest(){

            fetch('/tasks/suggest',{

                method:'POST',

                headers:{

                    'Content-Type':'application/json',

                    'Accept':'application/json',

                    'X-CSRF-TOKEN':'{{ csrf_token() }}'

                },

                body:JSON.stringify({

                    topic:this.title

                })

            })

            .then(res=>res.json())

            .then(data=>{

                this.suggestion=data.suggestion;

            });

        }

    }

}

</script>

<h1 class="text-4xl font-bold text-center mb-8">

    🤖 AI Task Manager

</h1>
<!-- Statistics -->

<div class="grid grid-cols-3 gap-4 mb-8">

    <div class="bg-blue-500 text-white rounded-xl p-5 text-center">
        <h2 class="text-lg">Total Tasks</h2>
        <p class="text-3xl font-bold" x-text="tasks.length"></p>
    </div>

    <div class="bg-green-500 text-white rounded-xl p-5 text-center">
        <h2 class="text-lg">Completed</h2>
        <p class="text-3xl font-bold"
           x-text="tasks.filter(t => t.is_completed).length"></p>
    </div>

    <div class="bg-red-500 text-white rounded-xl p-5 text-center">
        <h2 class="text-lg">Pending</h2>
        <p class="text-3xl font-bold"
           x-text="tasks.filter(t => !t.is_completed).length"></p>
    </div>

</div>

<!-- Add Task -->

<div class="bg-white rounded-xl shadow p-6 mb-8">

    <h2 class="text-2xl font-bold mb-4"
        x-text="editId ? 'Edit Task' : 'Add New Task'">
    </h2>

    <div class="grid md:grid-cols-2 gap-4">

        <input
            x-model="title"
            placeholder="Task Title"
            class="border rounded-lg p-3">

        <input
            x-model="description"
            placeholder="Description"
            class="border rounded-lg p-3">

        <select
            x-model="priority"
            class="border rounded-lg p-3">

            <option>High</option>
            <option>Medium</option>
            <option>Low</option>

        </select>

        <input
            type="date"
            x-model="due_date"
            class="border rounded-lg p-3">

    </div>

    <div class="flex gap-3 mt-5">

        <button
            @click="addTask()"
            class="bg-blue-600 text-white px-5 py-3 rounded-lg">

            <span x-text="editId ? 'Update Task' : 'Add Task'"></span>

        </button>

        <button
            @click="aiSuggest()"
            class="bg-purple-600 text-white px-5 py-3 rounded-lg">

            AI Suggest

        </button>

    </div>

    <div
        x-show="suggestion"
        class="bg-purple-100 rounded-lg mt-5 p-4">

        <strong>Suggestion:</strong>

        <p
            class="whitespace-pre-line"
            x-text="suggestion">
        </p>

    </div>

</div>

<!-- Search -->

<div class="bg-white rounded-xl shadow p-6 mb-8">

    <input
        x-model="search"
        type="text"
        placeholder="🔍 Search Task..."
        class="w-full border rounded-lg p-3">
<div class="flex gap-3 mt-4">

    <button
        @click="filter='All'"
        class="px-4 py-2 rounded bg-gray-600 text-white">
        All
    </button>

    <button
        @click="filter='Pending'"
        class="px-4 py-2 rounded bg-red-600 text-white">
        Pending
    </button>

    <button
        @click="filter='Completed'"
        class="px-4 py-2 rounded bg-green-600 text-white">
        Completed
    </button>

</div>
</div>
<!-- Task List -->

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold mb-5">
        My Tasks
    </h2>

    <template x-if="tasks.filter(t =>
    ((filter=='All') ||
     (filter=='Pending' && !t.is_completed) ||
     (filter=='Completed' && t.is_completed))
    &&
    (
        t.title.toLowerCase().includes(search.toLowerCase()) ||
        (t.description &&
         t.description.toLowerCase().includes(search.toLowerCase()))
    )
).length==0">

    <div class="text-center text-gray-500 py-8">
        No Task Found
    </div>

</template>

<template
    x-for="task in tasks.filter(t =>

        ((filter=='All') ||

        (filter=='Pending' && !t.is_completed) ||

        (filter=='Completed' && t.is_completed))

        &&

        (

        t.title.toLowerCase().includes(search.toLowerCase()) ||

        (t.description &&
         t.description.toLowerCase().includes(search.toLowerCase()))

        )

    )"
    :key="task.id">

        <div class="border rounded-xl p-5 mb-4 flex justify-between items-start">

            <div>

                <h3
                    class="text-xl font-bold"
                    :class="{
                        'line-through text-gray-400': task.is_completed
                    }"
                    x-text="task.title">
                </h3>

                <p
                    class="text-gray-600 mt-2"
                    x-text="task.description">
                </p>

                <div class="flex gap-2 mt-3">

                    <span
                        class="px-3 py-1 rounded text-white text-sm"
                        :class="{
                            'bg-red-500': task.priority=='High',
                            'bg-yellow-500': task.priority=='Medium',
                            'bg-green-500': task.priority=='Low'
                        }"
                        x-text="task.priority">
                    </span>

                    <span
                        x-show="task.due_date"
                        class="bg-gray-200 px-3 py-1 rounded text-sm">

                        📅
                        <span x-text="task.due_date ? task.due_date.substring(0,10) : ''"></span>

                    </span>

                </div>

            </div>

            <div class="flex gap-2">

                <button
                    @click="toggleTask(task.id)"
                    class="bg-green-600 text-white px-4 py-2 rounded">

                    <span
                        x-text="task.is_completed ? 'Undo' : 'Complete'">
                    </span>

                </button>

                <button
                    @click="editTask(task)"
                    class="bg-yellow-500 text-white px-4 py-2 rounded">

                    Edit

                </button>

                <button
                    @click="deleteTask(task.id)"
                    class="bg-red-600 text-white px-4 py-2 rounded">

                    Delete

                </button>

            </div>

        </div>

    </template>

</div>

</div>

</body>
</html>