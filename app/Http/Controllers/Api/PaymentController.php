<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    /**
     * Allows a student to add a payment method to his/her account
     * @param Request $request
     * @return string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addPaymentMethod(Request $request){
        $this->validate($request, [
            'payment' => 'required'
        ]);

        $user = $request->user();
        $paymentMethod = $request->input('payment');

        if(!$user->stripe_id){
            $user->createAsStripeCustomer();
        }

        $user->addPaymentMethod($paymentMethod);

        if(!$user->hasDefaultPaymentMethod()) {
            // Add the newly added payment method as default
            $user->updateDefaultPaymentMethod($paymentMethod);
        }

        return response()->json([
            'success' => true,
            'payment_method' => $paymentMethod
        ]);
    }

    /**
     * Allows a student to delete a payment method
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePaymentMethod(Request $request){
        error_log(print_r($request->all, true));
        $this->validate($request, [
            'payment_id' => 'required'
        ]);

        $paymentMethod = $request->user()->findPaymentMethod($request->input('payment_id'));
        if($paymentMethod) {
            $paymentMethod->delete();
        }else{
            return response()->json([
                'success' => false,
                'message' => "Payment method does not exist"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => ""
        ]);
    }

    public function subscribe(Request $request){
        $this->validate($request, [
            'card_id' => 'required',
            'plan_id' => 'required'
        ]);
        // todo: Check that the card id is owned by the logged in user !

        $user = $request->user();
        $planId = $request->input('plan_id');
        $cardId = $request->input('card_id');

        if(!$user->subscribed('default')) {
            $user->newSubscription('default', $planId)->create($cardId);
        }else{
            return response()->json([
                'success' => false,
                'message' => "You are already subscribed !"
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Thank you for subscribing !"
        ]);
    }
}
