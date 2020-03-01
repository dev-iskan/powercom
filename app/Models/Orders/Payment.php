<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'payment_method',
        'paid',
        'cancelled',
        'paid_time',
        'cancelled_time'
    ];

    protected $dates = [
        'paid_time',
        'cancelled_time',
        'payme_time',
        'payme_perform_time',
        'payme_cancel_time',
        'click_time',
        'click_perform_time',
        'click_cancel_time'
    ];

    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Наличные',
            'terminal' => 'Терминал',
            'transfer' => 'Перечисление',
            'payme' => 'Payme',
            'click' => 'Click'
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopePaid($query)
    {
        return $query->where('paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('paid', false);
    }

    // payme

    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 43200000;

    const STATE_CREATED = 1;
    const STATE_COMPLETED = 2;
    const STATE_CANCELLED = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED = 3;
    const REASON_CANCELLED_BY_TIMEOUT = 4;
    const REASON_FUND_RETURNED = 5;
    const REASON_UNKNOWN = 10;

    public function scopeByPaymeId($query, $id)
    {
        return $query->where('payme_receipt_id', $id);
    }

    public function isPaymeExpired()
    {
        // convert into milliseconds
        $now = now()->timestamp * 1000;
        $created_at = $this->payme_time->timestamp * 1000;
        return $now - $created_at > self::TIMEOUT;
    }

    public function cancelPayme($state, $reason)
    {
        $this->payme_state = $state;
        $this->payme_cancel_reason = $reason;
        $this->payme_cancel_time = now();
        return $this->save();
    }

    public function performPayme()
    {
        $this->payme_state = self::STATE_COMPLETED;
        $this->payme_perform_time = now();
        return $this->save();
    }


    const STATUS_CREATED = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = -1;

    // click
    public function scopeByClickId($query, $id)
    {
        return $query->where('click_trans_id', $id);
    }

    public function cancelClick()
    {
        $this->click_status = self::STATUS_CANCELLED;
        $this->click_cancel_time = now();
        return $this->save();
    }

    public function performClick()
    {
        $this->click_status = self::STATUS_COMPLETED;
        $this->click_perform_time = now();
        return $this->save();
    }

}
