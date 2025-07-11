<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get("/", function () {
    return redirect()->route("login");
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware(["auth", "auth.session"])->post('/upload-attachments', [CardController::class, 'store'])->name('upload.attachments');

Route::middleware("guest")->get("auth/login", [AuthController::class, "showLogin"])->name("login");
Route::middleware("guest")->post("auth/login", [AuthController::class, "doLogin"])->name("doLogin");
Route::middleware("guest")->get("auth/register", [AuthController::class, "showRegister"])->name("register");
Route::middleware("guest")->post("auth/register", [AuthController::class, "doRegister"])->name("doRegister");
Route::middleware("guest")->get("auth/forget", [AuthController::class, "forgetview"])->name("forget");
Route::middleware("guest")->post("auth/forgot", [AuthController::class, "forgot"])->name("forgot");
Route::middleware("guest")->get("auth/reseted", [AuthController::class, "reseted"])->name("reseted");
Route::middleware("guest")->post("auth/reset", [AuthController::class, "reset"])->name("reset");
Route::middleware("guest")->get("auth/make-password", [AuthController::class, "passGen"])->name("passgen");
Route::middleware("guest")->post("auth/reset", [AuthController::class, "passet"])->name("passet");



Route::get("get-comment/{card_id}", [CardController::class, "showComment"])->name("showcomment");


Route::middleware(["auth", "auth.session"])->get("team", [TeamController::class, "showTeams"])->name("home");
Route::middleware(["auth", "auth.session"])->get("chats", [ChatController::class, "index"])->name("chats");
Route::middleware(["auth", "auth.session"])->post("team", [TeamController::class, "createTeam"])->name("doCreateTeam");
Route::middleware(["auth", "auth.session"])->get("team/search", [TeamController::class, "search"])->name("searchTeam");
Route::middleware(["auth", "auth.session", ])->get("team/{team_id}/invite/accept/{user_id}", [TeamController::class, "acceptInvite"])->name("acceptTeamInvite");
Route::middleware(["auth", "auth.session", ])->get("team/{team_id}/invite/reject/{user_id}", [TeamController::class, "rejectInvite"])->name("rejectTeamInvite");
Route::middleware(["auth", "auth.session", ])->get("team/{team_id}/invite/{user_id}", [TeamController::class, "getInvite"])->name("getInvite");
Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/deletesa", [TeamController::class, "deleteTeam"])->name("doDeleteTeam");
Route::middleware(["auth", "auth.session"])->post("team/delete", [TeamController::class, "deleteTeamSec"])->name("doDeleteTeamSec");
Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/leave", [TeamController::class, "leaveTeam"])->name("doLeaveTeam");
Route::middleware(["auth", "auth.session"])->get("user/add/member", [TeamController::class, "allmembera"])->name("allmember");
Route::middleware(["auth", "auth.session" ])->post("team/invite", [TeamController::class, "inviteMemberto"])->name("InviteMemberto");

Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/invite", [TeamController::class, "inviteMembers"])->name("doInviteMembers");
Route::middleware(["auth", "auth.session", "userInTeam"])->get("team/{team_id}board/search", [TeamController::class, "searchBoard"])->name("searchBoard");
Route::middleware(["auth", "auth.session"])->post("team/user/add", [CardController::class, "AddTeamMember"])->name("AddTeamMember");

Route::middleware(["auth", "auth.session"])->post("lable/card/add", [CardController::class, "Addlable"])->name("tasks.store");
Route::middleware(["auth", "auth.session"])->get("lable/card/{id}", [CardController::class, "lableList"])->name("lable.List");
Route::middleware(["auth", "auth.session"])->post("update/lable/status", [CardController::class, "updateTaskStatus"])->name("status.lable");
Route::middleware(["auth", "auth.session"])->post("delete/lable", [CardController::class, "destroyLable"])->name("delete.lable");



Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/user/delete", [TeamController::class, "deleteMembers"])->name("deleteTeamMember");
Route::middleware(["auth", "auth.session", "userInTeam"])->get("team/{team_id}/view", [TeamController::class, "showTeam"])->name("viewTeam");
Route::middleware(["auth", "auth.session"])->get("Home/show", [TeamController::class, "showhome"])->name("viewHome");
Route::middleware(["auth", "auth.session","userInTeam"])->get("workspace/{team_id}", [TeamController::class, "workspace"])->name("viewWorkspace");


Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/update/profilesa", [TeamController::class, "updateData"])->name("doTeamDataUpdate");
Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/update/profile", [TeamController::class, "updateDataSec"])->name("doTeamDataUpdateSec");
Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}update/picture", [TeamController::class, "updateImage"])->name("doChangeTeamImage");

Route::middleware(["auth", "auth.session", "userInTeam"])->post("team/{team_id}/board", [BoardController::class, "createBoard"])->name("createBoard");

Route::middleware(["auth", "auth.session", "boardAccess"])->post('team/{team_id}/board/{board_id}/invite', [BoardController::class, 'inviteUser'])->name('invite.user');
Route::middleware(["auth", "auth.session", "boardAccess"])->get("team/{team_id}/board/{board_id}", [BoardController::class, "showBoard"])->name("board");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/delete", [BoardController::class, "deleteBoard"])->name("deleteBoard");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/remove", [BoardController::class, "removeMember"])->name("removeMember");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}", [BoardController::class, "updateBoard"])->name("updateBoard");
Route::middleware(["auth", "auth.session", "boardAccess"])->get("team/{team_id}/board/{board_id}/data", [BoardController::class, "getData"])->name("boardJson");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/column", [BoardController::class, "addColumn"])->name("addCol");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/column/{column_id}/card", [BoardController::class, "addCard"])->name("addCard");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/column/reorder", [BoardController::class, "reorderCol"])->name("reorderCol");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/column/update", [BoardController::class, "updateCol"])->name("updateCol");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/column/delete", [BoardController::class, "deleteCol"])->name("deleteCol");
Route::middleware(["auth", "auth.session", "boardAccess"])->post("team/{team_id}/board/{board_id}/card/reorder", [BoardController::class, "reorderCard"])->name("reorderCard");


