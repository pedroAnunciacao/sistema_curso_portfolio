<?php

namespace App\Traits;

trait HasRoleIds
{
    public function resolveRoleIds(): array
    {
        $ids = [
            'client_id'  => null,
            'teacher_id' => null,
            'student_id' => null,
        ];

        if ($this->person?->client) {
            $ids['client_id'] = $this->person->client->id;
        }

        if ($this->person?->teacher) {
            $ids['teacher_id'] = $this->person->teacher->id;
            $ids['client_id']  = $this->person->teacher->client_id;
        }

        if ($this->person?->student) {
            $ids['student_id'] = $this->person->student->id;
            $ids['client_id']  = $this->person->student->client_id;
        }

        return $ids;
    }
}
