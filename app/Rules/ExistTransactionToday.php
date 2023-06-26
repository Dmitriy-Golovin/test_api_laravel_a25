<?php

namespace App\Rules;

use App\Models\WorkReport;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class ExistTransactionToday implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $from = new Carbon('today');
        $to = new Carbon('tomorrow');
        $workReport = WorkReport::where('created_at', '>=', $from)
            ->where('created_at', '<', $to)
            ->where('employee_id', $value)
            ->get()
            ->toArray();

        if (!empty($workReport)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'По данному сотруднику уже проведена транзакция сегодня';
    }
}
