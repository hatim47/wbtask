<?php

namespace App\Http\Controllers;

use App\Logic\BoardLogic;
use App\Logic\CardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Models\BoardUser;
use App\Models\Upload;
use App\Models\User;
use App\Models\UserTeam;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use App\Events\BoardUpdated;
use Illuminate\Support\Str;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function __construct(
        protected TeamLogic $teamLogic,
        protected BoardLogic $boardLogic,
        protected CardLogic $cardLogic
    ) {
    }

    public function createBoard(Request $request, $team_id)
    {
        $request->validate([
            "team_id" => "required",
            "board_name" => "required",
            "board_pattern" => "required"
        ]);
        $team_id = intval($request->team_id);

        $createdBoard = $this->boardLogic->createBoard(
            $team_id,
            $request->board_name,
            $request->board_pattern,
        );

        if ($createdBoard == null)
            return redirect()->back()->with("notif", ["Error\nFail to create board, please try again"]);
           
        return redirect()->back()->with("notif", ["Success\nBoard created successfully!"]);
    }



    public function addColumn(Request $request, $team_id, $board_id,)
    {
        $request->validate([
            "board_id" => "required",
            "column_name" => "required",
        ]);
        $team_id = intval($team_id);
        $board_id = intval($request->board_id);

        $createdColumn = $this->boardLogic->addColumn($board_id, $request->column_name);

        if ($createdColumn == null)
            return redirect()->back()->with("notif", ["Error\nFail to create board, please try again"]);
          
        return response()->json($createdColumn);
    }

    public function showBoard($team_id, $board_id)
    {
        $board_id = intval($board_id);
        $board = $this->boardLogic->getData($board_id);
        $team = Team::find($board->team_id);
        $teamOwner = $this->boardLogic->getTeamOwner($board_id);
        $team_members = $this->boardLogic->getTeamMember($board_id);
        $user = User::find(Auth::user()->id);
        $assign_board = $user->boards()->wherePivotIn('status', ['member', 'owner'])->get();
// dd($board);
        return view("board")
            ->with("team", $team)
            ->with("owner", $teamOwner)
            ->with("board", $board)
            ->with("members", $team_members)
            ->with("assign_board", $assign_board);
    }
    public function viewmember(Request $request, $team_id)
    {
        $team = Team::find($team_id);
        $teamOwner = $this->teamLogic->getTeamOwner($team_id);
        $team_members = $this->teamLogic->getTeamMember($team_id);
        return view("boardmember")
        ->with("owner", $teamOwner)
        ->with("members", $team_members)
        ->with("team", $team);
    }
    public function updateBoard(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "board_name" => "required",
        ]);

        $board = Board::find(intval($board_id));
        $board->name = $request->board_name;
        $board->save();

        return redirect()->back()->with("notif", ["Success\nBoard is successfully updated!"]);
    }





public function inviteUser(Request $request, $team_id, $board_id)
{
    $request->validate([
        'invitemail' => 'required|email',
        'role' => 'required|in:Member,Owner',
    ]);

    $email = $request->invitemail;
    $role = $request->role;

    $user = User::where('email', $email)->first();

    if ($user) {
        // If user exists, attach to the team
        UserTeam::updateOrCreate([
            'user_id' => $user->id,
            'team_id' => $team_id,
        ], [
            'status' => 'Member',
        ]);


  BoardUser::updateOrCreate([
        'user_id' => $user->id,
        'board_id' => $board_id,
    ], [
        'status' => $role,
    ]);






    } else {
        // If user doesn't exist, send invitation
        $token = Str::uuid();

        TeamInvitation::updateOrCreate(
            ['email' => $email, 'team_id' => $team_id, 'board_id' => $board_id, 'board_role' => $role],
            ['token' => $token, 'status' => 'Pending']
        );

        // Send email with invite link (optional: use a mailable)
        Mail::raw("You’ve been invited to join a board. Click the link to accept: " .
            route('invite.accept', ['token' => $token]),
            function ($message) use ($email) {
                $message->to($email)->subject('You are invited to join a team on Mindr');
            });
    }

    return response()->json(['message' => 'Invitation sent successfully']);
}
    public function addCard(Request $request, $team_id, $board_id, $column_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($column_id);
        $card_name = $request->name;

        $newCard = $this->boardLogic->addCard($column_id, $card_name);
         $this->cardLogic->addUser($newCard->id, Auth::user()->id , "Owner");
        $this->cardLogic->cardAddEvent($newCard->id, Auth::user()->id, "Created card");   

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) { // Loop through each uploaded file
                $imagePath = $image->store('uploads', 'public'); // Store file
                Upload::create([
                    'card_id' => $newCard->id,
                    'file_path' => $imagePath,
                ]);
            }
        } else {
            $imagePath = null; // Define $imagePath even if no image is uploaded
        }
        return response()->json($newCard);
    }
    public function getData($team_id, $board_id)
    {
        $boardData = $this->boardLogic->getData(intval($board_id));
        return response()->json($boardData);
    }
    public function reorderCard(Request $request, $team_id, $board_id)
    {
        $board_id = intval($board_id);
        $column_id = intval($request->column_id);
        $middle_id = intval($request->middle_id);
        $bottom_id = intval($request->bottom_id);
        $top_id = intval($request->top_id);
        $updatedCard = $this->boardLogic->moveCard($middle_id, $column_id, $bottom_id, $top_id);       
        return response()->json($updatedCard);
    }
    public function reorderCol(Request $request, $team_id, $board_id)
    {
        $user_id = Auth::user()->id;
        $board_id = intval($board_id);
        $middle_id = intval($request->middle_id);
        $right_id = intval($request->right_id);
        $left_id = intval($request->left_id);
        if (!$this->boardLogic->hasAccess($user_id, $board_id)) {
            return response()->json(["url" => route("viewHome")], HttpResponse::HTTP_BAD_REQUEST);
        }
        $updatedCol = $this->boardLogic->moveCol($middle_id, $right_id, $left_id);
        return response()->json($updatedCol);
    }

    public function removeMember(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "user_id" => "required",
        ]);
        $user_id = intval($request->user_id);
        $board_id = intval($board_id);
        $board = BoardUser::where("board_id", $board_id)->where("user_id", $user_id)->delete();
        // Step 2: Check if the board has any users left
$remainingUsers = BoardUser::where('board_id', $board_id)->count();

if ($remainingUsers == 0) {
    // No users left → delete the board
    Board::where('id', $board_id)->delete();
}
        return redirect()->back()->with("notif", ["Success\nMember removed successfully"]);
    }
    public function deleteBoard($team_id, $board_id)
    {
        Board::where("id", intval($board_id))->delete();
        return redirect()->back()->with("notif", ["Deleted\n Board deleted successfully"]);
    }
    public function updateCol(Request $request, $team_id, $board_id)
    {
        $request->validate([
            "column_name" => "required|max:20",
            "column_id" => "required",
        ]);

        $col_id = intval($request->column_id);
        $column = Column::find($col_id);
        if (!$column) {
            return redirect()->back()->with("notif", ["The column not found or its is deleted please contact the owner"]);
        }
        $column->name = $request->column_name;
        $column->save();
        return redirect()->back()->with("notif", ["Success\nUpdate success"]);
    }

    public function deleteCol(Request $request, $team_id, $board_id)
    {
        $request->validate(["column_id" => "required"]);
        $col_id = intval($request->column_id);
        $this->boardLogic->deleteCol($col_id);
        return redirect()->back()->with("notif", ["Success\nDelete success"]);
    }
}
