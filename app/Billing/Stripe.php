<?php namespace App\Billing;

use App\Exceptions\GeneralException;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Refund;
use Stripe\StripeClient;
use Stripe\Token;

class Stripe
{
    /** @var $currency */
    private $currency = 'aud';

    /** @var Stripe\StripeClient $stripe */
    private $stripe;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct($currency = null)
    {
        $this->stripe = new StripeClient(config('stripe.secret'));

        if( $currency )
        {
            $this->currency = $currency;
        }
    }

    /**
     * Method createCustomer
     *
     * @param array $customerDetails [explicite description]
     *
     * @throws GeneralException
     * @return \Stripe\Customer
     */
    public function createCustomer(array $customerDetails)
    {
        $customerArr = [
            'email'         => $customerDetails['email'] ?? null,
            'name'          => $customerDetails['name'] ?? null,
            'phone'         => $customerDetails['phone'] ?? null,
            'address'       => [
                'city'          => $customerDetails['city'] ?? null,
                'country'       => $customerDetails['country'] ?? null,
                'line1'         => $customerDetails['line1'] ?? null,
                'line2'         => $customerDetails['line2'] ?? null,
                'postal_code'   => $customerDetails['postal_code'] ?? null,
                'state'         => $customerDetails['state'] ?? null,
            ]
        ];

        try
        {
            return $this->stripe->customers->create($customerArr);
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method createToken
     *
     * @param array $input [explicite description]
     *
     * @throws GeneralException
     * @return \Stripe\Token
     */
    public function createToken(array $input)
    {
        try
        {
            return $this->stripe->tokens->create([
                'card' => $input
            ]);
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method createSource
     *
     * @param $email $email [explicite description]
     * @param $token $token [explicite description]
     *
     * @throws GeneralException
     * @return mixed
     */
    public function createSource($email, $token)
    {
        try
        {
            return $this->stripe->sources->create([
                "type" => "card",
                "currency" => $this->currency,
                "owner" => [
                    "email" => $email
                ],
                'token' => $token->id
            ]);
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method attachSource
     *
     * @param $customerId $customerId [explicite description]
     * @param $sourceId $sourceId [explicite description]
     *
     * @return \Stripe\Account|\Stripe\BankAccount|\Stripe\Card|\Stripe\Source
     * @throws \App\Exceptions\GeneralException
     */
    public function attachSource($customerId, $sourceId)
    {
        try
        {
            return $this->stripe->customers->createSource(
                $customerId,
                [
                    'source' => $sourceId,
                ]
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method getSingleCard
     *
     * @param $customerId $customerId [explicite description]
     * @param $cardId $cardId [explicite description]
     *
     * @return \Stripe\Account|\Stripe\BankAccount|\Stripe\Card|\Stripe\Source
     */
    public function getSingleCard(string $customerId, string $cardId)
    {
        try
        {
            return $this->stripe->customers->retrieveSource(
                $customerId,
                $cardId,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method deleteCard
     *
     * @param $customerId $customerId [explicite description]
     * @param $cardId $cardId [explicite description]
     *
     * @return mixed
     */
    public function deleteCard($customerId, $cardId)
    {
        try
        {
            return $this->stripe->customers->deleteSource(
                $customerId,
                $cardId,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method Fetch Cards
     *
     * @param $customerId $customerId
     *
     * @return mixed
     */
    public function fetchCards($customerId)
    {
        try
        {
            return $this->stripe->customers->allSources(
                $customerId,
                [
                    'object' => 'card'
                ]
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method fetchCustomer
     *
     * @param $customerId $customerId [explicite description]
     *
     * @return mixed
     */
    public function fetchCustomer($customerId)
    {
        try
        {
            return $this->stripe->customers->retrieve(
                $customerId,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method retrieveToken
     *
     * @param string $token [explicite description]
     *
     * @return Token
     * @throws \App\Exceptions\GeneralException
     */
    public function retrieveToken($token): Token
    {
        if( isset( $token ) && $token != '' )
        {
            try
            {
                return $this->stripe->tokens->retrieve(
                    $token,
                    []
                );
            }
            catch(ApiErrorException $e)
            {
                throw new GeneralException($e->getError()->message);
            }
        }
        else
        {
            throw new GeneralException('There is no token found. Token is required.');
        }
    }

    public function retrieveSource(string $customerId, string $cardId)
    {
        try
        {
            return $this->stripe->customers->retrieveSource(
                $customerId,
                $cardId,
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method createCharge
     *
     * @param array $data [explicite description]
     *
     * @return Charge
     */
    public function createCharge(array $data): Charge
    {
        /*
        $data = [
            'amount' => 2000,
            'currency' => 'aud',
            'customer' => CUSTOMER_ID,
            'capture' => true,
            'source' => 'tok_visa', //A payment source to be charged. This can be the ID of a card (i.e., credit or debit card), a bank account, a source, a token, or a connected account. For certain sources—namely, cards, bank accounts, and attached sources—you must also pass the ID of the associated customer.
            'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
        ]
        */
        try
        {
            return $this->stripe->charges->create($data);
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method captureCharge
     *
     * @param string $charge [explicite description]
     *
     * @return Charge
     */
    public function captureCharge(string $charge): Charge
    {
        try
        {
            return $this->stripe->charges->capture(
                $charge,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method retrieveCharge
     *
     * @param string $charge [explicite description]
     *
     * @return Charge
     */
    public function retrieveCharge(string $charge): Charge
    {
        try
        {
            return $this->stripe->charges->retrieve(
                $charge,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }

    /**
     * Method refundCreate
     *
     * @param array $charge [explicite description]
     *
     * @return Charge
     */
    public function refundCreate(array $charge) : Refund
    {
        try {
            return $this->stripe->refunds->create(
                $charge,
                []
            );
        }
        catch(ApiErrorException $e)
        {
            throw new GeneralException($e->getError()->message);
        }
    }
}
