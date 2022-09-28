<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\Team;
use App\Models\Player;

use Illuminate\Http\Request;

class TeamController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        try {
            $teams = Team::all();
            return $teams;
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $request->validate(['name' => 'required|unique:teams']);
            Team::create($request->all());
            return response()->json(["message" => "Team $request->name was successfully created"], 202);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listByID($id)
    {
        try {
            $team = Team::find($id);
            $players = Player::where('team', $id)->orderBy('tshirt_number')->get()->makeHidden(['team']);

            $team['players'] = $players;

            return $team;
            if (!isset($team)) {
                return response()->json(["message" => "Unable to find any team with ID $id."], 404);
            }
            return $team;
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
            $team = Team::find($id);

            if (!isset($team)) {
                return response()->json(["message" => "Unable to find any team with ID $id."], 404);
            }
            if ($request->all === '') {
                return response()->json(["message" => "You must send at least one field to be edited"], 424);
            }
            $request->validate(["name" => "required|unique:teams"]);

            $team->update($request->all());

            return response()->json(["message" => "Team $team->name edited!", "team" => $team]);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $team = Team::find($id);

            if (!isset($team)) {
                return response()->json(["message" => "Unable to delete because we couldn't find any team with ID $id."], 404);
            }

            Team::destroy($id);

            return response()->json(["message" => "Team $team->name was deleted!"]);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }


    /**
     * List all players of specific team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPlayers($id)
    {
        try {
            $team = Team::find($id);
            if (!isset($team)) {
                return response()->json(["message" => "Unable to find any team with ID $id."], 404);
            }

            $players = Player::where('team', $id)->orderBy('tshirt_number')->get()->makeHidden(['team']);
            return $players;
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Associate existing player to the team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function addPlayer(Request $request, $id)
    {
        try {
            $request->validate(['player' => 'required|exists:players,id']);

            $team = Team::find($id);
            if (!isset($team)) {
                return response()->json(["message" => "Unable to find any team with ID $id."], 404);
            }

            $player = Player::find($request->player);
            if (!isset($player)) {
                return response()->json(["message" => "Unable to find any player with ID $request->player."], 404);
            }

            $player->update(["team" => $id]);

            return response()->json(["message" => "Player $player->name was added to $team->name"], 202);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }

    /**
     * Remove player from specifc team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removePlayer(Request $request, $id)
    {
        try {
            $request->validate(['player' => 'required|exists:players,id']);

            $team = Team::find($id);
            if (!isset($team)) {
                return response()->json(["message" => "Unable to find any team with ID $id."], 404);
            }

            $player = Player::find($request->player);
            if (!isset($player)) {
                return response()->json(["message" => "Unable to find any player with ID $request->player."], 404);
            } else if ($player->team != $id) {
                return response()->json(["message" => "Can't remove because player $player->name isn't in Team $team->name"], 424);
            }
            $player->update(["team" => 0]);

            return response()->json(["message" => "Player $player->name removed from $team->name"], 200);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 424);
        }
    }
}
