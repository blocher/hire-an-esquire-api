<?php

namespace App\Rules;


use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Rule;

class CandidateStatusRule implements Rule
{

    protected $request;
    protected $candidate;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request, Candidate $candidate=null)
    {
        $this->request = $request;
        $this->candidate = $candidate;
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
        if ($this->candidate == null) {
            return true;
        }

        if ($this->candidate->status == 'accepted' && $value == 'rejected') {
            return false;
        }

        if ($this->candidate->status == 'rejected' && $value == 'accepted') {
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
        return 'Rejected candidates cannot be changed to accepted candidates and vice versa.';
    }
}
