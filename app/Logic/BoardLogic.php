<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Notice;
use App\Models\CardComment;
use App\Models\Team;
use App\Models\Lable;
use App\Models\User;
use App\Models\BoardUser;
use App\Models\BoardLabel;
use App\Models\Upload;
use App\Models\UserTeam;
use Illuminate\Support\Facades\Auth;

class BoardLogic
{
    public const PATTERN = [
        'sunkist',
        'mini',
        'sha-la-la',
        'celestial',
        'dream',
        'blue',
        'purple',
        'ellegant',
        'jaipur',
        'mild',
        'sunset',
        'cosmic',
        'police',
        'morning'
    ];

    public function hasAccess(int $user_id, int $board_id)
    {
        $board = Board::find($board_id);
        if ($board == null) return false;

        $team = Team::find($board->team_id);
        if ($team == null) return false;

        $access =  UserTeam::where("user_id", $user_id)
            ->where("team_id", $team->id)
            ->whereNot("status", "Pending")
            ->first();

        return ($access != null);
    }


    public function createBoard(int $team_id, string $board_name, string $board_pattern)
    {
        $team = Team::find($team_id);
        $teamExist = ($team != null);
        if (!$teamExist) return null;

        $createdBoard = Board::create([
            "team_id" => $team->id,
            "name" => $board_name,
            "pattern" => $board_pattern
        ]);
        BoardUser::create([
            "user_id" => Auth::user()->id,
            "board_id" => $createdBoard->id,
            "status" => "Owner"
        ]);
        $defaultColors = [
    '#4CD964', // Green
    '#FFCC00', // Yellow
    '#FF9500', // Orange
    '#FF3B30', // Red
    '#AF52DE', // Purple
    '#007AFF'  // Blue
];

foreach ($defaultColors as $color) {
    BoardLabel::create([
        'board_id' => $createdBoard->id,
        'title' => null,
        'color' => $color
    ]);
}

        return $createdBoard;
    }

    public function addColumn(int $board_id, string $column_name)
    {
        $board = Board::find($board_id);

        if ($board == null) return null;

        $lastColumn = Column::where("board_id", $board->id)
            ->whereNull("next_id")
            ->first();

        $column = Column::create([
            "name" => $column_name,
            "board_id" => $board->id,
            "previous_id" => $lastColumn ? $lastColumn->id : null,
        ]);

        if ($lastColumn) {
            $lastColumn->next_id = $column->id;
            $lastColumn->save();
        }
        return $column;
    }

    public function getTeamOwner(int $team_id)
    {
       $owner = BoardUser::where("status", "Owner")
    ->where("board_id", $team_id)
    ->get();
        // $owner = User::find($team_user_pivot->user_id);
        // dd( $owner);
        return $owner;
    }


       function getTeamMember(int $team_id)
    {
    //     $team_members = BoardUser::with('users')
    // ->where('board_id', $team_id)
    // ->whereIn('status', ['Member', 'Owner'])
    // ->get();
$board = Board::find($team_id);
              $team_members =  $board->users()->where('board_id', $team_id)
    ->wherePivotIn('status', ['Member', 'Owner'])
    ->get();
        return $team_members;
    }







    public function addCard(int $column_id, string $card_name)
    {
        $lastCard = Card::where("column_id", $column_id)
            ->whereNull("next_id")
            ->first();

        $newCard = Card::create([
            "name" => $card_name,
            "column_id" => $column_id,
            "previous_id" => $lastCard ? $lastCard->id : null
        ]);

        if($lastCard){
            $lastCard->next_id = $newCard->id;
            $lastCard->save();
        }

        return $newCard;
    }

    public function getData(int $board_id)
    {
        // dd($board_id);
        $columns = collect();
        $board = Board::find($board_id);
       
        $column = Column::where("board_id", $board->id)
            ->whereNull('previous_id')
            ->first();

        while ($column) {
            $cards = collect();
            $card = Card::where("column_id", $column->id)
                ->whereNull('previous_id')
                ->first();
            while ($card) {
                $card->setHidden(['nextCard', "created_at", "updated_at", "column_id", "previous_id", "next_id"]);
                $card->images = Upload::where("card_id", $card->id)->where("f_cover", 1)->get(["id", "file_path"]); 
                $card->pic = Upload::where("card_id", $card->id)->get(["id", "file_path"]); 
                $card->files = $card->pic->count();
                $workerNames = Card::find($card->id)->users()->pluck('name')->map(fn($name) => substr($name, 0, 2));
                // dd($workerNames);
              $card->comments = CardComment::where("card_id", $card->id)->count();
                $card->users = $workerNames;
               $notify = Notice::where("froms", Auth::user()->id )->where("card_id", $card->id)->where("status",0)->count();
                //   dd($notify);
            $cardLabels = Lable::where("card_id", $card->id)->where("status", 0)->get()->all();
              $boardLabels = BoardLabel::where("board_id", $board->id)->where("status", 0)->get()->all();
                $card->notif = $notify;
                $card->lables = array_merge($cardLabels, $boardLabels);
                
            $cards->push($card);
            $card = $card->nextCard;

            }
            $column->setHidden(['nextColumn', 'updated_at', 'created_at', 'previous_id', "next_id", "board_id"]);
            $column->cards = $cards->values();
            
            $columns->push($column);
            $column = $column->nextColumn;
        }
        $board->columns = $columns->values();
        $board->setHidden(["team_id", "image_path", "created_at", "updated_at"]);
        
        return $board;
    }

