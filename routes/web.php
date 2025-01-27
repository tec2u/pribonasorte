<?php

use App\Http\Controllers\Admin\CategoriaAdminController;
use App\Http\Controllers\Admin\ConfigBonusController;
use App\Http\Controllers\Admin\DocumentsAdminController;
use App\Http\Controllers\Admin\GeraBonusAdminController;
use App\Http\Controllers\Admin\InvestmentAdminController;
use App\Http\Controllers\Admin\IpWhitelistAdminController;
use App\Http\Controllers\Admin\IpBlacklistAdminController;
use App\Http\Controllers\Admin\LibraryPdfAdminController;
use App\Http\Controllers\Admin\OrderAdmin\AdminOrderController;
use App\Http\Controllers\Admin\PackageAdminController;
use App\Http\Controllers\Admin\PayoutAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\ProductByCountryAdminController;
use App\Http\Controllers\Admin\SequenceAdminController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\WithdrawsAdminController;
use App\Http\Controllers\Admin\ReportsAdminController;
use App\Http\Controllers\Admin\SettingsAdminController;
use App\Http\Controllers\Admin\VideoAdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HistoricScoreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IpWhitelistController;
use App\Http\Controllers\LangingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\EcommController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\EcommRegisterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SmartshippingPaymentsRecurringController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\NewletterController;
use App\Mail\UserRegisteredEmail;
use App\Models\ConfigBonusunilevel;
use App\Models\EcommRegister;
use GuzzleHttp\Client;
use App\Models\User as UserReset;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\VideosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#region pagina inicial
// Route::get('/', function () {
//    return view('welcome.welcome');
// });

Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password/reset', [RegisterController::class, "ResetPassword"])->name("password.email");

Route::post('/forgot-password/update', [RegisterController::class, "UpdatePassword"])->name("password.update");

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Route::post('/reset-password', function (Request $request) {
//     $request->validate([
//         'token' => 'required',
//         'email' => 'required|email',
//         'password' => 'required|min:8|confirmed',
//     ]);

//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function (UserReset $user, string $password) {
//             $user->forceFill([
//                 'password' => Hash::make($password)
//             ])->setRememberToken(Str::random(60));

//             $user->save();

//             event(new PasswordReset($user));
//         }
//     );

//     return $status === Password::PASSWORD_RESET
//         ? redirect()->route('login')->with('status', __($status))
//         : back()->withErrors(['email' => [__($status)]]);
// })->middleware('guest')->name('password.update');

Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, Config::get('app.locales'))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'welcome')->name('.welcome');
    Route::get('/dev/newsite', 'newsite')->name('.welcome');
    Route::get('/fees', 'fees')->name('.fees');
});

Route::get('/ipayout', [PayoutAdminController::class, "index"])->name("payout.index");

//ECOMM


// Route::get('/bonus', [GeraBonusAdminController::class, "index"]);

Route::get('/ecomm', [EcommController::class, "index"])->name("ecomm");

Route::get('/ecomm/type/{id}', [EcommController::class, "categoria"])->name("ecomm.categoria");

Route::get('/ecomm/detals/{id}', [EcommController::class, "detals"])->name("detals");

Route::get('/ecomm/detals/invoice/{id}', [EcommController::class, "invoicePDF"])->name("invoicePDF");

Route::get('/tracking', [EcommController::class, "tracking"])->name("tracking");

Route::get('/ecomm/detals/new/invoice/{id}', [EcommController::class, "newInvoicePDF"])->name("newInvoicePDF");

Route::get('/ecomm/detals/orderslip/{id}', [EcommController::class, "orderslipPDF"])->name("orderslipPDF");

Route::post('/ecomm/add/cart', [EcommController::class, "addCart"])->name("add.cart.ecomm");

Route::post('/ecomm/cancel/smartshipping', [EcommController::class, "cancelSmartshipping"])->name("cancel.smartshipping");

Route::post('/ecomm/choose/day/smartshipping', [EcommController::class, "chooseDayForSmartshipping"])->name("choose.day.smartshipping");

Route::get('/ecomm/cart', [EcommController::class, "cart"])->name("index.cart");

Route::post('/ecomm/calculate/shipping', [EcommController::class, "CalculateShipping"])->name("calculate.shipping");

Route::get('/ecomm/delete/cart/{id}', [EcommController::class, "deleteCart"])->name("delete.cart");

Route::get('/ecomm/up/amount/cart/{id}', [EcommController::class, "upAmount"])->name("up.amount");

Route::get('/ecomm/down/amount/cart/{id}', [EcommController::class, "downAmount"])->name("down.amount");

Route::get('/ecomm/clear/cart', [EcommController::class, "clearCart"])->name("clear.cart");

Route::get('/ecomm/finalize/shop', [EcommController::class, "FinalizeShop"])->name("finalize.shop");

Route::get('/ecomm/finalize/shop/smart', [EcommController::class, "FinalizeShopSmart"])->name("finalize.shop.smart");

Route::post('/ecomm/finalize/notify', [EcommRegisterController::class, "notify"])->name("finalize.notify");

Route::post('/ecomm/finalize/register/order/crypto', [EcommRegisterController::class, "PayCrypto"])->name("finalize.register.order.crypto");

Route::post('/ecomm/finalize/register/order/comgate', [EcommRegisterController::class, "PayComgate"])->name("finalize.register.order.comgate");

Route::post('/ecomm/finalize/register/order/pay/smartshipping', [EcommRegisterController::class, "PaySmart"])->name("finalize.register.order.pay.smart");

Route::post('/ecomm/finalize/register/order/comgate/retry', [EcommRegisterController::class, "RetryPayComgate"])->name("finalize.register.order.retry.comgate");

Route::post('/ecomm/finalize/register/order/crypto/retry', [EcommRegisterController::class, "RetryPayCrypt"])->name("finalize.register.order.retry.crypt");

Route::post('/ecomm/finalize/register/order', [EcommRegisterController::class, "RegisterUser"])->name("finalize.register.order");

Route::post('/ecomm/update/register', [EcommRegisterController::class, "UpdateRegister"])->name("update.register");

Route::post('/ecomm/update/second/address', [EcommRegisterController::class, "UpdateSecondRegister"])->name("update.second.address");

Route::post('/ecomm/user/log', [EcommRegisterController::class, "LogUserEcomm"])->name("log.user.ecomm");

