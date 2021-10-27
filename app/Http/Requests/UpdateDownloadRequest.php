<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Download;

class UpdateDownloadRequest extends FormRequest
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
//        'video_id' => 'required|string|max:191',
//        'path' => 'required|string|max:191',
//        'available_format' => 'required|string|max:191',
        'selected_format' => 'required|string|max:191',
//        'title' => 'required|string|max:191',
//        'thumbnail_path' => 'required|string|max:191'
    ];
    }
}
