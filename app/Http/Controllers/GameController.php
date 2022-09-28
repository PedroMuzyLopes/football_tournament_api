<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\Game;

use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Get error code.
     * */
    private function getErrorCode($error)
    {
        if ($error->getCode() != null && $error->getCode() != 0) {
            return $error->getCode();
        } else if (isset($error->status)) {
            return $error->status;
        } else {
            return 500;
        }
    }

    /**
     * List all matches.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        try {
            $matches = Game::all();
            return $matches;
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 500);
        }
    }

    /**
     * Register new match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'team1' => 'required|exists:teams,id',
                'team1_score' => 'required',
                'team2' => 'required|exists:teams,id',
                'team2_score' => 'required'
            ]);

            $start_time = strtotime($request->start_time);
            $end_time = strtotime($request->end_time);

            if ($end_time < $start_time) {
                return response()->json(["message" => "Start time cannot be less than the end time"], 424);
            }

            Game::create($request->all());

            return response()->json(["message" => "Game successfully registered"], 202);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 424);
        }
    }

    /**
     * List specific match
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listByID($id)
    {
        try {
            $match = Game::find($id);

            if (!isset($match)) {
                return response()->json(["message" => "Unable to find any match with ID $id."], 404);
            }

            return $match;
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $match = Game::find($id);

            if (!isset($match)) {
                return response()->json(["message" => "Unable to find any match with ID $id."], 404);
            }
            if ($request->all === '') {
                return response()->json(["message" => "You must send at least one field to be edited"], 424);
            }

            $match->update($request->all());

            return response()->json(["message" => "Match edited!", "match" => $match]);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Remove the specified match.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $match = Game::find($id);

            if (!isset($match)) {
                return response()->json(["message" => "Unable to delete because we couldn't find any Game with ID $id."], 404);
            }

            Game::destroy($id);

            return response()->json(["message" => "Game $match->name deleted!"]);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }
}
