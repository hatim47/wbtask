<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BoardController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("team/user/add", [CardController::class, "AddTeamMember"])->name("AddTeamMember");
Route::post("/board/{board_id}/card/{card_id}/leave", [CardController::class, "leaveCard"])->name("leaveCard");
Route::post('/card/{card}/description', [CardController::class, 'updateDescription']);
Route::post('/upload-image', [CardController::class, 'uploadImage']);
Route::post('/upload-attachments', [CardController::class, 'uploadAttachments']);
Route::post('/card/{card}/upload/{upload}', [CardController::class, 'uploadDeleted']);
Route::post('/card/{card}/makeCover/{upload}', [CardController::class, 'MakeCover']);
Route::post('/card/{card}/removeCover/{upload}', [CardController::class, 'removeCover']);
Route::post('/update-label/{label}', [CardController::class, 'updateLabel']);
Route::post('/labels/insert', [CardController::class, 'addLabel']);
Route::post('/labels/{label}', [CardController::class, 'deleteLabel']);
Route::post('/comments', [CardController::class, 'addComments']);
Route::put('/comments/{id}', [CardController::class, 'updateComment']);
Route::post('/comments/{id}/delete', [CardController::class, 'destroyComment']);
Route::get('/notify/{id}/{userId}', [CardController::class, 'shownotification']);
Route::post('/update-card-name', [CardController::class, 'cardnameUpdate']);