Route::middleware(["auth", "auth.session"])->post("card/update-notify", [CardController::class, "notify"])->name("notify");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->get("team/{team_id}/board/{board_id}/card/{card_id}/view", [CardController::class, "showCard"])->name("viewCard");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("team/{team_id}/board/{board_id}/card/{card_id}/assign", [CardController::class, "assignCard"])->name("assignCard");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("team/{team_id}/board/{board_id}/card/{card_id}/assignself", [CardController::class, "assignSelf"])->name("assignSelf");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("/board/{board_id}/card/{card_id}/leave", [CardController::class, "leaveCard"])->name("leaveCard");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("team/{team_id}/board/{board_id}/card/{card_id}/delete", [CardController::class, "deleteCard"])->name("deleteCard");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("team/{team_id}/board/{board_id}/card/{card_id}/update", [CardController::class, "updateCard"])->name("updateCard");
Route::middleware(["auth", "auth.session", "boardAccess","cardExist"])->post("team/{team_id}/board/{board_id}/card/{card_id}/comment", [CardController::class, "addComment"])->name("commentCard");

Route::middleware(["auth", "auth.session"])->get("user/member/{card_id}/{team_id}/{board_id}", [CardController::class, "viewmember"])->name("viewmember");
Route::middleware(["auth", "auth.session"])->get("members/{team_id}", [BoardController::class, "viewmember"])->name("boardmember");
Route::middleware(["auth", "auth.session"])->get("user/setting", [UserController::class, "showSetting"])->name("setting");
Route::middleware(["auth", "auth.session"])->get("user/logout", [UserController::class, "logout"])->name("doLogout");
Route::middleware(["auth", "auth.session"])->post("user/deactivate", [UserController::class, "deactivate"])->name("doDeactivateUser");
Route::middleware(["auth", "auth.session"])->post("user/update/profile", [UserController::class, "updateData"])->name("doUserDataUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/password", [UserController::class, "updatePassword"])->name("doUserPasswordUpdate");
Route::middleware(["auth", "auth.session"])->post("user/update/image", [UserController::class, "updateImage"])->name("doUserPicturedUpdate");
