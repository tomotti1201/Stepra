
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupTaskController;
use App\Http\Controllers\GroupmemberController;





Route::post('/signup', [SignupController::class, 'signup']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/current-user', [LoginController::class, 'currentUser']);
Route::post('/passwordReset', [PasswordResetController::class, 'passwordReset']);
Route::get('/home/tasks', [HomeController::class, 'todayTasks']);

Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{id}', [TaskController::class, 'show']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
Route::get('/schedules/monthly', [ScheduleController::class, 'getMonthlySchedules']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::post('/schedules', [ScheduleController::class, 'store']);
Route::get('/schedules/{id}', [ScheduleController::class, 'show']);
Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);
Route::get('/groups', [GroupController::class, 'index']);
Route::post('/groups', [GroupController::class, 'store']);
Route::post('/groups/join', [GroupController::class, 'join']);
Route::put('/groups/{id}', [GroupController::class, 'update']);
Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
Route::get('/grouptasks', [GroupTaskController::class, 'index']);
Route::post('/grouptasks', [GroupTaskController::class, 'store']);
Route::delete('/grouptasks/{id}', [GroupTaskController::class, 'destroy']);
Route::get('/groupmembers', [GroupmemberController::class, 'index']);
Route::get('/groupmembers/{id}', [GroupmemberController::class, 'show']);
Route::delete('/groupmembers/{id}', [GroupmemberController::class, 'destroy']);

?>
