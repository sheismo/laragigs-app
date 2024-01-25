<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->tag); dd(request('tag'));
        // get and display all listings
        return view('listings.index', [
            'listings' => Listing::latest()
            ->filter(request(['tag', 'search']))->paginate(6)
            // ->simplePaginate()
        ]);
    }

    // Show single Listing
    public function show(Listing $listing)
    {
        // get and show a single listing
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //Show Create Listing Form
    public function create() {
        return view('listings.create');
    }

    // Store Listing Data
    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => 'required|email',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
        }

        if($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        
        Listing::create($data);

        // Session::flash('message', 'Listing Created');
        return redirect('/')->with('message', 'Listing created successfully!');

    }

    // show Edit Form
    public function edit(Listing $listing) {
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Request $request, Listing $listing) {
        // Ensure user owns listing
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }

        $data = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => 'required|email',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($data);

        // return redirect('/');
        return redirect("listings/{$listing->id}")->with('message', 'Listing updated successfully!');

    }

    //Delete Listing
    public function destroy(Listing $listing) {
        // Ensure user owns listing
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized action');
        }
        
        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted successfully');
    }

    //Manage Listing
    public function manage() {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get() ]);
    }
}
