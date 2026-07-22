<?php

namespace Dev3bdulrahman\Barcode\Policies;

use App\Models\User;
use Dev3bdulrahman\Barcode\Models\BarcodeJob;

class BarcodeJobPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('barcode.jobs.view');
    }

    public function view(User $user, BarcodeJob $job): bool
    {
        return $user->can('barcode.jobs.view') && $job->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('barcode.jobs.create');
    }

    public function update(User $user, BarcodeJob $job): bool
    {
        return $user->can('barcode.jobs.update') && $job->company_id === $user->company_id;
    }

    public function delete(User $user, BarcodeJob $job): bool
    {
        return $user->can('barcode.jobs.delete') && $job->company_id === $user->company_id;
    }
}
