<?php



namespace App\Http\Controllers;



use App\Logic\BoardLogic;

use App\Logic\FileLogic;

use App\Logic\TeamLogic;

use App\Logic\UserLogic;

use App\Models\Team;

use App\Models\User;
use App\Models\TeamInvitation;
use App\Models\BoardUser;
use App\Models\UserTeam;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Response as HttpResponse;



class TeamController extends Controller

{

    public function __construct(

        protected TeamLogic $teamLogic,

        protected FileLogic $fileLogic,

        protected UserLogic $userLogic,

    ) {

    }



    public function createTeam(Request $request)

    {

        $request->validate([

            "team_name" => "required|min:5|max:20",

            "team_description" => "required|min:5|max:90",

            "team_pattern" => 'required',

        ]);



        $createdTeam = $this->teamLogic->createTeam(

            Auth::user()->id,

            $request->team_name,

            $request->team_description,

            $request->team_pattern,

        );



        return redirect()->route("viewTeam", ['team_id' => $createdTeam->id]);

    }

    public function updateDataSec(Request $request)

    {

        $request->validate([

            "team_id" => "required|integer",

            "team_name" => "required|min:5|max:20",

            "team_description" => 'required|min:8|max:90',

            "team_pattern" => 'required',

        ]);

        $team_id = intval($request->team_id);

        $selectedTeam = Team::find($team_id);



        if ($selectedTeam == null) {

            return redirect()->route("home")->withErrors("This team is alredy deleted please contact team owner");

        }



        $selectedTeam->name = $request->team_name;

        $selectedTeam->description = $request->team_description;

        $selectedTeam->pattern = $request->team_pattern;

        $selectedTeam->save();

        

        return response()->json([

            'success' => true,

            'message' => "Success\nEdit successfully applied!",

        ]);



         //return redirect()->back()->with("notif", ["Success\nEdit succesfully applied!"]);

    }

    public function updateData(Request $request)

    {

        $request->validate([

            "team_id" => "required|integer",

            "team_name" => "required|min:5|max:20",

            "team_description" => 'required|min:8|max:90',

            "team_pattern" => 'required',

        ]);

        $team_id = intval($request->team_id);

        $selectedTeam = Team::find($team_id);



        if ($selectedTeam == null) {

            return redirect()->route("home")->withErrors("This team is alredy deleted please contact team owner");

        }



        $selectedTeam->name = $request->team_name;

        $selectedTeam->description = $request->team_description;

        $selectedTeam->pattern = $request->team_pattern;

        $selectedTeam->save();

        

    return redirect()->back()->with("notif", ["Success\nEdit succesfully applied!"]);

    }



