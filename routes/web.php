<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('/', [TaskController::class,'index']);


// API Task Load
Route::get('/tasks', [TaskController::class,'apiIndex']);


// Add Task
Route::post('/tasks', [TaskController::class,'store']);


// Delete Task
Route::delete('/tasks/{task}', [TaskController::class,'destroy']);


// Complete / Undo
Route::patch('/tasks/{task}/toggle', [TaskController::class,'toggle']);


// AI Suggest
Route::post('/tasks/suggest', [TaskController::class,'suggest']);


// Edit
Route::get('/task/{task}/edit', [TaskController::class,'edit']);


// Update
Route::put('/tasks/{task}', [TaskController::class,'update']);