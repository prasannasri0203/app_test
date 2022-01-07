<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CronjobController extends Controller
{
	    public function getResponseFromStripe(){

			require 'vendor/autoload.php';

	        \Stripe\Stripe::setApiKey('sk_test_51JUrcbSE4i3X4uira80g3bGMuLOC7qtM92otqGJJjjAhfqVZOItL
	 	     ow8SdRJlxOUryIjfcftwQMIsYkgT6AGfQJYV00HPbFqDx0');

			$payload = @file_get_contents('php://input');
			$event = null;

			try {
			  $event = \Stripe\Event::constructFrom(
			    json_decode($payload, true)
			  );
			} catch(\UnexpectedValueException $e) {
			  // Invalid payload
			  echo 'Webhook error while parsing basic request.';
			  http_response_code(400);
			  exit();
			}

			// Handle the event
			switch ($event->type) {
			  case 'payment_intent.succeeded':
			    $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
			    // Then define and call a method to handle the successful payment intent.
			    // handlePaymentIntentSucceeded($paymentIntent);
			    break;
			  case 'payment_method.attached':
			    $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
			    // Then define and call a method to handle the successful attachment of a PaymentMethod.
			    // handlePaymentMethodAttached($paymentMethod);
			    break;
			  default:
			    // Unexpected event type
			    echo 'Received unknown event type';
			}
			http_response_code(200);
		}
