<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        $ranking = array();

        $matches = Game::all();

        foreach ($matches as $match) {

            $team1 = Team::find($match->team1);
            $team2 = Team::find($match->team2);

            if (!isset($ranking[$team1->name])) {
                $ranking[$team1->name]['goals'] = 0;
                $ranking[$team1->name]['matches_played'] = 0;
                $ranking[$team1->name]['matches_won'] = 0;
                $ranking[$team1->name]['matches_lost'] = 0;
                $ranking[$team1->name]['matches_draw'] = 0;
                $ranking[$team1->name]['points'] = 0;
                $ranking[$team1->name]['team'] = $team1->name;
            }
            if (!isset($ranking[$team2->name])) {
                $ranking[$team2->name]['goals'] = 0;
                $ranking[$team2->name]['matches_played'] = 0;
                $ranking[$team2->name]['matches_won'] = 0;
                $ranking[$team2->name]['matches_lost'] = 0;
                $ranking[$team2->name]['matches_draw'] = 0;
                $ranking[$team2->name]['points'] = 0;
                $ranking[$team2->name]['team'] = $team2->name;
            }

            $ranking[$team1->name]['goals'] += $match->team1_score;
            $ranking[$team1->name]['matches_played'] += 1;

            $ranking[$team2->name]['goals'] += $match->team2_score;
            $ranking[$team2->name]['matches_played'] += 1;

            if ($match->team1_score > $match->team2_score) {
                $ranking[$team1->name]['points'] += 3;
                $ranking[$team1->name]['matches_won'] += 1;
                $ranking[$team2->name]['matches_lost'] += 1;
            } else if ($match->team1_score < $match->team2_score) {
                $ranking[$team2->name]['points'] += 3;
                $ranking[$team2->name]['matches_won'] += 1;
                $ranking[$team1->name]['matches_lost'] += 1;
            } else if ($match->team1_score == $match->team2_score) {
                $ranking[$team2->name]['matches_draw'] += 1;
                $ranking[$team1->name]['matches_draw'] += 1;
            }
        }

        return $this->orderResults($ranking);
    }

    /**
     * Order match results by descending points.
     *
     * @param  array $array
     * @return array
     */
    private function orderResults($array)
    {
        $results = $array;
        array_multisort(array_column($results, 'points'), SORT_DESC, $results);

        $position = 1;
        $orderedResults = array();

        foreach ($results as $result) {

            $data = $result;
            $data['position'] = $position;
            $position++;

            array_push($orderedResults, $data);
        }


        return $orderedResults;
    }
}
