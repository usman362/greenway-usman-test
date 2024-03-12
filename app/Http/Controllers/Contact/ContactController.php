<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $contacts = Contact::all();
            $table = DataTables::of($contacts);
            $table->addColumn('action', function ($row) {
                $action =
                    '<a href="javascript:void(0)" class="text-info edit" data-id="' . $row->id . '" data-toggle="modal" data-target="#addContact"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0)" class="text-danger delete" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a>';
                return $action;
            });
            return $table->make(true);
        }
        return view('contacts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateContactRequest $request)
    {
        dd($request->all());
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
        return response()->json(['message' => 'Contact Created Successfully', 'data' => $contact], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::find($id);
        return response()->json(['contact' => $contact], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, string $id)
    {
        $contact = Contact::find($id);
        if ($contact) {
            $contact->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            return response()->json(['message' => 'Contact Updated Successfully', 'data' => $contact],201);
        } else {
            return response()->json(['message' => 'Contact not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        return response()->json(['message' => 'Contact has been Deleted Successfully!'], 200);
    }
}