    public function updateImage(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'image' => "required|mimes:jpg,jpeg,png|max:10240",

            'team_id' => "required"

        ]);



        $registeredTeam = Team::find(intval($request->team_id));



        if ($validator->fails() || $registeredTeam == null) {

            return response()->json($validator->messages(), HttpResponse::HTTP_BAD_REQUEST);

        }



        $this->fileLogic->storeTeamImage($registeredTeam->id, $request, "image");

        return response()->json(["message" => "success"]);

    }



    public function showTeams()

    {

        $user = User::find(Auth::user()->id);

        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"]);

        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);



        return view("teams")

            ->with("teams", $teams)

            ->with("patterns", TeamLogic::PATTERN)

            ->with("invites", $invites);

    }


  public function workspace($team_id)
{

    
  $team_id = intval($team_id);
  $user = User::find(Auth::user()->id);
        $selected_team = Team::find($team_id);
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMemberr($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);

        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"]);
           //dd($invites);

    $assign_board = $user->boards()
    ->wherePivotIn('status', ['member', 'owner'])
    ->get();
       $team = UserTeam::where('user_id', Auth::user()->id)->whereNotIn("status", ["pending"])->get();
            // dd($team);
        // $team_id = intval($team_id);
        $teams_info = [];
foreach ($team as $userTeam) {
        $selected_teamm = Team::find($userTeam->team_id);

        if ($selected_teamm) {
        $team_ownerr = $this->teamLogic->getTeamOwner($selected_teamm->id);
        $team_memberss = $this->teamLogic->getTeamMember($selected_teamm->id);
        $team_boardss = $this->teamLogic->getBoards($selected_teamm->id);
  $teams_info[] = [
            'team' => $selected_teamm,
            'owner' => $team_ownerr,
            'members' => $team_memberss,
            'boards' => $team_boardss,
        ];
}
}
 return view("workspace") ->with("team", $selected_team)
            ->with("owner", $team_owner) 
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("members", $team_members)
            ->with("teams_info", $teams_info)
            ->with("invites", $invites)
            ->with("assign_board", $assign_board)
            ->with("boards", $team_boards);
          

}

  public function showhome()
    {
    $team = UserTeam::where('user_id', Auth::user()->id)->whereNotIn("status", ["pending"])->get();
        // dd($team);
        // $team_id = intval($team_id);
        $teams_info = [];
foreach ($team as $userTeam){
        $selected_team = Team::find($userTeam->team_id);
        if ($selected_team) {
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);
  $teams_info[] = [
            'team' => $selected_team,
            'owner' => $team_owner,
            'members' => $team_members,
            'boards' => $team_boards,
        ];
}
}
 $user = User::find(Auth::user()->id);
    $assign_board = $user->boards()
    ->wherePivotIn('status', ['member', 'owner'])
    ->get();
        return view("allteam")
            ->with("teams_info", $teams_info)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("assign_board", $assign_board);

    }

    public function showTeam($team_id)
    {

        $team_id = intval($team_id);
        $selected_team = Team::find($team_id);
       
        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);
        $team_members = $this->teamLogic->getTeamMember($selected_team->id);
        $team_boards = $this->teamLogic->getBoards($selected_team->id);
        $user = User::find(Auth::user()->id);
    $assign_board = $user->boards()
    ->wherePivotIn('status', ['member', 'owner'])
    ->get();
         //dd($assign_board);




        
        return view("team")
            ->with("team", $selected_team)
            ->with("owner", $team_owner) 
            ->with("assign_board", $assign_board)
            ->with("backgrounds", BoardLogic::PATTERN)
            ->with("boards", $team_boards);
    }



    

    public function allmember(Request $request)

    {

    }

    public function search(Request $request)
    {

        $validator = Validator::make($request->all(), ["team_name" => "required"]);

        if ($validator->fails()) {

            return redirect()->route("home");

        }



        $request->session()->flash("__old_team_name", $request->team_name);

        $user = User::find(Auth::user()->id);

        $teams = $this->teamLogic->getUserTeams($user->id, ["Member", "Owner"], $request->team_name);

        $invites = $this->teamLogic->getUserTeams($user->id, ["Pending"], $request->team_name);

        return view("teams")

            ->with("teams", $teams)

            ->with("patterns", TeamLogic::PATTERN)

            ->with("invites", $invites);

    }

    

    public function allmembera()

    {   $team = Team::with('users')

        ->get();

        //dd($team);



            return view("Allmember")

            ->with("team", $team);

    }

    public function searchBoard(Request $request, $team_id)

    {

        $request->validate([

            "team_id" => "required|integer",

            "user_id" => "required|integer",

            "board_name" => "required",

        ]);



        $team_id = intval($request->team_id);



        $request->session()->flash("__old_board_name", $request->board_name);

        $team_id = intval($request->team_id);

        $selected_team = Team::find($team_id);

        $team_owner = $this->teamLogic->getTeamOwner($selected_team->id);

        $team_members = $this->teamLogic->getTeamMember($selected_team->id);

        $team_boards = $this->teamLogic->getBoards($selected_team->id, $request->board_name);



        return view("team")

            ->with("team", $selected_team)

            ->with("owner", $team_owner)

            ->with("members", $team_members)

            ->with("patterns", TeamLogic::PATTERN)

            ->with("backgrounds", BoardLogic::PATTERN)

            ->with("boards", $team_boards);

    }



    public function getInvite($team_id, $user_id)

    {

        $user_id = intval($user_id);

        $team_id = intval($team_id);



        $owner = $this->teamLogic->getTeamOwner($team_id);

        $team = Team::find($team_id);

        $owner_initials = $this->userLogic->getInitials($owner->name);

        $team_initials = $this->userLogic->getInitials($team->name);



        return response()->json([

            "owner_name" => $owner->name,

            "owner_initial" => $owner_initials,

            "owner_image" => $owner->image_path,

            "team_name" => $team->name,

            "team_initial" => $team_initials,

            "team_description" => $team->description,

            "team_image" => $team->image_path,

            "team_pattern" => $team->pattern,

            "accept_url" => route('acceptTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),

            "reject_url" => route('rejectTeamInvite', ["user_id" => $user_id, "team_id" => $team_id]),

        ]);

    }



    public function acceptInvite($team_id, $user_id)

    {

        $user_id = intval($user_id);

        $team_id = intval($team_id);



        $userInvite = UserTeam::all()

            ->where("user_id", $user_id)

            ->where("team_id", $team_id)

            ->first();



        if ($userInvite == null) {

            return redirect()->back()->with("notif", ["Error\nThe invite not found, it is either canceled or expired contacet the team owner."]);

        }



        $userInvite->status = "Member";

        $userInvite->save();



        return redirect()->back()->with("notif", ["Success\nInvite is accepted"]);

    }



    public function rejectInvite($user_id, $team_id)

    {

        $user_id = intval($user_id);

        $team_id = intval($team_id);



        $userInvite = UserTeam::all()

            ->where("user_id", $user_id)

            ->where("team_id", $team_id)

            ->first();



        if ($userInvite == null) {

            return redirect()->back();

        }



        $userInvite->delete();



        return redirect()->back()->with("notif", ["Success\nInvite is rejected"]);

    }



    public function deleteMembers(Request $request)

    {

        $team_id = intval($request->team_id);

        $this->teamLogic->deleteMembers($team_id, $request->emails);

        return redirect()->back()->with('notif', ["Success\nMember removed from the team."]);

    }



    public function inviteMembers(Request $request, $team_id)

    {

        $emails = $request->emails;

        $team_id = intval($request->team_id);



        if ($emails == null)

            return redirect()->back();





        foreach ($emails as $email) {

            $user = User::where("email", $email)->first();

            if ($user == null) {

                $link = "http://task.wbsoftech.com/";
                $subject = "Request from TaskVerse";
                $message = "Click to log in: $link"; // Email body
            
                Mail::raw($message, function ($mail) use ($email, $subject) {
                    $mail->to($email)
                         ->subject($subject)
                         ->from('no-reply@task.wbsoftech.com', 'TaskVerse');
                });

                continue; 
            }

            $existingInvite = UserTeam::where('user_id', $user->id)

                ->where('team_id', $team_id)

                ->first();

              

            if ($existingInvite != null) continue;
                
           


            UserTeam::create([

                "user_id" => $user->id,

                "team_id" => $team_id,

                "status" => "Pending"

            ]);

        }



        return redirect()->back()->with('notif', ["Success\nInvite sent, please wait."]);

    }



    public function inviteMemberto(Request $request)
    {

         //dd($request->all());
        $email = $request->get('inv-email');
    $team_id = $request->team_id;
$board_id = $request->board_id ?? "";
    if (empty($email)) {
        return redirect()->back();
    }

    $link = "http://task.wbsoftech.com/";

  

        $user = User::where("email", $email)->first();
 $team = Team::find($team_id);
        // Send invitation email
        $subject = "Request from TaskVerse";
        $message = "you are invited to join the team " . $team->name; // Email body

        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)
                 ->subject($subject)
                 ->from('no-reply@task.wbsoftech.com', 'TaskVerse');
        });

        // If user exists, add to team if not already there
        if ($user) {
            $alreadyInTeam = UserTeam::where('user_id', $user->id)
                                     ->where('team_id', $team_id)
                                     ->exists();

            if (!$alreadyInTeam) {
                UserTeam::create([
                    'user_id' => $user->id,
                    'team_id' => $team_id,
                    'status'  => 'Pending',
                ]);
            }

            // If your logic also includes boards:
            if ($request->board_id) {
               
                // Check if user is already invited to board
                $alreadyInBoard = BoardUser::where('user_id', $user->id)
                                                ->where('board_id', $board_id)
                                                ->exists();
                if (!$alreadyInBoard) {
                    BoardUser::create([
                        'user_id'  => $user->id,
                        'board_id' => $board_id,
                        'status'   => 'pending',
                    ]);
                }
            }
        }
        else {
            // ❗User doesn't exist: store invitation by email only
            $alreadyInvited = TeamInvitation::where('email', $email)
                                            ->exists();



            if (!$alreadyInvited) {
                TeamInvitation::create([
                    'email'    => $email, 
                    'team_id'  => $team_id,
                    'board_id' => $board_id,
                    'status'   => 'Pending',
                ]);
            }
   $subject = "Request from TaskVerse";
        $message = "Click to log in: $link"; // Email body

        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)
                 ->subject($subject)
                 ->from('no-reply@task.wbsoftech.com', 'TaskVerse');
        });


        }
   return redirect()->back()->with('notif', ['Success', 'Invite sent, please wait.']);
}

    public function deleteTeamSec(Request $request)

    {

        $request->validate([

            "team_id" => "required"

        ]);



        $team_id = intval($request->team_id);

        $this->teamLogic->deleteTeam($team_id);



        return redirect()->route("home")->with("notif", ["Deleted\nTeam deleted successfully"]);

    }

    public function deleteTeam(Request $request, $team_id)

    {

        $request->validate([

            "team_id" => "required"

        ]);



        $team_id = intval($request->team_id);

        $this->teamLogic->deleteTeam($team_id);

        return redirect()->route("home")->with("notif", ["Deleted\nTeam deleted successfully"]);

    }



    public function leaveTeam(Request $request, $team_id)

    {

        $request->validate([

            "team_id" => "required",

        ]);



        $user_email  = Auth::user()->email;

        $team_id = intval($request->team_id);

        $this->teamLogic->deleteMembers($team_id, [$user_email]);

        return redirect()->route("home")->with("notif", ["Leave\nSuccessfully left team..."]);

    }

}

