<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Board;
class CleanUpBoardsWithoutUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    { 
               
    }
 public function clean()
    {
        Log::info('Running board cleanup manually...');

         $boards = Board::doesntHave('users')->get();

        foreach ($boards as $board) {
            // Loop through board's columns
            foreach ($board->columns as $column) {

                // Loop through each card in column
                foreach ($column->cards as $card) {
                    // Delete related card data
                    $card->comments()->delete();
                    $card->histories()->delete();
                    $card->notices()->delete();
                    $card->uploads()->delete();
                    $card->labels()->delete();
                    $card->users()->detach(); // Many-to-many pivot

                    $card->delete(); // Delete the card itself
                }

                $column->delete(); // Delete the column itself
            }

            $board->delete(); // Finally, delete the board
        }

        return count($boards);
    }

    /**
     * Handle the event.
     */
    public function handle(Login  $event): void
    {
          Log::info('Running board cleanup...');

        $boardsWithoutUsers = Board::doesntHave('users')->get();

        foreach ($boardsWithoutUsers as $board) {
            dd($board); // This should stop everything and dump data
        }
    }
}
