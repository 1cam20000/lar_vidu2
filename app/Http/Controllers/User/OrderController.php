<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private $endpoint    = 'https://test-payment.momo.vn/v2/gateway/api/create';
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey   = 'klm05TvNBzhg7h7j';
    private $secretKey   = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

    // Hiển thị form thanh toán
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        return view('user.payment.index', compact('cart'));
    }

    // Lưu đơn hàng
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'payment_method' => 'required|in:cod,momo',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('user.cart.index')->with('error', 'Không thể thanh toán vì giỏ hàng trống.');
        }

        $total = $cart->items->sum(fn($i) => $i->product->price * $i->quantity);

        $order = Order::create([
            'user_id'       => Auth::id(),
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'total_price'   => $total,
            'status'        => 'chờ thanh toán',
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart->items as $ci) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $ci->product_id,
                'quantity'   => $ci->quantity,
                'price'      => $ci->product->price,
            ]);
        }

        $cart->items()->delete();

        if ($request->payment_method === 'momo') {
            return $this->redirectToMoMo($order);
        }

        $order->update(['status' => 'đã đặt (COD)']);
        return redirect()->route('user.orders.index')->with('success', 'Đặt hàng COD thành công!');
    }

    protected function redirectToMoMo(Order $order)
    {
        $redirectUrl = route('user.payment.momo.callback');
        $ipnUrl      = route('user.payment.momo.ipn');
        $orderId     = time() . '_' . $order->id;
        $requestId   = uniqid();
        $amount      = (string) max(1000, (int)$order->total_price);
        $orderInfo   = "Thanh toán đơn hàng #{$order->id}";
        $extraData   = '';
        $requestType = 'payWithATM';

        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $payload = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => 'MyShop',
            'storeId'     => 'Store_01',
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $response = Http::post($this->endpoint, $payload);
        $json = $response->json();
        Log::info('MoMo resp:', $json);

        if (!empty($json['payUrl'])) {
            $order->update(['momo_order_id' => $orderId, 'momo_request_id' => $requestId]);
            return redirect()->away($json['payUrl']);
        }

        return redirect()->route('user.orders.index')->with('error', 'Không tạo được link MoMo: ' . ($json['message'] ?? ''));
    }

    public function callback(Request $request)
    {
        $parts = explode('_', $request->orderId);
        $oid = end($parts);
        $order = Order::find($oid);

        if ($request->resultCode == 0) {
            $order?->update(['status' => 'đã thanh toán (MoMo)']);
            return redirect()->route('user.orders.index')->with('success', 'Thanh toán MoMo thành công!');
        }
        $order?->update(['status' => 'thanh toán MoMo không thành công']);
        return redirect()->route('user.payment.index')->with('error', 'Thanh toán MoMo thất bại!');
    }

    public function ipn(Request $request)
    {
        $parts = explode('_', $request->orderId);
        $oid = end($parts);
        if ($order = Order::find($oid)) {
            $order->update(['status' => $request->resultCode == 0 ? 'đã thanh toán (MoMo)' : 'thanh toán thất bại (MoMo)']);
        }
        return response()->json(['resultCode' => 0, 'message' => 'Received']);
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->latest()->get();
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');
        return view('user.orders.show', compact('order'));
    }

    public function payAgain(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        return $this->redirectToMoMo($order);
    }
}
