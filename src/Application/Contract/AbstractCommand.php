<?php

namespace App\Application\Contract;

use Symfony\Component\Uid\Uuid;

class AbstractCommand implements CommandInterface
{
    private readonly Uuid $id;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
