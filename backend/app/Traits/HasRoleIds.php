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

        if ($this->pessoa?->client) {
            $ids['client_id'] = $this->pessoa->client->id;
        }

        if ($this->pessoa?->teacher) {
            $ids['teacher_id'] = $this->pessoa->teacher->id;
            $ids['client_id']  = $this->pessoa->teacher->cliente_id;
        }

        if ($this->pessoa?->student) {
            $ids['student_id'] = $this->pessoa->student->id;
            $ids['client_id']  = $this->pessoa->student->cliente_id;
        }

        return $ids;
    }
}
