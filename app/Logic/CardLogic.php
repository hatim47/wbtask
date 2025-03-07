<?php

namespace App\Logic;

use App\Models\Card;
use App\Models\CardHistory;
use App\Models\CardComment;
use App\Models\CardUser;
use App\Models\User;

class CardLogic
{
    public function getData(int $card_id) {
        $card = Card::find($card_id);
        return $card;
    }

    public function getWorkers(int $card_id) {
        $users = Card::find($card_id)->users()->get();
        return $users;
    }

    public function addUser(int $card_id, int $user_id , string $content) {
        CardUser::create([
            "user_id" => $user_id,
            "card_id" => $card_id,
            "status" => $content
        ]);
        return;
    }

    public function removeUser(int $card_id, int $user_id) {
        CardUser::where([
            "user_id" => $user_id,
            "card_id" => $card_id,
        ])->delete();
        return;
    }
    function getTeamMember(int $team_id , int $card_id)
    {
        // $team_members = Team::find($team_id)->users()
        //     ->wherePivot("status", "Member")
        //     ->whereHas('cards', function ($query) use ($card_id) {
        //         $query->where('card_id', $card_id);
        //     })->get();

        $team_members = User::whereHas('teams', function ($query) use ($team_id) {
            $query->where('team_id', $team_id);
        })
        ->whereDoesntHave('cardUsers', function ($query) use ($card_id) {
            $query->where('card_id', $card_id);
        })
        ->get();

        return $team_members;
    }

    function cardAddEvent(int $card_id, int $user_id, string $content){
        $event = CardHistory::create([
            "user_id" => $user_id,
            "card_id" => $card_id,
            "type" => "event",
            "content" => $content,
        ]);



        return $event;
    }

    function cardComment(int $card_id, int $user_id, string $content){
        $event = CardComment::create([
            "user_id" => $user_id,
            "card_id" => $card_id,
            "type" => "comment",
            "content" => $content,
        ]);
 
        return $event;
    }

    function getHistories(int $card_id){
        $evets = CardHistory::with("user")
            ->where("card_id", $card_id)
            ->orderBy("created_at","desc")
            ->get();

            $comments = CardComment::with("user")
            ->where("card_id", $card_id)
            ->orderBy("created_at","desc")
            ->get();
        
        // Merge comments into events
        $evets = $evets->merge($comments)->sortByDesc("created_at")->values();
        return $evets;
    }

    function deleteCard(int $target_card_id){
        $target_card = Card::find($target_card_id);
        $top_card = null;
        $bottom_card = null;
        if(!$target_card) return;
        if($target_card->previous_id) $top_card = Card::find($target_card->previous_id);
        if($target_card->next_id) $bottom_card = Card::find($target_card->next_id);

        if($top_card){
            $top_card->next_id = $bottom_card ? $bottom_card->id : null;
            $top_card->save();
        }
        if($bottom_card){
            $bottom_card->previous_id = $top_card ? $top_card->id : null;
            $bottom_card->save();
        }
        $target_card->delete();
    }
}