    public function moveCard(int $target_card_id, int $column_id, int $bottom_card_id, int $top_card_id)
    {
        $column = Column::find($column_id);
        $target_card = Card::find($target_card_id);
        $previous_top_card = null;
        $previous_bottom_card = null;
        $top_card = null;
        $bottom_card = null;

        if($column == null) return null;
        $current_top_card = $target_card->previous_id ? Card::find($target_card->previous_id) : null;
    $current_bottom_card = $target_card->next_id ? Card::find($target_card->next_id) : null;

        if (
            $target_card->column_id == $column_id &&
            optional($current_top_card)->id == $top_card_id &&
            optional($current_bottom_card)->id == $bottom_card_id
        ) {
            return $target_card; // No changes needed
        }



        if($bottom_card_id != 0) $bottom_card = Card::find($bottom_card_id);
        if($bottom_card != null) $top_card = Card::find($bottom_card->previous_id);
        if($target_card->previous_id) $previous_top_card = Card::find($target_card->previous_id);
        if($target_card->next_id) $previous_bottom_card = Card::find($target_card->next_id);
        if($bottom_card == null && $top_card == null) $top_card = Card::find($top_card_id);

        //insert in middle
        $target_card->column_id = $column->id;
        $target_card->previous_id = null;
        $target_card->next_id = null;
        if($previous_bottom_card){
            $previous_bottom_card->previous_id = $previous_top_card ? $previous_top_card->id : null;
        }
        if($previous_top_card){
            $previous_top_card->next_id = $previous_bottom_card ? $previous_bottom_card->id : null;
        }
        if($bottom_card){
            $target_card->next_id = $bottom_card->id;
            $bottom_card->previous_id = $target_card->id;
        }
        if($top_card){
            $target_card->previous_id = $top_card->id;
            $top_card->next_id = $target_card->id;
        }

        $target_card->save();
        if($bottom_card) $bottom_card->save();
        if($top_card) $top_card->save();
        if($previous_bottom_card) $previous_bottom_card->save();
        if($previous_top_card) $previous_top_card->save();
        return $target_card;
    }

    public function moveCol(int $target_column_id, int $right_column_id, int $left_column_id)
    {
        $target_column = Column::find($target_column_id);
        if (
    $target_column->next_id == $right_column_id &&
    $target_column->previous_id == $left_column_id
) {
    return response()->json(['message' => 'No position change'], 200);
}
        $previous_top_column = null;
        $previous_bottom_column = null;
        $top_column = null;
        $bottom_column = null;
        // dd( "target_column_id: $target_column_id, right_column_id: $right_column_id, left_column_id: $left_column_id");

        if($right_column_id != 0) $bottom_column = Column::find($right_column_id);
        if($bottom_column != null) $top_column = Column::find($bottom_column->previous_id);
        if($target_column->previous_id) $previous_top_column = Column::find($target_column->previous_id);
        if($target_column->next_id) $previous_bottom_column = Column::find($target_column->next_id);
        if($bottom_column == null && $top_column == null) $top_column = Column::find($left_column_id);

        //insert in middle
        $target_column->previous_id = null;
        $target_column->next_id = null;
        if($previous_bottom_column){
            $previous_bottom_column->previous_id = $previous_top_column ? $previous_top_column->id : null;
        }
        if($previous_top_column){
            $previous_top_column->next_id = $previous_bottom_column ? $previous_bottom_column->id : null;
        }
        if($bottom_column){
            $target_column->next_id = $bottom_column->id;
            $bottom_column->previous_id = $target_column->id;
        }
        if($top_column){
            $target_column->previous_id = $top_column->id;
            $top_column->next_id = $target_column->id;
        }

        $target_column->save();
        if($bottom_column) $bottom_column->save();
        if($top_column) $top_column->save();
        if($previous_bottom_column) $previous_bottom_column->save();
        if($previous_top_column) $previous_top_column->save();
        return $target_column;
    }

    function deleteCol(int $target_column_id) {
        $target_column = Column::find($target_column_id);
        $top_column = null;
        $bottom_column = null;
        if(!$target_column) return;
        if($target_column->previous_id) $top_column = Column::find($target_column->previous_id);
        if($target_column->next_id) $bottom_column = Column::find($target_column->next_id);

        if($top_column){
            $top_column->next_id = $bottom_column ? $bottom_column->id : null;
            $top_column->save();
        }
        if($bottom_column){
            $bottom_column->previous_id = $top_column ? $top_column->id : null;
            $bottom_column->save();
        }
        $target_column->delete();
        return;
    }
}
