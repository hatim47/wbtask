<?php

namespace App\Http\Controllers;

use App\Logic\CardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\Card;
use App\Models\Lable;
use App\Models\Notice;
use App\Models\CardUser;
use App\Models\Team;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function __construct(protected TeamLogic $teamLogic, protected CardLogic $cardLogic)
    {
    }
    public function showCard(Request $request, $team_id, $board_id, $card_id)
    {
        $board_id = intval($board_id);
        $team_id = intval($team_id);

        $card = Card::find($card_id);
        $board = Board::find($board_id);
        $team = Team::find($team_id);
        $upload = Upload::where("card_id", $card->id)->get()->all();
        $owner = $this->teamLogic->getTeamOwner($team_id);
        $team_members = $this->cardLogic->getTeamMember($team_id ,$card_id);
        $workers = $this->cardLogic->getWorkers($card_id);
        $hist = $this->cardLogic->getHistories($card_id);
        $chatUser = CardUser::with('user')->where("card_id", $card->id)->get()->all();
        $chatOwner = CardUser::with('user')->where("card_id", $card->id)->where("status","Owner")->get()->first();
        $lables = Lable::where("card_id", $card->id)->get()->all();
        // dd($card, $upload, $board, $team, $workers, $hist, $owner);
                //    dd($lables);
        return view("card")
            ->with("card", $card)
            ->with("upload", $upload)
            ->with("chatUser", $chatUser)
            ->with("board", $board)
            ->with("lables", $lables)
            ->with("team", $team)
            ->with("members", $team_members)
            ->with("workers", $workers)
            ->with("histories", $hist)
            ->with("chatOwner", $chatOwner)
            ->with("owner", $owner);
    }

    public function notify(Request $request) {
               
                // $request->card_id;
                Notice::where("card_id", $request->card_id)
                ->where("froms", Auth::user()->id)
                ->update(["status" => 1]);
                return;

    }

    public function AddTeamMember(Request $request) {
        // $team_id = intval($request->team_id);
        // $this->teamLogic->deleteMembers($team_id, $request->emails);
          //dd($request);
         $emails = $request->emails;
          foreach ($emails as $email) {
            CardUser::create([                
                'user_id' => $email,
                'card_id' => $request->card_id,
                'status' => 'Member'
            ]);    
        }
     return response()->json(["message" => "Add success"]);
    }

    public function store(Request $request) {

           

        $attachments = [];
        foreach ($request->file('attachments') as $file) {
            $path = $file->store('uploads', 'public');
            $attachments[] = Upload::create(['file_path' => $path, 'card_id' => $request->card_id]);
        }
        $workers = $this->cardLogic->getWorkers($request->card_id);
        $user_id = auth()->id(); // Get the current logged-in user ID
        
        foreach ($workers as $worker) {
            if ($worker->id != $user_id) {
                Notice::create([
                    'tos' => $user_id,
                    'froms' => $worker->id,
                    'card_id' => $request->card_id,
                    'title' => 'attachments Uploaded'
                ]);
            }
        }
        
        return response()->json([
            'success' => true,  // ✅ Ensure success key is returned
            'message' => 'Upload successful!'
        ], 200);
    }


    public function assignCard(Request $request, $team_id, $board_id, $card_id)
    {
        return redirect()->back();
    }

    public function assignSelf(Request $request, $team_id, $board_id, $card_id)
    {
        $user_id = Auth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->addUser($card_id, $user_id);
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Joined card.");
        return redirect()->back()->with("notif", ["Success\nAdded yourself to the card"]);
    }

    public function leaveCard(Request $request, $team_id, $board_id, $card_id)
    {
        $user_id = Auth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->removeUser($card_id, $user_id);
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Left card.");
        return redirect()
            ->route("board", ["team_id" => $team_id, "board_id" => $board_id])
            ->with("notif", ["Success\nQuit Card"]);
    }

    public function deleteCard(Request $request, $team_id, $board_id, $card_id)
    {
        $this->cardLogic->deleteCard(intval($card_id));
        return redirect()
            ->route("board", ["team_id" => $team_id, "board_id" => $board_id])
            ->with("notif", ["Success\nCard is deleted"]);
    }

    public function updateCard(Request $request, $team_id, $board_id, $card_id)
    {
        $request->validate([
            "card_name" => "required|max:95"
        ]);
        $user_id = AUth::user()->id;
        $card_id = intval($card_id);
        $card = Card::find($card_id);
        $card->name = $request->card_name;
        $card->description = $request->card_description;
        $card->save();
        $this->cardLogic->cardAddEvent($card_id, $user_id, "Updated card informations.");
        return redirect()->back()->with("notif", ["Succss\nCard updated successfully"]);
    }

    public function addComment(Request $request, $team_id, $board_id, $card_id)
    {
        $request->validate(["content" => "required|max:200"]);
        $user_id = AUth::user()->id;
        $card_id = intval($card_id);
        $this->cardLogic->cardComment($card_id, $user_id, $request->content);
        $workers = $this->cardLogic->getWorkers($card_id);
        foreach ($workers as $worker) {
                   if ($worker->id != $user_id) {      
        Notice::create([
            'tos' => $user_id,
            'froms' => $worker->id,
            'card_id' =>$card_id,
            'title' => 'Comment has been done',
        ]);
    }
}        // return redirect()->back();
        return response()->json([
            "message" => "Comment added successfully",
            "status" => "success",
            "content" => $request->content,
            "user_id" => $user_id
        ], 200);
    }

    public function showComment($card_id)
    {
    $workers = $this->cardLogic->getHistories($card_id);
    return $workers;
    }
    public function Addlable(Request $request)
    {
// dd($request);
            Lable::create([
                'card_id' => $request->card_id,
                'color'=>$request->rang,
                'text'=>$request->label,
            ]);
            return response()->json(['success' => true, 'message' => 'Task deleted successfully']);

    }
    public function lableList(int $id)
{
    $lable = Lable::where("card_id", $id)->get(); 
    return response()->json($lable);
}
public function updateTaskStatus( Request $request)
{
    $task = Lable::findOrFail($request->id);
    $task->status = $request->status;
    $task->save();

    return response()->json([
        'success' => true,
        'message' => 'Task status updated successfully!',
        'task'    => $task
    ]);
}
public function destroyLable(Request $request)
{
    $task = Lable::find($request->id);

    if ($task) {
        $task->delete();
        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Task not found'], 404);
}

}