Route::get('/ecomm/login', [EcommController::class, "PageLogin"])->name("page.login.ecomm");

Route::get('/ecomm/panel', [EcommController::class, "PagePanel"])->name("page.panel.ecomm");

Route::get('/ecomm/panel/orders', [EcommController::class, "PageOrdersPanel"])->name("orders.panel.ecomm");

Route::get('/ecomm/panel/orders/detal/{id}', [EcommController::class, "PageOrdersDetalPanel"])->name("orders_detal.panel.ecomm");

Route::get('/ecomm/panel/settings', [EcommController::class, "PageSettingsPanel"])->name("orders.settings.ecomm");

Route::get('/ecomm/panel/migrate/user', [EcommRegisterController::class, "migrateUser"])->name("orders.migrate.user.ecomm");

Route::get('/ecomm/panel/smartship/report', [EcommController::class, "PageSmartshipReport"])->name("orders.smartshipReport.ecomm");

Route::get('/ecomm/panel/qv/report', [EcommController::class, "PageQVReport"])->name("orders.qvReport.ecomm");

Route::get('/ecomm/panel/invoices/report', [EcommController::class, "PageInvoicesReport"])->name("orders.invoicesReport.ecomm");

Route::get('/payment-render/{id}', [EcommController::class, "renderPayment"])->name("payment_render.ecomm");

Route::get('/ecomm/user/logout', [EcommRegisterController::class, "logOutUser"])->name("logout.user.ecomm");

Route::get('/ecomm/user/recover', [EcommController::class, "RecoverPassword"])->name("recover.ecomm");

Route::get('/ecomm/user/replice/password', [EcommController::class, "pageReplicePass"])->name("replace_pass.ecomm");

Route::post('/ecomm/user/recover/validate', [EcommRegisterController::class, "RecoverValidade"])->name("recover_validade.ecomm");

Route::get('/ecomm/user/recover/alter/password', [EcommRegisterController::class, "AlterPassword"])->name("alter_password.ecomm");

Route::get('/ecomm/user/register', [EcommController::class, "RegisterUser"])->name("register.user.ecomm");

Route::get('/ecomm/user/register/referral/{id}', [EcommController::class, "RegisterUserReferral"])->name("register.user.ecomm.referral");

Route::post('/ecomm/user/registered', [EcommRegisterController::class, "RegisteredUser"])->name("registered.user.ecomm");

Route::get('/ecomm/shipping/registered', [EcommRegisterController::class, "RegisteredShipping"])->name("registered.shipping.ecomm");

Route::post('/ecomm/newsletter/registered', [NewletterController::class, "NewsletterRegister"])->name("registered.newsletter.ecomm");

Route::get('/ecomm/newsletter/delete/{id}', [NewletterController::class, "DeleteEmailNewsletter"])->name("delete.newsletter.ecomm");

Route::post('/ecomm/address/secondary/registered', [EcommRegisterController::class, "RegisteredAddressSecondary"])->name("registered.address.secondary");


//WEBSITE
Route::get('/site', function () {
    return view('site');
});

Route::get('/shop/true_omega', function () {
    return view('shop_true_omega');
})->name('true_omega');

Route::get('/shop/melatonin', function () {
    return view('shop_melatonin');
})->name('melatonin');

Route::get('/molecule_of_life', function () {
    return view('molecule_of_life');
})->name('molecule_of_life');

Route::get('/vitamin_and_minerals', function () {
    return view('vitamin_and_minerals');
})->name('vitamin_and_minerals');

Route::get('/test_kit', function () {
    return view('test_kit');
})->name('test_kit');

Route::get('/shop/{page?}', function ($page) {
    switch ($page) {
        case 'true_omega':
            return view('shop_true_omega');

        case 'melatonin':
            return view('shop_melatonin');

        case 'molecule_of_life':
            return view('molecule_of_life');

        case 'vitamin_and_minerals':
            return view('vitamin_and_minerals');

        case 'test_kit':
            return view('test_kit');

        default:
            // abort(404);
            break;
    }
});

Route::get('/general-terms-and-conditions', function () {
    return view('newGeneral_terms_conditions');
})->name('general_terms_conditions');

Route::get('/return-policy', function () {
    return view('newReturnPolicy');
})->name('return_policy');

Route::get('/gdpr-policy', function () {
    return view('newGdprPolicy');
})->name('gdpr_policy');

Route::get('/payment-policy', function () {
    return view('newPaymentPolicy');
})->name('payment_policy');




Route::get('/about', function () {
    return view('newAbout');
})->name('about');

Route::get('/faq_lp', [FaqController::class, "index"])->name("question");

Route::get('/news', function () {
    return view('news');
})->name('news');


Route::get('/brands', function () {
    return view('brands');
})->name('brands');

Route::get('/blog', [BlogController::class, "blog"])->name("blog");

// Route::get('/teste/smart', [SmartshippingPaymentsRecurringController::class, "index"]);

Route::get('/contact', function () {
    return view('newContact');
})->name('contact');

Route::get('/landingpage', function () {
    return view('welcome.landingpage');
});


Route::get('/partners', function () {
    return view('welcome.partners');
});

Route::get('/rewards', function () {
    return view('welcome.rewards');
});

Route::get('/transaction', function () {
    return view('transaction');
});

Route::get('/cards', function () {
    return view('welcome.cards');
});

Route::get('/faq', function () {
    return view('welcome.faq');
});

Route::get('/concierge', function () {
    return view('welcome.concierge');
});

Route::get('/accounts', function () {
    return view('welcome.accounts');
});

// Route::get('/contact', function () {
//     return view('welcome.contact');
// });

Route::get('/aboutus', function () {
    return view('welcome.aboutus');
});

Route::get('/roadmap', function () {
    return view('welcome.roadmap');
});

Route::get('/features', function () {
    return view('welcome.features');
});


Route::get('/adesao', function () {
    return view('adesao'); //apagar
});

Route::get('/paymentadesao', function () {
    return view('paymentadesao'); //apagar
});

Route::get('/payment', function () {
    return view('payment'); //apagar
});

Route::get('/userpackageinfo', function () {
    return view('userpackageinfo');
});

#endregion
Route::get('/rede', function () {
    return view('network.rede');
});

Route::get('/supporttickets', function () {
    return view('supporttickets');
});

