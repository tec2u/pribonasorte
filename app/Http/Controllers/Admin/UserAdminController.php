<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SearchRequest;
use App\Models\AddressBilling;
use App\Models\AddressSecondary;
use App\Models\Banco;
use App\Models\Career;
use App\Models\CareerUser;
use App\Models\ConfigBonus;
use App\Models\CustomLog;
use App\Models\EcommOrders;
use App\Models\EcommRegister;
use App\Models\HistoricScore;
use App\Models\OrderPackage;
use App\Models\Rede;
use App\Models\ShippingPrice;
use App\Models\User;
use App\Traits\CustomLogTrait;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserAdminController extends Controller
{
    use CustomLogTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(9);

        $user_stts = User::get();

        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }


        return view('admin.users.users', compact('users', 'all_states'));
    }

    public function alterCareer(Request $request)
    {
        $search = $request['search'];
        $status = isset($request['status']) ? $request['status'] : -1;
        $query = User::whereNull('id_corporate')
            ->whereNull('corporate_nome')
            ->with('lastMonthCareerUser')
            ->orderBy('id', 'DESC');


        if ($search) {
            $query->where('name', 'like', "%$search%")->orWhere('login', 'like', "%$search%");
        }

        if ($status != -1) {
            $query->where('activated', $status);
        }
        $users = $query->paginate(9);

        $careers = Career::all();

        // return response()->json($users);
        return view('admin.users.alter_career', compact('users', 'careers', 'search', 'status'));
    }

    public function updateCareer(Request $request)
    {

        $career_id = $request['career_change'];
        $user_id = $request['user_id'];

        $createdAt = Carbon::now()->subMonth()->endOfMonth()->setTime(23, 59, 59);

        CareerUser::unguard();

        // // Cria uma nova instância de CareerUser e define os atributos, incluindo created_at e updated_at
        CareerUser::create([
            'user_id' => $user_id,
            'career_id' => $career_id,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);

        // // Restaura a proteção dos atributos no modelo
        CareerUser::reguard();
        return redirect()->route('admin.users.alter_career');
    }

    public function customers()
    {
        $users = EcommRegister::orderBy('id', 'DESC')->paginate(9);

        $user_stts = EcommRegister::all();
        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $country = $sponsor->country;
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
                $country = '';
            }

            $user->sponsor = $sponsor;
            $user->country_sponsor = $country;
        }

        return view('admin.users.customers', compact('users', 'all_states'));
    }

    public function filterStatesCustomers(Request $request)
    {

        $user_stts = EcommRegister::all();
        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        if (isset($request->states)) {
            $users = EcommRegister::where('country', $request->states)->paginate(9);
        } else {
            $users = EcommRegister::paginate(9);
        }

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $country = $sponsor->country;
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
                $country = '';
            }

            $user->sponsor = $sponsor;
            $user->country_sponsor = $country;
        }

        return view('admin.users.customers', compact('users', 'all_states'));
    }

    public function searchUsersCustomers(Request $request)
    {
        try {
            $data = $request->search;

            $users = EcommRegister::where('name', 'like', '%' . $data . '%')
                // ->orWhere('username', 'like', '%' . $data . '%')
                ->orWhere('email', 'like', '%' . $data . '%')
                ->paginate(9);

            $user_stts = EcommRegister::all();
            $all_states = array();

            foreach ($user_stts as $states) {

                if (!in_array($states->country, $all_states)) {
                    array_push($all_states, $states->country);
                }
            }

            foreach ($users as $user) {
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $country = $sponsor->country;
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                    $country = '';
                }

                $user->sponsor = $sponsor;
                $user->country_sponsor = $country;
            }

            return view('admin.users.customers', compact('users', 'all_states'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            return redirect()->back();
        }
    }

    public function filterStates(Request $request)
    {
        $users = User::whereNull('id_corporate')
            ->whereNull('corporate_nome')
            ->orderBy('id', 'DESC')
            ->paginate(9);

        $user_stts = User::whereNull('id_corporate')
            ->whereNull('corporate_nome')->get();
        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        $filter_users = User::where('country', $request->states)
            ->whereNull('id_corporate')
            ->whereNull('corporate_nome')
            ->get();

        foreach ($filter_users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }

        return view('admin.users.users', compact('filter_users', 'all_states'));
    }

    public function searchUsers(SearchRequest $request)
    {
        try {
            $data = $request->search;
            $users = User::where('name', 'like', '%' . $data . '%')
                ->whereNull('id_corporate')
                ->whereNull('corporate_nome')
                ->orWhere('login', 'like', '%' . $data . '%')
                ->orWhere('email', 'like', '%' . $data . '%')
                ->paginate(9);

            foreach ($users as $user) {
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $user->sponsor = $sponsor;
            }

            return view('admin.users.users', compact('users'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            return redirect()->back();
        }
    }
    public function UsersFilter($parameter)
    {
        try {
            $users = User::whereNull('id_corporate')
                ->whereNull('corporate_nome')->orderBy('id', 'DESC');

            //Filters
            // switch ($parameter) {
            //     case 'activated':
            //         $users->where('activated', true);
            //         break;
            //     case 'desactivated':
            //         $users->where('activated', false);
            //         break;
            // }
            $users = $users->paginate(9);

            foreach ($users as $user) {
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $user->sponsor = $sponsor;
            }

            return view('admin.users.users', compact('users', 'parameter'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            return redirect()->back();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexInactive()
    {
        $users = User::whereNull('id_corporate')
            ->whereNull('corporate_nome')->orderBy('id', 'DESC')->where('activated', false)->paginate(9);

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }

        return view('admin.users.users', compact('users'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBan()
    {
        $users = User::orderBy('id', 'DESC')->where('ban', true)->paginate(9);
        return view('admin.users.users', compact('users'));
    }
    public function myinfo()
    {
        $user = User::find(auth()->user()->id);
        $ordersPackages = OrderPackage::where('user_id', $user)->get();
        $billing = AddressBilling::where('user_id', auth()->user()->id)->where('backoffice', 1)->first();
        $countryes = ShippingPrice::all();
        $address2 = AddressSecondary::where('id_user', auth()->user()->id)->where('backoffice', 1)->first();

        return view('admin.users.myinfo', compact('user', 'ordersPackages', 'countryes', 'billing', 'address2'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $ordersPackages = OrderPackage::where('user_id', $user->id)->get();
        $billing = AddressBilling::where('user_id', $user->id)->where('backoffice', 1)->first();
        $countryes = ShippingPrice::all();
        $address2 = AddressSecondary::where('id_user', $user->id)->where('backoffice', 1)->first();

        return view('admin.users.myinfo', compact('user', 'ordersPackages', 'countryes', 'billing', 'address2'));
    }

    public function editEcomm($id)
    {
        $user = EcommRegister::find($id);
        if (!isset($user))
            abort(404);
        $addressBilling = AddressBilling::where('user_id', $user->id)->where('backoffice', 0)->first();
        $AddressSecondary = AddressSecondary::where('id_user', $user->id)->where('backoffice', 0)->first();
        $allCountry = ShippingPrice::all();

        return view('admin.users.myinfoEcomm', compact('user', 'allCountry', 'AddressSecondary', 'addressBilling'));
    }

    public function updateEcomm(Request $request)
    {
        $register = EcommRegister::findOrFail($request->id);
        $register->update([
            'name' => $request->name,
            'username' => $request->username,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'corporate_name' => $request->corporate_name,
            'fantasy_name' => $request->fantasy_name,
            'id_corporate' => $request->id_corporate,
            'identity_card' => $request->identity_card,
            'birth' => $request->birth,
            'sex' => $request->sex,
            'phone' => $request->phone,
            'zip' => $request->zip,
            'address' => $request->address,
            'number' => $request->number,
            'city' => $request->city,
            'state' => '',
            'country' => $request->country,
        ]);

        $password = trim($request->password);
        $checkpass = trim($request->checkpass);

        if (!empty($password) and !empty($checkpass)) {
            // password and encryption

            if ($password != $checkpass) {

                // session()->flash('erro', 'Passwords do not match!');
                flash('Passwords do not match!')->error();
                return redirect()->back();
            }

            $pass_encrypt = Hash::make($password);
            $register->update(['password' => $pass_encrypt]);
            // $register->password = $pass_encrypt;
        }

        if (
            isset($request->address_billing) &&
            isset($request->number_billing) &&
            isset($request->city_billing) &&
            isset($request->zip_billing) &&
            // isset($request->state_billing) &&
            isset($request->country_billing)
        ) {

            $addressBilling = AddressBilling::where('user_id', $request->id)->where('backoffice', 0)->first();
            if (isset($addressBilling)) {
                $addressBilling->address = $request->address_billing;
                $addressBilling->number_residence = $request->number_billing;
                $addressBilling->city = $request->city_billing;
                $addressBilling->zip = $request->zip_billing;
                $addressBilling->state = '';
                $addressBilling->country = $request->country_billing;
                $addressBilling->save();
            } else {
                $address2 = new AddressBilling;
                $address2->user_id = $register->id;
                $address2->backoffice = 0;
                $address2->address = $request->address_billing;
                $address2->number_residence = $request->number_billing;
                $address2->city = $request->city_billing;
                $address2->zip = $request->zip_billing;
                $address2->state = '';
                $address2->country = $request->country_billing;
                $address2->save();
            }
        }

        flash('Updated!')->success();
        return redirect()->back();
    }

    public function updateSecondAddressEcomm(Request $request)
    {
        $userLogin = EcommRegister::find($request->id);

        $adress = AddressSecondary::where('id_user', $userLogin->id)->where('backoffice', 0)->first();

        if (isset($adress)) {
            $adress->phone = $request->cell;
            $adress->first_name = $request->first_name;
            $adress->last_name = $request->last_name;
            $adress->zip = $request->zip;
            $adress->address = $request->address;
            $adress->number = $request->number;
            $adress->phone = $request->phone;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state ?? '';
            $adress->country = $request->country;

            $adress->save();
        } else {
            $adress = new AddressSecondary;

            $adress->id_user = $userLogin->id;
            $adress->first_name = $request->first_name;
            $adress->last_name = $request->last_name;
            $adress->phone = $request->cell;
            $adress->zip = $request->zip;
            $adress->address = $request->address;
            $adress->number = $request->number;
            $adress->phone = $request->phone;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state ?? '';
            $adress->country = $request->country;
            $adress->backoffice = 0;

            $adress->save();
        }

        flash('Updated!')->success();
        return redirect()->back();
    }

    public function transactions($id, $date = null)
    {
        if (isset($date)) {

            $transactions = DB::table('banco')
                ->join('users', 'users.id', '=', 'banco.user_id')
                ->where('banco.user_id', $id)
                ->whereIn('banco.order_id', function ($query) use ($date) {
                    $query->select('number_order')
                        ->from('payments_order_ecomms')
                        ->where('created_at', 'LIKE', "%" . $date . "%");
                })
                ->select('banco.*')
                ->paginate(9);
        }

        foreach ($transactions as $item) {
            $order = EcommOrders::where('number_order', $item->order_id)->get();
            $user = User::where('id', $item->user_id)->first();
            if (isset($user)) {
                $item->username = $user->name . ' ' . $user->last_name;
            }
            $item->qv = 0;
            $item->cv = 0;
            if (count($order) > 0) {
                $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
            }


            $config_bonus = ConfigBonus::where('id', $item->description)->first();
            if ($item->description != 99 && $item->description != 98) {
                $item->config_bonus_description = $config_bonus->description;
            } elseif ($item->description == 99) {
                $item->config_bonus_description = 'Payment order';
            }
        }

        // dd($transactions);

        $bonus = ConfigBonus::all();
        // $transactions = Banco::where('user_id', $id)->paginate(9);
        return view('admin.reports.transactions', compact('transactions', 'bonus'));
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

        $type_account = $request->type_account ?? 0;
        $name = $request->name;
        $last_name = $request->last_name;
        $address1 = $request->address1;
        $address2 = $request->address2;
        $postcode = $request->postcode;
        $state = $request->state;
        $city = $request->city;
        $area_residence = $request->area_residence;
        $complement = $request->complement;
        $country = $request->country;
        $gender = $request->gender;
        $email = $request->email;
        $number_residence = $request->number_residence;
        $cell = $request->cell;
        $telephone = $request->telephone;
        $birthday = $request->birthday;
        $rule = $request->rule;



        // $data = $request->only([
        //     'name',
        //     'last_name',
        //     'address1',
        //     'address2',
        //     'postcode',
        //     'state',
        //     'area_residence',
        //     'complement',
        //     'city',
        //     'birthday',
        //     'number_residence',
        //     'gender',
        //     'email',
        //     'telephone',
        //     'cell',
        //     'country',
        //     'rule'
        // ]);
        $user = User::find($id);

        if (isset($request->pay_vat) && $request->pay_vat != '') {
            $user = User::find($id);
            $user->pay_vat = $request->pay_vat;
            $user->save();
        }

        if ($type_account == 1) {
            $user = User::find($id);
            $user->id_corporate = null;
            $user->corporate_nome = null;
            $user->activated_corporate = 0;
            $user->pay_vat = 1;

            $user->save();
        } else {

            if (isset($request->id_corporate) && isset($request->corporate_nome) && isset($request->activated_corporate)) {
                $user = User::find($id);
                $user->id_corporate = $request->id_corporate;
                $user->corporate_nome = $request->corporate_nome;
                $user->tax_id = $request->tax_id;
                $user->vat_reg_no = $request->vat_reg_no;
                $user->activated = $request->activated;
                $user->activated_corporate = $request->activated_corporate;

                $user->save();

                if (isset($request->billing_postcode) && isset($request->billing_address) && isset($request->billing_number) && isset($request->billing_country) && isset($request->billing_city)) {
                    # code...


                    try {
                        //code...

                        $adress = AddressBilling::where('user_id', $user->id)->first();
                        if (isset($adress)) {
                            $adress->zip = $request->billing_postcode;
                            $adress->address = $request->billing_address;
                            $adress->number_residence = $request->billing_number;
                            $adress->city = $request->billing_city;
                            $adress->state = '';
                            $adress->country = $request->billing_country;

                            $adress->save();
                        } else {
                            $adress = new AddressBilling;

                            $adress->user_id = $user->id;
                            $adress->zip = $request->billing_postcode;
                            $adress->address = $request->billing_address;
                            $adress->number_residence = $request->billing_number;
                            $adress->city = $request->billing_city;
                            $adress->state = '';
                            $adress->country = $request->billing_country;

                            $adress->save();
                        }
                    } catch (\Throwable $th) {
                        return redirect()->back()->withErrors(['error' => 'Failed in update']);
                    }
                } else {
                    return redirect()->back()->withErrors(['error' => 'address billing required']);
                }
            } else if (!isset($request->id_corporate) || !isset($request->corporate_nome)) {
                return redirect()->back()->withErrors(['error' => 'Fill all fields.']);
            }
        }


        if (($request->has('password') && $request->get('password')) && ($request->has('password_confirmation') && $request->get('password_confirmation'))) {
            if ($request->get('password') == $request->get('password_confirmation')) {
                $password = Hash::make($request->get('password'));
            } else {
                flash(__('admin_alert.password_confirm'))->warning();
                return redirect()->route('admin.users.edit', ['id' => $user->id]);
            }
        }

        try {
            // $user->update($data);

            $user->update([
                'name' => $name,
                'last_name' => $last_name,
                'address1' => $address1,
                'address2' => $address2,
                'postcode' => $postcode,
                'state' => $state,
                'area_residence' => $area_residence,
                'complement' => $complement,
                'city' => $city,
                'birthday' => $birthday,
                'number_residence' => $number_residence,
                'gender' => $gender,
                'email' => $email,
                'telephone' => $telephone,
                'cell' => $cell,
                'country' => $country,
                'rule' => $rule
            ]);

            if (isset($password)) {
                $user->update(['password' => $password]);
            }

            $this->createLog('User updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.user_update'))->success();
            return redirect()->route('admin.users.edit', ['id' => $user->id]);
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.user_noupdate'))->error();
            return redirect()->route('admin.users.edit', ['id' => $user->id]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactive($id)
    {
        try {
            $user = User::find($id);
            $user->activated = false;
            $user->update();
            $this->createLog('User inactive successfully', 204, 'success', auth()->user()->id);
            flash(__('admin_alert.user_inactive'))->success();
            return redirect()->route('admin.users.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.user_notremove'))->error();
            return redirect()->back();
        }
    }

    public function active($id)
    {
        try {
            $user = User::find($id);
            $user->activated = true;
            $user->update();
            $this->createLog('User active successfully', 204, 'success', auth()->user()->id);
            flash('user active')->success();
            return redirect()->route('admin.users.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.user_notremove'))->error();
            return redirect()->back();
        }
    }
    public function networkuser($parameter)
    {
        $rede = Rede::where('user_id', $parameter)->first();
        $name = empty($rede->upline_id) ? "" : Rede::find($rede->upline_id)->user->name;
        $redename = $rede->user->login;
        $id = $rede->id;
        $qty = $rede->qty;
        $email = $rede->user->email;
        $volume = $rede->user->getVolume($rede->user->id);
        $tag = '';
        $pay = OrderPackage::where('user_id', $rede->user->id)->where('status', 1)->where('payment_status', 1)->first();
        $getadessao = $rede->user->getAdessao($rede->user->id);
        $getpackages = $rede->user->getPackages($rede->user->id);
        if (!$pay) {
            $tag = ["Inactive"];
        }
        if ($getadessao > 0) {
            $tag = ["PreRegistration"];
        }
        if ($getpackages > 0) {
            $tag = ["AllCards"];
        }
        $rede_users = Rede::where('upline_id', $id)->get()->count();
        if ($rede_users > 0) {
            $network = $this->getNetwork($rede->id);
            $networks[] = array(
                "id" => "$id",
                "name" => "$redename",
                "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                "size" => ".$qty",
                "referred" => $name,
                "email" => $email,
                "volume" => "Volume: $volume",
                "tags" => $tag
            );
            $networks = array_merge($network, $networks);
        } else {
            $network = $this->getNetwork($rede->id);
            $networks = array(
                array(
                    "id" => "$id",
                    "name" => "$redename",
                    "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                    "size" => ".$qty",
                    "referred" => $name,
                    "volume" => "Volume: $volume",
                    "tags" => $tag
                )
            );
        }
        //$networks += $this->getNetwork($rede->id);
        // {
        //     id: 1,
        //     name: "Amber McKenzie",
        //     img: "https://cdn.balkan.app/shared/empty-img-none.svg",
        //     size: 10,
        //     referred: "master"
        // },
        $networks = json_encode($networks);
        //$networks = str_replace(array("\n", "\r"), '', $networks);
        //dd($networks);
        return view('admin.users.rede', compact('networks'));
    }
    public function networkuserdiferente($parameter)
    {
        $rede = Rede::where('user_id', $parameter)->first();
        $name = empty($rede->upline_id) ? "" : Rede::find($rede->upline_id)->user->login;
        $redename = $rede->user->login;
        $id = $rede->id;
        $network = $this->getNetworkDiferente($rede->id);
        if ($network != NULL) {
            $networks['tree'] = array($id => $network['tree']);
            $networks['params'] = array(
                $id => array(
                    'trad' => $redename . ' </br>',
                    'styles' => array(
                        'font-weight' => '600',
                        'font-size' => '18px',
                        'background-color' => '#f3f3f37a',
                        'color' => 'red',
                        'box-shadow' => '0 0 4px 1px #aeaeae',
                        'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                    )
                )
            );
            $networks['params'] = $network['params'] + $networks['params'];
        } else {
            $networks['tree'] = array($id => '');
            $networks['params'] = array(
                $id => array(
                    'trad' => $redename . ' </br>',
                    'styles' => array(
                        'font-weight' => '600',
                        'font-size' => '18px',
                        'background-color' => '#f3f3f37a',
                        'color' => 'red',
                        'box-shadow' => '0 0 4px 1px #aeaeae',
                        'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                    )
                )
            );
        }
        $tree = json_encode($networks['tree']);
        $params = json_encode($networks['params']);
        return view('admin.users.rede_diferente', compact('tree', 'params'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ban($id)
    {
        try {
            $user = User::find($id);
            $user->ban = true;
            $user->update();
            $this->createLog('User ban successfully', 204, 'success', auth()->user()->id);
            flash(__('admin_alert.userban'))->success();
            return redirect()->route('admin.users.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.user_notban'))->error();
            return redirect()->back();
        }
    }
    public function password()
    {
        return view('admin.changePassword.changePassword');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
                flash(__('admin_alert.changepassword_error'))->error();
                return redirect()->back();
            }
            $password = Hash::make($data['password']);
            $user->update([
                'password' => $password
            ]);
            $this->createLog('Password updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.changepassword'))->success();
            return redirect()->route('admin.users.password');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.changepassword_error2'))->error();
            return redirect()->back();
        }
    }
    private function getNetwork($id, $cont = '')
    {
        $cont = empty($cont) ? 1 : $cont;
        $rede_users = Rede::where('upline_id', $id)->get();
        $networks = array();
        foreach ($rede_users as $rede) {
            $name = empty(Rede::find($rede->upline_id)) ? "" : Rede::find($rede->upline_id)->first()->user->name;
            $redename = $rede->user->login;
            $id = $rede->id;
            $qty = $rede->qty;
            $email = $rede->user->email;
            $upline = $rede->upline_id;
            $volume = $rede->user->getVolume($rede->user->id);
            $tag = '';
            $pay = OrderPackage::where('user_id', $rede->user->id)->where('status', 1)->where('payment_status', 1)->first();
            $getadessao = $rede->user->getAdessao($rede->user->id);
            $getpackages = $rede->user->getPackages($rede->user->id);
            if (!$pay) {
                $tag = ["Inactive"];
            }
            if ($getadessao > 0) {
                $tag = ["PreRegistration"];
            }
            if ($getpackages > 0) {
                $tag = ["AllCards"];
            }
            $networks[] = array(
                "id" => "$id",
                "pid" => "$upline",
                "name" => "$redename",
                "img" => "https://cdn.balkan.app/shared/empty-img-none.svg",
                "size" => "$qty",
                "referred" => $name,
                "email" => $email,
                "volume" => "Volume: $volume ",
                "btn" => "<a href='" . route('admin.users.network', ['parameter' => $rede->user->id]) . "'> More + </a>",
                "tags" => $tag
            );
            $cont++;
            if ($cont < 3) {
                $networks = array_merge($this->getNetwork($rede->id, $cont), $networks);
            }
        }
        //dd($networks);
        return $networks;
    }
    private function getNetworkDiferente($parameter)
    {
        $redes = Rede::where('upline_id', $parameter)->get();
        if ($redes == NULL)
            return NULL;
        $networks = array();
        foreach ($redes as $rede) {
            $redename = $rede->user->login;
            $id = $rede->id;
            $network = $this->getNetworkDiferente($rede->id);
            if ($network != NULL) {
                if (isset($networks['tree'])) {
                    $networks['tree'] = $networks['tree'] + array($id => $network['tree']);
                    $networks['params'] = $networks['params'] + array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('admin.users.network', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                } else {
                    $networks['tree'] = array($id => $network['tree']);
                    $networks['params'] = array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('admin.users.network', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                }
                $networks['params'] = $network['params'] + $networks['params'];
            } else {
                if (isset($networks['tree'])) {
                    $networks['tree'] = $networks['tree'] + array($id => '');
                    $networks['params'] = $networks['params'] + array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('admin.users.network', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                } else {
                    $networks['tree'] = array($id => '');
                    $networks['params'] = array(
                        $id => array(
                            'trad' => $redename . ' </br> <a style="font-size: 14px; color: #111111; text-decoration: none !important;display: flex;justify-content: flex-end"href="' . route('admin.users.network', ['parameter' => $rede->user->id]) . '"> More + </a>',
                            'styles' => array(
                                'font-weight' => '600',
                                'font-size' => '18px',
                                'background-color' => '#f3f3f37a',
                                'color' => 'red',
                                'box-shadow' => '0 0 4px 1px #aeaeae',
                                'font-family' => '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
                            )
                        )
                    );
                }
            }
        }
        return $networks;
    }
    public function dashboard($id)
    {
        $user = User::find($id);
        $this->sendPostBonificacaoLogin($id, 1);
        Auth::login($user);

        return redirect()->intended('home');
    }

    public function sendPostBonificacaoLogin($id_user, $prod)
    {
        $client = new \GuzzleHttp\Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json'
        ];

        $data = [
            "type" => "bonificacao",
            "param" => "GeraCarreira",
            "prod" => $prod,
            "idusuario" => "$id_user"
        ];

        $url = 'https://Pribonasorte.eu/public/compensacao/bonificacao.php';

        try {
            //code...

            $resposta = $client->post($url, [
                'form_params' => $data,
                // 'headers' => $headers,

            ]);

            $statusCode = $resposta->getStatusCode();
            $body = $resposta->getBody()->getContents();

            parse_str($body, $responseData);

            try {
                //code...
                $log = new CustomLog;
                $log->content = json_encode($responseData);
                $log->user_id = $id_user;
                $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idusuario'];
                $log->controller = "app/middleware/isAdmin";
                $log->http_code = 200;
                $log->route = "login_backoffice";
                $log->status = "SUCCESS";
                $log->save();
            } catch (\Throwable $th) {
                return;
            }
        } catch (\Throwable $th) {
            return;
        }

        return $responseData;
    }

    public function dashboardEcomm($id)
    {
        $user = EcommRegister::find($id);
        session()->put('buyer', $user);
        return redirect()->route('page.panel.ecomm');
    }

    public function specialcomission($id)
    {
        $user = User::find($id);
        return view('admin.users.specialcomission', compact('user'));
    }
    public function upspecialcomission(Request $request, $id)
    {
        $data = $request->only([
            'special_comission',
            'special_comission_active'
        ]);
        $user = User::find($id);
        try {
            $user->update($data);
            $this->createLog('User updated successfully', 200, 'success', auth()->user()->id);
            flash(__('admin_alert.user_update'))->success();
            return redirect()->route('admin.users.index');
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            flash(__('admin_alert.user_noupdate'))->error();
            return redirect()->route('admin.users.index');
        }
    }

    public function corporates()
    {
        $users = User::where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)
            ->orderBy('id', 'DESC')
            ->paginate(9);

        $user_stts = User::where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)->get();

        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }

        return view('admin.users.usersCorporate', compact('users', 'all_states'));
    }

    public function filterStatesCorporate(Request $request)
    {
        $users = User::where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)
            ->orderBy('id', 'DESC')
            ->paginate(9);

        $user_stts = User::where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)->get();
        $all_states = array();

        foreach ($user_stts as $states) {

            if (!in_array($states->country, $all_states)) {
                array_push($all_states, $states->country);
            }
        }

        $filter_users = User::where('country', $request->states)
            ->where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)
            ->get();

        foreach ($filter_users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }

        return view('admin.users.usersCorporate', compact('filter_users', 'all_states'));
    }

    public function searchUsersCorporate(SearchRequest $request)
    {
        try {
            $data = $request->search;
            $users = User::where(function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data . '%')
                    ->orWhere('login', 'like', '%' . $data . '%')
                    ->orWhere('email', 'like', '%' . $data . '%');
            })
                ->whereNotNull('id_corporate')
                ->whereNotNull('corporate_nome')
                ->paginate(9);

            foreach ($users as $user) {
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $user->sponsor = $sponsor;
            }

            return view('admin.users.usersCorporate', compact('users'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            return redirect()->back();
        }
    }

    public function UsersFilterCorporate($parameter)
    {
        try {
            $users = User::where('id_corporate', '<>', null)
                ->where('corporate_nome', '<>', null)->orderBy('id', 'DESC');

            //Filters
            // switch ($parameter) {
            //     case 'activated':
            //         $users->where('activated', true);
            //         break;
            //     case 'desactivated':
            //         $users->where('activated', false);
            //         break;
            // }
            $users = $users->paginate(9);

            foreach ($users as $user) {
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $user->sponsor = $sponsor;
            }

            return view('admin.users.usersCorporate', compact('users', 'parameter'));
        } catch (Exception $e) {
            $this->errorCatch($e->getMessage(), auth()->user()->id);
            return redirect()->back();
        }
    }

    public function indexInactiveCorporate()
    {
        $users = User::where('id_corporate', '<>', null)
            ->where('corporate_nome', '<>', null)->orderBy('id', 'DESC')->where('activated', false)->paginate(9);

        foreach ($users as $user) {
            if (isset($user->recommendation_user_id)) {
                $sponsor = User::where('id', $user->recommendation_user_id)->first();
                $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
            } else {
                $sponsor = '';
            }

            $user->sponsor = $sponsor;
        }

        return view('admin.users.usersCorporate', compact('users'));
    }

    public function careerUser(Request $request)
    {
        // dd($request);

        $filter = $request->filter ?? null;
        $date1 = $request->date1 ?? null;
        $date2 = $request->date2 ?? null;
        $usern = $request->user_name ?? null;

        $date = "";

        if (isset($usern) and isset($filter) and isset($date1) and isset($date2)) {

            $user_id = User::where('login', 'like', "%$usern%")->first();

            $exp1 = explode('/', $date1);
            $exp2 = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $carreiras = DB::table('career_users as cu_current')
                ->select(
                    'cu_current.user_id',
                    DB::raw('MAX(cu_current.career_id) as career_id'),
                    'users.login',
                    'users.name',
                    DB::raw('MAX(career.name) as career_name'),
                    DB::raw('MAX(cu_current.updated_at) as updated_at'),
                    'cu_max.career_id as max_career_id',
                    'max_career.name as max_career_name'
                )
                ->join('users', 'users.id', '=', 'cu_current.user_id')
                ->join('career', 'career.id', '=', 'cu_current.career_id')
                ->join(DB::raw('(SELECT user_id, MAX(career_id) as career_id
                             FROM career_users
                             GROUP BY user_id) as cu_max'), 'cu_max.user_id', '=', 'cu_current.user_id')
                ->join('career as max_career', 'max_career.id', '=', 'cu_max.career_id')
                ->where('cu_current.career_id', '>', 2)
                ->where('cu_current.user_id', '=', $user_id->id)
                ->where('cu_current.updated_at', '>', $newdate1)
                ->where('cu_current.updated_at', '<', $newdate2)
                ->groupBy('cu_current.user_id', 'users.login', 'users.name', 'cu_max.career_id', 'max_career.name')
                ->orderBy('users.id')
                ->paginate(100);

                $carreirasCollection = $carreiras->getCollection();

                if ($filter) {
                    $carreirasCollection = $carreirasCollection->reject(function ($usere) use ($filter) {
                        return $usere->career_id != $filter;
                    });
                }

                // Atualiza a coleção do paginador
                $carreiras->setCollection($carreirasCollection);

                foreach ($carreiras as $usere) {
                    $user = User::where('id', $usere->user_id)->first();
                    if (isset($user->recommendation_user_id)) {
                        $sponsor = User::where('id', $user->recommendation_user_id)->first();
                        $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                    } else {
                        $sponsor = '';
                    }

                    $usere->sponsor = $sponsor;
                }
        } elseif (isset($usern) and isset($date1) and isset($date2)) {

            $user_id = User::where('login', 'like', "%$usern%")->first();

            $exp1 = explode('/', $date1);
            $exp2 = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $carreiras = DB::table('career_users as cu_current')
                ->select(
                    'cu_current.user_id',
                    DB::raw('MAX(cu_current.career_id) as career_id'),
                    'users.login',
                    'users.name',
                    DB::raw('MAX(career.name) as career_name'),
                    DB::raw('MAX(cu_current.updated_at) as updated_at'),
                    'cu_max.career_id as max_career_id',
                    'max_career.name as max_career_name'
                )
                ->join('users', 'users.id', '=', 'cu_current.user_id')
                ->join('career', 'career.id', '=', 'cu_current.career_id')
                ->join(DB::raw('(SELECT user_id, MAX(career_id) as career_id
                             FROM career_users
                             GROUP BY user_id) as cu_max'), 'cu_max.user_id', '=', 'cu_current.user_id')
                ->join('career as max_career', 'max_career.id', '=', 'cu_max.career_id')
                ->where('cu_current.career_id', '>', 2)
                ->where('cu_current.user_id', '=', $user_id->id)
                ->where('cu_current.updated_at', '>', $newdate1)
                ->where('cu_current.updated_at', '<', $newdate2)
                ->groupBy('cu_current.user_id', 'users.login', 'users.name', 'cu_max.career_id', 'max_career.name')
                ->orderBy('users.id')
                ->paginate(100);

            foreach ($carreiras as $usere) {
                $user = User::where('id', $usere->user_id)->first();
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $usere->sponsor = $sponsor;
            }
        } elseif (isset($filter) and isset($date1) and isset($date2)) {

            $exp1 = explode('/', $date1);
            $exp2 = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $carreiras = DB::table('career_users as cu_current')
                ->select(
                    'cu_current.user_id',
                    DB::raw('MAX(cu_current.career_id) as career_id'),
                    'users.login',
                    'users.name',
                    DB::raw('MAX(career.name) as career_name'),
                    DB::raw('MAX(cu_current.updated_at) as updated_at'),
                    'cu_max.career_id as max_career_id',
                    'max_career.name as max_career_name'
                )
                ->join('users', 'users.id', '=', 'cu_current.user_id')
                ->join('career', 'career.id', '=', 'cu_current.career_id')
                ->join(DB::raw('(SELECT user_id, MAX(career_id) as career_id
                             FROM career_users
                             GROUP BY user_id) as cu_max'), 'cu_max.user_id', '=', 'cu_current.user_id')
                ->join('career as max_career', 'max_career.id', '=', 'cu_max.career_id')
                ->where('cu_current.career_id', '>', 2)
                ->where('cu_current.updated_at', '>', $newdate1)
                ->where('cu_current.updated_at', '<', $newdate2)
                ->groupBy('cu_current.user_id', 'users.login', 'users.name', 'cu_max.career_id', 'max_career.name')
                ->orderBy('users.id')
                ->paginate(100);
            // dd($carreiras);
            $carreirasCollection = $carreiras->getCollection();

            if ($filter) {
                $carreirasCollection = $carreirasCollection->reject(function ($usere) use ($filter) {
                    return $usere->career_id != $filter;
                });
            }

            // Atualiza a coleção do paginador
            $carreiras->setCollection($carreirasCollection);

            foreach ($carreiras as $usere) {
                $user = User::where('id', $usere->user_id)->first();
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $usere->sponsor = $sponsor;
            }
        } elseif (isset($date1) and isset($date2)) {

            $exp1 = explode('/', $date1);
            $exp2 = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $carreiras = DB::table('career_users as cu_current')
                ->select(
                    'cu_current.user_id',
                    DB::raw('MAX(cu_current.career_id) as career_id'),
                    'users.login',
                    'users.name',
                    DB::raw('MAX(career.name) as career_name'),
                    DB::raw('MAX(cu_current.updated_at) as updated_at'),
                    'cu_max.career_id as max_career_id',
                    'max_career.name as max_career_name'
                )
                ->join('users', 'users.id', '=', 'cu_current.user_id')
                ->join('career', 'career.id', '=', 'cu_current.career_id')
                ->join(DB::raw('(SELECT user_id, MAX(career_id) as career_id
                             FROM career_users
                             GROUP BY user_id) as cu_max'), 'cu_max.user_id', '=', 'cu_current.user_id')
                ->join('career as max_career', 'max_career.id', '=', 'cu_max.career_id')
                ->where('cu_current.career_id', '>', 2)
                ->where('cu_current.updated_at', '>', $newdate1)
                ->where('cu_current.updated_at', '<', $newdate2)
                ->groupBy('cu_current.user_id', 'users.login', 'users.name', 'cu_max.career_id', 'max_career.name')
                ->orderBy('users.id')
                ->paginate(100);

            foreach ($carreiras as $usere) {
                $user = User::where('id', $usere->user_id)->first();
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $usere->sponsor = $sponsor;
            }
        } else {
            $carreiras = DB::table('career_users as cu_current')
                ->select(
                    'cu_current.user_id',
                    DB::raw('MAX(cu_current.career_id) as career_id'),
                    'users.login',
                    'users.name',
                    DB::raw('MAX(career.name) as career_name'),
                    DB::raw('MAX(cu_current.updated_at) as updated_at'),
                    'cu_max.career_id as max_career_id',
                    'max_career.name as max_career_name'
                )
                ->join('users', 'users.id', '=', 'cu_current.user_id')
                ->join('career', 'career.id', '=', 'cu_current.career_id')
                ->join(DB::raw('(SELECT user_id, MAX(career_id) as career_id
                             FROM career_users
                             GROUP BY user_id) as cu_max'), 'cu_max.user_id', '=', 'cu_current.user_id')
                ->join('career as max_career', 'max_career.id', '=', 'cu_max.career_id')
                ->where('cu_current.career_id', '>', 2)
                ->where('cu_current.updated_at', 'like', "%" . date('Y-m') . "%")
                ->groupBy('cu_current.user_id', 'users.login', 'users.name', 'cu_max.career_id', 'max_career.name')
                ->orderBy('users.id')
                ->paginate(100);

            // return response()->json($carreiras);
            foreach ($carreiras as $usere) {
                $user = User::where('id', $usere->user_id)->first();
                if (isset($user->recommendation_user_id)) {
                    $sponsor = User::where('id', $user->recommendation_user_id)->first();
                    $sponsor = $sponsor->name . ' ' . $sponsor->last_name;
                } else {
                    $sponsor = '';
                }

                $usere->sponsor = $sponsor;
            }
        }

        $tiposCarreira = Career::all();


        return view('admin.users.usersCareer', compact('carreiras', 'tiposCarreira', 'filter', 'date', 'usern', 'date1', 'date2'));
    }

    public function careerUserFirst(Request $request)
    {
        // dd($request);

        $date1 = $request->date1 ?? null;
        $date2 = $request->date2 ?? null;
        $usern = $request->user_name ?? null;

        $date = "";

        $filters = "";

        if ($usern) {
            $filters .= " AND U.login LIKE '%$usern%'";
        }

        if ($date1 && $date2) {
            $exp1 = explode('/', $date1);
            $exp2 = explode('/', $date2);

            $newdate1 = "$exp1[2]-$exp1[1]-$exp1[0] 00:00:00";
            $newdate2 = "$exp2[2]-$exp2[1]-$exp2[0] 23:59:59";

            $filters .= " AND C.created_at >= '$newdate1' AND C.created_at <= '$newdate2'";
        }

        $user = auth()->user();

        $Allcarreiras = DB::select("SELECT user_id, name, login, last_name, created_at, id, updated_at, career_name, career_id, sponsor,
            (SELECT COUNT(*)
            FROM career_users C2
            WHERE C2.user_id = subquery.user_id
            AND C2.career_id = subquery.career_id) AS count_career_id
        FROM (
            SELECT U.id AS user_id, U.name, U.login ,U.last_name, C.created_at, C.updated_at, C1.name AS career_name, C1.id, U2.name as sponsor,
                ROW_NUMBER() OVER (PARTITION BY U.id ORDER BY C.career_id DESC) AS row_num,
                C.career_id
            FROM users U
            LEFT JOIN career_users C ON U.id = C.user_id
            LEFT JOIN career C1 ON C1.id = C.career_id
            LEFT JOIN users U2 ON U.recommendation_user_id=U2.id
            WHERE C.career_id > 2 $filters
        ) AS subquery
        WHERE row_num = 1");

        $allCareerUsers = [];
        $carreiras = [];
        foreach ($Allcarreiras as $career) {
            if ($career->count_career_id == 1) {
                $carreiras[] = $career;
            }
        }
        $tiposCarreira = Career::all();

        return view('admin.users.usersCareerFirst', compact('carreiras', 'tiposCarreira', 'date', 'usern', 'date1', 'date2'));
    }

    public function careerUserEdit($id_user = null)
    {
        $user = null;

        if (isset($id_user)) {
            $user = User::where('id', $id_user)->first();
        }

        $carreiras = Career::all();

        return view('admin.users.usersCareerEdit', compact('carreiras', 'user'));
    }

    public function careerUserUpdate(Request $request)
    {
        $user = User::where('id', $request->user)->orWhere('login', $request->user)->first();
        if (!isset($user)) {
            abort(404);
        }

        abort(404);
    }

    public function updateAddress2(Request $request)
    {
        $adress = AddressSecondary::where('id', $request->id_address)->where('backoffice', 1)->first();
        if (isset($adress)) {
            $adress->phone = $request->cell;
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number = $request->number;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state;
            $adress->country = $request->country;
            $adress->backoffice = 1;

            $adress->save();
        } else {
            $adress = new AddressSecondary;

            $adress->id_user = $request->user_id;
            $adress->phone = $request->cell;
            $adress->zip = $request->postcode;
            $adress->address = $request->address1;
            $adress->number = $request->number;
            $adress->complement = $request->complement;
            $adress->neighborhood = $request->neighborhood;
            $adress->city = $request->city;
            $adress->state = $request->state;
            $adress->country = $request->country;
            $adress->backoffice = 1;
            $adress->save();
        }

        return redirect()->back();
    }

    public function sendBrevo(Request $request)
    {
        try {
            //code...
            set_time_limit(240);
            $userIntern = User::whereNotIn('id', [1, 2])->where('sended_brevo', 0)->get();
            $userExtern = EcommRegister::where('sended_brevo', 0)->get();

            foreach ($userIntern as $user) {
                if ($this->saveContactBrevo($user)) {

                    $user->sended_brevo = 1;
                    $user->save();
                }
                // dd($user);
            }

            foreach ($userExtern as $user) {

                if ($this->saveContactBrevo($user)) {
                    # code...
                    $user->sended_brevo = 1;
                    $user->save();
                }
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }

    private function saveContactBrevo($user)
    {
        try {
            //code...
            $client = new Client();
            $url = 'https://api.brevo.com/v3/contacts';
            $headers = [
                'Accept' => 'application/json',
                'api-key' => env('API_KEY_BREVO'),
                'Content-Type' => 'application/json',
            ];
            $body = [
                'email' => $user->email,
                'attributes' => [
                    'FNAME' => $user->name,
                    'LNAME' => $user->last_name ?? '',
                ],
                'listIds' => [2],
                'emailBlacklisted' => false,
                'updateEnabled' => true,
            ];

            $response = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $body,
            ]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            // dd($body);
            $log = new CustomLog;

            $log->content = json_encode($body);
            $log->user_id = 1;
            $log->operation = "SEND EMAIL BREVO BY ADMIN";
            $log->controller = "admin/userAdminController";
            $log->http_code = 200;
            $log->route = "send.email.brevo";
            $log->status = "success";
            $log->save();

            return true;
        } catch (\Throwable $th) {
            $log = new CustomLog;
            $log->content = $th->getMessage();
            $log->user_id = 1;
            $log->operation = "SEND EMAIL BREVO BY ADMIN";
            $log->controller = "admin/userAdminController";
            $log->http_code = $th->getCode();
            $log->route = "send.email.brevo";
            $log->status = "ERROR";
            $log->save();

            $log = new CustomLog;
            $log->content = env('API_KEY_BREVO');
            $log->user_id = 1;
            $log->operation = "SEND EMAIL BREVO BY ADMIN key";
            $log->controller = "admin/userAdminController";
            $log->http_code = $th->getCode();
            $log->route = "send.email.brevo";
            $log->status = "ERROR";
            $log->save();

            return false;
        }
    }
}
