<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Address;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = auth()->user();
        $addresses = $user->addresses;
        $profileUpdated = session('profile_updated', false);
        return view('profile.edit', compact('user', 'addresses', 'profileUpdated'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthdate' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Store the file in the 'profile-photos' directory in the public disk
            $avatarPath = $request->file('avatar')->store('profile-photos', 'public');
            // Update the user's profile_photo_path field
            $user->update(['profile_photo_path' => $avatarPath]);
        }

        $user->update($request->only('name', 'email', 'phone', 'birthdate'));
        
        // Refresh the user data to ensure we have the latest avatar path
        $user->refresh();

        return redirect()->route('profile.edit')->with('profile_updated', true)->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }
    
    /**
     * Display the user's addresses.
     *
     * @return \Illuminate\View\View
     */
    public function addresses()
    {
        $addresses = auth()->user()->addresses;
        return view('profile.addresses.index', compact('addresses'));
    }
    
    /**
     * Show the form for creating a new address.
     *
     * @return \Illuminate\View\View
     */
    public function createAddress()
    {
        return view('profile.addresses.create');
    }
    
    /**
     * Store a newly created address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $address = new Address($request->all());
        $address->user_id = auth()->id();
        
        // If this is the first address, make it default
        if (auth()->user()->addresses->count() == 0) {
            $address->is_default = true;
        }
        
        $address->save();

        return redirect()->route('profile.edit')->with('success', 'Address added successfully.');
    }
    
    /**
     * Show the form for editing an address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\View\View
     */
    public function editAddress(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('profile.addresses.edit', compact('address'));
    }
    
    /**
     * Update an address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $address->update($request->all());

        return redirect()->route('profile.edit')->with('success', 'Address updated successfully.');
    }
    
    /**
     * Remove an address.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAddress(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Prevent deletion of the default address if it's the only one
        if ($address->is_default && auth()->user()->addresses->count() == 1) {
            return back()->withErrors(['error' => 'You cannot delete your only address. Please add another address first.']);
        }
        
        // If we're deleting the default address, set another one as default
        if ($address->is_default && auth()->user()->addresses->count() > 1) {
            $anotherAddress = auth()->user()->addresses->firstWhere('id', '!=', $address->id);
            if ($anotherAddress) {
                $anotherAddress->update(['is_default' => true]);
            }
        }
        
        $address->delete();

        return back()->with('success', 'Address deleted successfully.');
    }
    
    /**
     * Set an address as default.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setDefaultAddress(Address $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Set all other addresses as non-default
        auth()->user()->addresses()->update(['is_default' => false]);
        
        // Set this address as default
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated successfully.');
    }
}