Route::get('/admin/investment', function () {
    return view('admin.investment.investment');
});

// Route::get('/admin/support', [ChatController::class, "PageSupport"])->name('admin.support.support');

Route::get('/admin/answer_chat', function () {
    return view('admin.support.answerChat');
});

Route::get('/admin/close_chat', function () {
    return view('admin.support.closeChat');
});

Route::get('/admin/reopen_chat', function () {
    return view('admin.support.reopenChat');
});

Route::get('/admin/membersList', function () {
    return view('admin.members.membersList');
});

// Route::get('/admin/newsletter', function () {
//     return view('admin.members.newsletter');
// });

Route::get('/admin/newsletter', [NewletterController::class, "List"])->name('list.newsletter');

Route::get('/admin/library', [LibraryPdfAdminController::class, "index"])->middleware(['auth', 'is.admin'])->name('list.library');

Route::post('/admin/library/store', [LibraryPdfAdminController::class, "store"])->middleware(['auth', 'is.admin'])->name('store.library');

Route::get('/admin/library/delete/{id}', [LibraryPdfAdminController::class, "delete"])->middleware(['auth', 'is.admin'])->name('delete.library');

Route::get('library_pdf/download/{id}', [LibraryPdfAdminController::class, 'download'])->name('download.library');


Route::get('/admin/mlmCharge', function () {
    return view('admin.settings.mlmCharge');
});


Route::get('/admin/general', function () {
    return view('admin.settings.general');
});

Route::get('/admin/smtp', function () {
    return view('admin.settings.smtp');
});

Route::get('/admin/rede', function () {
    return view('admin.users.rede');
});

//Route::post('/teste', [App\Http\Controllers\PaymentController::class, 'notity'])->name('notity');
Route::get('logginIn', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
Route::post('logginIn', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm');
Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register');
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

/**
 * Link teste
 */
// Route::get('/teste2', [App\Http\Controllers\MigraController::class, 'index'])->name('index');

// Route::get('/{slug}', function () {
//    if (auth()->user()->id != 1) {
//       return view('maintenance.index');
//    }
// });

/**
 * Backoffice Route
 */

Route::prefix('home')->middleware('auth')->name('home')->group(function () {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/', 'index')->name('.home');
    });
});

Route::prefix('packages')->middleware('auth')->name('packages')->group(function () {
    Route::controller(PackageController::class)->group(function () {
        Route::get('/packages', 'index')->name('.index');
        Route::get('/packagesActivator', 'packagesActivator')->name('.packagesActivator');
        Route::get('/packageslog', 'package')->name('.packagelog');
        Route::get('/{id}/hide', 'hide')->name('.hide');
        Route::get('/packages/{id}', 'detail')->name('.detail');
        Route::get('/packages/order/detail/{id}', 'ecommOrdersDetail')->name('.ecommOrdersDetail');
        Route::get('/packages/order/detail/{id}/edit', 'ecommOrdersDetailEdit')->name('.ecommOrdersDetailEdit');
        Route::post('/packages/order/detail/edit', 'ecommOrdersDetailEditPost')->name('.ecommOrdersDetailEditPost');
        Route::post('/packages/order/detail/edit/save', 'ecommOrdersDetailEditPostSave')->name('.ecommOrdersDetailEditPostSave');
        Route::get('/order/invoice/{id}', 'invoicePackage')->name('.invoicePackage');
        Route::get('/order/invoice/xml/{id}', 'invoicePackageXml')->name('.invoicePackageXml');
        // ROUTE BUY
        Route::get('/packages/buy/{id}/{method}', 'BuyPackage')->name('.buy_package');

        Route::get('/new/process/user', 'newProcessPackage1')->name('.newProcessPackage');
        Route::get('/new/process/{id}/user', 'newProcessPackage2')->name('.newProcessPackage2');
        Route::get('/new/process/user/{id}/step/3', 'newProcessPackage3')->name('.newProcessPackage3');
        Route::get('/new/process/user/step/4/get', 'newProcessPackage4GET')->name('.newProcessPackage4GET');
        Route::post('/new/process/user/step/4', 'newProcessPackage4')->name('.newProcessPackage4');
        Route::post('/new/process/user/step/5', 'newProcessPackage5')->name('.newProcessPackage5');
    });
    Route::controller(ProductController::class)->group(function () {
        Route::get('/tracking', "tracking")->name(".tracking");
        Route::get('/products', 'index')->name('.index_products');
        Route::get('/payment-link-render/{orderID}', 'paymentLinkRender')->name('.payment_link_render');
        Route::get('/products/type/{categoria}', 'categoria')->name('.index_categoria');
        Route::get('/product/{id}', 'detail')->name('.detail_products');
        Route::get('/product/buy/{id}', 'buyProduct')->name('.buy_products');
        Route::get('/product/edit_amount/down/{id}', 'editDownAmountBuy')->name('.edit_down_amount_buy');
        Route::get('/product/edit_amount/up/{id}', 'editUpAmountBuy')->name('.edit_up_amount_buy');
        Route::get('/product/delete_cart/{id}', 'deleteCartBuy')->name('.delete_cart_buy');
        Route::get('/cart/clear', 'clearCartBuy')->name('.clear_cart_buy');
        Route::get('/cart/buy/{btnSmart?}', 'cardBuy')->name('.cart_buy');
        Route::post('/product/order/smartshipping', 'smartshipping')->name('.smartshipping');
        Route::post('/cart/finalize', 'cartFinalize')->name('.cartFinalize');
    });
});

Route::prefix('withdraws')->middleware('auth')->name('withdraws')->group(function () {
    Route::controller(WithdrawController::class)->group(function () {
        Route::get('/withdrawRequests', 'withdrawRequests')->name('.withdrawRequests');
        Route::get('/withdrawLog', 'withdrawLog')->name('.withdrawLog');
        Route::get('/withdrawBonus', 'withdrawBonus')->name('.withdrawBonus');
        Route::post('/', 'store')->name('.store');
        Route::post('/bonus', 'bonus')->name('.bonus');
    });
});

Route::prefix('networks')->middleware('auth')->name('networks')->group(function () {
    Route::controller(NetworkController::class)->group(function () {
        Route::get('/{parameter}/mytree', 'mytree')->name('.mytree');
        Route::get('/{parameter}/mytreediferente', 'mytreediferente')->name('.mytreediferente');
        Route::get('/myreferrals', 'myreferrals')->name('.myreferrals');
    });
});

