<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "product_name" => ["required", "min:3", "max:100"],
            "product_description" => ["required", "min:4",],
            "product_price_capital" => ["required", "numeric"],
            "product_price_sell" => ["required", "numeric"]
        ];
    }
}
