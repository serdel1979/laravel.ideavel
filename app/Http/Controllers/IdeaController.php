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

    private array $rules = [
        'title' => 'required|string|max:100',
        'description' => 'required|string|max:300  ',
    ];

    private array $messagesError = [
        'title.required' => 'El campo Titulo es obligatorio',
        'description.required' => 'El campo Descripcion es obligatorio',
        'string' => 'Debe ser un string',
        'title.max' => 'El título no debe superar los 100 caracteres',
        'description.max' => 'El campo descripcion no debe superar los 300 caracteres'
    ];

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


    public function show(Idea $idea): View
    {

       // $idea = Idea::find($id);

        return view('ideas.show')->with('idea',$idea); // Asegúrate de que esta ruta apunta a la vista correcta
    }

    public function edit(Idea $idea): View
    {

       // $idea = Idea::find($id);

        return view('ideas.create_edit')->with('idea',$idea); // Asegúrate de que esta ruta apunta a la vista correcta
    }


    public function delete(Idea $idea): RedirectResponse
    {

       // $idea = Idea::find($id);
       $idea->delete();

       session()->flash('message','Idea eliminada');

       return redirect()->route('idea.index')->with('success', 'Idea actualizada con éxito.');
    }


    public function update(Request $request, Idea $idea): RedirectResponse
    {
        $validatedData = $request->validate($this->rules, $this->messagesError);
        
        $idea->update($validatedData);
        session()->flash('message','Idea actualizada correctamente!!!');
        return redirect()->route('idea.index')->with('success', 'Idea actualizada con éxito.');
    }


    
    public function liked(Request $request, Idea $idea): RedirectResponse
    {

        $request->user()->ideasLiked()->toggle([$idea->id]);//dd($idea->users()->count());
        
        $idea->update(['likes'=> $idea->users()->count()]);

        return redirect()->route('idea.show',$idea);
    }



    public function store(Request $request) : RedirectResponse
    {
        // Validar los datos del formulario
        $validatedData = $request->validate($this->rules,$this->messagesError);

       // $userid = Auth::id();

        Idea::create([
            'user_id'=> Auth::id(),
            'title'=>$validatedData['title'],
            'description'=>$validatedData['description']
        ]);
        session()->flash('message','Idea creada correctamente!!!');
        return redirect()->route('idea.index')->with('success', 'Idea guardada con éxito.');
    }
}
