<?php

namespace Dev3bdulrahman\Barcode\Policies;

use App\Models\User;
use Dev3bdulrahman\Barcode\Models\BarcodeTemplate;

class BarcodeTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('barcode.templates.view');
    }

    public function view(User $user, BarcodeTemplate $template): bool
    {
        return $user->can('barcode.templates.view') && $template->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('barcode.templates.create');
    }

    public function update(User $user, BarcodeTemplate $template): bool
    {
        return $user->can('barcode.templates.update') && $template->company_id === $user->company_id;
    }

    public function delete(User $user, BarcodeTemplate $template): bool
    {
        return $user->can('barcode.templates.delete') && $template->company_id === $user->company_id;
    }
}
