<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DynamicPopupRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'                => 'required|string|max:50',
            'summary'              => 'required|string|max:200',
            'banner'               => 'required',
            'btn_link'             => ['required', 'string', 'max:191', 'not_regex:/^\s*(?:javascript|data|vbscript):/i'],
            'btn_text'             => 'required|string|max:30',
            'btn_text_color'       => 'required|in:white,dark',
            'btn_background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required'                => translate('Popup title is required'),
            'summary.required'              => translate('Popup summary is required'),
            'banner.required'               => translate('Popup image is required'),
            'btn_link.required'              => translate('Link is required.'),
            'btn_text.required'             => translate('Button Text is required'),
            'btn_text_color.required'        => translate('Button Text Color is required.'),
            'btn_text_color.in'              => translate('Please select a valid Button Text Color.'),
            'btn_background_color.required' => translate('Button Color is required'),
            'btn_background_color.regex'    => translate('Button Color must be a valid six-digit hex color.')
        ];
    }
}
