<form action="{{ route('payment.initiate') }}" method="POST">
    @csrf

    <h2>Total: PKR 500.00</h2>

    <input type="hidden" name="amount" value="500">
    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Pay with PayFast</button>
</form>