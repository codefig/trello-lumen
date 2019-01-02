<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Board;
use Auth;

class BoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show ($id){
        $board = Board::findOrFail($id);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error', 'message' => 'Cannot access board'], 400);
        }
        return $board;
        // return 
    }

    public function index(){
        return Auth::user()->boards;
    }

    public function store(Request $request){
        
        $board = new Board();
        $board->name = $request->name;
        $board->user_id = Auth::user()->id;
        $board->save();

        return response()->json(['message' => 'success', 'data' => $board], 201);
    }

    public function update(Request $request, $id){
        $board = Board::find($id);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error', 'message' => 'Cannot access board'], 400);
        }
        $board->update($request->all());

        return response()->json(['message' => 'success', 'board' => $board], 200);
    }

    public function destroy($id){

        $board = Board::find($id);
        
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error', 'message' => 'Cannot access board'], 400);
        }

        if(Board::destroy($id)){
            return response()->json(['status' => 'success', 'message' => 'Board deleted Succesfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
    }
}  
