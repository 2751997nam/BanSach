@extends('layouts.master')

@section('title', 'Giỏ Hàng')

@section('content')
    <div style="background-color: white">
    <table class="table" style="background-color: white">
        <thead>
        <tr>
            <th>STT</th>
            <th>Sản Phẩm</th>
            <th>Đơn Giá</th>
            <th>Giảm Giá</th>
            <th>Số Lượng</th>
            <th>Thành Tiền</th>
            <th>Thao Tác</th>
        </tr>
        </thead>
        <tbody>
            @php($i = 1)
            @forelse($carts as $cart)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $cart->name }}</td>
                    <td >{{ $cart->price }}</td>
                    <td >{{ $cart->discount }} %</td>
                    <td>
                        <div class="quantity">
                            <span >Số Lượng:</span>
                            <button  style="width: 30px" class="sub">-</button>
                            <input type="text"  class="quantity-value" style="width: 50px; text-align: center" value="{{ $cart->pivot->quantity }}">
                            <button  style="width: 30px" class="add">+</button>
                            <span>{{$cart->quantity}} sản phẩm có sẵn</span>
                            <input type="hidden" class="bookID" value="{{ $cart->pivot->book_id }}">
                            <input type="hidden" class="maxQuantity" name="maxQuantity" value="{{$cart->quantity}}">
                            <input type="hidden" class="price-value" value="{{ $cart->price }}">
                            <input type="hidden" class="discount-value" value="{{ $cart->discount }}">
                        </div>
                    </td>
                    <td class="sum-value">{{ round(($cart->price * (100 - $cart->discount) / 100) * $cart->pivot->quantity, 2) }}</td>
                    <td><a href="javascript:void(0)" onclick="deleteCart(this, '{{ $cart->pivot->id }}')" class="deleteCart" role="button">Xoá</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center"> Giỏ Hàng Trống</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(count($carts) > 0)
    <div class="checkout" >
        <div class="checkout-info" style="float: right; background-color: white">
            <span>Tổng Tiền Hàng</span><span id="sumProduct"></span>
            <strong id="sumMoney" style="margin-left: 15px; color: orangered; font-size: 25px; margin-right: 15px"></strong>
            <button class="btn" style="background-color: orangered; color: white" onclick="window.location.href='{{route('order.create')}}'">Thanh Toán</button>
        </div>
    </div>
    @endif
    </div>
@endsection

@section('script')
    <script>
        var permition = 1;
        function sumUpQuantity() {
            var count = 0;
            var money = 0;
            $('.quantity-value').each( function () {
                count += parseInt($(this).val());
            })
            $('.sum-value').each(function () {
                money += parseFloat($(this).text());
            })
            money = money.toFixed(2);
            $('#sumProduct').text("(" + count + " sản phẩm)");
            $('#sumMoney').text(money + "đ");
            permition = 1;
        }
        $(document).ready(function () {
            sumUpQuantity();
        });

        $('.quantity-value').on('keydown', function(e){
            -1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
        $('.quantity-value').on('keydown keyup', function(e){
            var element = $(this);
            if (parseInt($(this).val()) > parseInt(element.siblings("input[name='maxQuantity']").val())
                && e.keyCode !== 46 // keycode for delete
                && e.keyCode !== 8 // keycode for backspace
            ) {
                e.preventDefault();
                $(this).val(element.siblings("input[name='maxQuantity']").val());
            }

            if ($(this).val() < 1
                && e.keyCode !== 46 // keycode for delete
                && e.keyCode !== 8 // keycode for backspace
            ) {
                e.preventDefault();
                $(this).val(1);
            }
        });
        function updateToDatabase(book_id, user_id, quantity) {
            $.ajax({
                url: "{{ route('cart.update') }}",
                method: "POST",
                data: {book_id: book_id, user_id: user_id, quantity: quantity,
                    _token: "{{ csrf_token()}}",  _method: "PUT" },
                success: function (result) {
                    $('.cart-dropdown-menu').html(result);
                }
            });
        }
        function changeQuantity(element) {
            var book_id = element.siblings('.bookID').val();
            var quantity = element.val();
            var price = element.siblings('.price-value').val();
            var discount = element.siblings('.discount-value').val();
            element.parents('td').siblings('.sum-value').text(
                ((parseFloat(price) * (100 - parseInt(discount)) / 100) * quantity).toFixed(2));
            updateToDatabase(book_id, "{{ $user->id }}", quantity);
        }

        $('.quantity-value').on('blur', function () {
            changeQuantity($(this));
            sumUpQuantity();
        });
        $('.sub').click(function sub() {
            $(this).unbind('click');
            if(permition === 0) return;
            permition = 0;
            var element = $(this);
            var quantity = element.siblings('.quantity-value').val();
            if(quantity > 1) quantity--;
            element.siblings('.quantity-value').val(quantity);
            changeQuantity(element.siblings('.quantity-value'));
            sumUpQuantity()
            $(this).bind('click', sub);
        });

        $('.add').click(function add() {
            $(this).unbind('click');
            if(permition === 0) return;
            permition = 0;
            var element = $(this);
            var max = element.siblings("input[name='maxQuantity']").val();
            max = parseInt(max);
            var quantity = element.siblings('.quantity-value').val();
            if(quantity < max) quantity++;
            element.siblings('.quantity-value').val(quantity);
            changeQuantity(element.siblings('.quantity-value'));
            sumUpQuantity()
            $(this).bind('click', add);
        })
        
        function deleteCart(element, cart_id) {
            $.ajax({
                url: "{{ route('cart.destroy') }}",
                method: "POST",
                data: {_method: "delete", _token: "{{ csrf_token() }}", id: cart_id},
                success: function (result) {
                    $(element).parents('tr').remove();
                    $('.cart-dropdown-menu').html(result);
                    sumUpQuantity();
                }
            })
        }
    </script>
@endsection