
<form action="{{ route('order.store') }}" method="POST">
    {{ csrf_field() }}
    <label for="address">Address:</label><input maxlength="500" type="text" name="address" required>
    <label for="phone">Phone:</label><input type="text" name="phone" required maxlength="15">
    <button type="submit">Thanh Toán</button>
</form>