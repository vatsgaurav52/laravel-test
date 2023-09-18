@extends('layouts.app') <!-- You can use your own layout if available -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Purchase a Product') }}</div>

                <div class="card-body">
                    <form method="POST" id="payment-form" action="{{ route('purchase') }}">
                        @csrf

                        <div class="form-group">
                            <label for="product_type">Select Product:</label>
                            <select name="product_type" id="product_type" class="form-control" required>
                                <option value="B2C">B2C Product</option>
                                <option value="B2B">B2B Product</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="card-element">Credit Card Details:</label>
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>

                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>

                        <button type="submit" id="purchase-button" class="btn btn-primary mt-3">
                            {{ __('Purchase') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<!-- Include Stripe.js library -->
<script src="https://js.stripe.com/v3/"></script>

<!-- JavaScript to handle Stripe payment -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

    // Create a Stripe client
    var stripe = Stripe("{{env('STRIPE_KEY')}}");

    // Create an instance of Elements
    var elements = stripe.elements();

    // Create a card Element
    var card = elements.create('card');

    // Add the card Element to the DOM
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    console.log('dd');
    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Disable the submit button to prevent multiple submissions
        document.getElementById('purchase-button').disabled = true;

        // Create a payment method with the card Element
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                // Show error to your customer (e.g., insufficient funds, card declined)
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;

                // Enable the submit button
                document.getElementById('purchase-button').disabled = false;
            } else {

                // Tokenize the payment method ID and send it to your server
                var token = result.paymentMethod.id;
                console.log('result.paymentMethod.card',result.paymentMethod.card);
                // Append the token to the form data
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token);
                form.appendChild(hiddenInput);

                var hiddenInputBrand = document.createElement('input');
                hiddenInputBrand.setAttribute('type', 'hidden');
                hiddenInputBrand.setAttribute('name', 'brand');
                hiddenInputBrand.setAttribute('value', result.paymentMethod.card.brand);
                form.appendChild(hiddenInputBrand);

                var hiddenInputCard = document.createElement('input');
                hiddenInputCard.setAttribute('type', 'hidden');
                hiddenInputCard.setAttribute('name', 'lastFourDigits');
                hiddenInputCard.setAttribute('value', result.paymentMethod.card.last4);
                form.appendChild(hiddenInputCard);
                

                // Submit the form
                form.submit();
            }
        });

    });

});
</script>