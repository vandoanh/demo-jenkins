<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this-> fullname,
            'avatar' => image_url($this-> avatar),
            'gender' => $this-> gender,
            'birthday' => $this->birthday,
            'description' => $this->description,
            'timezone' => $this->timezone,
            'receive_notification' => (boolean) $this->receive_notification,
        ];
    }
}
