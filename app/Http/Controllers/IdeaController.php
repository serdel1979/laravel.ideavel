<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{



    public function index(): View
    {

        //$ideas = DB::table('ideas')->get();
        $ideas = Idea::get(); // select * from ideas
        return view('ideas.index', ['ideas' => $ideas]);
    }

    public function create(): View
    {
        return view('ideas.create_edit'); // Asegúrate de que esta ruta apunta a la vista correcta
    }



    public function edit(Idea $idea): View
    {

       // $idea = Idea::find($id);

        return view('ideas.create_edit')->with('idea',$idea); // Asegúrate de que esta ruta apunta a la vista correcta
    }

    public function update(Request $request): RedirectResponse
    {


       return redirect()->route('idea.index')->with('success', 'Idea actualizada con éxito.');
    }


    public function store(Request $request) : RedirectResponse
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:300  ',
        ]);

       // $userid = Auth::id();

        Idea::create([
            'user_id'=> Auth::id(),
            'title'=>$validatedData['title'],
            'description'=>$validatedData['description']
        ]);

        return redirect()->route('idea.index')->with('success', 'Idea guardada con éxito.');
    }
}
