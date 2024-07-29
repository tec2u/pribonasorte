<?php

namespace App\Traits;

use App\Models\ConfigBonus;
use App\Models\EcommOrders;
use App\Models\OrderPackage;
use App\Models\PaymentOrderEcomm;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;


trait ApiReports
{
    private function ordersPackage(User $user)
    {
        try {
            $id_user = $user->id;

            $orderPackages = OrderPackage::orderBy('id', 'DESC')
                ->where('hide', false)
                ->select('id', 'reference', 'price', 'created_at', 'payment_status')
                ->where('user_id', $id_user)->paginate(9);

            foreach ($orderPackages as $item) {
                $item['date'] = date('d/m/Y', strtotime($item->created_at));

                if ($item->payment_status == 1) {
                    $item['payment'] = "Paid";
                } else if ($item->payment_status == 2) {
                    $item['payment'] = "Cancelled";
                } else {
                    $item['payment'] = "Waiting";
                }
            }


            $paginationLinks = [
                'first' => $orderPackages->url(1),
                'last' => $orderPackages->url($orderPackages->lastPage()),
                'prev' => $orderPackages->previousPageUrl(),
                'next' => $orderPackages->nextPageUrl(),
                'current' => $orderPackages->url($orderPackages->currentPage()),
            ];

            if (count($orderPackages) > 0) {
                return [
                    'data' => $orderPackages->items(),
                    'pagination' => [
                        'total' => $orderPackages->total(),
                        'per_page' => $orderPackages->perPage(),
                        'current_page' => $orderPackages->currentPage(),
                        'last_page' => $orderPackages->lastPage(),
                        'links' => $paginationLinks
                    ]
                ];
            }
            return null;

        } catch (\Throwable $th) {
            return null;
        }
    }

    private function ordersProduct(User $user)
    {
        try {
            $id_user = $user->id;

            $orderIds = \DB::table('ecomm_orders')
                ->select(\DB::raw('MIN(id) as id'))
                ->where('id_user', $id_user)
                ->groupBy('number_order')
                ->pluck('id');

            $orderProducts = EcommOrders::whereIn('id', $orderIds)
                ->orderBy('created_at', 'DESC')
                ->select('id_payment_order', 'number_order', 'smartshipping', 'method_shipping', 'created_at')
                ->paginate(9);

            foreach ($orderProducts as $item) {
                $payment = PaymentOrderEcomm::where('id', $item->id_payment_order)->first();

                if (isset($payment)) {
                    $item['payment_status'] = ucfirst(strtolower($payment->status));
                } else {
                    $item['payment_status'] = '';
                }

                $item['total_price'] = $payment->total_price ?? '';
                $item['date'] = date('d/m/Y', strtotime($item->created_at));

                $item['qv'] = EcommOrders::where('number_order', $item->number_order)
                    ->sum('qv');

                $item['cv'] = EcommOrders::where('number_order', $item->number_order)
                    ->sum('cv');

                $pedido = EcommOrders::where('number_order', $item->number_order)
                    ->first();

                $recorrente = false;

                if (isset($pedido) && isset($pedido->id_payment_recurring)) {
                    $recorrente = true;
                }

                if ($recorrente) {
                    $item['status_smartshipping'] = 'Created by Smartshipping';
                } else {
                    if ($item->smartshipping == 1) {
                        $item['status_smartshipping'] = 'Smartshipping';
                    } else {
                        $item['status_smartshipping'] = 'Not Smartshipping';
                    }
                }
            }


            $paginationLinks = [
                'first' => $orderProducts->url(1),
                'last' => $orderProducts->url($orderProducts->lastPage()),
                'prev' => $orderProducts->previousPageUrl(),
                'next' => $orderProducts->nextPageUrl(),
                'current' => $orderProducts->url($orderProducts->currentPage()),
            ];



            if (count($orderProducts) > 0) {
                return [
                    'data' => $orderProducts->items(),
                    'pagination' => [
                        'total' => $orderProducts->total(),
                        'per_page' => $orderProducts->perPage(),
                        'current_page' => $orderProducts->currentPage(),
                        'last_page' => $orderProducts->lastPage(),
                        'links' => $paginationLinks
                    ]
                ];
            }
            return null;

        } catch (\Throwable $th) {
            return null;
        }
    }

