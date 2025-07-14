<?php

namespace App\Http\Controllers;

use App\Logic\CardLogic;
use App\Logic\TeamLogic;
use App\Models\Board;
use App\Models\BoardLabel;
use App\Models\BoardUser;
use App\Models\Card;
use App\Models\CardUser;
use App\Models\CardComment;
use App\Models\Lable;
use App\Models\Notice;
use App\Models\Team;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $cardcomment = CardComment::with('user')->where("card_id", $card->id)->get()->all();
        $team_members = $this->cardLogic->getTeamMember($team_id ,$card_id);
        $workers = $this->cardLogic->getWorkers($board_id);
        $hist = $this->cardLogic->getHistories($card_id);
        $chatUser = CardUser::with('user')->where("card_id", $card->id)->get()->all();
        $chatOwner = CardUser::with('user')->where("card_id", $card->id)->where("status","Owner")->get()->first();
        $cardLabels = Lable::where("card_id", $card->id)->get()->all();
        $labels = BoardLabel::where("board_id", $board_id)->get()->all();


return response()->json([
    'card' => $card,
    'upload' => $upload,
    'chatUser' => $chatUser,
    'board' => $board,
    'cardLabels' => $cardLabels,
    'labels' => $labels,
    'cardcomment' => $cardcomment,
    'team' => $team,
    'members' => $team_members,
    'workers' => $workers,
    'histories' => $hist,
    'chatOwner' => $chatOwner,
   
]);

    }
    

 public function updateLabel(Request $request ,int $label)
{
$labelId = $label;
    $request->validate([
           'id' => 'nullable|integer',
        'color' => 'required|string|max:10', 
        'title' => 'required|string|max:50',
        'status' => 'required|boolean'
    ]);

    // $label = BoardLabel::findOrFail($label);
  try {
        // Try to find in BoardLabel
        $label = BoardLabel::findOrFail($labelId);

    } catch (ModelNotFoundException $e) {
        // Fallback to Label if not found
        $label = Lable::findOrFail($labelId);
        
    }
    
    $label->update($request->only(['color', 'title', 'status']));

    return response()->json(['success' => true, 'message' => 'Label updated successfully']);





}
public function addLabel(Request $request){
    $request->validate([
        'id' => 'nullable|integer',
        'card_id' => 'required|integer',
        'color' => 'required|string|max:10', 
        'title' => 'nullable|string|max:50',
    ]);
        if ($request->id != 0){
        $existing = Lable::where('card_id', $request->card_id)->where('board_card_id', $request->id)->first();
        if (!$existing) {
            return response()->json(['message' => 'Label not found'], 404);
        }
        // Toggle the status
        $existing->status = $existing->status == 0 ? 1 : 0;
        $existing->save();
        return response()->json(['message' => 'Label status updated', 'status' => $existing]);
    } else {
        // Create new label
        $label = Lable::create([
            'board_card_id' => $request->id,
            'card_id' => $request->card_id,
            'color' => $request->color,
            'title' => $request->title,
            'status' => 0
        ]);
        return response()->json(['message' => 'Label created', 'label' => $label]);
    }
}

public function deleteLabel(Request $request, int $label)
{
if ($request->superid == 0) {
    $labell = Lable::find($label);
    if ($labell) {
        $labell->delete();  
         return response()->json(['success' => true, 'message' => 'Label deleted successfully' , 'data' => $labell]);  
    }  
}
else{
     $boardLabel = BoardLabel::find($label);
        if ($boardLabel) {
            $boardLabel->delete();          
        return response()->json(['success' => true, 'message' => 'Label deleted successfully', 'data' => $boardLabel]); 
        } 
    }       
 return response()->json(['success' => false, 'message' => 'Label not found'], 404);
}

               public function updateNotify(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'card_id' => 'required|integer',
            'board_id' => 'required|integer',
        ]);

        // Fetch the card details based on the provided IDs
        // Replace the following line with your actual data retrieval logic
        $card = Card::where('id', $validated['card_id'])
                    ->where('board_id', $validated['board_id'])
                    ->first();

        if (!$card) {
            return response()->json(['error' => 'Card not found.'], 404);
        }

        // Return the card details as a JSON response
        return response()->json([
            'title' => $card->title,
            'description' => $card->description,
        ]);
    }


    public function viewmember($card_id ,$team_id,$board_id) {
        $owner = $this->teamLogic->getTeamOwner($team_id);
        $workers = $this->cardLogic->getWorkers($card_id);
        $board = Board::find($board_id);
        $team = Team::find($team_id);
          $card = Card::find($card_id);
        $chatUser = CardUser::with('user')->where("card_id", $card_id)->get()->all();
        $chatOwner = CardUser::with('user')->where("card_id", $card_id)->where("status","Owner")->get()->first();
        return view("members")
        ->with("chatUser", $chatUser)->with("chatOwner", $chatOwner)->with("card", $card_id)->with("team", $team)->with("board", $board)->with("owner", $owner)->with("workers", $workers);
    }


