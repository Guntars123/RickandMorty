<?php declare(strict_types=1);

use App\Controllers\CardController;

return
    [
        ['/', [CardController::class, 'index']],
    ];
