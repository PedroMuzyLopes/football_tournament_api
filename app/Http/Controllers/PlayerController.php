<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{

    /**
     * Get status code from error.
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
            $players = Player::all();
            return $players;
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
            $request->validate([
                'name' => 'required',
                'tshirt_number' => 'required',
            ]);

            if ($request->team) {
                $request->validate([
                    'team' => 'exists:teams,id'
                ]);
            }

            Player::create($request->all());

            return response()->json(["message" => "Player $request->name was successfully created"], 202);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], 424);
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
            $player = Player::find($id);

            if (!isset($player)) {
                return response()->json(["message" => "Unable to find any player with ID $id."], 404);
            }
            return $player;
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
            $player = Player::find($id);

            $request->validate(["name" => "required|unique:teams"]);

            if (!isset($player)) {
                return response()->json(["message" => "Unable to find any player with ID $id."], 404);
            }

            $player->update($request->all());

            return response()->json(["message" => "Player $player->name edited!", "player" => $player]);
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
            $player = Player::find($id);

            if (!isset($player)) {
                return response()->json(["message" => "Unable to delete because we couldn't find any player with ID $id."], 404);
            }

            Player::destroy($id);

            return response()->json(["message" => "Player $player->name was deleted!"]);
        } catch (Exception $error) {
            return response()->json(["message" => $error->getMessage()], $this->getErrorCode($error));
        }
    }
}
