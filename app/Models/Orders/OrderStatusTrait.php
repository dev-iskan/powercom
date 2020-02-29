<?php

namespace App\Models\Orders;

trait OrderStatusTrait
{
    public function setCreatedStatus()
    {
        return $this->order_status_id = OrderSetting::statusCreated()->id;
    }

    public function setInProgressStatus()
    {
        return $this->order_status_id = OrderSetting::statusInProgress()->id;
    }

    public function setCompletedStatus()
    {
        return $this->order_status_id = OrderSetting::statusCompleted()->id;
    }

    public function setCancelledStatus()
    {
        return $this->order_status_id = OrderSetting::statusCancelled()->id;
    }

    public function isCreated()
    {
        return $this->order_status_id == OrderSetting::statusCreated()->id;
    }

    public function isInProgress()
    {
        return $this->order_status_id == OrderSetting::statusInProgress()->id;
    }

    public function isCompleted()
    {
        return $this->order_status_id == OrderSetting::statusCompleted()->id;
    }

    public function isCancelled()
    {
        return $this->order_status_id == OrderSetting::statusCancelled()->id;
    }

    public function scopeJustCreated($query)
    {
        return $query->where('order_status_id', OrderSetting::statusCreated()->id);
    }
}