    private function comissions($request, User $user)
    {
        try {
            $id_user = $user->id;
            $fdate = $request->first_date ?? null;
            $sdate = $request->second_date ?? null;
            $option = $request->option ?? null;

            $bonus = ConfigBonus::select('id', 'description')->get();

            $totalTrans = 0;

            if (!empty($fdate) && !empty($sdate) && !empty($option)) {
                $transactions = User::find($id_user)->banco()->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', $option)->paginate(15);
                $transactionsAll = User::find($id_user)->banco()->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', $option)->get();
                $orderIds = $transactionsAll->pluck('order_id')->unique();
                $totalTrans = 0;
                foreach ($orderIds as $t) {
                    $totalTrans += PaymentOrderEcomm::where('number_order', $t)->sum('total_price');
                }
            } else if (!empty($fdate) and !empty($sdate)) {
                $transactions = User::find($id_user)->banco()->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', '<>', '98')->paginate(15);
                $transactionsAll = User::find($id_user)->banco()->where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->orderBy('id', 'desc')->where('description', '<>', '98')->get();
                $orderIds = $transactionsAll->pluck('order_id')->unique();
                $totalTrans = 0;
                foreach ($orderIds as $t) {
                    $totalTrans += PaymentOrderEcomm::where('number_order', $t)->sum('total_price');
                }
            } else if (!empty($option)) {
                $transactions = User::find($id_user)->banco()->orderBy('id', 'desc')->where('description', $option)->paginate(15);
                $transactionsAll = User::find($id_user)->banco()->orderBy('id', 'desc')->where('description', $option)->get();
                $orderIds = $transactionsAll->pluck('order_id')->unique();
                $totalTrans = 0;
                foreach ($orderIds as $t) {
                    $totalTrans += PaymentOrderEcomm::where('number_order', $t)->sum('total_price');
                }
            } else {
                $transactions = User::find($id_user)->banco()->where('description', '<>', '98')->orderBy('id', 'DESC')->paginate(15);
                $totalTrans = 0;
                $totalTrans = PaymentOrderEcomm::where('id_user', $id_user)->sum('total_price');
            }

            $totalTrans = number_format($totalTrans, 2);

            foreach ($transactions as $item) {
                $order = EcommOrders::where('number_order', $item->order_id)->get();
                $item->qv = 0;
                $item->cv = 0;
                if (count($order) > 0) {
                    $item->qv = EcommOrders::where('number_order', $item->order_id)->sum('qv');
                    $item->cv = EcommOrders::where('number_order', $item->order_id)->sum('cv');
                }

                if ($item->description != 99) {
                    $bn = ConfigBonus::select('description')->where('id', $item->description)->first();
                    if (isset($bn)) {
                        $item->description_name = $bn->description;
                    } else {
                        $item->description_name = 'Payment order';
                    }
                } else {
                    $item->description_name = 'Payment order';
                }

                $item->user_from = $item->getUserOrderLogin($item->order_id);
                $item->package = "Product Purchase";
                $item->package_value = number_format($item->getUserOrderPackageValue($item->order_id), 2, ',', '.');
                $item->created = date('d/m/Y', strtotime($item->created_at));

            }


            $paginationLinks = [
                'first' => $transactions->url(1),
                'last' => $transactions->url($transactions->lastPage()),
                'prev' => $transactions->previousPageUrl(),
                'next' => $transactions->nextPageUrl(),
                'current' => $transactions->url($transactions->currentPage()),
            ];


            return [
                "total" => $totalTrans,
                "data" => $transactions->items(),
                "filter" => [
                    "choosed" => [
                        "option_bonus" => $option,
                        "first_date" => $fdate,
                        "Second_date" => $sdate,
                    ],
                    "option_bonus_available" => $bonus
                ],
                "pagination" => [
                    'total' => $transactions->total(),
                    'per_page' => $transactions->perPage(),
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'links' => $paginationLinks
                ]
            ];
        } catch (\Throwable $th) {
            return null;
        }

    }

    private function smartshipping(User $user)
    {
        try {
            $id_user = $user->id;

            $pedidosSmart = \DB::table('ecomm_orders')
                ->join('payments_order_ecomms', 'ecomm_orders.number_order', '=', 'payments_order_ecomms.number_order')
                ->where('ecomm_orders.smartshipping', 1)
                ->where('payments_order_ecomms.id_user', $id_user)
                ->select('payments_order_ecomms.number_order', 'payments_order_ecomms.total_price', 'payments_order_ecomms.status')
                ->distinct()
                ->get();

            foreach ($pedidosSmart as $item) {
                $item->qv = EcommOrders::where('number_order', $item->number_order)->where('smartshipping', 1)->sum('qv');
                $item->status = ucfirst(strtolower($item->status));
            }

            return $pedidosSmart;

        } catch (\Throwable $th) {
            return null;
        }
    }
}