public function notify(Request $request) {
               
                // $request->card_id;
                Notice::where("card_id", $request->card_id)
                ->where("froms", Auth::user()->id)
                ->update(["status" => 1]);
                return;

    }

    public function addComments(Request $request)
    {
        $request->validate([
            'card_id' => 'required|integer',
            'content' => 'required|string|max:1000',
        ]);

        $comment = CardComment::create([
            'card_id' => $request->card_id,
            'user_id' => $request->user_id,
            'content' => $request->content,
        ]);
         $workers = BoardUser::where('board_id', $request->board_id)->get();
        foreach ($workers as $worker) {
              if ($worker->user_id != $request->user_id) {
        Notice::create([
            'tos' => $request->user_id,
            'froms' => $worker->user_id,
            'card_id' =>$request->card_id,
            'title' => 'Comment has been done',
        ]);

    }
}
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => $comment,
        ]);
    }
public function shownotification ($id,$userId)
    {

    Notice::where('card_id', $id)
          ->where('froms', $userId)
          ->update(['status' => 1]);

return response()->json(['message' => 'Notices updated']);
    }



public function updateComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = CardComment::findOrFail($id);
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully.',
            'comment' => $comment,
        ]);
    }

    public function destroyComment($id)
    {
        $comment = CardComment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
        ]);
    }


public function uploadImage(Request $request)
{
    $request->validate([
        'image' => 'required|image|max:10240', // max 10MB
    ]);

    $path = $request->file('image')->store('attachments', 'public');

    // Optionally save in DB
    $uploadedFiles = Upload::create([
        'file_path' => $path,
        'original_name' => $request->file('image')->getClientOriginalName(),
        'card_id' => $request->card_id ?? null, // if provided
    ]);

    return response()->json([
        'success' => true,
        'uploaded_files' => $uploadedFiles,
    ]);
}

public function uploadAttachments (Request $request)
{
    $request->validate([
        'image.*' => 'required|file|max:10240', // max 10MB each
        'card_id' => 'required|exists:cards,id'
    ]);

    $uploadedFiles = [];

    foreach ($request->file('image') as $file) {
        $path = $file->store('attachments', 'public');

        $attachment = Upload::create([
        'file_path' => $path,
        'original_name' => $file->getClientOriginalName(),
        'card_id' => $request->card_id ?? null, // if provided
    ]);

        $uploadedFiles[] = $attachment;
    }

    return response()->json([
        'success' => true,
        'uploaded_files' => $uploadedFiles,
    ]);
}

public function uploadDeleted(Request $request,  $card,  $upload)
{
  

    // Find the upload record
    $attachment = Upload::where('id', $upload)->where('card_id', $card)->first();

    if (!$attachment) {
        return response()->json(['error' => 'File not found.'], 404);
    }

    // Delete the file from storage
    Storage::disk('public')->delete($attachment->file_path);

    // Delete the record from the database
    $attachment->delete();

    return response()->json([
    'success' => true,
    'file_id' => $upload,
    'file_path'=> $attachment->file_path,
]);
}
public function updateDescription(Request $request, Card $card)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:990000'
        ]);
        // $card = Card::find($card);   
        $card->description = $validated['description'];
        $card->save();

        return response()->json([
            'message' => 'Description updated successfully.',
            'card' => $card
        ]);
    }
public function MakeCover (Request $request,  $card,  $upload)
 {
 $newCover = Upload::where('card_id', $card)
                      ->where('id', $upload)
                      ->first();
    if (!$newCover) {
        return response()->json(['message' => 'File not found for this card.'], 404);
    }
    Upload::where('card_id', $card)
          ->where('f_cover', 1)
          ->update(['f_cover' => 0]);
    $newCover->f_cover = 1;
    $newCover->save();
    return response()->json(['message' => 'Cover updated successfully.']);
 }
public function AddTeamMember(Request $request) {
                 $id_card =  $request->card_id;
                 $status = $request->status;
                  $user_id =  $request->user_id;
            CardUser::create([  
                'user_id' => $user_id,
                'card_id' => $id_card,
                'status' => $status
            ]);    
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
public function leaveCard(Request $request,  $board_id, $card_id)
    {
        $id = $request->user_id;
        $card_id = intval($card_id);
        $this->cardLogic->removeUser($card_id, $id);
        $this->cardLogic->cardAddEvent($card_id, $id, "Left card.");
     return response()->json([
            'success' => true,
            'message' => 'User removed successfully'
        ]);
        
          
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
//     public function addComment(Request $request, $team_id, $board_id, $card_id)
//     {
//         $request->validate(["content" => "required|max:200"]);
//         $user_id = AUth::user()->id;
//         $card_id = intval($card_id);
//         $this->cardLogic->cardComment($card_id, $user_id, $request->content);
//         $workers = $this->cardLogic->getWorkers($card_id);
//         foreach ($workers as $worker) {
     
// }        // return redirect()->back();
//         return response()->json([
//             "message" => "Comment added successfully",
//             "status" => "success",
//             "content" => $request->content,
//             "user_id" => $user_id
//         ], 200);
//     }

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
                'title'=>$request->title,
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
