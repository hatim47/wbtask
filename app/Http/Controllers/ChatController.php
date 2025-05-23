<?php

namespace App\Http\Controllers;



use App\Logic\BoardLogic;

use App\Logic\FileLogic;

use App\Logic\TeamLogic;

use App\Logic\UserLogic;

use App\Models\Team;

use App\Models\User;

use App\Models\UserTeam;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class ChatController extends Controller
{
    //
   public function __construct(

        protected TeamLogic $teamLogic,

        protected FileLogic $fileLogic,

        protected UserLogic $userLogic,

    ) {

    }
      public function index()
    {

        $user = User::find(Auth::user()->id);

        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);

        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);



        return view("chats")

            ->with("teams", $teams)

            ->with("patterns", TeamLogic::PATTERN)

            ->with("invites", $invites);
        // return view("chats");
    }
}