Route::prefix('networks')->middleware('auth')->name('networks')->group(function () {
    Route::controller(NetworkController::class)->group(function () {
        Route::get('/{parameter}/mytree', 'mytree')->name('.mytree');
        Route::get('/myreferrals', 'myreferrals')->name('.myreferrals');
        Route::get('/mycareer', 'mycareer')->name('.mycareer');
        Route::get('/indication/ecomm', 'IndicationEcomm')->name('.IndicationEcomm');
        Route::post('/indication/ecomm/filter', 'IndicationEcommFilter')->name('.IndicationEcommFilter');
        Route::get('/indication/ecomm/filter/month', 'IndicationEcommFilterMonth')->name('.IndicationEcommFilterMonth');
        Route::get('/indication/ecomm/orders/{id}', 'IndicationEcommOrders')->name('.IndicationEcommOrders');
        Route::get('/indication/ecomm/orders/{id}/filter/month', 'IndicationEcommOrdersFilter')->name('.IndicationEcommOrdersFilter');
        Route::get('/indication/ecomm/orders/detail/{order}', 'IndicationEcommOrdersDetail')->name('.IndicationEcommOrdersDetail');
        Route::get('/associatesReport', 'associatesReport')->name('.associatesReport');
        Route::post('/associatesReport/filter', 'associatesReport')->name('.associatesReport_filter');
        // Route::get('/associates/pesquisa/get/{id}', 'pesquisaGet')->name('.pesquisa');
        Route::post('/associates/pesquisa', 'pesquisa')->name('.pesquisa');
    });
});

Route::get('library/list', [App\Http\Controllers\LibraryPdfController::class, 'index'])->middleware('auth')->name('backoffice.library.list');

Route::prefix('supports')->middleware('auth')->name('supports')->group(function () {
    Route::controller(ChatController::class)->group(function () {
        Route::get('/supporttickets', 'index')->name('.supporttickets');
        Route::post('/', 'store')->name('.store');
        Route::get('/answer_chat/{id}', 'answerChat')->name('.answerChat');
        Route::post('/answer_chat/{id}', 'createMessage')->name('.createMessage');
        Route::get('/close_chat/{id}', 'closeChat')->name('.closeChat');
        Route::get('/reopen_chat/{id}', 'reopenChat')->name('.reopenChat');
    });

    Route::controller(FaqController::class)->group(function () {
        Route::get('/tutorials/videos', 'PageTutorials')->name('.tutorials');
        Route::get('/tutorials/video/{id}', 'PageTutorialsVideo')->name('.tutorialsVideo');
    });
});

Route::get('/marketing', function () {
    return view('marketing.marketing');
});

Route::prefix('reports')->middleware('auth')->name('reports')->group(function () {
    Route::controller(ReportsController::class)->group(function () {
        Route::get('/signupcommission', 'signupcommission')->name('.signupcommission');
        Route::get('/commission', 'newCommission')->name('.newCommission');
        Route::post('/signupcommission/filter', 'signupcommission_filter')->name('.signupcommission_filter');
        Route::get('/rankReward', 'rankReward')->name('.rankReward');
        Route::get('/levelIncome', 'levelIncome')->name('.levelIncome');
        Route::get('/stakingRewards', 'stakingRewards')->name('.stakingRewards');
        Route::get('/monthlyCoins', 'monthlyCoins')->name('.monthlyCoins');
        Route::get('/transactions', 'transactions')->name('.transactions');
        Route::get('/commissions-month', 'commissionMonth')->name('.commissions_month');
        Route::get('/searchDataTransactions', 'getDateTransactions')->name('.getDateTransactions');
        Route::get('/searchUserTransactions', 'searchuser')->name('.searchTransactions');
        Route::get('/searchFilterTransactions', 'transactionsFilters')->name('.transactionsFilters');
        Route::get('/poolcommission', 'poolcommission')->name('.poolcommission');
        Route::get('/bonus-group', 'reportBonusGroup')->name('.bonus_group');
        // NEWS REPORTS
        Route::get('/newrecruits', 'newrecruits')->name('.newrecruits');
        Route::post('/newrecruits/filter/date', 'newrecruitsDate')->name('.newrecruitsDate');
        Route::get('/costumerrecruits', 'costumerrecruits')->name('.costumerrecruits');
        Route::get('/smartshippeople', 'smartshippeople')->name('.smartshippeople');
        Route::get('/smartshipping', 'smartshipping')->name('.smartshipping_report');

        Route::get('/newsmartshipping', 'newsmartshipping')->name('.newsmartshipping');
        Route::get('/smartshippingSumTotal', 'smartshippingSumTotal')->name('.smartshippingSumTotal');
        Route::post('/smartshippingSum', 'smartshippingSum')->name('.smartshippingSum');
        Route::get('/smartshippingOrder', 'smartshippingOrder')->name('.smartshippingOrder');
        Route::get('/smartshippingOrderDetail/{id}', 'smartshippingOrderDetail')->name('.smartshippingOrderDetail');
        Route::get('/smartshippingOrderBack', 'smartshippingOrderBack')->name('.smartshippingOrderBack');

        Route::get('/smartshipping/check/cancel/{id}', 'smartshipping_check_cancel')->name('.smartshipping.check.cancel');
        Route::get('/smartshipping/cancel/{id}', 'smartshipping_cancel')->name('.smartshipping.cancel');
        Route::get('/teamorders', 'teamorders')->name('.teamorders');
        Route::get('/teamorders/filter', 'teamordersFilter')->name('.teamorders.filter');
        Route::post('/teamorders/filter/date', 'teamordersFilterDate')->name('.teamorders.filter.date');
        Route::get('/detailorder/{id}', 'detailorder')->name('.detailorder');
        Route::get('/detail/allorder/{id}/{month?}/{year?}', 'DetailOrdersTeam')->name('.DetailOrdersTeam');
        Route::get('/teamranks', 'teamranks')->name('.teamranks');
        Route::get('/teamrankscurrent', 'teamrankscurrent')->name('.teamrankscurrent');
    });
});
Route::prefix('indication')->name('indication')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/{id}/register', 'register')->name('.register');
    });
    Route::controller(LangingController::class)->group(function () {
        Route::get('/{id}/landing', 'index')->name('.index');
    });
});

