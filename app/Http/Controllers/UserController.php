<?php

namespace App\Http\Controllers;

use App\Models\AddressBilling;
use App\Models\AddressSecondary;
use App\Models\ShippingPrice;
use App\Models\User;
use App\Traits\CustomLogTrait;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Alert;
use App\Models\Package;
use App\Models\OrderPackage;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Attribute\Cache;
use App\Http\Controllers\ClubSwanController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use CustomLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;

        $user = User::find($id);

        $address2 = AddressSecondary::where('id_user', $id)->where('backoffice', 1)->first();
        $billing = AddressBilling::where('user_id', $id)->where('backoffice', 1)->first();
        $allCountry = ShippingPrice::orderBy('country', 'ASC')->get();

        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('user.myinfo', compact('user', 'countPackages', 'allCountry', 'address2', 'billing'));
    }

    public function password()
    {
        $id_user = Auth::id();
        $openProduct = OrderPackage::where('user_id', $id_user)->where('payment_status', 1)->where('status', 1)->orderBy('id', 'DESC')->get();
        $countPackages = count($openProduct);

        return view('user.password', compact('countPackages'));
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


        $message = [
            "name.alpha" => "the name cannot contain symbols and spaces",
            "last_name.alpha" => "the last name cannot contain symbols and spaces",
        ];


        $validateData = $request->validate([
            'name' => ['required', 'alpha', 'max:255'],
            'last_name' => ['required', 'alpha', 'max:255'],
            'address1' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'telephone' => ['numeric'],
            'cell' => ['required', 'numeric'],
            'country' => ['required', 'string', 'max:255'],
        ], $message);


        $data = $request->only([
            'name',
            'password',
            'last_name',
            'birthday',
            'address1',
            'postcode',
            'state',
            'city',
            'gender',
            'email',
            'telephone',
            'area_residence',
            'number_residence',
            'cell',
            'country'
        ]);

        $data['telephone'] = $request->get('countryCodePhone') . $data['telephone'];
        $data['cell'] = $request->get('countryCodeCell') . $data['cell'];
        $data['country_code_cel'] = $request->get('countryCodeCell');
        $data['country_code_fone'] = $request->get('countryCodePhone');
        // $data['address2'] = $request->get('address2') ?? null;
        $data['complement'] = $request->get('complement') ?? null;

        if ($request->hasFile('image')) {
            $user = User::find($id);
            // dd($user);

            $oldImagePath = public_path($user->image_path);

            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('user'), $imageName);
            $data['image_path'] = 'user/' . $imageName;
        }



        $user = User::find($id);

        if (!Hash::check($request->get('password'), $user->password)) {
            Alert::error(__('backoffice_alert.current_password_is_not_correct'));
            return redirect()->back();
        }

        if ($request->get('wallet') != NULL && $request->get('wallet') == 'change_wallet') {
            $chat = new ChatController;
            $chat->storeWallet(array('title' => 'Wallet Change Request', 'text' => 'I request the change of my wallet because the previously registered one is not suitable.'));
        }

        if ($user->wallet->first() == NULL) {
            if ($request->get('wallet') != NULL && $request->get('wallet') != 'change_wallet') {
                $datawallet = [
                    "wallet" => $request->get('wallet'),
                    "description" => "wallet"
                ];
                $user->wallet()->create($datawallet);
                $this->createLog('Wallet created successfully -> ' . $request->get('wallet'), 200, 'success', auth()->user()->id);
            }
        } else {
            if ($request->get('wallet') != NULL && $request->get('wallet') != 'change_wallet') {
                $datawallet = [
                    "wallet" => $request->get('wallet'),
                ];
                $wallet = $user->wallet()->first();
                $oldWallet = $wallet->wallet;
                $wallet->update($datawallet);
                $this->createLog('Wallet updated successfully -> NEW: ' . $request->get('wallet') . ' | OLD: ' . $oldWallet, 200, 'success', auth()->user()->id);
            }
        }

        if ($request->btn_user == "update_user_api") {
            $club = new ClubSwanController;
            $clubResponse = $club->singUp($data);
            if (isset($clubResponse->status) && $clubResponse->status == 'success') {
                $data['contact_id'] = $clubResponse->data->contactId;
            } else {
                throw new Exception(json_encode($clubResponse));
            }
        }

        if ($request->btn_user == "update_user") {
            unset($data['password']);
            $user->update($data);
            // dd($user);
        }

        // dd($data);

        $this->createLog('User updated successfully', 200, 'success', auth()->user()->id);
        Alert::success(__('backoffice_alert.user_update'));
        return redirect()->route('users.index');
    }

    public function changePassword(Request $request)
    {
        $data = $request->only([
            'password',
            'old_password'
        ]);

        try {
            $id = auth()->user()->id;

            $user = User::find($id);
            if (!Hash::check($data['old_password'], $user->password)) {
                Alert::error(__('backoffice_alert.current_password_is_not_correct'));
                return redirect()->back();
            }

            $password = Hash::make($data['password']);

            $user->update([
                'password' => $password
            ]);
            $this->createLog('Password updated successfully', 200, 'success', auth()->user()->id);
            Alert::success(__('backoffice_alert.password_changed'));
            return redirect()->route('users.password');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            Alert::error(__('backoffice_alert.password_not_changed'));

            return redirect()->route('users.password');
        }
    }

    public function register($id)
    {
        Auth::logout();

        $packages = Package::where('activated', 1)->where('type', '!=', 'activator')->orderBy('price')->get();

        $user = User::where('id', $id)->first();

        $allCountry = ShippingPrice::orderBy('country', 'ASC')->get();

        if ($user->payFirstOrder()) {
            return view('auth.register', compact('id', 'packages', 'user', 'allCountry'));
        } else {
            Alert::error('Your indicator must be active for you to register!');
            return view('auth.register_must_active');
        }
    }

    public function updateAddress2(Request $request)
    {
        $adress = AddressSecondary::where('id_user', auth()->user()->id)->where('backoffice', 1)->first();
        if (isset($adress)) {
            $adress->phone = $request->cell;
            $adress->first_name = $request->first_name;
            $adress->last_name = $request->last_name;
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number = $request->number;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state ?? '';
            $adress->country = $request->country;

            $adress->save();
        } else {
            $adress = new AddressSecondary;

            $adress->id_user = auth()->user()->id;
            $adress->phone = $request->cell;
            $adress->first_name = $request->first_name;
            $adress->last_name = $request->last_name;
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number = $request->number;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state ?? '';
            $adress->country = $request->country;
            $adress->backoffice = 1;

            $adress->save();
        }

        return redirect()->back();
    }

    public function updateBilling(Request $request)
    {
        $adress = AddressBilling::where('user_id', auth()->user()->id)->where('backoffice', 1)->first();
        if (isset($adress)) {
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number_residence = $request->number;
            $adress->city = $request->city;
            // $adress->state = $request->state;
            $adress->country = $request->country;

            $adress->save();
        } else {
            $adress = new AddressBilling;

            $adress->user_id = auth()->user()->id;
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number_residence = $request->number;
            $adress->city = $request->city;
            $adress->state = '';
            $adress->country = $request->country;

            $adress->save();
        }

        return redirect()->back();
    }
}
