<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
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
Route::post('/update-label/{label}', [CardController::class, 'updateLabel']);
