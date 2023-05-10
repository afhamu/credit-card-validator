<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Credit Card Validator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

</head>
<body class="antialiased">
<h1 class="text-center my-5">Credit Card Validator</h1>
<div class="container border p-5" style="width: 50%; margin: auto">

    <div class="alert alert-success alert-block" style="display: none">
        <strong id="success-message"></strong>
    </div>

    <div class="alert alert-danger alert-block" style="display: none">
        <strong id="error-message"> </strong>
    </div>

    <form method="POST">
        @csrf
        <div class="row gy-3">
            <div class="col-md-6">
                <label for="cc-name" class="form-label">Name on card</label>
                <input type="text" class="form-control" id="cc-name" name="cc_holder_name" placeholder="Holder Name"
                       required>
            </div>

            <div class="col-md-6">
                <label for="cc-number" class="form-label">Credit card number</label>
                <input type="text" class="form-control" id="cc-number" name="cc_number"
                       placeholder="0000 00000 0000 0000" required>
            </div>

            <div class="col-md-4">
                <label for="cc-expiration-month" class="form-label">Expiration MM</label>
                <select class="form-select" id="cc-expiration-month" name="cc_expiration_month" required>
                    <option value="">Month</option>
                    @foreach(range(1,12) as $month)
                        <option value="{{$month}}">
                            {{date("M", strtotime('2016-'.$month))}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="cc-expiration-year" class="form-label">Expiration YY</label>
                <select class="form-select" id="cc-expiration-year" name="cc_expiration_year" required="">
                    <option value="">Year</option>
                    @for ($year = date('Y')+10; $year > date('Y') - 20; $year--)
                        <option value="{{$year}}">
                            {{$year}}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <label for="cc-cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cc-cvv" name="cc_cvv" placeholder="CVV" required>
            </div>

            <button type="button" class="w-100 btn btn-success btn-lg" id="btn-validate">Validate</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#btn-validate').click(function (e) {
            e.preventDefault();

            let card_holder = $('#cc-name').val();
            let card_number = $('#cc-number').val();
            let expiry_month = $('#cc-expiration-month').val();
            let expiry_year = $('#cc-expiration-year').val();
            let cvv = $('#cc-cvv').val();

            let data = {
                'card_holder': card_holder,
                'card_number': card_number,
                'expiry_month': expiry_month,
                'expiry_year': expiry_year,
                'cvv': cvv
            }

            $.ajax({
                type: 'POST',
                url: "http://localhost:8000/api/credit-card/validate",
                data: {'data': data},
                dataType: 'JSON',
                success: function (response) {
                    if (response.status == "success") {
                        $('.alert-danger').css("display", "none");
                        $('.alert-success').css("display", "block");
                        $('.alert-success').html(response.message);
                    } else {
                        $('.alert-success').css("display", "none");
                        $('.alert-danger').css("display", "block");
                        $('.alert-danger').html(response.message);
                    }

                },

            });

        });
    });
</script>
</body>
</html>
