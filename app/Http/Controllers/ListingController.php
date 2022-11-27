<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index(){
        
        return view('listings.index', [
            'listings' => Listing::latest()->
            filter(request(['tag', 'search']))->paginate(6)//ile chcesz wyswietlic (odrazu rozdziela na strony ?page=2), get() - wez wszystkie
        ]);
    }
    public function show(Listing $listing){
        return view('listings.show', [
            'listing' => $listing
            ]);
    }
    //show create form
    public function create(){
        return view('listings.create');
    }
    //store listing data
    public function store(Request $request){
        // dd($request->file('logo')->store());
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings','company')],//(tabela, wiersz w tabeli)
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

    if($request->hasFile('logo')){
        $formFields['logo'] = $request->file('logo')
        ->store('logos', 'public');
    }
    $formFields['user_id'] = auth()->id();

    Listing::create($formFields);

        //rozne sposoby:
        //Session::flash('message','Listing Created'); Import potrzebny


        return redirect('/')->with('message','Listing created succesfully');
    }
    //show edit form
    public function edit(Listing $listing){
       
        return view('listings.edit', ['listing'=>$listing]);
    }
    public function update(Request $request, Listing $listing){
        // dd($request->file('logo')->store());

        //Make sure logged user is owner
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],//(tabela, wiersz w tabeli)
            'location' => 'required',
            'website' => 'required',
            'email' => ['required','email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

    if($request->hasFile('logo')){
        $formFields['logo'] = $request->file('logo')
        ->store('logos', 'public');
    }
    

        $listing->update($formFields);

        return back()->with('message','Listing updated succesfully');
    }
    //Delete listing
    public function destroy(Listing $listing){
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('message','Listing Deleted Successfully');
    }
    public function manage(){
        return view('listings.manage', ['listings'=>auth()->user()->listings()->get()]);
    
    }
}

