<?php

if (!function_exists('statusBadge')) {
    function statusBadge($statusCode) {
        switch ($statusCode) {
            case 0:
                return ['class' => 'bg-secondary', 'label' => 'Pending'];
            case 1:
                return ['class' => 'bg-success', 'label' => 'Approved'];
            case 2:
                return ['class' => 'bg-danger', 'label' => 'Rejected'];
            case 3:
                return ['class' => 'bg-info', 'label' => 'Under Review'];
            case 4:
                return ['class' => 'bg-primary', 'label' => 'Controlled'];
            case 5:
                return ['class' => 'bg-warning', 'label' => 'Obsolete'];
            default:
                return ['class' => 'bg-light text-dark', 'label' => 'Unknown'];
        }
    }
}