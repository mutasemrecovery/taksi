<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

     protected $guarded = [];
     
      public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the driver associated with the transaction.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the admin who created the transaction.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the transaction type text.
     *
     * @return string
     */
    public function getTransactionTypeText()
    {
        return $this->type_of_transaction == 1 
               ? __('messages.Deposit') 
               : __('messages.Withdrawal');
    }

    /**
     * Get the entity type (user or driver).
     *
     * @return string
     */
    public function getEntityType()
    {
        if ($this->user_id) {
            return 'user';
        } elseif ($this->driver_id) {
            return 'driver';
        } else {
            return 'unknown';
        }
    }

    /**
     * Get the entity ID (user_id or driver_id).
     *
     * @return int|null
     */
    public function getEntityId()
    {
        if ($this->user_id) {
            return $this->user_id;
        } elseif ($this->driver_id) {
            return $this->driver_id;
        } else {
            return null;
        }
    }

    /**
     * Get the entity name.
     *
     * @return string
     */
    public function getEntityName()
    {
        if ($this->user_id && $this->user) {
            return $this->user->name;
        } elseif ($this->driver_id && $this->driver) {
            return $this->driver->name;
        } else {
            return __('messages.Unknown');
        }
    }

    /**
     * Get formatted amount with sign.
     *
     * @return string
     */
    public function getFormattedAmount()
    {
        $prefix = $this->type_of_transaction == 1 ? '+' : '-';
        return $prefix . $this->amount;
    }

}
