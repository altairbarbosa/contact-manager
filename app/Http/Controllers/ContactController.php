<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Publicly accessible, no authentication required for index
        $contacts = Contact::all();
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Requires authentication
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Requires authentication
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            'contact' => 'required|string|digits:9|unique:contacts,contact',
            'email' => 'required|string|email|unique:contacts,email',
        ]);

        Contact::create($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Publicly accessible, no authentication required for show
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        // Requires authentication
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // Requires authentication
        $validated = $request->validate([
            'name' => 'required|string|min:5',
            // Rule::unique ignores the current contact's own contact/email
            'contact' => ['required', 'string', 'digits:9', Rule::unique('contacts')->ignore($contact->id)],
            'email' => ['required', 'string', 'email', Rule::unique('contacts')->ignore($contact->id)],
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        // Requires authentication
        $contact->delete(); // Soft delete

        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully (soft deleted)!');
    }
}