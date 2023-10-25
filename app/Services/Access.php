<?php namespace App\Services;

use App\Models\User;

/**
 * Class Access
 */
class Access
{
    /**
     * Get the currently authenticated user or null.
     */
    public function user()
    {
        return auth()->user();
    }

    /**
     * Return if the current session user is a guest or not.
     *
     * @return mixed
     */
    public function guest()
    {
        return auth()->guest();
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        return auth()->logout();
    }

    /**
     * Get the currently authenticated user's id.
     *
     * @return mixed
     */
    public function id()
    {
        return auth()->id();
    }

    /**
     * Method isAdmin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user()->user_type == User::ADMIN;
    }

    /**
     * Method isRestaurantOwner
     *
     * @return bool
     */
    public function isRestaurantOwner()
    {
        return $this->user()->user_type == User::RESTAURANT_OWNER;
    }

    /**
     * Method isBartender
     *
     * @return bool
     */
    public function isBartender()
    {
        return $this->user()->user_type == User::BARTENDER;
    }

    /**
     * Method isCustomer
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->user()->user_type == User::CUSTOMER;
    }

    /**
     * Method isKitchen
     *
     * @return bool
     */
    public function isKitchen()
    {
        return $this->user()->user_type == User::KITCHEN;
    }

    /**
     * Method isWaiter
     *
     * @return bool
     */
    public function isWaiter()
    {
        return $this->user()->user_type == User::WAITER;
    }


}