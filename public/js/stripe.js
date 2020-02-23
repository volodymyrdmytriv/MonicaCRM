(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/stripe"],{

/***/ "./resources/assets/js/stripe.js":
/*!***************************************!*\
  !*** ./resources/assets/js/stripe.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var elements = stripe.elements(); // Custom styling can be passed to options when creating an Element.\n// (Note that this demo uses a wider set of styles than the guide below.)\n\nvar style = {\n  base: {\n    color: '#32325d',\n    lineHeight: '18px',\n    fontFamily: '\"Helvetica Neue\", Helvetica, sans-serif',\n    fontSmoothing: 'antialiased',\n    fontSize: '16px',\n    '::placeholder': {\n      color: '#aab7c4'\n    }\n  },\n  invalid: {\n    color: '#fa755a',\n    iconColor: '#fa755a'\n  }\n}; // Create an instance of the card Element\n\nvar card = elements.create('card', {\n  hidePostalCode: true,\n  style: style\n}); // Add an instance of the card Element into the `card-element` <div>\n\ncard.mount('#card-element'); // Handle real-time validation errors from the card Element.\n\ncard.addEventListener('change', function (event) {\n  var displayError = document.getElementById('card-errors');\n\n  if (event.error) {\n    displayError.textContent = event.error.message;\n  } else {\n    displayError.textContent = '';\n  }\n}); // Handle form submission\n\nvar form = document.getElementById('payment-form');\nform.addEventListener('submit', function (event) {\n  event.preventDefault();\n  var extraDetails = {\n    name: this.querySelector('input[name=cardholder-name]').value,\n    address_zip: this.querySelector('input[name=address-zip]').value\n  };\n  stripe.createToken(card, extraDetails).then(function (result) {\n    if (result.error) {\n      // Inform the user if there was an error\n      var errorElement = document.getElementById('card-errors');\n      errorElement.textContent = result.error.message;\n    } else {\n      // Send the token to your server\n      stripeTokenHandler(result.token);\n    }\n  });\n});\n\nfunction stripeTokenHandler(token) {\n  // Insert the token ID into the form so it gets submitted to the server\n  var form = document.getElementById('payment-form');\n  var hiddenInput = document.createElement('input');\n  hiddenInput.setAttribute('type', 'hidden');\n  hiddenInput.setAttribute('name', 'stripeToken');\n  hiddenInput.setAttribute('value', token.id);\n  form.appendChild(hiddenInput); // Submit the form\n\n  form.submit();\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvYXNzZXRzL2pzL3N0cmlwZS5qcz9hZmNhIl0sIm5hbWVzIjpbImVsZW1lbnRzIiwic3RyaXBlIiwic3R5bGUiLCJiYXNlIiwiY29sb3IiLCJsaW5lSGVpZ2h0IiwiZm9udEZhbWlseSIsImZvbnRTbW9vdGhpbmciLCJmb250U2l6ZSIsImludmFsaWQiLCJpY29uQ29sb3IiLCJjYXJkIiwiY3JlYXRlIiwiaGlkZVBvc3RhbENvZGUiLCJtb3VudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJldmVudCIsImRpc3BsYXlFcnJvciIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJlcnJvciIsInRleHRDb250ZW50IiwibWVzc2FnZSIsImZvcm0iLCJwcmV2ZW50RGVmYXVsdCIsImV4dHJhRGV0YWlscyIsIm5hbWUiLCJxdWVyeVNlbGVjdG9yIiwidmFsdWUiLCJhZGRyZXNzX3ppcCIsImNyZWF0ZVRva2VuIiwidGhlbiIsInJlc3VsdCIsImVycm9yRWxlbWVudCIsInN0cmlwZVRva2VuSGFuZGxlciIsInRva2VuIiwiaGlkZGVuSW5wdXQiLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwiaWQiLCJhcHBlbmRDaGlsZCIsInN1Ym1pdCJdLCJtYXBwaW5ncyI6IkFBQUEsSUFBSUEsUUFBUSxHQUFHQyxNQUFNLENBQUNELFFBQVAsRUFBZixDLENBRUE7QUFDQTs7QUFDQSxJQUFJRSxLQUFLLEdBQUc7QUFDUkMsTUFBSSxFQUFFO0FBQ0ZDLFNBQUssRUFBRSxTQURMO0FBRUZDLGNBQVUsRUFBRSxNQUZWO0FBR0ZDLGNBQVUsRUFBRSx5Q0FIVjtBQUlGQyxpQkFBYSxFQUFFLGFBSmI7QUFLRkMsWUFBUSxFQUFFLE1BTFI7QUFNRixxQkFBaUI7QUFDYkosV0FBSyxFQUFFO0FBRE07QUFOZixHQURFO0FBV1JLLFNBQU8sRUFBRTtBQUNMTCxTQUFLLEVBQUUsU0FERjtBQUVMTSxhQUFTLEVBQUU7QUFGTjtBQVhELENBQVosQyxDQWlCQTs7QUFDQSxJQUFJQyxJQUFJLEdBQUdYLFFBQVEsQ0FBQ1ksTUFBVCxDQUFnQixNQUFoQixFQUF3QjtBQUMvQkMsZ0JBQWMsRUFBRSxJQURlO0FBRS9CWCxPQUFLLEVBQUVBO0FBRndCLENBQXhCLENBQVgsQyxDQUtBOztBQUNBUyxJQUFJLENBQUNHLEtBQUwsQ0FBVyxlQUFYLEUsQ0FFQTs7QUFDQUgsSUFBSSxDQUFDSSxnQkFBTCxDQUFzQixRQUF0QixFQUFnQyxVQUFTQyxLQUFULEVBQWdCO0FBQzVDLE1BQUlDLFlBQVksR0FBR0MsUUFBUSxDQUFDQyxjQUFULENBQXdCLGFBQXhCLENBQW5COztBQUNBLE1BQUlILEtBQUssQ0FBQ0ksS0FBVixFQUFpQjtBQUNiSCxnQkFBWSxDQUFDSSxXQUFiLEdBQTJCTCxLQUFLLENBQUNJLEtBQU4sQ0FBWUUsT0FBdkM7QUFDSCxHQUZELE1BRU87QUFDSEwsZ0JBQVksQ0FBQ0ksV0FBYixHQUEyQixFQUEzQjtBQUNIO0FBQ0osQ0FQRCxFLENBU0E7O0FBQ0EsSUFBSUUsSUFBSSxHQUFHTCxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsY0FBeEIsQ0FBWDtBQUNBSSxJQUFJLENBQUNSLGdCQUFMLENBQXNCLFFBQXRCLEVBQWdDLFVBQVNDLEtBQVQsRUFBZ0I7QUFDNUNBLE9BQUssQ0FBQ1EsY0FBTjtBQUVBLE1BQUlDLFlBQVksR0FBRztBQUNmQyxRQUFJLEVBQUUsS0FBS0MsYUFBTCxDQUFtQiw2QkFBbkIsRUFBa0RDLEtBRHpDO0FBRWZDLGVBQVcsRUFBRSxLQUFLRixhQUFMLENBQW1CLHlCQUFuQixFQUE4Q0M7QUFGNUMsR0FBbkI7QUFLQTNCLFFBQU0sQ0FBQzZCLFdBQVAsQ0FBbUJuQixJQUFuQixFQUF5QmMsWUFBekIsRUFBdUNNLElBQXZDLENBQTRDLFVBQVNDLE1BQVQsRUFBaUI7QUFDekQsUUFBSUEsTUFBTSxDQUFDWixLQUFYLEVBQWtCO0FBQ2Q7QUFDQSxVQUFJYSxZQUFZLEdBQUdmLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixhQUF4QixDQUFuQjtBQUNBYyxrQkFBWSxDQUFDWixXQUFiLEdBQTJCVyxNQUFNLENBQUNaLEtBQVAsQ0FBYUUsT0FBeEM7QUFDSCxLQUpELE1BSU87QUFDSDtBQUNBWSx3QkFBa0IsQ0FBQ0YsTUFBTSxDQUFDRyxLQUFSLENBQWxCO0FBQ0g7QUFDSixHQVREO0FBVUgsQ0FsQkQ7O0FBb0JBLFNBQVNELGtCQUFULENBQTRCQyxLQUE1QixFQUFtQztBQUMvQjtBQUNBLE1BQUlaLElBQUksR0FBR0wsUUFBUSxDQUFDQyxjQUFULENBQXdCLGNBQXhCLENBQVg7QUFDQSxNQUFJaUIsV0FBVyxHQUFHbEIsUUFBUSxDQUFDbUIsYUFBVCxDQUF1QixPQUF2QixDQUFsQjtBQUNBRCxhQUFXLENBQUNFLFlBQVosQ0FBeUIsTUFBekIsRUFBaUMsUUFBakM7QUFDQUYsYUFBVyxDQUFDRSxZQUFaLENBQXlCLE1BQXpCLEVBQWlDLGFBQWpDO0FBQ0FGLGFBQVcsQ0FBQ0UsWUFBWixDQUF5QixPQUF6QixFQUFrQ0gsS0FBSyxDQUFDSSxFQUF4QztBQUNBaEIsTUFBSSxDQUFDaUIsV0FBTCxDQUFpQkosV0FBakIsRUFQK0IsQ0FTL0I7O0FBQ0FiLE1BQUksQ0FBQ2tCLE1BQUw7QUFDSCIsImZpbGUiOiIuL3Jlc291cmNlcy9hc3NldHMvanMvc3RyaXBlLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsidmFyIGVsZW1lbnRzID0gc3RyaXBlLmVsZW1lbnRzKCk7XHJcblxyXG4vLyBDdXN0b20gc3R5bGluZyBjYW4gYmUgcGFzc2VkIHRvIG9wdGlvbnMgd2hlbiBjcmVhdGluZyBhbiBFbGVtZW50LlxyXG4vLyAoTm90ZSB0aGF0IHRoaXMgZGVtbyB1c2VzIGEgd2lkZXIgc2V0IG9mIHN0eWxlcyB0aGFuIHRoZSBndWlkZSBiZWxvdy4pXHJcbnZhciBzdHlsZSA9IHtcclxuICAgIGJhc2U6IHtcclxuICAgICAgICBjb2xvcjogJyMzMjMyNWQnLFxyXG4gICAgICAgIGxpbmVIZWlnaHQ6ICcxOHB4JyxcclxuICAgICAgICBmb250RmFtaWx5OiAnXCJIZWx2ZXRpY2EgTmV1ZVwiLCBIZWx2ZXRpY2EsIHNhbnMtc2VyaWYnLFxyXG4gICAgICAgIGZvbnRTbW9vdGhpbmc6ICdhbnRpYWxpYXNlZCcsXHJcbiAgICAgICAgZm9udFNpemU6ICcxNnB4JyxcclxuICAgICAgICAnOjpwbGFjZWhvbGRlcic6IHtcclxuICAgICAgICAgICAgY29sb3I6ICcjYWFiN2M0J1xyXG4gICAgICAgIH1cclxuICAgIH0sXHJcbiAgICBpbnZhbGlkOiB7XHJcbiAgICAgICAgY29sb3I6ICcjZmE3NTVhJyxcclxuICAgICAgICBpY29uQ29sb3I6ICcjZmE3NTVhJ1xyXG4gICAgfVxyXG59O1xyXG5cclxuLy8gQ3JlYXRlIGFuIGluc3RhbmNlIG9mIHRoZSBjYXJkIEVsZW1lbnRcclxudmFyIGNhcmQgPSBlbGVtZW50cy5jcmVhdGUoJ2NhcmQnLCB7XHJcbiAgICBoaWRlUG9zdGFsQ29kZTogdHJ1ZSxcclxuICAgIHN0eWxlOiBzdHlsZVxyXG59KTtcclxuXHJcbi8vIEFkZCBhbiBpbnN0YW5jZSBvZiB0aGUgY2FyZCBFbGVtZW50IGludG8gdGhlIGBjYXJkLWVsZW1lbnRgIDxkaXY+XHJcbmNhcmQubW91bnQoJyNjYXJkLWVsZW1lbnQnKTtcclxuXHJcbi8vIEhhbmRsZSByZWFsLXRpbWUgdmFsaWRhdGlvbiBlcnJvcnMgZnJvbSB0aGUgY2FyZCBFbGVtZW50LlxyXG5jYXJkLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uKGV2ZW50KSB7XHJcbiAgICB2YXIgZGlzcGxheUVycm9yID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NhcmQtZXJyb3JzJyk7XHJcbiAgICBpZiAoZXZlbnQuZXJyb3IpIHtcclxuICAgICAgICBkaXNwbGF5RXJyb3IudGV4dENvbnRlbnQgPSBldmVudC5lcnJvci5tZXNzYWdlO1xyXG4gICAgfSBlbHNlIHtcclxuICAgICAgICBkaXNwbGF5RXJyb3IudGV4dENvbnRlbnQgPSAnJztcclxuICAgIH1cclxufSk7XHJcblxyXG4vLyBIYW5kbGUgZm9ybSBzdWJtaXNzaW9uXHJcbnZhciBmb3JtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BheW1lbnQtZm9ybScpO1xyXG5mb3JtLmFkZEV2ZW50TGlzdGVuZXIoJ3N1Ym1pdCcsIGZ1bmN0aW9uKGV2ZW50KSB7XHJcbiAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuICAgIHZhciBleHRyYURldGFpbHMgPSB7XHJcbiAgICAgICAgbmFtZTogdGhpcy5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPWNhcmRob2xkZXItbmFtZV0nKS52YWx1ZSxcclxuICAgICAgICBhZGRyZXNzX3ppcDogdGhpcy5xdWVyeVNlbGVjdG9yKCdpbnB1dFtuYW1lPWFkZHJlc3MtemlwXScpLnZhbHVlLFxyXG4gICAgfTtcclxuXHJcbiAgICBzdHJpcGUuY3JlYXRlVG9rZW4oY2FyZCwgZXh0cmFEZXRhaWxzKS50aGVuKGZ1bmN0aW9uKHJlc3VsdCkge1xyXG4gICAgICAgIGlmIChyZXN1bHQuZXJyb3IpIHtcclxuICAgICAgICAgICAgLy8gSW5mb3JtIHRoZSB1c2VyIGlmIHRoZXJlIHdhcyBhbiBlcnJvclxyXG4gICAgICAgICAgICB2YXIgZXJyb3JFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NhcmQtZXJyb3JzJyk7XHJcbiAgICAgICAgICAgIGVycm9yRWxlbWVudC50ZXh0Q29udGVudCA9IHJlc3VsdC5lcnJvci5tZXNzYWdlO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgIC8vIFNlbmQgdGhlIHRva2VuIHRvIHlvdXIgc2VydmVyXHJcbiAgICAgICAgICAgIHN0cmlwZVRva2VuSGFuZGxlcihyZXN1bHQudG9rZW4pO1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG59KTtcclxuXHJcbmZ1bmN0aW9uIHN0cmlwZVRva2VuSGFuZGxlcih0b2tlbikge1xyXG4gICAgLy8gSW5zZXJ0IHRoZSB0b2tlbiBJRCBpbnRvIHRoZSBmb3JtIHNvIGl0IGdldHMgc3VibWl0dGVkIHRvIHRoZSBzZXJ2ZXJcclxuICAgIHZhciBmb3JtID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BheW1lbnQtZm9ybScpO1xyXG4gICAgdmFyIGhpZGRlbklucHV0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnKTtcclxuICAgIGhpZGRlbklucHV0LnNldEF0dHJpYnV0ZSgndHlwZScsICdoaWRkZW4nKTtcclxuICAgIGhpZGRlbklucHV0LnNldEF0dHJpYnV0ZSgnbmFtZScsICdzdHJpcGVUb2tlbicpO1xyXG4gICAgaGlkZGVuSW5wdXQuc2V0QXR0cmlidXRlKCd2YWx1ZScsIHRva2VuLmlkKTtcclxuICAgIGZvcm0uYXBwZW5kQ2hpbGQoaGlkZGVuSW5wdXQpO1xyXG5cclxuICAgIC8vIFN1Ym1pdCB0aGUgZm9ybVxyXG4gICAgZm9ybS5zdWJtaXQoKTtcclxufVxyXG4iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/assets/js/stripe.js\n");

/***/ }),

/***/ 2:
/*!*********************************************!*\
  !*** multi ./resources/assets/js/stripe.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\Develop\monica\monica\resources\assets\js\stripe.js */"./resources/assets/js/stripe.js");


/***/ })

},[[2,"/js/manifest"]]]);