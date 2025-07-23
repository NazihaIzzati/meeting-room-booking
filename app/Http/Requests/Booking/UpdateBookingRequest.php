<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'meeting_room_id' => ['required', 'exists:meeting_rooms,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_email' => ['required', 'email', 'max:255'],
            'pic_phone' => ['required', 'string', 'max:32'],
            'pic_staff_id' => ['required', 'string', 'max:32'],
            'meeting_title' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'meeting_room_id.required' => 'Please select a meeting room.',
            'meeting_room_id.exists' => 'The selected meeting room is invalid.',
            'date.required' => 'Meeting date is required.',
            'date.date' => 'Please enter a valid date.',
            'date.after_or_equal' => 'Meeting date must be today or a future date.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Please enter a valid start time (HH:MM format).',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'Please enter a valid end time (HH:MM format).',
            'end_time.after' => 'End time must be after start time.',
            'pic_name.required' => 'PIC name is required.',
            'pic_name.max' => 'PIC name cannot exceed 255 characters.',
            'pic_email.required' => 'PIC email is required.',
            'pic_email.email' => 'Please enter a valid PIC email address.',
            'pic_email.max' => 'PIC email cannot exceed 255 characters.',
            'pic_phone.required' => 'PIC phone number is required.',
            'pic_phone.max' => 'PIC phone number cannot exceed 32 characters.',
            'pic_staff_id.required' => 'PIC staff ID is required.',
            'pic_staff_id.max' => 'PIC staff ID cannot exceed 32 characters.',
            'meeting_title.required' => 'Meeting title is required.',
            'meeting_title.max' => 'Meeting title cannot exceed 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'meeting_room_id' => 'meeting room',
            'date' => 'meeting date',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'pic_name' => 'PIC name',
            'pic_email' => 'PIC email',
            'pic_phone' => 'PIC phone number',
            'pic_staff_id' => 'PIC staff ID',
            'meeting_title' => 'meeting title',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateBusinessHours($validator);
        });
    }

    /**
     * Validate business hours (8 AM to 6 PM)
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateBusinessHours($validator)
    {
        $startTime = $this->input('start_time');
        $endTime = $this->input('end_time');

        if ($startTime && $endTime) {
            $startHour = (int) substr($startTime, 0, 2);
            $endHour = (int) substr($endTime, 0, 2);

            if ($startHour < 8 || $startHour >= 18) {
                $validator->errors()->add('start_time', 'Start time must be between 8:00 AM and 6:00 PM.');
            }

            if ($endHour < 8 || $endHour > 18) {
                $validator->errors()->add('end_time', 'End time must be between 8:00 AM and 6:00 PM.');
            }
        }
    }
}
