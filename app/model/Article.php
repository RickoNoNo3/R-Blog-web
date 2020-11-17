<?php

namespace app\model;

class Article extends Entity {
    public string $html;

    public function __construct(int $id) {
        parent::__construct(0, $id);
        $this->html = $this->readRes['html'];
    }
}