Route::prefix('users')->middleware('auth')->name('users')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('.index');
        Route::put('/{id}/update', 'update')->name('.update');
        Route::put('/{id}/update/address2', 'updateAddress2')->name('.update.address2');
        Route::put('/{id}/update/billing', 'updateBilling')->name('.update.billing');
        Route::get('/password', 'password')->name('.password');
        Route::put('/password/change', 'changePassword')->name('.change.password');
        Route::get('/{id}/register', 'register')->name('.register');
    });
});

Route::prefix('payment')->middleware('auth')->name('payment')->group(function () {
    Route::controller(ProductController::class)->group(function () {
        // Route::post('/payment', 'indexPost')->name('.paymentPost');
        Route::get('/payment-test', 'checkClientExistsAPI')->name('.payment_test');
        Route::get('/payment-order-test', 'createNewPaymentOrderAPI')->name('.payment_order_test');
        Route::get('/update-address-test', 'updateOrCreateClientAddress')->name('.update_address_test');
        // Route::post('/payment/notity', 'notity')->name('.notity');
        // Route::get('/payment/{package}/{value}', 'index')->name('.payment');
        // Route::get('/paymentUSDTERC/{package}/{value}', 'indexUSDTERC')->name('.paymentUSDTERC');
        // Route::get('/paymentBTC/{package}/{value}', 'indexBTC')->name('.paymentBTC');
        // Route::get('/subscriptionClub/{package}', 'subscriptionClub')->name('.subscriptionClub');
    });
});

Route::prefix('documents')->middleware('auth')->name('documents')->group(function () {
    Route::controller(DocumentsController::class)->group(function () {
       Route::get('/documents', 'index')->name('.index');
       Route::get('/download/{file}', 'downloadFile')->name('.download');
       Route::post('/getDateDocuments', 'getDateDocuments')->name('.getDateDocuments');
    });
 });

 Route::prefix('videos')->middleware('auth')->name('videos')->group(function () {
    Route::controller(VideosController::class)->group(function () {
       Route::get('/videos', 'index')->name('.index');
       Route::get('/download/{file}', 'downloadFile')->name('.download');
       Route::post('/getDateVideos', 'getDateVideos')->name('.getDateVideos');
    });
 });


/**
 * Admin Route
 */
