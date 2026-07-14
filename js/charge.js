// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
var stripe = Stripe('pk_test_51HuaapIOJZX8IcEVlWJYiGufxerWWYBmJt2JpVhiYFl9EVWdPm7LPeBOyX1UmbNIfvgRtBVTkdbahWWdpeNs71aU00bp1zg2oQ');
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
var style = {
    base: {
      // Add your base input styles here. For example:
      fontSmoothing: 'antialiased',
      color: '#32325d',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
  };
  
  // Create an instance of the card Element.
  var card = elements.create('card', {style: style});
  
  // Add an instance of the card Element into the `card-element` <div>.
  card.mount('#card-element');

  // Create a token or display an error when the form is submitted.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the customer that there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Send the token to your server.
      stripeTokenHandler(result.token);
    }
  });
});

function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
  
    // Submit the form
    form.submit();
  }