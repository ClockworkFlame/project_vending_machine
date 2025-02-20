<?php

namespace Src\Interface;

interface Printable {
    public function print():true;
    public function __toString():string;
}