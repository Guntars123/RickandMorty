<?php declare(strict_types=1);

use App\Controllers\CardController;

return
    [
        ['/', [CardController::class, 'index']],
        ['/characters', [CardController::class, 'characters']],
        ['/filter', [CardController::class, 'filter']],
        ['/locations', [CardController::class, 'locations']],
        ['/episodes', [CardController::class, 'episodes']],
    ];
