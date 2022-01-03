<?php

namespace twent\mvccore;
use twent\mvccore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function fullName(): string;
}
