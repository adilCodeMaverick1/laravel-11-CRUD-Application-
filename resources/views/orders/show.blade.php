<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
</head>
<body>
    <h1>Order Details</h1>

    @if(session('success'))
        <div style="background: green; color: white; padding: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: red; color: white; padding: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Amount:</strong> PKR {{ $order->amount / 100 }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
    <p><strong>Tracker:</strong> {{ $order->tracker }}</p>

    @if($order->payment_reference)
        <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
    @endif

    @if($order->verified_at)
        <p><strong>Verified At:</strong> {{ $order->verified_at }}</p>
    @endif

    <p><strong>Created:</strong> {{ $order->created_at }}</p>
</body>
</html>