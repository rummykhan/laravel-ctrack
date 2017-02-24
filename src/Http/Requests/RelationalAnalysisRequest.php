<?php

namespace RummyKhan\Mongomies\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RelationalAnalysisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "primaryKey" => "required",
            "foreignKey" => "required",
            "primaryRelation" => "required|in:one,many",
            "foreignRelation" => "required|in:one,many",
            "primaryCollection" => "required",
            "foreignCollection" => "required"
        ];
    }
}