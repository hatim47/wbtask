<?php

namespace App\Logic;

use App\Models\Board;
use App\Models\Team;
use App\Models\User;
use App\Models\UserTeam;
use App\Models\BoardUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TeamLogic
{
    public const PATTERN = [
        'isometric',
        'zig-zag',
        'zig-zag-flat',
        'wavy',
        'triangle',
        'triangle-2',
        'moon',
        'rect',
        'box',
        'polka',
        'polka-2',
        'paper',
        'line-bold-horizontal',
        'line-bold-vertical',
        'line-thin-diagonal',
    ];


    /**
     * get all registered teams of a given user
     *
     * @param int $user_id owner id
     * @param string $team_name team name
     * @param string $team_description team description
     * @param string $team_pattern team pattern
     *
     * @return Team created team
     */
    function createTeam(int $user_id, string $team_name, string $team_description, string $team_pattern)
    {
        $newTeam = Team::create([
            "name" => $team_name,
            "description" => $team_description,
            "pattern" => $team_pattern
        ]);

        UserTeam::create([
            "user_id" => $user_id,
            "team_id" => $newTeam->id,
            "status" => "Owner"
        ]);

        return $newTeam;
    }

    /**
     * get the owner of a certain team
     *
     * @param int $team_id team id
     *
     * @return User owner of the team
     */
    function getTeamOwner(int $team_id)
    {
        $team_user_pivot = UserTeam::all()
            ->where("status", "Owner")
            ->where("team_id", $team_id)
            ->first();
        $owner = User::find($team_user_pivot->user_id);
        return $owner;
    }

    /**
     * get the list of member from a certain team
     *
     * @param int $team_id team id
     *
     * @return Collection<int, User> list of team member
     */
    function getTeamMemberr(int $team_id)
    {
        
        $team_members = Team::find($team_id)->users()
              ->wherePivotIn('status', ['Member', 'Owner'])
            ->get();
            //   dd($team_members);

           
         

         
    $membersWithBoards = $team_members->map(function ($user) use ($team_id) {
        return [
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'status'  => $user->pivot->status ?? null,
            'image'   => $user->image_path ?? null,
           'created_at' => $user->created_at ? $user->created_at->format('d M y') : null,          
            'boards' => $user->boards()
            ->where('team_id', $team_id)  // this is key!
            ->with('team')
            ->get()
            ->map(function ($board) {
                return [
                    'board_id'   => $board->id,
                    'board_name' => $board->name,
                    'status'     => $board->pivot->status ?? null,
                    'team_id'    => $board->team_id,
                    'pattern'    => $board->pattern,
                    'image_path' => $board->image_path ?? null,
                ];
            }),
        ];
    });
    
        return $membersWithBoards;
    }
function getTeamMember(int $team_id)
    {
        $team_members = Team::find($team_id)->users()
            ->wherePivot("status", "Member")
            ->get();


        return $team_members;
    }
    /**
     * get all registered teams of a given user
     *
     * @param int $user_id user id
     *
     * @return Collection<int, Team> team where user is a member
     */
    function getUserTeams(int $user_id, $status = ["Member", "Owner", "Pending"], $team_name = "%")
    {
        $teams = User::find($user_id)->teams()
            ->wherePivotIn("status", $status)
            ->where("name", "LIKE", "%" . $team_name . "%")
            ->get();

            // dd($teams);
        return $teams;
    }

    /**
     * change the user team access to member
     *
     * @param int $user_id user id
     * @param int $team_id team id
     */
    public function inviteAccept(int $user_id, int $team_id)
    {
        $teamStatus = UserTeam::where([
            "user_id", $user_id,
            "team_id", $team_id,
        ])->first();

        $teamStatus->status = "Member";
        return;
    }

    /**
     * get boards of a certain team
     *
     * @param int $team_id team id
     * @param string $search search string (optional)
     *
     * @return Collection<int, Board> team's boards where name contains search string
     */
    public function getBoards(int $team_id, string $search = "%")
    {
        $boards = Team::find($team_id)
            ->boards()
            ->where("name", "LIKE", "%".$search."%")
            ->get();

        return $boards;
    }

    /**
     * check if user can access a sertain team
     *
     * @param int $user_id user id
     * @param int $team_id team id
     *
     * @return boolean is user has access to team
     */
    public function userHasAccsess(int $user_id, int $team_id)
    {
        $isAuthorized = UserTeam::where("user_id", $user_id)
            ->where("team_id", $team_id)
            ->whereNot("status", "Pending")
            ->first();

        return !($isAuthorized == null);
    }

    /**
     * @param int $team_id team id
     * @param array $emails member emaile to be removed from team
     */
    public function deleteMembers(int $team_id, $emails)
    {
        $deletedUser = User::where("email", $emails)->first();

        if (!$deletedUser) {
            return "User not found.";
        }
    
        $currentUser = Auth::user();
        $isSelf = $currentUser->id === $deletedUser->id;
    
        $userTeamRecord = UserTeam::where("team_id", $team_id)
            ->where("user_id", $deletedUser->id)
            ->first();
    
        if (!$userTeamRecord) {
            return "User is not part of the team.";
        }
    
        $teamMembers = UserTeam::where("team_id", $team_id)->get();
    
        if ($teamMembers->count() <= 1) {
            return "You can't remove the last team member.";
        }
    
        $currentUserStatus = $teamMembers->firstWhere("user_id", $currentUser->id)?->status;
    
        if (!$isSelf && $currentUserStatus !== "Owner") {
            return "Only team owners can remove members.";
        }
    
        if ($userTeamRecord->status === "Owner") {
            $otherOwners = $teamMembers->filter(fn($m) => $m->status === "Owner" && $m->user_id !== $deletedUser->id);
    
            if ($otherOwners->isEmpty()) {
                $nextMember = $teamMembers->firstWhere(fn($m) =>
                    $m->user_id !== $deletedUser->id && $m->status !== "Owner"
                );
    
                if ($nextMember) {
                    $nextMember->status = 'Owner';
                    $nextMember->save();
                } else {
                    return "Can't remove last owner. No other members to promote.";
                }
            }
        }    
        $boardIds = Board::where('team_id', $team_id)->pluck('id');    
        foreach ($boardIds as $boardId) {
            $boardUser = BoardUser::where('board_id', $boardId)
                ->where('user_id', $deletedUser->id)
                ->first();    
            if ($boardUser) {
                if ($boardUser->status === 'Owner') {
                    $otherBoardUsers = BoardUser::where('board_id', $boardId)
                        ->where('user_id', '!=', $deletedUser->id)
                        ->get();    
                    if ($otherBoardUsers->isNotEmpty()) {
                        $nextBoardUser = $otherBoardUsers->first();
                        $nextBoardUser->status = 'Owner';
                        $nextBoardUser->save();
                    }
                    else {
                        // 🚨 No users left, so delete the board too
                        Board::where('id', $boardId)->delete();
                    }
                }    
                $boardUser->delete();
            }
        }    
        $userTeamRecord->delete();    
        return $isSelf ? "You have left the team." : "Member removed from the team.";
    }  
    /**
     * delete a certain team data, including boards,
     * cards, and mebers data
     *
     * @param int $team_id team id
     */
    public function deleteTeam(int $team_id)
    {
        Board::where("team_id", $team_id)->delete();
        UserTeam::where("team_id", $team_id)->delete();
        Team::where("id", $team_id)->delete();
        return;
    }
}
