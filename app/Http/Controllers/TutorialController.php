<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutorial;


class TutorialController extends Controller
{
    public function index() {
      return Tutorial::all();
    }


    public function show($id) {
      $tutorial = Tutorial::find($id);

      if(!$tutorial)
          return  response()->json(['error' => 'Id Tutorial Tidak Ditemukan'], 404);

      return $tutorial;
    }


    public function store(Request $request) {
      $this->validate($request, [
            'title'    => 'required',
            'body'    => 'required',
      ]);

      $tutorial = $request->user()->tutorials()->create([
            'title'     => $request->json('title'),
            'slug'      => str_slug($request->json('title')),
            'body'      => $request->json('body'),
      ]);

      return $tutorial;
    }

    public function update(Request $request, $id) {
      $this->validate($request, [
            'title'    => 'required',
            'body'    => 'required',
      ]);

      $tut = Tutorial::find($id);

      if ($request->user()->id != $tut->user_id) {
          return response()->json(['error' => 'Your Credential Is Prohibited to Editing this Tutorial'], 403);
      }

      $tut->title = $request->title;
      $tut->body  = $request->body;
      $tut->save();

      return $tut;
    }

    public function destroy(Request $request,$id) {
      $tut = Tutorial::find($id);
      if ($request->user()->id != $tut->user_id) {
          return response()->json(['error' => 'Your Credential Is Prohibited to Deleting this Tutorial'], 403);
      }

      $tut->delete();

      return response()->json(['Message' => 'Tutorial is Deleted !'], 403);
    }
}
