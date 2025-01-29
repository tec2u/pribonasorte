<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredEmail;
use App\Models\AddressBilling;
use App\Models\CustomLog;
use App\Models\HistoricScore;
use App\Models\Rede;
use App\Models\ShippingPrice;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\IpBlockTrait;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterController extends Controller
{
   use IpBlockTrait;
   /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   use RegistersUsers;

   /**
    * Where to redirect users after registration.
    *
    * @var string
    */
   protected $redirectTo = RouteServiceProvider::HOME;

   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      $this->middleware('guest');
   }

   /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {

      // $data['birthday'] = str_replace("/", "-", $data['birthday']);

      if ((DB::table('users')->where('id', $data['recommendation_user_id'])->orWhere('login', $data['recommendation_user_id'])->exists())) {
         $user_rec = DB::table('users')->where('id', $data['recommendation_user_id'])->orWhere('login', $data['recommendation_user_id'])->first();
         $data['recommendation_user_id'] = $user_rec->id;
      } else {
         Alert::error('Referral User not found!');
         $data['recommendation_user_id'] = null;
      }

      $ip = $this->get_client_ip();
      $login = $data['login'];
      $password = $data['password'];

      return Validator::make($data, [
         'name' => ['required', 'alpha', 'max:255'],
         'login' => ['required', 'alpha_num', 'lowercase', 'max:255', 'unique:users'],
         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
         'password' => ['required', 'regex:/^\S*$/u', 'string', 'min:8', 'confirmed'],
         'cell' => ['required', 'regex:/[0-9\+]/'],
         'country' => ['required', 'string', 'max:255'],
         'city' => ['required', 'string', 'max:255'],
         'last_name' => ['required', 'alpha', 'max:255'],
         'recommendation_user_id' => ['required', 'int'],
      ]);
   }

   /**
    * Create a new user instance after a valid registration.
    *x
    * @param  array  $data
    * @return \App\Models\User
    */
   protected function create(array $data)
   {
      $ip = $this->get_client_ip();
      $login = $data['login'];
      $password = $data['password'];

      $verify = $this->verifyBlacklist($ip, $login, $password);

      $user_rec = DB::table('users')->where('id', $data['recommendation_user_id'])->orWhere('login', $data['recommendation_user_id'])->first();

      $recommendation = $user_rec != null ? $user_rec->id : '3';
      // $data['telephone'] = ($data['telephone']=='') ? 0 :  $data['telephone'];

      $eliminar_espace = str_replace(' ', '', $data['login']);

      // ELIMINAR ALGUM TIPO DE ACENTUAÇÃO
      $comAcentos = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');

      $semAcentos = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U');

      $sem_acent = str_replace($comAcentos, $semAcentos, $eliminar_espace);
      $new_usern = strtolower($sem_acent);

      $user = new User;
      $user->name = $data['name'];

      $user->email = $data['email'];
      $user->name = $data['name'];
      $user->login = $new_usern;
      $user->activated = 0;
      $user->password = Hash::make($data['password']);
      $user->financial_password = Hash::make($data['password']);
      $user->recommendation_user_id = $recommendation;
      $user->special_comission = 1;
      $user->special_comission_active = 0;
      $user->cell = $data['cell'];
      $user->country = $data['country'];
      $user->city = $data['city'];
      $user->last_name = $data['last_name'];
      $user->birthday = date('Y-m-d', strtotime($data['birthday']));
      $user->address1 = $data['address'] ?? '';
      $user->address2 = $data['address2'] ?? '';
      $user->postcode = $data['zip'];
      $user->state = $data['state'] ?? '';
      $user->number_residence = $data['number_residence'] ?? '';
      $user->complement = $data['complement'] ?? '';
      $user->area_residence = $data['area_residence'] ?? '';

      //code...

      if (isset($data['type_account']) && $data['type_account'] == 2) {


         if (
            !isset($data['addressBillingSameDelivery']) && (
               !isset($data['faturing_country']) || !isset($data['faturing_city']) ||
               !isset($data['faturing_zip']) ||
               !isset($data['faturing_address']) || !isset($data['faturing_number_residence'])
            )
         ) {
            throw new Exception("Fill all fields of billing address", 1);
         }


         if (
            isset($data['id_corporate']) && isset($data['corporate_nome']) &&
            isset($data['tax_id']) && isset($data['vat_reg_no'])
         ) {

            $user->id_corporate = $data['id_corporate'];
            $user->corporate_nome = $data['corporate_nome'];
            $user->tax_id = $data['tax_id'];
            $user->vat_reg_no = $data['vat_reg_no'];
         } else {
            throw new Exception("Fill all fields of corporate", 1);
         }
      }


      $user->save();

      if (isset($data['type_account']) && $data['type_account'] == 2) {
         if (
            isset($data['addressBillingSameDelivery']) && isset($data['addressBillingSameDelivery']) == "on"
         ) {
            $billing_address = new AddressBilling;
            $billing_address->user_id = $user->id;
            $billing_address->country = $countryTable->country;
            $billing_address->city = $data['city'];
            $billing_address->zip = $data['zip'];
            $billing_address->state = $data['state'] ?? '';
            $billing_address->address = $data['address'];
            $billing_address->number_residence = $data['number_residence'];
            $billing_address->save();
         } else {
            $billing_address = new AddressBilling;
            $billing_address->user_id = $user->id;
            $billing_address->country = $data['faturing_country'];
            $billing_address->city = $data['faturing_city'];
            $billing_address->zip = $data['faturing_zip'];
            $billing_address->state = $data['faturing_state'] ?? '';
            $billing_address->address = $data['faturing_address'];
            $billing_address->number_residence = $data['faturing_number_residence'];
            $billing_address->save();
         }
      }


      $rede_recommedation = Rede::where('user_id', $recommendation)->first();

      $user->rede()->create([
         "upline_id" => $rede_recommedation->id,
         "qty" => 0,
         "ciclo" => 1,
         "saque" => 0
      ]);

      $rede_recommedation->update([
         "qty" => $rede_recommedation->qty + 1
      ]);

      $sair = 1;
      $count = 1;
      $primeiro_id = $user->id;
      $fato_gerador = $user->id;
      // ini_set('max_execution_time', '300');
      while ($sair == 1) {
         $nivel_self = User::where('id', $fato_gerador)->first();
         if ($nivel_self->recommendation_user_id == NULL)
            break;
         $soma_qty1 = User::where('id', $nivel_self->recommendation_user_id)->first();
         if ($soma_qty1 != NULL && $soma_qty1->recommendation_user_id >= 0) {
            if ($nivel_self->name != "") {
               $check_existe = HistoricScore::where('user_id_from', $primeiro_id)->where('user_id', $soma_qty1->id)->where('description', '6')->first();
               if ($check_existe == NULL) {
                  // HistoricScore::create([
                  //    'score' => '1',
                  //    'user_id' => $soma_qty1->id,
                  //    'status' => '1',
                  //    'description' => '6',
                  //    'level_from' => $count,
                  //    'orders_package_id' => '4710',
                  //    'user_id_from' => $primeiro_id
                  // ]);
                  if ($soma_qty1->qty == NULL) {
                     User::where('id', $nivel_self->recommendation_user_id)->update(['qty' => 1]);
                  } else {
                     User::where('id', $nivel_self->recommendation_user_id)->increment('qty', 1);
                  }
               }
            }
            $nivel1 = User::where('id', $nivel_self->recommendation_user_id)->first();
            $nivel12 = User::where('recommendation_user_id', $nivel1->recommendation_user_id)->first();
            $count++;
            $fato_gerador = $nivel12->id;
         }
      }



      // $dominio = $request->getHost();
      $dominio = "Pribonasorte.eu";
      if (strtolower($dominio) == 'Pribonasorte.eu') {
         $prod = 1;
      } else {
         $prod = 0;
      }
      $this->sendPostRegisterUser($user->id, $prod);
      // ini_set('max_execution_time', '60');

      $this->sendEmailRegister($user->name, $user->email, $user->id);
      $this->sendEmailRegisterNewDirectDistributor($user->id, $user->name, $user->login, $user->email, $user->cell, $user->recommendation_user_id);

      return $user;
      // }
   }

   public function sendEmailRegister($nome, $email, $user_id)
   {

      $client = new Client();

      $url = 'https://api.brevo.com/v3/smtp/email';

      $headers = [
         'Accept' => 'application/json',
         'api-key' => env('API_KEY_BREVO'),
         'Content-Type' => 'application/json',
      ];

      $data = [
         'sender' => [
            'name' => 'Pribonasorte',
            'email' => 'info@Pribonasorte.eu',
         ],
         'to' => [
            [
               'email' => "$email",
               'name' => "$nome",
            ],
         ],
         'subject' => 'Account Created Successfully',
         'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $nome
                                    <br>
                                    <br>
                                    Welcome to Pribonasorte!
                                    <br>
                                    <br>
                                    We're thrilled to have you on board. Your first step on this exciting journey is to become a Pribonasorte Distributor (Independent Distributor).
                                    To get started, click on 'Purchase,' select 'My Packages,' and enroll online by paying the required DISTRIBUTOR fee. This fee is 39.99 EUR annually. You can also purchase the license along with a package/products at the same time.
                                    <br>
                                    <br>
                                    To become an ACTIVE DISTRIBUTOR, you need to make a minimum purchase of 100 QV.
                                    <br>
                                    Please note that you must maintain your DISTRIBUTOR fee to continue as an active distributor. Without a paid annual fee, you won't be able to register new distributors and customers or earn any commissions. Additionally, Fast Start bonuses are calculated from the moment you join.
                                    <br>
                                    <br>
                                    This fee covers:<br>
                                    <ul>
                                       <li>Replicated Pribonasorte Websites</li>
                                       <li>Pribonasorte Secure Back Office</li>
                                       <li>Pribonasorte Training and Support Tools</li>
                                       <li>Administrative Support</li>
                                       <li>Better pricing for products</li>
                                    </ul>
                                    <br>
                                    <br>
                                    This is your opportunity to join a dynamic community dedicated to well-being and prosperity. We're here to support you every step of the way.
                                    Communication is key, and it's very important to follow us on all social media platforms. Immediately after registration, the first steps should be to follow us on our private Facebook group and Telegram, or WhatsApp:
                                    <br>
                                    <br>
                                    <ul>
                                       <li>Private Facebook Group <a href='https://www.facebook.com/groups/467430965762866'>https://www.facebook.com/groups/467430965762866/</a> </li>
                                       <li>Telegram <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0/</a> </li>
                                       <li>WhatsApp <a href='https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j'>https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    Additionally, you can stay connected through:
                                    <br>
                                    <br>
                                     <ul>
                                       <li>Instagram <a href='https://www.instagram.com/Pribonasorte_official'>https://www.instagram.com/Pribonasorte_official/</a> </li>
                                       <li>Facebook <a href='https://www.facebook.com/profile.php?id=61557861185751'>https://www.facebook.com/profile.php?id=61557861185751/</a> </li>
                                       <li>YouTube <a href='https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ'>https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        Pribonasorte Team<br>
                                        Support-  +420 234 688 024<br>
                                        WHATSAPP number- +421 918 142 520
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
      ];

      try {
         $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
         ]);


         $log = new CustomLog;
         $log->content = "Email registration backoffice";
         $log->user_id = $user_id;
         $log->operation = "EMAIL REGISTRATION";
         $log->controller = "EcommRegisterController";
         $log->http_code = 200;
         $log->route = "finalize.register.order";
         $log->status = "success";
         $log->save();

         return $response;
      } catch (\Throwable $th) {
         $log = new CustomLog;
         $log->content = $th->getMessage();
         $log->user_id = $user_id;
         $log->operation = "EMAIL REGISTRATION";
         $log->controller = "Auth/RegisterController";
         $log->http_code = $th->getCode();
         $log->route = "register";
         $log->status = "ERROR";
         $log->save();
      }
   }

   public function sendEmailRegisterNewDirectDistributor($id, $name, $username, $email, $phone, $recommendation_user_id)
   {

      $distributor = User::where('id', $recommendation_user_id)->first();

      $client = new Client();

      $url = 'https://api.brevo.com/v3/smtp/email';

      $headers = [
         'Accept' => 'application/json',
         'api-key' => env('API_KEY_BREVO'),
         'Content-Type' => 'application/json',
      ];

      $data = [
         'sender' => [
            'name' => 'Pribonasorte',
            'email' => 'info@Pribonasorte.eu',
         ],
         'to' => [
            [
               'email' => "$distributor->email",
               'name' => "$distributor->name",
            ],
         ],
         'subject' => 'Account Created Successfully',
         'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $distributor->name
                                    <br>
                                    <br>
                                    We're excited to inform you that a new distributor has registered using your referral link.
                                    <br>
                                    <br>
                                    Here are their details: <br>
                                    Name: $name <br>
                                    Username: $username <br>
                                    Email: $email <br>
                                    Phone: $phone <br>
                                    <br>
                                    <br>
                                    Please reach out to them to provide support as they begin their journey. Helping your new distributor understand our business model is key to their success and yours. Together, you can build a thriving network and reach your goals.
                                    <br>
                                    <br>
                                    In the first 72 hours, assist them in inviting 20 people to attend a Pribonasorte company presentation. These presentations can be conducted online, via Zoom, webinar, or in-person.
                                    <br>
                                    <br>
                                    Communication is key, and it's very important to follow us on all social media platforms. Immediately after registration, the first steps should be to follow us on our private Facebook group and Telegram, or WhatsApp:
                                    <br>
                                    <ul>
                                       <li>Private Facebook Group <a href='https://www.facebook.com/groups/467430965762866'>https://www.facebook.com/groups/467430965762866/</a> </li>
                                       <li>Telegram <a href='https://t.me/+4pRjuBp4Pw1kYjY0'>https://t.me/+4pRjuBp4Pw1kYjY0/</a> </li>
                                       <li>WhatsApp <a href='https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j'>https://chat.whatsapp.com/DzI11cuddPu4BMhl64827j/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    Additionally, you can stay connected through:
                                    <br>
                                    <br>
                                     <ul>
                                       <li>Instagram <a href='https://www.instagram.com/Pribonasorte_official'>https://www.instagram.com/Pribonasorte_official/</a> </li>
                                       <li>Facebook <a href='https://www.facebook.com/profile.php?id=61557861185751'>https://www.facebook.com/profile.php?id=61557861185751/</a> </li>
                                       <li>YouTube <a href='https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ'>https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ/</a> </li>
                                    </ul>
                                    <br>
                                    <br>
                                    <div style='display:flex;justify-content: space-between;width:100%;gap:2rem;'>
                                        <div>
                                        Best Regards, <br>
                                        JURAJ MOJZIS<br>
                                        CEO of Pribonasorte<br>
                                        Support- +420234688024<br>
                                        WHATSAPP number- +420 721 530 732
                                        </div>

                                        <div>
                                            <img src='https://Pribonasorte.eu/img/ceo_Pribonasorte.jpeg' alt='CEO'
                                            style='width:100px;height:100px;display:block;margin-left:16px;' />
                                        </div>
                                    </div>
                                 </p>
                              </body>
                           </html>",
      ];

      try {
         $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
         ]);


         $log = new CustomLog;
         $log->content = "SEND EMAIL DISTRIBUTORS, WHEN THEY ENROLL CUSTOMERS backoffice";
         $log->user_id = $id;
         $log->operation = "SEND EMAIL DISTRIBUTORS, WHEN THEY ENROLL CUSTOMERS";
         $log->controller = "EcommRegisterController";
         $log->http_code = 200;
         $log->route = "finalize.register.order";
         $log->status = "success";
         $log->save();

         return $response;
      } catch (\Throwable $th) {
         $log = new CustomLog;
         $log->content = $th->getMessage();
         $log->user_id = $id;
         $log->operation = "SEND EMAIL DISTRIBUTORS, WHEN THEY ENROLL CUSTOMERS";
         $log->controller = "EcommRegisterController";
         $log->http_code = $th->getCode();
         $log->route = "finalize.register.order";
         $log->status = "ERROR";
         $log->save();
      }
   }

   public function sendPostRegisterUser($id_user, $prod)
   {
      $client = new \GuzzleHttp\Client();

      $headers = [
         'Content-Type' => 'application/x-www-form-urlencoded',
         'Accept' => 'application/json'
      ];

      $data = [
         "type" => "Contador",
         "param" => "NewUser",
         "idusuario" => "$id_user",
         "prod" => "$prod"
      ];

      $url = 'https://Pribonasorte.eu/public/compensacao/bonificacao.php';

      try {
         $resposta = $client->post($url, [
            'form_params' => $data,
            // 'headers' => $headers,

         ]);

         $statusCode = $resposta->getStatusCode();
         $body = $resposta->getBody()->getContents();

         parse_str($body, $responseData);
         $responseDataString = json_encode($responseData);

         $log = new CustomLog;
         $log->content = $responseDataString;
         $log->user_id = $id_user;
         $log->operation = $data['type'] . "/" . $data['param'] . "/" . $data['idusuario'];
         $log->controller = "Auth/RegisterController";
         $log->http_code = $statusCode;
         $log->route = "https://Pribonasorte.eu/register";
         $log->status = "Success";
         $log->save();

      } catch (\Throwable $th) {
         return false;
      }



   }

   function get_client_ip()
   {
      $ipaddress = '';
      if (isset($_SERVER['HTTP_CLIENT_IP']))
         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
      else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
      else if (isset($_SERVER['HTTP_X_FORWARDED']))
         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
      else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
      else if (isset($_SERVER['HTTP_FORWARDED']))
         $ipaddress = $_SERVER['HTTP_FORWARDED'];
      else if (isset($_SERVER['REMOTE_ADDR']))
         $ipaddress = $_SERVER['REMOTE_ADDR'];
      else
         $ipaddress = 'UNKNOWN';
      return $ipaddress;
   }

   public function ResetPassword(Request $request)
   {

      $register = User::where('email', $request->email)->first();

      if (isset($register)) {

         $update_user = User::find($register->id);

         $code = random_int(100000, 999999);

         $update_user->update([
            'validate_code' => $code,
         ]);

         $capture_user = User::find($update_user->id);

         $this->sendEmailRecover($capture_user->name, $capture_user->email, $capture_user->id, $capture_user->validate_code);
         // Mail::to($mail)->send(new MailValidate($register));

         return view('auth.passwords.reset');
      } else {
         return redirect()->route('password.request');
      }
   }

   public function UpdatePassword(Request $request)
   {

      $code = $request->code;

      $user = User::where('validate_code', $code)->first();

      if (isset($user)) {

         // password and encryption
         $password = trim($request->password);
         $checkpass = trim($request->checkpass);

         if ($password != $checkpass) {

            session()->flash('erro', 'Passwords do not match!');
            return redirect()->route('password.request');
         }

         $pass_encrypt = Hash::make($password);

         $user->update([
            'password' => $pass_encrypt,
         ]);

         return view('auth.login');

      } else {
         return view('auth.passwords.reset');
      }
   }

   public function sendEmailRecover($nome, $email, $user_id, $code)
   {
      $client = new Client();

      $url = 'https://api.brevo.com/v3/smtp/email';

      $headers = [
         'Accept' => 'application/json',
         'api-key' => env('API_KEY_BREVO'),
         'Content-Type' => 'application/json',
      ];

      $data = [
         'sender' => [
            'name' => 'Pribonasorte',
            'email' => 'info@Pribonasorte.eu',
         ],
         'to' => [
            [
               'email' => "$email",
               'name' => "$nome",
            ],
         ],
         'subject' => 'Account Created Successfully',
         'htmlContent' => "<html>
                              <head>
                              </head>
                              <body>
                                 <div style='background-color:#480D54;width:100%;'>
                                 <img src='https://Pribonasorte.eu/img/Logo_AuraWay.png' alt='Pribonasorte Logo' width='300'
                                    style='height:auto;display:block;' />
                                 </div>
                                 <p>
                                    Hello, $nome
                                    <br>
                                    Thank you for registering with us.
                                    <br>
                                    <br>
                                    Enter the code below on the password recovery page to reset your access:
                                    <br>
                                    $code
                                 </p>
                              </body>
                           </html>",
      ];

      try {
         $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
         ]);

         $log = new CustomLog;
         $log->content = "Email recover backoffice";
         $log->user_id = $user_id;
         $log->operation = "EMAIL recover backoffice";
         $log->controller = "EcommRegisterController";
         $log->http_code = 200;
         $log->route = "finalize.register.order";
         $log->status = "success";
         $log->save();

         return $response;
      } catch (\Throwable $th) {
         $log = new CustomLog;
         $log->content = $th->getMessage();
         $log->user_id = $user_id;
         $log->operation = "EMAIL REGISTRATION";
         $log->controller = "EcommRegisterController";
         $log->http_code = $th->getCode();
         $log->route = "finalize.register.order";
         $log->status = "ERROR";
         $log->save();
      }
   }
}

