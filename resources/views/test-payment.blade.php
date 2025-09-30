<!DOCTYPE html>
<html>
<head>
    <title>Test Payment Verification</title>
</head>
<body>
    <h1>Test Payment Verification</h1>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Tracker:</strong> {{ $order->tracker }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>

    <form method="GET" action="{{ route('payment.success', $order->id) }}">
        <label>Tracker (optional, will use stored tracker if empty):</label>
        <input type="text" name="tracker" value="{{ $order->tracker }}" style="width: 400px;">
        <br><br>
        <label>TBT (from SafePay URL):</label>
        <input type="text" name="tbt" value="" style="width: 400px;" placeholder="Paste TBT from SafePay URL">
        <br><br>
        <button type="submit">Test Payment Verification</button>
    </form>

    <h3>Instructions:</h3>
    <ol>
        <li>Complete payment on SafePay</li>
        <li>Copy the TBT value from the SafePay success URL</li>
        <li>Paste it in the form above and click "Test Payment Verification"</li>
    </ol>
</body>
</html>