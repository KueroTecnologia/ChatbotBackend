<?php


declare(strict_types=1);

namespace KED\Module\User\Services;

interface UserInterface
{
    public function getId();

    public function getEmail();

    public function getFullName();

    public function getGroupId();
}