Route::prefix('admin')->middleware(['auth', 'is.admin'])->name('admin')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeAdminController::class, 'indexAdmin'])->name('.home');
    Route::get('/tracking', [ProductAdminController::class, "tracking"])->name(".tracking");

    Route::prefix('order-admin')->name('.order-admin')->group(function () {
        Route::controller(AdminOrderController::class)->group(function () {
            Route::get('/', 'index')->name('.order-admin.home');
            Route::post('/payment-admin', 'payment')->name('.payment.admin');
            // Route::post('/searchUser', 'searchUserToMaster')->name('.searchUserToMaster');
        });
    });



    // Route::group([
    //     'prifix' => 'order-admin'
    // ], function () {
    //     Route::get('/', [AdminOrderController::class, 'index'])->name('.order-admin.home');
    // });
    Route::prefix('reports/UsernameUpToMaster')->name('.UsernameUpToMaster')->group(function () {
        Route::controller(App\Http\Controllers\Admin\UsernameUpToMasterController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
            Route::post('/searchUser', 'searchUserToMaster')->name('.searchUserToMaster');
        });
    });

    Route::prefix('reports/RegistrationsWithDate')->name('.RegistrationsWithDate')->group(function () {
        Route::controller(App\Http\Controllers\Admin\RegistrationsWithDateController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
        });
    });
    Route::prefix('reports/UsersByCountry')->name('.UsersByCountry')->group(function () {
        Route::controller(App\Http\Controllers\Admin\UsersByCountryController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
            Route::get('/list/{id}', 'ListUserCountry')->name('.list_country');
        });
    });


    Route::prefix('packages')->name('.packages')->group(function () {

        Route::controller(PackageAdminController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
            Route::get('/orderPackages', 'orderPackages')->name('.orderPackages');
            Route::get('/orderPackagesCorporate', 'orderPackagesCorporate')->name('.orderPackagesCorporate');
            Route::post('/', 'store')->name('.store');
            Route::get('/{parameter}/orderfilter', 'orderfilter')->name('.order_filter');

            Route::get('/{parameter}/orderfilter/corporate', 'orderfilterCorporate')->name('.order_filter.corporate');
            Route::post('/search', 'search')->name('.search');
            Route::get('/{parameter}/filter', 'packageFilter')->name('.filter');
            Route::post('/getDateOrders', 'getDateOrders')->name('.getDateOrders');
            Route::post('/getDateOrdersCorporate', 'getDateOrdersCorporate')->name('.getDateOrdersCorporate');
            Route::get('/create', 'create')->name('.create');
            Route::post('/searchOrders', 'searchOrders')->name('.searchOrders');
            Route::post('/searchOrdersCorporate', 'searchOrdersCorporate')->name('.searchOrdersCorporate');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}/update', 'update')->name('.update');
            Route::put('/{id}/orderupdate', 'orderupdate')->name('.orderupdate');
            Route::delete('/{id}/remove', 'destroy')->name('.delete');
            Route::get('/payall', 'payall')->name('.payall');
            Route::get('/orderProducts/Fakturoid/package/{id}', 'Fakturoid')->name('.Fakturoid.packages');
            Route::post('/orderProducts/filter/name/{name?}', 'orderFilterName')->name('.order_filter_name');
        });

        Route::controller(ProductAdminController::class)->group(function () {
            // Route::get('/ecomm/detals/Fakturoid/{id}', [EcommController::class, "Fakturoid"])->name("Fakturoid");
            Route::get('/orderProducts/Fakturoid/{id}', 'Fakturoid')->name('.Fakturoid');
            Route::post('/orderProducts/label/pdf', 'generatePDFPPL')->name('.order_products_label.pdf');
            Route::post('/orderProducts/status/change', 'changeStatus')->name('.change_status');
            Route::get('/orderProducts/label/{order}', 'orderproductslabel')->name('.order_products_label');
            Route::get('/orderProducts', 'orderproducts')->name('.orderproducts');
            Route::get('/corporate/orderfilter/product', 'orderfilterCorporate')->name('.orderfilter.products.corporate');
            Route::post('/corporate/orderfilter/product/save/{id}', 'orderfilterCorporateSave')->name('.orderfilter.products.corporate.save');
            Route::get('/{parameter}/orderfilter/product', 'orderfilter')->name('.orderfilter.products');

            Route::get('/{parameter}/orderfilter/product/name', 'orderFilterName')->name('.orderFilterName.products');

            Route::get('/{parameter}/orderfilter/product/corporate', 'orderfilterStatusCorporate')->name('.orderfilter.products.corporate.status');
            Route::get('/products', 'index')->name('.index_admin');
            Route::get('/products/create', 'create')->name('.create_admin');
            Route::post('/products/creating', 'store')->name('.store_admin');
            Route::get('/product/{id}/edit', 'edit')->name('.edit_admin');
            Route::get('/product/stock', 'stock')->name('.stock_admin');
            Route::get('/product/stock/report', 'stockReport')->name('.stock_admin_report');
            Route::get('/product/stock/detal/{id}', 'stock_detal')->name('.stock_admin.detal');

            Route::get('/product/stock/edit/quantity', 'stockEdit')->name('.stock_admin.edit');
            Route::post('/product/stock/edit/quantity/store', 'stockUpdate')->name('.stock_admin.update');

            Route::post('/product/stock/report/download', 'stockReportDownload')->name('.stock_admin_report_download');
            Route::post('/orderfilter/product/cancel/', 'CancelOrders')->name('.orderfilter.CancelOrders');
            Route::get('/product/stock/filter', 'stockFilter')->name('.stock_admin.filter');
            Route::put('/product/{id}/update', 'update')->name('.update_admin');
            Route::delete('/product/{id}/remove', 'destroy')->name('.delete_admin');
        });

        // tax

        Route::controller(TaxController::class)->group(function () {
            Route::get('/tax', 'index')->name('.index_tax');
            Route::get('/tax/edit/value', 'edit')->name('.edit_tax');
            Route::get('/tax/validate/vat', 'validateVatId')->name('.validateVatId');
            Route::get('/tax/get', 'getByCountry')->name('.getByCountry_tax');
            Route::post('/tax/update/value', 'update')->name('.update_tax');
        });

        Route::controller(CategoriaAdminController::class)->group(function () {
            Route::get('/categorias', 'index')->name('.categorias');
            Route::get('/categorias/create', 'create')->name('.categorias.create');
            Route::post('/categorias/store', 'store')->name('.categorias.store');
            Route::get('/categorias/edit/{id}', 'edit')->name('.categorias.edit');
            Route::post('/categorias/update', 'update')->name('.categorias.update');
            Route::get('/categorias/destroy/{id}', 'destroy')->name('.categorias.destroy');


        });

        Route::controller(SequenceAdminController::class)->group(function () {
            Route::get('/sequence', 'index')->name('.sequence');
            Route::post('/sequence/update', 'update')->name('.sequence.update');
        });

        Route::controller(ProductByCountryAdminController::class)->group(function () {
            Route::get('/filterBycountry/country', 'filterBycountry')->name('.filterBycountry.country');
            Route::post('/filterBycountry/country/update', 'filterBycountrySelected')->name('.filterBycountry.country.selected');
            Route::get('/filterBycountry/country/selected/{id}', 'filterBycountrySelect')->name('.filterBycountry.country.select');

        });
    });
    Route::prefix('configBonus')->name('.configBonus')->group(function () {
        Route::controller(ConfigBonusController::class)->group(function () {
            Route::get('/', 'index')->name('.list');
            Route::post('/', 'store')->name('.store');
            Route::get('/create', 'create')->name('.create');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}/update', 'update')->name('.update');
            Route::get('/{id}/remove', 'inactivate')->name('.inactivate');
            Route::get('/{id}/activate', 'activate')->name('.activate');
            Route::get('/removeall', 'inactivateall')->name('.inactivateall');
            Route::get('/activateall', 'activateall')->name('.activateall');
        });
    });

    Route::prefix('users')->name('.users')->group(function () {
        Route::controller(UserAdminController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
            Route::get('/customers', 'customers')->name('.customers');
            Route::post('/update-career', 'updateCareer')->name('.update_career');
            Route::get('/alter-career', 'alterCareer')->name('.alter_career');
            Route::get('/corporates', 'corporates')->name('.corporates');
            Route::get('/inactive', 'indexInactive')->name('.indexinactive');
            Route::get('/ban', 'indexBan')->name('.indexban');
            Route::get('/{id}/ban', 'ban')->name('.ban');
            Route::get('/{id}/inactive', 'inactive')->name('.inactive');
            Route::get('/{id}/active', 'active')->name('.active');
            Route::get('/myinfo', 'myinfo')->name('.myinfo');
            Route::get('/{id}/edit/ecomm', 'editEcomm')->name('.editEcomm');
            Route::post('/{id}/update/ecomm', 'updateEcomm')->name('.updateEcomm');
            Route::post('/{id}/update/address/ecomm', 'updateSecondAddressEcomm')->name('.updateSecondAddressEcomm');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}/update', 'update')->name('.update');
            Route::put('/update/address2', 'updateAddress2')->name('.update.address2');
            Route::get('/{id}/dashboard', 'dashboard')->name('.dashboard');
            Route::get('/{id}/dashboard/ecomm', 'dashboardEcomm')->name('.dashboardEcomm');
            Route::post('/', 'store')->name('.store');
            Route::post('/sendBrevo', 'sendBrevo')->name('.sendBrevo');
            Route::post('/search', 'search')->name('.search');
            Route::get('/{parameter}/network', 'networkuser')->name('.network');
            Route::get('/{parameter}/networkdiferente', 'networkuserdiferente')->name('.networkdiferente');
            Route::get('/create', 'create')->name('.create');
            Route::get('/password', 'password')->name('.password');
            Route::get('/searchUsers/corporate', 'searchUsersCorporate')->name('.searchUsersCorporate');
            Route::get('/searchUsers', 'searchUsers')->name('.searchUsers');
            Route::post('/searchUsers/customers', 'searchUsersCustomers')->name('.searchUsersCustomers');
            Route::put('/password/change', 'changePassword')->name('.change.password');
            Route::get('/{id}/specialcomission', 'specialcomission')->name('.specialcomission');
            Route::put('/{id}/upspecialcomission', 'upspecialcomission')->name('.upspecialcomission');
            Route::get('/{parameter}/filter/corporate', 'UsersFilterCorporate')->name('.filterCorporate');
            Route::get('/{parameter}/filter', 'UsersFilter')->name('.filter');
            Route::get('/{id}/transactions/{date?}', 'transactions')->name('.transactions');
            Route::get('/filter/states/corporate', 'filterStatesCorporate')->name('.filterStatesCorporate');
            Route::get('/filter/states', 'filterStates')->name('.filterStates');
            Route::get('/filter/states/customers', 'filterStatesCustomers')->name('.filterStatesCustomers');
            Route::get('/report/career', 'careerUser')->name('.careerUser');
            Route::get('/report/career-first', 'careerUserFirst')->name('.careerUserFirst');
            Route::get('/report/career/edit/{id?}', 'careerUserEdit')->name('.careerUserEdit');
            Route::post('/report/career/update', 'careerUserUpdate')->name('.careerUserUpdate');
        });
    });
    Route::prefix('investment')->name('.investment')->group(function () {
        Route::controller(InvestmentAdminController::class)->group(function () {
            Route::get('/', 'index')->name('.index');
        });
    });
    Route::prefix('reports')->name('.reports')->group(function () {
        Route::controller(ReportsAdminController::class)->group(function () {
            Route::get('/signupcommission', 'signupcommission')->name('.signupcommission');
            Route::get('/searchSignup', 'searchSignup')->name('.searchSignup');
            Route::get('/getDateSignup', 'getDateSignup')->name('.getDateSignup');
            Route::get('/rankReward', 'rankReward')->name('.rankReward');
            Route::get('/searchrankReward', 'searchrankReward')->name('.searchrankReward');
            Route::get('/searchDataRank', 'getDaterankReward')->name('.getDaterankReward');
            Route::get('/levelIncome', 'levelIncome')->name('.levelIncome');
            Route::get('/stakingRewards', 'stakingRewards')->name('.stakingRewards');
            Route::get('/searchstakingRewards', 'searchstakingRewards')->name('.searchstakingRewards');
            Route::get('/searchDataRewards', 'getstakingRewards')->name('.getstakingRewards');
            Route::get('/monthlyCoins', 'monthlyCoins')->name('.monthlyCoins');
            Route::get('/searchMonthly', 'searchMonthly')->name('.searchMonthly');
            Route::get('/select', 'getDateMonthly')->name('.getDateMonthly');
            Route::get('/transactions/bonus/filter', 'getDatereportBonus')->name('.getDatereportBonus');
            Route::get('/transactions/bonus', 'reportBonus')->name('.transactions.bonus');
            Route::get('/transactions', 'transactions')->name('.transactions');
            Route::get('/searchTransactions', 'searchTransactions')->name('.searchTransactions');
            Route::get('/searchDataTransactions', 'getDateTransactions')->name('.getDateTransactions');
            Route::get('/searchLevelIncome', 'searchLevelIncome')->name('.searchLevelIncome');
            Route::get('/searchData', 'getDateLevelIncome')->name('.getDateLevelIncome');
            Route::get('/poolcommission', 'poolcommission')->name('.poolcommission');
            Route::get('/searchPool', 'searchPool')->name('.searchPool');
            Route::get('/getDatePool', 'getDatePool')->name('.getDatePool');
            Route::get('/orders/payed', 'ordersPayed')->name('.ordersPayed');
            Route::get('/smartShippingCustomers', 'smartShippingCustomers')->name('.smartShippingCustomers');
            Route::post('/smartShippingCustomers/disable', 'disableSmartshipping')->name('.disableSmartshipping');
            Route::post('/smartShippingCustomers/filter', 'smartShippingCustomersFilter')->name('.smartShippingCustomersFilter');
            Route::get('/monthlyCommissions', 'monthlyCommissions')->name('.monthlyCommissions');
            Route::get('/monthlyCommissionsFilter', 'monthlyCommissionsFilter')->name('.monthlyCommissionsFilter');
            Route::get('/monthlyCommissionsDetail/{id}', 'monthlyCommissionsDetail')->name('.monthlyCommissionsDetail');
            Route::get('/monthlyCommissionsExcel', 'monthlyCommissionsExcel')->name('.monthlyCommissionsExcel');
            Route::get('/smartshipping-history', 'smartshippingHistory')->name('.smartshipping_history');
        });
    });
    Route::prefix('withdraws')->name('.withdraw')->group(function () {
        Route::controller(WithdrawsAdminController::class)->group(function () {
            Route::get('/withdrawRequests', 'withdrawRequests')->name('.withdrawRequests');
            Route::get('/withdrawLog', 'withdrawLog')->name('.withdrawLog');
            Route::put('/{id}/update', 'update')->name('.update');
            ;
        });
    });

    Route::prefix('blog')->name('.blog')->group(function () {
        Route::controller(BlogController::class)->group(function () {
            Route::get('/post', 'AddPostBlog')->name('.AddPostBlog');
            Route::get('/posts', 'ListPostsBlog')->name('.ListPostsBlog');
            Route::get('/post/edit/{id}', 'PostsEdit')->name('.PostsEdit');
            Route::get('/post/update/{id}', 'PostsUpdate')->name('.PostsUpdate');
            Route::get('/post/delete/{id}', 'PostsDelete')->name('.PostsDelete');
            Route::post('/post/create', 'CreatePostBlog')->name('.CreatePostBlog');
        });
    });

    Route::prefix('news')->name('.news')->group(function () {
        Route::controller(BlogController::class)->group(function () {
            Route::get('/create', 'AddPostNews')->name('.AddPostNews');
            Route::get('/list', 'ListPostsNews')->name('.ListPostsNews');
            Route::get('/post/edit/{id}', 'PostsNewsEdit')->name('.PostsNewsEdit');
            Route::get('/post/update/{id}', 'PostsNewsUpdate')->name('.PostsNewsUpdate');
            Route::get('/post/delete/{id}', 'PostsNewsDelete')->name('.PostsNewsDelete');
            Route::get('/post/disabled/{id}', 'PostsNewsDisabled')->name('.PostsNewsDisabled');
            Route::get('/post/activated/{id}', 'PostsNewsActivated')->name('.PostsNewsActivated');
            Route::post('/post/create', 'CreatePostNews')->name('.CreatePostNews');
        });
    });

    Route::prefix('faq')->name('.faq')->group(function () {
        Route::controller(FaqController::class)->group(function () {
            Route::get('/create', 'AddFaq')->name('.AddFaq');
            Route::get('/list', 'ListFaq')->name('.ListFaq');
            Route::get('/edit/{id}', 'FaqEdit')->name('.FaqEdit');
            Route::get('/update/{id}', 'FaqUpdate')->name('.FaqUpdate');
            Route::get('/delete/{id}', 'FaqDelete')->name('.FaqDelete');
            Route::post('/creating', 'CreateFaq')->name('.CreateFaq');
        });
    });

    Route::prefix('tutorial')->name('.tutorial')->group(function () {
        Route::controller(FaqController::class)->group(function () {
            Route::get('/create', 'AddTutorial')->name('.AddTutorial');
            Route::get('/list', 'ListTutorial')->name('.ListTutorial');
            Route::get('/edit/{id}', 'TutorialEdit')->name('.TutorialEdit');
            Route::get('/update/{id}', 'TutorialUpdate')->name('.TutorialUpdate');
            Route::get('/delete/{id}', 'TutorialDelete')->name('.TutorialDelete');
            Route::post('/creating', 'CreateTutorial')->name('.CreateTutorial');
        });
    });

    Route::prefix('video-upload')->name('.video-upload')->group(function () {
        Route::controller(VideoAdminController::class)->group(function () {
           Route::get('/', 'index')->name('.index');
           Route::post('/', 'store')->name('.store');
           Route::get('download/{file}', 'downloadFile')->name('.download');
           Route::get('/{id}/edit', 'edit')->name('.edit');
           Route::put('/{id}/update', 'update')->name('.update');
           Route::delete('/{id}/remove', 'destroy')->name('.delete');
        });
     });

     Route::prefix('documents-upload')->name('.documents-upload')->group(function () {
        Route::controller(DocumentsAdminController::class)->group(function () {
           Route::get('/', 'index')->name('.index');
           Route::post('/', 'store')->name('.store');
           Route::get('download/{file}', 'downloadFile')->name('.download');
           Route::get('/{id}/edit', 'edit')->name('.edit');
           Route::put('/{id}/update', 'update')->name('.update');
           Route::delete('/{id}/remove', 'destroy')->name('.delete');
        });
     });

    Route::prefix('settings')->name('.settings')->group(function () {
        Route::controller(SettingsAdminController::class)->group(function () {
            Route::get('/mlmLevel', 'mlmLevel')->name('.mlmLevel');
            Route::get('/{id}/edit', 'edit')->name('.edit');
            Route::put('/{id}/update', 'update')->name('.update');
            Route::get('/indication', 'indication')->name('.indication');
            Route::post('/', 'store')->name('.store');
            Route::get('/create', 'create')->name('.create');
            Route::get('/{id}/edit', 'editVideo')->name('.editVideo');
            Route::post('/{id}/update', 'updateVideo')->name('.updateVideo');
            Route::get('/system', 'systemuser')->name('.system');
            Route::put('/upsystemconfig', 'upsystemconfig')->name('.upsystemconfig');
            Route::get('/popup', 'popup')->name('.popup');
            Route::post('/upload-image', 'storeImage')->name('image.store');
            Route::get('/{id}/inactive', 'inactive')->name('.inactive');
            Route::get('/{id}/activated', 'activated')->name('.activated');
            Route::get('/{id}/edit/pop', 'editpop')->name('.editpop');
            Route::put('/{id}/update/pop', 'updatepop')->name('.updatepop');
        });
    });

    Route::prefix('bonus')->name('.bonus')->group(function () {
        Route::controller(GeraBonusAdminController::class)->group(function () {
            // Route::get('/generate/teste', 'index')->name('.index');
            Route::get('/generate', 'generate')->name('.generate');
            Route::post('/generate/ajax', 'generateAjax')->name('.generateAjax');
        });
    });

    Route::prefix('whitelist')->name('.whitelist')->group(function () {
        Route::controller(IpWhitelistAdminController::class)->group(function () {
            Route::get('/whitelist', 'whitelist')->name('.whitelist');
            Route::get('/create', 'create')->name('.create');
            Route::post('/', 'store')->name('.store');
            Route::get('/{id}/inactive', 'inactive')->name('.inactive');
            Route::get('/{id}/activated', 'activated')->name('.activated');
        });
    });

    Route::prefix('blacklist')->name('.blacklist')->group(function () {
        Route::controller(IpBlacklistAdminController::class)->group(function () {
            Route::get('/blacklist', 'blacklist')->name('.blacklist');
            Route::delete('/{id}/remove', 'destroy')->name('.delete');
        });
    });


    Route::get('/support', [App\Http\Controllers\Admin\ChatAdminController::class, 'index'])->name('.support');

    Route::get('/change_wallet', [App\Http\Controllers\Admin\ChatAdminController::class, 'indexChange'])->name('.indexChange');

    Route::get('/payWithdraw/{id}', [App\Http\Controllers\Admin\PayWithdrawAdminController::class, 'index'])->name('.payWithdraw');

    Route::get('/payWithdrawCC/{id}', [App\Http\Controllers\Admin\PayWithdrawAdminController::class, 'indexCC'])->name('.payWithdrawCC');

    Route::get('/answer_chat/{id}', [App\Http\Controllers\Admin\ChatAdminController::class, 'answerChat'])->name('.answerChat');
    Route::post('/answer_chat/{id}', [App\Http\Controllers\Admin\ChatAdminController::class, 'createMessage'])->name('.createMessage');

    Route::get('/close_chat/{id}', [App\Http\Controllers\Admin\ChatAdminController::class, 'closeChat'])->name('.closeChat');

    Route::get('/cw/{id}', [App\Http\Controllers\Admin\ChatAdminController::class, 'changeWallet'])->name('.changeWallet');

    Route::get('/reopen_chat/{id}', [App\Http\Controllers\Admin\ChatAdminController::class, 'reopenChat'])->name('.reopenChat');
});

use App\Models\User;
use Illuminate\Support\Facades\Mail;

Route::get('/email', function () {

    $user = User::find(1);

    Mail::to($user->email)->send(new UserRegisteredEmail($user));
});
