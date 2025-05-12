<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfluencerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambio a true para permitir la creación de influencers
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048', // 2MB max
            
            // Social profile validations
            'social_username' => 'nullable|string|max:255',
            'platform_id' => 'nullable|exists:platforms,id',
            
            // Multiple social profiles
            'social_usernames.*' => 'nullable|string|max:255',
            'platform_ids.*' => 'nullable|exists:platforms,id',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre del influencer es obligatorio',
            'platform_id.exists' => 'La plataforma seleccionada no es válida',
            'platform_ids.*.exists' => 'Una de las plataformas seleccionadas no es válida',
        ];
    }
}
