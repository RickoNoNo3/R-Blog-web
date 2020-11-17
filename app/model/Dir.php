<?php

namespace app\model;

class Dir extends Entity {
    public array $list;

    public function __construct(int $id) {
        parent::__construct(1, $id);
        $this->list = $this->readRes['list'];
    }
}